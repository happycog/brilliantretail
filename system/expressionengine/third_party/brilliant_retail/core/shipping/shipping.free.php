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

class Shipping_free extends Brilliant_retail_shipping {
	public $title 	= 'Free Shipping';
	public $label  	= 'Free Shipping';
	public $descr 	= 'Free shipping with a minimum purchase amount';
	public $enabled = true;
	public $version = '1.0';

	function quote($data,$config){
		
		$this->rates = array();
		
		// Did we already setup free shipping with the promtions module? 
			if(isset($_SESSION["discount"]["free_shipping_set"])){ return $this->rates; }

		// No countries configured 
			if(!isset($config["country"])){ return $this->rates; }
		
		$country = unserialize($config["country"]);
		if(!in_array($data["to_country"],$country)){
			return $this->rates;
		}	

		if($data["total"] >= $config["amount"]){
			$this->rates['free'] = array(
											'code' => 'free',
											'rate' => '0.00',
											'label' => $config["label"]
										);
		}

		return $this->rates;
	}
	
	
	function install($config_id){
		
		// Get all countries 
			$countries = $this->EE->product_model->get_countries(0);
			foreach($countries as $c){
				$arr[] = $c["code"].":".$c["title"];
			}
			$cList = join("|",$arr);

		// Build our inputs 
			$data[] = array(
								'config_id' => $config_id, 
								'label'	 	=> 'Countries', 
								'code' 		=> 'country',
								'type' 		=> 'multiselect',
								'value'		=> 'a:1:{i:0;s:2:"US";}',
								'options' 	=> $cList, 
								'descr'		=> 'Select countries where free shipping is available. Control + click to add multiple countries',
								'sort' 		=> 1
							);
			$data[] = array(
								'config_id' => $config_id, 
								'label'	 	=> 'Amount', 
								'code' 		=> 'amount',
								'type' 		=> 'text',
								'value'		=> '0',
								'descr'		=> 'Minimum amount for free shipping',
								'sort' 		=> 2
							);
		// Insert
			foreach($data as $d){
				$this->EE->db->insert('br_config_data',$d);
			}

		return true;
	}
	
	
	function remove($config_id){
		
	}
	
	function update($current = '',$config_id = ''){
		return true;
	}
}