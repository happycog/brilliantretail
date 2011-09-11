<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010-2011, Brilliant2.com 	*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.0 Beta							*/
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

class Gateway_authorize extends Brilliant_retail_gateway {
	// Required variables
		public $title 	= 'Authorize.net';
		public $label 	= 'Credit Card Payment (Authorize.net)';
		public $descr 	= 'Accept credit cards directly from your site with an Authorize.net payment gateway account using the AIM method.';
		public $enabled = true;
		public $version = '1.0';
		
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
			"shipto_zip", "shipto_country", "tax_amount", "duty_amount", "freight_amount",
			"tax_exempt_flag", "po_number", "hash", "cvv_response_code",
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
							'x_login'			=> $config["x_login"],
							'x_currency_code' 	=> $this->_config["currency"],
							'x_tran_key'		=> $config["x_tran_key"],
							'x_test_request'	=> $config['x_test_request'],
							'x_email_customer' 	=> $config["x_email_customer"], 
							'x_version'			=> '3.1',
							'x_delim_data'		=> 'TRUE',
							'x_delim_char'		=> '|',
							'x_url'				=> 'FALSE',
							'x_type'			=> $config["x_type"],
							'x_method'			=> 'CC',
							'x_relay_response'	=> 'FALSE',
							'x_encap_char'		=> '',
							'x_invoice_num'		=> '', 
							'x_cust_id'			=> $member_id,
							'x_first_name'		=> $data["br_billing_fname"], 
							'x_last_name'		=> $data["br_billing_lname"],
							'x_company'			=> $data["br_billing_company"],
							'x_address'			=> $data["br_billing_address1"].' '.$data["br_billing_address2"],
							'x_city'			=> $data["br_billing_city"],
							'x_state'			=> $data["br_billing_state"],
							'x_zip'				=> $data["br_billing_zip"],
							'x_country'			=> $data["br_billing_country"],
							'x_email'			=> $email,
							'x_phone'			=> $data["br_billing_phone"],
							'x_ship_to_first_name'	=> $data["br_shipping_fname"],
							'x_ship_to_last_name'	=> $data["br_shipping_lname"],
							'x_ship_to_company'	=> $data["br_shipping_company"],
							'x_ship_to_address'	=> $data["br_shipping_address1"].' '.$data["br_shipping_address2"],
							'x_ship_to_city'	=> $data["br_shipping_city"],
							'x_ship_to_state'	=> $data["br_shipping_state"],
							'x_ship_to_zip'		=> $data["br_shipping_zip"],
							'x_ship_to_country'	=> $data["br_shipping_country"],
							'x_card_num'		=> $data["autho_cc_num"],
							'x_amount'			=> $data["order_total"],
							'x_description'		=> '',
							'x_exp_date'		=> $data["autho_cc_month"].'-'.$data["autho_cc_year"],
							'x_card_code'		=> $data["autho_cc_code"], 
							'x_tax'				=> $data["cart_tax"]);

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
				
					//Check for subscriptions 
						$subscription = array();
						foreach($data["cart"]["items"] as $item){
							if($item["type_id"] == 6){
								$subResp = $this->create_subscription($item,$data,$config);
								$subArr = $this->_parse_return($subResp);
								if($subArr[1] == 'Ok'){
									$subscription[] = array_merge($item,$subArr);
								}else{
									#debug print_r($arr);
								}
							}
						}
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
											'subscription' => $subscription  
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
							'code'		=> 'x_login', 
							'type' 		=> 'password',
							'required' 	=> true,
							'sort' 		=> 1
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Transaction Key', 
							'code'		=> 'x_tran_key', 
							'type' 		=> 'password',
							'required' 	=> true,
							'sort' 		=> 2
							);
	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Authorization Type', 
							'code' 		=> 'x_type',
							'type' 		=> 'dropdown', 
							'options' 	=> 'AUTH_ONLY:Authorization Only|AUTH_CAPTURE:Authorization & Capture',
							'value' 	=> 'AUTH_CAPTURE', 
							'sort' 		=> 3
							);	
	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Test Mode', 
							'code' 		=> 'x_test_request',
							'type' 		=> 'dropdown', 
							'options' 	=> 'TRUE:True|FALSE:False (Transactions are Live)',
							'descr'		=> 'Test mode requires an authorize.net developer account', 
							'value' 	=> 'TRUE',
							'sort' 		=> 4
							);	
	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Email Customer<br />(Authorize.net Email)', 
							'code'		=> 'x_email_customer', 
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
					$host = ($config['x_test_request'] == true) ? "apitest.authorize.net" : "api.authorize.net";
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