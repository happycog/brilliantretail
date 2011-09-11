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
<div id="sub_type_2" class="subtypes">
	<div class="search">
		<?=lang('search')?>
		<input type="text" id="bundle_search">
	</div>
	<h4><?=lang('add_products')?></h4>
	<div id="bundle_result" class="result_div">
		<table id="bundle_results" width="100%" cellpadding="0" cellspacing="0">
			<tr><td colspan="4" style="background-color:#fff"><?=lang('bundle_product_search')?></td></tr>
		</table>
	</div>
	<p>&nbsp;</p>
	<h4><?=lang('bundle_products')?></h4>
	<table id="bundle_selected" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th><?=lang('product')?></th>
			<th><?=lang('type')?></th>
			<th>&nbsp;</th>
		</tr>
		<?php
			$i = 1;
			foreach($products[0]["bundle"] as $b){
				$class = ($i % 2 == 0) ? 'even' : 'odd' ;
				echo '	<tr class="'.$class.'">
							<td width="60%" style="width:auto;">
								'.$b["title"].'</td>
							<td>
								'.$product_type[$b["type_id"]].'</td>
							<td width="10%">
								<a class="remove_bundle" href="#">remove</a><input type="hidden" value="'.$b["product_id"].'" name="bundle[]">
							</td>
						</tr>';
				$i++;
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
				brResult.find('tr').remove();
				return;
			}
			$.getJSON("<?=$product_search?>",{'type':'bundle','term':term},
	        	function(data){
	            	brResult.find('tr').remove();
					$.each(data, function(i,item){
	            		$('	<tr id="product_'+item.product_id+'"><td>'+item.title+'</td><td>'+type[item.type_id]+'</td><td>'+item.price+'</td><td width="10%"><a href="#" class="add_bundle {product_id:'+item.product_id+'}" ><?=lang('add')?></a></td></tr>').appendTo(brResult);
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
		new_row.find('td:eq(2)').remove();
		new_row.find('td:eq(0)').attr({'style':'width:auto','width':'60%'});
		$('<td width="10%"><a href="#" class="remove_bundle">remove</a><input type="hidden" name="bundle[]" value="'+product_id+'"></td>').appendTo(new_row);
		$(new_row).appendTo(bundleSelected);
		row.remove();
		bundleSelected.find('tr:even').addClass('even');
		bundleSelected.find('tr:odd').addClass('odd');
		_remove_bundle();
		return false;
	}
	
	function _remove_bundle(){
		$('.remove_bundle').unbind('click').bind('click',function(){
			var bundleSelected = $('#bundle_selected');
			bundleSelected.find('tr').removeClass('even').removeClass('odd');
			$(this).parent().parent().remove();
			bundleSelected.find('tr:even').addClass('even');
			bundleSelected.find('tr:odd').addClass('odd');
			return false;
		});
	}
</script>