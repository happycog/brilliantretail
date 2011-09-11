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

//Get the cart promo data
	$tab["promo"] = '';
	
	$cp_pad_table_template["table_open"] = '<table id="promoTable" cellpadding="0" cellspacing="0" class="mainTable">';
	
	$this->table->set_template($cp_pad_table_template);
	
	$this->table->set_heading(	
								array('data' => '', 'style' => 'width:10px'), 
								array('data' => lang('br_promo_name'), 'style' => 'width:150px'),
								array('data' => lang('br_promo_code'), 'style' => 'width:70px'),
								array('data' => lang('br_promo_start_dt'), 'style' => 'width:70px'),
								array('data' => lang('br_promo_end_dt'), 'style' => 'width:70px') 
							);
	
	foreach($promo as $c){
		$start		= ($c["start_dt"] > 0) 	? 	date("n/d/y",strtotime($c["start_dt"])) : '-';
		$end 		= ($c["end_dt"] > 0) 	? 	date("n/d/y",strtotime($c["end_dt"])) : '-';
		$enabled 	= ($c["enabled"] == 1) 	? 	'status_on' : 'status_off' ;

		$this->table->add_row(
								'<img src="'.$theme.'images/icon_'.$enabled.'.png" />', 
								'<a href="'.$base_url.'&method=promo_edit&promo_id='.$c['promo_id'].'">'.$c["title"].'</a>',
								$c["code"],
								$start,
								$end 
							);	
	}
	$content = $this->table->generate();
	$this->table->clear();
	
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
		var oTable = $('#promoTable').dataTable({
										"bStateSave": true
									});
		
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b>Clear</b></a></p>').insertBefore('#promoTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('#promoTable_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>