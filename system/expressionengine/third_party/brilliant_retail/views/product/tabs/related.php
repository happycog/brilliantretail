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
<div class="publish_field" id="hold_br_related">

	<label class="hide_field">
		<span>
			<em class="required">*</em> <?=lang('br_related')?>
		</span>
	</label>

	<div id="sub_hold_br_related">
		
		<fieldset class="holder">
		
			
			
			
			
			
<div class="product_edit">
	
	<div class="related_search">
		<input id="related_search" type="text">
	</div>
	
	<div class="result_div product_edit">
		
		<table id="related_results" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td colspan="3">
						<?=lang('br_results')?></td>
				</tr>
				<tr>
					<th><?=lang('nav_br_product')?></th>
					<th><?=lang('br_type')?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr><td colspan="3" style="border-width:0"><?=lang('br_ft_product_search')?></td></tr>
			</tfoot>
			<tbody>
				<tr>
					
				</tr>
			</tbody>
		</table>
	
	</div>
	<table id="related_selected" width="100%" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th width="40%"><?=lang('br_title')?></th>
				<th width="20%"><?=lang('br_type')?></th>
				<th width="5%"><?=lang('br_sort')?></th>
				<th width="10%"><?=lang('delete')?></th>
			</tr>
		</thead>
		<tbody>
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
		</tbody>
	</table>
</div>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<?php 
			/*
			<table id="relatedTable" cellspacing="0" cellpadding="0" border="0" class="product_edit" width="100%">
				<tr>
					<td>
						<div class="br_fieldtype">
							<div class="br_fieldtype_search">
								<div id="related_clear" class="search_clear"><!-- clear !--></div>
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
								<table class="product_edit" id="" cellpadding="0" cellspacing="0" width="100%">
									<thead>
										<tr class="nodrag nodrop">
											<td colspan="4">
												<?=lang('br_selected_products')?>
											</td>
										</tr>
										<tr class="nodrag nodrop">
											<th><?=lang('br_title')?></th>
											<th><?=lang('br_type')?></th>
											<th><?=lang('br_sort')?></th>
											<th><?=lang('delete')?></th>
										</tr>								
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
							<div style="clear:both"><!-- --></div>
						</div>
					</td>
				</tr>
			</table>
			*/
			?>
		
		</fieldset>
	</div> <!-- /sub_hold_field -->
</div>

<script type="text/javascript">
	$(function(){
		var brSearch = $('#related_search');
		var brResult = $('#related_results');
		var brClear  = $('#related_clear');
		
		stripe_table();
		
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
	            	brResult.find('tbody tr').remove();
					
					$.each(data, function(i,item){
	            		$('<tr id="product_'+item.product_id+'"><td>'+item.title+'</td><td>'+type[item.type_id]+'</td><td width="10%"><a href="#" class="add_related {product_id:'+item.product_id+'}" ><?=lang('br_add')?></a></td></tr>').appendTo($('#related_results tbody'));
	            	});
	            	$('.add_related').unbind('click').bind('click',function(){
						_add_related($(this).metadata().product_id);
						return false;
					});

	        		stripe_table();
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
	
	function stripe_table(){
		$('#related_selected tr').removeClass('even');
		$('#related_selected tr:even').addClass('even');
	}
	
	function _add_related(product_id){
		var relatedSelected = $('#related_selected');
		var row = $('#product_'+product_id,'#related_results');
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
		var relatedSelected = $('#related_selected tbody');
		relatedSelected.sortable({
									axis:'y', 
									cursor:'move', 
									opacity:0.6,
									helper:function(e, ui) {
										ui.children().each(function() {
											$(this).width($(this).width());
										});		
										return ui;
									},
									update: stripe_table
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
		$('<tr><td colspan="4"><?=lang('br_ft_product_search')?></td></tr>').appendTo($('#related_result tbody'));
		$('#related_search').val('').focus();
		$(this).hide();
	}
</script>