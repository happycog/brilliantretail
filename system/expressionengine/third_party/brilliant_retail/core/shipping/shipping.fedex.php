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

class Shipping_fedex extends Brilliant_retail_shipping {
	public $title 	= 'FedEx Shipping';
	public $label  	= 'FedEx';
	public $descr 	= 'FedEx Shipping';
	public $enabled = true;
	public $version = '1.0.1';

	function quote($data,$config){
		$this->rates = array();

		$title['PRIORITYOVERNIGHT'] = 'Priority Overnight';
		$title['STANDARDOVERNIGHT'] = 'Standard Overnight';
		$title['FIRSTOVERNIGHT'] = 'First Overnight';
		$title['FEDEX2DAY'] = 'Second Day';
		$title['FEDEXEXPRESSSAVER'] = 'Express Saver';
		$title['FEDEXGROUND'] = 'Ground';
		$title['FEDEX1DAYFREIGHT'] = 'Overnight Day Freight';
		$title['FEDEX2DAYFREIGHT'] = 'Second Day Freight';
		$title['FEDEX3DAYFREIGHT'] = 'Three Day Freight';
		$title['GROUNDHOMEDELIVERY'] = 'Home Delivery';
		$title['INTERNATIONALECONOMY'] = 'International Economy';
		$title['INTERNATIONALFIRST'] = 'International First';
		$title['INTERNATIONALPRIORITY'] = 'International Priority';

		$code = unserialize($config["code"]);
		
		if($data["weight"] < 1){
			$data["weight"] = 1;
		}
		
		foreach($code as $c){
			$reqs = '<?xml version="1.0" encoding="UTF-8" ?>
						<FDXRateRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="FDXRateRequest.xsd">
							<RequestHeader>
								<CustomerTransactionIdentifier>Express Rate</CustomerTransactionIdentifier>
								<AccountNumber>'.$config["fedex_account"].'</AccountNumber>
								<MeterNumber>'.$config["fedex_meter"].'</MeterNumber>
								<CarrierCode>'.(in_array($c,array('FEDEXGROUND','GROUNDHOMEDELIVERY')) ? 'FDXG' : 'FDXE').'</CarrierCode>
							</RequestHeader>
							<DropoffType>REGULARPICKUP</DropoffType>
							<Service>'.$c.'</Service>
							<Packaging>YOURPACKAGING</Packaging>
							<WeightUnits>LBS</WeightUnits>
							<Weight>'.number_format(($config["weight_unit"] != 'lb' ? $this->_convert_weight($data["weight"],$config["weight_unit"],'lb') : $data["weight"]), 1, '.', '').'</Weight>
							<OriginAddress>
								<StateOrProvinceCode>'.$config["from_state"].'</StateOrProvinceCode>
								<PostalCode>'.$config["from_zip"].'</PostalCode>
								<CountryCode>'.$config["from_country"].'</CountryCode>
							</OriginAddress>
							<DestinationAddress>
								<StateOrProvinceCode>'.$data["to_state"].'</StateOrProvinceCode>
								<PostalCode>'.$data["to_zip"].'</PostalCode>
								<CountryCode>'.$data["to_country"].'</CountryCode>
							</DestinationAddress>
							<Payment>
								<PayorType>SENDER</PayorType>
							</Payment>
							<PackageCount>1</PackageCount>
						</FDXRateRequest>';
				
			// Curl
				$results = $this->_curl($config["url"],$reqs);
			
			// Match Rate
			preg_match('/<NetCharge>(.*?)<\/NetCharge>/',$results,$rate);
			
			$price = isset($rate[1]) ? $rate[1] : '' ;
			if($price != ''){
				$this->rates[$c] = array(
									'code' => $c,
									'rate' => $price,
									'label' => $title[$c]
								);
			}
		}
		return $this->rates;
	}
	
	function install($config_id){
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Account', 
							'code' 		=> 'fedex_account',
							'type' 		=> 'password',
							'sort' 		=> 1
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Meter Number', 
							'code' 		=> 'fedex_meter',
							'type' 		=> 'password',
							'sort' 		=> 2
						);
		
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Services', 
							'code' 		=> 'code',
							'type' 		=> 'checkbox', 
							'options' 	=> 'PRIORITYOVERNIGHT:Priority Overnight|STANDARDOVERNIGHT:Standard Overnight|FIRSTOVERNIGHT:First Overnight|FEDEX2DAY:Second Day|FEDEXEXPRESSSAVER:Express Saver|FEDEXGROUND:Ground|FEDEX1DAYFREIGHT:Overnight Day Freight|FEDEX2DAYFREIGHT:Second Day Freight|FEDEX3DAYFREIGHT:Three Day Freight|GROUNDHOMEDELIVERY:Home Delivery|INTERNATIONALECONOMY:International Economy|INTERNATIONALFIRST:International First|INTERNATIONALPRIORITY:International Priority',
							'sort' 		=> 3
						);
		
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Weight Unit', 
							'code' 		=> 'weight_unit',
							'type' 		=> 'dropdown', 
							'options' 	=> 'oz:'.lang('ounces').'|lb:'.lang('pounds').'|gram:'.lang('grams').'|kg:'.lang('kilograms'), 
							'value' 	=> 'lb', 
							'sort' 		=> 4
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_zip', 
							'code' 		=> 'from_zip',
							'type' 		=> 'text',
							'value' 	=> '90025',
							'descr'		=> 'Enter the ship from zip or postal code',
							'sort' 		=> 5
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_state', 
							'code' 		=> 'from_state',
							'type' 		=> 'text',
							'value' 	=> 'CA',
							'descr'		=> 'Enter the 2 character state code',
							'sort' 		=> 6
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'from_country', 
							'code' 		=> 'from_country',
							'type' 		=> 'text',
							'value' 	=> 'US',
							'descr'		=> 'Enter the 2 character country code',
							'sort' 		=> 7
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'API Url', 
							'code' 		=> 'url',
							'type' 		=> 'text',
							'value' 	=> 'https://gatewaybeta.fedex.com/GatewayDC', 
							'sort' 		=> 8
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
