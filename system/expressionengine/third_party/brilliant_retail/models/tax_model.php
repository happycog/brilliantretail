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

class Tax_model extends CI_Model {

	function get_tax($zone,$state,$zip){
		// Get zone_id for the country
			$countries = $this->get_zone_by_code();
			$zone_id = 0;
			if(isset($countries[strtoupper($zone)])){
				$zone_id = $countries[strtoupper($zone)]["zone_id"];
			}

		// Get state_id for the state		
			$state_id = 0;
			$states = $this->get_state_by_code('',$zone_id);
			if(isset($states[strtoupper($state)])){
				$state_id = $states[strtoupper($state)]["state_id"];
			}

			$where = "(zone_id = ".$zone_id." OR zone_id = 0)";
			$this->db->where($where);
			$where = "(state_id = ".$state_id." OR state_id = 0)";
			$this->db->where($where);
			$this->db->from('br_tax');
			$query = $this->db->get();
			
			$rate = 0;

			if($query->num_rows() == 0){
				return $rate;
			}else{
				foreach($query->result_array() as $rst){
					// Do we have zip code restrictions?
					if($rst["zipcode"] != ''){
						$a = explode("|",$rst["zipcode"]);
						if(in_array(trim($zip),$a)){
							$rate = $rst["rate"];
							// We matched the most granular option lets break 
							// out of the loop. 
							break;
						}
					}else{
						$rate = $rst["rate"];
					}	
				}
			}
			return $rate;
			
	}
	
	function get_tax_by_id($tax_id){
		$this->db->from('br_tax');
		$this->db->where('tax_id',$tax_id);
		$query = $this->db->get();
		$tax = array();
		foreach ($query->result_array() as $row){
			$tax = $row;
		}
		return $tax;
	}
	function list_taxes(){
		$this->db->select('t.tax_id,t.title,t.zipcode,t.rate,z.title as zone,s.title as state');
		$this->db->from('br_tax t');
		$this->db->join('br_zone z', 'z.zone_id = t.zone_id','left');
		$this->db->join('br_state s', 't.state_id = s.state_id','left');
		$this->db->where('t.site_id',$this->config->item('site_id'));
		$this->db->order_by('t.sort');
		$query = $this->db->get();
		$tax = array();
		foreach ($query->result_array() as $row){
			$tax[] = $row;
		}
		return $tax;
	}

	function update_tax($data){
		$tax_id = $data["tax_id"];
		unset($data["tax_id"]);
		$this->db->where('tax_id',$tax_id);
		$this->db->update('br_tax',$data);
		return true;	
	}
	
	function create_tax($data){
		$data["site_id"] = $this->config->item('site_id');
 		$this->db->insert('br_tax',$data);
		$tax_id = $this->db->insert_id();
		return $tax_id;
	}
	
	function delete_tax($tax_id){
		$this->db->where('tax_id',$tax_id);
		$this->db->delete('br_tax');
		return true;
	}
	function get_state(){
		$this->db->from('br_state');
		$this->db->order_by("title", "asc"); 
		$query = $this->db->get();
		$state = array();
		foreach ($query->result_array() as $row){
			$state[] = $row;
		}
		return $state;
	}

	function get_state_by_code($code='',$zone_id=''){
		if($code != ''){
			$this->db->where('code',$code);
		}
		if($zone_id != ''){
			$this->db->where('zone_id',$zone_id);
		}
		$this->db->from('br_state');
		$this->db->order_by("title", "asc"); 
		$query = $this->db->get();
		$state = array();
		foreach ($query->result_array() as $row){
			$state[$row["code"]] = $row;
		}
		return $state;
	}
	
	function get_zone(){
		$this->db->from('br_zone');
		$this->db->order_by("title", "asc"); 
		$query = $this->db->get();
		$zone = array();
		foreach ($query->result_array() as $row){
			$zone[] = $row;
		}
		return $zone;
	}
	function get_zone_by_code($code=''){
		if($code != ''){
			$this->db->where('code',$code);
		}
		$this->db->from('br_zone');
		$this->db->order_by("title", "asc"); 
		$query = $this->db->get();
		$zone = array();
		foreach ($query->result_array() as $row){
			$zone[$row["code"]] = $row;
		}
		return $zone;
	}
}