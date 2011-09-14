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
?>
<?php
	echo form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_attributeset_update',
					array(	'method' 	=> 'POST', 
							'id' 		=> 'attribute_set_edit',
							'class' 	=> 'b2r_category', 
							'encrypt' 	=> 'multipart/form-data'),
					array(	'attribute_set_id' => $attribute_set_id));
?>

<div id="b2r_page" class="b2r_category">
	<table id="attribute_tbl" class="mainTable" cellpadding="0" cellspacing="0">
    	<thead>
	    	<tr>
	    		<th colspan="2">
	    			<?=lang('br_attribute_set_settings')?></th>
	    	</tr>
		</thead>
    	<tbody>
	        <tr class="odd">
	        	<td class="cell_1">
	        		<?=lang('br_title')?> *</td>
	        	<td class="cell_2">
	        		<input type="text" name="title" id="title" class="{required:true}" value="<?=$title?>" /></td>
	        </tr>
	        <tr class="even">
	        	<td>
	        		<?=lang('br_attributes')?></td>
	        	<td>
			    	<p><?=lang('br_attribute_set_instruction')?></p>
			    	<ul id="sel_attribute">
			    	<?php
				    	foreach($attributes as $a){
							$sel = ($a["selected"] == 1) ? 'checked' : '' ;
							echo '<li><input type="checkbox" name="attr[]" value="'.$a["attribute_id"].'" '.$sel.' />&nbsp;'.$a["title"].'</li>';
						}
			    	?>
			    	</ul></td>
	        </tr>
		</tbody>
    </table>
	<div id="header_buttons">
	    <?=form_submit(array('name' => 'submit', 'value' => lang('br_save_continue'), 'class'=>'submit'))?>
		<?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
		<?php 
			if($attribute_set_id != 0){
		?>
				<?=form_submit(array('name' => 'duplicate', 'value' => lang('br_duplicate'), 'class'=>'submit'))?>
				<?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'submit'))?>
		<?php
			}
		?>
    	<div class="b2r_clearboth"><!-- --></div>
    </div>
</form>                     
<script type="text/javascript">
	$(function(){
		$('#attribute_set_edit').validate();
		$('#sel_attribute').sortable({ axis: 'y' });
		$('#title').bind('click',function(){
			var a = $(this);
			if(a.val() == '<?=lang('br_new_attribute_set')?>'){
				a.val('');
			}
		});
		$('#delete').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_attribute_set')?>')){
				window.location = '<?=$base_url?>&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_attributeset_delete&attribute_set_id=<?=$attribute_set_id?>';
			}
			return false;
		});
	});
</script>