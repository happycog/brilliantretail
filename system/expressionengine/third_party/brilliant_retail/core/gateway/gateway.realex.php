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

class Gateway_realex extends Brilliant_retail_gateway {
	public $title 	= 'Realex';
	public $label 	= 'Credit Card Payment';
	public $descr 	= 'Accept credit cards directly from your site with Realex payment gateway account. Supports #d Secure for Visa & MasterCard.';
	public $enabled = true;

	public $ipn_enabled = true;
	public $osc_enabled = true;
	public $cart_button = false;

	public $version = 1.0;

	function __construct()
	{
		parent::__construct();
		$this->EE->load->library('encrypt');
	}
	
	function process($data,$config)
	{
		//Initialise arrays
			$parentElements = array();
			$TSSChecks = array();
			$currentElement = 0;
			$currentTSSCheck = "";

		// Order details for check
			$currency 	= $this->_config["currency"];
			$amount 	= ($data["order_total"]*100);
			$cardnumber = $data["realex_cc_num"];
			$cardname 	= $data["realex_cc_name"];
			$cardtype 	= $data["realex_cc_type"];
			$expdate 	= $data["realex_cc_month"].$data["realex_cc_year"];

		// These values provided by realex Payments
			$merchantid = $config['merchant_id'];
			$secret = $config['secret'];
				
		// Defines whether it's in test mode or not.
			if ($config['x_test_request']=="true"){
				$account = "internettest";
			}else{
				$account = "internet";
			}
				
		//Creates timestamp that is needed to make up orderid
			$timestamp = strftime("%Y%m%d%H%M%S");
			mt_srand((double)microtime()*1000000);

		//enter orderID into the DB, and pull from the last
			$orderid = $timestamp."-".mt_rand(1, 999);

		// Create the sha1hash that is needed
			$tmp = "$timestamp.$merchantid.$orderid.$amount.$currency.$cardnumber";
			$sha1hash = sha1($tmp);
			$tmp = "$sha1hash.$secret";
			$sha1hash = sha1($tmp);
		
		
		// If using MC or VISA then lets try to send it to 3D secure

			if($cardtype == 'MC' || $cardtype='VISA')
			{
				// Request xml that is sent to Realex Payments.
					$xml = "<request type='3ds-verifyenrolled' timestamp='$timestamp'>
								<merchantid>$merchantid</merchantid>
								<account>$account</account>
								<orderid>$orderid</orderid>
								<amount currency='$currency'>$amount</amount>
								<card> 
									<number>$cardnumber</number>
									<expdate>$expdate</expdate>
									<type>$cardtype</type> 
									<chname>$cardname</chname> 
								</card> 
								<autosettle flag='1'/>
								<sha1hash>$sha1hash</sha1hash>
								<tssinfo>
									<address type=\"billing\">
										<country>ie</country>
									</address>
								</tssinfo>
							</request>";
		        
				// Send the request array to Realex Payments
					$ch = curl_init();  
					curl_setopt($ch, CURLOPT_HEADER, 0);  
					curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
					curl_setopt($ch, CURLOPT_POST, 1); 
					curl_setopt($ch, CURLOPT_USERAGENT, "payandshop.com php version 0.9"); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // this line makes it work under https 
					$response = curl_exec ($ch);     
					curl_close ($ch); 
				
					$response = preg_replace ("/\s+/", " ", $response);
					$response = preg_replace ("/[\r\n]/", "", $response);
		
					$xmlcheck = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
					
					if($xmlcheck->result == 00)
					{
					
						$this->EE->session->cache['br_realex_payment']['url'] 			= trim((string)$xmlcheck->url);
						$this->EE->session->cache['br_realex_payment']['pareq'] 		= trim((string)$xmlcheck->pareq);
						$this->EE->session->cache['br_realex_payment']['sha1hash']		= $sha1hash;
						$this->EE->session->cache['br_realex_payment']['orderid']		= $orderid;
						$this->EE->session->cache['br_realex_payment']['timestamp']		= $timestamp;
						$this->EE->session->cache['br_realex_payment']['cardnumber']	= $cardnumber;
						$this->EE->session->cache['br_realex_payment']['cardname']		= $cardname;
						$this->EE->session->cache['br_realex_payment']['cardtype']		= $cardtype;
						$this->EE->session->cache['br_realex_payment']['currency']		= $currency;
						$this->EE->session->cache['br_realex_payment']['amount']		= $amount;
						$this->EE->session->cache['br_realex_payment']['expdate']		= $expdate;
			
								$details = array(
													"Method" => "Realex"
												);
												
							// Return the trans details 
								$trans = array(
													'status' 			=> -1, 
													'transaction_id' 	=> $orderid,
													'payment_card' 		=> '',
													'payment_type'	 	=> 'Realex', 
													'amount' 			=> $data["order_total"],
													'details' 			=> serialize($details), 
													'approval' 			=> '' 
												);
						return $trans;					
					}
			}
			
				// Request xml that is sent to Realex Payments.
					$xml = "<request type='auth' timestamp='$timestamp'>
								<merchantid>$merchantid</merchantid>
								<account>$account</account>
								<orderid>$orderid</orderid>
								<amount currency='$currency'>$amount</amount>
								<card> 
									<number>$cardnumber</number>
									<expdate>$expdate</expdate>
									<type>$cardtype</type> 
									<chname>$cardname</chname> 
								</card> 
								<autosettle flag='1'/>
								<sha1hash>$sha1hash</sha1hash>
								<tssinfo>
									<address type=\"billing\">
										<country>ie</country>
									</address>
								</tssinfo>
							</request>";
		        
				// Send the request array to Realex Payments
					$ch = curl_init();  
					curl_setopt($ch, CURLOPT_HEADER, 0);  
					curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
					curl_setopt($ch, CURLOPT_POST, 1); 
					curl_setopt($ch, CURLOPT_USERAGENT, "payandshop.com php version 0.9"); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // this line makes it work under https 
					$response = curl_exec ($ch);     
					curl_close ($ch); 
						
				//Tidy it up
				
					$response = preg_replace ("/\s+/", " ", $response);
					$response = preg_replace ("/[\r\n]/", "", $response);
		
					$xmlcheck = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
												
					/* ==/ end realex auth code /== */
			 			
					if($xmlcheck->result != 00){	
						$trans = array(
										'error' => $xmlcheck->result . " : " . $xmlcheck->message
										);			
					}else{
						$details = array(
											"Method" => "Realex",
											//"Card Type" => $resp[51],
											//"Card" => $resp[50],
											"Approval Code" => trim((string)$xmlcheck->pasref),
											"Transaction ID" => trim((string)$xmlcheck->authcode)
										);
											
						// Return the trans details 
						$trans = array(
										'status' => 3, 
										'transaction_id' => $xmlcheck->pasref,
										//'payment_card' => $resp[50],
										'payment_type' => 'Realex', 
										'amount' => $data["order_total"],
										'details' => serialize($details), 
										'approval' => $xmlcheck->authcode,
										);	
					}
				return $trans;
	}
	
	function form()
	{
		$form =  ' 	<div class="general">
	                    <label>Card Holders Name *<br />
	                    <input class="txtinp required" name="realex_cc_name" type="text" /></label>
	                </div>
					<div class="general">
	                    <label>Credit Card Number *<br />
	                    <input class="txtinp required creditcard" name="realex_cc_num" type="text" /></label>
	                </div>
	                <div class="expdate_month">
	                    <label>Expiration Date *<br />
	                    <select name="realex_cc_month" class="required">
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
	                    <select name="realex_cc_year" class="required">';
			$year = date("y");
			for($i=$year;$i<=($year+10);$i++){
				$form .= '			<option value="'.$i.'">20'.$i.'</option>';
			}
			$form .=  '	</select></label>
	                </div>
	                <div class="general">
	                <select name="realex_cc_type" class="required">
						<option value="VISA">Visa</option>
						<option value="MC">Mastercard</option>
						<option value="LASER">Laser</option>
                    </select>
	                </div>
	                <div class="general">
	                    <label>Security Code (CV2)<br />
	                    <input class="txtinp" name="realex_cc_cv2" type="text" /></label>
	                </div>
	                <div class="clearboth"><!-- --></div>';
		return $form;
	}
	
	function install($config_id)
	{
		$data = array();
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Customer ID', 
							'code'		=> 'merchant_id', 
							'type' 		=> 'text',
							'value'		=> '',
							'descr'		=> 'Enter your Merchant ID provided by Realex',
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
							'code' 		=> 'secret',
							'type' 		=> 'text', 
							'value' 	=> '',
							'descr'		=> 'Enter your Realex Secret Password',
							'required'	=> true,
							'sort' 		=> 2
							);	
							
			foreach($data as $d){
				$this->EE->db->insert('br_config_data',$d);
			}				
			return true;
	}
	
	public function start_ipn($data,$config)
	{
		$url 		= $this->EE->session->cache['br_realex_payment']['url'];
		$pareq		= $this->EE->session->cache['br_realex_payment']['pareq'];
		$sha1hash	= $this->EE->session->cache['br_realex_payment']['sha1hash'];
		$orderid	= $this->EE->session->cache['br_realex_payment']['orderid'];
		$timestamp	= $this->EE->session->cache['br_realex_payment']['timestamp'];
		$cardnumber = $this->EE->session->cache['br_realex_payment']['cardnumber'];
		$cardname	= $this->EE->session->cache['br_realex_payment']['cardname'];
		$cardtype	= $this->EE->session->cache['br_realex_payment']['cardtype'];
		$currency	= $this->EE->session->cache['br_realex_payment']['currency'];
		$amount		= $this->EE->session->cache['br_realex_payment']['amount'];
		$expdate	= $this->EE->session->cache['br_realex_payment']['expdate'];
		
		$md = $this->EE->encrypt->encode(base64_encode("orderid=".$orderid."&cardnumber=".$cardnumber."&cardname=".$cardname."&cardtype=".$cardtype."&currency=".$currency."&amount=".$amount."&expdate=".$expdate));

		echo '	<HTML> 
					<HEAD> 
						<TITLE>Realex RealMPI Redirect</TITLE> 
						<SCRIPT LANGUAGE="Javascript" > 
							<!-- 
							function OnLoadEvent() { 
							document.form.submit(); 
							} 
							//--> 
						</SCRIPT> 
					</HEAD> 
					<BODY onLoad="OnLoadEvent()"> 
						<FORM NAME="form" ACTION="'.$url.'" METHOD="POST"> 
							<INPUT TYPE="hidden" NAME="PaReq" VALUE="'.$pareq.'" /> 
							<INPUT TYPE="hidden" NAME="TermUrl" VALUE="'.$data["notify_url"].'" /> 
							<INPUT TYPE="hidden" NAME="MD" VALUE="'.$md.'" /> 
							<NOSCRIPT><INPUT TYPE="submit"></NOSCRIPT> 
						</FORM> 
					</BODY> 
				</HTML>'; 
	}
	
	public function gateway_ipn($config)
	{
		$md = base64_decode($this->EE->encrypt->decode($_POST["MD"]));	
		$arr = $this->_md_to_array($md);
		
		//Creates timestamp that is needed to make up orderid
			$timestamp = strftime("%Y%m%d%H%M%S");
			mt_srand((double)microtime()*1000000);

		// These values provided by realex Payments
			$merchantid = $config['merchant_id'];
			$secret = $config['secret'];
			
		// Create the sha1hash that is needed
			$tmp = "$timestamp.$merchantid.".$arr['orderid'].".".$arr['amount'].".".$arr['currency'].".".$arr['cardnumber'];
			$sha1hash = sha1($tmp);
			$tmp = "$sha1hash.$secret";
			$sha1hash = sha1($tmp);
			
			$xml = "<request type='3ds-verifysig' timestamp='".$timestamp."'>
						<merchantid>".$config['merchant_id']."</merchantid>
						<account />
						<orderid>".$arr["orderid"]."</orderid>
						<amount currency='".$arr['currency']."'>".$arr["amount"]."</amount>
						<card> 
							<number>".$arr["cardnumber"]."</number>
							<expdate>".$arr["expdate"]."</expdate>
							<type>".$arr["cardtype"]."</type> 
							<chname>".$arr["cardname"]."</chname> 
						</card>
						<pares>".$_POST["PaRes"]."</pares>
						<sha1hash>".$sha1hash."</sha1hash>
					</request>";
			
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_HEADER, 0);  
			curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_USERAGENT, "payandshop.com php version 0.9"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // this line makes it work under https 
			$response = curl_exec ($ch);     
			curl_close ($ch); 

			$response = preg_replace ("/\s+/", " ", $response);
			$response = preg_replace ("/[\r\n]/", "", $response);

			$xmlcheck = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
			
			if($xmlcheck->result == 00){
				$sql = "SELECT order_id from exp_br_order_payment WHERE transaction_id = '".$xmlcheck->orderid."'";
				$qry = $this->EE->db->query($sql);
				$rst = $qry->result_array();
				$order_id = $rst[0]["order_id"];

				// Set the status id
					$data["order_id"] 	= $order_id;
					$data["status_id"]	= 3;
					$this->EE->order_model->update_order_status($data);
				// Redirect to account				
					$_SESSION["order_id"] = $order_id;
					$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["thankyou_url"]));
					exit();	
			}
	}
	
	function remove($config_id)
	{
		return true;		
	}

	function _md_to_array($str)
	{
		$a = explode("&",$str);
		foreach($a as $b)
		{
			$c = explode("=",$b);
			$arr[$c[0]] = $c[1];
		}
		return $arr;
	}

}
/* End of file gateway.realex.php */