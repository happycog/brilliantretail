<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright © 2010-2013						*/
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
	
	class Product_form 
	{
		public function __construct()
		{
			$this->EE =& get_instance();
			$this->EE->load->library('api'); 
			$this->EE->api->instantiate('channel_fields');
		}
	
		public function select_editor($data){
			
			$ft = 'editor';
			
			$cf = $this->EE->api_channel_fields;
			$cf->ft_paths[$ft] = PATH_THIRD.$ft;
			$cf->field_type = $ft;
			$cf->include_handler($ft);
			$cf->field_types[$ft] = ucwords($ft).'_ft';
			$cf->setup_handler($ft);
			
			$settings = unserialize(base64_decode('YTo3OntzOjY6ImVkaXRvciI7YToyNjp7czoxNToiZWRpdG9yX3NldHRpbmdzIjtzOjEwOiJwcmVkZWZpbmVkIjtzOjE0OiJ1cGxvYWRfc2VydmljZSI7czo1OiJsb2NhbCI7czoyMDoiZmlsZV91cGxvYWRfbG9jYXRpb24iO3M6MToiMCI7czoyMToiaW1hZ2VfdXBsb2FkX2xvY2F0aW9uIjtzOjE6IjAiO3M6MTQ6ImltYWdlX2Jyb3dzaW5nIjtzOjM6InllcyI7czoxMjoiaW1hZ2Vfc3ViZGlyIjtzOjM6InllcyI7czoyOiJzMyI7YTo0OntzOjQ6ImZpbGUiO2E6MTp7czo2OiJidWNrZXQiO3M6MDoiIjt9czo1OiJpbWFnZSI7YToxOntzOjY6ImJ1Y2tldCI7czowOiIiO31zOjE0OiJhd3NfYWNjZXNzX2tleSI7czoxNzoiZGF2aWRAY29kZXNseS5jb20iO3M6MTQ6ImF3c19zZWNyZXRfa2V5IjtzOjk6IkdvbmluZXJzNyI7fXM6NjoiaGVpZ2h0IjtzOjM6IjIwMCI7czo5OiJkaXJlY3Rpb24iO3M6MzoibHRyIjtzOjc6InRvb2xiYXIiO3M6MzoieWVzIjtzOjY6InNvdXJjZSI7czozOiJ5ZXMiO3M6NToiZm9jdXMiO3M6Mjoibm8iO3M6MTA6ImF1dG9yZXNpemUiO3M6MzoieWVzIjtzOjU6ImZpeGVkIjtzOjI6Im5vIjtzOjEyOiJjb252ZXJ0bGlua3MiO3M6MzoieWVzIjtzOjExOiJjb252ZXJ0ZGl2cyI7czozOiJ5ZXMiO3M6Nzoib3ZlcmxheSI7czozOiJ5ZXMiO3M6MTM6Im9ic2VydmVpbWFnZXMiO3M6MzoieWVzIjtzOjM6ImFpciI7czoyOiJubyI7czozOiJ3eW0iO3M6Mjoibm8iO3M6MTg6ImFsbG93ZWR0YWdzX29wdGlvbiI7czo3OiJkZWZhdWx0IjtzOjExOiJhbGxvd2VkdGFncyI7YTowOnt9czoxNDoiZm9ybWF0dGluZ3RhZ3MiO2E6Nzp7aTowO3M6MToicCI7aToxO3M6MTA6ImJsb2NrcXVvdGUiO2k6MjtzOjM6InByZSI7aTozO3M6MjoiaDEiO2k6NDtzOjI6ImgyIjtpOjU7czoyOiJoMyI7aTo2O3M6MjoiaDQiO31zOjg6Imxhbmd1YWdlIjtzOjI6ImVuIjtzOjg6ImNzc19maWxlIjtzOjA6IiI7czoxMToiZWRpdG9yX2NvbmYiO3M6MToiMyI7fXM6MTg6ImZpZWxkX3Nob3dfc21pbGV5cyI7czoxOiJuIjtzOjE5OiJmaWVsZF9zaG93X2dsb3NzYXJ5IjtzOjE6Im4iO3M6MjE6ImZpZWxkX3Nob3dfc3BlbGxjaGVjayI7czoxOiJuIjtzOjI2OiJmaWVsZF9zaG93X2Zvcm1hdHRpbmdfYnRucyI7czoxOiJuIjtzOjI0OiJmaWVsZF9zaG93X2ZpbGVfc2VsZWN0b3IiO3M6MToibiI7czoyMDoiZmllbGRfc2hvd193cml0ZW1vZGUiO3M6MToibiI7fQ=='));
			$cf->field_types[$ft]->settings = $settings;
			/*
			[$ft] = array( 'buttons' => array('html', '|', 'formatting', '|', 'bold', 'italic', '|',
																						'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
																						'image', 'video', 'file', 'link', '|', 'alignleft', 'aligncenter', 'alignright', '|',
																						'horizontalrule'));
			*/
			
			
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