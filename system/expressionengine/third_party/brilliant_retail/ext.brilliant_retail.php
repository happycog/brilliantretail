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

class Brilliant_retail_ext {
	
	public $settings 		= array();
	public $description		= 'BrilliantRetail Extension';
	public $docs_url		= 'http://www.brilliantretail.com';
	public $name			= 'Brilliant Retail';
	public $settings_exist	= 'n';
	public $version			= '1.0.4.5';
	
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
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
			'method'	=> 'br_hidden_channel_entries',
			'hook'		=> 'edit_entries_additional_where',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);
		
		$data[] = array(
			'class'		=> __CLASS__,
			'method'	=> 'br_hidden_channel_menu',
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
	public function br_hidden_channel_menu($menu)
	{
		if ($this->EE->extensions->last_call !== FALSE)
		{
			$menu = $this->EE->extensions->last_call;
		}
		
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
					unset($menu["content"]["edit"][$key]);
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
	public function br_hidden_channel_entries()
	{
		#$arr = $this->_get_channels();
		#$filter['channel_id !='] = $arr;
		#return $filter;
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
	// ----------------------------------------------------------------------
}

/* End of file ext.brilliant_retail.php */
/* Location: /system/expressionengine/third_party/brilliant_retail/ext.brilliant_retail.php */