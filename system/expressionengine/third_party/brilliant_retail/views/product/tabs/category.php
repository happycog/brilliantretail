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
/* Categories Tab 	*/
/********************/
?>
<div class="publish_field" id="hold_br_catalog">

	<label class="hide_field">
		<span>
			<em class="required">*</em> <?=lang('br_catalog')?>
		</span>
	</label>

	<div id="sub_hold_br_catalog">
		
		<fieldset class="holder">

			<table id="categoryTable" cellspacing="0" cellpadding="0" border="0" width="100%">
				<thead>
					<tr>
						<th>	
							<?=lang('br_categories')?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td id="category_options">
							<?=$categories?></td>
					</tr>
				</tbody>
			</table>

		</fieldset>
	</div> <!-- /sub_hold_field -->
</div>
<script type="text/javascript">
	$(function(){
		$('#product_category_tree li').each(function(){
			var a = $(this);
			var b = a.find('ul');
			if(b.size() >= 1){
				$('<span class="anchor">&nbsp;</span>').prependTo(a);
			}else{
				$('<span class="anchor_empty">&nbsp;</span>').prependTo(a);
			}
		});

		$('#product_category_tree li.expanded').each(function(){
			var a = $(this);
			a.find('.anchor:eq(0)').addClass('anchor_show');
			a.find('ul:eq(0)').show();		
		});

		$('.anchor').bind('click',function(){
			var a = $(this);
			if(a.hasClass('anchor_show')){
				a.removeClass('anchor_show');
				a.parent().find('ul:eq(0)').slideUp();
			}else{
				a.addClass('anchor_show');
				a.parent().find('ul:eq(0)').slideDown();
			}
		});		
	});
</script>