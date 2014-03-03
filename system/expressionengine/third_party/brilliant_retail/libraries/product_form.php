<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright © 2010-2014						*/
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
	
/* 
	Pre-cursor to allowing for selecting other editors in the 
	product detail form. 
*/

	class Product_form 
	{
		public function __construct()
		{
			$this->EE =& get_instance();
			$this->EE->load->library('api'); 
			$this->EE->api->instantiate('channel_fields');
		}
	
		public function select_editor($data){
			
			$ft = 'poe';
			$cf = $this->EE->api_channel_fields;
			$cf->ft_paths[$ft] = PATH_THIRD.$ft;
			$cf->field_type = $ft;
			$cf->include_handler($ft);
			$cf->field_types[$ft] = ucwords($ft).'_ft';
			$cf->setup_handler($ft);
			
			$settings = array('poe_toolbar' => 1);
			
			$cf->field_types[$ft]->settings = $settings;

			$cf->field_types[$ft]->field_name = 'detail';
			
			return $cf->apply('display_field', array('data' => $data));

		}
		
		public function config_editor()
		{
			$cf->apply('display_settings',array('settings' => array( 'buttons' => array('html', '|', 
																						'formatting', '|', 'bold', 'italic', '|',
																						'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
																						'image', 'video', 'file', 'link', '|', 'alignleft', 'aligncenter', 'alignright', '|',
																						'horizontalrule'))));
		
			$this->vars["detail_field"] = $this->EE->table->generate();
			
		}
	
	}