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
/* Categories Tab 	*/
/********************/
?>
<div class="publish_field" id="hold_br_catalog">

	<label class="hide_field">
		<span>
			<em class="required">*</em> <?=lang('br_catalog')?>
		</span>
	</label>

	<div id="sub_hold_br_catalog">
		
		<fieldset class="holder">

			<table cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="clear:both">
					<tr>
						<th>	
							<?=lang('br_categories')?></th>
					</tr>
					<tr>
						<td id="category_options">
							<?=$categories?></td>
					</tr>
			</table>

		</fieldset>
	</div> <!-- /sub_hold_field -->
</div>