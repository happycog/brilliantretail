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
$subscription = $products[0]["subscription"][0];
?>
<div id="sub_type_6" class="subtypes">

	<table width="100%" class="subTable" cellspacing="0" cellpadding="0" id="subscription_selected">
		<tbody>
			<tr>
				<th colspan="2"><?=lang('br_subscrib_opt_title')?></th>
			</tr>
			<tr>
				<td>
					<?=lang('br_subscription_period')?></td>
				<td>
					<input type="text" name="length" value="<?=$subscription["length"]?>" style="width:50px;" />&nbsp;
					<select name="period">
						<?php
							$periods = array(
											1=>lang('br_days'),
											2=>lang('br_months')
										);
							foreach($periods as $key => $val){
								$sel = ($subscription["period"] == $key) ? 'selected="selected"' : '' ;
								echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
							}
						?>
					</select>
					<?php
						$chk = '';
						$class = 'subscribe_hide subscribe_opt';
						if($subscription["trial_offer"] == 1){
							$chk = 'checked="checked"';
							$class = 'subscribe_opt';
						}
						// Trial Offer
					?>
						<input type="checkbox" name="trial_offer" id="trial_offer" <?=$chk?> style=""> <?=lang('br_trial_offer')?></td>
			</tr>
			<tr class="<?=$class?>">
				<td class="indent_title">
					<?=lang('br_trial_price')?></td>
				<td>
					<input type="text" name="trial_price" value="<?=$subscription["trial_price"]?>" style="width:50px;" /></td>
			</tr>
			<tr class="<?=$class?>">
				<td class="indent_title">
					<?=lang('br_trial_occur')?></td>
				<td>
					<input type="text" name="trial_occur" value="<?=$subscription["trial_occur"]?>" style="width:50px;" /></td>
			</tr>
			<tr>
				<td>
					<?=lang('br_move_to_group')?></td>
				<td>
					<select name="group_id">
						<option value="0"><?=lang('br_no_change')?></option>
						<?php
							foreach($groups as $g){
								$sel = ($subscription["group_id"] == $g["group_id"]) ? 'selected="selected"' : '' ;
								echo '<option value="'.$g["group_id"].'" '.$sel.'>'.$g["group_title"].'</option>';
							}
						?>
						</option>
					</select></td>
			</tr>
			<tr>
				<td>
					<?=lang('br_cancel_group')?></td>
				<td>
					<select name="cancel_group_id">
						<option value="0"><?=lang('br_no_change')?></option>
						<?php
							foreach($groups as $g){
								$sel = ($subscription["cancel_group_id"] == $g["group_id"]) ? 'selected="selected"' : '' ;
								echo '<option value="'.$g["group_id"].'" '.$sel.'>'.$g["group_title"].'</option>';
							}
						?>
					</select></td>
			</tr>
		</tbody>
	</table>
</div>
<style type="text/css">
	.subscribe_hide {
		display: none;	
	}
</style>
<script type="text/javascript">
	$(function(){
		$('#trial_offer').bind('change',function(){
			if(this.checked == true){
				$('.subscribe_opt').show();
			}else{
				$('.subscribe_opt').hide();
			}
		});
	});
</script>