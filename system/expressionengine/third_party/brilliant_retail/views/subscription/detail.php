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
	
	$cp_pad_table_template["table_open"] = '<table id="subscriptionTable" cellpadding="0" cellspacing="0" class="mainTable" style="clear:both;">';
	
	$this->table->set_template($cp_pad_table_template);

	$this->table->set_heading(
							array(
						    		'data' => lang('br_subscrition_id'),
						    		'width' => '5%'),
						    array(
						    		'data' => lang('br_order_customer'), 
						    		'width' => '*'), 
						    array(
						    		'data' => lang('br_order_date'), 
						    		'width' => '15%'), 
						    array(
						    		'data' => lang('br_length'), 
						    		'width' => '15%'), 
						    array(
						    		'data' => lang('br_next_renewal'), 
						    		'width' => '15%'), 
						    array(
						    		'data' => lang('br_price'), 
									'width' => '15%'),
						   	array(
						    		'data' => lang('br_type'), 
									'width' => '15%') 
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
                            
                            details
                            
                        	<div class="b2r_clearboth"><!-- --></div>
                        
                    </div> <!-- b2r_dashboard --> 

                </div> <!-- b2r_panel_int -->
            </div> <!-- b2r_panel -->

    	</div> <!-- b2r_main -->

        <div class="b2r_clearboth"><!-- --></div>
        
        <?=$br_footer?>
        
    </div> <!-- b2r_content -->
    
</div> <!-- #b2retail -->