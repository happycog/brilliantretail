<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010, Brilliant2.com 			*/
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
	
	var $info = array(
		'name'		=> 'BrilliantRetail Poe',
		'version'	=> '1.0.0'
	);
	
	function Poe_ft()
	{
		parent::EE_Fieldtype();
	}
	
	// --------------------------------------------------------------------
	
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
		$str 	= 	'<textarea name="'.$this->field_name.'" class="replace_ckeditor">'.$data.'</textarea>';
		$str 	.=	$this->_create_js();
		return $str;
	}

	function display_cell($data)
	{
		$this->_set_theme();
		$str 	= 	'<textarea name="'.$this->cell_name.'" class="replace_ckeditor" >'.$data.'</textarea>';
		$str 	.=	$this->_create_js();
		return $str;
	}
	


	

	
	function _create_js(){
		if (isset($this->session->cache['br_poe_js'])){
			return "<script type='text/javascript'>
						$(function(){
							initPoeCkeditorFields();
						});
					</script>";	
		}
		$str = "<script type='text/javascript'>
					$(function(){
						if (typeof CKEDITOR == 'undefined'){
					        window.CKEDITOR_BASEPATH = '".$this->theme."/script/ckeditor/';
					        $.getScript('".$this->theme."/script/ckeditor/ckeditor.js',function(){
						        var checkPoeCkeditor = setInterval(function() {
					            	if (typeof CKEDITOR != 'undefined'){
										initPoeCkeditorFields();
					                	clearInterval(checkPoeCkeditor);
					                }
					        	}, 10);
					        });
					    }else{
					    	initPoeCkeditorFields();
						}
					})
					function initPoeCkeditorFields(){
						var a = $('.replace_ckeditor');
						if (a.length > 0){
							$.each(a,function(index,value){
								CKEDITOR.replace($(value).attr('name'),	{
									toolbar :
									[
								        ['Paste','PasteText','PasteFromWord'],
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
									    ['Source'] 
									]
								});
								$(value).removeClass('replace_ckeditor');
							});
							clearTimeout(setCKEditor);
						}else{
							clearInterval(setCKEditor);
							var setCKEditor = setTimeout(function() {
						    	if (typeof CKEDITOR != 'undefined'){
						        	initPoeCkeditorFields();
						        }
					        }, 10);
						}
					}
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