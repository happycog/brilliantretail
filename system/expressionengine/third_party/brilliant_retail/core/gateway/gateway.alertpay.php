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

include_once('assets/alertpay/alertpay.php');

class Gateway_alertpay extends Brilliant_retail_gateway {
	public $title 		= 'AlertPay IPN v2';
	public $label 		= 'AlertPay IPN v2';
	public $descr 		= 'Accept payments via AlertPay IPN v2 Gateway';
	public $enabled 	= true;
	public $version 	= 1;
	public $ipn_enabled = true;
	public $instructions = 'Accept payments via AlertPay IPN v2 Gateway';
	
		public function process($data,$config){
		
			// Process only builds a process shell and sets order status = 1
			
			// Set the transaction details into 
			// a serialized array for posting to
			// the order
				$details = array(
									"Method" => "AlertPay IPN" 
								);

			// Return the trans details 
				$trans = array(
									'status' => -1, 
									'transaction_id' => $data["transaction_id"],
									'payment_card' => "",
									'payment_type' => 'AlertPay IPN', 
									'amount' => $data["order_total"],
									'details' => serialize($details), 
									'approval' => "" 
								);
			return $trans;
		}

	// Start IPN Call
		public function start_ipn($data,$config){
			
			$ap = new AlertPay();
			$ap->addField('ap_merchant', $config['ap_merchant']);
			$ap->addField('ap_alerturl', $data["notify_url"]);
			$ap->addField('ap_test', $config['ap_test']);
			
			$ap->addField('ap_purchasetype','item');
			
			// Specify the currency
			$ap->addField('ap_currency',$this->_config["currency"]);
			
			
			// Specify the url where the user goes on success/failure
			$ap->addField('ap_returnurl',$data["return"]);
			$ap->addField('ap_cancelurl',$data["cancel_return"].'&token='.$data["transaction_id"]);
			
			$ap->addField('apc_1',$data["transaction_id"]);
			
			$i = 1;
			foreach($data["cart"]["items"] as $items){
				$ap->addField('ap_itemname_'.$i, $items["title"]);
				$ap->addField('ap_amount_'.$i, $this->_currency_round($items["price"]));
				$ap->addField('ap_itemcode_'.$i, $items["product_id"]);
				$ap->addField('ap_quantity_'.$i, $items["quantity"]);
				$i++;
			}	
			
			$ap->addField('ap_totalamount',$data["cart_total"]);
				
			// Let's start the train!
			$ap->RunPayment();
		}
	
	// Process IPN Calls 
		public function gateway_ipn($config){
			$cancel = $this->EE->input->get('cancel',TRUE);
			if($cancel != ''){
				$this->EE->product_model->cart_update_status(session_id(),0);
				$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
				exit();
			}
			
			//file_put_contents(APPPATH.'cache/brilliant_retail/alertpay_'.time().'.txt', $_POST['token']);
			
			// Create an instance of the paypal library
			$ap = new AlertPay();
			
			$return_data = $ap->callToken(urlencode($_POST['token']),$config['ap_securitycode']);
			//
			//// Check validity and write down it
			//    if ($_POST['ap_returncode'] == '100')
			//    {
					$this->ipn_create_order($return_data['transactionid'],3);
			//
			//
			//    }
			//}
			@header("HTTP/1.0 200 OK");
			@header("HTTP/1.1 200 OK");
			exit('Success');
		}
	
	// Create a inputs for the checkout form
		public function form(){
			$form = '<div class="alert_pay"><img src="https://www.alertpay.com/images/AlertPay_accepted_97x95.png" alt="AlertPay-Acceptance-1"/></div>';
			return $form;
		}
	
	// Check the status of an existing subscription	
		public function status_subscription(){
			return true;
		}
	
	// Install the gateway
		public function install($config_id){
			$data = array();
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Business E-mail', 
							'code'		=> 'dt_merchant', 
							'type' 		=> 'text',
							'required' 	=> true,
							'sort' 		=> 1
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Test Mode', 
							'code' 		=> 'dt_test',
							'type' 		=> 'dropdown', 
							'options' 	=> '1:True|0:False (Transactions are Live)',
							'value' 	=> '1',
							'sort' 		=> 3
							);	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'IPN Security Code', 
							'code'		=> 'dt_securitycode', 
							'type' 		=> 'text',
							'sort' 		=> 2
							);
			foreach($data as $d){
				$this->EE->db->insert('br_config_data',$d);
			}				
			return true;
	}

	// Remove the gateway
		public function remove($config_id){
			return true;		
		}
}