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

class Gateway_eway_au extends Brilliant_retail_gateway {
		// Required variables
		public $title 	= 'eWay Australlia';
		public $label 	= 'Credit Card Payment (eWay)';
		public $descr 	= 'Accept credit cards directly from your site with an eWay payment gateway account.';
		public $enabled = true;
		public $version = 1.0;
		
		function process($data,$config){ 
		
						$myTransactionData = $this->set_array($data,$config);
							
						$xmlRequest = "<ewaygateway>";
						
						foreach($myTransactionData as $key=>$value){
							$xmlRequest .= "<$key>$value</$key>";
    					}
       					
       					$xmlRequest .= "</ewaygateway>";
						
       					$xmlResponse = $this->sendTransactionToEway($xmlRequest,$config['x_test_request']);

						if($xmlResponse!=""){
							$responseFields 	= simplexml_load_string($xmlResponse);
							$responseArray 		= objectsIntoArray($responseFields);
						}

						if(trim($responseArray['ewayTrxnStatus']) != "True"){
							$trans = array(
												'error' => $responseArray['ewayTrxnError']
											);
						}else{
	   						$details = array(
											"Method" => "eWay",
											"Approval Code" => $responseArray['ewayAuthCode'],
											"Transaction ID" => $responseArray['ewayTrxnNumber'],
											"Transaction Info" => $responseArray['ewayTrxnError']
										);

							// Return the trans details 
								$trans = array(
													'status' => 3, 
													'transaction_id' => $responseArray['ewayTrxnNumber'],
													'payment_type' => 'eWay', 
													'amount' => $data["order_total"],
													'details' => serialize($details), 
													'approval' => $responseArray['ewayTrxnStatus'] 
												);
						}
						return $trans;
	}
	
	function sendTransactionToEway($xmlRequest,$test) {
		
		$EWAY_LIVE_AU = 'https://www.eway.com.au/gateway/xmlpayment.asp';
		$EWAY_TEST_AU = 'https://www.eway.com.au/gateway/xmltest/testpage.asp';
		
		if ($test=="TRUE")
		{
		$ch = curl_init($EWAY_TEST_AU);
		}else {
		$ch = curl_init($EWAY_LIVE_AU);
		}

			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
	        $xmlResponse = curl_exec($ch);
	        
        if(curl_errno( $ch ) == CURLE_OK)
        	return $xmlResponse;
	}
	
	// Create a inputs for the checkout form
		function form(){
			$form =  ' 	<div class="general">
		                    <label>Card Holders Name *</label>
		                    <input class="txtinp required" name="ewayau_cc_name" type="text" />
		                </div>
			
						<div class="general">
		                    <label>Credit Card Number *</label>
		                    <input class="txtinp required creditcard" name="ewayau_cc_num" type="text" />
		                </div>
		                
		                <div class="expdate_month">
		                    <label>Expiration Date *</label>
		                    <select name="ewayau_cc_month" class="required">
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
		                    <select name="ewayau_cc_year" class="required">';
			$year = date("Y");
			for($i=$year;$i<=($year+10);$i++){
				$form .= '			<option value="'.$i.'">'.$i.'</option>';
			}
			$form .=  '   	</select>
		                </div>

						<div class="general">
		                	<label>CVN Number</label>
		                	<input class="txtinp required" name="ewayau_cvn" />
		                </div>

		                <div class="clearboth"><!-- --></div>';

			return $form;
	}
	
	// Install the gateway
		function install($config_id){
			$data = array();
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Customer ID', 
							'code'		=> 'cmr_id', 
							'type' 		=> 'text',
							'value'		=> '87654321',
							'descr'		=> 'Enter your Customer ID. Enter: 87654321 to test the API',
							'required' 	=> true,
							'sort' 		=> 1
							);	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Test Mode', 
							'code' 		=> 'x_test_request',
							'type' 		=> 'dropdown', 
							'options' 	=> 'TRUE:True|FALSE:False',
							'descr'		=> 'Select Test Mode. If you select FALSE, transactions will be live', 
							'value' 	=> 'TRUE',
							'sort' 		=> 2
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Invoice Desscription', 
							'code' 		=> 'invoic_descr',
							'type' 		=> 'text', 
							'value' 	=> 'Store Purchase from '.$this->EE->config->item('site_name'),
							'sort' 		=> 3
							);	
							
			foreach($data as $d){
				$this->EE->db->insert('br_config_data',$d);
			}				
			return true;
	}
	
	function remove($config_id){
		return true;		
	}
	
	function set_array($data,$config){
			$email = $this->EE->session->userdata["email"];
			$td = array(
						'ewayOption1'						=> '',
						'ewayOption2'						=> '',
						'ewayOption3'						=> '',
						'ewayCustomerID'					=> $config["cmr_id"],
						'ewayCustomerEmail' 				=> $data["email"], 
						'ewayCustomerFirstName'				=> $data["br_billing_fname"], 
						'ewayCustomerLastName'				=> $data["br_billing_lname"],
						'ewayCustomerAddress'				=> $data["br_billing_address1"].' '.$data["br_billing_address2"],
						'ewayCustomerPostcode'				=> $data["br_billing_zip"],
						'ewayCardNumber'					=> $data["ewayau_cc_num"],
						'ewayCardHoldersName'				=> $data["ewayau_cc_name"],
						'ewayTotalAmount'					=> (100 * $data["order_total"]),
						'ewayCustomerInvoiceDescription' 	=> $config["invoic_descr"], 
						'ewayCustomerInvoiceRef' 			=> '',
						'ewayTrxnNumber' 					=> '',
						'ewayCardExpiryMonth'				=> $data["ewayau_cc_month"],
						'ewayCardExpiryYear' 				=> $data["ewayau_cc_year"],
						'ewayCVN'							=> $data["ewayau_cvn"]
						);
			return $td;
		}
}