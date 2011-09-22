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
/* SEO Tab			*/
/********************/
?>
<table cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form">
	<tr>
		<th colspan="2">
			<?=lang('br_seo')?></th>
	</tr>
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
</table>