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

class Order_model extends CI_Model {

	protected $EE;
	
	function __construct()
	{
		$this->EE =& get_instance();
	}

	/* 
	*	Pass in the order_id and optionally if the order note is private
	*/
	
	function get_order($order_id,$private=FALSE){
		$this->EE->load->model('customer_model');
		$order = array();
		
		// Seems a little odd but we are going to get the order address information 
		// first. This is so we can use the billing address as the static information 
		// if the member_id no longer exists
		
			$this->EE->db->select('*');
			$this->EE->db->from('br_order_address');
			$this->EE->db->where('order_id',$order_id);
			$query = $this->EE->db->get();
			$i = 0;
			foreach($query->result_array() as $val){
				$order['address'][$i] = $val;	
				$i++;
			}
		
		$this->EE->db->select('o.*');
		$this->EE->db->from('br_order o');
		$this->EE->db->where('o.order_id = '.$order_id);
		$query = $this->EE->db->get();
		
		// No order
			if($query->num_rows() == 0){
				return FALSE;
			}
		
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

		$this->EE->db->select('	oi.*,
							opts.options as opts,
							opts.order_item_option_id');
		$this->EE->db->from('br_order_item oi');
		$this->EE->db->where('oi.order_id = '.$order_id);
		$this->EE->db->join('br_order_options opts', 
						'opts.order_id = oi.order_id AND opts.order_item_id = oi.order_item_id', 'left');
		$query = $this->EE->db->get();
		$i = 0;
		foreach($query->result_array() as $val){
			$val['subtotal'] = number_format($val['quantity'] * $val['price'],2,'.','');
			$order['items'][$i] = $val;	
			$i++;
		}
		// Get payment info
			$this->EE->db->select('*');
			$this->EE->db->from('br_order_payment');
			$this->EE->db->where('order_id',$order_id);
			$query = $this->EE->db->get();
			$i = 0;
			$order["payment"] = array(); 
			foreach($query->result_array() as $val){
				$order["payment"][$i] = $val;
				$i++;
			}		

		// Get shipping info
			$this->EE->db->select('*');
			$this->EE->db->from('br_order_ship');
			$this->EE->db->where('order_id',$order_id);
			$query = $this->EE->db->get();
			$i = 0;
			$order["shipment"] = array();
			foreach($query->result_array() as $val){
				$order["shipment"][$i] = $val;
				$i++;
			}	
			
		// Order Notes 
			$this->EE->db->select('	br_order_note.*,
								members.group_id,
								members.username,
								members.screen_name') 
					->from('br_order_note') 
					->where('br_order_note.order_id',$order_id);
					
				if($private === FALSE){
					$this->EE->db->where('br_order_note.isprivate',0);
				}
			$this->EE->db->join('members', 'members.member_id = br_order_note.member_id','left') 
					->order_by('br_order_note.created','desc');
			
			$query = $this->EE->db->get();
			$i = 0;
			$order["notes"] = array();
			foreach($query->result_array() as $val){
				$order["notes"][$i] = $val; 
				$order["notes"][$i]["note_created"] = $order["notes"][$i]["order_created"] = $val["created"];
				$i++;
			}	

		return $order;
	}
	function get_order_collection($start_date='',$end_date='',$limit=0,$search='',$offset=0,$sort=0,$dir='desc',$status_id=''){
		
		// 
			if (isset($this->session->cache['get_order_collection'])){
				$total = $this->session->cache['get_order_collection'];
			}else{
				$sql = "SELECT 
							count(order_id) as cnt 
						FROM 
							".$this->EE->db->dbprefix."br_order ";
				if($status_id == '')
				{
					$sql .= "WHERE status_id >= 0";
				}
				else
				{
					$sql .= "WHERE status_id = ".$status_id;
				}
				$query = $this->EE->db->query($sql);
				$rst = $query->result_array();
				$total = $rst[0]["cnt"];
				$this->session->cache['get_order_collection'] = $total;
			}
		
		
			// Get the field_id for br_fname
				if(isset($_SESSION["fl_fname"])){
					$fl_fname = $_SESSION["fl_fname"];
				}else{
					$this->EE->db->where('m_field_name','br_fname')
								->from('member_fields');
					$query = $this->EE->db->get();
					foreach($query->result_array() as $row){
						$fl_fname = 'm_field_id_'.$row["m_field_id"];
					}
					$_SESSION["fl_fname"] = $fl_fname;
				}
			// Get the field_id for br_lname 
				if(isset($_SESSION["fl_lname"])){
					$fl_lname = $_SESSION["fl_lname"];
				}else{
					$this->EE->db->where('m_field_name','br_lname')
								->from('member_fields');
					$query = $this->EE->db->get();
					foreach($query->result_array() as $row){
						$fl_lname = 'm_field_id_'.$row["m_field_id"];
					}
					$_SESSION["fl_lname"] = $fl_lname;
				}
								
			// Create a SQL statement.
			// DPD _ Dont reorder the fields in the select 
			// statement. They correspond to the sort column
			// sent in the ajax call. 			

			$sql = "SELECT 
						SQL_CALC_FOUND_ROWS 
						o.order_id,
						o.created, 
						CONCAT(d.".$fl_fname.", ' ', d.".$fl_lname.") as customer,  
						(o.base + o.shipping + o.tax - o.discount) as total,
						SUM(p.amount) as payment, 
						((o.base + o.shipping + o.tax - o.discount) - SUM(p.amount)) as balance,
						o.status_id,
						CONCAT(a.billing_fname, ' ', a.billing_lname) as billing_customer, 
						o.base,
						o.tax,
						o.shipping,
						o.discount,
						o.member_id  
					FROM 
						".$this->EE->db->dbprefix."br_order_address a,  
						".$this->EE->db->dbprefix."br_order o  
					INNER JOIN 
						exp_br_order_payment p 
							ON 
						o.order_id = p.order_id 
					LEFT JOIN
						".$this->EE->db->dbprefix."members m  
							ON 
						o.member_id = m.member_id 
					LEFT JOIN					
						".$this->EE->db->dbprefix."member_data d 
							ON 
						o.member_id = d.member_id 
					WHERE 
						o.site_id = ".$this->config->item('site_id');
						
				if($status_id == '')
				{
					$sql .= " AND o.status_id >= 0 ";
				}
				else
				{
					$sql .= " AND o.status_id = ".$status_id;
				}

			$sql .= " AND 
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

			// Set the group by so we get the correct SUM on balance
				$sql .= " GROUP BY o.order_id ";
			
			// Set the order by clause 		
				$sql .= " ORDER BY ".($sort+1)." ".$dir;

			// Know your limits. 
				if($limit != 0){
					$sql .= " LIMIT ".$offset.",".$limit;
				}
			
		// Run the sql
			$query = $this->EE->db->query($sql);
			$rst = $query->result_array();
			$orders = array(
								"total"		=> $total,
								"results" 	=> $rst
							);

		// Get the total without LIMIT restrictions
			$query = $this->EE->db->query("SELECT FOUND_ROWS() as dTotal");
			$rst = $query->result_array();
			$orders["displayTotal"] = $rst[0]["dTotal"];
			
			#echo '<pre>';var_dump($orders);echo '<pre>';
			
		// Get the count of ALL 
			return $orders;
	}
	
	function get_download_collection($start_date='',$end_date='',$limit=''){
		$this->EE->load->model('customer_model');
		$this->EE->db->select('o.order_id,o.status_id,m.email,o.created,m.member_id,d.cnt,d.license');
		$this->EE->db->from('br_order_download d');
		$this->EE->db->join('br_order o', 'd.order_id = o.order_id');
		$this->EE->db->join('members m', 'o.member_id = m.member_id');
		// Are there time boundaries
			if($start_date != ''){
				$start_date = date("U",strtotime(date("Y-n-d 00:00:00",strtotime($start_date))));
				$this->EE->db->where('o.created >=',$start_date);
			}
			if($end_date != ''){
				$end_date = date("U",strtotime(date("Y-n-d 23:59:59",strtotime($end_date))));
				$this->EE->db->where('o.created <=',$end_date);
			}
		// Run 
		$this->EE->db->where('site_id =',$this->config->item('site_id'));
		$this->EE->db->where('status_id >=',0);
		$this->EE->db->order_by('o.created','asc');
		$query = $this->EE->db->get();
		
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
		$this->EE->load->model('customer_model');
		$this->EE->db->select('o.*,m.*');
		$this->EE->db->from('br_order o');
		$this->EE->db->join('members m', 'o.member_id = m.member_id');
		// Are there time boundaries
			if($start_date != ''){
				$start_date = date("U",strtotime(date("Y-n-d 00:00:00",strtotime($start_date))));
				$this->EE->db->where('created >=',$start_date);
			}
			if($end_date != ''){
				$end_date = date("U",strtotime(date("Y-n-d 23:59:59",strtotime($end_date))));
				$this->EE->db->where('created <=',$end_date);
			}
		// Run 
		$this->EE->db->where('site_id =',$this->config->item('site_id'));
		$this->EE->db->where('status_id >=',0);
		$this->EE->db->where('coupon_code <>','');
		$this->EE->db->order_by('created','desc');
		$query = $this->EE->db->get();
		
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
		$this->EE->db->select('product_id,SUM(quantity) AS qty, title, SUM((i.price-i.discount)*i.quantity) AS total_sales');
		$this->EE->db->from('br_order_item i');
		$this->EE->db->join("br_order o","o.order_id = i.order_id");
		
		if($start_date != ''){
				$start_date = date("Y-n-d 00:00:00",strtotime($start_date));
				$this->EE->db->where('i.created >=',$start_date);
			}
			if($end_date != ''){
				$end_date = date("Y-n-d 23:59:59",strtotime($end_date));
				$this->EE->db->where('i.created <=',$end_date);
			}
		$this->EE->db->where('o.status_id >=','1');
		$this->EE->db->where('o.site_id =',$this->config->item('site_id'));
		$this->EE->db->group_by('i.product_id');
		$this->EE->db->order_by('qty','desc');
		
		$query = $this->EE->db->get();
		
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
		$this->EE->db->from('br_order')
				->order_by('created','desc')
				->where('member_id',$member_id); 
		if($order_id != ''){
			$this->EE->db->where('order_id',$order_id); 
		}
		$this->EE->db->where('site_id =',$this->config->item('site_id'));
		$this->EE->db->where('status_id >=',0); 
		$query = $this->EE->db->get();
		foreach ($query->result_array() as $row){
			$orders[] = $this->get_order($row["order_id"]);
		}
		return $orders;
	}
	
	function get_downloads_by_member($member_id,$hash='',$order_id=''){
		$downloads = array();
		$this->EE->db->select('	o.*,
							d.*, 
							i.title')
				->from('br_order o')
				->join('br_order_download d','o.order_id = d.order_id')
				->join('br_order_item i','i.order_id = d.order_id')
				->where('o.site_id =',$this->config->item('site_id'))
				->where('d.member_id',$member_id)
				->where('o.status_id >=',1); // Canceled = 0 so don't show it. 
		if($hash != ''){
			$this->EE->db->where("md5(d.order_download_id)",$hash);
		}
		if($order_id != ''){
			$this->EE->db->where("o.order_id",$order_id);
		}
		$this->EE->db->group_by("d.order_download_id");
		
		$this->EE->db->order_by('o.created','desc'); 
		$query = $this->EE->db->get();
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
		$this->EE->db->where('order_download_id',$order_download_id)
				->update('br_order_download',$data);
		return true;
	}
	
	function update_download_note(){
	
	}

/**
 * Create Order Id
 *
 * Returns the next order id so that we can have it before actually creating the order. 
 * @author David Dexter
 * @package	Order
 */	
	function create_order_id()
	{
	   // Get the next order id
    	   $this->EE->db->select('config_data_id,value')->from('br_config_data')->where('label','Order ID'); 
    	   $qry = $this->EE->db->get();
    	   $result = $qry->row();
    	   $order_id = $result->value;
        
        // Set the order_id to a next number  
            $this->db->where('config_data_id', $result->config_data_id);
            $this->db->update('br_config_data', array('value'=>($order_id+1)));

        // Return the order_id	   
            return $order_id;
	}

/**
 * Create Order
 *
 * Creates the main order record
 * @author David Dexter
 * @package	Order
 */		
	function create_order($order){
		$this->EE->db->insert('br_order',$order);
		return $this->EE->db->insert_id();
	}

/**
 * Create Shipment
 *
 * Creates the main shipment record for an order
 * @author David Dexter
 * @package	Order
 */
	function create_shipment($data){
		$this->EE->db->insert('br_order_ship',$data);
		return $this->EE->db->insert_id();
	}
	
	function create_order_address($address){
		$this->EE->db->insert('br_order_address',$address);
		return $this->EE->db->insert_id();
	}

	function create_order_download($download){
		$this->EE->db->insert('br_order_download',$download);
		return $this->EE->db->insert_id();
	}

	function create_order_payment($payment){
		$this->EE->db->insert('br_order_payment',$payment);
		return $this->EE->db->insert_id();
	}

	function create_order_item($item){
		$this->EE->db->insert('br_order_item',$item);
		return $this->EE->db->insert_id();
	}
	
	function reduce_item_inventory($item){
		// Reduce the Product Inventory 
			// Get the current quantity 
				$query = $this->EE->db->select('quantity,type_id')->from('br_product')->where('product_id',$item["product_id"])->get();
				$row = $query->result();
				$quantity = $row[0]->quantity;
				$type_id =  $row[0]->type_id;
				
			// We only need to reduce the inventory on type_id <= 3 the others are virtual products. 	
				if($type_id <= 3){

					// 	New Quantity 
							$quantity =  $quantity - $item["quantity"];
							$data = array('quantity' => $quantity);
							$this->EE->db->where('product_id',$item["product_id"]);
							$this->EE->db->update('br_product',$data);
			
					// Reduce the Configurable Product Inventory 
						if($item["configurable_id"] != 0){
							// Get the current quantity 
							$query = $this->EE->db->select('qty')->from('br_product_configurable')->where('configurable_id',$item["configurable_id"])->get();
							$row = $query->result();
							$quantity = $row[0]->qty;
				
							// 	New Quantity 
							$quantity =  $quantity - $item["quantity"];
							$data = array('qty' => $quantity);
							$this->EE->db->where('configurable_id',$item["configurable_id"]);
							$this->EE->db->update('br_product_configurable',$data);	
						}

				}
	}
	
	function create_order_note($arr){
		$this->EE->db->insert('br_order_note',$arr);
		return true;	
	}
	
	function remove_order_note($order_note_id){
		$this->EE->db->where('order_note_id',$order_note_id);
		$this->EE->db->delete('br_order_note');
		return true;	
	}
	
	function update_order_status($data){
		$order_id = $data["order_id"];
		unset($data["order_id"]);
		$this->EE->db->where('order_id',$order_id);
		$this->EE->db->update('br_order',$data);
		return true;
	}
	
	function _get_gateway($gid){
		$this->EE->db->where('md5(config_id)',$gid);
		$this->EE->db->from('br_config');
		$query = $this->EE->db->get();
		$row = $query->result_array();
		return $row[0]["code"];
	}
	
	function _get_download_file($item){
		$this->EE->db->where('product_id',$item["product_id"])
					->from('br_product_download')
					->order_by('created',"desc")
					->limit(1);
		$query = $this->EE->db->get();
		$row = $query->result_array();
		$row[0]["order_id"] = $item["order_id"];
		return $row[0];
	}
}