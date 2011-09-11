<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010-2011, Brilliant2.com		*/
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

$cp_pad_table_template["table_open"] = '<table id="order_tbl" cellpadding="0" cellspacing="0" class="mainTable">';
	
$this->table->set_template($cp_pad_table_template); 

$this->table->set_heading(
	array(
			'data' => lang('br_order_id'),
			'style' => 'width:40px'
		),
	array(
			'data' => lang('br_order_date'),
			'style' => 'width:60px'
		),
	array(	
			'data' => lang('br_order_customer'),
			'style' => ''
		),
	array(
			'data' => lang('br_order_amount'),
			'style' => 'width:60px'
		),
	array(
			'data' => lang('br_order_status'),
			'style' => 'width:60px'
		)
		#,array(
		#'data' => '<input type="checkbox" id="toggle_check" />', 
		#'style' => 'text-align:center',
		#'width' => '5%')
);

foreach($order_collection as $order){
	$this->table->add_row(
		array(
			'<a href="'.$base_url.'&method=order_detail&order_id='.$order["order_id"].'">'.$order["order_id"].'</a>', 
			date('n/d/y',$order["created"]),
			$order["br_fname"].' '.$order["br_lname"].' (<a href="'.BASE.'&C=myaccount&id='.$order["member_id"].'">'.$order["username"].'</a>)',
			$order["total"],
			$status[$order["status_id"]]
			#,array('data' => '<input type="checkbox" name="batch['.$order["order_id"].']" />', 'style' => 'text-align:center')
		)
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
		var oTable = $('#order_tbl').dataTable({
										"bStateSave": true,
										"aoColumns": [
														null, 
														null,
														null,
														null,
														{ "bSortable": false }
													]
									});
		
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b>Clear</b></a></p>').insertBefore('#order_tbl_filter input');
		$('<div style="clear:both"></div>').insertAfter('#order_tbl_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
		$('#toggle_check').click(function(){
			if(this.checked){
				$('input[type=checkbox]').attr('checked','checked');
			}else{
				$('input[type=checkbox]').attr('checked','');
			}
		});
	});
</script>