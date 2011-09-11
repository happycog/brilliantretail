<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010, Brilliant2.com 			*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.0 Beta							*/
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

class Store_model extends CI_Model {

	function get_store_list(){
		$this->db->from('sites');
		$this->db->join('br_store', 'br_store.site_id = sites.site_id');
		$query = $this->db->get();
		$row = array();
		$i=0;
		foreach($query->result_array() as $row){
			$site[$i] = $row;
			$i++;
		}
		return $site;
	}
	
	function build_url_types($type='',$site_id=0)
	{
	if ($type==''){
	return;
	}
	
	$this->db->select($type);
	$this->db->from('br_store');
	$this->db->where('site_id',$site_id);
	
	$query = $this->db->get();
	$row = $query->result_array();
	return $row[0][$type];
	
	}
	
	function get_store_by_id($site_id){
		$this->db->from('sites');
		$this->db->join('br_store', 'br_store.site_id = sites.site_id');
		$this->db->where('br_store.site_id',$site_id);
		$query = $this->db->get();
		$site = $query->result_array();
		return $site;
	}
	
	function update_store($data){
		$this->db->where('site_id',$data["site_id"]);
		$this->db->update('br_store',$data);
		remove_from_cache('config');
		return true;
	}
	
	function update_countries($data){
		// Reset all selected to 0
			$this->db->update('br_zone',array('enabled' => 0));
		// Highlight the new selection
			foreach($data["countries"] as $zone_id){
				$this->db->where('zone_id',$zone_id)
						->update('br_zone',array('enabled' => 1));
			}	
		return true;
	}
	
	function get_currencies($currency_id=''){
		if($currency_id != ''){
			$currency_id = $currency_id * 1;
			$this->db->where('currency_id',$currency_id);
		}
		$this->db->from('br_currencies')
				->order_by('title');
		$query = $this->db->get();
		$currencies = $query->result_array();
		return $currencies;
	}

}