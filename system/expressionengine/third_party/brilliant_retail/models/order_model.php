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

class Order_model extends CI_Model {

	function get_order($order_id){
		$this->load->model('customer_model');
		$order = array();
		
		// Seems a little odd but we are going to get the order address information 
		// first. This is so we can use the billing address as the static information 
		// if the member_id no longer exists
		
			$this->db->select('*');
			$this->db->from('br_order_address');
			$this->db->where('order_id',$order_id);
			$query = $this->db->get();
			$i = 0;
			foreach($query->result_array() as $val){
				$order['address'][$i] = $val;	
				$i++;
			}
		
		$this->db->select('o.*');
		$this->db->from('br_order o');
		$this->db->where('o.order_id = '.$order_id);
		$query = $this->db->get();
		foreach($query->result_array() as $val){
			$member = $this->customer_model->get_customer_profile($val["member_id"]);
			$order = array_merge($order,$val);
			if($member){
				$order["member"] = $member;
			}else{
				// The member_id no longer exists so lets 
				// do some static magic
					$order["member"] = array(
											'br_fname' 			=> $order['address'][0]['billing_fname'],
											'br_lname' 			=> $order['address'][0]['billing_lname'],
											'email'				=> '', 
											'photo_filename' 	=> '' 
											);
			}
		}

		$this->db->select('	oi.*,
							opts.options as opts,
							opts.order_item_option_id');
		$this->db->from('br_order_item oi');
		$this->db->where('oi.order_id = '.$order_id);
		$this->db->join('br_order_options opts', 
						'opts.order_id = oi.order_id AND opts.order_item_id = oi.order_item_id', 'left');
		$query = $this->db->get();
		$i = 0;
		foreach($query->result_array() as $val){
			$order['items'][$i] = $val;	
			$i++;
		}
		// Get payment info
			$this->db->select('*');
			$this->db->from('br_order_payment');
			$this->db->where('order_id',$order_id);
			$query = $this->db->get();
			$i = 0;
			$order["payment"] = array(); 
			foreach($query->result_array() as $val){
				$order["payment"][$i] = $val;
				$i++;
			}		

		// Get shipping info
			$this->db->select('*');
			$this->db->from('br_order_ship');
			$this->db->where('order_id',$order_id);
			$query = $this->db->get();
			$i = 0;
			$order["shipment"] = array();
			foreach($query->result_array() as $val){
				$order["shipment"][$i] = $val;
				$i++;
			}	
			
		// Order Notes 
			$this->db->select('*') 
					->from('br_order_note') 
					->where('br_order_note.order_id',$order_id)
					->join('members', 'members.member_id = br_order_note.member_id') 
					->order_by('br_order_note.created','desc');
			
			$query = $this->db->get();
			$i = 0;
			$order["notes"][0] = array();
			foreach($query->result_array() as $val){
				$order["notes"][$i] = $val; 
				$order["notes"][$i]["order_created"] = $val["created"]; 
				$i++;
			}	
		return $order;
	}
	function get_order_collection($start_date='',$end_date='',$limit=0,$search='',$offset=0,$sort=0,$dir='',$prefix='exp_'){
		
		// 
			if (isset($this->session->cache['get_order_collection'])){
				$total = $this->session->cache['get_order_collection'];
			}else{
				$sql = "SELECT 
							count(order_id) as cnt 
						FROM 
							".$prefix."br_order 
						WHERE 
							status_id >= 0";
				$query = $this->db->query($sql);
				$rst = $query->result_array();
				$total = $rst[0]["cnt"];
				$this->session->cache['get_order_collection'] = $total;
			}
		
		
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
						o.order_id,
						o.created, 
						CONCAT(d.".$fl_fname.", ' ', d.".$fl_lname.") as customer,  
						CONCAT(a.billing_fname, ' ', a.billing_lname) as billing_customer, 
						(o.base + o.shipping + o.tax - o.discount) as total,
						o.status_id,
						o.base,
						o.tax,
						o.shipping,
						o.discount,
						o.member_id  
					FROM 
						".$prefix."br_order_address a,  
						".$prefix."br_order o  
					LEFT JOIN
						".$prefix."members m  
							ON 
						o.member_id = m.member_id 
					LEFT JOIN					
						".$prefix."member_data d 
							ON 
						o.member_id = d.member_id 
					WHERE 
						o.site_id = ".$this->config->item('site_id')." 
					AND 
						o.status_id >= 0 
					AND 
						o.order_id = a.order_id ";
			
			if($start_date != ''){
				$start_date = date("U",strtotime(date("Y-n-d 00:00:00",strtotime($start_date))));
				$sql .= " AND o.created >= '".$start_date."' ";
			}
			if($end_date != ''){
				$end_date = date("U",strtotime(date("Y-n-d 23:59:59",strtotime($end_date))));
				$sql .= " AND o.created <= '".$end_date."' ";
			}
			
			if($search != ''){
				$sql .= "AND (	m.email LIKE '%".$search."%' 
									|| 
								o.order_id LIKE '%".$search."%' 
									|| 
								d.".$fl_fname." LIKE '%".$search."%' 
									|| 
								d.".$fl_lname." LIKE '%".$search."%'
									||
								a.billing_fname LIKE '%".$search."%' 
									|| 
								a.billing_lname  LIKE '%".$search."%' )";	
			}
			
			$sql .= " ORDER BY ".($sort+1)." ".$dir;

			if($limit != 0){
				$sql .= " LIMIT ".$offset.",".$limit;
			}

		// Run the sql
			$query = $this->db->query($sql);
			$rst = $query->result_array();
			$orders = array(
								"total"		=> $total,
								"results" 	=> $rst
							);

		// Get the total without LIMIT restrictions
			$query = $this->db->query("SELECT FOUND_ROWS() as dTotal");
			$rst = $query->result_array();
			$orders["displayTotal"] = $rst[0]["dTotal"];
			
			#echo '<pre>';var_dump($orders);echo '<pre>';
			
		// Get the count of ALL 
			return $orders;
	}
	
	function get_download_collection($start_date='',$end_date='',$limit=''){
		$this->load->model('customer_model');
		$this->db->select('o.order_id,o.status_id,m.email,o.created,m.member_id,d.cnt,d.license');
		$this->db->from('br_order_download d');
		$this->db->join('br_order o', 'd.order_id = o.order_id');
		$this->db->join('members m', 'o.member_id = m.member_id');
		// Are there time boundaries
			if($start_date != ''){
				$start_date = date("U",strtotime(date("Y-n-d 00:00:00",strtotime($start_date))));
				$this->db->where('o.created >=',$start_date);
			}
			if($end_date != ''){
				$end_date = date("U",strtotime(date("Y-n-d 23:59:59",strtotime($end_date))));
				$this->db->where('o.created <=',$end_date);
			}
		// Run 
		$this->db->where('site_id =',$this->config->item('site_id'));
		$this->db->where('status_id >=',0);
		$this->db->order_by('o.created','asc');
		$query = $this->db->get();
		
		// Build the output array
		$i = 0;
		$orders = array();
		foreach($query->result_array() as $val){
			$member = $this->customer_model->get_customer_data($val["member_id"]);
			$orders[$i] = array_merge($member["custom"],$val);	
			$i++;
		}
		if($limit != ''){
			$cap = count($orders);
			for($i=$limit;$i<$cap;$i++){
				unset($orders[$i]);
			}
		}
		
		return $orders;
	}
	
	function get_order_coupons($start_date='',$end_date='',$limit=''){
		$this->load->model('customer_model');
		$this->db->select('o.*,m.*');
		$this->db->from('br_order o');
		$this->db->join('members m', 'o.member_id = m.member_id');
		// Are there time boundaries
			if($start_date != ''){
				$start_date = date("U",strtotime(date("Y-n-d 00:00:00",strtotime($start_date))));
				$this->db->where('created >=',$start_date);
			}
			if($end_date != ''){
				$end_date = date("U",strtotime(date("Y-n-d 23:59:59",strtotime($end_date))));
				$this->db->where('created <=',$end_date);
			}
		// Run 
		$this->db->where('site_id =',$this->config->item('site_id'));
		$this->db->where('status_id >=',0);
		$this->db->where('coupon_code <>','');
		$this->db->order_by('created','desc');
		$query = $this->db->get();
		
		// Build the output array
		$i = 0;
		$orders = array();
		foreach($query->result_array() as $val){
			$member = $this->customer_model->get_customer_data($val["member_id"]);
			$orders[$i] = array_merge($member["custom"],$val);	
			$i++;
		}
		if($limit != ''){
			$cap = count($orders);
			for($i=$limit;$i<$cap;$i++){
				unset($orders[$i]);
			}
		}
		return $orders;
	}
	
	function get_best_products($start_date='',$end_date='',$limit=''){
		$this->db->select('product_id,count(i.product_id)*quantity AS qty, title, SUM((i.price-i.discount)*i.quantity) AS total_sales');
		$this->db->from('br_order_item i');
		$this->db->join("br_order o","o.order_id = i.order_id");
		
		if($start_date != ''){
				$start_date = date("Y-n-d 00:00:00",strtotime($start_date));
				$this->db->where('i.created >=',$start_date);
			}
			if($end_date != ''){
				$end_date = date("Y-n-d 23:59:59",strtotime($end_date));
				$this->db->where('i.created <=',$end_date);
			}
		$this->db->where('o.status_id >=','1');
		$this->db->where('o.site_id =',$this->config->item('site_id'));
		$this->db->group_by('i.product_id');
		$this->db->order_by('qty','desc');
		
		$query = $this->db->get();
		
		$i = 0;
		$products = array();
		foreach($query->result_array() as $val){
			$products[$i] = array_merge($val);	
			$i++;
		}
		if($limit != ''){
			$cap = count($products);
			for($i=$limit;$i<$cap;$i++){
				unset($products[$i]);
			}
		}
		return $products;
	}

	function get_order_by_member($member_id,$order_id=''){
		$orders = array();
		$this->db->from('br_order')
				->order_by('created','desc')
				->where('member_id',$member_id); 
		if($order_id != ''){
			$this->db->where('order_id',$order_id); 
		}
		$this->db->where('site_id =',$this->config->item('site_id'));
		$this->db->where('status_id >=',0); 
		$query = $this->db->get();
		foreach ($query->result_array() as $row){
			$orders[] = $this->get_order($row["order_id"]);
		}
		return $orders;
	}
	
	function get_downloads_by_member($member_id,$hash=''){
		$downloads = array();
		$this->db->select('	o.*,
							d.*, 
							i.title')
				->from('br_order o')
				->join('br_order_download d','o.order_id = d.order_id')
				->join('br_order_item i','i.order_id = d.order_id')
				->where('o.site_id =',$this->config->item('site_id'))
				->where('o.member_id',$member_id)
				->where('o.status_id >=',1); // Canceled = 0 so don't show it. 
		if($hash != ''){
			$this->db->where("md5(d.order_download_id)",$hash);
		}
		$this->db->group_by("d.order_download_id");
		
		$this->db->order_by('o.created','desc'); 
		$query = $this->db->get();
		$tmp = array();
		$file = array();
		foreach ($query->result_array() as $row){
			$file = $this->_get_download_file($row);
			foreach($row as $key => $val){
				$tmp[$key] = $val;
			}
			// We want the purchase version and the current version
			$tmp["purchase_version"] = $tmp["download_version"];
			$tmp = array_merge($tmp,$file);
			$downloads[] = $tmp;
		}
		return $downloads;
	}
	
	function update_downloads_by_member($member_id,$order_download_id,$data){
		$this->db->where('order_download_id',$order_download_id)
				->update('br_order_download',$data);
		return true;
	}
	
	function create_order($order){
		$this->db->insert('br_order',$order);
		return $this->db->insert_id();
	}
	
	function create_shipment($data){
		$this->db->insert('br_order_ship',$data);
		return $this->db->insert_id();
	}
	
	function create_order_address($address){
		$this->db->insert('br_order_address',$address);
		return $this->db->insert_id();
	}

	function create_order_download($download){
		$this->db->insert('br_order_download',$download);
		return $this->db->insert_id();
	}

	function create_order_payment($payment){
		$this->db->insert('br_order_payment',$payment);
		return $this->db->insert_id();
	}

	function create_order_item($item){
		$this->db->insert('br_order_item',$item);
		return $this->db->insert_id();
	}
	
	function reduce_item_inventory($item){
		// Reduce the Product Inventory 
			// Get the current quantity 
				$query = $this->db->select('quantity')->from('br_product')->where('product_id',$item["product_id"])->get();
				$row = $query->result();
				$quantity = $row[0]->quantity;
	
			// 	New Quantity 
				$quantity =  $quantity - $item["quantity"];
				$data = array('quantity' => $quantity);
				$this->db->where('product_id',$item["product_id"]);
				$this->db->update('br_product',$data);

		// Reduce the Configurable Product Inventory 
			if($item["configurable_id"] != 0){
				// Get the current quantity 
				$query = $this->db->select('qty')->from('br_product_configurable')->where('configurable_id',$item["configurable_id"])->get();
				$row = $query->result();
				$quantity = $row[0]->qty;
	
				// 	New Quantity 
				$quantity =  $quantity - $item["quantity"];
				$data = array('qty' => $quantity);
				$this->db->where('configurable_id',$item["configurable_id"]);
				$this->db->update('br_product_configurable',$data);	
			}
	}
	
	function create_order_note($arr){
		$this->db->insert('br_order_note',$arr);
		return true;	
	}
	
	function remove_order_note($order_note_id){
		$this->db->where('order_note_id',$order_note_id);
		$this->db->delete('br_order_note');
		return true;	
	}
	
	function update_order_status($data){
		$order_id = $data["order_id"];
		unset($data["order_id"]);
		$this->db->where('order_id',$order_id);
		$this->db->update('br_order',$data);
		return true;
	}
	
	function _get_gateway($gid){
		$this->db->where('md5(config_id)',$gid);
		$this->db->from('br_config');
		$query = $this->db->get();
		$row = $query->result_array();
		return $row[0]["code"];
	}
	
	function _get_download_file($item){
		$this->db->where('product_id',$item["product_id"])
					->from('br_product_download')
					->order_by('created',"desc")
					->limit(1);
		$query = $this->db->get();
		$row = $query->result_array();
		$row[0]["order_id"] = $item["order_id"];
		return $row[0];
	}
}