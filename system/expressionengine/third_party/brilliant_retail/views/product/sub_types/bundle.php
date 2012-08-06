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
<div id="sub_type_2" class="subtypes">
	<div class="br_product_search">
		<input type="text" id="bundle_search" autocomplete="off" />
	</div>
	<div class="result_div">
		
		<table id="bundle_result" class="product_edit" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th><?=lang('nav_br_product')?></th>
					<th><?=lang('br_type')?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr><td colspan="3" style="border-width:0"><?=lang('br_bundle_product_search')?></td></tr>
			</tfoot>
			<tbody></tbody>
		</table>
	
	</div>
	<table id="bundle_selected" class="product_edit" width="100%" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th><?=lang('nav_br_product')?></th>
				<th><?=lang('br_type')?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<?php
			$i = 1;
			if(isset($products[0]["bundle"])){
				foreach($products[0]["bundle"] as $b){
					$class = ($i % 2 == 0) ? 'even' : 'odd' ;
					echo '	<tr class="'.$class.'">
								<td width="60%" style="width:auto;">
									'.$b["title"].'</td>
								<td>
									'.$product_type[$b["type_id"]].'</td>
								<td width="10%" class="move_related_row" style="text-align:center"><img src="'.$theme.'images/icon_move.png" /></td>
								<td width="10%">
									<a class="remove_bundle" href="#">'.lang('delete').'</a><input type="hidden" value="'.$b["product_id"].'" name="bundle[]">
								</td>
							</tr>';
					$i++;
				}
			}
		?>
	</table>
</div>

<script type="text/javascript">
	$(function(){
		var brSearch = $('#bundle_search');
		var brResult = $('#bundle_results');
		<?php
			echo "var type = new Array(".count($product_type).")\n";
			foreach($product_type as $key => $val){
				echo 'type['.$key.'] = "'.$val.'";';
			}
		?>
		brSearch.keyup(function(e){
			var term = $(this).val();
			if(term.length <= 3){
				brResult.find('tbody tr').remove();
				return;
			}
			$.getJSON("<?=$product_search?>",{'type':'bundle','term':term},
	        	function(data){

	            	$('#bundle_result tfoot').hide();
	            	
	            	$.each(data, function(i,item){
	            		$('	<tr id="product_'+item.product_id+'"><td>'+item.title+'</td><td>'+type[item.type_id]+'</td><td width="10%"><a href="#" class="add_bundle {product_id:'+item.product_id+'}" ><?=lang('br_add')?></a></td></tr>').appendTo($('#bundle_result tbody'));
	            	});

	            	$('.add_bundle').unbind('click').bind('click',function(){
						_add_bundle($(this).metadata().product_id);
						return false;
					});

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
		
		_remove_bundle();

	});
	
	function _add_bundle(product_id){
		var bundleSelected = $('#bundle_selected');
		var row = $('#product_'+product_id);
		new_row = row.clone();
		new_row.attr({'id':''}).find('td:eq(3)').remove();
		new_row.addClass('odd');
		new_row.find('td:eq(2)').remove();
		new_row.find('td:eq(0)').attr({'style':'width:auto','width':'60%'});
		$('<td width="10%" class="move_related_row" style="text-align:center"><img src="<?=$theme?>images/icon_move.png" /><input type="hidden" name="bundle[]" value="'+product_id+'"></td><td width="10%"><a href="#" class="remove_bundle"><?=lang('delete')?></a></td>').appendTo(new_row);
		$(new_row).appendTo(bundleSelected);
		row.remove();
		_remove_bundle();
		return false;
	}
	
	function _remove_bundle(){
		$('#bundle_selected tbody').sortable({axis:'y', cursor:'move', opacity:0.6, handle:'.move_related_row',
						helper:function(e, ui) {
							ui.children().each(function() {
								$(this).width($(this).width());
							});		
							return ui;
						},
						update: function(){
							_add_bundle();
						}
					});
		$('.remove_bundle').unbind('click').bind('click',function(){
			var bundleSelected = $('#bundle_selected');
			$(this).parent().parent().remove();
			return false;
		});
	}
</script>