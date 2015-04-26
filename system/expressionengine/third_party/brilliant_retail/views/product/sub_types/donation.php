<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
/* 	@license	http://opensource.org/licenses/OSL-3.0	*/
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

	<table width="100%" class="product_edit" cellspacing="0" cellpadding="0" id="donation_selected">
		<tbody>
			<tr>
				<th colspan="2"><?=lang('br_donation_opt_title')?></th>
			</tr>
			<tr>
				<td class="indent_title">
					<?=lang('br_min_donation')?></td>
				<td>
					<input type="hidden" name="allow_recurring" value="0" />
					<input type="text" name="min_donation" value="<?=$donation["min_donation"]?>" style="width:50px;" /></td>
			</tr>
		</tbody>
	</table>
</div>