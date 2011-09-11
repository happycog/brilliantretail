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
	
	$cp_pad_table_template["table_open"] = '<table id="productTable" cellpadding="0" cellspacing="0" class="mainTable">';
	
	$this->table->set_template($cp_pad_table_template);

	$this->table->set_heading(
							array(
						    		'data' => '', 
						    		'width' => '5%'),
						    array(
						    		'data' => lang('br_title'), 
						    		'width' => '*'), 
							array(
						    		'data' => lang('br_price'), 
						    		'width' => '20%'),
						    array(
						    		'data' => lang('br_qty'), 
									'width' => '15%'),
						   	array(
						    		'data' => lang('br_type'), 
									'width' => '15%')
						   );
	
	foreach($subscriptions as $p){
		$enabled = ($p['enabled'] == 1) ? 'status_on' : 'status_off' ;
		$this->table->add_row(
								'<img src="'.$theme.'images/icon_'.$enabled.'.png" />', 
								'<a href="'.$base_url.'&method=product_edit&product_id='.$p['product_id'].'">'.$p['title'].'</a>',
								number_format($p['price'],2),
								$p['quantity'],
								$product_type[$p['type_id']]
							);
	}
	
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

	                       	<?=$content?>
                        	
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
													"bStateSave": true
												});
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b>Clear</b></a></p>').insertBefore('#productTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('#productTable_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>