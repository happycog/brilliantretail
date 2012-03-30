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

// This class was inspired by the information posted 
// here: http://www.rbrasier.com/2012/01/australia-post-calculator-php/
// 

class Shipping_australian_post extends Brilliant_retail_shipping {

	public $title 	= 'Australian Post Shipping';
	public $label  	= 'Australian Post';
	public $descr 	= 'Australian Post';
	public $enabled = true;
	public $version = '1.0';

	function quote($data,$config){
		
		// Its Domestic 
		if($data["to_country"] == 'AU'){
		  	
		  	$arr = array(	
	  		 				"from_postcode" => $config["aus_post_from_post_code"],
	  		 				"to_postcode"	=> $data["to_zip"],
	  		 				"length"		=> $config["aus_post_length"], 
	  		 				"height"		=> $config["aus_post_height"],
	  		 				"width"			=> $config["aus_post_width"],
	  		 				"weight"		=> $data["weight"]
	  		 			);
		  		
		  	
		  	
		  	// Get the international Services
			  	$str = 'https://auspost.com.au/api/postage/parcel/domestic/service.json?';
			  	foreach($arr as $key => $val){
		  			$str .= $key.'='.$val.'&';
		  		}
		  		$str = rtrim($str,'&');
		  		
				$response = $this->_get_api($str,$config["aus_post_api_key"]);
				$r = json_decode($response);

  			foreach($r->services->service as $s){
				$str = "https://auspost.com.au/api/postage/parcel/domestic/calculate.json?";
		  		
		  		$arr["service_code"] = $s->code;
		  		
		  		foreach($arr as $key => $val){
		  			$str .= $key.'='.$val.'&';
		  		}
		  		
		  		$str = rtrim($str,'&');
				$response = $this->_get_api($str,$config["aus_post_api_key"]);
		 		
		 		$r = json_decode($response);
		 		if(isset($r->postage_result->service)){
		 			$this->rates[] = array(
											'code' 	=> $r->postage_result->service,
											'rate' 	=> $r->postage_result->total_cost,
											'label' => $r->postage_result->service.'<span>'.$r->postage_result->delivery_time.'</span>'
										);
		 		}
  			}
		
		}else{

		  	// Get the international Services
			  	$str = 'https://auspost.com.au/api/postage/parcel/international/service.json?country_code='.$data["to_country"].'&weight='.$data["weight"];
				$response = $this->_get_api($str,$config["aus_post_api_key"]);
				$r = json_decode($response);

			foreach($r->services->service as $s){
				$str = "https://auspost.com.au/api/postage/parcel/international/calculate.json?";
		  		$arr = array(	
		  		 				"from_postcode" => $config["aus_post_from_post_code"],
		  		 				"to_postcode"	=> $data["to_zip"],
		  		 				"length"		=> $config["aus_post_length"], 
		  		 				"height"		=> $config["aus_post_height"],
		  		 				"width"			=> $config["aus_post_width"],
		  		 				"weight"		=> $data["weight"],
		  		 				"country_code"	=> $data["to_country"], 
		  		 				"service_code"	=> $s->code  
		  		 			);
		  		
		  		foreach($arr as $key => $val){
		  			$str .= $key.'='.$val.'&';
		  		}
		  		$str = rtrim($str,'&');
				$response = $this->_get_api($str,$config["aus_post_api_key"]);
		 		
		 		$r = json_decode($response);
		 		if(isset($r->postage_result->service)){
		 			$this->rates[] = array(
											'code' 	=> $r->postage_result->service,
											'rate' 	=> $r->postage_result->total_cost,
											'label' => $r->postage_result->service
										);
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
							'label'	 	=> 'aus_post_api_key', 
							'code' 		=> 'aus_post_api_key',
							'type' 		=> 'text',
							'sort' 		=> 1
						);

		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'aus_post_from_post_code', 
							'code' 		=> 'aus_post_from_post_code',
							'type' 		=> 'text',
							'value' 	=> '2671',
							'descr'		=> 'Enter the ship from postal code.',
							'sort' 		=> 2
						);

		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'aus_post_height',
							'code' 		=> 'aus_post_height',
							'type' 		=> 'text',
							'value' 	=> '',
							'descr'		=> 'Enter the box height in centimeters.',
							'sort' 		=> 3
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'aus_post_width',
							'code' 		=> 'aus_post_width',
							'type' 		=> 'text',
							'value' 	=> '',
							'descr'		=> 'Enter the box width in centimeters.',
							'sort' 		=> 3
						);
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'aus_post_length',
							'code' 		=> 'aus_post_length',
							'type' 		=> 'text',
							'value' 	=> '',
							'descr'		=> 'Enter the box length in centimeters.',
							'sort' 		=> 3
						);

		foreach($data as $d){
			$this->EE->db->insert('br_config_data',$d);
		}
		return true;
	}
	
	
	function _get_api($url,$key)
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array('AUTH-KEY: '.$key));
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
	
	// Sort by rate key. 
		function _rate_sort($a,$b){
			return ($a["rate"] > $b["rate"]) ? +1 : -1;
		}
}
