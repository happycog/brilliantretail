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
?>
<div id="b2r_page" class="b2r_category">
    <table id="admin_header" cellpadding="0" cellspacing="0">
    	<tr>
			<td>
				<?php
					echo '	<select id="select_config">';
        			foreach($submenu as $key => $val){
            			$sel = ($key == $sub_selected) ? 'selected="selected"' : '' ; 
            			echo '	<option value="'.$key.'" '.$sel.'>'.lang($key).'</option>'; 
            		}
            		echo '	</select>
                			<script type="text/javascript">
                				$(function(){
                					$(\'#select_config\').change(function(){
										window.location = \''.$base_url.'&&method=\'+$(this).val();
                					});
                				});	
                			</script>';
				?>
				<h3><?=lang('br_tax_settings')?></h3>
				<div class="b2r_clearboth"><!-- --></div>
    			<p class="b2r_addprod"><a href="<?=$base_url.AMP?>method=config_tax_new"><?=lang('br_add')?></a></p>
				</td>
		</tr>
    </table>

	<div class="b2r_clearboth"><!-- --></div>

	<table id="taxTable" class="mainTable" style="clear:both">
    	<thead>
	    	<tr>
	    		<th>
	    			<?=lang('br_title')?></th>
	   			<th>
					<?=lang('br_zone')?></th>
				<th>
					<?=lang('br_state')?></th>
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
					        	<td class="cell_2">
					        		'.$zone.'</td>
					        	<td>
					        		'.$state.'</td>
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