<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
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

class Gateway_securehosting extends Brilliant_retail_gateway {
	// Required variables
	public $title 	= 'Secure Hosting';
	public $label 	= 'Credit Card Payment (SecureHosting)';
	public $descr 	= 'Accept credit cards directly from your site with a Secure Hosting payment gateway account.';
	public $enabled = true;
	public $version = 1.0;
	
	function process($data,$config)
	{

		$myTransactionData = $this->set_array($data,$config);
			
		$xmlRequest = "<?xml version=\"1.0\"?>";
		$xmlRequest .= "<request>";
		$xmlRequest .= "	<type>transaction</type>";
		$xmlRequest .= "	<authtype>authorise</authtype>";
		$xmlRequest .= "	<authentication>";
		
		$xmlRequest .= "		<shreference>".$config['sh_ref']."</shreference>";
		$xmlRequest .= "		<checkcode>".$config['sh_check']."</checkcode>";

		$xmlRequest .= "	</authentication>";
		
		$xmlRequest .= "	<transaction>";
		
		foreach($myTransactionData as $key=>$value){
			$xmlRequest .= "<$key>$value</$key>";
		}
			
			$xmlRequest .= "	</transaction>";
			$xmlRequest .= "</request>";
				
			// =debug
			$xmlResponse = $this->sendTransaction($xmlRequest,$config);
			
			if($xmlResponse!=""){
			$responseFields = simplexml_load_string($xmlResponse);
			$responseArray = objectsIntoArray($responseFields);
		}
		
		if(trim($responseArray['status']) != "OK"){
			$trans = array(
				'error' => $responseArray['reason']
				);
		}else{
				$details = array(
							"Method" => "SecureHosting",
							"Approval Code" => $responseArray['reference']
						);

		// Return the trans details 
		$trans = array(
							'status' => 3, 
							'transaction_id' => $responseArray['reference'],
							'payment_type' => 'SecureHosting', 
							'amount' => $data["order_total"],
							'details' => serialize($details), 
							'approval' => $responseArray['authtype'] 
						);
		}
		return $trans;
	}

	function sendTransaction($xmlRequest,$config) 
	{
		if ($config['x_test_request']=="TRUE")
		{
		  $ch = curl_init("https://test.secure-server-hosting.com/secutran/api.php");
		} else
		{
		  $ch = curl_init("https://www.secure-server-hosting.com/secutran/api.php");
		}
		        curl_setopt($ch, CURLOPT_POST, 1);
	            curl_setopt($ch, CURLOPT_POSTFIELDS, "xmldoc=" . $xmlRequest);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    
	    $xmlResponse = curl_exec($ch);
	
	    if(curl_errno( $ch ) == CURLE_OK)
	    	return $xmlResponse;
	}

	// Create a inputs for the checkout form
	function form()
	{
		$form =  ' 	<div class="general">
	                    <label>Credit Card Number *<br />
	                    <input class="txtinp required creditcard" name="securehost_cc_num" type="text" /></label>
	                </div>
	                
	                <div class="general">
	                    <label>CV2 Number (if applicable)<br />
	                    <input class="txtinp" name="securehost_cc_cv2" type="text" /></label>
	                </div>
	                
	                <div class="general">
	                    <label>Switch Issue Number (if applicable)<br />
	                    <input class="txtinp" name="securehost_cc_issue" type="text" /></label>
	                </div>
	                
	                <div class="expdate_month">
	                    <label>Expiration Date *<br />
	                    <select name="securehost_cc_month_exp" class="required">
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
	                    <select name="securehost_cc_year_exp" class="required">';
		$year = date("y");
		for($i=$year;$i<=($year+10);$i++){
			$i = str_pad($i, 2, 0, STR_PAD_LEFT);
			$form .= '			<option value="'.$i.'">'.$i.'</option>';
		}
		$form .=  '   	</select></label>
	                </div>
	                <div class="expdate_month">
	                    <label>Start From Date *<br />
	                    <select name="securehost_cc_month_start" class="required">
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
	                    <select name="securehost_cc_year_start" class="required">';
		$year = date("y");
		for($i=$year;$i>=($year-10);$i--){
			$i = str_pad($i, 2, 0, STR_PAD_LEFT);
			$form .= '			<option value="'.$i.'">'.$i.'</option>';
		}
		$form .=  '   	</select></label>
	                </div>
	                <div class="clearboth"><!-- --></div>';
		return $form;
	}

	// Install the gateway
	function install($config_id)
	{
		$data = array();
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'SH Reference', 
						'code'		=> 'sh_ref', 
						'type' 		=> 'text',
						'value'		=> '',
						'descr'		=> 'Personal client reference number',
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
						'label'	 	=> 'Check Code', 
						'code' 		=> 'sh_check',
						'type' 		=> 'text', 
						'descr' 	=> 'Second level security check code',
						'sort' 		=> 3
						);	

		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Account Currency', 
						'code' 		=> 'acc_curr',
						'type' 		=> 'text', 
						'descr' 	=> 'Account Currency must match SecureHosting Transaction Settings (GBP/EUR/AUD etc..)',
						'sort' 		=> 4
						);	
						
		foreach($data as $d){
			$this->EE->db->insert('br_config_data',$d);
		}				
		return true;
	}

	function remove($config_id)
	{
		return true;		
	}

	function set_array($data,$config)
	{
		$email = $this->EE->session->userdata["email"];
		$td = array(
					'cardnumber'		=> $data['securehost_cc_num'],
					'cardstartmonth'	=> $data['securehost_cc_month_start'],
					'cardstartyear'		=> $data['securehost_cc_year_start'],
					'cardexpiremonth'	=> $data['securehost_cc_month_exp'],
					'cardexpireyear' 	=> $data['securehost_cc_year_exp'], 
					'cv2'				=> $data["securehost_cc_cv2"], 
					'switchnumber'		=> $data["securehost_cc_issue"],
					'cardholdersname'	=> $data["br_billing_fname"].' '.$data["br_billing_lname"],
					'cardholderaddr1'	=> $data["br_billing_address1"] . ' ' . $data['br_billing_address2'],
					'cardholdercity'	=> $data["br_billing_city"],
					'cardholderstate'	=> $data["br_billing_state"],
					'cardholderpostcode'	=> $data["br_billing_zip"],
					'currency'			=> $config['acc_curr'], 
					'transactionamount' => $data["order_total"],
					'transactiontax' 	=> $data["cart_tax"],
					'shippingcharge'	=> $data["cart_shipping"]);
		return $td;
	}
}
/* End of file gateway.securehosting.php */