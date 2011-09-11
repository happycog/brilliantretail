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
$subscription = $products[0]["subscription"][0];
?>
<div id="sub_type_6" class="subtypes">

	<table width="100%" cellspacing="0" cellpadding="0" id="subscription_selected">
		<tbody>
			<tr>
				<th colspan="2"><?=lang('br_subscrib_opt_title')?></th>
			</tr>
			<tr class="even">
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
						</option>
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
			<tr class="even <?=$class?>">
				<td class="indent_title">
					<?=lang('br_trial_price')?></td>
				<td>
					<input type="text" name="trial_price" value="<?=$subscription["trial_price"]?>" style="width:50px;" /></td>
			</tr>
			<tr class="even <?=$class?>">
				<td class="indent_title">
					<?=lang('br_trial_length')?></td>
				<td>
					<input type="text" name="trial_length" value="<?=$subscription["trial_length"]?>" style="width:50px;" />&nbsp;
					<select name="trial_period">
						<?php
							$periods = array(
											1=>lang('br_days'),
											2=>lang('br_months')
										);
							foreach($periods as $key => $val){
								$sel = ($subscription["trial_period"] == $key) ? 'selected="selected"' : '' ;
								echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
							}
						?>
						</option>
					</select></td>
			</tr>
			<tr class="even <?=$class?>">
				<td class="indent_title">
					<?=lang('br_trial_occur')?></td>
				<td>
					<input type="text" name="trial_occur" value="<?=$subscription["trial_occur"]?>" style="width:50px;" /></td>
			</tr>
			<tr class="odd">
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
			<tr class="even">
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
			<tr>
				<th colspan="2">
					<?=lang('br_subscrib_disc_price')?>
				</th>
			</tr>
			<tr class="even">
				<td colspan="2">
					<?=lang('br_subscrib_price_desc')?>
					<span style="float: right; margin: 0pt;">
						<a class="add_btn" href="#" id="addsub_price" style="color:#fff"><?=lang('br_add_option')?></a>
					</span></td>
			</tr>
			<tr class="odd">
				<td colspan="2" id="sub_row">
					
					<?php 
						if(isset($products[0]["subscription"][0]["sub_price"])){
							foreach($products[0]["subscription"][0]["sub_price"] as $p){
								echo '	<div style="padding: 10px">'.lang('br_periods').'
											<select name="sub_price_period[]" style="width:50px">';
								
								for($i=1;$i<=30;$i++){
									$sel = ($p["periods"] == $i) ? 'selected="selected"' : '';
									echo "<option ".$sel.">".$i."</option>";
								}
								
								echo '		</select>&nbsp;'.lang('br_discount').'
											<input type="text" name="sub_price_adjust[]" value="'.$p["discount"].'" style="width:50px" />&nbsp;&nbsp;&nbsp;
											<a href="#" class="sub_remove">'.lang('delete').'</a>
										</div>';
							}
						}
					?>
				</td>
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
		
		_bind_subprice_remove();
		
		$('#addsub_price').bind('click',function(){
			$(sub_row).appendTo('#sub_row');
			_bind_subprice_remove();
			return false;

		});
		var sub_row = 	'	<div style="padding: 10px">'+
						'		<?=lang('br_periods')?>'+
						'		<select name="sub_price_period[]" style="width:50px">'+
									<?php 
										for($i=1;$i<=12;$i++){
											echo "'<option>".$i."</option>'+\n";
										}
									?>
						'		</select>'+
						'		&nbsp;'+
						'		<?=lang('br_percent_discount')?>'+
						'		<input type="text" name="sub_price_adjust[]" style="width:50px" />&nbsp;&nbsp;&nbsp;'+
						'		<a href="#" class="sub_remove"><?=lang('delete')?></a>'+
						'	</div>';
						
	});
	
	function _bind_subprice_remove(){
		$('.sub_remove')
			.unbind()
			.bind('click',function(){
				$(this).parent().remove();
				return false;
			});
	}
			
	
</script>