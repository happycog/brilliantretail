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

class Access_model extends CI_Model {
	
	/**
	 * Get Member Groups
	 *
	 * Get list of member groups with BR Access
	 *
	 * @access public
	 * @return array 
	 */
	public function get_member_groups(){
		$this->db->from('member_groups')
			->where('can_access_cp','y')
			->where('can_access_modules','y')
			->where('site_id',$this->config->item('site_id'));
		
		$query = $this->db->get();
		$groups = array();
		$i = 0;
		foreach ($query->result_array() as $row){
			$groups[$row["group_id"]] = $row["group_title"];
		}
		return $groups;
	}

	/**
	 * Get Member Groups
	 *
	 * Returns an array of the methods with group 
	 * Access. 
	 *
	 * @access public
	 * @param int 	Optional Member Group Id
	 * @return array 
	 */
	public function get_admin_access($group_id){
		$this->db->from('br_admin_access')->where('group_id',$group_id);
		$query = $this->db->get();
		$groups = array();
		$i = 0;
		foreach ($query->result_array() as $row){
			$groups[$row["class"]][$row["method"]] = $row["method"];
		}
		return $groups;	
	}
	
	/**
	 * Check Access 
	 *
	 * @access public
	 * @param string 
	 * @return boolean
	 */
	 public function check_admin_access($method){
	 	return TRUE;
	 }

	/**
	 * Delete Group Access 
	 *
	 * @access public
	 * @param string 
	 * @return boolean
	 */
		public function create_admin_access($data){
			$this->db->insert('br_admin_access',$data);
			return TRUE;
		}
	
	/**
	 * Delete Group Access 
	 *
	 * @access public
	 * @param string 
	 * @return boolean
	 */
		public function delete_admin_access($group_id){
			$this->db->delete('br_admin_access', array('group_id' => $group_id));
			return TRUE;
		}
	
	/**
	 * Group Name 
	 *
	 * @access public
	 * @param int
	 * @return string
	 */
	public function get_group_title($group_id){
		$this->db->select('group_title')
				->from('member_groups')
				->where('group_id',$group_id)
				->limit(1);
	
		$query = $this->db->get();
		$row = $query->row(); 
		return $row->group_title;
	}
}