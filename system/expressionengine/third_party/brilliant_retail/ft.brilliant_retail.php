<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

include_once('mod.brilliant_retail.php');

class Brilliant_retail_ft extends EE_Fieldtype {
	
	public $has_array_data = TRUE;
	public $switch_cnt = 0;

	var $info = array(
		'name'		=> 'BrilliantRetail',
		'version'	=> '1.0.1.1'
	);
	
	function Brilliant_retail_ft()
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
		$this->EE->lang->loadfile('brilliant_retail');
		$rows = '';
		if($data != ''){
			$this->EE->load->model('product_model');
			if(is_array($data)){
				$products = $data;
			}else{
				$products = explode(",",$data);
			}
			foreach($products as $p){
				$prod = $this->EE->product_model->get_product_basic($p);
				if($prod){
					$rows .= '	<tr>
									<td width="60%" style="width:auto;">
										'.$prod["title"].'</td>
									<td width="10%" style="text-align:right">
										<a class="remove_product" href="#">'.lang('remove').'</a><input type="hidden" value="'.$p.'" name="'.$this->field_name.'[]">
									</td>
								</tr>';
				}
			}
		}
		
		$theme = $this->EE->config->item('theme_folder_url').'third_party/brilliant_retail';
		$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$theme.'/script/jquery.tableDnD.js"></script>');
		$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$theme.'/script/jquery.metadata.js"></script>');
		$this->EE->cp->add_to_head('<link rel="stylesheet" href="'.$theme.'/css/br_fieldtype.css" type="text/css" media="screen" /> ');
		
		$product_search = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->cp->fetch_action_id('Brilliant_retail_mcp', 'product_search');			
		$output = '	<div id="product_opts_'.$this->field_name.'" class="br_fieldtype">
						<div class="br_fieldtype_search">
							<div id="product_clear_'.$this->field_name.'" class="search_clear"><!-- clear !--></div>
							<div class="search"> 
								<input type="text" id="product_search_'.$this->field_name.'" autocomplete="off">
							</div>
							<div id="product_result_'.$this->field_name.'" class="result_div">
								<table id="product_results_'.$this->field_name.'" width="100%" cellpadding="0" cellspacing="0">
									<tr><td colspan="4">'.lang('ft_product_search').'</td></tr>
								</table>
							</div>
						</div>
						<div class="br_fieldtype_results">
							<h4>'.lang('selected_products').'</h4>
							<table id="product_selected_'.$this->field_name.'" width="100%" cellpadding="0" cellspacing="0">
								'.$rows.'
							</table>
						</div>
						<div style="clear:both"><!-- --></div>
					</div>
					<script type="text/javascript">
						$(function(){
							var brSearch = $(\'#product_search_'.$this->field_name.'\');
							var brResult = $(\'#product_results_'.$this->field_name.'\');
							var brClear  = $(\'#product_clear_'.$this->field_name.'\');
							
							brSearch.keyup(function(e){
								var term = $(this).val();
								brClear.show();
								brClear.bind(\'click\',_cleat_search_'.$this->field_name.');

								if(term.length <= 3){
									brResult.find(\'tr\').remove();
									return;
								}
								$.getJSON("'.$product_search.'",{\'type\':\'product\',\'term\':term},
						        	function(data){
						            	$(\'#product_result_'.$this->field_name.'\').slideDown();
						            	brResult.find(\'tr\').remove();
										$.each(data, function(i,item){
						            		$(\'<tr id="product_\'+item.product_id+\'"><td>\'+item.title+\'</td><td width="10%" style="text-align:right"><a href="#" class="add_product {product_id:\'+item.product_id+\'}" >'.lang('add').'</a></td></tr>\').appendTo(brResult);
						            	});
						            	$(\'.add_product\').unbind(\'click\').bind(\'click\',function(){
											_add_product_'.$this->field_name.'($(this).metadata().product_id);
											return false;
										});
						        	}
						        );
							});
							brSearch.keypress( function(e) {
								/* Prevent default */
								if ( e.keyCode == 13 )
								{
									return false;
								}
							});
							_remove_product_'.$this->field_name.'();
						});	
						function _add_product_'.$this->field_name.'(product_id){
							var productSelected = $(\'#product_selected_'.$this->field_name.'\');
							var cnt = $(\'#product_results_'.$this->field_name.' tr\');
							var row = $(\'#product_\'+product_id,\'#product_opts_'.$this->field_name.'\');
							new_row = row.clone();
							new_row.attr({\'id\':\'\'}).find(\'td:eq(3)\').remove();
							new_row.find(\'td:eq(1)\').remove();
							new_row.find(\'td:eq(0)\').attr({\'style\':\'width:auto\',\'width\':\'60%\'});
							$(\'<td width="10%" style="text-align:right"><a href="#" class="remove_product">'.lang('remove').'</a><input type="hidden" name="'.$this->field_name.'[]" value="\'+product_id+\'"></td>\').appendTo(new_row);
							$(new_row).appendTo(productSelected);
							row.remove();
							_remove_product_'.$this->field_name.'();
							return false;
						}
						
						function _remove_product_'.$this->field_name.'(){
							var productSelected = $(\'#product_selected_'.$this->field_name.'\');
							productSelected.tableDnD({onDragStyle:"backgroundColor:blue"});
							$(\'.remove_product\').unbind(\'click\').bind(\'click\',function(){
								$(this).parent().parent().remove();
								return false;
							});
						}
						
						function _cleat_search_'.$this->field_name.'(){
							$(\'#product_results_'.$this->field_name.' tr\').remove();
							$(\'#product_search_'.$this->field_name.'\').val(\'\').focus();
							$(this).hide();
						}
					</script>';
		return $output;
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
		$this->EE->load->helper('form');
		$output = '';
		$action = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_add');
		$pid = explode(",",$data);
		$prod = new Brilliant_retail();
		$i=0;
		$items = array();
		if(isset($pid)){
			foreach($pid as $p){
				$products = $prod->_get_product($p);
				if(isset($products[0]["product_id"])){
					if(!isset($products[0]['attribute'][0])){
						$products[0]['attribute'][0] = array();
					}
					if($products != false){
						$items[$i] = $products[0];
						$f_open = form_open($action,
											array( "id" => "form_".$products[0]["product_id"]), 
											array( "product_id" => $products[0]["product_id"])
											);
						$items[$i]['product_count'] = ($i+1);
						$items[$i]['form_open']  = $f_open;
						$items[$i]['form_close'] = '</form>';
						$i++;
					}
				}
			}
		}
		// if random 
			if(	isset($params["random"]) 
				&& (strtoupper($params["random"]) == "TRUE" || strtoupper($params["random"]) == "YES")){
				//shuffle randomly
					shuffle($items);
			}
			
		// if limit is set unset passed cap
			if(isset($params["limit"])){
				foreach($items as $key => $val){
					if($key >= $params["limit"]){
						unset($items[$key]);
					}	
				}
			}
		$vars[0] = array('items' => $items);
		$output .= $this->EE->TMPL->parse_variables($tagdata, $vars);
		
		$this->switch_cnt = 0;
		$output = preg_replace_callback('/'.LD.'product_switch\s*=\s*([\'\"])([^\1]+)\1'.RD.'/sU', array(&$this, '_parse_switch'), $output);
		return $output;
	}
	
	function save($data)
	{
		$arr = array();
		if(is_array($data)){
			foreach($data as $d){
				$arr[] = $d;
			}
			$list = join(",",$arr);
			return $list; 
		}else{
			return '';
		}
	}
	
	public function _parse_switch($match){
		$options = explode('|', $match[2]);
		$option = $this->switch_cnt % count($options);
		$this->switch_cnt++;
		return $options[$option];
	}
	
	
	
}