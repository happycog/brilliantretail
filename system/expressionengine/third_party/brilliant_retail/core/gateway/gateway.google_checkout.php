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

class Gateway_google_checkout extends Brilliant_retail_gateway {
	public $title 		= 'Google Checkout';
	public $label 		= 'Google Checkout';
	public $descr 		= 'Accept payments via Google Checkout';
	public $enabled 	= true;
	public $version 	= '1.0';
	public $ipn_enabled = true;
	public $osc_enabled = true;
	public $cart_button = false;
	
	// Save the order to the database  
		public function process($data,$config)
		{
			// Process only builds a process shell and sets order status = -1
			
			// Set the transaction details into 
			// a serialized array for posting to
			// the order
				$details = array(
									"Method" => "Google Checkout"
								);

			// Return the trans details 
				$trans = array(
									'status' 			=> -1, 
									'transaction_id' 	=> $data["transaction_id"],
									'payment_card' 		=> "",
									'payment_type' 		=> 'Google Checkout',
									'amount' 			=> $data["order_total"],
									'details' 			=> serialize($details), 
									'approval' 			=> "" 
								);
			return $trans;
		}

	// Start IPN handoff to Google Checkout for payment
		public function start_ipn($data,$config)
		{
		
		}
	
	// Process IPN Calls which come back from Google 
		public function gateway_ipn($config)
		{
		}
	
	// Create a inputs for the checkout form
		public function form(){
			$form = '<img  src="https://checkout.google.com/buttons/checkout.gif?merchant_id=&w=168&h=44&style=trans&variant=text&loc=en_US" />';
			return $form;
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