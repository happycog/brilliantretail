<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2012						*/
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
<table id="addonTable" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="clear:both">
	<tr>
		<th>
			<?=lang('br_addon')?></th>
	</tr>
	<tr class="even">
		<td style="padding:0">
			<div class="br_fieldtype" style="margin:0;border-width:0">
				<div class="br_fieldtype_search">
							<div id="addon_clear" class="search_clear"><!-- clear !--></div>
							<div class="search"> 
								<input id="addon_search" autocomplete="off" type="text">
							</div>
							<div id="addon_result" class="result_div">
								<table id="addon_results" width="100%" cellpadding="0" cellspacing="0">
									<tr><td colspan="4"><?=lang('br_ft_product_search')?></td></tr>
								</table>
							</div>
						</div>
						<div class="br_fieldtype_results">
							<table class="subTable" id="addon_selected" cellpadding="0" cellspacing="0" width="100%">
								<tr class="nodrag nodrop">
									<th colspan="4">
										<?=lang('br_selected_products')?>
									</th>
								</tr>
								<tr class="nodrag nodrop">
									<td>
										<b><?=lang('br_title')?></b></td>
									<td>
										<b><?=lang('br_type')?></b></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>								
								<?php
									$i = 0;
									foreach($products[0]["addon"] as $rel){
										echo '	<tr>
													<td width="60%">
														'.$rel["title"].'</td>
													<td width="20%">
														'.$product_type[$rel["type_id"]].'</td>
													<td width="10%" class="move_addon_row" style="text-align:center">
														<img src="'.$theme.'images/move.png" /></td>
													<td width="10%">
														<a class="remove_addon" href="#"><img src="'.$theme.'images/delete.png" alt="'.lang('delete').'" title="'.lang('delete').'" /></a><input type="hidden" value="'.$rel["product_id"].'" name="addon['.$rel["product_id"].']">
													</td>
												</tr>';
										$i++;
									}
								?>
							</table>
						</div>
						<div style="clear:both"><!-- --></div>
			</div>
			</td>
	</tr>
</table>



<script type="text/javascript">
	$(function(){
		var brAddonSearch = $('#addon_search');
		var brAddonResult = $('#addon_results');
		var brAddonClear  = $('#addon_clear');
		
		stripe_table();
		
		<?php
			echo "var type = new Array(".count($product_type).")\n";
			foreach($product_type as $key => $val){
				echo 'type['.$key.'] = "'.$val.'";';
			}
		?>
		brAddonSearch.keyup(function(e){
			var term = $(this).val();
			if(term.length < 3){
				return;
			}
			brAddonClear.show();
			brAddonClear.bind('click',_addon_clear_search);

			$.getJSON("<?=$product_search?>",{'type':'addon','term':term},function(data){
	            	brAddonResult.find('tr').remove();
					
					$.each(data, function(i,item){
	            		$('<tr id="product_'+item.product_id+'"><td>'+item.title+'</td><td>'+type[item.type_id]+'</td><td>'+item.price+'</td><td width="10%"><a href="#" class="add_addon {product_id:'+item.product_id+'}" ><?=lang('br_add')?></a></td></tr>').appendTo(brAddonResult);
	            	});
	            	$('.add_addon').unbind('click').bind('click',function(){
						_add_addon($(this).metadata().product_id);
						return false;
					});

	        		stripe_table();
	        	}
	        );
		});
		brAddonSearch.keypress( function(e) {
			/* Prevent default */
			if ( e.keyCode == 13 )
			{
				return false;
			}
		});
		
		// Bind the remove addon on edit
			_remove_addon();

	});
	
	function stripe_table(){
		$('#addon_selected tr').removeClass('even');
		$('#addon_selected tr:even').addClass('even');
	}
	
	function _add_addon(product_id){
		var addonSelected = $('#addon_selected');
		var row = $('#product_'+product_id,'#addon_results');
		new_row = row.clone();
		new_row.attr({'id':''}).find('td:eq(3)').remove();
		new_row.find('td:eq(2)').remove();
		new_row.find('td:eq(0)').attr({'style':'width:auto','width':'60%'});
		$('<td width="10%" class="move_addon_row" style="text-align:center"><img src="<?=$theme?>images/move.png" /></td><td width="10%"><a href="#" class="remove_addon">remove</a><input type="hidden" name="addon[]" value="'+product_id+'"></td>').appendTo(new_row);
		$(new_row).appendTo(addonSelected);
		row.remove();
		addonSelected.find('tr').removeClass('even').removeClass('odd');
		addonSelected.find('tr:even').addClass('even');
		addonSelected.find('tr:odd').addClass('odd');
		_remove_addon(); 
		return false;
		
	}
	function _remove_addon(){
		var addonSelected = $('#addon_selected');
		addonSelected.tableDnD({
										dragHandle:'move_addon_row',
										onDragClass: 'tDnD_whileDrag',  
										onDrop: stripe_table
									});
			
		$('.remove_addon').unbind('click').bind('click',function(){
			var addonSelected = $('#addon_selected');
			addonSelected.find('tr').removeClass('even').removeClass('odd');
			$(this).parent().parent().remove();
			addonSelected.find('tr:even').addClass('even');
			addonSelected.find('tr:odd').addClass('odd');
			return false;
		});
	}
	
	function _addon_clear_search(){
		$('#addon_result tr').remove();
		$('<tr><td colspan="4"><?=lang('br_ft_product_search')?></td></tr>').appendTo($('#addon_result tbody'));
		$('#addon_search').val('').focus();
		$(this).hide();
	}
</script>