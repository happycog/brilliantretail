<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2014						*/
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

class Promo_model extends CI_Model {

	function get_promo($promo_id = ''){
		$this->db->from('br_promo');
		if($promo_id != ''){
			$this->db->where('promo_id',$promo_id);
		}
		$this->db->where('site_id',$this->config->item('site_id'));
		$query = $this->db->get();
		$coupons = array();
		foreach ($query->result_array() as $row){
			$coupons[] = $row;
		}
		return $coupons;
	}
	function get_promo_by_code($code = ''){
		$this->db->from('br_promo');
		$this->db->where('code',trim($code));
		$this->db->where('site_id',$this->config->item('site_id'));
		$query = $this->db->get();
		// Invalid Code
			if($query->num_rows() != 1){
				return false;
			}
		// Get the result
			$promo = $query->result_array();
			
			if($promo[0]["start_dt"] == '0000-00-00 00:00:00')
				$promo[0]["start_dt"] = null;
			if($promo[0]["end_dt"] == '0000-00-00 00:00:00')
				$promo[0]["end_dt"] = null;
		
			return $promo;
	}

	function get_promo_use_count($code){
		$this->db->from('br_order')
				->where('coupon_code',$code)
				->where('status_id >=',1);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	function create_promo($data){
		unset($data["promo_id"]);
		$data["site_id"] = $this->config->item('site_id');
		$this->db->insert('br_promo',$data);
		$promo_id = $this->db->insert_id();
		return $promo_id;
	}

	function update_promo($data){
		$promo_id = $data["promo_id"];
		unset($data["promo_id"]);
		$this->db->where('promo_id',$promo_id);
		$this->db->update('br_promo',$data);
		return true;
	}
	
	function delete_promo($promo_id){
		$this->db->where('promo_id',$promo_id);
		$this->db->delete('br_promo');
		return true;
	}
}