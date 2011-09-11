<?php
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


/********************/
/* Options Tab		*/
/********************/
?>
<table id="optionTable" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="clear:both">
	<tr>
		<th colspan="6">
			<?=lang('br_options')?></th>
	</tr>
	<tr>
		<td colspan="6">
			<span class="button" style="float: right; margin: 0pt;">
				<a class="submit" href="#" id="addoption" style="color:#fff"><?=lang('br_add_option')?></a>
			</span></td>
	</tr>
	<tr>
		<td><?=lang('br_title')?></td>
		<td><?=lang('br_type')?></td>
		<td><?=lang('br_required')?></td>
		<td><?=lang('br_sort')?></td>
		<td><?=lang('delete')?></td>
	</tr>	
</table>
<?
/*
	$this->table->add_row(	array(	'data' => '	'));
	
	// Put in any existing rows 
		$rows = '';
		if(isset($options)){
			$i = 0;
			foreach($options as $key => $val){
				$rows .= _build_table($val,$i);
				$i++;
			}
			$this->table->add_row(array("data" => $rows));
		}else{
			$this->table->add_row('');
		}

*/
// We setup up these clone blocks for the javascript to 
// dynamically create rows. 

	echo '	<div style="display:none" id="optionClone">
				<table width="100%" class="optionTable">
						<tr>
							<td>
								'.form_input(array('name' => 'cOptions_title[]','style' => 'width:150px;','title' => 'title')).'</td>
							<td>
								'.form_dropdown(	'cOptions_type[]', 
													array(	'text' => lang('br_text'),
															'textarea' => lang('br_textarea'),
															'dropdown' => lang('br_dropdown')
														),
													'',
													'title="type" style="width: 100px" class="dropdown"').'
								<div style="display:none">
									<table class="dropOptions" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:10px;">
										<tr>
											<th colspan="4">
												'.lang('br_dropdown_values').'</th>	
										</tr>
										<tr>
											<td colspan="4">
												<span class="button" style="float: right; margin: 0pt;">
													<a class="submit addDropOption" href="#" style="color:#fff">'.lang('br_add_option').'</a>
												</span></td>
										</tr>
										<tr>
											<td>
												<b>'.lang('br_title').'</b></td>
											<td>
												<b>'.lang('br_price').'</b></td>
											<td>	
												&nbsp;</td>
											<td>	
												&nbsp;</td>
										</tr>
									</table></td>
								</div>
							<td>'.form_dropdown(	
													'cOptions_required[]', 
													array(0 => lang('br_no'),1 => lang('br_yes')),
													'',
													'title="required"' 
												).'</td>
							<td class="move_option_row">
								<img src="'.$theme.'images/icon_move.png" /></td>
							<td>
								<a href="#" class="remove">'.lang('delete').'</a></td>
						</tr>
				</table>
				<div id="dropOptions">
					<table>
						<tr>
							<td>
								'.form_input(array('name' => 'cOptions_opt_droptitle[]','style' => 'width:150px;','title' => 'title')).'</td>
							<td>					
								'.form_input(array('name' => 'cOptions_opt_price[]','style' => 'width:70px;', 'title' => 'price','value' => 0)).'
								<input type="hidden" name="cOptions_opt_type[]" title="type" value="fixed" /></td>
							<td class="move_drop_option_row">
								<img src="'.$theme.'images/icon_move.png" /></td> 
							<td>
								<a href="#" class="removeDropOpt">'.lang('delete').'</a></td>
						</tr>
					</table>
				</div>
			</div>';


// Function to build our rows 
	
	function _build_table($data,$option_id){
		
		// Style for dropdown options 
			$dd_style = ($data["type"] != 'dropdown') ? 'style="display:none"' : '';
		
		$tmp =  ' 	<table width="100%" class="optionTable" id="opt_'.$option_id.'">
						<tr>
							<td>
								<a href="#" class="remove" style="float:right;margin:0 0 10px">'.lang('delete').'</a>
								<div style="clear:both"><!-- --></div>

								<label>'.lang('br_option_title').'</label>&nbsp;'.form_input(array('name' => 'cOptions_title['.$option_id.']','style' => 'width:150px;','title' => 'title', 'value' => $data["title"])).'&nbsp;&nbsp;

								<label>'.lang('br_option_type').'</label>&nbsp;'.form_dropdown(	'cOptions_type['.$option_id.']', 
																								array(	'text' => lang('br_text'),
																										'textarea' => lang('br_textarea'),
																										'dropdown' => lang('br_dropdown')
																									),
																								$data["type"],
																								'title="type" style="width: 100px" class="dropdown"').'&nbsp;&nbsp;
								<label>'.lang('br_option_required').'</label>&nbsp;'.form_dropdown('cOptions_required['.$option_id.']', array(	
																																0 => lang('br_no'),
																																1 => lang('br_yes'),
																															),
																															$data["required"],
																															'title="required"' 
																															).'&nbsp;&nbsp;
								<label>'.lang('br_option_sort').'</label>&nbsp;'.form_input(array('name' => 'cOptions_sort['.$option_id.']','style' => 'width:40px;', 'title' => 'sort','value' => $data["sort"])).'
							</td>
						</tr>
						<tr '.$dd_style.'>
							<td>
								<h4>'.lang('br_dropdown_values').' <a href="#" class="addDropOption {add:'.$option_id.'}">'.lang('br_add_value').'</a></h4>
								<div class="dropOptions">';
								
		if(isset($data["opts"])){
			$option_drop_id = 0;
			foreach($data["opts"] as $key => $val){
					$tmp .= '		<div>
										<a href="#" class="removeDropOpt" style="float:right;margin:3px 7px;">'.lang('delete').'</a>
										<input type="hidden" name="cOptions_opt_type['.$option_id.']['.$option_drop_id.']" value="fixed" />&nbsp;&nbsp;
										<label>'.lang('br_option_drop_title').'</label>&nbsp;'.form_input(array('name' => 'cOptions_opt_title['.$option_id.']['.$option_drop_id.']','style' => 'width:150px;','title' => 'title', 'value' => $val["title"])).'&nbsp;&nbsp;
										<label>'.lang('br_option_drop_price').'</label>&nbsp;'.form_input(array('name' => 'cOptions_opt_price['.$option_id.']['.$option_drop_id.']','style' => 'width:40px;', 'title' => 'price','value' => $val["price"])).'&nbsp;&nbsp;
										<label>'.lang('br_option_sort').'</label>&nbsp;'.form_input(array('name' => 'cOptions_opt_sort['.$option_id.']['.$option_drop_id.']','style' => 'width:40px;', 'title' => 'sort','value' => $val["sort"])).'
									</div>';
				$option_drop_id++;
			}
		}
				
		$tmp .= '				</div>
							</td>
						</tr>
					</table>';
		return $tmp;
	}
?>
<script type="text/javascript">
	var option = 10000;
	$(function(){
		$('#addoption').bind('click',function(){

													// Clone the template
													var opt = $('#optionClone table tr:first').clone().attr('id','opt_'+field).appendTo('#optionTable');

													// Set the field names
													$('#opt_'+field+' input,#opt_'+field+' select').each(function(){
																								$(this).attr('name','cOptions_'+$(this).attr('title')+'['+field+']');
																							});
														
													// Update the meta data on the 
													// add dropdown button
													$('#opt_'+field+' .addDropOption').each(function(){
																								$(this).attr('class','submit addDropOption {add:'+field+'}');
																							});
													_set_option_bind();
													
													field++;
													return false
													
												});

		// Bind up the buttons for 
		// the options tab
		_set_option_bind();
	});
	
	function _set_option_bind(){
	
		_remove_drop_opt();
		
		$('#optionTable').unbind().tableDnD({
										dragHandle:'move_option_row',
										onDragClass: 'tDnD_whileDrag',  
										onDrop: _option_related
									});
		
				
		$('.dropOptions').unbind().tableDnD({
										dragHandle:'move_drop_option_row',
										onDragClass: 'tDnD_whileDrag',  
										onDrop: _option_related
									});
	
		// bind the remove button																								
		$('.remove').unbind().bind('click',function(){
														$(this)	.parent()
																.parent()
																.parent()
																.parent().remove();
																return false
													});
		// bind the type select 
		// 'dropdown' creates the options 
		// submenu 
		
		$('.dropdown').unbind().bind('change',function(){
														var a = $(this);
														var b = a.next();
														var c = a.val();
														if(c == 'dropdown'){
															b.show();
														}else{
															b.hide();
														}
													});
		
		// bind the add drop down option button
		$('.addDropOption').unbind().bind('click',function(){
																var add = $(this).metadata().add;
																var a = $(this).parent().parent().parent().parent();
																var b = $('#optionClone #dropOptions table tr:first').clone();
																
																b.attr("id","cOptions_opt_"+option).appendTo(a);
																
																_remove_drop_opt();
																
																// Set the field names
																$('#cOptions_opt_'+option+' input, #cOptions_opt_'+option+' select')
																		.each(function(){
																							$(this).attr('name','cOptions_opt_'+$(this).attr('title')+'['+add+']['+option+']');
																						});
																
																
																
																option++;
																return false;
		
															});
	}
	function _remove_drop_opt(){
		$('.removeDropOpt').unbind().bind('click',function(){
																$(this).parent().parent().remove();
																return false
															});
	}
	
	function _option_related(){
	
	}														
</script>