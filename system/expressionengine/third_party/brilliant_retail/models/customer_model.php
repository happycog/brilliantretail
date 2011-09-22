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

class Customer_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function get_customers($group_id = '', $limit=0,$offset=0,$search='',$sort,$dir,$prefix='exp_')
	{
		// Get a simple count of all products
			$sql = "SELECT 
						count(member_id) as cnt 
					FROM 
						".$prefix."members";
			$query = $this->db->query($sql);
			$rst = $query->result_array();
			$total = $rst[0]["cnt"];
		
		// Get the field_id for br_fname
			if(isset($_SESSION["fl_fname"])){
				$fl_fname = $_SESSION["fl_fname"];
			}else{
				$this->db->where('m_field_name','br_fname')
							->from('member_fields');
				$query = $this->db->get();
				foreach($query->result_array() as $row){
					$fl_fname = 'm_field_id_'.$row["m_field_id"];
				}
				$_SESSION["fl_fname"] = $fl_fname;
			}
			
		// Get the field_id for br_lname 
			if(isset($_SESSION["fl_lname"])){
				$fl_lname = $_SESSION["fl_lname"];
			}else{
				$this->db->where('m_field_name','br_lname')
							->from('member_fields');
				$query = $this->db->get();
				foreach($query->result_array() as $row){
					$fl_lname = 'm_field_id_'.$row["m_field_id"];
				}
				$_SESSION["fl_lname"] = $fl_lname;
			}
		 
		$sql = "SELECT 
					SQL_CALC_FOUND_ROWS 
					CONCAT(d.".$fl_fname.", ' ', d.".$fl_lname.") as customer,  
					m.email, 
					m.join_date, 
					g.group_title,
					SUM(o.base+o.shipping+o.tax-o.discount) as total, 
					m.member_id, 
					m.screen_name, 
					m.last_visit, 
					m.group_id, 
					m.member_id, 
					m.in_authorlist,
					m.username 
				FROM 
					".$prefix."member_groups g, 
					".$prefix."member_data d, 
					".$prefix."members m 
				LEFT JOIN 
						".$prefix."br_order o 
					ON 
						m.member_id = o.member_id
					AND 
						o.status_id > 0
				WHERE 
					m.group_id = g.group_id 
				AND 
					m.member_id = d.member_id ";

		if ($group_id !== ''){
			$sql .= " AND m.group_id = ".$group_id;
		}
		
		if($search != ''){
			$sql .= " AND (	m.email LIKE '%".$search."%' 
							|| 
							m.username LIKE '%".$search."%'
							|| 
							g.group_title LIKE '%".$search."%'
							|| 
							d.".$fl_fname." LIKE '%".$search."%'
							|| 
							d.".$fl_lname." LIKE '%".$search."%') ";
		}
		
		$sql .= "	GROUP BY 
						m.member_id 
					ORDER BY ".($sort+1)." ".$dir;

		if($limit != 0){
			$sql .= " LIMIT ".$offset.",".$limit;
		}

		$query = $this->db->query($sql);
		$rst = $query->result_array();
			
		$members = array(
							"total"		=> $total,
							"results" 	=> $rst
						);
		
	// Get the total without LIMIT restrictions
		$query = $this->db->query("SELECT FOUND_ROWS() as dTotal");
		$rst = $query->result_array();
		$members["displayTotal"] = $rst[0]["dTotal"];
		return $members;
	}
	
	function create_customer($data,$password='',$group_id=5){
		$member_id = '';
		if($password == ''){
			$password = strtolower(substr(md5(time()),0,8));
		}
		$new['group_id'] 		= $group_id;
		$new['username']		= $data["email"];
		$new['password']		= $this->functions->hash($password);
		$new['ip_address']  	= $this->input->ip_address();
		$new['unique_id']		= $this->functions->random('encrypt');
		$new['join_date']		= $this->localize->now;
		$new['email']			= $data["email"];
		$new['screen_name'] 	= $data["br_fname"].' '.$data["br_lname"];
		$new['url']		 		= '';
		$new['location']	 	= $data["br_billing_country"];
		// overridden below if used as optional fields
		$new['language']	= ($this->config->item('deft_lang')) ? $this->config->item('deft_lang') : 'english';
		$new['time_format'] = ($this->config->item('time_format')) ? $this->config->item('time_format') : 'us';
		$new['timezone']	= ($this->config->item('default_site_timezone') && $this->config->item('default_site_timezone') != '') ? $this->config->item('default_site_timezone') : $this->config->item('server_timezone');
		$new['daylight_savings'] = ($this->config->item('default_site_dst') && $this->config->item('default_site_dst') != '') ? $this->config->item('default_site_dst') : $this->config->item('daylight_savings');		
		
		// Create the member 
			$str = $this->db->insert_string('exp_members', $new);
			$this->db->query($str);
			$member_id = $this->db->insert_id();

		// Add the members_data info 		
			$member_data['member_id'] = $member_id;
			$str = $this->db->insert_string('member_data', $member_data);
			$this->db->query($str);
		
		return $member_id;
	}
	
	function get_customer_data($member_id){
		$member = $this->member_model->get_member_data($member_id);
		foreach((array)$member->row() as $key => $val){
			$customer[$key] = $val;
		}
		$fields = $this->member_model->get_all_member_fields(array(),FALSE);
		$data = $this->member_model->get_all_member_data($member_id);
		foreach($data->result() as $row){
			$f = (array)$row;
		}
		foreach($fields->result() as $row){
			$customer["custom"][$row->m_field_name] = $f['m_field_id_'.$row->m_field_id];
		}
		return $customer;
	}
	
	function get_customer_profile($member_id){
		// First get all the fields
			$fields = $this->_get_custom_fields();
			
		// Now merge in the memeber data
			$customer = array();
			
		// Member Data
		
			$this->db->where('member_id',$member_id)
					->from('members');
			$query = $this->db->get();
			
			$allowed = array("member_id","group_id","username","screen_name","email","url","location","occupation","interests","bday_d","bday_m","bday_y","aol_im","yahoo_im","msn_im","icq","bio","signature","avatar_filename","avatar_width","avatar_height","photo_filename","photo_width","photo_height","sig_img_filename","sig_img_width","sig_img_height","private_messages","accept_messages","last_view_bulletins","last_bulletin_date","ip_address","join_date","last_visit","last_activity","total_entries","total_comments","total_forum_topics","total_forum_posts","last_entry_date","last_comment_date","last_forum_post_date","last_email_date","in_authorlist","accept_admin_email","accept_user_email","notify_by_default","notify_of_pm","display_avatars","display_signatures","parse_smileys","smart_notifications","language","timezone","daylight_savings","localization_is_site_default","time_format","profile_theme","forum_theme");

			foreach ($query->result_array() as $key => $val){
				foreach($val as $k => $v){
					if(in_array($k,$allowed)){
						$customer[$k] = $v;
					}
				}
			}				
			$this->db->where('member_id',$member_id)
					->from('member_data');
			$query = $this->db->get();
			foreach ($query->result_array() as $key => $val){
				foreach($val as $k => $v){
					if(isset($fields[$k])){
						$customer[$fields[$k]] = $v;
					}
				}
			}
		return $customer;
	}
	
	function update_member_profile($member='',$custom='',$member_id){
		if($member != ''){
			// update the member table
				$this->db->where('member_id',$member_id);
				$this->db->update('members',$member);
		}
		if($custom != ''){
			// update the custom fields
				$this->db->where('member_id',$member_id);
				$this->db->update('member_data',$custom);
		}
		return true;
	}
	
	function get_customer_by_email($email){
		$email = trim($email);
		$this->db->where('email',$email)->from('members');
		$query = $this->db->get();
		if($query->num_rows() == 0){
			return false;
		}else{
			$rst = $query->result_array();
			return $rst;
		}
	}
	
	function _get_custom_fields(){
		$fields = array();
		$this->db->from('member_fields');
		$query = $this->db->get();
		foreach ($query->result_array() as $row){
			$fields["m_field_id_".$row["m_field_id"]] = $row["m_field_name"];
		}	
		return $fields;
	}

}