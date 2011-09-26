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
$donation = $products[0]["donation"][0];
?>

<div id="sub_type_7" class="subtypes">

	<table width="100%" class="subTable" cellspacing="0" cellpadding="0" id="donation_selected">
		<tbody>
			<tr>
				<th colspan="2"><?=lang('br_donation_opt_title')?></th>
			</tr>
			<tr>
				<td class="indent_title">
					<?=lang('br_min_donation')?></td>
				<td>
					<input type="text" name="trial_price" value="<?=$donation["trial_price"]?>" style="width:50px;" /></td>
			</tr>
<?php
	if($can_subscribe){	
?>		
			<tr>
				<td>
					<?=lang('br_allow_recurring')?></td>
				<td>
					<select name="allow_recurring">
						<?php
							$periods = array(
												0=>lang('br_no'),
												1=>lang('br_yes') 
											);
							foreach($periods as $key => $val){
								$sel = ($donation["period"] == $key) ? 'selected="selected"' : '' ;
								echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
							}
						?>
					</select> <em><?=lang('br_donation_instructions')?></em></td>
			</tr>
<?php
}
?>
		</tbody>
	</table>
</div>