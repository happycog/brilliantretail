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

//Get the cart promo data

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
		$enabled 	= ($c["enabled"] == 1) 	? 	'status_on' : 'status_off' ;

		$this->table->add_row(
								$c['promo_id'],
								'<a href="'.$base_url.'&method=promo_edit&promo_id='.$c['promo_id'].'">'.$c["title"].'</a>',
								$c["code"],
								$start,
								$end, 
								'<img src="'.$theme.'images/icon_'.$enabled.'.png" />'
							);	
	}
	
	echo  $this->table->generate();

	echo $br_footer;
?>

<script type="text/javascript">
	$(function(){
		var oTable = $('.mainTable').dataTable({
										"bStateSave": true
									});
		
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b>Clear</b></a></p>').insertBefore('.mainTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('.mainTable_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>