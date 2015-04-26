<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
/* 	@license	http://opensource.org/licenses/OSL-3.0	*/
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

// Header
	echo $br_header;

	$cp_table_template["table_open"] = '<table id="customerTable" cellpadding="0" cellspacing="0" class="product_edit" width="100%">';

// Create the table
	$this->table->set_template($cp_table_template);
   	$this->table->set_heading(
                lang('br_customer_username'),
                lang('br_customer_email'),
				lang('br_customer_created'),
				lang('br_member_group'),
				lang('br_total'),
				lang('br_customer_history')
		);
	echo $this->table->generate();

// Footer
	echo $br_footer;

?>
<script type="text/javascript">
	$(function(){
		var oTable = $('#customerTable').dataTable({
													"sDom": "lfrt<'dataTables_footer'ip<'clear'>>",
													"sPaginationType": "full_numbers", 
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
		
		$('<p class="b2r_search_btn"><a href="#" id="clear" class="submit">Clear</a></p>').insertBefore('#customerTable_filter input');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>