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

                        	<table id="customer_tbl" cellpadding="0" cellspacing="0" class="mainTable" style="clear:both">
								<thead>
									<tr>
										<th style="width:100px"><?=lang('br_customer_username')?></th>
										<th><?=lang('br_customer_email')?></th>
										<th style="width:60px"><?=lang('br_customer_created')?></th>
										<th style="width:80px"><?=lang('br_customer_group')?></th>
										<th style="width:120px"><?=lang('br_total')?></th>
										<th style="width:80px"><?=lang('br_customer_history')?></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>

                        	<div class="b2r_clearboth"><!-- --></div>
                        
                    	</div> <!-- b2r_dashboard --> 
                    
                	</div> <!-- b2r_panel_int -->
            	</div> <!-- b2r_panel -->

    		</div> <!-- b2r_main -->

		</div> <!-- b2r_main -->

        <div class="b2r_clearboth"><!-- --></div>
        
        <?=$br_footer?>
        
    </div> <!-- b2r_content -->
    
</div> <!-- #b2retail -->

<script type="text/javascript">
	$(function(){
		var oTable = $('#customer_tbl').dataTable({
													"iDisplayLength": 25, 
													"aoColumns": [
																		null,
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
		
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b>Clear</b></a></p>').insertBefore('#customer_tbl_filter input');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>