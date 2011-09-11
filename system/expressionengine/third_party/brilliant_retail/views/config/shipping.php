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
				<h3><?=lang('br_shipping_methods')?></h3>
				<div class="b2r_clearboth"><!-- --></div>
    		</td>
		</tr>
    </table>

    <div class="b2r_clearboth"><!-- --></div>
    
	<table id="shippingTable" class="mainTable" style="clear:both">
    	<thead>
	    	<tr>
	    		<th>	
	    			&nbsp;</th>
	    		<th>
	    			<?=lang('br_title')?></th>
	   			<th>
					<?=lang('br_description')?></th>
				<th>
					<?=lang('version')?></th>
				<th>
					<?=lang('br_action')?></th>
	    	</tr>
		</thead>
    	<tbody>
	    	<?php
		    	foreach($modules as $m){
					if(isset($m["config_id"])){
						if($m["enabled"] == 1){
							$enabled = 'status_on';
						}else{
							$enabled = 'status_off' ;
						}
						$class = 'installed';
						$link 	= '<a href="'.$base_url.AMP.'method=config_shipping_remove&config_id='.$m["config_id"].'&code='.$m["code"].'" class="remove">'.lang('delete').'</a>';
						if($m['has_options'] == 0){
							$title 	= '<b>'.$m["title"].'</b>';	
						}else{
							$title 	= '<a href="'.$base_url.AMP.'method=config_shipping_edit&config_id='.$m["config_id"].'&code='.$m["code"].'">'.$m["title"].'</a>';	
						}
					}else{
						$enabled = 'status_off' ;
						$class 	= 'not_installed';
						$link 	= '<a href="'.$base_url.AMP.'method=config_shipping_install&type='.$m["type"].'&code='.$m["code"].'" class="install">'.lang('br_install').'</a>';
						$title 	= $m["title"];	
					}
					
					
					echo '	<tr>
					        	<td>
					        		<img src="'.$theme.'images/icon_'.$enabled.'.png" /></td /> 
					        	<td class="cell_1 '.$class.'">
					        		<span style="display:none">'.$m["title"].'</span>
					        		'.$title.'</td>
					        	<td class="cell_2 '.$class.'">
					        		'.$m["descr"].'</td>
					        	<td class="'.$class.'">
					        		'.$m["version"].'</td>
					        	<td class="'.$class.'"> 
					            	'.$link.'</td>
					    	</tr>';
				}
	    	?>
		</tbody>
    </table>
</div>                     
<script type="text/javascript">
	$(function(){
		var oTable = $('#shippingTable').dataTable({
													"bStateSave": true
												});
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b><?=lang('br_clear')?></b></a></p>').insertBefore('#shippingTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('#shippingTable_filter');
		$('#clear').click(function(){
										oTable.fnFilterClear();
										return false
									});

		$('a.remove').bind('click',function(){
			if(confirm('Are you sure you want to delete the selected module? All associated configuration data will be permanently deleted.')){
				return true;
			}else{
				return false;
			}
		});
	});
</script>

