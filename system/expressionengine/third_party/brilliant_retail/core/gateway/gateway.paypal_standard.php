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
include_once('assets/paypal/paypal.php');

class Gateway_paypal_standard extends Brilliant_retail_gateway {
	public $title 	= 'Paypal Standard';
	public $label 	= 'Paypal Standard';
	public $descr 	= 'Accept payments via PayPal website standard';
	public $enabled = true;
	public $version = '1.0.1';
	public $ipn_enabled = true;
	public $osc_enabled = true;
	public $cart_button = false;
	
		public function process($data,$config){
		
			// Process only builds a process shell and sets order status = 1
			
			// Set the transaction details into 
			// a serialized array for posting to
			// the order
				$details = array(
									"Method" => "PayPal Standard" 
								);

			// Return the trans details 
				$trans = array(
									'status' => -1, 
									'transaction_id' => $data["transaction_id"],
									'payment_card' => "",
									'payment_type' => 'PayPal Standard', 
									'amount' => $data["order_total"],
									'details' => serialize($details), 
									'approval' => "" 
								);
			return $trans;
		}

	// Start IPN Call
		public function start_ipn($data,$config){
			
			$myPaypal = new Paypal();
			$myPaypal->addField('cmd', '_cart');
			$myPaypal->addField('charset','utf-8');
			
			// Specify your paypal email
			$myPaypal->addField('business',$config["email"]);
			$myPaypal->addField('upload',1);
			
			if(isset($config["image_url"]) && $config["image_url"] != ''){
				$myPaypal->addField('image_url',$config["image_url"]);
			}
			if(isset($config["cpp_header_image"]) && $config["cpp_header_image"] != ''){
				$myPaypal->addField('cpp_header_image',$config["cpp_header_image"]);
			}
			// Specify the currency
			$myPaypal->addField('currency_code',$this->_config["currency"]);
			
			// Specify the url where paypal will send the user on success/failure
			
				$myPaypal->addField('return',$this->EE->functions->create_url($config["thank_you_url"]));
				$myPaypal->addField('cancel_return',$data["cancel_return"].'&token='.$data["transaction_id"]);
			
			// Specify the url where paypal will send the IPN
	
				$myPaypal->addField('notify_url',$data["notify_url"]);
			
			$i = 1;
			foreach($data["cart"]["items"] as $items){
				$myPaypal->addField('item_name_'.$i, $items["title"]);
				$myPaypal->addField('amount_'.$i, $this->_currency_round($items["price"]));
				$myPaypal->addField('item_number_'.$i, $items["product_id"]);
				$myPaypal->addField('quantity_'.$i, $items["quantity"]);
				$i++;
			}	
			
			// Add shipping address info
				#$myPaypal->addField('address_override',1);
				$myPaypal->addField('address1',$data["br_shipping_address1"]);
				$myPaypal->addField('address2',$data["br_shipping_address2"]);
				$myPaypal->addField('city',$data["br_shipping_city"]);
				$myPaypal->addField('country',$data["br_shipping_country"]);
				$myPaypal->addField('email',$data["email"]); 
				$myPaypal->addField('first_name',$data["br_shipping_fname"]);
				$myPaypal->addField('last_name',$data["br_shipping_lname"]);
				$myPaypal->addField('state',$data["br_shipping_state"]);
				$myPaypal->addField('zip',$data["br_shipping_zip"]);
				
			// Specify the shipping / discount / tax 
				$myPaypal->addField('no_shipping',2);
				$myPaypal->addField('handling_cart',$data["cart_shipping"]);
				$myPaypal->addField('discount_amount_cart',$data["cart_discount"]);
				$myPaypal->addField('tax_cart', $data["cart_tax"]);
			
			// Specify any custom value
				$myPaypal->addField('no_note',1);
				$myPaypal->addField('custom',$data["transaction_id"]); 
				
				if($config["sandbox"] == "TRUE"){
					$myPaypal->enableTestMode();
				}
			
			// Let's start the train!
				$myPaypal->submitPayment();
		}
	
	// Process IPN Calls 
		public function gateway_ipn($config){
			$cancel = $this->EE->input->get('cancel',TRUE);
			if($cancel != ''){
				$this->EE->product_model->cart_update_status(session_id(),0);
				$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
				exit();
			}

			// Create an instance of the paypal library
				$myPaypal = new Paypal();
			
			if($config["sandbox"] == "TRUE"){
				$myPaypal->enableTestMode();
			}

			# Debug file_put_contents(APPPATH.'cache/brilliant_retail/paypal_'.time().'.txt', 'SUCCESS\n\n'.json_encode($_POST));
			
			// Check validity and write down it
			if ($myPaypal->validateIpn())
			{
			    if ($myPaypal->ipnData['payment_status'] == 'Completed' || $myPaypal->ipnData['payment_status'] == 'Pending')
			    {
					$status['Pending'] = 2;
				    $status['Completed'] = 3;
				    $new_status = $status[$myPaypal->ipnData['payment_status']];
					// The ipn_create_order funtion is a core 
			    	// function that will 'create' the order 
			    	// based on the merchant_id value stored in the br_order_table. 
			    	// Function handles both creating and updating from pending to complete
			    	// just pass the merchant_id. For paypal standard it is in the custom field. 
			 	   		$this->ipn_create_order($myPaypal->ipnData['custom'],$new_status);


			    }
			}
			@header("HTTP/1.0 200 OK");
			@header("HTTP/1.1 200 OK");
			exit('Success');
		}
	
	// Create a inputs for the checkout form
		public function form(){
			$form = '<a href="#" onclick="javascript:window.open(\'https://www.paypal.com/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350\');"><img  src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" border="0" alt="Solution Graphics"></a>';
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
							'code'		=> 'email', 
							'type' 		=> 'text',
							'required' 	=> true,
							'sort' 		=> 1
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Sandbox Mode', 
							'code' 		=> 'sandbox',
							'type' 		=> 'dropdown', 
							'options' 	=> 'TRUE:True|FALSE:False (Transactions are Live)',
							'value' 	=> 'TRUE',
							'sort' 		=> 2
							);	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Thank you page', 
							'code'		=> 'thank_you_url', 
							'type' 		=> 'text',
							'descr' 	=> '(Optional) The path to your thank you page. <b>Note:</b> BrilliantRetail will 
											build the full url. Example: "thankyou" will automatically be built into '.$this->_secure_url('thankyou'),
							'sort' 		=> 3,
							'value'		=> 'thankyou' 
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Image Url', 
							'code'		=> 'image_url', 
							'type' 		=> 'text',
							'descr' 	=> '(Optional) The URL of the 150x50-pixel image displayed as your logo in the upper left corner of the PayPal checkout pages. Default Ð Your business name',
							'sort' 		=> 4
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Header Image', 
							'code'		=> 'cpp_header_image', 
							'type' 		=> 'text',
							'descr' 	=> '(Optional) The image at the top left of the checkout page. The imageÕs maximum size is 750 pixels wide by 90 pixels high. PayPal recommends that you provide an image that is stored only on a secure (https) server.',
							'sort' 		=> 5
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
		
		public function cart_button($config){
			$target = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'process_ipn').'&GID='.$config["config_id"]);
			return '<a href="'.$target.'"><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif"></a>';
		}
}