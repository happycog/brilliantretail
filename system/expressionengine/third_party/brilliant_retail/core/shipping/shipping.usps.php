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

class Shipping_usps extends Brilliant_retail_shipping {

	public $title 	= 'USPS Shipping';
	public $label  	= 'United States Postal Service';
	public $descr 	= 'USPS Shipping';
	public $enabled = true;
	public $version = '1.0';


	public $domestic = array(	'EXPRESS:Express',
								'PRIORITY:Priority',
								'PARCEL:Parcel',
								'FIRST CLASS:First Class',
								'EXPRESS SH:Express SH',
								'BPM:BPM',
								'MEDIA:Media',
								'LIBRARY:Library');
	public $inter = array(
							'FIRST_CLASS_INT_LARGE_ENV:First-Class Mail International Large Envelope',
							'FIRST_CLASS_MAIL_INT_PACKAGE:First-Class Package International Service',
							'PRIORITY_MAIL_INT_FLAT_RATE_ENV:Priority Mail International Window Flat Rate Envelope',
							'PRIORITY_MAIL_INT_SM_FLAT_RATE_ENV:Priority Mail International Small Flat Rate Envelope',
							'PRIORITY_MAIL_INT_GC_FLAT_RATE_ENV:Priority Mail International Gift Card Flat Rate Envelope',
							'PRIORITY_MAIL_INT_PAD_FLAT_RATE_ENV:Priority Mail International Padded Flat Rate Envelope',
							'PRIORITY_MAIL_INT_LEGAL_FLAT_RATE_ENV:Priority Mail International Legal Flat Rate Envelope',
							'PRIORITY_MAIL_INT_FLAT_RATE_ENV:Priority Mail International Flat Rate Envelope',
							'PRIORITY_MAIL_INT_LARGE_VIDEO_FLAT_RATE_BOX:Priority Mail International Large Video Flat Rate Box',
							'PRIORITY_MAIL_INT_DVD_FLAT_RATE_BOX:Priority Mail International DVD Flat Rate Box',
							'PRIORITY_MAIL_INT_SM_FLAT_RATE_BOX:Priority Mail International Small Flat Rate Box',
							'PRIORITY_MAIL_INT_MD_FLAT_RATE_BOX:Priority Mail International Medium Flat Rate Box',
							'PRIORITY_MAIL_INT_LG_FLAT_RATE_BOX:Priority Mail International Large Flat Rate Box',
							'PRIORITY_MAIL_INT_PRIORITY_MAIL:Priority Mail International',
							'EXP_MAIL_INT_LEGAL_FLAT_RATE_ENV:Express Mail International Legal Flat Rate Envelope',
							'EXP_MAIL_INT_FLAT_RATE_ENV:Express Mail International Flat Rate Envelope',
							'EXP_MAIL_INT:Express Mail International',
							'USPS_GXG_ENV:USPS GXG Envelopes',
							'GLOBAL_EXP_GRNTD_ND_NR:Global Express Guaranteed Non-Document Non-Rectangular',
							'GLOBAL_EXP_GRNTD_ND_R:Global Express Guaranteed Non-Document Rectangular',
							'GLOBAL_EXP_GRNTD:Global Express Guaranteed (GXG)'
						);
						
	function quote($data,$config){
		// 
		$this->EE->load->model('product_model');
		$countries = $this->EE->product_model->get_countries();

		$this->rates = array();
		
		$title['EXPRESS'] = 'Express';
		$title['PRIORITY'] = 'Priority';
		$title['PARCEL'] = 'Parcel';
		$title['FIRST CLASS'] = 'First Class';
		$title['EXPRESS SH'] = 'Express SH';
		$title['BPM'] = 'BPM';
		$title['MEDIA'] = 'Media';
		$title['LIBRARY'] = 'Library';

		$domestic = unserialize($config["domestic"]);
		$inter = unserialize($config["inter"]);

		// Weight (in lbs)
			if($config["weight_unit"] != 'lb'){
				$data["weight"] = $this->_convert_weight($data["weight"],$config["weight_unit"],'lb');
			}
		
		// Split into Lbs and Ozs
			$lbs = floor($data["weight"]);
			$ozs = ($data["weight"] - $lbs) * 16;
			if($lbs == 0 and $ozs < 1) $ozs = 1;
		
		// Code(s)
			if(!is_array($domestic)) {
				$domestic = array($domestic);
			}
			if(!is_array($inter)) {
				$inter = array($inter);
			}
		
		// if Domestic
			if($config["from_country"] == $data["to_country"]){
				$isdomestic = 1;
				$reqs = 'API=RateV4&XML=<RateV4Request USERID="'.$config["username"].'">';
				foreach($domestic as $x => $c){
					
					// Update to First Class Code. 
						if($c == 'FIRST CLASS'){ $c = 'First-Class Mail'; }
					
					$reqs .= '	<Package ID="'.$x.'">
									<Service>'.$c.'</Service>
									<ZipOrigination>'.$config["from_zip"].'</ZipOrigination>
									<ZipDestination>'.$data["to_zip"].'</ZipDestination>
									<Pounds>'.$lbs.'</Pounds>
									<Ounces>'.$ozs.'</Ounces>
									<Container></Container>
									<Size>REGULAR</Size>
									<Machinable>TRUE</Machinable>
								</Package>';
				}
				$reqs .= '</RateV4Request>';
			}else{
				$isdomestic = 0;
				$reqs =	'<IntlRateRequest USERID="'.$config["username"].'" PASSWORD="'.$config["username"].'">'.
							'<Package ID="0">'.
								'<Pounds>'.$lbs.'</Pounds>'.
								'<Ounces>'.$ozs.'</Ounces>'.
								'<MailType>Package</MailType>'.
								'<ValueOfContents>'.$data["total"].'</ValueOfContents>'.
								'<Country>'.$countries[$data["to_country"]]["title"].'</Country>'.
							'</Package>'.
						'</IntlRateRequest>';
				$reqs = 'API=IntlRate&XML=' . urlencode($reqs);
			}		
		// Curl
			$results = $this->_curl($config["url"],$reqs);
			
			if($isdomestic == 1){ // Domestic 
				// Domestic Rate(s)
				preg_match_all('/<Package ID="([0-9]{1,3})">(.+?)<\/Package>/',$results,$packages);
				foreach($packages[1] as $x => $package) {
					preg_match('/<Rate>(.+?)<\/Rate>/',$packages[2][$x],$rate);
					if(isset($rate[1])){
						$this->rates[$domestic[$package]] = array(
																'code' 	=> $domestic[$package],
																'rate' 	=> $rate[1],
																'label' => $title[$domestic[$package]]
															);
					}
				}
				
			}else{
				foreach($inter as $int){
					$select[$int] = 1;
				}
				foreach($this->inter as $opt){
					$a = explode(":",$opt);
					if(isset($select[$a[0]])){
						$interOpts[$a[1]] = $a[0];
					}
				}
				
				// International options
				$xml = simplexml_load_string($results);
				$id = 0;
				if(isset($xml->Package->Service)){
					foreach($xml->Package->Service as $s){
							$service = (array)$s;
							$descr = strip_tags($service["SvcDescription"]);
							$descr = str_replace('**', '', $descr);
							$descr = str_replace('&lt;sup&gt;&amp;reg;&lt;/sup&gt;', '', $descr);
							$descr = str_replace('&lt;sup&gt;&amp;trade;&lt;/sup&gt;', '', $descr);
						if(isset($interOpts[$descr])){
							$this->rates[$id] = array(
																				'code' 	=> $service["SvcDescription"],
																				'rate' 	=> $service["Postage"],
																				'label' => $descr
																			);
							
							$id++;
						}
					} 
				}
			}
		if(count($this->rates) > 1){
			usort($this->rates,array($this,'_rate_sort'));
		}
		return $this->rates;
	}

	function install($config_id){
		$data = array();

		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Username', 
							'code' 		=> 'username',
							'value' 	=> '207BRILL7265',
							'type' 		=> 'text',
							'sort' 		=> 1
						);

		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Domestic Services', 
							'code' 		=> 'domestic',
							'type' 		=> 'checkbox', 
							'options' 	=> join("|",$this->domestic),
							'sort' 		=> 2
						);

		$data[] = array(
						'config_id' => $config_id, 
						'label'	 	=> 'International Services', 
						'code' 		=> 'inter',
						'type' 		=> 'checkbox', 
						'options' 	=> join("|",$this->inter),
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
							'value' 	=> 'http://Production.ShippingAPIs.com/ShippingAPI.dll', 
							'sort' 		=> 8
						);


		foreach($data as $d){
			$this->EE->db->insert('br_config_data',$d);
		}
		return true;
	}
	
	function _rate_sort($a,$b){
		return ($a["rate"] > $b["rate"]) ? +1 : -1;
	}
		
	function remove($config_id){
		return true;
	}
	
	function update($current = '',$config_id = ''){
		return true;
	}
}
