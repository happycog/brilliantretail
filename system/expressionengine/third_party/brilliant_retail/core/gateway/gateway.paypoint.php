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
include_once('assets/paypoint/xmlrpc.inc');

class Gateway_paypoint extends Brilliant_retail_gateway {
	// Required variables
	public $title 	= 'PayPoint';
	public $label 	= 'Credit Card Payment (PayPoint)';
	public $descr 	= 'Accept credit cards directly from your site with a PayPoint payment gateway account.';
	public $enabled = true;
	public $version = 1.0;
		
	function process($data,$config)
	{
		//Creates timestamp that is needed to make up orderid
		$timestamp = strftime("%Y%m%d%H%M%S");
		mt_srand((double)microtime()*1000000);
		
		//enter orderID into the DB, and pull from the last
		$orderid = $timestamp."-".mt_rand(1, 999);
		
		$f=new xmlrpcmsg('SECVPN.validateCardFull');
		
		$f->addParam(new xmlrpcval($config['pp_mid'], "string")) ;		// mid
		$f->addParam(new xmlrpcval($config['pp_password'], "string")) ;		// VPN password
		$f->addParam(new xmlrpcval("trans_id", "string")) ;		// trans_id
		$f->addParam(new xmlrpcval($_SERVER['REMOTE_ADDR'], "string")) ;	// ip
		$f->addParam(new xmlrpcval($data["br_billing_fname"]. ' '.$data["br_billing_lname"], "string")) ;	// Name
		$f->addParam(new xmlrpcval($data["paypoint_cc_num"], "string")) ;	// Card number
		$f->addParam(new xmlrpcval($data['order_total'], "string")) ;		// Amount
		$f->addParam(new xmlrpcval($data["paypoint_cc_exp_month"].'/'.$data["paypoint_cc_exp_year"], "string")) ;		// Expiry
		$f->addParam(new xmlrpcval($data["paypoint_cc_issue"], "string")) ;			// Issue
		$f->addParam(new xmlrpcval($data["paypoint_cc_start_month"].'/'.$data["paypoint_cc_start_year"], "string")) ;			// Start
		$f->addParam(new xmlrpcval($orderid, "string")) ;			// Order
		$f->addParam(new xmlrpcval("", "string")) ;			// Shipping Add.
		$f->addParam(new xmlrpcval("", "string")) ;			// Billing Add.
		if ($config["pp_test"]){
			$f->addParam(new xmlrpcval("test_status=true,dups=false", "string")) ;			// Options 
		}
		else {
			$f->addParam(new xmlrpcval("", "string")) ;			// Options 
		}
		
		//print "<pre>sending data ...\n" . htmlentities($f->serialize()) . "... end of send\n</pre>";
		$c=new xmlrpc_client("/secxmlrpc/make_call", "www.secpay.com", 443);
		$c->setDebug(0);
		$r=$c->send($f,20,"https");
		  
		if (!$r) { 
			$trans = array(
				'error' => "A Communication Error occurred with the Gateway Provider, please contact system administrator"
				);						
		}
		else
		{
			$v=$r->value();
		
			$responseFields = simplexml_load_string($r->serialize());
			$responseArray = objectsIntoArray($responseFields);
			$retData = $responseArray['params']['param']['value']['string'];
			
			$retData = str_replace("?","",$retData);
			parse_str($retData,$ary);
			
			if ($ary['valid']!="true")
			{
			$trans = array(
				'error' => "Error Occurred: An Error Code: ".$ary['code']." (".$ary['message'].") was raised by the gateway provider"
				);
			}
			else
			{
			$details = array(
							"Method" => "PayPoint",
							"Approval Code" => $ary['auth_code'],
							"Message" => $ary['message'],
							"Ref"	=> $orderid
						);
			// Return the trans details 
			$trans = array(
							'status' => 3, 
							'transaction_id' => $ary['auth_code'],
							'payment_type' => 'PayPoint', 
							'amount' => $data["order_total"],
							'details' => serialize($details), 
							'approval' => $ary['valid'] 
						);
			}
										
		}
		return $trans;
	}


	// Create a inputs for the checkout form
	function form()
	{
		$form =  ' 	<div class="general">
	                    <label>Card Holders Name *<br />
	                    <input class="txtinp required" name="paypoint_cc_name" type="text" /></label>
	                </div>
		
					<div class="general">
	                    <label>Credit Card Number *<br />
	                    <input class="txtinp required creditcard" name="paypoint_cc_num" type="text" /></label>
	                </div>
	                
	                <div class="expdate_month">
	                    <label>Expiration Date *<br />
	                    <select name="paypoint_cc_exp_month" class="required">
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
	                    <select name="paypoint_cc_exp_year" class="required">';
		$year = date("y");
		for($i=$year;$i<=($year+10);$i++){
			$form .= '			<option value="'.$i.'">20'.$i.'</option>';
		}
		$form .=  '   	</select></label>
	                </div>
	                <div class="expdate_month">
	                    <label>Start Date *<br />
	                    <select name="paypoint_cc_start_month" class="required">
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
	                    <select name="paypoint_cc_start_year" class="required">';
		$year = date("Y");
		for($i=$year;$i>=($year-10);$i--){
			$form .= '			<option value="'.$i.'">'.$i.'</option>';
		}
		$form .=  '   	</select></label>
	                </div>
	                <div class="general">
	                    <label>Issue Number<br />
	                    <input class="txtinp" name="paypoint_cc_issue" type="text" /></label>
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
						'label'	 	=> 'Gateway Account Name', 
						'code'		=> 'pp_mid', 
						'type' 		=> 'text',
						'value'		=> 'secpay',
						'descr'		=> 'This is your PayPoint.net gateway account name (usually six letters and two numbers)',
						'required' 	=> true,
						'sort' 		=> 1
						);	
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Test Mode', 
						'code' 		=> 'pp_test',
						'type' 		=> 'dropdown', 
						'options' 	=> 'TRUE:True|FALSE:False',
						'descr'		=> 'Select Test Mode. If you select FALSE, transactions will be live', 
						'value' 	=> 'TRUE',
						'sort' 		=> 3
						);
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'VPN Password', 
						'code' 		=> 'pp_password',
						'type' 		=> 'text', 
						'value'		=> 'secpay',
						'sort' 		=> 2
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
/* End of file gateway.paypoint.php */