<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter 								*/
/* 	@copyright	Copyright (c) 2012				 			*/
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
include_once(PATH_THIRD.'brilliant_retail/mcp.brilliant_retail.php');

class Brilliant_retail_ext {
	
	public $settings 		= array();
	public $description		= 'BrilliantRetail Extension';
	public $docs_url		= 'http://www.brilliantretail.com';
	public $name			= 'Brilliant Retail';
	public $settings_exist	= 'n';
	public $version			= '1.0.4.5';
	public $site_id 		= 1;
	public $base_url 		= '';
	public $nav_menu 		= array();
	public $_config 		= array();
	
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
		
		$this->site_id = $this->EE->config->item('site_id');				
		$this->base_url = str_replace('&amp;','&',BASE).'&C=addons_modules&M=show_module_cp&module=brilliant_retail';
	}// ----------------------------------------------------------------------
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array();
		
		$data[] = array(
			'class'		=> __CLASS__,
			'method'	=> 'br_edit_entries_additional_where',
			'hook'		=> 'edit_entries_additional_where',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);
		
		$data[] = array(
			'class'		=> __CLASS__,
			'method'	=> 'br_cp_menu_array',
			'hook'		=> 'cp_menu_array',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);
		
		foreach($data as $d){
			$this->EE->db->insert('extensions', $d);			
		}
	}	

	// ----------------------------------------------------------------------
	
	/**
	 * br_hidden_channel_menu
	 *
	 * @param 
	 * @return 
	 */
	public function br_cp_menu_array($menu)
	{
		$this->EE->lang->loadfile('brilliant_retail');
		$this->EE->load->model('access_model');
		$this->EE->load->model('core_model');
		
		$this->_config = $this->EE->core_model->get_config();

		if ($this->EE->extensions->last_call !== FALSE)
		{
			$menu = $this->EE->extensions->last_call;
		}
		$group_id = $this->EE->session->userdata['group_id'];
		
		if($group_id == 1){
			// We are going to give the super admin access to 
			// all sections
			$f = get_class_methods('Brilliant_retail_mcp');
			foreach($f as $m){
				if(substr($m,0,1) != '_' && $m != 'index'){
					$this->group_access['brilliant_retail'][$m] = $m;
				}
			}
		}else{
			$this->group_access = $this->EE->access_model->get_admin_access($group_id);
		}
		

		$this->_create_admin_menu();
				
		$tmp["br_store"] = $this->nav_menu;
		
		foreach($menu as $key => $val){
			$tmp[$key] = $val;
		}
		$menu = $tmp;
		// Get the channel_titles
		
			$arr = $this->_get_channels();
			$titles = array();
			
			// Only get the titles if we have setup the channels
			if(count($arr) >0){
				$this->EE->db->select('channel_title')
							->from('channels')
							->where_in('channel_id',$arr);
				$qry = $this->EE->db->get();
				foreach($qry->result() as $row){
					$titles[] = $row->channel_title;
				}
			}

		// Loop through and unset any BrilliantRetail Channel 
		// Publish or Edit links
			foreach($menu["content"]["publish"] as $key => $val){
				if(in_array($key,$titles)){
					unset($menu["content"]["publish"][$key]);
					if(is_array($menu["content"]["edit"])){
						if(isset($menu["content"]["edit"][$key])){
							unset($menu["content"]["edit"][$key]);
						}
					}
				}
			}
		return $menu;
	}

	/**
	 * br_hidden_channel_entries
	 *
	 * @param 
	 * @return 
	 */
	public function br_edit_entries_additional_where()
	{
		$arr = $this->_get_channels();
		$filter['channel_id !='] = $arr;
		return $filter;
	}
	
	// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}	
	
	function _get_channels(){
		if (isset($this->session->cache['br_hidden_channel'])){
			$arr = $this->session->cache['br_hidden_channel'];
		}else{
			// Lets Get 
			$qry = $this->EE->db->get('br_store');
			$arr = array();
			foreach($qry->result() as $row){
				if(isset($row->channel_id)){
					$arr[] = $row->channel_id;
				}
			}
			$this->session->cache['br_hidden_channel'] = $arr;
		}
		return $arr;
	}
	
	
	function _create_admin_menu(){
			
	/* Create the primary menu */
		$this->nav_menu["br_dashboard"] 	= $this->base_url.AMP.'method=dashboard';
		$this->nav_menu[] = "----";
					
		if(isset($this->group_access["brilliant_retail"]["customer"])){
			$this->nav_menu["br_customer"]		= $this->base_url.AMP.'method=customer';
		}
		if(isset($this->group_access["brilliant_retail"]["order"])){
			$this->nav_menu["br_order"] 		= $this->base_url.AMP.'method=order';
		}
		if(isset($this->group_access["brilliant_retail"]["product"])){
			$this->nav_menu["br_product"]		= $this->base_url.AMP.'method=product';
		}
		
		if($this->_config["store"][$this->site_id]["subscription_enabled"] == 1){
			if(isset($this->group_access["brilliant_retail"]["subscription"])){
				$this->nav_menu["subscription"] = $this->base_url.AMP.'method=subscription';
			}
		}
	
		if(isset($this->group_access["brilliant_retail"]["promo"])){
			$this->nav_menu["br_promo"]	= $this->base_url.AMP.'method=promo';
		}

		if(isset($this->group_access["brilliant_retail"]["report"])){
			$this->nav_menu["br_report"]	= $this->base_url.AMP.'method=report';
		}
		if(isset($this->group_access["brilliant_retail"]["config"])){
	
			/* Create the submenu for configuration */
				
				$config_subs = array(	'config_attribute',
									 	'config_attributeset',
									 	'config_category',
									 	'config_gateway',
										'config_email',
										'config_permission', 
									 	'config_feeds', 
										'config_shipping',
									 	'config_site', 
									 	'config_tax'
									 );
				foreach($config_subs as $sub){
					if(isset($this->group_access["brilliant_retail"][$sub])){
						$c['br_'.$sub] = $this->base_url.AMP.'method='.$sub;
					}
				}
				if(isset($c)){
					$this->nav_menu[] = "----";
					foreach($c as $key => $val){
						$this->nav_menu["br_config"][$key] = $val;
					}
				}						
				#ksort($this->nav_menu["config"]);
		}
}
	
	// ----------------------------------------------------------------------
}

/* End of file ext.brilliant_retail.php */
/* Location: /system/expressionengine/third_party/brilliant_retail/ext.brilliant_retail.php */