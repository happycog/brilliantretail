<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
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

	echo form_open_multipart(  $action, 
	                           array(
	                                   'method' => 'POST', 
	                                   'id' => 'storeForm'
	                               ),
	                           $hidden);
?>
	<table cellspacing="0" id="site_edit" cellpadding="0" border="0" class="product_edit" width="100%">
		<thead>
			<tr>
				<th width="35%">
					<?=$group?></th>
				<th>
					&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<?=lang('br_permission')?></td>
				<td>
					<a href="#" id="permission_select_all"><?=lang('select_all')?></a> | <a href="#" id="permission_clear_all"><?=lang('br_clear_all')?></a>
					<hr />
					<?=$permissions?></td>
			</tr>
		</tbody>
	</table>
	
	<div id="bottom_buttons">
	    <?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
    	<div class="b2r_clearboth"><!-- --></div>
    </div>
</form>
<script type="text/javascript">
	$(function(){
		$('.permmision_checkbox').change(function(){
			if($(this).is(':checked')){
				var a = $(this).val().split('|');
			}
		});
		$('#permission_select_all').click(function(){
			$('input[type=checkbox]','#permmision_tree').attr('checked','checked');
			return false;
		});
		$('#permission_clear_all').click(function(){
			$('input[type=checkbox]','#permmision_tree').removeAttr('checked');
			return false;
		});
		
	});
</script>