<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2012						*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.0 Beta							*/
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
class Poe_ft extends EE_Fieldtype {

	public $has_array_data = TRUE;
	public $theme = '';

	private $btn_options = array(
									'Paste','PasteText','PasteFromWord','Undo','Redo',
									'Find','Replace','SelectAll','RemoveFormat','Bold',
									'Italic','Underline','Strike','Subscript','Superscript',
									'NumberedList','BulletedList','-','Outdent','Indent',
									'Blockquote','CreateDiv','JustifyLeft','JustifyCenter',
									'JustifyRight','JustifyBlock','Image','Link','Unlink','Anchor',
									'Format','Font','FontSize','TextColor','BGColor','Source'
								);
	var $info = array(
		'name'		=> 'BrilliantRetail Poe',
		'version'	=> '1.0.4'
	);

	function Poe_ft()
	{
		parent::EE_Fieldtype();
	}

	// --------------------------------------------------------------------


	/*
	* Install 
	*/
	function install()
	{
		// Call the install settings method
		$this->_create_config_table();
		return TRUE;
	}


	/**
	 * Display Field on Publish
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	function display_field($data)
	{
		$this->_set_theme();
		$str 	= 	'<textarea id="'.$this->field_name.'" name="'.$this->field_name.'">'.$data.'</textarea>';
		$str 	.=	$this->_create_js($this->field_name,$this->settings["poe_toolbar"]);
		return $str;
	}

	function display_cell($data)
	{
		$this->_set_theme();
		$textarea_id = $this->cell_name;
		$str 		 = '<textarea id="'.$textarea_id.'" name="'.$this->cell_name.'">'.$data.'</textarea>';
		$str 		.=	$this->_create_js($textarea_id,$this->settings["poe_toolbar"]);
		return $str;
	}

/*
* BEGIN SOME UPDATES TO ADD CONFIGURATIONS TO POE 
*/

	function display_global_settings()
	{

		// Not quite ready for prime time 
		#return '';

	    // Get the current settings
	    	$val = array_merge($this->settings, $_POST);

		// Get the theme from BrilliantRetail
			$path = $this->EE->config->item('theme_folder_url').'third_party/brilliant_retail/script/';
			$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$path.'ckeditor/skins/kama/editor.css" />');

		// Get the javascript for the control panel
			$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$path.'poe/poe.js"></script>');

		// 
			$unique = array('selectall','removeformat');				

		$this->EE->db->from('br_poe_config');
		$rst = $this->EE->db->get();
		$form = '	<style type="text/css">
						.poe_highlight {
							opacity: 1;
							border: 1px #ABB7C3 green;
							background : #ABB7C3;
						}
					</style>
					<table class="mainTable" cellpadding="0" cellspacing="0">
					<tr>
						<th colspan="2">
							'.lang('settings').'</th> 
					</tr>';
	    $i = 0;
	    foreach($rst->result() as $row){
			$class = ($i % 2 == 0) ? 'even' : 'odd'; 
			$settings = unserialize(base64_decode($row->settings));
			$list = '<span id="cke_'.$i.'" class="cke_toolbar" role="toolbar">';
			foreach($settings as $s){
				if(count($s) == 1 && $s[0] == '/'){
					$list .= ' 	<div class="cke_break"></div>';
				}else{
					$list .= '	<span id="cke_10" class="cke_toolbar" role="toolbar">
									<span class="cke_toolbar_start"></span>
									<span class="cke_toolgroup" style="background-image:none">';

					foreach($s as $f){
						if($f != '-'){

							$icon = strtolower($f);
							if(in_array($icon,$unique)){
								$first = strtolower(substr($f,0,1));
								$icon = $first.substr($f,1,(strlen($f)-1));
							}

							$list .= '		<span class="cke_button">
											<a class="poe_highlight cke_button_'.$icon.'">
												<span class="cke_icon">&nbsp;</span>
												<span id="cke_11_label" class="cke_label">'.$f.'</span>
												<input type="hidden" value="y" name="poe_'.$f.'" />
											</a>
										</span>';

						}
					}

					$list .= '		</span>
									<span class="cke_toolbar_end"></span>
								</span>';
				}
			}
			$list .= '</span>';

			$form .= '	<tr class="'.$class.'">
							<td>
								<label>'.$row->title.'</label></td>
							<td class="cke_skin_kama">
								<table>
									<tr>
										<td style="background-color:#ABB7C3;width:500px;">
											Toolbar</td>
									</tr>
									<tr>
										<td class="cke_top" style="background-color:#FFF">
											<div class="cke_toolbox">
												'.$list.'
											</div>
										</td>
									</tr>
								</table>
								<div style="clear:both"><!-- !--></div></td>
						</tr>';	
			$i++;					
		}		
	 	$form .= '</table>';
		return $form;
	}

	function save_global_settings()
	{
		return array_merge($this->settings, $_POST);
	}

	function display_settings($data)
	{
		$toolbar = isset($data["poe_toolbar"]) ? $data["poe_toolbar"] : 1;
		$opt1 = $opt2 = '';
		if($toolbar == 1)
		{ 
			$opt1 = 'selected';
		}else{
			$opt2 = 'selected';
		}
		$sel = '<select name="poe_toolbar">
					<option value="1" '.$opt1.'>Default</option>
					<option value="2" '.$opt2.'>Basic</option>
				<select>';
		$this->EE->table->add_row('Toolbar',$sel);
	}

	function save_settings()
	{
		return array(
	        'poe_toolbar'  => $this->EE->input->post('poe_toolbar')
	    );
	}

	function display_cell_settings($data)
	{
		$toolbar = isset($data["poe_toolbar"]) ? $data["poe_toolbar"] : 1;
		$opt1 = $opt2 = '';
		if($toolbar == 1)
		{ 
			$opt1 = 'selected';
		}else{
			$opt2 = 'selected';
		}
		$sel = '<select name="poe_toolbar">
					<option value="1" '.$opt1.'>Default</option>
					<option value="2" '.$opt2.'>Basic</option>
				<select>';
		return array(
			array(lang('toolbar'), $sel)
		);
	}

	function save_cell_settings($data)
	{
		return $data;
	}	

	function update($from){
		// If we don't have a configuration table then things are strange!	
			if (! $this->EE->db->table_exists('br_poe_config')){
				$this->_create_config_table();
			}
	}

	function _create_config_table(){
		$this->EE->load->dbforge();
		$this->EE->dbforge->add_field(array(
			'config_id' 	=> array(	
										'constraint' 		=> 10, 
										'unsigned' 			=> TRUE,
										'type' 				=> 'int', 
										'auto_increment' 	=> TRUE
									),
			'title' 		=> array(
										'type' => 'varchar', 
										'constraint' => 50
									),
			'settings'    	=> array(
										'type' => 'text'
									)
		));
		$this->EE->dbforge->add_key('config_id', TRUE);
		$this->EE->dbforge->create_table('br_poe_config');

		// Default Settings
			$data[]	= array(
								'title' 	=> 'Default', 
								'settings' 	=> 'YToxMjp7aTowO2E6Mzp7aTowO3M6NToiUGFzdGUiO2k6MTtzOjk6IlBhc3RlVGV4dCI7aToyO3M6MTM6IlBhc3RlRnJvbVdvcmQiO31pOjE7YTo4OntpOjA7czo0OiJVbmRvIjtpOjE7czo0OiJSZWRvIjtpOjI7czoxOiItIjtpOjM7czo0OiJGaW5kIjtpOjQ7czo3OiJSZXBsYWNlIjtpOjU7czoxOiItIjtpOjY7czo5OiJTZWxlY3RBbGwiO2k6NztzOjEyOiJSZW1vdmVGb3JtYXQiO31pOjI7YTozOntpOjA7czo0OiJCb2xkIjtpOjE7czo2OiJJdGFsaWMiO2k6MjtzOjk6IlVuZGVybGluZSI7fWk6MzthOjQ6e2k6MDtzOjY6IlN0cmlrZSI7aToxO3M6MToiLSI7aToyO3M6OToiU3Vic2NyaXB0IjtpOjM7czoxMToiU3VwZXJzY3JpcHQiO31pOjQ7YToxOntpOjA7czoxOiIvIjt9aTo1O2E6Nzp7aTowO3M6MTI6Ik51bWJlcmVkTGlzdCI7aToxO3M6MTI6IkJ1bGxldGVkTGlzdCI7aToyO3M6MToiLSI7aTozO3M6NzoiT3V0ZGVudCI7aTo0O3M6NjoiSW5kZW50IjtpOjU7czoxMDoiQmxvY2txdW90ZSI7aTo2O3M6OToiQ3JlYXRlRGl2Ijt9aTo2O2E6NDp7aTowO3M6MTE6Ikp1c3RpZnlMZWZ0IjtpOjE7czoxMzoiSnVzdGlmeUNlbnRlciI7aToyO3M6MTI6Ikp1c3RpZnlSaWdodCI7aTozO3M6MTI6Ikp1c3RpZnlCbG9jayI7fWk6NzthOjQ6e2k6MDtzOjU6IkltYWdlIjtpOjE7czo0OiJMaW5rIjtpOjI7czo2OiJVbmxpbmsiO2k6MztzOjY6IkFuY2hvciI7fWk6ODthOjE6e2k6MDtzOjE6Ii8iO31pOjk7YTozOntpOjA7czo2OiJGb3JtYXQiO2k6MTtzOjQ6IkZvbnQiO2k6MjtzOjg6IkZvbnRTaXplIjt9aToxMDthOjI6e2k6MDtzOjk6IlRleHRDb2xvciI7aToxO3M6NzoiQkdDb2xvciI7fWk6MTE7YToxOntpOjA7czo2OiJTb3VyY2UiO319'
							);
		// Basic Settings 
			$data[]	= array(
								'title' 	=> 'Basic',
								'settings' 	=> 'YTozOntpOjA7YTozOntpOjA7czo0OiJCb2xkIjtpOjE7czo2OiJJdGFsaWMiO2k6MjtzOjk6IlVuZGVybGluZSI7fWk6MTthOjI6e2k6MDtzOjEyOiJOdW1iZXJlZExpc3QiO2k6MTtzOjEyOiJCdWxsZXRlZExpc3QiO31pOjI7YTo0OntpOjA7czoxMToiSnVzdGlmeUxlZnQiO2k6MTtzOjEzOiJKdXN0aWZ5Q2VudGVyIjtpOjI7czoxMjoiSnVzdGlmeVJpZ2h0IjtpOjM7czoxMjoiSnVzdGlmeUJsb2NrIjt9fQ==' 
							);
		foreach($data as $d){
			$this->EE->db->insert('br_poe_config',$d);
		}		
	}
/*
* END UPDATES
*/

	function _create_js($textarea,$toolbar=1){
		$str = '';
		if ( ! isset($this->session->cache['br_poe_js'])){
			$str = "	<script type='text/javascript'>
							window.CKEDITOR_BASEPATH = '".$this->theme."/script/ckeditor/';
						</script>
						<script type='text/javascript' src='".$this->theme."/script/ckeditor/ckeditor.js'></script>";	
			$this->session->cache['br_poe_js'] = TRUE;
		}

		if($toolbar == 1){
			$config = "	['Paste','PasteText','PasteFromWord'],
					    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
					    ['Bold','Italic','Underline'], 
					    ['Strike','-','Subscript','Superscript'],
					    '/',
					    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
					    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					    ['Image','Link','Unlink','Anchor'],
					    '/',
					    ['Format','Font','FontSize'],
					    ['TextColor','BGColor'],
					    ['Source']";
		}else{
			$config = "	['Bold','Italic','Underline'], 
					    ['NumberedList','BulletedList'],
					    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']";
		}

		$str .= "<script type='text/javascript'>
					$(function(){
						CKEDITOR.replace('".$textarea."',{
									toolbar :
									[
								    ".$config." 
									]
								});
					});
				</script>";	

		$this->session->cache['br_poe_js'] = TRUE;

		return $str;		
	}
	// --------------------------------------------------------------------

	/**
	 * Replace tag
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		return $data;
	}

	function save($data)
	{
		return $data;
	}

	private function _set_theme(){
		if($this->theme == ''){
			$BR = new Brilliant_retail_mcp();
			$this->theme = base_url().'themes/third_party/brilliant_retail';
		}
	}
}