<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
/* 	@license	http://opensource.org/licenses/OSL-3.0	*/
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
include_once('assets/paypal/phpPayPal.php');

class Gateway_paypal_pro extends Brilliant_retail_gateway {
	public $title 	= 'Paypal Pro';
	public $label 	= 'Credit Card Payment (PayPal)';
	public $descr 	= 'Accept credit cards directly from your site with an PayPal Pro payment gateway account.';
	public $enabled = true;
	public $version = .5;
	
	// Process function receives all the data from the 
	// and the configuration post and the config values
	// as defined in the settings > gateway section
		public function process($data,$config){
			// Pass the store currency to the gateway
				$config["currency_code"] = $this->_config["currency"];
	
			// Create instance of the phpPayPal class
				$paypal = new phpPayPal($config);
				// (required)
					$paypal->ip_address = $_SERVER['REMOTE_ADDR'];
				 
				// Order Totals (amount_total is required)
					$paypal->amount_total = $data["order_total"];
					$paypal->amount_tax = $data["cart_tax"];
					$paypal->amount_shipping = $data["cart_shipping"];
				 
				// Credit Card Information (required)
					$paypal->credit_card_number = $data["autho_cc_num"];
					#$paypal->credit_card_type = 'Visa';
					$paypal->cvv2_code =  $data["autho_cc_code"];
					$paypal->expire_date = $data["autho_cc_month"].$data["autho_cc_year"];
				 
				// Billing Details (required)
					$paypal->first_name = $data["br_billing_fname"];
					$paypal->last_name 	= $data["br_billing_lname"];
					$paypal->address1 	= $data["br_billing_address1"];
					$paypal->address2 	= $data["br_billing_address2"];
					$paypal->city 		= $data["br_billing_city"];
					$paypal->state 		= $data["br_billing_state"];
					$paypal->postal_code 	= $data["br_billing_zip"];
					$paypal->phone_number 	= $data["br_billing_phone"];
					$paypal->country_code 	= $data["br_billing_country"];
				 
				// Shipping Details (NOT required)
					#$paypal->email = 'johndoe@example.com';
					$paypal->shipping_name = $data["br_shipping_fname"].' '.$data["br_shipping_lname"];
					$paypal->shipping_address1 = $data["br_shipping_address1"];
					$paypal->shipping_address2 = $data["br_shipping_address2"];
					$paypal->shipping_city = $data["br_shipping_city"];
					$paypal->shipping_state = $data["br_shipping_state"];
					$paypal->shipping_postal_code = $data["br_shipping_zip"];
					$paypal->shipping_country_code = $data["br_shipping_country"];
				 
				// Add Order Items (NOT required) - Name, Number, Qty, Tax, Amt
				// Repeat for each item needing to be added
				$total = 0;
				foreach($data["cart"]["items"] as $item){
					$p = number_format($item["price"],2);
					$paypal->addItem($item["title"],$item["product_id"],$item["quantity"],0,$p);
					$total += $item["quantity"]*$p;
				}
				
				if($data["cart_discount"] != 0){
					$d = number_format(($data["cart_discount"] * -1),2);
					$paypal->addItem('Discount','Discount',1,0,$d);
					$total += $d;
				}			
				// Perform the payment
				$paypal->do_direct_payment();
				
				$resp = $paypal->Response;
				
				// Get the card type from the number
					$card_type = cc_type_number($data["autho_cc_num"]);
				
				if(strtoupper($resp["ACK"]) != "SUCCESS" && strtoupper($resp["ACK"]) != "SUCCESSWITHWARNING"){
					$trans = array(
										'error' => $resp["L_LONGMESSAGE0"] 
									);
				}else{
				
					//Check for subscriptions 
						$subscription = array();
						foreach($data["cart"]["items"] as $item){
							if($item["type_id"] == 6){
								$subResp = $this->create_subscription($item,$data,$config);
								$subArr = $this->_parse_return($subResp);
								if($subArr[1] == 'Ok'){
									$subscription[] = array_merge($item,$subArr);
								}else{
									#debug print_r($arr);
								}
							}
						}
					// Set the transaction details into 
					// a serialized array for posting to
					// the order
						$details = array(
											"Method" => "PayPal Pro",
											"Card Type" => $card_type,
											"Card" => 'XXXX'.substr($data["autho_cc_num"],-4,4),
											"Approval Code" => $resp["CORRELATIONID"],
											"Transaction ID" => $resp["TRANSACTIONID"] 
										);

					// Return the trans details 
						$trans = array(
											'status' => 3, 
											'transaction_id' => $resp["TRANSACTIONID"],
											'payment_card' => 'XXXX'.substr($data["autho_cc_num"],-4,4),
											'payment_type' => 'Authorize', 
											'amount' => $data["order_total"],
											'details' => serialize($details), 
											'approval' => $resp["CORRELATIONID"],
											'subscription' => $subscription  
										);
				}
				return $trans;
		}

	// Create a inputs for the checkout form
		public function form(){
			$form =  ' 	<div class="general">
		                    <label>Credit Card Number *<br />
		                    <input class="txtinp required creditcard" name="autho_cc_num" type="text" /></label>
		                </div>
		                
		                <div class="expdate_month">
		                    <label>Expiration Date *<br />
		                    <select name="autho_cc_month" class="required">
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
		                    <select name="autho_cc_year" class="required">';
			$year = date("Y");
			for($i=$year;$i<=($year+10);$i++){
				$form .= '			<option value="'.$i.'">'.$i.'</option>';
			}
			$form .=  '   	</select></label>
		                </div>
		                <div class="clearboth"><!-- --></div>
		                <div class="card_code">
		                    <label>Security Code *<br />
		                    <input class="txtinp required" name="autho_cc_code" type="text" /></label>
		                </div>
		                <div class="clearboth"><!-- --></div>';
			return $form;
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
			$data = array();
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'API Username', 
							'code'		=> 'username', 
							'type' 		=> 'text',
							'required' 	=> true,
							'sort' 		=> 1
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'API Password', 
							'code'		=> 'password', 
							'type' 		=> 'text',
							'required' 	=> true,
							'sort' 		=> 2
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'API Signature', 
							'code'		=> 'signature', 
							'type' 		=> 'text',
							'required' 	=> true,
							'sort' 		=> 2
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Sandbox Mode', 
							'code' 		=> 'sandbox',
							'type' 		=> 'dropdown', 
							'options' 	=> 'TRUE:True|FALSE:False (Transactions are Live)',
							'value' 	=> 'TRUE',
							'sort' 		=> 4
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
	
	
	
} // End Class 
/* End of file gateway.paypal_pro.php */