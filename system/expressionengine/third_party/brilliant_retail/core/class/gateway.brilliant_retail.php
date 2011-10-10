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

class Brilliant_retail_gateway extends Brilliant_retail_core{

	public $instructions 			= true;
	public $osc_enabled 			= true;
	public $subscription_enabled 	= false;
	public $cart_button 			= false;
	public $ipn_enabled 			= false;
	public $ipn_url					= '';
	public $zero_checkout 			= false;
	public $cart_total 				= 0;
	
	function __construct($total=''){
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
		public function process($data,$config){
			return true;
		}

	// Process IPN calls 
		function process_ipn(){
			return true;
		}
	
	// Create a inputs for the checkout form
		public function form(){
			return true;
		}
	
	// Create a new subscription
		public function create_subscription($item,$data,$config){
			return true;
		}

	// Update an existing subscription	
		function update_subscription(){
			return true;
		}
	
	// Cancel an existing subscription
		public function cancel_subscription(){
			return true;
		}

	// Check the status of an existing subscription	
		public function status_subscription(){
			return true;
		}
	
	// Install the gateway
		public function install($config_id){
				return true;
		}
	
	// Update the gateway
		public function update($config_id){
			return true;		
		}
		
		
	// 
		public function ipn_create_order($merchant_id,$status){
			$this->EE->load->library('extensions');
			$this->EE->load->model('order_model');

			if(!$order = $this->_ipn_validate_order($merchant_id)){
				return;
			}
			
			if($order["status_id"] == -1){

				// Process order_create_before hook
					if($this->EE->extensions->active_hook('br_order_create_before') === TRUE){
						$order = $this->EE->extensions->call('br_order_create_before', $order); 
					}

				// To 'create' this order we just need to update the status from 
				// -1 to the new status. 
				
				$order = $this->EE->order_model->get_order($order["order_id"]);		

				$note = isset($order["notes"][0]["order_note"]) ? $order["notes"][0]["order_note"] : '';
				
				$vars[0] = array(
								"fname" => $order["member"]["br_fname"],
								"lname" => $order["member"]["br_lname"],
								"email" => $order["email"], 
								"address" => $order["address"],
								"payment" => $order["payment"],
								"order_id" => $order["order_id"], 
								"order_num" => $order["order_id"], 
								"order_note" => $note,
								"delivery_method" => $order["shipment"][0]["method"], 
								"delivery_label" => $order["shipment"][0]["label"], 
								"items" => $order["items"], 
								"order_subtotal" => $this->_currency_round($order["base"]), 
								"discount_total" => $this->_currency_round($order["discount"]), 
								"tax_total" => $this->_currency_round($order["tax"]), 
								"shipping" 	=> $this->_currency_round($order["shipping"]), 
								"order_total" => $this->_currency_round($order["total"]+$order["tax"]+$order["shipping"])
							);
				
				$arr = array(
								"order_id" 	=> $order["order_id"],
								"status_id" => $status
							); 
				
				$this->EE->order_model->update_order_status($arr);
				
				// Send the notification
					$this->_send_email('customer-order-new', $vars);
				
				// Reduce the inventory
					foreach($order["items"] as $items){
						// Reduce the item inventory 
						$this->EE->order_model->reduce_item_inventory($items);
						remove_from_cache('product_'.$items["product_id"]);
					}

				// Hook after we create the order before cleanup 
					$data["order_id"] = $order["order_id"];
					if($this->EE->extensions->active_hook('br_order_create_after') === TRUE){
						$data = $this->EE->extensions->call('br_order_create_after', $data); 
					}

			}elseif($order["status_id"] == 2){
			
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

		function _ipn_validate_order($merchant_id){
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
				return false;
			}
		}
		
}