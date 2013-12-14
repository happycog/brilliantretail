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

class Shipping_instore extends Brilliant_retail_shipping {
	public $title 	= 'Pick up Instore';
	public $label  	= 'Pick up Instore';
	public $descr 	= 'Allow the customer to pick up instore (no shipping)';
	public $enabled = true;
	public $version = '1.0';

	function quote($data,$config){
		$this->rates = array();
		
		$this->rates['instore'] = array(
										'code' => 'instore',
										'rate' => $config["amount"],
										'label' => $config["label"]
									);
		return $this->rates;
	}
	
	
	function install($config_id){
		$data[] = array(
							'config_id' => $config_id, 
							'label'	 	=> 'Amount', 
							'code' 		=> 'amount',
							'type' 		=> 'text',
							'value'		=> '0',
							'descr'		=> 'Set the Amount for store Pickup',
							'sort' 		=> 1
						);

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