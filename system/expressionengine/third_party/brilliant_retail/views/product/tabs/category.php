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

			<table id="categoryTable" class="product_edit" cellspacing="0" cellpadding="0" border="0" width="100%">
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
				var leaf = 'collapse';
				if(a.hasClass('expand'))
				{
					leaf = 'expand';		
				}
				
				$('<span class="anchor anchor_'+leaf+'">&nbsp;</span>').prependTo(a);
			}else{
				$('<span class="anchor anchor_empty">&nbsp;</span>').prependTo(a);
			}
		});

		$('#product_category_tree li.expanded').each(function(){
			var a = $(this);
			var b = a.find('ul:eq(0)');
			var c = a.find('.anchor:eq(0)');
			
			// Show the first matched ul element
				b.show();
				c.removeClass('anchor_collapse');

				// Make sure we aren't at the end 
				// end of a branch 
					if(c.parent().find('ul').size() > 0){
						c.addClass('anchor_expand');
					}
				
			// Walk up the tree to make sure 
			// we open non-expanded parents
				a.parents('li').each(function(){
					var d = $(this);
					// Open the ul
						d.find('ul:eq(0)').show();
					// Show the expanded anchor  
						d.find('span.anchor:eq(0)')
							.removeClass('anchor_collapse')
							.addClass('anchor_expand');
				});
		});

		$('.anchor').bind('click',function(){
			var a = $(this);
			if(a.hasClass('anchor_expand'))
			{
				a.removeClass('anchor_expand')
					.addClass('anchor_collapse')
					.parent().find('ul:eq(0)').slideUp();
			}
			else if(a.hasClass('anchor_collapse'))
			{
				a.addClass('anchor_expand')
					.removeClass('anchor_collapse')
					.parent().find('ul:eq(0)').slideDown();
			}
		});		
	});
</script>