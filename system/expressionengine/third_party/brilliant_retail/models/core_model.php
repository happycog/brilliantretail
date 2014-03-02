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

class Core_model extends CI_Model {

	protected $EE;
	
	function __construct(){
		parent::__construct();
		
		$this->EE =& get_instance();
		$this->load->helper('brilliant_retail');
	}

	function get_config(){

		// Container for configuration info
			$arr = array();
		
		// Try to get the configuration data 
		// from a cached config file
		// 
		// Added a disable option in 1.2.1 - dpd
			$disable_cache = ($this->config->item('br_disable_system_cache') === TRUE) ? 1 : 0;
			if($disable_cache == 0){
				if($str=read_from_cache('config')){
					$arr = unserialize($str);
					return $arr;
				}
			}
			
		// Get the store config 
			$this->EE->db->select('	s.*, 
								c.code as currency,
								c.currency_id,
								c.marker as currency_marker')
					
					->from('br_store s')
					->join('br_currencies c','c.currency_id = s.currency_id');	
			$query = $this->EE->db->get();
			foreach ($query->result_array() as $row){
				$arr["store"][$row["site_id"]] = $row;
			}

		// Get the config data for each item

			$this->EE->db->select('*')->from('br_config_data')->order_by('sort');
			$query = $this->EE->db->get();
			foreach ($query->result_array() as $row){
				$data[$row['config_id']][] = $row;
			}
			
		// Get the items

			$this->EE->db->select('*')->from('br_config')->order_by('sort');
			$query = $this->EE->db->get();
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
		$this->EE->db->insert('br_config',$data); 
		return $this->EE->db->insert_id();
	}
	
	function module_update($data){
		
		// Need to update the label first
			if(isset($data["label"])){
				$this->EE->db->update(	
									'br_config',
									array(
											'sort' 		=> round($data["sort"] * 1),
											'label' 	=> $data["label"],
											'enabled' 	=> $data["enabled"],
											'groups'    => isset($data["groups"]) ? $data["groups"] : 0
										), 
									"config_id = ".$data["config_id"]
								);
				unset($data["sort"]);
				unset($data["label"]);
				unset($data["enabled"]);
			}
			
		// We are going to build an array of all config elements so we can 
		// handle if the user submits no value for a checkbox for instance. 
			$this->EE->db->from('br_config_data')->where('config_id',$data["config_id"]); 
			$qry = $this->EE->db->get();
			foreach($qry->result_array() as $rst)
			{
				$list[$rst["config_data_id"]] = TRUE;
			}
		
		// Now update the params				
			foreach($data as $key => $val){
				if(strpos($key,'cAttribute_') !== false){ 
					// Custom Attributes
					$config_data_id = str_replace('0_cAttribute_','',$key);
					$this->EE->db->update('br_config_data',array('value' => $val), "config_data_id = ".$config_data_id);
					unset($list[$config_data_id]);
				}
				if(strpos($key,'cAttributePW_') !== false){ 
					// Custom Attributes
					$config_data_id = str_replace('0_cAttributePW_','',$key);
					if(trim($val) != '************************'){
						$this->EE->db->update('br_config_data',array('value' => $val), "config_data_id = ".$config_data_id);
					}
					unset($list[$config_data_id]);
				}
			}
		
		// If there are any empty settings left we need to clear them
			if(isset($list)){
				foreach($list as $key => $val)
				{
					$this->EE->db->update('br_config_data',array('value' => ''), "config_data_id = ".$key);
				}	
			}
	}
	
	function module_update_version($version,$config_id){
		$this->EE->db->update('br_config',array('version'=>$version),"config_id = ".$config_id);
	}
	
	function module_remove($config_id){
		// Remove the primary entry
			$this->EE->db->delete('br_config', array('config_id' => $config_id)); 
	
		// Remove any custom configuration 
		// data that the module has
			$this->EE->db->delete('br_config_data', array('config_id' => $config_id)); 
	}
	
	function get_aid($class,$method){
		$this->EE->db->select('action_id')
				->from('actions')
				->where('class',$class)
				->where('method',$method);
		$query = $this->EE->db->get();
		if($query->num_rows() > 0){
		   $row = $query->row(); 
		   return $row->action_id;
		}
		return 0;
	}
	
	function get_sites(){
		$sites = array();
		if(isset($this->EE->session->cache["get_sites"])){
			$sites = $this->EE->session->cache["get_sites"];
		}else{
			$this->EE->db->from('sites');
			$query = $this->EE->db->get();
			foreach($query->result_array() as $row){
			   $sites[$row["site_id"]] = $row;
			}
			$this->EE->session->cache["get_sites"] = $sites;
		}
		return $sites;
	}

	function get_stores(){
		$stores = array();
		if(isset($this->EE->session->cache["get_stores"])){
			$stores = $this->EE->session->cache["get_stores"];
		}else{
			$this->EE->db->from('br_store');
			$query = $this->EE->db->get();
			foreach($query->result_array() as $row){
			   $stores[$row["site_id"]] = $row;
			}
			$this->EE->session->cache["get_stores"] = $stores;
		}
		return $stores;
	}

	function create_store($site_id){
		// Create the store record 
			$sql  = "	INSERT INTO exp_br_store 
						(site_id,channel_id,logo,license,phone,address1,address2,city,state,country,zipcode,fax,currency_id,result_limit,result_per_page,result_paginate,register_group,guest_checkout,media_url,media_dir,meta_title,meta_keywords,meta_descr,subscription_enabled,first_notice,second_notice,third_notice,cancel_subscription,secure_url,cart_url,checkout_url,customer_url,product_url,low_stock)
							VALUES 
						(".$site_id.",'0','logo.png', '', '(888) 555-5555', '1234 First Steet Ln.', '', 'Los Angeles', 'CA', 'USA', '90025', '(888) 555-5555', '1', '96', '12', '5', '5', '1', '/media/','/media/','','','',0,7,14,21,28,'http://".$_SERVER["HTTP_HOST"]."','cart','checkout','customer','product','25')";
			$this->EE->db->query($sql);
		// Create the store system config record
			
			$data = array(
							'title' 	=> 'Status Codes',
							'code' 		=> 'status', 
							'type' 		=> 'system', 
							'site_id' 	=> $site_id,  
							'enabled' 	=> 1 
						);
			$this->EE->db->insert('br_config',$data);
			$config_id = $this->EE->db->insert_id();

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
				$this->EE->db->insert('br_config_data',$data);
			}
		
		return true;
	}

	function exempt_csfr()
	{
		$sql = "UPDATE ".$this->EE->db->dbprefix."actions SET csrf_exempt = 1 WHERE class = 'Brilliant_retail' AND method IN ('gateway_ipn','process_ipn')";
		$this->EE->db->query($sql);
		return true;
	}

}	


