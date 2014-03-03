<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2014						*/
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

//Get the cart promo data
	
	$cp_pad_table_template["table_open"] = '<table id="promoTable" cellpadding="0" cellspacing="0" class="product_edit" width="100%">';
		
	$this->table->set_template($cp_pad_table_template);
	
	$this->table->set_heading(	
								lang('id'),
								lang('br_title'),
								lang('br_promo_code'),
								lang('br_promo_start_dt'),
								lang('br_promo_end_dt'),
								lang('status') 
							);
	
	foreach($promo as $c){
		$start		= ($c["start_dt"] > 0) 	? 	date("n/d/y",strtotime($c["start_dt"])) : '-';
		$end 		= ($c["end_dt"] > 0) 	? 	date("n/d/y",strtotime($c["end_dt"])) : '-';
		$enabled 	= ($c["enabled"] == 1) 	? 	lang('br_enabled') : lang('br_disabled') ;

		$this->table->add_row(
								$c['promo_id'],
								'<a href="'.$base_url.'&method=promo_edit&promo_id='.$c['promo_id'].'">'.$c["title"].'</a>',
								$c["code"],
								$start,
								$end, 
								$enabled
							);	
	}
	
	echo  $this->table->generate();

	echo $br_footer;
?>

<script type="text/javascript">
	$(function(){
		var oTable = $('#promoTable').dataTable({
										"sDom": "lfrt<'dataTables_footer'ip<'clear'>>",
													"iDisplayLength": 25, 
													"sPaginationType": "full_numbers", 
													"bStateSave": true
									});
		
		$('<p class="b2r_search_btn"><a href="#" id="clear" class="submit"><?=lang('br_clear')?></a></p>').insertBefore('#promoTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('#promoTable_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>