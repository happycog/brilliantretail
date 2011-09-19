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

$content = '<table id="order_tbl" cellpadding="0" cellspacing="0" class="mainTable">
			<thead>
				<tr>
					<th style="width:40px">'.lang('br_order_id').'</th>
					<th style="width:60px">'.lang('br_order_date').'</th>
					<th>'.lang('br_line_items').'</th>
					<th>'.lang('br_order_amount').'</th>
					<th>'.lang('br_order_status').'</th>
				</tr>
			<thead>';

$total_value = 0;

foreach($order_collection as $order){
	$row[] = array(
						'<a href="'.$base_url.'&method=order_detail&order_id='.$order["order_id"].'">'.$order["order_id"].'</a>', 
						date('n/d/y',$order["created"]),
						$order['line_items'],
						$order["total"],
						$order["status"]
					);
	$total_value += $order["total"];
}
$content .= '<tfoot>
			 	<tr>
			 		<td colspan="3" style="text-align:right;font-weight:bold;">'.lang('br_total').'</td>
			 		<td>
			 			<strong>'.$currency_marker.number_format($total_value,2).'</strong></td>
			 		<td>
			 			&nbsp;</td>
			 	</tr>
			 </tfoot>
			 <tbody>';
			 
if(isset($row)){
	foreach($row as $r){
	$content .= '	<tr>
						<td>'.$r[0].'</td>
						<td>'.$r[1].'</td>
						<td>'.$r[2].'</td>
						<td>'.$r[3].'</td>
						<td>'.$r[4].'</td>
					</tr>';
	}
}
$content .= '		</tbody>
				</table>';
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