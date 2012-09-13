<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

require_once('assets/stripe/Stripe.php');

class Gateway_stripe extends Brilliant_retail_gateway {

	// Required variables
	public $title 			= 'Stripe';
	public $label 			= 'Credit Card Payment (Stripe)';
	public $descr 			= 'Accept credit cards securely with a <a href="https://stripe.com/">Stripe</a> account.';
	public $instructions 	= 'Accept credit cards securely with a <a href="https://stripe.com/">Stripe</a> account.';
	public $zero_checkout = true;
	
	public $enabled = TRUE;
	public $version = 2.0;

	protected $table = 'br_stripe_customers';
	protected $allow_address_updates = FALSE;
	
	/**
	 * Process Payment
	 *
	 * Handles the meat of the transaction and error handling
	 *
	 * @access	public
	 */
	function process($data, $config)
	{
		// Email Address
		$email = $this->EE->session->userdata('email');

		if (isset($data['email']))
		{
			$email = $data['email'];
		}
		
		// Get the right key
		$key = ($config["test_mode"] == 'TRUE') ? $config["test_api_key"] : $config["api_key"];

		Stripe::setApiKey($key);

		try
		{
			if (isset($data['stripeCustomer']))
			{
				$customer_id = $data['stripeCustomer'];

				// crosscheck that it's actually them
				if ( ! $this->_check_owns_card($customer_id,$data["member_id"]))
				{
					throw new Exception('Invalid Card');
				}

				// See note on _update_customer_address()
				if ($this->allow_address_updates && $this->EE->input->post('stripe-update-address') == 'y')
				{
					$this->_update_customer_address($customer_id, $data);
				}
			}
			else if (isset($data['stripeToken']))
			{
				$cu = $this->_save_stripe_card($data);
				$customer_id = $cu->id;

				// security checks
				// options: pass, fail, unchecked

				if ($cu->cvc_check == 'fail')
				{
					throw new Exception('Invalid Security Code');
				}

				if ($cu->address_line1_check == 'fail')
				{
					throw new Exception('Invalid Address');
				}

				if ($cu->address_zip_check == 'fail')
				{
					throw new Exception('Invalid ZIP Code');
				}
 
			}
			else
			{
				throw new Exception('Could not charge card');
			}

			// Create the charge
			// We only create a charge if there is an order total. 
				if($data["order_total"] > 0){
					$result = Stripe_Charge::create(array(
						"amount" 	=> (100 * $data["order_total"]), // stripe charges in cents
						"currency" 	=> strtolower($this->_config["currency"]),
						"customer" 	=> $customer_id,
						"description" => "Charge for ".$email
					));
				}else{
					$result = json_encode(
											array(
													"id" => "",
													"card"	=> array(
																		"type" 		=> $cu->active_card->type,
																		"last4"		=> $cu->active_card->last4, 
																		"exp_year"	=> $cu->active_card->exp_year,
																		"cvc_check"	=> $cu->active_card->cvc_check
																	)	
													)
										);
				}
		}
		catch (Exception $e)
		{
			return array(
				'error' => $e->getMessage()
			);
		}
		
		$res = json_decode($result);
		$details = array(
			"Method" 		=> "Stripe",
			"id" 			=> $res->id, 
			"Card Type" 	=> $res->card->type,
			"C.C. Num"		=> 'XXXX'.$res->card->last4, 
			"Exp Year"		=> $res->card->exp_year,
			"CVC Check"		=> $res->card->cvc_check
		);

		// Return the transaction details 
		return array(
			"customer_id"		=> $customer_id, 
			"member_id"			=> $data["member_id"], 
			'status' 			=> 3, 
			'transaction_id'	=> $res->id, 
			'payment_type' 		=> 'Stripe', 
			'amount' 			=> $data["order_total"],
			'details' 			=> serialize($details), 
			'approval'			=> $res->id 
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Get Public Key
	 *
	 * We need the public key for the frontend, this makes sure that it really
	 * is that key and not some other nonsense.
	 *
	 * @access	public
	 */
	function get_publishable_key()
	{
		$config = array();
		foreach ($this->_config['gateway'][$this->site_id]['stripe']['config_data'] as $data)
		{
			$config[$data['code']] = $data['value'];
		}

		return ($config['test_mode'] == 'TRUE') ? $config['test_public_key'] : $config['public_key'];
	}

	function get_private_key()
	{
		$config = array();
		foreach ($this->_config['gateway'][$this->site_id]['stripe']['config_data'] as $data)
		{
			$config[$data['code']] = $data['value'];
		}

		return ($config['test_mode'] == 'TRUE') ? $config['test_api_key'] : $config['api_key'];
	}


	// Create a inputs for the checkout form
	function form()
	{
		$member_id = $this->EE->session->userdata('member_id');
	
		$form = '';

		if($member_id != 0){
			$cars = $this->get_saved_stripe_cards();
			
			// Check for member cards
			$form .= '	<div class="general">
	                    	<select id="stripeCustomer">
	                    		<option value="0">(+) New Credit Card</option>
	                		</select>
	                	</div>';	
		}else{
			$form .= '<input type="hidden" name="stripe_card_on_file" value="0" />';
		}
		$id = "stripe_id_".time();
		
		$form .=  ' <div class="general">
	                    <label>Credit Card Name *</label>
	                    <input class="txtinp required" id="stripe_name" type="text" />
	                </div>
	                
	                <div class="general">
	                    <label>Credit Card Number *</label>
	                    <input class="txtinp required creditcard" id="stripe_num" type="text" /></label>
	                </div>
	                
	                <div class="general">
	                    <label>CV2 Number (if applicable)</label>
	                    <input class="txtinp" id="stripe_cvc" type="text" />
	                </div>
	                
	                <div class="expdate_month">
	                    <label>Expiration Date *</label>
	                    <select id="stripe_month_exp" class="required">
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
	                    </select>
	                </div>
	                <div class="expdate_year">
	                    <label>&nbsp;</label>
	                    <select id="stripe_year_exp" class="required">';
		$year = date("Y");
		for($i=$year;$i<=($year+10);$i++){
			$i = str_pad($i, 2, 0, STR_PAD_LEFT);
			$form .= '	<option value="'.$i.'">'.$i.'</option>';
		}
		
		// Build the JS
		// We only want to get a Stripe token if stripe is selected
		// We know that if the value of input = gateway is an md5 of 
		// the config_id	
			$config_id = $this->_config['gateway'][$this->site_id]['stripe']['config_data'][0]["config_id"];
		
		$form .=  '   	</select>
	                </div>
	                <div class="clearboth"><!-- --></div>
					<script type="text/javascript">
						$(function(){
							$.getScript("https://js.stripe.com/v1/", function(data, textStatus, jqxhr) {
								
								if($("#stripe_loaded").size() == 0){

									$("#checkoutform").append(\'<input type="hidden" id="stripe_loaded" value="1"/>\');
									
									var a = $("#checkoutform");

									$("#checkoutform").on("submit",function(e){
										if($("input:radio[name=gateway]:checked").val() == "'.md5($config_id).'"){
											Stripe.setPublishableKey("'.$this->get_publishable_key().'");
											Stripe.createToken({
											    number: $("#stripe_num").val(),
											    cvc: $("#stripe_cvc").val(),
											    exp_month: $("#stripe_month_exp").val(),
											    exp_year: $("#stripe_year_exp").val()
											}, function stripeResponseHandler(status, response) {
											    if (response.error) {
											        alert(response.error.message);
													return false;
											    } else {
											    	var token = response["id"];
											    	$("#checkoutform")
											    		.append("<input type=\'hidden\' name=\'stripeToken\' value=\'" + token + "\'/>")
														.get(0).submit();
												}
											});	
											return false;
										}
									});
								}
							});
						});
					</script>';
		return $form;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Saved Cards
	 *
	 * Returns a list of the saved cards for easy reuse. This is
	 * called form the store_billing extension.
	 *
	 * @access	public
	 */
	function get_saved_stripe_cards()
	{
		$member_id = $this->EE->session->userdata('member_id');
		$card_q = $this->EE->db->get_where(
			$this->table,
			array('member_id' => $member_id)
		);
		return $card_q->result();
	}

	// --------------------------------------------------------------------

	/**
	 * Delete Saved Card
	 *
	 * We want users to have the freedom to remove their stored
	 * information. The Stripe delete call will only purge their
	 * card information. Their payment history remains intact.
	 *
	 * @access	public
	 */
	function delete_stripe_card($id)
	{
		$member_id = $this->EE->session->userdata('member_id');
		$this->EE->db->where(array(
			'stripe_id' => $id,
			'member_id' => $member_id
		))->delete($this->table);

		if ($this->EE->db->affected_rows())
		{
			// Remove from stripe
			$c = Stripe_Customer::retrieve($id);
			$c->delete();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Check Card Ownership
	 *
	 * Prevent replay attacks on customer IDs by tying their card
	 * to their EE account.
	 *
	 * @access	private
	 */
	private function _check_owns_card($token,$member_id)
	{
		$q = $this->EE->db->where(array(
			'stripe_id' => $token,
			'member_id' => $member_id
		));

		return (bool) $this->EE->db->count_all_results($this->table);
	}

	// --------------------------------------------------------------------

	/**
	 * Save Card
	 *
	 * Store the stripe customer information. These are things like
	 * their billing address so that we can show it back to them.
	 * Credit card numbers and cvcs are never stored!
	 *
	 * @access	private
	 */
	private function _save_stripe_card($data)
	{
		// save as a new customer
		$customer = Stripe_Customer::create(array(
			'card' => $data["stripeToken"],
			'description' => 'Customer for '.$data["email"]
		));

		$post = $this->_get_address_data($_POST);

		// save in our db
		$this->EE->db->insert($this->table, array_merge($post, array(
			'member_id' => $data["member_id"],
			'stripe_id' => $customer->id,
			'last_four' => $customer->active_card->last4,
			'name'		=> $customer->active_card->name,
			'type'		=> $customer->active_card->type,
			'exp_month'	=> $customer->active_card->exp_month,
			'exp_year'	=> $customer->active_card->exp_year,
		)));

		return $customer;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Address Data
	 *
	 * Helper function to retrieve the address data from the form
	 * submission. Let's us avoid lots of isset checks in the methods
	 * that deal with address data.
	 *
	 * @access	private
	 */
	private function _get_address_data($data_in)
	{
		$fields = array('fname', 'lname', 'address1', 'address2', 'city', 'state', 'zip', 'country');
		$data = array_fill_keys($fields, '');

		foreach ($data as $field => &$value)
		{
			$field_name = 'br_billing_'.$field;

			if (isset($data_in[$field_name]))
			{
				$value = $data_in[$field_name];
			}
		}

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Update Customer's Billing Address
	 *
	 * Stripe currently doesn't support this, but when they
	 * do, we'll be ready. It's a much better experience.
	 *
	 * https://answers.stripe.com/questions/can-i-update-the-address-details-of-a-customer-s-active-card
	 *
	 * @access private
	 */
	private function _update_customer_address($id, $data_in)
	{
		throw new Exception('Updating Address Information not Supported');

		$cu = Stripe_Customer::retrieve($id);
		$data = $this->_get_address_data($data_in);

		$cu->address_line1 = $data['address1'];
		$cu->address_line2 = $data['address2'];
		$cu->address_city = $data['city'];
		$cu->address_state = $data['state'];
		$cu->address_zip = $data['zip'];
		$cu->address_country = $data['country'];
		$cu->save();

		$this->EE->db
			->set($data)
			->where('stripe_id', $id)
			->update($this->table);
	}

	
	function create_subscription($data,$trans)
	{
		// Get the customer id 
			$customer_id = $trans["customer_id"];
			
		// Get the right key
			$key = $this->get_private_key();
			Stripe::setApiKey($key);
			
			$c = Stripe_Customer::retrieve($customer_id);
			$response = $c->updateSubscription(array("plan" => $data["br_subscribe"]["id"]));
			
			// The extension create_subsciption method expects
			// a subscription_id
			$result = array(
								"status_id"				=> 	1,
								"subscription_id" 		=> 	$customer_id, # Stripe doesn't have a subscription_id its just a plan on the customer record
								"current_period_start"	=>	$response->current_period_start,
								"current_period_end"	=> 	$response->current_period_end,
								"trial_end"				=> 	$response->trial_end,
								"trial_start"			=> 	$response->trial_start,
								"response"				=> 	$response
							);	
			return $result;
			
	}
	
	// --------------------------------------------------------------------

	/**
	 * Install the Gateway
	 *
	 * @access	public
	 */
	function install($config_id)
	{
		$this->EE->load->dbforge();

		// Stripe Customer Table
		$fields = array(
			'id'				=> array(
				'type'				=> 'int',
				'constraint'		=> '10',
				'unsigned'			=> TRUE,
				'auto_increment'	=> TRUE
			),
			'member_id'			=> array(
				'type'				=> 'int',
				'constraint'		=> '10',
				'default'			=> '0'
			),
			'stripe_id'			=> array(
				'type'				=> 'varchar',
				'constraint'		=> '100',
			),
			'name'				=> array(
				'type'				=> 'text'
			),
			'last_four'			=> array(
				'type'				=> 'int',
				'constraint'		=> '4',
				'unsigned'			=> TRUE
			),
			'type'				=> array(
				'type'				=> 'varchar',
				'constraint'		=> '100'
			),
			'exp_month'			=> array(
				'type'				=> 'int',
				'constraint'		=> '2'
			),
			'exp_year'			=> array(
				'type'				=> 'int',
				'constraint'		=> '4'
			),
			'address1'			=> array(
				'type'				=> 'text'
			),
			'address2'			=> array(
				'type'				=> 'text'
			),
			'city'				=> array(
				'type'				=> 'text'
			),
			'state'				=> array(
				'type'				=> 'text'
			),
			'zip'				=> array(
				'type'				=> 'text'
			),
			'country'			=> array(
				'type'				=> 'text'
			),
			'fname'				=> array(
				'type'				=> 'text'
			),
			'lname'				=> array(
				'type'				=> 'text'
			)
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->add_key(array('member_id', 'stripe_id'));
		$this->EE->dbforge->create_table($this->table,TRUE);

		$data = array();
		$data[] = array(
			'config_id' => $config_id, 
			'label'	 	=> 'Live Secret Key', 
			'code'		=> 'api_key', 
			'type' 		=> 'text',
			'value'		=> '',
			'descr'		=> '',
			'sort' 		=> 0
		);
		$data[] = array(
			'config_id' => $config_id, 
			'label'	 	=> 'Live Publishable Key', 
			'code'		=> 'public_key', 
			'type' 		=> 'text',
			'value'		=> '',
			'descr'		=> '',
			'sort' 		=> 2
		);
		$data[] = array(
			'config_id' => $config_id, 
			'label'	 	=> 'Test Secret Key', 
			'code'		=> 'test_api_key', 
			'type' 		=> 'text',
			'value'		=> '',
			'descr'		=> '',
			'sort' 		=> 3
		);
		$data[] = array(
			'config_id' => $config_id, 
			'label'	 	=> 'Test Publishable Key', 
			'code'		=> 'test_public_key', 
			'type' 		=> 'text',
			'value'		=> '',
			'descr'		=> '',
			'sort' 		=> 4
		);
		$data[] = array(
			'config_id' => $config_id, 
			'label'	 	=> 'Test Mode', 
			'code' 		=> 'test_mode',
			'type' 		=> 'dropdown', 
			'options' 	=> 'TRUE:True|FALSE:False',
			'descr'		=> 'Select Test Mode. If you select FALSE, transactions will be live', 
			'value' 	=> 'TRUE',
			'sort' 		=> 5
		);
						
		foreach($data as $d)
		{
			$this->EE->db->insert('br_config_data',$d);
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Remove the Gateway
	 *
	 * @access	public
	 */
	function remove($config_id)
	{
		$this->EE->load->dbforge();
		$this->EE->dbforge->drop_table($this->table);

		return TRUE;		
	}
}
/* End of file gateway.stripe.php */