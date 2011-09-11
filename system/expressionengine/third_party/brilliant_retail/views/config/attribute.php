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

	<table id="attributeTable" class="mainTable" cellpadding="0" cellspacing="0">
    	<thead>
	    	<tr>
	    		<th>
	    			<?=lang('br_title')?></th>
	   			<th>
					<?=lang('br_code')?></th>
				<th>
					<?=lang('br_field_type')?></th>
	    	</tr>
		</thead>
    	<tbody>
	    	<?php
		    	foreach($attributes as $a){
					$title 	= '<a href="'.$base_url.AMP.'method=config_attribute_edit&attribute_id='.$a["attribute_id"].'">'.$a["title"].'</a>';	
					echo '	<tr>
					        	<td class="cell_1">
					        		'.$title.'</td>
					        	<td class="cell_2">
					        		'.$a["code"].'</td>
					        	<td class="">
					        		'.lang($a["fieldtype"]).'</td>
					        </tr>';
				}
	    	?>
		</tbody>
    </table>
</div>                     
<script type="text/javascript">
	$(function(){
		$('#attributeTable').tablesorter({
			textExtraction: "complex",			
			widgets: ["zebra"]
		});
	});
</script>