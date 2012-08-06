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


// Header
	echo $br_header;
	
// Create the table
	$this->table->set_template($cp_table_template);
   	$this->table->set_heading(
			   					lang('br_order_id'),
			   					lang('br_order_date'),
			   					lang('br_line_items'),
			   					lang('br_order_amount'),
			   					lang('br_order_status')
			   				);
	
	$total_value = 0;

	foreach($order_collection as $order){
		$this->table->add_row(
								'<a href="'.$base_url.'&method=order_detail&order_id='.$order["order_id"].'">'.$order["order_id"].'</a>', 
								date('n/d/y',$order["created"]),
								$order['line_items'],
								$order["total"],
								$order["status"]
							);
		$total_value += $order["total"];
	}
	echo $this->table->generate();

// Footer
	echo $br_footer;
?>
<script type="text/javascript">
	$(function(){
		
		/*
			Need to inject the footer with JS cause CI Table doesn't have a footer method 
		*/
			$('.mainTable').append('<tfoot><tr><td></td><td></td><td style="text-align:right;font-weight:bold"><strong><?=lang('br_total')?></strong></td><td><?=$currency_marker.number_format($total_value,2)?></td><td></td></tr></tfoot>');
						
		/*
			Build the dynamic table
		*/
		var oTable = $('.mainTable').dataTable({
										"bStateSave": true,
										"aoColumns": [
														null, 
														null,
														null,
														null,
														{ "bSortable": false }
													]
									});
		
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b>Clear</b></a></p>').insertBefore('.mainTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('.mainTable_filter');
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