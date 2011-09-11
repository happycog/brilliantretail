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
?>
<?=form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_tax_update', array('method' => 'POST', 'id' => 'promoForm','encrypt' => 'multipart/form-data'),$hidden)?>
<div id="b2r_page" class="b2r_category">
   <table id="admin_header" cellpadding="0" cellspacing="0">
    	<tr>
			<td>
				<?php
					echo '	<select id="select_config">';
        			foreach($submenu as $key => $val){
            			$sel = ($key == $sub_selected) ? 'selected="selected"' : '' ; 
            			echo '	<option value="'.$key.'" '.$sel.'>'.lang($key).'</option>'; 
            		}
            		echo '	</select>
                			<script type="text/javascript">
                				$(function(){
                					$(\'#select_config\').change(function(){
										window.location = \''.$base_url.'&&method=\'+$(this).val();
                					});
                				});	
                			</script>';
				?>
				<h3><?=lang('br_attributes')?></h3>
				<div class="b2r_clearboth"><!-- --></div>
    			<div id="header_buttons">
				    <?=form_submit(array('name' => 'save_continue', 'value' => lang('br_save_continue'), 'class'=>'submit'))?>
					<?=form_submit(array('name' => 'save', 'value' => lang('save'), 'class'=>'submit'))?>
					<?php 
						if($tax["tax_id"] != 0){
					?>
							<?=form_submit(array('name' => 'duplicate', 'value' => lang('br_duplicate'), 'class'=>'submit'))?>
							<?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'delete'))?>
					<?php
						}
					?>
					<p class="b2r_cancel"><a href="<?=$base_url.'&method=config_tax'?>"><?= lang('br_cancel'); ?></a></p>
			    	<div class="b2r_clearboth"><!-- --></div>
			    </div>
    		</td>
		</tr>
    </table>

	<div class="b2r_clearboth"><!-- --></div>

	<table class="mainTable" style="clear:both">
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
					<select name="zone_id">
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
					<select name="state_id">
						<option value="0"><?=lang('br_all_states')?></option>
						<?php
							$opt = array(1 => lang('br_enabled'), 0 => lang('br_disabled'));
							$sel = $tax["state_id"];
							foreach($states as $s){
								$selected = ($s["state_id"] == $sel) ? 'selected="selected"' : '';
								echo '<option value="'.$s["state_id"].'" '.$selected.'>'.$s["title"].'</option>';
							}
						?>
					</select></td>
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
    	
	<div class="b2r_clearboth"><!-- --></div>
</div>
</form>                     



<script type="text/javascript">
	$(function(){
		$('#delete').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_tax')?>')){
				return true;
			}
			return false;
		});
	});
</script>