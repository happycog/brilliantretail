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

class Email_model extends CI_Model {

	function get_emails_by_site_id($site_id){
		$this->db->select("*")
				->from("br_email")
				->where("site_id",$site_id);
		$this->db->order_by('title','desc');
		$query = $this->db->get();
		
		$emails = array();
		foreach ($query->result_array() as $row){
			$emails[$row["title"]] = $row;
		}
		return $emails;
	}
	
	function create_email($data){
		$this->db->insert('br_email',$data);
		return $this->db->insert_id();
	}
	
	function get_email($key){
		$this->db->from('br_email')->where('title',$key);
		$this->db->where('site_id',$this->config->item('site_id'));
		$query = $this->db->get();
		$row = $query->result_array();
		return $row[0];
	}
	
	function get_email_by_id($email_id){
		$email = array();
		$this->db->select("*")
				->from("br_email")
				->where("email_id",$email_id);
		$query = $this->db->get();
		foreach($query->result_array() as $row){
			$email = $row;
		}
		return $email;
	}
	
	function update_email($email_id,$data){
		$this->db->where('email_id',$email_id);
		$this->db->update('br_email',$data);
		return true;
	}
}