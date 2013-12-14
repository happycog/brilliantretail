<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2013						*/
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
<table id="attributeSetTable" class="product_edit" width="100%" cellpadding="0" cellspacing="0">
	<thead>
    	<tr>
    		<th style="width:30px;">
    			<?=lang('id')?></th>
    		<th>
    			<?=lang('br_title')?></th>
    	</tr>
	</thead>
	<tbody>
    	<?php
	    	foreach($attributes as $a){
				$title 	= '<a href="'.$base_url.AMP.'method=config_attributeset_edit&attribute_set_id='.$a["attribute_set_id"].'">'.$a["title"].'</a>';	
				echo '	<tr>
				        	<td>
				        		'.$a["attribute_set_id"].'</td>
				        	<td>'.$title.'</td>
				        </tr>';
			}
    	?>
	</tbody>
</table>
<script type="text/javascript">
	$(function(){
		$('#attributeSetTable').tablesorter({
			textExtraction: "complex" 			
		});
	});
</script>

