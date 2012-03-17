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

class Search_model extends CI_Model {

	function get_search_results($start_date='',$end_date='',$limit='')
	{
		$this->load->model('customer_model');
		$this->db->select('s.*,m.*');
		$this->db->from('br_search s');
		$this->db->join('members m', 's.member_id = m.member_id','left outer');
		// Are there time boundaries
			if($start_date != ''){
				$start_date = date("Y-n-d 00:00:00",strtotime($start_date));
				$this->db->where('created >=',$start_date);
			}
			if($end_date != ''){
				$end_date = date("Y-n-d 23:59:59",strtotime($end_date));
				$this->db->where('created <=',$end_date);
			}	
		$this->db->where('site_id',$this->config->item('site_id'));

		// Run 
		$this->db->order_by('created','desc');
		
		$query = $this->db->get();
		
		// Build the output array
		$i = 0;
		$search = array();
		foreach($query->result_array() as $val){
			if($val["member_id"] != ''){
				$member = $this->customer_model->get_customer_data($val["member_id"]);
			}else{
				$member["custom"] = array();
			}
			$search[$i] = array_merge($member["custom"],$val);	
			$i++;
		}
		if($limit != ''){
			$cap = count($search);
			for($i=$limit;$i<$cap;$i++){
				unset($search[$i]);
			}
		}
		
		//return $query;
		
		return $search;
	}
	
}