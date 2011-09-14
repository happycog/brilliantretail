<?php
	/************************************************/
	/*	Brilliant Retail - Version 1				*/
	/*												*/
	/*	Author: David Dexter (david@brillian2.com) 	*/
	/*	Version: 0.1	*/
	/*	Date: 	*/
	/*					*/
	/*	Description:	*/
	/*	*/
	/*	*/
	/*	*/
	/*												*/
	/************************************************/
	
	$cp_pad_table_template["table_open"] = '<table id="productTable" cellpadding="0" cellspacing="0" class="mainTable" style="clear:both;">';
	
	$this->table->set_template($cp_pad_table_template);

	$this->table->set_heading(
							array(
						    		'data' => '', 
						    		'width' => '5%'),
						    array(
						    		'data' => lang('br_title'), 
						    		'width' => '*'), 
						    array(
						    		'data' => lang('br_qty'), 
									'width' => '15%'),
						   	array(
						    		'data' => lang('br_type'), 
									'width' => '15%'),
						   	array(
						    		'data' => '<input type="checkbox" id="toggle_check" />', 
									'style' => 'text-align:center',
									'width' => '5%')
						   );
	$content = $this->table->generate();
?>
<div id="b2retail">

	<?=$br_header?>
   
    <div id="b2r_content">

    	<?=$br_menu?>
        
        <div id="b2r_main">

            <?=$br_logo?>
            
            <div id="b2r_panel">
                
                <div id="b2r_panel_int">
                
                	<div id="b2r_settings">
                
						<div id="b2r_page" class="b2r_category">
                            <?=form_open_multipart('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=product_batch',array('method' => 'POST', 'id' => 'productForm'))?>
                            <div id="filterMenu"> 
								<fieldset>
									<legend>Search Products</legend> 
	                            	<select id="filter" style="margin-top:5px;margin-left:10px;">
	                            		<option value=""><?=lang('br_filter_by_category')?></option>
	                            		<? foreach($categories as $c) { ?>
	                            		<option value="<?= $c['category_id'];?>" <? if ($c['category_id']==$catid){echo('selected="selected"');}?>><?= $c['title'];?></option>
	                            		<? } ?>
	                            	</select>
								</fieldset> 
							</div>								
                            	<div class="b2r_clearboth"><!-- --></div>
	                            <?=$content?>
                        		<div id="header_buttons">
	                        		<select id="action" name="action">
	                            		<option value="">------------</option> 
	                            		<option value="0"><?=lang('br_delete_selected_products')?></option>
	                            		<option value="1"><?=lang('br_enable_selected_products')?></option>
	                            		<option value="2"><?=lang('br_disable_selected_products')?></option>
	                            	</select>
	                            	<?=form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class'=>'submit', 'id' => 'batch_submit'))?>
	                        	</div>
							</form>
                        	<div class="b2r_clearboth"><!-- --></div>
                        
                    </div> <!-- b2r_dashboard --> 

                </div> <!-- b2r_panel_int -->
            </div> <!-- b2r_panel -->

    	</div> <!-- b2r_main -->

        <div class="b2r_clearboth"><!-- --></div>
        
        <?=$br_footer?>
        
    </div> <!-- b2r_content -->
    
</div> <!-- #b2retail -->

<script type="text/javascript">
	$(function(){
		var oTable = $('#productTable').dataTable({
													"iDisplayLength": 25, 
													"aoColumns": [
																		null,
																		null,
																		null,
																		null,
																		{ "bSortable": false }
																	], 
													"bProcessing": true,
													"bServerSide": true,
													"sAjaxSource": "<?=str_replace("&amp;","&",$ajax_url)?>", 
													"fnDrawCallback": function() {
														$('#toggle_check').click(function(){
															if(this.checked){
																$('input[type=checkbox]').attr('checked','checked');
															}else{
																$('input[type=checkbox]').each(function() {  
																	this.checked = false;  
																});  
															}
														});
													}
													
													});
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b><?=lang('br_clear')?></b></a></p>').insertBefore('#productTable_filter input');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
		
		$('#filter').change(function(){
			location.href="<?=$base_url;?>&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=product&cat_id="+$("#filter").val();
		})
		
		$('#batch_submit').bind('click',function(){
			var val = $('#action').val();
			var message = Array(3);
			message[0] = '<?=lang('br_confirm_batch_delete')?>';
			message[1] = '<?=lang('br_confirm_batch_enable')?>';
			message[2] = '<?=lang('br_confirm_batch_disable')?>';
			if(val == ''){
				alert('<?=lang('br_batch_action_required')?>');
				return false;
			}
			if(confirm(message[val])){
				return true;
			}else{
				return false;
			}
		});
	});
</script>