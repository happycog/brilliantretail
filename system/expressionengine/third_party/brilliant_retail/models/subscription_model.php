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

class Subscription_model extends CI_Model {

	function get_subscription($order_subscription_id){
		$this->db->select('s.*,p.title')
				->from('br_order_subscription s')
				->join('br_product p','p.product_id=s.product_id')
				->where('order_subscription_id',$order_subscription_id);
		$qry = $this->db->get();
		$arr = $qry->result_array();
		return $arr;
	}
	
	function get_subscription_collection($search,$limit=0,$offset=0,$sort,$dir,$prefix='exp_'){
				// Get a simple count of all products
			$sql = "SELECT 
						count(subscription_id) as cnt 
					FROM 
						".$prefix."br_order_subscription 
					WHERE 
						status_id >= 0";
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
								
			// Create a SQL statement
			$sql = "SELECT 
						SQL_CALC_FOUND_ROWS 
						os.order_subscription_id,
						CONCAT(d.".$fl_fname.", ' ', d.".$fl_lname.") as customer,  
						os.created, 
						os.length, 
						os.next_renewal, 
						os.renewal_price, 
						os.status_id,
						os.period,
						d.member_id  
					FROM 
						".$prefix."br_order o, 
						".$prefix."br_order_subscription os,
						".$prefix."member_data d  
					WHERE 
						o.order_id = os.order_id 
					AND 
						o.member_id = d.member_id 
					AND 
						o.site_id = ".$this->config->item('site_id')." 
					AND 
						os.status_id >= 0 ";
			
			if($search != ''){
				$sql .= "AND (	d.".$fl_fname." LIKE '%".$search."%' 
									|| 
								d.".$fl_lname." LIKE '%".$search."%' )";	
			}
			
			$sql .= " ORDER BY ".($sort+1)." ".$dir;

			if($limit != 0){
				$sql .= " LIMIT ".$offset.",".$limit;
			}
			#echo $sql;
		
		// Run the sql
			$query = $this->db->query($sql);
			$rst = $query->result_array();
			$subscriptions = array(
								"total"		=> $total,
								"results" 	=> $rst
							);

		// Get the total without LIMIT restrictions
			$query = $this->db->query("SELECT FOUND_ROWS() as dTotal");
			$rst = $query->result_array();
			$subscriptions["displayTotal"] = $rst[0]["dTotal"];
			
			#echo '<pre>';var_dump($subscriptions);echo '<pre>';
			
		// Get the count of ALL 
			return $subscriptions;
	}
	
	function get_subscription_by_member($member_id,$order_subscription_id=''){
		$subscription = array();
		$this->db->from('br_order_subscription s')
				->order_by('s.created','desc')
				->join('br_order o','o.order_id=s.order_id') 
				->where('o.member_id',$member_id);
		if($order_subscription_id != ''){
			$this->db->where('order_subscription_id',$order_subscription_id); 
		}
		$query = $this->db->get();
		foreach ($query->result_array() as $row){
			$s = $this->get_subscription($row["order_subscription_id"]);
			$subscriptions[] = $s[0];
		}
		return $subscriptions;
	}

	function create_subscription($data){
		$this->db->insert('br_order_subscription',$data);
		return true;
	}

	function update_subscription_status($data){
		$order_id = $data["order_id"];
		unset($data["order_id"]);
		$this->db->where('order_id',$order_id);
		$this->db->update('br_order_subscription',$data);
		return true;
	}	
}