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
<?=form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_tax_update', array('method' => 'POST', 'id' => 'taxTable','encrypt' => 'multipart/form-data'),$hidden)?>
	<table class="product_edit" width="100%" cellpadding="0" cellspacing="0">
    	<thead>
    		<tr>
    			<th width="35%">
    				<?=lang('br_tax_settings')?></th>
    			<th>
    				&nbsp;</th>
    		</tr>
    	</thead>
    	<tbody>
			<tr>
				<td>
					<?=lang('br_title').' *'?></td>
				<td>
					<input 	type="text" 
							name="title"
							value="<?=$tax["title"]?>" 
							class="{required:true}"
							title="<?=lang('br_product_title').' '.lang('br_is_required')?>" /></td>
			</tr>
			<tr>
				<td>
					<?=lang('br_zone')?></td>
				<td>
					<select name="zone_id" id="zone_id">
						<option value="0"><?=lang('br_all_zones')?></option>
						<?php
							$opt = array(1 => lang('br_enabled'), 0 => lang('br_disabled'));
							$sel = $tax["zone_id"];
							foreach($zones as $z){
								$selected = ($z["zone_id"] == $sel) ? 'selected="selected"' : '';
								echo '<option value="'.$z["zone_id"].'" '.$selected.'>'.$z["title"].'</option>';
							}
						?>
					</select></td>
			</tr>
			<tr>
				<td>
					<?=lang('br_state')?></td>
				<td>
					<select name="state_id" id="state_id">
						<option value="0"><?=lang('br_all_states')?></option>
						<?php
							$opt = array(1 => lang('br_enabled'), 0 => lang('br_disabled'));
							$sel = $tax["state_id"];
							foreach($states as $s){
								$selected = ($s["state_id"] == $sel) ? 'selected="selected"' : '';
								echo '<option value="'.$s["state_id"].'" data-zone_id="'.$s["zone_id"].'" class="nodisplay" '.$selected.'>'.$s["title"].'</option>';
							}
						?>
					</select></td>
			</tr>
			<tr>
				<td>
					<?=lang('br_zip_code')?></td>
				<td>
					<textarea name="zipcode"><?=$tax["zipcode"]?></textarea><br />
					<em><?=lang('br_tax_zipcode_instructions')?></em></td>
			</tr>
			<tr>
				<td>
					<?=lang('br_tax_rate').' *'?></td>
				<td>
					<input 	type="text" 
							name="rate"
							value="<?=$tax["rate"]?>" 
							class="{required:true}"
							title="<?=lang('br_tax_rate').' '.lang('br_is_required')?>" /></td>
			</tr>
		</tbody>
    </table>
	<div id="bottom_buttons">
	    <?=form_submit(array('name' => 'save_continue', 'value' => lang('br_save_continue'), 'class'=>'submit'))?>
		<?=form_submit(array('name' => 'save', 'value' => lang('save'), 'class'=>'submit'))?>
		<?php 
			if($tax["tax_id"] != 0){
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

	var map = Array();
	var select;
	
	$(function(){
		$('#taxTable tr:even').addClass('even');
		$('#taxTable tr:odd').addClass('odd');
		$('#taxTable').validate();
		$('#delete').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_tax')?>')){
				return true;
			}
			return false;
		});
		
		// Handle the state filtering
			select = $('#state_id');
			var state_selected = <?=$tax["state_id"]?>;
			var country_state_map = <?=$map?>;
			
			$('#zone_id').bind('change',function(){
			
				var country = $('#zone_id option:selected').val();
				var str = '<option value="0"><?=lang('br_all_states')?></option>';
				
				$.each(country_state_map[country], function(k, v) {
																		str += '<option value="'+v+'">'+k+'</option>';
																	});
				select.empty().append(str);
				select.val(state_selected);
			}).triggerHandler('change');
	});
</script>