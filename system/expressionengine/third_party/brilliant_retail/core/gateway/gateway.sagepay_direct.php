<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2013						*/
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

include_once('assets/sagepay/sagepay.php');

class Gateway_sagepay_direct extends Brilliant_retail_gateway {
	
	protected $sagepay;
	public $title 	= 'SagePay Direct (Beta)';
	public $label 	= 'SagePay Direct (Beta)';
	public $descr 	= 'Accept payments via SagePay\'s direct gateway service.';
	public $enabled = true;
	public $version = '1.0';
	public $ipn_enabled = true;
	public $osc_enabled = true;
	public $cart_button = false;
	public $card_types = array('VISA'=>'Visa','MC'=>'MasterCard','DELTA'=>'Visa Debit / Delta','MAESTRO'=>'Maestro','SOLO'=>'Solo','UKE'=>'Visa Electron','AMEX'=>'American Express','DC'=>'Diner\'s Club','JCB'=>'JCB','LASER'=>'Laser');

	public function process($data,$config)
	{
		$this->sagepay = new SagePay();
		
		$config['currency'] = $this->_config["currency"];
		
		$config['simulator']['purchase_url'] = "https://test.sagepay.com/Simulator/VSPDirectGateway.asp";
		$config['test']['purchase_url'] = "https://test.sagepay.com/gateway/service/vspdirect-register.vsp";
		$config['live']['purchase_url'] = "https://live.sagepay.com/gateway/service/vspdirect-register.vsp";

		$config['simulator']['callback_url'] = "https://test.sagepay.com/Simulator/VSPDirectCallback.asp";
		$config['test']['callback_url'] = "https://test.sagepay.com/gateway/service/direct3dcallback.vsp";
		$config['live']['callback_url'] = "https://live.sagepay.com/gateway/service/direct3dcallback.vsp";
		
		// Order details
		
		$order['Amount'] = $data["order_total"];
		$order['Description'] = "My Product";//!!!!


		// Add card holder name
		$order['CardHolder'] = $data["sagepay_cc_name"];//!!!!
		
		// Add card type
		$order['CardType'] = isset($data["sagepay_cc_type"]) ? $data["sagepay_cc_type"] : '';
		
		// Add card number
		$order['CardNumber'] = $data["sagepay_cc_num"];
		
		// Check if start date is supplied
		if(isset($data["sagepay_cc_smonth"]) || isset($data["sagepay_cc_syear"])){
			// If so, add start date to data array to be appended to POST
			$order['StartDate'] = $data["sagepay_cc_smonth"] . $data["sagepay_cc_syear"];
		}
		
		// Add expiry date
		$order['ExpiryDate'] = $data["sagepay_cc_month"].$data["sagepay_cc_year"];
		
		$order['CV2'] = $data["sagepay_cc_code"];
		
		//Check if issue number (UK switch and solo) is supplied
		if(isset($data["sagepay_cc_issue"])) {
			$order['IssueNumber'] = $data["sagepay_cc_issue"];
		}

		
		$order["BillingSurname"] = $data["br_billing_fname"];
		$order["BillingFirstnames"] = $data["br_billing_lname"];
		$order["BillingAddress1"] = $data["br_billing_address1"];
		$order["BillingAddress2"] = $data["br_billing_address2"];
		$order["BillingCity"] = $data["br_billing_city"];
		
		if ($data["br_billing_country"] == "US")
		{
			$order["BillingState"] = $data["br_billing_state"];
		}
		
		$order["BillingPostCode"] = $data["br_billing_zip"];
		$order["BillingCountry"] = $data["br_billing_country"];
		
		$order["DeliverySurname"] = $data["br_shipping_fname"];
		$order["DeliveryFirstnames"] = $data["br_shipping_lname"];
		$order["DeliveryAddress1"] = $data["br_shipping_address1"];
		$order["DeliveryAddress2"] = $data["br_shipping_address2"];
		$order["DeliveryCity"] = $data["br_shipping_city"];

        if ($data["br_shipping_country"] == "US")
		{
			$order["DeliveryState"] = $data["br_shipping_state"];
		}

		$order["DeliveryPostCode"] = $data["br_shipping_zip"];
		$order["DeliveryCountry"] = $data["br_shipping_country"];

		
					
		// Send data to SagePay
		$response = $this->sagepay->requestPost('payment',$order,$config);
		
		$baseStatus = array_shift(@split(" ",$response["Status"]));

		switch ($baseStatus) 
		{
			
			case '3DAUTH':

				// Process only builds a process shell and sets order status = 1
				// ** 3D Authentication is required, so show a form to redirect the browser **
				//$this->EE->load->library('session');
				//
				$this->EE->session->cache['brilliant_retail']['order_price'] = $order['Amount'];
				$this->EE->session->cache['brilliant_retail']['VendorTxCode'] = $config['vendor'];
				//
				//// Store details in session, use iFrame for 3D secure
				//
				
				$this->EE->session->cache['brilliant_retail']['ACSURL'] = $response["ACSURL"];
				$this->EE->session->cache['brilliant_retail']['PaReq'] = $response["PAReq"];
				$this->EE->session->cache['brilliant_retail']['MD'] = $response["MD"];
				
				// Set the transaction details into 
				// a serialized array for posting to
				// the order
					$details = array(
										"Method" => "SagePay Direct",
										'Transaction ID' => $data["transaction_id"] 
									);
				
				// Return the trans details 
					$trans = array(
										'status' => -1, 
										'transaction_id' => $data["transaction_id"],
										'payment_card' => "",
										'payment_type' => 'SagePay Direct', 
										'amount' => $data["order_total"],
										'details' => serialize($details), 
										'approval' => "" 
									);
				
				return $trans;			
				
				
				break; // END case '3DAUTH'
			
			
			case 'OK':
				/**************************************************************************************************
				Transaction registered successfully, now redirect the user to your success URL.
				**************************************************************************************************/
				
				$details = array(
										"Method" => "SagePay Direct",
										"Approval Code" => $response['StatusDetail']
									);

					// Return the trans details 
					$trans = array(
										'status' => 3, 
										'transaction_id' => $response['TxAuthNo'],
										'payment_type' => 'SagePay Direct', 
										'amount' => $data["order_total"],
										'details' => serialize($details), 
										'approval' => $response['Status'] 
									);
				return $trans;
				
				break; // END case 'OK'
			
			/*
			In all following cases, the status is not OK. 
			You may wish to check the status field in your database at a later date, 
			to enable you to delete orders that did not complete.
			*/
			
			case 'NOTAUTHED':
				/**************************************************************************************************
				Transaction was not authorised.
				Redirect the user to your not authorised URL.
				**************************************************************************************************/
				$trans = array(
						'error' => "Not Authorised : " . $response["StatusDetail"]
				);
				return $trans;
				
				break;
			
			case 'REJECTED':
				/**************************************************************************************************
				Transaction was rejected.
				Redirect the user to your rejected URL.
				**************************************************************************************************/
				$trans = array(
						'error' => "Rejected : " . $response["StatusDetail"]
				);
				return $trans;
				
				break;
			
			// Connection timed out
			case 'FAIL':
				/**************************************************************************************************
				Connection to sagepay could not be made (timed out)
				**************************************************************************************************/
				
				$trans = array(
						'error' => "Time out Occurred : " . $response["StatusDetail"]
				);
				return $trans;
				
				break;
			
			// There was an error of some kind
			default:
				/**************************************************************************************************
				Status was not OK, so whilst communication was successful, something was wrong with the POST
				Display information about the error on screen and update your database with this information
				**************************************************************************************************/
				
				$trans = array(
						'error' => "Error : " . $response["StatusDetail"]
				);
				
				return $trans;
				
				break;													
		}
	}
		
	public function start_ipn($data,$config)
	{
		echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js\"></script>
			 <script language=\"javascript\">
			 /* Submit form */
			 
			 $(document).ready(function(){
			 
			     $('#3dform').submit();
			 
			 });
			 
			 </script>
			     				
			 <form name=\"form\" method=\"POST\" action=\"".$this->EE->session->cache['brilliant_retail']['ACSURL']."\" target=\"3dsecure\" id=\"3dform\">
			 <input type=\"hidden\" name=\"PaReq\" value=\"".$this->EE->session->cache['brilliant_retail']['PaReq']."\" />
			 <input type=\"hidden\" name=\"TermUrl\" value=\"".$data["sagepay_return"]."\" />
			 <input type=\"hidden\" name=\"MD\" value=\"".$this->EE->session->cache['brilliant_retail']['MD']."\" />					
			 <noscript><input type=\"submit\" value=\"Continue to 3d Auth\" /></noscript>
			 </form>
			 
			 
			 <h1>3d Auth Function</h1>                   
			 <iframe width=\"100%\" height=\"500\" name=\"3dsecure\" frameborder=\"0\" style=\"margin-top: 15px;\">
			   <p>Your browser does not support iframes.</p>
			 </iframe>";
			
	}
		
	public function gateway_ipn($config)
	{
		$this->sagepay = new SagePay();
		$config['currency'] = $this->_config["currency"];
		$config['simulator']['purchase_url'] = "https://test.sagepay.com/Simulator/VSPDirectGateway.asp";
		$config['test']['purchase_url'] = "https://test.sagepay.com/gateway/service/vspdirect-register.vsp";
		$config['live']['purchase_url'] = "https://live.sagepay.com/gateway/service/vspdirect-register.vsp";
		$config['simulator']['callback_url'] = "https://test.sagepay.com/Simulator/VSPDirectCallback.asp";

		$config['test']['callback_url'] = "https://test.sagepay.com/gateway/service/direct3dcallback.vsp";
		$config['live']['callback_url'] = "https://live.sagepay.com/gateway/service/direct3dcallback.vsp";

		$data["MD"] = $this->EE->input->post("MD");
		$data["PaRes"] = $this->EE->input->post("PaRes");
		
		$response = $this->sagepay->requestPost('callback',$data,$config);

		if (($response['Status'] == "OK") || ($response['Status'] == "ATTEMPTONLY")) {
			$this->ipn_create_order($this->EE->input->get('tid',TRUE),"3");	
			
			$this->EE->functions->redirect($this->EE->functions->create_url('/_assets/iframe_redirect/'));
			
			//$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["checkout_url"].'/thankyou'));
		}
		
		if($response['Status'] != 'OK'){
			
			$_SESSION["br_alert"] = $response['Status'] . ' : ' . $response['StatusDetail'];
			
			$this->EE->product_model->cart_update_status(session_id(),0);
			$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
			exit();
		}
	}

	// Create a inputs for the checkout form
	public function form()
	{
		$form =  ' 	<div class="general">
	                    <label>Card Holders Name *<br />
	                    <input class="txtinp required" name="sagepay_cc_name" type="text" /></label>
	                </div>
	                
	                <div class="general">
	                    <label>Card Number *<br />
	                    <input class="txtinp required creditcard" name="sagepay_cc_num" type="text" /></label>
	                </div>
	                
	                <div class="expdate_month">
	                    <label>Expiration Date *<br />
	                    <select name="sagepay_cc_month" class="required">
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
	                    </select></label>
	                </div>
	                <div class="expdate_year">
	                    <label>&nbsp;<br />
	                    <select name="sagepay_cc_year" class="required">';
						$year = date("Y");
						for($i=$year;$i<=($year+15);$i++){
							$form .= '			<option value="'.substr($i,-2).'">'.$i.'</option>';
						}
		$form .=  '   	</select></label>
	                </div>
	                <div class="general">
	   				  	<select name="sagepay_cc_type" class="required">';
	   		foreach($this->card_types as $code => $name){
	   			$form .= '	<option value="'.$code.'">'.$name.'</option>';
	   		}
   		$form .= '		</select>
   					</div>';  
	        
	   if($this->_config["currency"] == 'GBP'){
	   		        
	         $form .= '<div class="start_date">
	                    <label>Start Date<br />
	                    <select name="sagepay_cc_smonth">
	                    <option value="">--</option>
	                      <option value="01">01</option>
	                      <option value="02">02</option>
	                      <option value="03">03</option>
	                      <option value="04">04</option>
	                      <option value="05">05</option>
	                      <option value="06">06</option>
	                      <option value="07">07</option>
	                      <option value="08">08</option>
	                      <option value="09">09</option>
	                      <option value="10">10</option>
	                      <option value="11">11</option>
	                      <option value="12">12</option>
	                    </select>
	                    <select name="sagepay_cc_syear">
	                    <option value="">----</option>';
						for($i=$year;$i>=($year-6);$i--){
							$form .= '			<option value="'.substr($i,-2).'">'.$i.'</option>';
						}
			$form .=  '   	</select></label></div>';
	    				            
	   		$form .= '<div class="issue_number">
	    			  	<label>Issue Number<br />
	    					<select name="sagepay_cc_issue">
	    						<option value="">--</option>
	    						<option value="01">01</option>
	                     		<option value="02">02</option>
	                      		<option value="03">03</option>
	                      		<option value="04">04</option>
	                      		<option value="05">05</option>
	                      		<option value="06">06</option>
	                      		<option value="07">07</option>
	                      		<option value="08">08</option>
	                      		<option value="09">09</option>
	                      		<option value="10">10</option>
	                      		<option value="11">11</option>
	                      		<option value="12">12</option>
	                     	</select>
	                   </label>
	                </div>';

	   	
	   	
	   	} 
	            
	      $form .=  '<div class="clearboth"><!-- --></div>
	                <div class="card_code">
	                    <label>Security Code *<br />
	                    <input class="txtinp required" name="sagepay_cc_code" type="text" /></label>
	                </div>
	                <div class="clearboth"><!-- --></div>';
		return $form;
	}
	
	// Check the status of an existing subscription	
	public function status_subscription()
	{
		return true;
	}
	
	// Install the gateway
	public function install($config_id)
	{
		$data = array();
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Vendor Id', 
						'code'		=> 'vendor', 
						'type' 		=> 'text',
						'required' 	=> true,
						'sort' 		=> 1
						);
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'System Mode', 
						'code' 		=> 'system',
						'type' 		=> 'dropdown', 
						'options' 	=> 'simulator:Simulator|test:Test|live:Live (Transactions are Live)',
						'value' 	=> 'TRUE',
						'sort' 		=> 2
						);
		
		foreach($data as $d){
			$this->EE->db->insert('br_config_data',$d);
		}				
		return true;
	}

	// Remove the gateway
	public function remove($config_id)
	{
		return true;		
	}
}
/* End of file gateway.sagepay_direct.php */