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

/********************/
/* SEO Tab			*/
/********************/
?>
<div class="publish_field" id="hold_br_seo">

	<label class="hide_field">
		<span>
			<?=lang('br_seo')?>
		</span>
	</label>

	<div id="sub_hold_br_seo">
		
		<fieldset class="holder">
		
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="product_edit">
	<thead>
		<tr>
			<th width="20%"><?=lang('br_title')?></th>
			<th><?=lang('br_value')?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=lang('br_meta_title')?></td>
			<td><?=form_input(array('name' => 'meta_title', 
									'value' => $products[0]["meta_title"]))?></td>
		</tr>
		<tr>
			<td><?=lang('br_meta_keyword')?></td>
			<td><?=form_textarea(array(	'name' => 'meta_keyword', 
										'value' => $products[0]["meta_keyword"],
										'class' => 'br_textarea'))?></td>
		</tr>
		<tr>
			<td><?=lang('br_meta_descr')?></td>
			<td><?=form_textarea(array(	'name' => 'meta_descr', 
										'value' => $products[0]["meta_descr"],
										'class' => 'br_textarea'))?></td>
		</tr>	
	</tbody>
</table>



		</fieldset>
	</div> <!-- /sub_hold_field -->
</div>