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
			<table id="product_attributes_tbl" class="product_edit" cellspacing="0" cellpadding="0" border="0" width="100%">
				<thead>
				<?php
				
					// Figure out if we are showing the 
					// add button or the remove button 
					// based on if one exists already
						$show_attr_add 		= '';
						$show_attr_remove 	= 'nodisplay';
						
						if($products[0]["attribute_set_id"] != 0){
							$show_attr_add 		= 'nodisplay';
							$show_attr_remove 	= '';
						}
						
					// Add a table header just for
					// the custom attributes
											
					$attrOptions = '<div id="attributeContainer">
										<label>
											'.lang('br_select_attribute_set').': 	
										</label>
											<select id="attributeOptions">';
					foreach($attribute_sets	as $set){
						$attrOptions .= '<option value="'.$set["attribute_set_id"].'">'.$set["title"].'</option>';
					}	
					$attrOptions .= '	</select>
										<div id="optionSelect" class="button"><a class="submit">'.lang('br_select_attribute_set').'</a></div>
										<div id="optionCancel" class="button"><a class="submit">'.lang('br_cancel').'</a></div>
									</div>';
				?>
					<tr class="<?=$show_attr_add?>">
						<td colspan="2">
							<?php
							echo '<div id="addCustomAttribute">'.lang('br_add').'</div>'.$attrOptions;
							?></td>
					</tr>
					<tr class="<?=$show_attr_remove?>" id="removeCustom">
						<td colspan="2">
							<span class="button" style="float: right; margin: 0pt;">
								<a class="submit" href="#" style="color:#fff"><?=lang('remove')?></a>
							</span></td>
					</tr>
					<tr>
						<th><?=lang('br_title')?></th>
						<th><?=lang('br_value')?></th>
					</tr>
				</thead>
				<?php
					$show_attr_foot = 'nodisplay';
					if($products[0]["attribute_set_id"] == 0){
						$show_attr_foot = '';
					}
				?>
				<tfoot class="<?=$show_attr_foot?>">
					<tr>
						<td colspan="2">
							<?=lang('br_attribute_empty')?></td>
					</tr>
				</tfoot>
		<tbody>
<?php 
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
 
		$('#optionSelect a').bind('click',function(){
			$.get('<?=$add_attributes?>',{
											set_id:$('#attributeOptions').val(), 
											product_id:<?=$hidden["product_id"]?>
											},function(resp){
				var a = $('#product_attributes_tbl tbody');
				a.find('tr').remove();
				$(resp).appendTo(a);
				$('#product_attributes_tbl thead tr:eq(0)').hide();
				$('#product_attributes_tbl tfoot').hide();
				$('#removeCustom').show();
			});
			return false;
		});
		
		$('#optionCancel a').bind('click',function(){
			$('#addCustomAttribute').show();
			$('#attributeContainer').hide();
			return false;
		});
	
		$('#removeCustom .submit').bind('click',function(){
			$('#removeCustom').hide();
			$('#addCustomAttribute').show();
			$('#attributeContainer').hide();
			$('#product_attributes_tbl tbody tr').remove();
			$('#product_attributes_tbl thead tr:eq(0)').show();
			$('#product_attributes_tbl tfoot').show();
			return false;
		});
		
	});
</script>
