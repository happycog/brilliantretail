<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2013						*/
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

	echo form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_attribute_update',
					array(	'method' 	=> 'POST', 
							'id' 		=> 'attribute_edit',
							'encrypt' 	=> 'multipart/form-data'),
					array(	'attribute_id' => $attributes["attribute_id"]));
?>
<div id="b2r_page" class="b2r_category">
	<table id="attribute_tbl" class="product_edit" width="100%" cellpadding="0" cellspacing="0">
		<thead>
			<tr class="odd">
				<th width="35%">
					<?=lang('br_attribute_settings')?></th>
				<th>
					&nbsp;</th>
			</tr>
		</thead>
    	<tbody>
	    	<tr>
	        	<td>
	        		<?=lang('br_title')?> *</td>
	        	<td>
	        		<input type="text" id="title" name="title" value="<?=$attributes["title"]?>" class="{required:true}" /></td>
	        </tr>
	 		<tr>
	        	<td>
	        		<?=lang('br_code')?> *</td>
	        	<td>
	        		<input type="text" id="code" name="code" value="<?=$attributes["code"]?>" class="{required:true}"  />
	        </tr>
			<tr>
	        	<td>
	        		<?=lang('br_required')?></td>
	        	<td>
	        		<select name="required">
	        			<?php
	        				$yes = ($attributes["required"] == 1) ? 'selected' : '' ;
	        				$no = ($attributes["required"] == 0) ? 'selected' : '' ;
	        				echo '	<option value="1" '.$yes.'>'.lang('br_yes').'</option>
	        						<option value="0" '.$no.'>'.lang('br_no').'</option>';
	        			?>
	        		</select></td>
	        </tr>	        
	 		<tr>
	        	<td>
	        		<?=lang('br_field_type')?></td>
	        	<td>
	        		<select id="fieldtype" id="fl_type" name="fieldtype">
	        			<?php 
	        				$opts = array('text','textarea','dropdown','multiselect','file');

	        				foreach($opts as $o){
	        					$sel = ($attributes["fieldtype"] == $o) ? 'selected' : '' ;
	        					echo '<option value="'.$o.'" '.$sel.'>'.lang('br_'.$o).'</option>';
	        				}
	        			?>
	        		</select><br />
	        		<div id="options" class="type_opts">
		        		<br />
		        		<table width="100%" cellpadding="0" cellspacing="0" id="option_rows" class="product_edit">
		        			<thead>
			        			<tr>
			        				<th colspan="3">
			        					<?=lang('br_label')?></th>
			        			</tr>
							</thead>
		        			<tbody>
		        		<?php
		        			$i = 1;
		        			foreach($attributes["options"] as $opt){
		        				echo '	<tr>
		        							<td>
		        								<input type="hidden" name="option[id]['.$i.']" value="'.$opt["attr_option_id"].'" />
		        								<input type="hidden" class="opt_sort" name="option[sort]['.$i.']" value="'.$i.'" />
		        								<input type="hidden" id="remove_'.$i.'" name="option[remove]['.$i.']" value="0" />
		        								<input type="text" name="option[label]['.$i.']" value="'.$opt["label"].'" />
		        							</td>
		        							<td class="move_config_row" width="5%">
		        								<img src="'.$theme.'images/move.png" /></td>
		        							<td width="5%">
		        								<a href="#" data-remove="'.$i.'" class="config_item_remove"><img src="'.$theme.'images/delete.png" /></a></td>
		        						</tr>';
		        				$i++;
		        			}
		        		?>
		        			</tbody>
		        		</table>
		        		<p><a href="#" class="add_row">[+]</a></p>
		        		<p><?=lang('br_dropdown_instruction')?></p>
	        		</div>
	        		<div id="default" class="type_opts">
		        		<br />
		        		<input type="text" name="default_text" value="<?=$attributes["default_text"]?>" />
	        			<p><?=lang('br_text_default_instruction')?></p>
	        		</div></td>
	        </tr>
	        <tr id="filterable" class="type_opts">
	        	<td>
	        		<?=lang('br_filterable')?></td>
	        	<td>
	        		<select name="filterable">
	        			<?php
	        				$yes = ($attributes["filterable"] == 1) ? 'selected' : '' ;
	        				$no = ($attributes["filterable"] == 0) ? 'selected' : '' ;
	        				echo '	<option value="1" '.$yes.'>'.lang('yes').'</option>
	        						<option value="0" '.$no.'>'.lang('no').'</option>';
	        			?>
	        		</select></td>
	        </tr>
		</tbody>
    </table>
		<div id="bottom_buttons">
		    <?=form_submit(array('name' => 'submit', 'value' => lang('br_save_continue'), 'class'=>'submit'))?>
			<?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
			<?php 
				if($attributes["attribute_id"] != 0){
			?>
					<?=form_submit(array('name' => 'duplicate', 'value' => lang('br_duplicate'), 'class'=>'submit'))?>
					<?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'submit'))?>
			<?php
				}
			?>
	    	<div class="b2r_clearboth"><!-- --></div>
	    </div>
</form>   
<script type="text/javascript">
	
	var new_cnt = 0;

	function _set_remove(e){
		e.preventDefault();
		var a = $(this);
		var b = a.data('remove');
		$('#remove_'+b).val(1);
		a.closest('tr').hide();
	}
		
	$(function(){

		$('#option_rows tbody').sortable({axis:'y', cursor:'move', opacity:0.6, handle:'.move_config_row',
			helper:function(e, ui) {
				ui.children().each(function() {
					$(this).width($(this).width());
				});		
				return ui;
			},
			update: function( event, ui ) {
				var i = 1;
				$('.opt_sort').each(function(){
					$(this).val(i);
					i++;
				});
			}
		});
		
		$('.add_row').click(function(event){
			event.preventDefault();
			var row = 	'<tr>'+
						'	<td>'+
						'		<input type="hidden" name="option[id][new_'+new_cnt+']" value="new" />'+
						'		<input type="hidden" class="opt_sort" name="option[sort][new_'+new_cnt+']" value="new" />'+
						'		<input type="hidden" id="remove_new_'+new_cnt+'" name="option[remove][new_'+new_cnt+']" value="0" />'+
						'		<input type="text" name="option[label][new_'+new_cnt+']" value="" />'+
						'	</td>'+
						'	<td class="move_config_row" width="5%">'+
						'		<img src="<?=$theme?>images/move.png" /></td>'+
						'	<td width="5%">'+
						'		<a href="#" data-remove="new_'+new_cnt+'" class="config_item_remove"><img src="<?=$theme?>images/delete.png" /></a></td>'+
						'</tr>';
			new_cnt++;
			$(row).appendTo($('#option_rows tbody'));
			$('#option_rows tbody tr:last input:visible').focus();	
			$('.config_item_remove').unbind().click(_set_remove);

			// Reset the sort
				var i = 1;
				$('.opt_sort').each(function(){
					$(this).val(i);
					i++;
				});
		});
		
		$('.config_item_remove').click(_set_remove);
		
		$('.product_edit tbody tr:even').addClass('even');
		
		$('#delete_button').bind('click',function(e){
			e.preventDefault();
			if(confirm('<?=lang('br_confirm_delete_attribute')?>')){
				window.location = '<?=$base_url?>&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_attribute_delete&attribute_id=<?=$attributes["attribute_id"]?>';
			}
			return false;
		});
		$('#title').bind('click',function(){
			var a = $(this);
			if(a.val() == '<?=lang('br_new_attribute')?>'){
				a.val('');
			}
		});
		$('#attribute_edit').validate();
		<?php
			if($attributes["fieldtype"] == 'text'){
				echo "$('#default').show();";
			}elseif($attributes["fieldtype"] == 'dropdown' || 
					$attributes["fieldtype"] == 'multiselect')
			{
				echo "$('#filterable,#options').show();";
			}
			
		?>
		
		
		$('#fieldtype').bind('change',function(){
			var a = $(this);
			$('.type_opts').hide();
			if(a.val() == 'dropdown' || a.val() == 'multiselect'){
				$('#filterable').show();
				$('#options').show();
			}else{
				$('#default').show();
			}
			
		});
		
		$('#delete').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_attribute')?>')){
				return true;
			}else{
				return false;
			}
		});

	});
</script>