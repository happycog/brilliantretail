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
<div id="b2r_page" class="b2r_category">
	<table id="taxTable" class="mainTable" cellpadding="0" cellspacing="0" style="clear:both">
    	<thead>
	    	<tr>
	    		<th>
	    			<?=lang('br_title')?></th>
	   			<th>
					<?=lang('br_zone')?></th>
				<th>
					<?=lang('br_state')?></th>
				<th>
					<?=lang('br_zip_code')?></th>
				<th>
					<?=lang('br_rate')?></th>
	    	</tr>
		</thead>
    	<tbody>
	    	<?php
	    		$i = 1;
		    	foreach($tax as $t){
					$zone = ($t["zone"] == '') ? '<em>'.lang('br_all_zones').'</em>' : $t["zone"];
					$state = ($t["state"] == '') ? '<em>'.lang('br_all_states').'</em>' : $t["state"];
					echo '	<tr>
					        	<td class="cell_1">
					        		<a href="'.$base_url.'&method=config_tax_edit&tax_id='.$t["tax_id"].'">'.$t["title"].'</td>
					        	<td>
					        		'.$zone.'</td>
					        	<td>
					        		'.$state.'</td>
					        	<td>
					        		'.$t["zipcode"].'</td>
					        	<td>
					        		'.$t["rate"].'%</td>
					    	</tr>';
					$i++;
				}
	    	?>
		</tbody>
    </table>
</div>
<script type="text/javascript">
	$(function(){
		var oTable = $('#taxTable').dataTable({
													"bStateSave": true
												});
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b><?=lang('br_clear')?></b></a></p>').insertBefore('#taxTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('#taxTable_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>