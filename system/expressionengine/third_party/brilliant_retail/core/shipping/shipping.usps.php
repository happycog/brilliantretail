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

class Shipping_usps extends Brilliant_retail_shipping {

	public $title 	= 'USPS Shipping';
	public $label  	= 'United States Postal Service';
	public $descr 	= 'USPS Shipping';
	public $enabled = true;
	public $version = '1.1';


	public $domestic = array(	
								'FIRST CLASS:First Class',
								#'FIRST CLASS COMMERCIAL:First Class Commercial',
								#'FIRST CLASS HFP COMMERCIAL:First Class Hold For Pickup Commercial',
								'PRIORITY:Priority',
								#'PRIORITY COMMERCIAL:Priority Commercial',
								#'PRIORITY CPP:Priority Commercial Plus Price',
								#'PRIORITY HFP COMMERCIAL:Priority Hold For Pickup Commercial',
								#'PRIORITY HFP CPP:Priority Hold For Pickup Commercial Plus Price',
								'EXPRESS:Express',
								#'EXPRESS COMMERCIAL:Express Commercial',
								#'EXPRESS CPP:Express Commercial Plus Price',
								# 'EXPRESS SH:Express SH',
								# 'EXPRESS SH COMMERCIAL: Express SH Commercial',
								#'EXPRESS HFP:Express Hold For Pickup',
								#'EXPRESS HFP COMMERCIAL:Express Hold For Pickup Commercial',
								#'EXPRESS HFP CPP:Express Hold For Pickup Commercial Plus Price',
								'STANDARD POST:Standard Post',
								'MEDIA:Media'
								#,'LIBRARY:Library',
								#'ALL:All',
								#'ONLINE:Online',
								#'PLUS:Plus'
							);
							
	public $inter = array(
							'GXG:Global Express Guaranteed (GXG)',
							'GXG_D:Global Express Guaranteed Document',
							'GXG_NDR:Global Express Guaranteed Non-Document Rectangular',
							'GXG_NDNR:Global Express Guaranteed Non-Document Non-Rectangular',
							'FCM_IL:First-Class Mail International Letter',
							'FCM_ILE:First-Class Mail International Large Envelope',
							'FCM_IP:First-Class Mail International Postcard',
							'FCPIS:First-Class Package International Service',
							'PMI:Priority Mail International',
							'PMI_DFRPB:Priority Mail International DVD Flat Rate priced box',
							'PMI_FRE:Priority Mail International Flat Rate Envelope',
							'PMI_GCFRE:Priority Mail International Gift Card Flat Rate Envelope',
							'PMI_LFR:Priority Mail International Large Flat Rate Box',
							'PMI_LFRE:Priority Mail International Legal Flat Rate Envelope',
							'PMI_LVFRPB:Priority Mail International Large Video Flat Rate priced box',
							'PMI_PFRE:Priority Mail International Padded Flat Rate Envelope',
							'PMI_MFR:Priority Mail International Medium Flat Rate Box',
							'PMI_SFRE:Priority Mail International Small Flat Rate Envelope',
							'PMI_SFRB:Priority Mail International Small Flat Rate Box',
							'PMI_WFRE:Priority Mail International Window Flat Rate Envelope',
							'PMEI:Priority Mail Express International',
							'PMEI_FRB:Priority Mail Express International Flat Rate Boxes',
							'PMEI_FRE:Priority Mail Express International Flat Rate Envelope',
							'PMEI_LFRE:Priority Mail Express International Legal Flat Rate Envelope',
							'PMEI_PFRE:Priority Mail Express International Padded Flat Rate Envelope',
							'USPS_GXG:USPS GXG Envelopes'
						);
						
	function quote($data,$config){
		// 
		$this->EE->load->model('product_model');
		$countries = $this->EE->product_model->get_countries();

		$this->rates = array();
		
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
					
					$s = explode(":",$c);
					
					// Update to First Class Code. 
						if(strpos($s[0],'FIRST CLASS') !== -1){ 
							$firstclasstype = '<FirstClassMailType>PARCEL</FirstClassMailType>'; 
						}
					
					$reqs .= '	<Package ID="'.$x.'">
									<Service>'.$s[0].'</Service>
									'.$firstclasstype.'
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
		  		$reqs =	'<IntlRateV2Request USERID="'.$config["username"].'">'.
				   			'	<Revision>2</Revision>'.
      						'	<Package ID="0">'.
							      	'<Pounds>'.$lbs.'</Pounds>'.
							    	'<Ounces>'.$ozs.'</Ounces>'.
									'<Machinable>TRUE</Machinable>'.
									'<MailType>ALL</MailType>'. # ENUM(All,Package,Envelope)
							      	'<ValueOfContents>'.$data["total"].'</ValueOfContents>'. 
							      	'<Country>'.$countries[$data["to_country"]]["title"].'</Country>'.
								   	'<Container>RECTANGULAR</Container>'.
								   	'<Size>REGULAR</Size>'.
									'<Width></Width>'.
									'<Length></Length>'.
									'<Height></Height>'.
									'<Girth></Girth>'.
									'<CommercialFlag>y</CommercialFlag>'.
						      '</Package>'.
      					'</IntlRateV2Request>';
      		
      			$reqs = 'API=IntlRateV2&&XML=' . urlencode($reqs);
			}		

		// Curl
			$results = $this->_curl($config["url"],$reqs);
			
			if($isdomestic == 1){ // Domestic 
				// Domestic Rate(s)
				preg_match_all('/<Package ID="([0-9]{1,3})">(.+?)<\/Package>/',$results,$packages);
				foreach($packages[1] as $x => $package) {
					
					// Get the Rate
						preg_match('/<Rate>(.+?)<\/Rate>/',$packages[2][$x],$rate);
								
					if(isset($rate[1])){
						
						// We shouldn't show 0.00 options
							if($rate[1] <= 0){ continue; }
						
						// Get the Titles
							preg_match('/<MailService>(.+?)<\/MailService>/',$packages[2][$x],$service);
	
							$descr = strip_tags($service[1]);
							$descr = str_replace('**', '', $descr);
							$descr = str_replace('&amp;lt;sup&amp;gt;&amp;#8482;&amp;lt;/sup&amp;gt;', '', $descr);
							$descr = str_replace('&amp;lt;sup&amp;gt;&amp;#174;&amp;lt;/sup&amp;gt;', '', $descr);
							$descr = str_replace('&lt;sup&gt;&amp;reg;&lt;/sup&gt;', '', $descr);
							$descr = str_replace('&lt;sup&gt;&amp;trade;&lt;/sup&gt;', '', $descr);
							 

						$this->rates[$descr] = array(
																'code' 	=> $descr,
																'rate' 	=> $rate[1],
																'label' => $descr 
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
							
							$descr = str_replace('&lt;sup&gt;&#8482;&lt;/sup&gt;', '', $descr);
							$descr = str_replace('&lt;sup&gt;&#174;&lt;/sup&gt;', '', $descr);
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
