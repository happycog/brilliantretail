<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2012						*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/*															*/
/************************************************************/
/* NOTICE													*/
/*															*/
/* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF 	*/
/* ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED	*/
/* TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 		*/
/* PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT 		*/
/* SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY */
/* CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION	*/
/* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR 	*/
/* IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 		*/
/* DEALINGS IN THE SOFTWARE. 								*/	
/************************************************************/

include_once(PATH_THIRD.'brilliant_retail/core/gateway/assets/authorize/AuthorizeNet.php');

class Gateway_authorize extends Brilliant_retail_gateway {
	// Required variables
		public $title 			= 'Authorize.net';
		public $label 			= 'Credit Card Payment (Authorize.net)';
		public $descr 			= 'Accept credit cards directly from your site with an Authorize.net payment gateway account using the AIM method.';
		public $enabled 		= true;
		public $version 		= '1.0';
		public $instructions 	= 'Accept credit cards directly from your site with an Authorize.net payment gateway account using the AIM method.';
		
	// Some internal variables
		protected $nice_keys = array (
			"Response Code", "Response Subcode", "Response Reason Code", "Response Reason Text",
			"Approval Code", "AVS Result Code", "Transaction ID", "Invoice Number", "Description",
			"Amount", "Method", "Transaction Type", "Customer ID", "Cardholder First Name",
			"Cardholder Last Name", "Company", "Billing Address", "City", "State",
			"Zip", "Country", "Phone", "Fax", "Email", "Ship to First Name", "Ship to Last Name",
			"Ship to Company", "Ship to Address", "Ship to City", "Ship to State",
			"Ship to Zip", "Ship to Country", "Tax Amount", "Duty Amount", "Freight Amount",
			"Tax Exempt Flag", "PO Number", "MD5 Hash", "Card Code (CVV2/CVC2/CID) Response Code",
			"Cardholder Authentication Verification Value (CAVV) Response Code"
		);
	
		protected $code_keys = array (
			"response_code", "response_subcode", "response_reason_code", "response_reason_text",
			"approval_code", "avs_result_code", "transaction_id", "invoice_number", "description",
			"amount", "method", "transaction_type", "customer_id", "cardholder_first_name",
			"cardholder_last_name", "company", "billing_address", "city", "state",
			"zip", "country", "phone", "fax", "email", "shipto_first_name", "shipto_last_name",
			"shipto_company", "shipto_address", "shipto_city", "shipto_state",
			"shipto_zip", "shipto_country", "taamount", "duty_amount", "freight_amount",
			"taexempt_flag", "po_number", "hash", "cvv_response_code",
			"cavv_response_code"
		);
	
		protected $response = array();


	// Process function recieves all the data from the 
	// and the configuration post and the config values
	// as defined in the settings > gateway section
	
		function process($data,$config){
			
			$member_id = $this->EE->session->userdata["member_id"];
			$email = $this->EE->session->userdata["email"];
			
			$info = array(
							'login'				=> $config["login"],
							'currency_code' 	=> $this->_config["currency"],
							'tran_key'			=> $config["tran_key"],
							'test_request'		=> $config['test_request'],
							'email_customer' 	=> $config["email_customer"], 
							'version'			=> '3.1',
							'delim_data'		=> 'TRUE',
							'delim_char'		=> '|',
							'url'				=> 'FALSE',
							'type'				=> $config["type"],
							'method'			=> 'CC',
							'relay_response'	=> 'FALSE',
							'encap_char'		=> '',
							'invoice_num'		=> '', 
							'cust_id'			=> $member_id,
							'first_name'		=> $data["br_billing_fname"], 
							'last_name'			=> $data["br_billing_lname"],
							'company'			=> $data["br_billing_company"],
							'address'			=> $data["br_billing_address1"].' '.$data["br_billing_address2"],
							'city'				=> $data["br_billing_city"],
							'state'				=> $data["br_billing_state"],
							'zip'				=> $data["br_billing_zip"],
							'country'			=> $data["br_billing_country"],
							'email'				=> $config["email"],
							'phone'				=> $data["br_billing_phone"],
							'ship_to_first_name'=> $data["br_shipping_fname"],
							'ship_to_last_name'	=> $data["br_shipping_lname"],
							'ship_to_company'	=> $data["br_shipping_company"],
							'ship_to_address'	=> $data["br_shipping_address1"].' '.$data["br_shipping_address2"],
							'ship_to_city'		=> $data["br_shipping_city"],
							'ship_to_state'		=> $data["br_shipping_state"],
							'ship_to_zip'		=> $data["br_shipping_zip"],
							'ship_to_country'	=> $data["br_shipping_country"],
							'card_num'			=> $data["autho_cc_num"],
							'amount'			=> $data["order_total"],
							'description'		=> '',
							'exp_date'			=> $data["autho_cc_month"].'-'.$data["autho_cc_year"],
							'card_code'			=> $data["autho_cc_code"], 
							'tax'				=> $data["cart_tax"]);

			// Format post string
				$post_string = '';
				foreach( $info as $key => $val ){ 
					$post_string .= "$key=".urlencode($val)."&"; 
				}
				$post_string = rtrim( $post_string, "&" );
			
			// Process 
				$url = 'https://secure.authorize.net/gateway/transact.dll';
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$post_string);
				$response = urldecode(curl_exec($ch));
				
				$resp = explode("|",$response);
				if($resp[0] != 1){
					$trans = array(
										'error' => $resp[3]
									);
				}else{
					// Set the transaction details into 
					// a serialized array for posting to
					// the order
						$details = array(
											"Method" => "Authorize",
											"Card Type" => $resp[51],
											"Card" => $resp[50],
											"Approval Code" => $resp[4],
											"Transaction ID" => $resp[37]
										);

					// Return the trans details 
						$trans = array(
											'status' => 3, 
											'transaction_id' => $resp[37],
											'payment_card' => $resp[50],
											'payment_type' => 'Authorize', 
											'amount' => $data["order_total"],
											'details' => serialize($details), 
											'approval' => $resp[4],
											'subscription' => ''
										);
				}
				return $trans;
	}

	// Create a inputs for the checkout form
		function form(){
			$form =  ' 	<div class="general">
		                    <label>Credit Card Number *</label>
		                    <input class="txtinp required creditcard" name="autho_cc_num" type="text" />
		                </div>
		                <div class="expdate_month">
		                    <label>Expiration Date *</label>
		                    <select name="autho_cc_month" class="required">
		                      <option value="01">January</option>
		                      <option value="02">February</option>
		                      <option value="03">March</option>
		                      <option value="04">April</option>
		                      <option value="05">May</option>
		                      <option value="06">June</option>
		                      <option value="07">July</option>
		                      <option value="08">August</option>
		                      <option value="09">September</option>
		                      <option value="10">October</option>
		                      <option value="11">November</option>
		                      <option value="12">December</option>
		                    </select>
		                </div>
		                <div class="expdate_year">
		                    <label>&nbsp;</label>
		                    <select name="autho_cc_year" class="required">';
			$year = date("Y");
			for($i=$year;$i<=($year+10);$i++){
				$form .= '			<option value="'.$i.'">'.$i.'</option>';
			}
			$form .=  '   	</select></label>
		                </div>
		                <div class="clearboth"><!-- --></div>
		                <div class="card_code">
		                    <label>Security Code *</label>
		                    <input class="txtinp required" name="autho_cc_code" type="text" />
		                </div>
		                <div class="clearboth"><!-- --></div>';
			return $form;
	}
	
	// Install the gateway
		function install($config_id){
			$data = array();
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Login ID', 
							'code'		=> 'login', 
							'type' 		=> 'password',
							'required' 	=> true,
							'sort' 		=> 1
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Transaction Key', 
							'code'		=> 'tran_key', 
							'type' 		=> 'password',
							'required' 	=> true,
							'sort' 		=> 2
							);
	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Authorization Type', 
							'code' 		=> 'type',
							'type' 		=> 'dropdown', 
							'options' 	=> 'AUTH_ONLY:Authorization Only|AUTH_CAPTURE:Authorization & Capture',
							'value' 	=> 'AUTH_CAPTURE', 
							'sort' 		=> 3
							);	
	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Test Mode', 
							'code' 		=> 'test_request',
							'type' 		=> 'dropdown', 
							'options' 	=> 'TRUE:True|FALSE:False (Transactions are Live)',
							'descr'		=> '',
							'value' 	=> 'TRUE',
							'sort' 		=> 4
							);	
	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Email Customer<br />(Authorize.net Email)', 
							'code'		=> 'email_customer', 
							'type' 		=> 'dropdown',
							'options' 	=> 'FALSE|TRUE',
							'value'   	=> 'FALSE',
							'sort' 		=> 5
							);
							
			foreach($data as $d){
				$this->EE->db->insert('br_config_data',$d);
			}				
			return true;
	}
	
	function remove($config_id){
		return true;		
	}
	
	/* AUTHORIZE.NET HELPER FUNCTION */
	
		//function to send xml request via curl
			function _send_request_via_curl($content,$config){
				// Subscription keys 
					$host = ($config['test_request'] == true) ? "apitest.authorize.net" : "api.authorize.net";
					$path = "/xml/v1/request.api";

				$posturl = "https://" . $host . $path;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $posturl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$response = curl_exec($ch);
				return $response;
			}

		//function to parse Authorize.net response
			function _parse_return($content){
				$refId = $this->_substring_between($content,'<refId>','</refId>');
				$resultCode = $this->_substring_between($content,'<resultCode>','</resultCode>');
				$code = $this->_substring_between($content,'<code>','</code>');
				$text = $this->_substring_between($content,'<text>','</text>');
				$subscriptionId = $this->_substring_between($content,'<subscriptionId>','</subscriptionId>');
				return array ($refId, $resultCode, $code, $text, $subscriptionId);
			}
		
		//helper function for parsing response
			function _substring_between($haystack,$start,$end){
				if (strpos($haystack,$start) === false || strpos($haystack,$end) === false){
					return false;
				}else{
					$start_position = strpos($haystack,$start)+strlen($start);
					$end_position = strpos($haystack,$end);
					return substr($haystack,$start_position,$end_position-$start_position);
				}
			}
}