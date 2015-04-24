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
require_once('assets/fedex/library/fedex-common.php');

/*
For testing we disabled the wsdl cache during 
shipping method development. It should be fine 
to cache the wsdl file while using test mode and live 
shipping rates. 

ini_set("soap.wsdl_cache_enabled", "0");
*/

class Shipping_fedex extends Brilliant_retail_shipping {
	public $title 	= 'FedEx Shipping';
	public $label  	= 'FedEx';
	public $descr 	= 'FedEx Shipping';
	public $enabled = true;
	public $version = '1.5';

	function quote($data,$config){
		
		if($config["test_mode"] == 'true'){
			$wsdl = PATH_THIRD."brilliant_retail/core/shipping/assets/fedex/wsdl/RateService_v13_test.wsdl";
		}else{
			$wsdl = PATH_THIRD."brilliant_retail/core/shipping/assets/fedex/wsdl/RateService_v13.wsdl";
		}
		
		$client = new SoapClient($wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

		// Need at least 1 for the weight
			if($data["weight"] < 1) $data["weight"] = 1;
		
		$request['WebAuthenticationDetail'] = array(
													'UserCredential' => array(
																				'Key' 		=> $config["fedex_key"],
																				'Password' 	=> $config["fedex_password"]
																			)
													); 
		$request['ClientDetail'] = array(
											'AccountNumber' => $config["fedex_account"], 
											'MeterNumber' 	=> $config["fedex_meter"] 
										);
		
		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v13 using BrilliantRetail ***');
		$request['Version'] = array(
										'ServiceId' => 'crs', 
										'Major' => '13', 
										'Intermediate' => '0', 
										'Minor' => '0'
									);
				
		$request['ReturnTransitAndCommit'] 					= true;
		$request['RequestedShipment']['DropoffType'] 		= 'REGULAR_PICKUP'; 
		$request['RequestedShipment']['ShipTimestamp'] 		= date('c');
		$request['RequestedShipment']['PackagingType'] 		= 'YOUR_PACKAGING';

		$request['RequestedShipment']['TotalInsuredValue']	= array('Ammount'=>$data["total"],'Currency'=>'USD');
		
		// Shipper Info
			$request['RequestedShipment']['Shipper'] 			= array(
																		'Contact' => array(
																							'PersonName' 	=> '',
																							'CompanyName' 	=> '',
																							'PhoneNumber' 	=> ''),
																							'Address' => array(
																												'StreetLines' 			=> array(''),
																												'City'					=> '',
																												'StateOrProvinceCode' 	=> $config["from_state"],
																												'PostalCode' 			=> $config["from_zip"],
																												'CountryCode' 			=> $config["from_country"]
																												)
																		);
		// Recipient Info
			$request['RequestedShipment']['Recipient']= array(
					'Contact' => array(
						'PersonName' 	=> '',
						'CompanyName' 	=> '',
						'PhoneNumber' 	=> '',
					),
					'Address' => array(
						'StreetLines' 			=> array(),
						'City' 					=> '',
						'StateOrProvinceCode' 	=> substr($data["to_state"],0,2),
						'PostalCode' 			=> $data["to_zip"],
						'CountryCode' 			=> $data["to_country"],
						'Residential' 			=> true)
				);
			
		$request['RequestedShipment']['RateRequestTypes'] 	= 'LIST'; 
		$request['RequestedShipment']['PackageCount'] 		= '1';
		$request['RequestedShipment']['RequestedPackageLineItems'] = $packageLineItem = array(
				'SequenceNumber'	=> 1,
				'GroupPackageCount'	=> 1,
				'Weight' => array(
					'Value' =>	$data["weight"],
					'Units' =>	strtoupper($config["weight_unit"])
				),
				'Dimensions' => array(
					'Length' 	=> (int) $config["size_length"],
					'Width' 	=> (int) $config["size_width"],
					'Height' 	=> (int) $config["size_height"],
					'Units' 	=> 'IN'
				)
			);
		
		$code = unserialize($config["code"]);
		
		$this->rates = array();
		
		foreach($code as $c){
			$request['RequestedShipment']['ServiceType'] 	= $c;
			try 
			{
				if(setEndpoint('changeEndpoint'))
				{
					$newLocation = $client->__setLocation(setEndpoint('endpoint'));
				}
				
				$response = $client ->getRates($request);
			        
			    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
			    {  	
			    	$rateReply = $response -> RateReplyDetails;
			    	$this->rates[$c] = array(
											'code' 	=> $c,
											'rate' 	=> number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2),
											'label' => ucwords(strtolower(str_replace("_"," ",$rateReply -> ServiceType)))
										);
			   	}
			} catch (SoapFault $exception) {
			   printFault($exception, $client);        
			}
		}		
		
		if(count($this->rates) > 1){
			usort($this->rates,array($this,'_rate_sort'));
		}

		return $this->rates;
	}
	
	function install($config_id){
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Test Mode', 
							'code' 		=> 'test_mode',
							'type' 		=> 'dropdown', 
							'options' 	=> 'true:TRUE|false:FALSE (live rate service)',
							'value' 	=> 'true', 
							'sort' 		=> 0
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Account', 
							'code' 		=> 'fedex_account',
							'type' 		=> 'text',
							'sort' 		=> 1
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Meter Number', 
							'code' 		=> 'fedex_meter',
							'type' 		=> 'text',
							'sort' 		=> 2
						);
						
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Key', 
							'code' 		=> 'fedex_key',
							'type' 		=> 'text',
							'sort' 		=> 3
				);

		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Password', 
							'code' 		=> 'fedex_password',
							'type' 		=> 'text',
							'sort' 		=> 4
						);				
						
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Services', 
							'code' 		=> 'code',
							'type' 		=> 'checkbox', 
							'options' 	=> 'EUROPE_FIRST_INTERNATIONAL_PRIORITY:Europe First International Priority|FEDEX_1_DAY_FREIGHT:FedEx 1 Day|FEDEX_2_DAY:FedEx 2 Day|FEDEX_2_DAY_AM:FedEx 2 Day AM|FEDEX_EXPRESS_SAVER:FedEx Express Saver|FEDEX_FIRST_FREIGHT:FedEx First Freight|GROUND_HOME_DELIVERY:Ground Home|FEDEX_GROUND:Ground|FIRST_OVERNIGHT:First Overnight|INTERNATIONAL_ECONOMY:International Economy|INTERNATIONAL_FIRST:International First|INTERNATIONAL_PRIORITY:International Priority|PRIORITY_OVERNIGHT:Priority Overnight|STANDARD_OVERNIGHT:Standard Overnight', 
							'sort' 		=> 5
						);
		
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Weight Unit', 
							'code' 		=> 'weight_unit',
							'type' 		=> 'dropdown', 
							'options' 	=> 'oz:'.lang('ounces').'|lb:'.lang('pounds').'|gram:'.lang('grams').'|kg:'.lang('kilograms'), 
							'value' 	=> 'lb', 
							'sort' 		=> 6
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_zip', 
							'code' 		=> 'from_zip',
							'type' 		=> 'text',
							'value' 	=> '90025',
							'descr'		=> 'Enter the ship from zip or postal code',
							'sort' 		=> 7
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_state', 
							'code' 		=> 'from_state',
							'type' 		=> 'text',
							'value' 	=> 'CA',
							'descr'		=> 'Enter the 2 character state code',
							'sort' 		=> 8
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_country', 
							'code' 		=> 'from_country',
							'type' 		=> 'text',
							'value' 	=> 'US',
							'descr'		=> 'Enter the 2 character country code',
							'sort' 		=> 9
						);
						
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Weight Unit', 
							'code' 		=> 'weight_unit',
							'type' 		=> 'dropdown', 
							'options' 	=> 'oz:'.lang('ounces').'|lb:'.lang('pounds').'|gram:'.lang('grams').'|kg:'.lang('kilograms'), 
							'value' 	=> 'lb', 
							'sort' 		=> 10
						);				

		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Size Length', 
							'code' 		=> 'size_length',
							'type' 		=> 'text', 
							'value' 	=> '12', 
							'descr'		=> 'Enter the standard box size length in inches',
							'sort' 		=> 11
						);
		
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Size Width', 
							'code' 		=> 'size_width',
							'type' 		=> 'text', 
							'value' 	=> '12', 
							'descr'		=> 'Enter the standard box size width in inches',
							'sort' 		=> 12
						);
		
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Size Height', 
							'code' 		=> 'size_height',
							'type' 		=> 'text', 
							'value' 	=> '8', 
							'descr'		=> 'Enter the standard box height in inches',
							'sort' 		=> 13
						);

		foreach($data as $d){
			$this->EE->db->insert('br_config_data',$d);
		}
		return true;
	}
	
	function remove($config_id){
		return true;
	}
	
	function update($current = '',$config_id = ''){
		
		$data 	= array();
		$prefix = $this->EE->db->dbprefix;
		
		// ADDED 1.3
			if(version_compare($current, '1.3', '<')) {
				echo $current;
				$this->EE->db->query("	UPDATE 
											".$this->EE->db->dbprefix."br_config_data 
										SET 
											type = 'text' 
										WHERE 
											config_id = ".$config_id." 
										AND 
											code IN ('fedex_account','fedex_meter')");

				$this->EE->db->query("	UPDATE 
											".$this->EE->db->dbprefix."br_config_data 
										SET 
											sort = 5 
										WHERE 
											config_id = ".$config_id." 
										AND 
											code IN ('code','weight_unit')");
				$this->EE->db->query("	UPDATE 
											".$this->EE->db->dbprefix."br_config_data 
										SET 
											value 	= '',
											options = 'EUROPE_FIRST_INTERNATIONAL_PRIORITY:Europe First International Priority|FEDEX_1_DAY_FREIGHT:FedEx 1 Day|FEDEX_2_DAY:FedEx 2 Day|FEDEX_2_DAY_AM:FedEx 2 Day AM|FEDEX_EXPRESS_SAVER:FedEx Express Saver|FEDEX_FIRST_FREIGHT:FedEx First Freight|GROUND_HOME_DELIVERY:Ground Home|FEDEX_GROUND:Ground|FIRST_OVERNIGHT:First Overnight|INTERNATIONAL_ECONOMY:International Economy|INTERNATIONAL_FIRST:International First|INTERNATIONAL_PRIORITY:International Priority|PRIORITY_OVERNIGHT:Priority Overnight|STANDARD_OVERNIGHT:Standard Overnight'
										WHERE 
											config_id = ".$config_id." 
										AND 
											code = 'code'");
			
				$data[] = array(
					'config_id' => $config_id, 
					'label'	 	=> 'Key', 
					'code' 		=> 'fedex_key',
					'type' 		=> 'text',
					'sort' 		=> 3
				);

				$data[] = array(
									'config_id' => $config_id, 
									'label'	 	=> 'Password', 
									'code' 		=> 'fedex_password',
									'type' 		=> 'text',
									'sort' 		=> 4
								);
			}
			
		// ADDED 1.4	
			if(version_compare($current, '1.4', '<')) {
				
				//REMOVE THE URL FIELD 
					$this->EE->db->query("	DELETE FROM 
												".$this->EE->db->dbprefix."br_config_data 
											WHERE 
												config_id = ".$config_id." 
											AND 
												code = 'url'");
				// ADD THE BOX SIZES
					$data[] = array(
										'config_id' => $config_id, 
										'label'	 	=> 'Weight Unit', 
										'code' 		=> 'weight_unit',
										'type' 		=> 'dropdown', 
										'options' 	=> 'oz:'.lang('ounces').'|lb:'.lang('pounds').'|gram:'.lang('grams').'|kg:'.lang('kilograms'), 
										'value' 	=> 'lb', 
										'sort' 		=> 10
									);				
					$data[] = array(
										'config_id' => $config_id, 
										'label'	 	=> 'Size Length', 
										'code' 		=> 'size_length',
										'type' 		=> 'text', 
										'value' 	=> '12', 
										'descr'		=> 'Enter the standard box size length in inches',
										'sort' 		=> 11
									);
					
					$data[] = array(
										'config_id' => $config_id, 
										'label'	 	=> 'Size Width', 
										'code' 		=> 'size_width',
										'type' 		=> 'text', 
										'value' 	=> '12', 
										'descr'		=> 'Enter the standard box size width in inches',
										'sort' 		=> 12
									);
					
					$data[] = array(
										'config_id' => $config_id, 
										'label'	 	=> 'Size Height', 
										'code' 		=> 'size_height',
										'type' 		=> 'text', 
										'value' 	=> '8', 
										'descr'		=> 'Enter the standard box height in inches',
										'sort' 		=> 13
									);
			}
			
		// ADDED 1.5
			if(version_compare($current, '1.5', '<')) {
				$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'Test Mode', 
						'code' 		=> 'test_mode',
						'type' 		=> 'dropdown', 
						'options' 	=> 'true:TRUE|false:FALSE (live rate service)',
						'value' 	=> 'true', 
						'sort' 		=> 0
					);
			}
		
		// Run any db updates
			foreach($data as $d){
				$this->EE->db->insert('br_config_data',$d);
			}
			
		return true;
	}
}
