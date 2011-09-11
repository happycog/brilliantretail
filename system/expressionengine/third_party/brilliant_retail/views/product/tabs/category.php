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

/********************/
/* Categories Tab 	*/
/********************/
?>
<table cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="clear:both">
		<tr>
			<th colspan="2">
				<?=lang('br_catalog')?></th>
		</tr>
		<tr>
			<td>
				<?=lang('br_categories')?></td>
			<td id="category_options">
				<?=$categories?></td>
		</tr>
</table>
<?php
/*
<script type="text/javascript">
$(function(){
	$('#category_options input[type=checkbox]').bind('click',function(){
		var obj = $(this);
		_set_product_cat(obj);
	});
});
function _set_product_cat(obj){
	var pid = obj.metadata().parent;
	var lvl = obj.metadata().level;
	chk = (obj.attr('checked') == true) ? 1 : 0;
	if(chk == 1){
		$('#product_cat_'+pid).attr('checked','checked');
	}else{
		// $('#product_cat_'+pid).attr('checked','');
	}
	if(lvl > 0){
		_set_product_cat($('#product_cat_'+pid));
	}
}
</script>
*/