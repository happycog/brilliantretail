<?php
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

/********************/
/* Options Tab		*/
/********************/

$option = 10000;

?>
<table id="optionTable" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="clear:both">
	<thead>
		<tr class="nodrag nodrop">
			<th colspan="5">
				<?=lang('br_options')?></th>
		</tr>
	</thead>
	<tbody>
		<tr class="nodrag nodrop">
			<td colspan="5">
				<span class="button" style="float: right; margin: 0pt;">
					<a class="submit" href="#" id="addoption" style="color:#fff"><?=lang('br_add_option')?></a>
				</span></td>
		</tr>
		<tr class="nodrag nodrop" id="option_header">
			<td><b><?=lang('br_title')?></b></td>
			<td><b><?=lang('br_type')?></b></td>
			<td><b><?=lang('br_sort')?></b></td>
			<td><b><?=lang('delete')?></b></td>
		</tr>	
<?php
	$i = 0;
	if(isset($options)){
		foreach($options as $opt){
			echo '	<tr>
						<td>
							'.form_input(array('name' => 'cOptions_title[]','style' => 'width:150px;','title' => 'title','value'=>$opt["title"])).'</td>
						<td>
							'.form_dropdown(	'cOptions_type[]', 
												array(	'text' => lang('br_text'),
														'textarea' => lang('br_textarea'),
														'dropdown' => lang('br_dropdown')
													),
												$opt["type"],
												'title="type" style="width: 100px" class="dropdown"').'
							&nbsp;'.lang('br_required').'&nbsp;
							'.form_dropdown(	
												'cOptions_required[]', 
												array(0 => lang('br_no'),1 => lang('br_yes')),
												$opt["required"],
												'title="required"' 
											);
			
			$style = ($opt["type"] != 'dropdown') ? 'display:none' : '';
							
			echo '			<div style="'.$style.'">
								<table class="subTable dropOptions" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:10px;">
									<tr>
										<th colspan="4">
											'.lang('br_dropdown_values').'</th>	
									</tr>
									<tr>
										<td colspan="4">
											<span class="button" style="float: right; margin: 0pt;">
												<a class="submit addDropOption" data-add="'.$i.'" href="#" style="color:#fff">'.lang('br_add_option').'</a>
											</span></td>
									</tr>
									<tr>
										<td>
											<b>'.lang('br_title').'</b></td>
										<td>
											<b>'.lang('br_price').'</b></td>
										<td>	
											<b>'.lang('br_sort').'</b></td>
										<td>	
											&nbsp;</td>
									</tr>';
			
			if($opt["type"] == 'dropdown'){
				if(isset($opt["opts"])){
					foreach($opt["opts"] as $d){
						echo '	<tr>
									<td>
										'.form_input(array('name' => 'cOptions_opt_title['.$i.']['.$option.']','style' => 'width:150px;','title' => 'title','value'=>$d["title"])).'</td>
									<td>					
										'.form_input(array('name' => 'cOptions_opt_price['.$i.']['.$option.']','style' => 'width:70px;', 'title' => 'price','value' => $d["price"])).'
										<input type="hidden" name="cOptions_opt_type['.$i.']['.$option.']" title="type" value="fixed" /></td>
									<td class="move_drop_option_row">
										<input type="text" name="cOptions_opt_sort['.$i.']['.$option.']" title="sort" value="'.$d["sort"].'" /></td> 
									<td>
										<a href="#" class="removeDropOpt">'.lang('delete').'</a></td>
								</tr>';				
						$option++;
					}
				}
			}						

			echo '				</table></td>
							</div>
						<td class="move_option_row">
							<img src="'.$theme.'images/icon_move.png" /></td>
						<td>
							<a href="#" class="remove">'.lang('delete').'</a></td>
					</tr>';
			$i++;
		}
	}
?>
	</tbody>
</table>

<?php
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
								&nbsp;'.lang('br_required').'&nbsp;
								'.form_dropdown(	
									'cOptions_required[]', 
									array(0 => lang('br_no'),1 => lang('br_yes')),
									'',
									'title="required"' 
								).'

								<div style="display:none">
									<table class="dropOptions" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:10px;">
										<tr>
											<th colspan="4">
												'.lang('br_dropdown_values').'</th>	
										</tr>
										<tr>
											<td colspan="4">
												<span class="button" style="float: right; margin: 0pt;">
													<a class="submit addDropOption" data-add="'.$i.'" href="#" style="color:#fff">'.lang('br_add_option').'</a>
												</span></td>
										</tr>
										<tr>
											<td>
												<b>'.lang('br_title').'</b></td>
											<td>
												<b>'.lang('br_price').'</b></td>
											<td>	
												<b>'.lang('br_sort').'</b></td>
											<td>	
												&nbsp;</td>
										</tr>
									</table></td>
								</div>
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
								'.form_input(array('name' => 'cOptions_opt_dropTitle','style' => 'width:150px;','title' => 'title')).'</td>
							<td>					
								'.form_input(array('name' => 'cOptions_opt_dropPrice','style' => 'width:70px;', 'title' => 'price','value' => 0)).'
								<input type="hidden" name="cOptions_opt_dropType" title="type" value="fixed" /></td>
							<td class="move_drop_option_row">
								<input type="text" name="cOptions_opt_dropSort" title="sort" value="0" /></td> 
							<td>
								<a href="#" class="removeDropOpt">'.lang('delete').'</a></td>
						</tr>
					</table>
				</div>
			</div>';
?>
<script type="text/javascript">
	var option = <?=$option?>;
	var field = 0;
	$(function(){
		<?php
			if(!isset($options)){
				echo "$('#option_header').hide();";
			}
		?>
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
																								$(this).attr('data-add',field);
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
		
		$('#optionTable tbody').unbind().sortable({axis:'y', cursor:'move', opacity:0.6,
							helper:function(e, ui) {
								ui.children().each(function() {
									$(this).width($(this).width());
								});		
								return ui;
							},
							update: _option_related
							});
		
				
		$('#optionTable tbody').unbind().sortable({axis:'y', cursor:'move', opacity:0.6,
													helper:function(e, ui) {
														ui.children().each(function() {
															$(this).width($(this).width());
														});		
														return ui;
													},
													update: _option_related
												});
	
		// bind the remove button																								
		$('.remove').unbind().bind('click',function(){
														$(this)	.parent()
																.parent().remove();
																if($('#optionTable tbody tr').size() == 2){
																	$('#option_header').hide();
																}
																
																return false;
													});
		// bind the type select 
		// 'dropdown' creates the options 
		// submenu 
		
		$('.dropdown').unbind().bind('change',function(){
														var a = $(this);
														var b = a.next().next();
														var c = a.val();
														if(c == 'dropdown'){
															b.show();
														}else{
															b.hide();
														}
													});
		
		// bind the add drop down option button
		$('.addDropOption').unbind().bind('click',function(){
																var add = $(this).attr('data-add');
																var a = $(this).parent().parent().parent().parent();
																var b = $('#optionClone #dropOptions table tr:first').clone();

																$('#option_header').show();

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
		$('#optionTable tr').removeClass('even').removeClass('odd');
		$('#optionTable tr:even').addClass('even');
		$('#optionTable tr:odd').addClass('odd');
	}														
</script>