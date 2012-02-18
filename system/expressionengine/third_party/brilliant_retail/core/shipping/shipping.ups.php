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

class Shipping_ups extends Brilliant_retail_shipping {
	public $title 	= 'UPS Shipping';
	public $label  	= 'UPS';
	public $descr 	= 'UPS Shipping';
	public $enabled = true;
	public $version = '1.0';

	function quote($data,$config){
		
		$this->rates = array();
		$tmp = array();
		
		$title['14'] = 'Next Day Air Early AM';
		$title['01'] = 'Next Day Air';
		$title['13'] = 'Next Day Air Saver';
		$title['65'] = 'Saver';
		$title['59'] = '2nd Day Air Early AM';
		$title['02'] = '2nd Day Air';
		$title['12'] = '3 Day Select';
		$title['03'] = 'Ground';
		$title['11'] = 'Standard';
		$title['07'] = 'Worldwide Express';
		$title['54'] = 'Worldwide Express Plus';
		$title['08'] = 'Worldwide Expedited';
		
		// You have to have a minimum weight for UPS to return rates
			if($data["weight"] <= 1){
				$data["weight"] = 1;
			}

		$code = unserialize($config["code"]);
		if(is_array($code)){
			foreach($code as $c){
				$reqs = '<?xml version="1.0"?>  
						<AccessRequest xml:lang="en-US">  
							<AccessLicenseNumber>8C6D8C424E63C8D0</AccessLicenseNumber>  
							<UserId>'.$config["username"].'</UserId>  
							<Password>'.$config["password"].'</Password>  
						</AccessRequest>  
						<?xml version="1.0"?>  
						<RatingServiceSelectionRequest xml:lang="en-US">  
							<Request>  
								<TransactionReference>  
									<CustomerContext>BrilliantRetail Rate Request</CustomerContext>  
									<XpciVersion>1.0001</XpciVersion>  
								</TransactionReference>  
								<RequestAction>Rate</RequestAction>  
								<RequestOption>Rate</RequestOption>  
							</Request>  
							<PickupType>  
								<Code>01</Code>  
							</PickupType>  
							<Shipment>  
								<Shipper>  
									<Address>  
										<PostalCode>'.$config["from_zip"].'</PostalCode>  
										<CountryCode>'.$config["from_country"].'</CountryCode>  
									</Address>  
								<ShipperNumber>'.$config["account"].'</ShipperNumber>  
								</Shipper>  
								<ShipTo>  
									<Address>  
										<PostalCode>'.$data["to_zip"].'</PostalCode>  
										<CountryCode>'.$data["to_country"].'</CountryCode>  
										<ResidentialAddressIndicator>1</ResidentialAddressIndicator>  
									</Address>  
								</ShipTo>  
								<ShipFrom>  
									<Address>  
										<PostalCode>'.$config["from_zip"].'</PostalCode>  
										<CountryCode>'.$config["from_country"].'</CountryCode>  
									</Address>  
								</ShipFrom>  
								<Service>  
									<Code>'.$c.'</Code>  
								</Service>  
								<Package>  
									<PackagingType>  
										<Code>02</Code>  
									</PackagingType>  
									<Dimensions>  
										<UnitOfMeasurement>  
											<Code>IN</Code>  
										</UnitOfMeasurement>  
										<Length>'.($config["size_unit"] != "in" ? $this->_convert_size($config["size_length"],$config["size_unit"],"in") : $config["size_length"]).'</Length>  
										<Width>'.($config["size_unit"] != "in" ? $this->_convert_size($config["size_width"],$config["size_unit"],"in") : $config["size_width"]).'</Width>  
										<Height>'.($config["size_unit"] != "in" ? $this->_convert_size($config["size_height"],$config["size_unit"],"in") : $config["size_height"]).'</Height>  
									</Dimensions>  
									<PackageWeight>  
										<UnitOfMeasurement>  
											<Code>LBS</Code>  
										</UnitOfMeasurement>  
										<Weight>'.($config["weight_unit"] != "lb" ? $this->_convert_weight($data["weight"],$config["weight_unit"],"lb") : $data["weight"]).'</Weight>  
									</PackageWeight>  
								</Package>  
							</Shipment>  
						</RatingServiceSelectionRequest>'; 
			// Curl
				$results = $this->_curl($config["url"],$reqs);
			
			// Match Rate
				preg_match('/<MonetaryValue>(.*?)<\/MonetaryValue>/',$results,$rate);
				$price = isset($rate[1]) ? $rate[1] : '' ;
				if($price != ''){
					$tmp[$price][$c] = array(
										'code' => $c,
										'rate' => $price,
										'label' => $title[$c]
									);
				}
			}
		}
		// Resort the responses based on rate
			ksort($tmp);
			foreach($tmp as $t){
				foreach($t as $key=>$val){
					$this->rates[$key] = $val;
				}
			}

		// Reture rates
		return $this->rates;
	}
	
	
	function install($config_id){
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Username', 
							'code' 		=> 'username',
							'type' 		=> 'password',
							'sort' 		=> 1
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Password', 
							'code' 		=> 'password',
							'type' 		=> 'password',
							'sort' 		=> 2
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Account Number', 
							'code' 		=> 'account',
							'type' 		=> 'password',
							'sort' 		=> 3
						);
		
		
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Services', 
							'code' 		=> 'code',
							'type' 		=> 'checkbox', 
							'options' 	=> '14:Next Day Air Early AM|01:Next Day Air|13:Next Day Air Saver|65:Saver|59:Second Day Air Early AM|02:Second Day Air|12:Three Day Select|03:Ground|11:Standard|07:Worldwide Express|54:Worldwide Express Plus|08:Worldwide Expedited',
							'sort' 		=> 4
						);
		
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Size Unit', 
							'code' 		=> 'size_unit',
							'type' 		=> 'dropdown', 
							'options' 	=> 'in:'.lang('inches').'|cm:'.lang('centimeters').'|feet:'.lang('feet'),
							'value' 	=> 'in', 
							'sort' 		=> 5
						);

		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Size Length', 
							'code' 		=> 'size_length',
							'type' 		=> 'text', 
							'value' 	=> '12', 
							'sort' 		=> 6
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Size Width', 
							'code' 		=> 'size_width',
							'type' 		=> 'text', 
							'value' 	=> '12', 
							'sort' 		=> 7
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Size Height', 
							'code' 		=> 'size_height',
							'type' 		=> 'text', 
							'value' 	=> '8', 
							'sort' 		=> 8
						);
								
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Weight Unit', 
							'code' 		=> 'weight_unit',
							'type' 		=> 'dropdown', 
							'options' 	=> 'oz:'.lang('ounces').'|lb:'.lang('pounds').'|gram:'.lang('grams').'|kg:'.lang('kilograms'), 
							'value' 	=> 'lb', 
							'sort' 		=> 9
						);


		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_zip', 
							'code' 		=> 'from_zip',
							'type' 		=> 'text',
							'value' 	=> '90025',
							'descr'		=> 'Enter the ship from zip or postal code',
							'sort' 		=> 10
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_state', 
							'code' 		=> 'from_state',
							'type' 		=> 'text',
							'value' 	=> 'CA',
							'descr'		=> 'Enter the 2 character state code',
							'sort' 		=> 11
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_country', 
							'code' 		=> 'from_country',
							'type' 		=> 'text',
							'value' 	=> 'US',
							'descr'		=> 'Enter the 2 character country code',
							'sort' 		=> 12
						);
						
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'API Url', 
							'code' 		=> 'url',
							'type' 		=> 'text',
							'value' 	=> 'https://www.ups.com/ups.app/xml/Rate', 
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
		return true;
	}
}