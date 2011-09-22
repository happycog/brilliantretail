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

class Shipping_rates_matrix extends Brilliant_retail_shipping {
	public $title 	= 'Rates Matrix';
	public $label  	= 'Shipping Rates';
	public $descr 	= 'Rates Matrix';
	public $enabled = true;
	public $version = '1.0';

	function quote($data,$config){
		$this->rates = array();
		$rows = unserialize($config["rates_matrix"]);
		$i = 1;
		if($rows){
			foreach($rows as $key => $val){
				// Have to process all the rules 
				// Keys 0-6 are settings that will effect 
				// matches. All must match to be a valide 
				// rate. 
				$is_good = TRUE;

				// [0] To Country
					if($val[0] != ''){
						$a = explode("|",strtoupper($val[0]));
						$country = strtoupper($data["to_country"]);
						if(!in_array($country,$a)){
							$is_good = FALSE;
						}
					}
				// [1] To State
					if($val[1] != ''){
						$a = explode("|",strtoupper($val[1]));
						$state = strtoupper($data["to_state"]);
						if(!in_array($state,$a)){
							$is_good = FALSE;
						}
					}
				// [2] To Zip / Postal 
					if($val[2] != ''){
						$a = explode("|",strtoupper($val[2]));
						$zip = strtoupper($data["to_zip"]);
						if(!in_array($zip,$a)){
							$is_good = FALSE;
						}
					}
				// [3] From Price
					if($val[3] != ''){
						if($data["total"] < $val[3]){
							$is_good = FALSE;
						}
					}
				// [4] To Price 
					if($val[4] != ''){
						if($data["total"] > $val[4]){
							$is_good = FALSE;
						}
					}
				// [5] From Weight 
					if($val[5] != ''){
						if($data["weight"] < $val[5]){
							$is_good = FALSE;
						}
					}
				// [6] To Weight 
					if($val[6] != ''){
						if($data["weight"] > $val[6]){
							$is_good = FALSE;
						}
					}
				
				if($is_good === TRUE){
					if(trim($val[7]) == ''){
						$val = 0;
					}
					$this->rates[$key] = array(
							'code' => 'RatesMatrix',
							'rate' => $this->_currency_round($val[7]*1),
							'label' => $val[8] 
						);
				}
			}
		}

		return $this->rates;
	}
	
	function install($config_id){
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'rates_matrix', 
							'code' 		=> 'rates_matrix',
							'type' 		=> 'table', 
							'options' 	=> 'country|state|zip_code|from_price|to_price|from_weight|to_weight|rate|label',
							'sort' 		=> 1 
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