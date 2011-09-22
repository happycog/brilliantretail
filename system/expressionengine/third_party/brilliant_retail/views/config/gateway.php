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

	<table id="gatewayTable" class="mainTable" cellpadding="0" cellspacing="0">
    	<thead>
	    	<tr>
	    		<th>
	    			<?=lang('br_title')?></th>
	   			<th>
					<?=lang('br_descr')?></th>
				<th>
					<?=lang('version')?></th>
				<th>
					<?=lang('action')?></th>
	    	</tr>
		</thead>
    	<tbody>
	    	<?php
		    	foreach($modules as $m){
					if($m["installed"] == 1){
						$enabled = ($m["enabled"] == 1)? '' : 'disabled';
						$class 	= '';
						$link 	= '<a href="'.$base_url.AMP.'method=config_gateway_remove&config_id='.$m["config_id"].'&code='.$m["code"].'&type='.$m["type"].'" class="remove">'.lang('delete').'</a>';
						$title 	= '<a href="'.$base_url.AMP.'method=config_gateway_edit&config_id='.$m["config_id"].'&code='.$m["code"].'" class="'.$enabled.'">'.$m["title"].'</a>';	
					}else{
						$class 	= 'not_installed';
						$link 	= '<a href="'.$base_url.AMP.'method=config_gateway_install&type='.$m["type"].'&code='.$m["code"].'" class="install">'.lang('install').'</a>';
						$title 	= $m["title"];	
					}
					
					echo '	<tr>
					        	<td class="cell_1">
					        		<span style="display:none">'.$m["title"].'</span>
					        		'.$title.'</td>
					        	<td class="cell_2">
					        		'.$m["descr"].'</td>
					        	<td class="'.$class.'">
					        		'.$m["version"].'</td>
					        	<td class="cell_4"> 
					            	'.$link.'</td>
					    	</tr>';
				}
	    	?>
		</tbody>
    </table>
</div>                     
<script type="text/javascript">
	$(function(){
		$('#gatewayTable').tablesorter({
			headers: {},
        	textExtraction: "complex",			
			widgets: ["zebra"]
		});
	});
</script>