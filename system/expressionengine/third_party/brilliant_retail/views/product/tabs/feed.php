<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
/* 	@license	http://opensource.org/licenses/OSL-3.0	*/
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
<div class="publish_field" id="hold_br_feeds" style="border-bottom:1px solid #D1D5DE">

	<label class="hide_field">
		<span>
			<?=lang('nav_br_config_feeds')?>
		</span>
	</label>

	<div id="sub_hold_br_feeds">
		<fieldset class="holder">
			<table cellspacing="0" cellpadding="0" border="0"  class="product_edit" width="100%">
				<thead>
					<tr>
						<th>
							<?=lang('br_title')?></th>
					</tr>
				</thead>
				<tbody>
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
										<td><input type="checkbox" name="feed_id['.$c.']" value="'.$f['feed_id'].'"'. $sel .' />&nbsp;'.$f['feed_title'].'</td>
								    </tr>';
					  		$c++;
					  	}	  
					}
				?>
				</tbody>
			</table>
		</fieldset>
	</div>
</div>