<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
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
	
	echo $br_header;
	
	$cp_pad_table_template["table_open"] = '<table id="reportTable" cellpadding="0" cellspacing="0" class="product_edit" width="100%">';

	$this->table->set_template($cp_pad_table_template);

	$this->table->set_heading(
							array(
						    		'data' => lang('br_title'), 
						    		'width' => '20%'),  
							array(
						    		'data' => lang('br_descr'), 
						    		'width' => '*'),
						    array(
						    		'data' => lang('br_type'), 
									'width' => '15%'),
						   	array(
						    		'data' => lang('version'), 
									'width' => '15%')
						   );
	
	foreach($reports as $r){
		$this->table->add_row(
								$r["title"],
								$r["descr"],
								lang($r["type"]),
								$r["version"]
							);
	}
	
	// Generate the table
		echo  $this->table->generate();

	// Put in our footer
		echo $br_footer;
?>
<script type="text/javascript">
	$(function(){
		var oTable = $('#reportTable').dataTable({
													"sDom": "lfrt<'dataTables_footer'ip<'clear'>>",
													"iDisplayLength": 10, 
													"sPaginationType": "full_numbers", 
													"bStateSave": true 
												});
		$('<p class="b2r_search_btn"><a href="#" id="clear" class="submit"><?=lang('br_clear')?></a></p>').insertBefore('#reportTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('#reportTable_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>