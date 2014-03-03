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

class Gateway_cdg_commerce extends Brilliant_retail_gateway {
		// Required variables
		public $title 	= 'CDG Commerce';
		public $label 	= 'Credit Card Payment (CDG)';
		public $descr 	= 'Accept credit cards directly from your site with a CDG Commerce account.';
		public $enabled = true;
		public $version = '1.0';
		
		function process($data,$config){

			$url="https://secure.quantumgateway.com/cgi/tqgwdbe.php";
			$LOGINURL = "$url";
			$agent = "BrilliantRetail Gateway Processor 1.0";
			$POSTFIELDS =
			"&gwlogin=".$config['gwlogin']. #required
			"&ccnum=".$data['cdg_ccnum']. #required
			"&ccmo=".$data['cdg_ccmo']. #required
			"&ccyr=".$data['cdg_ccyr']. #required
			"&amount=".$data['order_total']. #required
			"&BADDR1=".$data["br_billing_address1"].$data["br_billing_address2"]. #required
			"&BZIP1=".$data['br_billing_zip']. #required
			"&BCUST_EMAIL=".$data['email']. #required
			"&BNAME=".$data["br_billing_fname"]. ' '.$data["br_billing_lname"].
			"&Dsep=Pipe".
			"&CVVtype=1".
			"&CVV2=".$data['cdg_cvv2'].
			"&customer_ip=".$_SERVER['REMOTE_ADDR'].
			"&MAXMIND=1" ; 
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$LOGINURL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_USERAGENT, $agent);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS);
			curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANYSAFE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec ($ch);
			curl_close ($ch);
			
			
			$responseArray = explode("|",$result);
			
			if(trim(clear_quotes($responseArray[0])) != "APPROVED"){
				$trans = array(
					'error' => "Error Code : ". clear_quotes($responseArray[7]) . "(" . clear_quotes($responseArray[6]) . ")"
					);
			}else{
					$details = array(
								"Method" => "CDG",
								"Approval Code" => clear_quotes($responseArray[1]),
								"Transaction ID" => clear_quotes($responseArray[2]),
								"Transaction Ref" => $data['transaction_id'],
								"Transaction Info" => clear_quotes($responseArray[7])
							);

			// Return the trans details 
			$trans = array(
								'status' => 3, 
								'transaction_id' => clear_quotes($responseArray[2]),
								'payment_type' => 'CDG', 
								'amount' => $data["order_total"],
								'details' => serialize($details), 
								'approval' => clear_quotes($responseArray[1])
							);
			}
			return $trans;
	}
	
	// Create a inputs for the checkout form
		function form(){
			$form =  ' 	<div class="general">
		                    <label>Card Holders Name *<br />
		                    <input class="txtinp required" name="cdg_cc_name" type="text" /></label>
		                </div>
			
						<div class="general">
		                    <label>Credit Card Number *<br />
		                    <input class="txtinp required creditcard" name="cdg_ccnum" type="text" /></label>
		                </div>
		                
		                <div class="expdate_month">
		                    <label>Expiration Date *<br />
		                    <select name="cdg_ccmo" class="required">
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
		                    <select name="cdg_ccyr" class="required">';
			$year = date("y");
			for($i=$year;$i<=($year+10);$i++){
				$form .= '			<option value="'.$i.'">20'.$i.'</option>';
			}
			$form .=  '   	</select></label>
		                </div>
		                <div class="cvv2">
		                    <label>CVV2 *<br />
		                    <input class="txtinp required" name="cdg_cvv2" type="text" /></label>
		                </div>
		                <div class="clearboth"><!-- --></div>';
			return $form;
	}
	
	// Install the gateway
		function install($config_id){
			$data = array();
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Gateway Login', 
							'code'		=> 'gwlogin', 
							'type' 		=> 'text',
							'value'		=> '',
							'descr'		=> 'Enter your Gateway Login provided by CDG',
							'required' 	=> true,
							'sort' 		=> 1
							);	
			foreach($data as $d){
				$this->EE->db->insert('br_config_data',$d);
			}				
			return true;
	}
	
	function remove($config_id){
		return true;		
	}
}