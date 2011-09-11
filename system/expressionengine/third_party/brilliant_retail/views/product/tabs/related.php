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
<table id="relatedTable" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="clear:both">
	<tr>
		<th>
			<?=lang('br_related')?></th>
	</tr>
	<tr class="even">
		<td>
			<div class="br_fieldtype" style="margin:0">
				<div class="br_fieldtype_search">
							<div id="product_clear" class="search_clear"><!-- clear !--></div>
							<div class="search"> 
								<input id="related_search" autocomplete="off" type="text">
							</div>
							<div id="related_result" class="result_div">
								<table id="related_results" width="100%" cellpadding="0" cellspacing="0">
									<tr><td colspan="4"><?=lang('br_ft_product_search')?></td></tr>
								</table>
							</div>
						</div>
						<div class="br_fieldtype_results">
							<table class="subTable" id="related_selected" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<th colspan="4">
										<?=lang('br_selected_products')?>
									</th>
								</tr>
								<tr class="nodrag nodrop">
									<td>
										<?=lang('br_title')?></td>
									<td>
										<?=lang('br_type')?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>								
								<?php
									$i = 0;
									foreach($products[0]["related"] as $rel){
										echo '	<tr>
													<td width="60%">
														'.$rel["title"].'</td>
													<td width="20%">
														'.$product_type[$rel["type_id"]].'</td>
													<td width="10%" class="move_related_row" style="text-align:center">
														<img src="'.$theme.'images/icon_move.png" /></td>
													<td width="10%">
														<a class="remove_related" href="#">'.lang('delete').'</a><input type="hidden" value="'.$rel["product_id"].'" name="related['.$rel["product_id"].']">
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
		var brSearch = $('#related_search');
		var brResult = $('#related_results');
		var brClear  = $('#product_clear');
		
		_restripe_related();
		
		<?php
			echo "var type = new Array(".count($product_type).")\n";
			foreach($product_type as $key => $val){
				echo 'type['.$key.'] = "'.$val.'";';
			}
		?>
		brSearch.keyup(function(e){
			var term = $(this).val();
			if(term.length < 3){
				return;
			}
			brClear.show();
			brClear.bind('click',_clear_search);

			$.getJSON("<?=$product_search?>",{'type':'related','term':term},function(data){
	            	brResult.find('tr').remove();
					
					$.each(data, function(i,item){
	            		$('<tr id="product_'+item.product_id+'"><td>'+item.title+'</td><td>'+type[item.type_id]+'</td><td>'+item.price+'</td><td width="10%"><a href="#" class="add_related {product_id:'+item.product_id+'}" ><?=lang('br_add')?></a></td></tr>').appendTo(brResult);
	            	});
	            	$('.add_related').unbind('click').bind('click',function(){
						_add_related($(this).metadata().product_id);
						return false;
					});

	        		_restripe_related();
	        	}
	        );
		});
		brSearch.keypress( function(e) {
			/* Prevent default */
			if ( e.keyCode == 13 )
			{
				return false;
			}
		});
		
		// Bind the remove related on edit
			_remove_related();

	});
	
	function _restripe_related(){
		$('#related_selected tr').removeClass('even');
		$('#related_selected tr:even').addClass('even');
	}
	
	function _add_related(product_id){
		var relatedSelected = $('#related_selected');
		var row = $('#product_'+product_id);
		new_row = row.clone();
		new_row.attr({'id':''}).find('td:eq(3)').remove();
		new_row.find('td:eq(2)').remove();
		new_row.find('td:eq(0)').attr({'style':'width:auto','width':'60%'});
		$('<td width="10%" class="move_related_row" style="text-align:center"><img src="<?=$theme?>images/icon_move.png" /></td><td width="10%"><a href="#" class="remove_related">remove</a><input type="hidden" name="related[]" value="'+product_id+'"></td>').appendTo(new_row);
		$(new_row).appendTo(relatedSelected);
		row.remove();
		relatedSelected.find('tr').removeClass('even').removeClass('odd');
		relatedSelected.find('tr:even').addClass('even');
		relatedSelected.find('tr:odd').addClass('odd');
		_remove_related(); 
		return false;
		
	}
	function _remove_related(){
		var relatedSelected = $('#related_selected');
		relatedSelected.tableDnD({
										dragHandle:'move_related_row',
										onDragClass: 'tDnD_whileDrag',  
										onDrop: _restripe_related
									});
			
		$('.remove_related').unbind('click').bind('click',function(){
			var relatedSelected = $('#related_selected');
			relatedSelected.find('tr').removeClass('even').removeClass('odd');
			$(this).parent().parent().remove();
			relatedSelected.find('tr:even').addClass('even');
			relatedSelected.find('tr:odd').addClass('odd');
			return false;
		});
	}
	
	function _clear_search(){
		$('#related_result tr').remove();
		$('<tr><td colspan="4"><?=lang('br_ft_product_search')?></td></tr>').appendTo($('#related_result'));
		$('#related_search').val('').focus();
		$(this).hide();
	}
</script>