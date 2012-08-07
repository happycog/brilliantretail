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
	        		</select>
					<div id="dropdown_options" class="type_opts">
		        		<br />
		        		<textarea name="dropdown_options" id="dropdown"><?=$attributes["options"]?></textarea>
		        		<p><?=lang('br_dropdown_instruction')?></p>
	        		</div>
	        		<div id="multiselect_options" class="type_opts">
		        		<br />
		        		<textarea name="multiselect_options" id="multiselect"><?=$attributes["options"]?></textarea>
		        		<p><?=lang('br_multiselect_instruction')?></p>
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
	$(function(){
		
		$('.product_edit tbody tr:even').addClass('even');
		
		$('#delete_button').bind('click',function(){
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
			}elseif($attributes["fieldtype"] == 'dropdown'){
				echo "$('#filterable').show();";
				echo "$('#dropdown_options').show();";
			}elseif($attributes["fieldtype"] == 'multiselect'){
				echo "$('#multiselect_options').show();";
			}
			
		?>
		
		
		$('#fieldtype').bind('change',function(){
			var a = $(this);
			$('.type_opts').hide();
			if(a.val() == 'dropdown'){
				$('#filterable').show();
				$('#dropdown_options').show();
			}else if(a.val() == 'multiselect'){
				$('#multiselect_options').show();
			}else if(a.val() == 'text'){
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