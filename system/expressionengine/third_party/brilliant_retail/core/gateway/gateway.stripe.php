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
require_once('assets/stripe/Stripe.php');

class Gateway_stripe extends Brilliant_retail_gateway {
	// Required variables
	public $title 			= 'Stripe';
	public $label 			= 'Credit Card Payment (Stripe)';
	public $descr 			= 'Accept credit cards directly from your site with a stripe gateway account.';
	public $instructions 	= 'Accept credit cards directly from your site with a stripe gateway account.';
	public $enabled = true;
	public $version = 1.0;
	
	function process($data,$config)
	{

		// Email Address
			$email = isset($data["email"]) ? $data["email"] : $this->EE->session->userdata["email"];
		
		// Get the right key
		
			$key = ($config["test_mode"] == 'TRUE') ? $config["test_api_key"] : $config["api_key"];
			Stripe::setApiKey($key);

			try {
				$result = Stripe_Charge::create(
												array(
													  "amount" 		=> (100*$data["order_total"]), // stripe charges in cents
													  "currency" 	=> strtolower($this->_config["currency"]),
													  "card" 		=> array(
															  					'number' 	=> $_POST["stripe_num"],
															  					'exp_month' => $_POST["stripe_month_exp"], 
															  					'exp_year' 	=> $_POST["stripe_year_exp"], 
															  					'cvc' 		=> $_POST["stripe_cvc"], 
															  					'name' 		=> $_POST["stripe_name"]
															  				),
													  "description" => "Charge for ".$email
													 )
												);
			} catch (Exception $e) {
				$msg = $e->getMessage();
				$trans = array(
					'error' => $msg
				);
				return $trans;
			}
			
			$res = json_decode($result);
			
			$details = array(
							"Method" => "Stripe",
							"id" => $res->id, 
							"Card Type" 	=> $res->card->type,
							"C.C. Num"		=> 'XXXX'.$res->card->last4, 
							"Exp Year"		=> $res->card->exp_year,
							"CVC Check"		=> $res->card->cvc_check
						);

		// Return the trans details 
		$trans = array(
							'status' 			=> 3, 
							'transaction_id'	=> $res->id, 
							'payment_type' 		=> 'Stripe', 
							'amount' 			=> $data["order_total"],
							'details' 			=> serialize($details), 
							'approval'			=> $res->id 
						);

		return $trans;
	}

	// Create a inputs for the checkout form
	function form()
	{
		$form =  ' 	<div class="general">
	                    <label>Credit Card Name *<br />
	                    <input class="txtinp required" name="stripe_name" type="text" /></label>
	                </div>
	                
	                <div class="general">
	                    <label>Credit Card Number *<br />
	                    <input class="txtinp required creditcard" name="stripe_num" type="text" /></label>
	                </div>
	                
	                <div class="general">
	                    <label>CV2 Number (if applicable)<br />
	                    <input class="txtinp" name="stripe_cvc" type="text" /></label>
	                </div>
	                
	                <div class="expdate_month">
	                    <label>Expiration Date *<br />
	                    <select name="stripe_month_exp" class="required">
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
	                    <select name="stripe_year_exp" class="required">';
		$year = date("Y");
		for($i=$year;$i<=($year+10);$i++){
			$i = str_pad($i, 2, 0, STR_PAD_LEFT);
			$form .= '	<option value="'.$i.'">'.$i.'</option>';
		}
		$form .=  '   	</select></label>
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
						'label'	 	=> 'Live Secret Key', 
						'code'		=> 'api_key', 
						'type' 		=> 'text',
						'value'		=> '',
						'descr'		=> '',
						'sort' 		=> 0
						);	
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Test Secret Key', 
						'code'		=> 'test_api_key', 
						'type' 		=> 'text',
						'value'		=> '',
						'descr'		=> '',
						'sort' 		=> 1
						);	
		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Test Mode', 
						'code' 		=> 'test_mode',
						'type' 		=> 'dropdown', 
						'options' 	=> 'TRUE:True|FALSE:False',
						'descr'		=> 'Select Test Mode. If you select FALSE, transactions will be live', 
						'value' 	=> 'TRUE',
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
/* End of file gateway.payleap.php */