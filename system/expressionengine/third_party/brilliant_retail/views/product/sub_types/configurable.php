<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2012						*/
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
?>
<div id="sub_type_3" class="subtypes">

	<input type="hidden" name="require_configurable" title="<?=lang('br_details').' - '.lang('br_configurable_product_required').' '.lang('br_is_required')?>" id="sub_type_req_3"  value="1" class="{required:true} sub_type_req" />

	<?php
		if(isset($config_opts)){
	?>
		
		<div id="config_opts_container">
			<table width="100%" cellpadding="0" cellspacing="0" class="product_edit">
				<tr>
					<td>
						<div id="config_opts_instructions">
							<?=lang('br_config_opts_instructions')?>
						</div></td>
				</tr>
				<tr>
					<th>
						<?=lang('br_attribute_set')?></th>
				</tr>
				<tr>
					<td>
						<select id="config_opts" multiple="multiple" name="config_opts[]" title="<?=lang('br_select_attributes')?>">
							<?php
								foreach($config_opts as $opts){
									echo '<option value="'.$opts["attribute_id"].'">'.$opts["title"].'</option>'; 
								}
							?>
							
						</select>
						<p>
							<div id="create_config" class="add_btn"><?=lang('create')?></div>
						</p></td>
				</tr>
			</table>
		</div>
		<div id="config_form_container"></div>
				<script type="text/javascript">
				$(function() {
					$("#config_opts").asmSelect({
						addItemTarget: 'bottom',
						sortable: true
					});
					$('#create_config').bind('click',function(){
						if($('#config_opts').val() != null){
							$.post(	'<?=$config_opts_link?>',
									{'fields':$('#config_opts').val()},
									function(data){
										$('#config_opts_container').remove();
										$('#config_form_container').html(data);
										_bind_config_button();
									});
						}else{
							alert('<?=lang('br_configurable_create_error')?>');
						}
						return false; 
					});
				}); 
		</script>
	
	<?php
		}else{
			echo $config_products;
			echo '	<script type="text/javascript">
						$(function(){
							_bind_config_button();
							$(\'.config_item_remove\').unbind().click(function(){
								$(this).parent().parent().remove();
								return false;
							});
						});
					</script>
				';
		}
	?>
</div>
<script type="text/javascript">
	function _bind_config_button(){
		// Activate Sortable
		$('#config_selected tbody').sortable({axis:'y', cursor:'move', opacity:0.6, handle:'.move_config_row',
			helper:function(e, ui) {
				ui.children().each(function() {
					$(this).width($(this).width());
				});		
				return ui;
			}
		});
		
		$('#configurableCreate a').bind('click',function(){
			var config_attr = $('#config_attr').val();
			var config_sku = $('#config_sku').val();
			var config_qty = $('#config_qty').val();
			var config_adjust_type = $('#config_adjust_type').val();
			var config_adjust_type = $('#config_adjust').val();
			var attr = config_attr.split(',');
			var tmp = '<tr>';
			for(i=0;i<attr.length;i++){
				tmp += '<td><input type="hidden" name="config_attr_'+attr[i]+'[]" value="'+$('select[name=configurable_'+attr[i]+'] option:selected').text()+'" />'+$('select[name=configurable_'+attr[i]+'] option:selected').text()+'</td>';
			}
			tmp += 	'<td><input type="text" name="config_sku[]" /></td>'+
					'<td><input type="text" name="config_qty[]" value="0" /></td><td>'+
					'<select style="display:none" name="config_adjust_type[]"><option>fixed</option><option>percent</option></select>'+
					'<input type="text" name="config_adjust[]" value="0.00" /></td>'+
					'<td class="move_config_row"><img src="<?=$theme?>images/icon_move.png" /></td>'+
					'<td><a href="#" class="config_item_remove"><?=lang('delete')?></a></td></tr>';
			$(tmp).prependTo($('#config_selected tbody'));

			// reset the dnd for the rows

			$('.config_item_remove').unbind().bind('click',function(){
				$(this).parent().parent().remove();
				var rows = $('#config_selected tbody tr').size();
				if(rows == 0){
					$('#sub_type_req_3').val('');
				}				
				return false;
			});
			
			$('#sub_type_req_3').val(1);
			
			return false;
			
		});
	}
</script>