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
			require_once('assets/google-checkout/library/googlecart.php');
			require_once('assets/google-checkout/library/googleitem.php');
			require_once('assets/google-checkout/library/googleshipping.php');
			require_once('assets/google-checkout/library/googletax.php');
			
			$merchant_id 	= $config['merchant_id'];  // Your Merchant ID
			$merchant_key 	= $config['merchant_key'];  // Your Merchant Key
			$server_type 	= $config['sandbox'];
			$currency 		= "USD";
			
			$cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);
			
			$cart->SetMerchantPrivateData(new MerchantPrivateData(array("transaction_id" =>$data['transaction_id'])));
			
			
			$i = 1;
			foreach($data["cart"]["items"] as $items){
				
				
				${'item_'.$i} = new GoogleItem($items['title'],
										"",
										$items['quantity'],
										$this->_currency_round($items["price"])
										);
				${'item_'.$i}->SetMerchantItemId($items["product_id"]);
				
				$cart->AddItem(${'item_'.$i});
				
				$i++;
			}
			
			
			// Applying a discount
			if ($data['cart_discount'] != 0)
			{
				${'item_'.$i} = new GoogleItem("Discount Applied",
										"",
										1,
										"-".$this->_currency_round($data['cart_discount'])
										);
													
				$cart->AddItem(${'item_'.$i});
				
			}
			
			// Calculating the Tax by taking the subtotal, subtracting the discount and 
			// dividing by the pre-calculated rate.
			// -- Then formatting the number by 2 decimal places
			if($data['cart_tax'] == 0){
				$taxrate = 0;
			}else{
				$taxrate = number_format(($data['cart_subtotal']-$data['cart_discount']/$data['cart_tax']),2);
			}
			
			$tax_rule = new GoogleDefaultTaxRule($taxrate);
			$tax_rule->SetWorldArea(true);
			$cart->AddDefaultTaxRules($tax_rule);
				
			
			$ship_1 = new GoogleFlatRateShipping("Shipping", $data['cart_shipping']);
			$cart->AddShipping($ship_1);
			
			// Specify <edit-cart-url>
			$cart->SetEditCartUrl($data['return']);
	
		    // Specify "Return to xyz" link
		    $cart->SetContinueShoppingUrl($data['cancel_return']);
		
		    // Define rounding policy
		    $cart->AddRoundingPolicy("CEILING", "TOTAL");
	    
   	        echo '	<html>
   	        			<head>
   	        				<title>Google Checkout</title>
   	        			</head>
   	        			<body onLoad="document.forms[\'gateway_form\'].submit();">
							<form method="post" name="gateway_form" action="'.$cart->checkout_url.'">
			    				<input type="hidden" name="cart" value="'.base64_encode($cart->GetXML()).'"/>
								<input type="hidden" name="signature" value="'.base64_encode($cart->CalcHmacSha1($cart->GetXML())).'" />
								<p style="text-align:center;">
									Your order is being processed... will be redirected to the payment website.
								 	<br />
								 	<br />
								 	If you are not automatically redirected to Google Checkout within 5 seconds...<br /><br />
									<input type="submit" value="Click Here">
								</p>
							</form>
						</body>
					</html>';
		}
	
	// Process IPN Calls which come back from Google 
		public function gateway_ipn($config)
		{
		
		$cancel = $this->EE->input->get('cancel',TRUE);
		if($cancel != ''){
			$this->EE->product_model->cart_update_status(session_id(),0);
			$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
			exit();
		}
		
		$this->EE->load->library('logger');
		
		$this->EE->logger->developer('IPN Started');
				
		  require_once('assets/google-checkout/library/googleresponse.php');
		  require_once('assets/google-checkout/library/googlemerchantcalculations.php');
		  require_once('assets/google-checkout/library/googlerequest.php');
		  require_once('assets/google-checkout/library/googlenotificationhistory.php');
		
		 
		  //Definitions
		  $merchant_id = $config['merchant_id'];  // Your Merchant ID
		  $merchant_key = $config['merchant_key'];  // Your Merchant Key
		  $server_type = $config['sandbox'];
		  $currency = "USD";
		  $certificate_path = ""; // set your SSL CA cert path
		  
		  
		  //Create the response object
		  $Gresponse = new GoogleResponse($merchant_id, $merchant_key);
		  
		 
		  //Retrieve the XML sent in the HTTP POST request to the ResponseHandler
		  $xml_response = isset($HTTP_RAW_POST_DATA)?
		                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");
		  
		  //If serial-number-notification pull serial number and request xml
		  if(strpos($xml_response, "xml") == FALSE){
		   
		    //Find serial-number ack notification
		    $serial_array = array();
		    parse_str($xml_response, $serial_array);
		    $serial_number = $serial_array["serial-number"];
		    
		    //Request XML notification
		    $Grequest = new GoogleNotificationHistoryRequest($merchant_id, $merchant_key, $server_type);
		    
		    $raw_xml_array = $Grequest->SendNotificationHistoryRequest($serial_number);
		    
		    if ($raw_xml_array[0] != 200){
		      //Add code here to retry with exponential backoff
		    } else {
		      $raw_xml = $raw_xml_array[1];
		    }
		    $Gresponse->SendAck($serial_number, false);
		  }
		  else{
		    
		    //Else assume pre 2.5 XML notification
		    //Check Basic Authentication
		    $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);
		    $status = $Gresponse->HttpAuthentication();
		    if(! $status) {
		      die('authentication failed');
		    }
		    $raw_xml = $xml_response;
		    $Gresponse->SendAck(null, false);
		  }
		  
		  if (get_magic_quotes_gpc()) {
		    $raw_xml = stripslashes($raw_xml);
		  }
		  
		  list($root, $data) = $Gresponse->GetParsedXML($raw_xml);
		  
		  
		  switch($root){
		    case "new-order-notification": {
		      
		      $this->EE->logger->developer('Google New Order Notification');
		      
		      $transaction_id = $data[$root]['shopping-cart']['merchant-private-data']['transaction_id']['VALUE'];
		      $google_order_number = $data[$root]['google-order-number']['VALUE'];
		      
		      $this->ipn_create_order($transaction_id,2);
		      
		      $this->add_gc_entry($transaction_id,$google_order_number);
		      
		      break;
		    }
		    case "authorization-amount-notification": {
		     	break;
      		}
      		case "order-state-change-notification": {
		     
		      $this->EE->logger->developer('Order State Change Triggered');
		      
		      $orderstate = $data[$root]['new-fulfillment-order-state']['VALUE'];
		      
		      $google_order_number = $data[$root]['google-order-number']['VALUE'];
		      
		      $br_merchant_id = $this->get_gc_entry($google_order_number);
		      
		      $status['NEW'] = 2;
		      $status['PROCESSING'] = 3;
		      $status['DELIVERED'] = 4;
			
			  if(isset($status[$orderstate]))
			  {
			  	$this->ipn_create_order($br_merchant_id,$status[$orderstate]);	
			  	
			  	$this->EE->logger->developer("Updating order with status ID: ". $status[$orderstate]);
			  }		  
		      
		      break;
		    }
		  }
		  
		
		}
	
	// Create a inputs for the checkout form
		public function form(){
			$form = '<img  src="https://checkout.google.com/buttons/checkout.gif?merchant_id=&w=168&h=44&style=trans&variant=text&loc=en_US" />';
			return $form;
		}
	
	// Install the gateway
		public function install($config_id){
			$sql[] = "DROP TABLE IF EXISTS exp_br_google_checkout;";
			$sql[] = "CREATE TABLE exp_br_google_checkout (
						  google_checkout_id int(11) NOT NULL AUTO_INCREMENT,
						  google_order_id varchar(128) NOT NULL DEFAULT '',
						  br_merchant_id varchar(128) NOT NULL DEFAULT '',
						  PRIMARY KEY (google_checkout_id)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			
			foreach($sql as $line)
			{
				$this->EE->db->query($line);
			}
			
			
			$data = array();
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Sandbox Mode', 
							'code' 		=> 'sandbox',
							'type' 		=> 'dropdown', 
							'options' 	=> 'sandbox:True|production:False (Transactions are Live)',
							'value' 	=> 'TRUE',
							'sort' 		=> 1
							);	
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Merchant ID', 
							'code' 		=> 'merchant_id',
							'type' 		=> 'text',
							'sort' 		=> 2
							);
			$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Merchant Key', 
							'code' 		=> 'merchant_key',
							'type' 		=> 'text',
							'sort' 		=> 3
							);	
			foreach($data as $d){
				$this->EE->db->insert('br_config_data',$d);
			}				
			return true;
	}

	// Remove the gateway
		public function remove($config_id){
			
			$this->EE->db->query("DROP TABLE IF EXISTS exp_br_google_checkout;");
			
			return true;		
		}
		
		function add_gc_entry($transid,$googleid)
		{
			$data = array(
				'google_order_id' => $googleid,
				'br_merchant_id' => $transid
			);
			
			$this->EE->db->insert('br_google_checkout',$data);
		}
		
		function get_gc_entry($googleid)
		{
			return $this->EE->db->select('br_merchant_id')->from('br_google_checkout')->where('google_order_id',$googleid)->get()->row()->br_merchant_id;
		}
}