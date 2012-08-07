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

echo form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_attributeset_update',
				array(	'method' 	=> 'POST', 
						'id' 		=> 'attribute_set_edit',
						'class' 	=> 'b2r_category', 
						'encrypt' 	=> 'multipart/form-data'),
				array(	'attribute_set_id' => $attribute_set_id));
?>

<table id="attribute_tbl" class="product_edit" width="100%" cellpadding="0" cellspacing="0">
	<thead>
    	<tr>
    		<th width="35%">
    			<?=lang('br_attribute_set_settings')?></th>
    		<th>
    			&nbsp;</th>
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
		    	<table id="sel_attribute" class="product_edit" cellpadding="0" cellspacing="0" width="100%">
		    		<thead>
		    			<tr>
		    				<th width="90%">
		    					<?=lang('br_attributes')?></th>
		    				<th width="10%">
		    					<?=lang('br_sort')?></th>
		    			</tr>
		    		</thead>
					<tbody>
				    	<?php
					    	foreach($attributes as $a){
								$sel = ($a["selected"] == 1) ? 'checked' : '' ;
								echo '	<tr>
											<td>
												<input type="checkbox" name="attr[]" value="'.$a["attribute_id"].'" '.$sel.' />&nbsp;'.$a["title"].'</td>
											<td class="move_attr_row">
												<img src="'.$theme.'images/move.png" /></td>
										</tr>';
							}
				    	?>
					</tbody>
		    	</table></td>
        </tr>
	</tbody>
</table>
	<div id="bottom_buttons">
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
		
		
		$('#sel_attribute tbody').sortable({
										axis:'y', 
										cursor:'move', opacity:0.6, handle:'.move_attr_row',
										helper:function(e, ui) {
											ui.children().each(function() {
												$(this).width($(this).width());
											});		
											return ui;
										},
										update: function(){
											_restripe_images() 
										}
									});
		
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