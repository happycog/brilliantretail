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
	echo form_open_multipart('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_category_update',
					array(	'method' 	=> 'POST', 
							'id' 		=> 'category_update_order',
							'class' 	=> '', 
							'encrypt' 	=> 'multipart/form-data'));
?>
	<input type="hidden" id="action" name="action" value="update" />
	<input type="hidden" id="category_id" name="category_id" value="<?=$category["category_id"]?>" />
	

<div id="b2r_page" class="b2r_category">
   <table id="admin_header" cellpadding="0" cellspacing="0">
    	<tr>
			<td>
				<?php
					echo '	<select id="select_config">';
        			foreach($submenu as $key => $val){
            			$sel = ($key == $sub_selected) ? 'selected="selected"' : '' ; 
            			echo '	<option value="'.$key.'" '.$sel.'>'.lang($key).'</option>'; 
            		}
            		echo '	</select>
                			<script type="text/javascript">
                				$(function(){
                					$(\'#select_config\').change(function(){
										window.location = \''.$base_url.'&&method=\'+$(this).val();
                					});
                				});	
                			</script>';
				?>
				<h3><?=lang('br_categories')?></h3>
				<div class="b2r_clearboth"><!-- --></div>
    			<div id="header_buttons">
				    <?php
				    	#form_submit(array('name' => 'submit', 'value' => lang('br_save_continue'), 'class'=>'submit'))
				    ?>
					<?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
					<?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'delete'))?>
					<p class="b2r_cancel"><a href="<?=$base_url.'&method=config_category'?>"><?= lang('br_cancel'); ?></a></p>
			    	<div class="b2r_clearboth"><!-- --></div>
			    </div>
    		</td>
		</tr>
    </table>
	
	<div class="b2r_clearboth"><!-- --></div>


<table id="category_update_tbl" class="mainTable" style="margin-top:0;">
	<thead>
		<tr class="odd">
			<th colspan="2">
				<?=lang('br_category_settings')?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
	    	<td class="cell_1">
	    		<?=lang('br_title')?> *</td>
	    	<td>
	        	<input name="title" id="title" value="<?=$category["title"]?>" type="text" class="{required:true}" /></td>
		</tr>	
    	<tr class="odd">
        	<td>
        		<?=lang('br_url_title')?> *</td>
        	<td>
	        	<input name="url_title" id="url_title" value="<?=$category["url_title"]?>" type="text" class="{required:true}" /></td>
	    </tr>	
		<tr>
	    	<td>
	    		<?=lang('br_enabled')?></td>
	    	<td>
				<select name="enabled" id="enabled" title="input three" class="">
					<?php
						$sel = ($category["enabled"] == 1) ? 'selected="selected"' : '';
							echo '<option value="1" '.$sel.'>'.lang('br_yes').'</option>';
						$sel = ($category["enabled"] == 0) ? 'selected="selected"' : '';
							echo '<option value="0" '.$sel.'>'.lang('br_no').'</option>';
					?>
				</select></td>
		</tr>
		<tr class="odd">
        	<td>
        		<?=lang('br_detail')?> *</td>
        	<td>
	        	<textarea name="detail" id="detail" class="ckeditor"><?=$category["detail"]?></textarea></td>
	    </tr>
		<tr>
	    	<td>
	    		<?=lang('br_images')?></td>
	    	<td>
				<?=$category["image"]?>
				<input type="file" name="image" id="image" /></td>
		</tr>	
	    <tr class="odd">
        	<td>
        		<?=lang('br_template_path')?></td>
        	<td>
            	<input name="template_path" id="template_path" value="<?=$category["template_path"]?>" type="text" /></td>
    	</tr>	
		<tr class="odd">
			<th colspan="2">
				<?=lang('br_category_settings')?></th>
		</tr>
		<tr class="odd">
        	<td>
        		<?=lang('br_meta_title')?></td>
        	<td>
            	<input name="meta_title" id="meta_title" value="<?=$category["meta_title"]?>" type="text" /></td>
    	</tr>	
    	<tr>
        	<td>
        		<?=lang('br_meta_descr')?></td>
        	<td>
            	<textarea name="meta_descr" id="meta_descr"><?=$category["meta_descr"]?></textarea></td>
    	</tr>	
    	<tr class="odd">
        	<td>
        		<?=lang('br_meta_keyword')?></td>
        	<td>
            	<textarea name="meta_keyword" id="meta_keyword" title="input two" class=""><?=$category["meta_keyword"]?></textarea></td>
        </tr>	     	
	</tbody>
</table>

<div class="b2r_clearboth"><!-- --></div>

<table id="product_sort_tbl" class="mainTable" style="margin-top:25px;">
	<thead>
		<tr class="odd">
			<th colspan="2">
				<?=lang('br_product_sort')?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($products as $items) { ?>
		<tr class="odd">
        	<td style="vertical-align:middle;">
        		<?= $items['title'] ?>
        	</td>
        	<td style="text-align:right;">
	        	<input name="items[<?= $items['id']?>]" value="<?= $items['sort_order'] ?>" type="text" style="width:25px;" /></td>
	    </tr>
	<?php } ?>
	</tbody>
	<tr>
    		<td colspan="2" style="text-align:right">
    	    	<input type="submit" value="<?=lang('br_save_changes')?>" /><br/>
    			<br />
    			* Required Field</td>
    	</tr>
</table>
</div>
</form>
<script type="text/javascript">
<!--
$(function() {
	$('#delete').bind('click',function(){
		if(confirm('<?=lang('br_confirm_delete_category')?>')){
			$('#action').val('delete');
			$('#category_update_order').submit();
		}
	});
});
-->
</script>
