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

class Gateway_brilliantretail extends Brilliant_retail_gateway {
	// Required variables
	public $title 	= 'BrilliantRetail';
	public $label 	= 'Credit Card Payment (BrilliantRetail)';
	public $instructions = 'Accept credit cards directly from your site with a BrilliantRetail powered by payleap merchant & gateway account. For more information 
						visit <b><a href="http://www.brilliantretail.com/accept-credit-cards">our website</a></b>';
	public $descr 	= 'Accept credit cards directly from your site with a BrilliantRetail powered by payleap merchant & gateway account. For more information 
						visit <b><a href="http://www.brilliantretail.com/accept-credit-cards">our website</a></b>';
	public $subscription_enabled = 1;
	public $enabled = true;
	public $version = 1.0;

	function process($data,$config)
	{

		$timestamp = strftime("%Y%m%d%H%M%S");
		$orderid = $timestamp.mt_rand(1, 999);
		$email = $this->EE->session->userdata["email"];
		
		$args = "&Username=".$config['api_username']; //Your API Username which can be located in your PayLeap merchant interface
    	$args .= "&Password=".$config['transaction_key']; //Your Transaction Key which can be located in your PayLeap merchant interface
    	$args .= "&TransType=Sale";  // Review guide for transaction types
    	$args .= "&NameOnCard=".$data['payleap_cc_name'];
    	$args .= "&CardNum=".$data['payleap_cc_num'];
    	$args .= "&ExpDate=".$data['payleap_cc_month_exp'].$data['payleap_cc_year_exp']; //MMYY Format
		$args .= "&CVNum=".$data['payleap_cc_cv2'];
    	$args .= "&Amount=".$data['order_total'];
    	$args .= "&ExtData=<TrainingMode>".substr($config['test_mode'],0,1)."</TrainingMode>
    				<Invoice>
    					<InvNum>".$orderid."</InvNum>
    					<BillTo>
    						<Name>".$data['br_billing_fname']." ".$data['br_billing_lname']."</Name>
    						<Address>
    							<Street>".$data['br_billing_address1']."</Street>
    							<City>".$data['br_billing_city']."</City>
								<State>".$data['br_billing_state']."</State>
								<Zip>".$data['br_billing_zip']."</Zip>
								<Country>".$data['br_billing_country']."</Country>
							</Address>
							<Email>".$email."</Email>
							<Phone>".$data['br_billing_phone']."</Phone>
						</BillTo>
						<Description>".$config['order_description']."</Description>
					</Invoice>";
		$args .= "&PNRef=";
    	$args .= "&MagData="; 
		if($config['test_mode']=="TRUE")
		{
			$host = "https://uat.payleap.com/TransactServices.svc/ProcessCreditCard";
		}
		else
		{
			$host = "https://secure1.payleap.com/TransactServices.svc/ProcessCreditCard";
		}
		
		$result = $this->payleap_send($args, $host);
				
		if($result!=""){
			$responseFields = simplexml_load_string($result);
			$responseArray = objectsIntoArray($responseFields);
		}
		
		if(trim($responseArray['Result']) != "0"){
			$trans = array(
				'error' => '<b>Gateway Error:</b> '.$responseArray['RespMSG']
				);
		}else{
				$details = array(
							"Method" => "PayLeap",
							"Approval Code" => $responseArray['PNRef']
						);

		// Return the trans details 
				$trans = array(
									'status' => 3, 
									'transaction_id' => $responseArray['AuthCode'],
									'payment_type' => 'PayLeap', 
									'amount' => $data["order_total"],
									'details' => serialize($details), 
									'approval' => $responseArray['Message'] 
								);
		}
		return $trans;
	}

	function payleap_send($packet, $url) {
		$header = array("MIME-Version: 1.0","Content-type: application/x-www-form-urlencoded","Contenttransfer-encoding: text"); 
		$ch = curl_init();
		 
		// set URL and other appropriate options 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_VERBOSE, 1); 
		curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); 
		// uncomment for host with proxy server
		// curl_setopt ($ch, CURLOPT_PROXY, "http://proxyaddress:port"); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $packet); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10); 
		
		// send packet and receive response
		$response = curl_exec($ch); 
		curl_close($ch); 
		return($response);
	}

	// Create a inputs for the checkout form
	function form()
	{
		$form =  ' 	<div class="general">
	                    <label>Credit Card Name *<br />
	                    <input class="txtinp required" name="payleap_cc_name" type="text" /></label>
	                </div>
	                
	                <div class="general">
	                    <label>Credit Card Number *<br />
	                    <input class="txtinp required creditcard" name="payleap_cc_num" type="text" /></label>
	                </div>
	                
	                <div class="general">
	                    <label>CV2 Number (if applicable)<br />
	                    <input class="txtinp" name="payleap_cc_cv2" type="text" /></label>
	                </div>
	                
	                <div class="expdate_month">
	                    <label>Expiration Date *<br />
	                    <select name="payleap_cc_month_exp" class="required">
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
	                    <select name="payleap_cc_year_exp" class="required">';
		$year = date("y");
		for($i=$year;$i<=($year+10);$i++){
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
						'label'	 	=> 'API Username', 
						'code'		=> 'api_username', 
						'type' 		=> 'text',
						'value'		=> '',
						'descr'		=> 'Your API Username which can be located in your PayLeap merchant interface',
						'sort' 		=> 1
						);	
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Transaction Key', 
						'code' 		=> 'transaction_key',
						'type' 		=> 'text', 
						'descr' 	=> 'Your Transaction Key which can be located in your PayLeap merchant interface',
						'sort' 		=> 2
						);
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Test Mode', 
						'code' 		=> 'test_mode',
						'type' 		=> 'dropdown', 
						'options' 	=> 'TRUE:True|FALSE:False',
						'descr'		=> 'Select Test Mode. If you select FALSE, transactions will be live', 
						'value' 	=> 'TRUE',
						'sort' 		=> 3
						);
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Order Description', 
						'code' 		=> 'order_description',
						'type' 		=> 'text', 
						'value' 	=> 'Order from '.$this->EE->config->item('site_name'),							
						'descr'		=> 'Enter the description of what you would like the order description to say',
						'required' 	=> true,
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
}
/* End of file gateway.brilliantretail.php */