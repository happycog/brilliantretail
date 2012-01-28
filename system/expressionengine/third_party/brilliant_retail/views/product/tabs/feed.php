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

/********************/
/* SEO Tab			*/
/********************/
?>
<table cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form">
	<tr>
		<th colspan="2">
			<?=lang('nav_br_config_feeds')?></th>
	</tr>
<?php

	if ( count($feeds) > 0 ){
	  	$c=0;
	  	foreach($feeds as $f)
	  	{
	  		$sel='';
	  		foreach($product_feeds as $f1)
	  		{
	  			if ($f1['feed_id']==$f['feed_id']) {$sel='checked="checked"';}
	  		}
			
			echo '	<tr>
						<td>'.$f['feed_title'].'</td>
				    	<td><input type="checkbox" name="feed_id['.$c.']" value="'.$f['feed_id'].'"'. $sel .' /></td>
				    </tr>';
	  		$c++;
	  	}	  
	}
?>
</table>
