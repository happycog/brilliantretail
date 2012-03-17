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

class Gateway_nab_transact extends Brilliant_retail_gateway {
		// Required variables
		public $title 	= 'NAB Transact (National Australia Bank)';
		public $label 	= 'Credit Card Payment (NAB)';
		public $descr 	= 'Accept credit cards directly from your site with an NAB Transact account.';
		public $enabled = true;
		public $version = 1.0;
		
		function process($data,$config){
		
						$messageID = md5(time().rand(1000,1000000).time());
						
						if ($config['x_test_request']=="TRUE") {
						$merchantID = "XYZ0010";
						$merchantPW = "abcd1234";
						}
						else {
						$merchantID = $config['merchant_id'];
						$merchantPW = $config['password'];
						}
						
						
						$myTransactionData = $this->set_array($data,$config);
						
						$xmlRequest = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
						$xmlRequest .= "<NABTransactMessage>";
						
						$xmlRequest .= "<MessageInfo>";
						
							$xmlRequest .= "<messageID>". $messageID ."</messageID>";
							$xmlRequest .= "<messageTimestamp>". create_date() ."</messageTimestamp>";
							$xmlRequest .= "<timeoutValue>60</timeoutValue>";
							$xmlRequest .= "<apiVersion>xml-4.2</apiVersion>";
						
						$xmlRequest .= "</MessageInfo>";
						$xmlRequest .= "<MerchantInfo>";
							
							$xmlRequest .= "<merchantID>".$merchantID."</merchantID>";
							$xmlRequest .= "<password>".$merchantPW."</password>";
						
						$xmlRequest .= "</MerchantInfo>";
						
						$xmlRequest .= "<RequestType>Payment</RequestType>";
						
						$xmlRequest .= "<Payment>";
							$xmlRequest .= '<TxnList count="1">';
							$xmlRequest .= '<Txn ID="1">';
						
						foreach($myTransactionData as $key=>$value){
							$xmlRequest .= "<$key>$value</$key>";
    					}
    						$xmlRequest .= "<CreditCardInfo>";
    							$xmlRequest .= "<cardNumber>".$data['nab_cc_num']."</cardNumber>";
    							$xmlRequest .= "<expiryDate>".$data['nab_cc_month'].'/'.$data['nab_cc_year']."</expiryDate>";
    						$xmlRequest .= "</CreditCardInfo>";
       						$xmlRequest .= "</Txn>";
       						$xmlRequest .= "</TxnList>";
       					$xmlRequest .= "</Payment>";
       					$xmlRequest .= "</NABTransactMessage>";
       					
       					$xmlResponse = $this->sendTransactionToNab($xmlRequest,$config['x_test_request']);
       					
       					if($xmlResponse!=""){
							$responseFields = simplexml_load_string($xmlResponse);
							$responseArray = objectsIntoArray($responseFields);
						}
						
						
						if(trim($responseArray['Payment']['TxnList']['Txn']['responseCode']) != "00"){
							$trans = array(
								'error' => $responseArray['Payment']['TxnList']['Txn']['responseText']
								);
						}else{
	   						$details = array(
											"Method" => "NAB",
											"Approval Code" => $responseArray['Payment']['TxnList']['Txn']['responseCode'],
											"Transaction ID" => $responseArray['Payment']['TxnList']['Txn']['txnID'],
											"Transaction Ref" => $data['transaction_id'],
											"Transaction Info" => $responseArray['Payment']['TxnList']['Txn']['responseText']
										);

						// Return the trans details 
						$trans = array(
											'status' => 3, 
											'transaction_id' => $responseArray['Payment']['TxnList']['Txn']['txnID'],
											'payment_type' => 'NAB', 
											'amount' => $data["order_total"],
											'details' => serialize($details), 
											'approval' => $responseArray['Payment']['TxnList']['Txn']['responseCode'] 
										);
						}
						return $trans;
	}
	
	function sendTransactionToNab($xmlRequest,$test) {
		
		$NAB_LIVE_AU = 'https://www.eway.com.au/gateway/payment.asp';
		$NAB_TEST_AU = 'https://transact.nab.com.au/test/xmlapi/payment';
		
		if ($test=="TRUE")
		{
		$ch = curl_init($NAB_TEST_AU);
		}else {
		$ch = curl_init($NAB_LIVE_AU);
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
		                    <label>Card Holders Name *<br />
		                    <input class="txtinp required" name="nab_cc_name" type="text" /></label>
		                </div>
			
						<div class="general">
		                    <label>Credit Card Number *<br />
		                    <input class="txtinp required creditcard" name="nab_cc_num" type="text" /></label>
		                </div>
		                
		                <div class="expdate_month">
		                    <label>Expiration Date *<br />
		                    <select name="nab_cc_month" class="required">
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
		                    <select name="nab_cc_year" class="required">';
			$year = date("y");
			for($i=$year;$i<=($year+10);$i++){
				$form .= '			<option value="'.$i.'">20'.$i.'</option>';
			}
			$form .=  '   	</select></label>
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
							'code'		=> 'merchant_id', 
							'type' 		=> 'text',
							'value'		=> '',
							'descr'		=> 'Enter your Merchant ID provided by NAB Transact',
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
							'sort' 		=> 3
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Password', 
							'code' 		=> 'password',
							'type' 		=> 'text', 
							'value' 	=> '',
							'descr'		=> 'Enter your NAB Transact API Password',
							'required'	=> true,
							'sort' 		=> 2
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
						'txnType'		=> '0',
						'txnSource'		=> '23',
						'amount'		=> ($data['order_total']*100),
						'currency'	=> 	$this->_config["currency"],
						'purchaseOrderNo'	=>	$data["transaction_id"]
						);
			return $td;
		}
}
/* End of file gateway.nab_transact.php */