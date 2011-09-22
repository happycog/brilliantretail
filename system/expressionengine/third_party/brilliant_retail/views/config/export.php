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
				<h3><?=lang('br_sites')?></h3>
    			<div class="b2r_clearboth"><!-- --></div>
    			</td>
		</tr>
    </table>
    
    <div class="b2r_clearboth"><!-- --></div>
    
	<table id="siteTable" class="mainTable" style="clear:both">
    	<thead>
	    	<tr>
	    		<th>
	    			<?=lang('br_title')?></th>
	    		<th>
	    			<?=lang('br_site_name')?></th>
	    	</tr>
		</thead>
    	<tbody>
	    	<?php
				foreach($stores as $s){
					echo '	<tr>
					        	<td class="cell_1">
					        		<a href="'.$base_url.AMP.'method=config_site_edit&site_id='.$s["site_id"].'">'.$s["site_label"].'</a></td>
					        	<td class="cell_2">
					        		'.$s["site_name"].'</td>
					        </tr>';
				}
	    	?>
		</tbody>
    </table>
</div> 
<script type="text/javascript">
	$(function(){
		var oTable = $('#siteTable').dataTable({
													"bStateSave": true
												});
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b><?=lang('br_clear')?></b></a></p>').insertBefore('#siteTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('#siteTable_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});
	});
</script>