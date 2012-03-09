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

/****************/
/* Attributes	*/
/****************/
?>

<div class="publish_field" id="hold_br_custom_attributes">

	<label class="hide_field">
		<span>
			<?=lang('br_custom_attributes')?>
		</span>
	</label>

	<div id="sub_hold_br_custom_attributes">
		<fieldset class="holder">

			<table id="product_attributes_tbl" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="margin-top:5px;">
				<tr>
					<th colspan="2">
						</th>
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
			$i = 0;
			foreach($attrs as $a){
				$req = ($a["required"] == 1) ? ' *' : '';
				echo '	<tr>
							<td>';
				if($i == 0){
					echo '<input type="hidden" name="attribute_set_id" value="'.$products[0]["attribute_set_id"].'" />';
				}				
				echo 			$a['title'].$req.'</td>
							<td>
								'.$a['input'].'</td>
						</tr>';
				$i++;
			}
		}
?>
			</table>

		</fieldset>
	</div> <!-- /sub_hold_field -->
</div>

<script type="text/javascript">
	$(function(){
		$('#addCustomAttribute').bind('click',function(){
			$('#attributeContainer').show();
			$(this).hide();
		});
 
		$('#optionSelect').bind('click',function(){
			$.post('<?=$add_attributes?>',{
											set_id:$('#attributeOptions').val(), 
											product_id:<?=$hidden["product_id"]?>
											},function(resp){
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
