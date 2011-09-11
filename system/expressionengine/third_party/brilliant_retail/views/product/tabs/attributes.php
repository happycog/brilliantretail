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

/****************/
/* Attributes	*/
/****************/
?>
	<table id="product_attributes_tbl" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="margin-top:5px;">
		<tr>
			<th colspan="2">
				<?=lang('br_custom_attributes')?></th>
		</tr>
<?php
	if($products[0]["attribute_set_id"] == 0){
		// Add a table header just for
		// the custom attributes
								
		$attrOptions = '<div id="attributeContainer">
								<select id="attributeOptions">';
			foreach($attribute_sets	as $set){
				$attrOptions .= '<option value="'.$set["attribute_set_id"].'">'.$set["title"].'</option>';
			}	
			$attrOptions .= '	</select>
								<div class="b2r_clearboth"><!-- --></div>
								<div id="optionSelect">'.lang('br_select_attribute_set').'</div>
								<div id="optionCancel">'.lang('br_cancel').'</div>
							</div>';
							 
?>

		<tr>
			<td><?=lang('br_add_options')?></td>
			<td><?php
				echo '<div id="addCustomAttribute">'.lang('br_add_custom_attributes').'</div>'.$attrOptions;
				?></td>
		</tr>
<?
		}else{
			foreach($attrs as $a){
				$req = ($a["required"] == 1) ? ' *' : '';
				echo '	<tr>
							<td>'.$a['title'].$req.'</td>
							<td>'.$a['input'].'</td>
						</tr>';
			}
		}
?>
	</table>
<script type="text/javascript">
	$(function(){
		$('#addCustomAttribute').bind('click',function(){
			$('#attributeContainer').show();
			$(this).hide();
		});
 
		$('#optionSelect').bind('click',function(){
			$.post('<?=$add_attributes?>',{set_id:$('#attributeOptions').val()},function(resp){
				$(resp).appendTo($('#product_attributes_tbl'));
				$('#attributeContainer').parent().parent().remove();
			});
		});
		$('#optionCancel').bind('click',function(){
			$('#addCustomAttribute').show();
			$('#attributeContainer').hide();
		});
	});
</script>
