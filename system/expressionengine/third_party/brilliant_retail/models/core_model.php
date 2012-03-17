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

class Core_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->helper('brilliant_retail');
	}

	function get_config(){

		// Container for configuration info
			$arr = array();
		
		// Try to get the configuration data 
		// from a cached config file
			if($str=read_from_cache('config')){
				$arr = unserialize($str);
				return $arr;
			}

		// Get the store config 
			$this->db->select('	s.*, 
								c.code as currency,
								c.currency_id,
								c.marker as currency_marker')
					
					->from('br_store s')
					->join('br_currencies c','c.currency_id = s.currency_id');	
			$query = $this->db->get();
			foreach ($query->result_array() as $row){
				$arr["store"][$row["site_id"]] = $row;
			}

		// Get the config data for each item

			$this->db->select('*')->from('br_config_data')->order_by('sort');
			$query = $this->db->get();
			foreach ($query->result_array() as $row){
				$data[$row['config_id']][] = $row;
			}
			
		// Get the items

			$this->db->select('*')->from('br_config')->order_by('sort');
			$query = $this->db->get();
			foreach ($query->result_array() as $row){
				$arr[$row["type"]][$row["site_id"]][$row["code"]] = $row;
				if(isset($data[$row["config_id"]])){
					$arr[$row["type"]][$row["site_id"]][$row["code"]]["config_data"] = $data[$row["config_id"]];
				}
			}

		// Save the new array to cache
			save_to_cache('config',serialize($arr));
			return $arr;
	}
	
	function module_install($type,$title,$label,$code,$descr,$version = 1.0){
		$data = array(
						'site_id' 	=> $this->config->item('site_id'), 
						'type' 		=> $type, 
			          	'title' 	=> $title,
			          	'label'		=> $label, 
			          	'code' 	 	=> $code,
			          	'version' 	=> $version,
			           	'descr' 	=> $descr,
			           	'enabled' 	=> 1 
			       	);
		$this->db->insert('br_config',$data); 
		return $this->db->insert_id();
	}
	
	function module_update($data){
		// Need to update the label first
			if(isset($data["label"])){
				$this->db->update(	'br_config',
									array(
											'sort' 		=> round($data["sort"] * 1),
											'label' 	=> $data["label"],
											'enabled' 	=> $data["enabled"]), 
											"config_id = ".$data["config_id"]);
				unset($data["sort"]);
				unset($data["label"]);
				unset($data["enabled"]);
			}
			
		// Now update the params				
			foreach($data as $key => $val){
				if(strpos($key,'cAttribute_') !== false){ 
					// Custom Attributes
					$config_data_id = str_replace('0_cAttribute_','',$key);
					$this->db->update('br_config_data',array('value' => $val), "config_data_id = ".$config_data_id);
				}
				if(strpos($key,'cAttributePW_') !== false){ 
					// Custom Attributes
					if($val != '************************'){
						$config_data_id = str_replace('0_cAttributePW_','',$key);
						$this->db->update('br_config_data',array('value' => $val), "config_data_id = ".$config_data_id);
					}
				}
			
			}
	}
	function module_remove($config_id){
		// Remove the primary entry
			$this->db->delete('br_config', array('config_id' => $config_id)); 
	
		// Remove any custom configuration 
		// data that the module has
			$this->db->delete('br_config_data', array('config_id' => $config_id)); 
	}
	
	function get_aid($class,$method){
		$this->db->select('action_id')
				->from('actions')
				->where('class',$class)
				->where('method',$method);
		$query = $this->db->get();
		if($query->num_rows() > 0){
		   $row = $query->row(); 
		   return $row->action_id;
		}
		return 0;
	}
	
	function get_sites(){
		$sites = array();
		if(isset($this->session->cache["get_sites"])){
			$sites = $this->session->cache["get_sites"];
		}else{
			$this->db->from('sites');
			$query = $this->db->get();
			foreach($query->result_array() as $row){
			   $sites[$row["site_id"]] = $row;
			}
			$this->session->cache["get_sites"] = $sites;
		}
		return $sites;
	}

	function get_stores(){
		$stores = array();
		if(isset($this->session->cache["get_stores"])){
			$stores = $this->session->cache["get_stores"];
		}else{
			$this->db->from('br_store');
			$query = $this->db->get();
			foreach($query->result_array() as $row){
			   $stores[$row["site_id"]] = $row;
			}
			$this->session->cache["get_stores"] = $stores;
		}
		return $stores;
	}

	function create_store($site_id){
		// Create the store record 
			$sql  = "	INSERT INTO exp_br_store 
						(site_id,channel_id,logo,license,phone,address1,address2,city,state,country,zipcode,fax,currency_id,result_limit,result_per_page,result_paginate,register_group,guest_checkout,media_url,media_dir,meta_title,meta_keywords,meta_descr,subscription_enabled,first_notice,second_notice,third_notice,cancel_subscription,secure_url,cart_url,checkout_url,customer_url,product_url,low_stock)
							VALUES 
						(".$site_id.",'0','logo.png', '', '(888) 555-5555', '12207 Wilshire Blvd', 'Suite 201', 'Los Angeles', 'CA', 'USA', '90025', '(888) 555-5555', '1', '96', '12', '5', '5', '1', '/media/','/media/','','','',0,7,14,21,28,'http://".$_SERVER["HTTP_HOST"]."','cart','checkout','customer','product','25')";
			$this->db->query($sql);
		// Create the store system config record
			
			$data = array(
							'title' 	=> 'Status Codes',
							'code' 		=> 'status', 
							'type' 		=> 'system', 
							'site_id' 	=> $site_id,  
							'enabled' 	=> 1 
						);
			$this->db->insert('br_config',$data);
			$config_id = $this->db->insert_id();

		// Create the store system config data records 
			$status = array(
								0 => "Canceled",
								1 => "New Order",
								2 => "Pending",
								3 => "Processing",
								4 => "Shipping",
								5 => "Complete"
							);
			foreach($status as $key => $val){
				$data = array(
								'config_id' => $config_id,  
								'label' 	=> $val, 
								'value' 	=> $key 
							);
				$this->db->insert('br_config_data',$data);
			}
		
		return true;
	}
}	
