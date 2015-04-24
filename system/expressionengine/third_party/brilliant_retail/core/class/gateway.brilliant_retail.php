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

class Brilliant_retail_gateway extends Brilliant_retail_core{

	public $instructions 			= ''; 			# Description shown on the gateway settings page
	public $osc_enabled 			= TRUE;			# One step checkout enabled 
	public $ipn_enabled 			= FALSE;		# Instant Payment Notification Enabled
	public $ipn_url					= '';			# IPN Url
	public $subscription_enabled 	= FALSE;		# Subscription Enabled
	public $cart_button 			= FALSE;		# Show cart button
	public $zero_checkout 			= FALSE;		# Allow $0 checkout
	public $cart_total 				= 0;			# Cart total
	public $admin_enabled			= FALSE;		# Admin order entry enabled
	public $admin_order				= FALSE;		# Add a flag so we can differentiate admin / frontend orders

	/* 
	*	Construct  
	*/
		function __construct($total='')
		{
			parent::__construct();
	
			$this->EE =& get_instance();
	
			$this->EE->load->helper('gateway');
			
			// Set the cart totol to a public variable 
				$this->cart_total = $total;
			
			// url for ipn callback
				if(isset($_SESSION["ipn_url"])){
					$this->ipn_url = $_SESSION["ipn_url"];
				}else{
					$this->EE->db->from('actions');
					$this->EE->db->where('class','Brilliant_retail');
					$this->EE->db->where('method','gateway_ipn');
					$query = $this->EE->db->get();
					$row = $query->result_array();
					$this->ipn_url = 'http://'.rtrim($_SERVER["HTTP_HOST"],'/').'/?ACT='.$row[0]["action_id"].'&GID=';
					$_SESSION["ipn_url"] = $this->ipn_url;
				}
		}

	// Process function receives all the data from the 
	// and the configuration post and the config values
	// as defined in the settings > gateway section
		
		public function process($data,$config)
		{
			return TRUE;
		}

	// Create a inputs for the checkout form
		public function form()
		{
			return TRUE;
		}

	// Install the gateway
		public function install($config_id)
		{
			return TRUE;
		}	
	
	// Remove the gateway 
		function remove($config_id){
			return true;		
		}

	/**
	 * update function.
	 * 
	 * @access public
	 * @param mixed $config_id
	 * @return void
	 */
    	public function update($config_id)
		{
			return TRUE;		
		}
    		
/** IPN FUNCTIONS **/

	public function start_ipn($data,$config)
	{
		return TRUE;
	}
	
	public function gateway_ipn()
	{
		return TRUE;
	}
	
	// Not used yet but I have plans for it when we start adding 
	// pay from cart buttons to the system. 
	public function process_ipn()
	{
		return TRUE;
	}

	// Here we create the hidden order. 
	// David Phillip Dexter wtf were you doing? DRY! 
	// Need to refactor this so that create_order is not 
	// duplicated. Create_order should be abstracted to a 
	// internal API method. 
	
	function ipn_create_order($merchant_id,$status)
	{
		// Load the extension library so we can get add our hooks
			$this->EE->load->library('extensions');
	
		// And we need our order model	
			$this->EE->load->model('order_model');

		// Check for a valid ipn order
			if(!$order = $this->_ipn_validate_order($merchant_id)){ return; }
		
		// There is an order waiting for IPN verfication
			
			// This is a brand new order

			if($order["status_id"] == -1){
				// Process order_create_before hook
					if($this->EE->extensions->active_hook('br_order_create_before') === TRUE){
						$order = $this->EE->extensions->call('br_order_create_before', $order); 
					}

				// To 'create' this order we just need to update the status from 
				// -1 to the new status. 
				
				$order = $this->EE->order_model->get_order($order["order_id"]);		

				$note = isset($order["notes"][0]["order_note"]) ? $order["notes"][0]["order_note"] : '';
				
				$has_item 			= false;
				$has_subscription 	= false;
				$has_donation 		= false;
				
				$vars[0] = array(
								"fname" 			=> $order["member"]["br_fname"],
								"lname" 			=> $order["member"]["br_lname"],
								"email" 			=> $order["member"]["email"], 
								"address" 			=> $order["address"],
								"payment" 			=> $order["payment"],
								"order_id"			=> $order["order_id"], 
								"order_num" 		=> $order["order_id"], 
								"order_note" 		=> $note,
								"delivery_method" 	=> $order["shipment"][0]["method"], 
								"delivery_label" 	=> $order["shipment"][0]["label"], 
								"items" 			=> $order["items"], 
								"order_subtotal" 	=> $this->_currency_round($order["base"]), 
								"discount_total" 	=> $this->_currency_round($order["discount"]), 
								"tax_total" 		=> $this->_currency_round($order["tax"]), 
								"shipping" 			=> $this->_currency_round($order["shipping"]), 
								"order_total" 		=> $this->_currency_round($order["total"]+$order["tax"]+$order["shipping"]),
								"has_item"			=> $has_item, 
								"has_subscription" 	=> $has_subscription,
								"has_donation"		=> $has_donation
							);
				
				$arr = array(
								"order_id" 	=> $order["order_id"],
								"status_id" => $status
							); 
				
				$this->EE->order_model->update_order_status($arr);
				
				// Send the notification
                    if($this->EE->config->item('br_suppress_new_order_email') !== TRUE) {
                    
                    	// Hook after we create the order before cleanup
                    		if($this->EE->extensions->active_hook('br_order_email_before') === TRUE){
                    			$vars = $this->EE->extensions->call('br_order_email_before', $vars, $data);
                    		}
                    
                    	$this->_send_email('customer-order-new', $vars);
                    }
				
				// Reduce the inventory
					foreach($order["items"] as $items){
						// Reduce the item inventory 
						$this->EE->order_model->reduce_item_inventory($items);
						remove_from_cache('product_'.$items["product_id"]);
					}

				// Hook after we create the order before cleanup 
					$order["status_id"] =  $status;
					if($this->EE->extensions->active_hook('br_order_create_after') === TRUE){
						$order = $this->EE->extensions->call('br_order_create_after', $order); 
					}

			}elseif($order["status_id"] == 2){
				// This is an order moving from pending to 
				// processing

					$data = array(
									"order_id" 	=> $order["order_id"],
									"status_id" => $status
								); 

				// Hook before we update the order
					if($this->EE->extensions->active_hook('br_order_update_before') === TRUE){
						$data = $this->EE->extensions->call('br_order_update_before', $data); 
					}

					$this->EE->order_model->update_order_status($data);
									
				// Hook after we update the order
					if($this->EE->extensions->active_hook('br_order_update_after') === TRUE){
						$data = $this->EE->extensions->call('br_order_update_after', $data); 
					}
			
				$tmp = $this->EE->order_model->get_order($order["order_id"]);
				
				$eml[0]["email"] = $tmp["member"]["email"];
				$eml[0]["order_id"] = $order["order_id"];
				$eml[0]["order_num"] = $order["order_num"];
				$eml[0]["order_status"] = $this->_config["status"][$status_id];
				
				foreach($tmp["member"] as $key => $val){
					if(substr($key,0,3) == 'br_'){
						$eml[0][str_replace("br_","",$key)] = $val;
					}
				}
				$this->_send_email('customer-order-status', $eml);
			} elseif($order["status_id"] == 3){
				// This is an order moving from processing to 
				// something else

					$data = array(
									"order_id" 	=> $order["order_id"],
									"status_id" => $status
								); 

				// Hook before we update the order
					if($this->EE->extensions->active_hook('br_order_update_before') === TRUE){
						$data = $this->EE->extensions->call('br_order_update_before', $data); 
					}

					$this->EE->order_model->update_order_status($data);
									
				// Hook after we update the order
					if($this->EE->extensions->active_hook('br_order_update_after') === TRUE){
						$data = $this->EE->extensions->call('br_order_update_after', $data); 
					}
			
				$tmp = $this->EE->order_model->get_order($order["order_id"]);
				
				$eml[0]["email"] = $tmp["member"]["email"];
				$eml[0]["order_id"] = $order["order_id"];
				$eml[0]["order_num"] = $order["order_num"];
				$eml[0]["order_status"] = $this->_config["status"][$status_id];
				
				foreach($tmp["member"] as $key => $val){
					if(substr($key,0,3) == 'br_'){
						$eml[0][str_replace("br_","",$key)] = $val;
					}
				}
				$this->_send_email('customer-order-status', $eml);
			}
			$_SESSION["order_id"] = $order["order_id"];
			return;
	}

	/** HELPER FUNCTION **/

	private function _ipn_validate_order($merchant_id)
	{
		$this->EE->db->select('status_id,order_id')
					->from('br_order')
					->where('merchant_id',$merchant_id);
		$query = $this->EE->db->get();
		if($query->num_rows() > 0){
		   	$row = $query->row(); 
		   	$arr = array(
				'status_id' => $row->status_id,
				'order_id' 	=> $row->order_id  	
			);
			return $arr;
		}else{
			return FALSE;
		}
	}
}