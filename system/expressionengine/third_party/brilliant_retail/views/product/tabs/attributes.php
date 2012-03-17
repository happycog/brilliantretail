<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2012						*/
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
			<table id="product_attributes_tbl" cellspacing="0" cellpadding="0" border="0" width="100%">
				<thead>
					<th><?=lang('br_title')?></th>
					<th><?=lang('br_value')?></th>
<?php

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
		</thead>
		<tbody>
<?
		if($products[0]["attribute_set_id"] != 0){
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
				</tbody>

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
				var a = $('#product_attributes_tbl tbody');
				a.find('tr').remove();
				$(resp).appendTo(a);
				$('#product_attributes_tbl thead tr:eq(1)').hide();
			});
		});
		$('#optionCancel').bind('click',function(){
			$('#addCustomAttribute').show();
			$('#attributeContainer').hide();
		});
	});
</script>
