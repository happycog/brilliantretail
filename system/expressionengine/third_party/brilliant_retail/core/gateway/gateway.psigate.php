<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2014						*/
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

class Gateway_psigate extends Brilliant_retail_gateway {
	// Required variables
	public $title 	= 'PSIGate';
	public $label 	= 'Credit Card Payment (PSIGate)';
	public $descr 	= 'Accept credit cards directly from your site with a PSIGate payment gateway account.';
	public $enabled = true;
	public $version = 1.0;
		
	function process($data,$config)
	{
		$myTransactionData = $this->set_array($data,$config);
		
		$xmlRequest = "<Order>";
		
		foreach($myTransactionData as $key=>$value){
		$xmlRequest .= "<$key>$value</$key>";
		}
		
		$xmlRequest .= "</Order>";
		$xmlResponse = $this->sendTransactionToPSI($xmlRequest,$config);
		
		if($xmlResponse!=""){
		$responseFields = simplexml_load_string($xmlResponse);
		$responseArray = objectsIntoArray($responseFields);
		}
		if(trim($responseArray['Approved']) != "APPROVED"){
		$trans = array(
			'error' => 'An error occurred while processing your transaction: ' . $responseArray['ErrMsg']
			);
		}else{
			$details = array(
						"Method" => "PSIGate",
						"Approval Code" => $responseArray['CardAuthNumber'],
						"Transaction Info" => $responseArray['TransRefNumber']
					);
		
		// Return the trans details 
		$trans = array(
						'status' => 3, 
						'transaction_id' => $responseArray['OrderID'],
						'payment_type' => 'PSIGate', 
						'amount' => $responseArray["FullTotal"],
						'details' => serialize($details), 
						'approval' => $responseArray['Approved'] 
					);
		}
		return $trans;
	}
	

	
	// Create a inputs for the checkout form
	function form()
	{
		$form =  ' 	<div class="general">
	                    <label>Card Holders Name *<br />
	                    <input class="txtinp required" name="auth_cc_name" type="text" /></label>
	                </div>
		
					<div class="general">
	                    <label>Credit Card Number *<br />
	                    <input class="txtinp required creditcard" name="psigate_cc_num" type="text" /></label>
	                </div>
	                
	                <div class="expdate_month">
	                    <label>Expiration Date *<br />
	                    <select name="psigate_cc_month" class="required">
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
	                    <select name="psigate_cc_year" class="required">';
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
	function install($config_id)
	{
		$data = array();
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Store ID', 
						'code'		=> 'store_id', 
						'type' 		=> 'text',
						'value' 	=> 'teststore',
						'descr'		=> 'Enter your Store ID. Enter: \'teststore\' to test the API',
						'required' 	=> true,
						'sort' 		=> 1
						);	
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Pass Phrase', 
						'code' 		=> 'passphrase',
						'type' 		=> 'text', 
						'value' 	=> 'psigate1234',
						'descr'		=> 'Enter your supplied passphrase from PSIGate. Enter \'psigate1234\' to test the gateway', 
						'required' 	=> true,
						'sort' 		=> 2
						);
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Order Description', 
						'code' 		=> 'order_description',
						'type' 		=> 'text', 
						'value' 	=> 'Order from '.$this->EE->config->item('site_name'),							
						'descr'		=> 'Enter the description of what you would like the order description to say',
						'required' 	=> true,
						'sort' 		=> 3
						);
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Test Mode', 
						'code' 		=> 'test_mode',
						'type' 		=> 'dropdown',	
						'value'		=> 'TRUE', 
						'options'	=> 'TRUE:True|FASE:False (transactions are live)',
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

	function sendTransactionToPSI($xmlRequest,$config) {
		$url = ( $config['test_mode'] == "TRUE" ) ? 'https://dev.psigate.com:7989/Messenger/XMLMessenger' : 'https://secure.psigate.com:7934/Messenger/XMLMessenger';
		$ch = curl_init($url);
		curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $xmlRequest );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 240 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        $xmlResponse = curl_exec( $ch );

		if(curl_errno( $ch ) == CURLE_OK)
        	return $xmlResponse;
	}
	
	function set_array($data,$config)
	{
		$email = $this->EE->session->userdata["email"];
		$td = array(
						'StoreID' => $config['store_id'],
						'Passphrase' => $config['passphrase'],
						'Subtotal' => $data["order_total"],
						'PaymentType' => 'CC',
						'CardAction' => '1',
						'CardNumber' => $data["psigate_cc_num"],
						'CardExpMonth' => $data["psigate_cc_month"],
						'myComments' => "<![CDATA[".$config['order_description']."]]>",
						'CardExpYear' => $data["psigate_cc_year"]);
		return $td;
	}
}
/* End of file gateway.psigate.php */