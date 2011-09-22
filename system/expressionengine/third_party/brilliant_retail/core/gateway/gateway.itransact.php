<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter 								*/
/* 	@copyright	Copyright (c) 2011, Brilliant2.com 			*/
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

include_once('assets/itransact.php');

class Gateway_itransact extends Brilliant_retail_gateway {
		// Required variables
		public $title 	= 'iTransact';
		public $label 	= 'Credit Card Payment (iTransact)';
		public $descr 	= 'Accept credit cards directly from your site with an iTransact account.';
		public $enabled = true;
		public $version = '1.0';
		public $ipn_enabled = true;
		
		function process($data,$config){
		
		// Process only builds a process shell and sets order status = 1
			
			// Set the transaction details into 
			// a serialized array for posting to
			// the order
				$details = array(
									"Method" => "iTransact" 
								);

			// Return the trans details 
				$trans = array(
									'status' => -1, 
									'transaction_id' => $data["transaction_id"],
									'payment_card' => "",
									'payment_type' => 'iTransact', 
									'amount' => $data["order_total"],
									'details' => serialize($details), 
									'approval' => "" 
								);
			return $trans;
			
	}
	
	public function start_ipn($data,$config){
			
			$code = $this->EE->order_model->_get_gateway($data["gateway"]);
			
			$it = new iTransact();
			$it->addField('vendor_id', $config['it_vendorid']);
			
			$it->addField('home_page', $this->EE->functions->fetch_site_index(0,0));
			
			$it->addField('ret_addr', $data['notify_url']);
			
			$i = 1;
			foreach($data["cart"]["items"] as $items){
				$it->addField($i.'_desc', $items["title"]);
				$it->addField($i.'_cost', $this->_currency_round($items["price"]));
				$it->addField($i.'_qty', $items["quantity"]);
				$i++;
			}	
			
			$it->addField('acceptcards',$config['it_accept_cc']);
			$it->addField('acceptchecks',$config['it_accept_ch']);
			$it->addField('showcvv2',"1");
			$it->addField('accepteft',"1");
			
			$it->addField('passback','X|cust_id');
		
			$it->addField('X',"1");
			$it->addField('cust_id',$data['transaction_id']);			
			
			
			
			$it->addField('show_items',"1");
			
			$it->addField('first_name',$data['br_billing_fname']);
			$it->addField('last_name',$data['br_billing_lname']);
			$it->addField('address',$data['br_billing_address1'] . ' ' . $data['br_billing_address2']);
			$it->addField('city',$data['br_billing_city']);
			$it->addField('state',$data['br_billing_state']);
			$it->addField('zip',$data['br_billing_zip']);
			$it->addField('country',$data['br_billing_country']);
			$it->addField('phone',$data['br_billing_phone']);
			$it->addField('email',$data['email']);
			
			$it->addField('ret_mode',"post");
			
			$it->submitPayment();
		}
	
	// Process IPN Calls 
		public function gateway_ipn($config){
			$cancel = $this->EE->input->get('cancel',TRUE);
			if($cancel != ''){
				$this->EE->product_model->cart_update_status(session_id(),0);
				$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
				exit();
			}
			
			$str = '';
			
			foreach($_POST as $k => $v)
			{
				$str .= $k . '	 = 	  ' . $v .'           ';
			}
			
			file_put_contents(APPPATH.'cache/brilliant_retail/itransact_'.time().'.txt', $str);
			
			// Create an instance of the paypal library
			//
			//// Check validity and write down it
			//    if ($_POST['ap_returncode'] == '100')
			//    {
					$this->ipn_create_order($_POST['cust_id'],3);
			//
			//
			//    }
			//}
			$this->EE->functions->redirect($this->EE->functions->fetch_site_index(0,0));
			exit();
		}
	
	// Create a inputs for the checkout form
	function form(){
		$form =  '';
		 
		return $form;
	}
	
	// Install the gateway
		function install($config_id){
			$data = array();
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Vendor ID', 
							'code'		=> 'it_vendorid', 
							'type' 		=> 'text',
							'value'		=> '',
							'descr'		=> 'Enter your Randomly Generated UID value of your Gateway ID',
							'required' 	=> true,
							'sort' 		=> 1
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Accept Credit Cards', 
							'code' 		=> 'it_accept_cc',
							'type' 		=> 'dropdown', 
							'options' 	=> '1:True|0:False',
							'value' 	=> '1',
							'sort' 		=> 2
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Accept Checks', 
							'code' 		=> 'it_accept_ch',
							'type' 		=> 'dropdown', 
							'options' 	=> '1:True|0:False',
							'value' 	=> '1',
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
}
/* End of file gateway.itransact.php */