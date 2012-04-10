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
<div id="b2retail">

	<?=$br_header?>
   
    <div id="b2r_content">

    	<?=$br_menu?>
        
        <div id="b2r_main">
        
            <?=$br_logo?>
            
            <div id="b2r_panel">
                
                <div id="b2r_panel_int">
                
                	<div id="b2r_settings">

					<?=form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=promo_update', array('method' => 'POST', 'id' => 'promoForm','encrypt' => 'multipart/form-data'),$hidden)?>
					<div id="b2r_page" class="b2r_category">
<?php
	$start = ($promo[0]["start_dt"] > 0) ? date("n/d/y",strtotime($promo[0]["start_dt"])) : '';
	$end = ($promo[0]["end_dt"] > 0) ? date("n/d/y",strtotime($promo[0]["end_dt"])) : '';
?>
	<table id="promoTableEdit" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form">
		<tr>
			<th colspan="2"><?=lang('br_details')?></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_promo_title').' *'?></td>
			<td>
				<input 	type="text" 
						name="title"
						value="<?=$promo[0]["title"]?>" 
						class="{required:true}"
						title="<?=lang('br_product_title').' '.lang('br_is_required')?>" /></td>
		</tr>	
		<tr>
			<td>
				<?=lang('br_promo_code')?> *</td>
			<td>
				<input 	type="text" 
						name="code"
						value="<?=$promo[0]["code"]?>" 
						class="{required:true}"
						title="<?=lang('br_code').' '.lang('br_is_required')?>" /></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_promo_descr').' *'?></td>
			<td>
				<input 	type="text" 
						name="descr"
						value="<?=$promo[0]["descr"]?>" 
						class="{required:true}"
						title="<?=lang('br_descr').' '.lang('br_is_required')?>" /></td>
		</tr>
		<tr>
			<td>
				<?=lang('status')?></td>
			<td>
				<select name="enabled">
					<?php
						$opt = array(1 => lang('br_enabled'), 0 => lang('br_disabled'));
						$sel = $promo[0]["enabled"];
						
						foreach($opt as $key => $val){
							$selected = ($sel == $key) ? 'selected' : '';
							echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
						}
					?>
				</select></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_discount_type')?></td>
			<td>
				<select name="discount_type" id="discount_type">
					<?php
						$opt = array('item' => lang('br_item'), 'cart' => lang('br_cart'));
						$sel = $promo[0]["discount_type"];
						foreach($opt as $key => $val){
							$selected = ($sel == $key) ? 'selected' : '';
							echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
						}
					?>
				</select></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_code_type')?></td>
			<td>
				<select name="code_type">
					<?php
						$opt = array('fixed' => lang('br_fixed'), 'percent' => lang('br_percent'));
						$sel = $promo[0]["code_type"];
						foreach($opt as $key => $val){
							$selected = ($sel == $key) ? 'selected' : '';
							echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
						}
					?>
				</select></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_amount')?></td>
			<td>
				<input 	type="text" 
						name="amount"
						value="<?=$promo[0]["amount"]?>" 
						title="<?=lang('br_amount')?>" style="width:100px;" /></td>
		</tr>
		<?php
		/*
			<tr>
				<td>
					<?=lang('br_max_discount')?></td>
				<td>
					<input 	type="text" 
							name="min_subtotal"
							value="<?=$promo[0]["min_subtotal"]?>"
							title="<?=lang('br_min_subtotal')?>" style="width:100px;" /></td>
							
			</tr>
			<tr>
				<td>
					<?=lang('br_min_subtotal')?></td>
				<td>
					<input 	type="text" 
							name="min_subtotal"
							value="<?=$promo[0]["min_subtotal"]?>"
							title="<?=lang('br_min_subtotal')?>" style="width:100px;" /></td>
							
			</tr>
		*/
		?>
		<tr>
			<td>
				<?=lang('br_start_dt')?></td>
			<td>
				<input 	type="text" 
						name="start_dt"
						value="<?=$start?>" 
						class="datepicker" 
						title="<?=lang('br_start_dt')?>" style="width:100px;" /></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_end_dt')?></td>
			<td>
				<input 	type="text" 
						name="end_dt"
						value="<?=$end?>" 
						class="datepicker" 
						title="<?=lang('br_end_dt')?>" style="width:100px;" /></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_uses_per')?></td>
			<td>
				<input 	type="text" 
						name="uses_per"
						value="<?=$promo[0]["uses_per"]?>"
						title="<?=lang('br_user_per')?>" style="width:100px;" /></td>
						
		</tr>
		<tr class="opt_items">
			<td>
				<?=lang('br_categories')?></td>
			<td>
				<?php
					$checked = ($promo[0]["category_list"] == '') ? 'checked="checked"' : '';
					$showCats = ($promo[0]["category_list"] == '') ? 'display:none' : '';
				?>
				<input 	id="category_list" 
						type="checkbox" 
						name="category_list"
						title="<?=lang('br_categories')?>"
						value="all" 
						<?=$checked?> />&nbsp;<?=lang('br_all_categories')?>
						<br />
						<div id="category_opts" style="border:1px #ccc solid;background:#fff;padding: 15px;margin-top:10px;<?=$showCats?>">
							<?=$categories?>
						</div></td>
		</tr>
		<tr class="opt_items">
			<td>
				<?=lang('br_products')?></td>
			<td>
				<?php
					$checked = ($promo[0]["product_list"] == '') ? 'checked="checked"' : '';
					$showProd = ($promo[0]["product_list"] == '') ? 'display:none' : '';
				?>
				<input 	id="product_list" 
						type="checkbox" 
						name="product_list"
						title="<?=lang('br_products')?>" 
						value="all" 
						<?=$checked?>/>&nbsp;<?=lang('br_all_products')?>
						<br />
						<div id="product_opts" style="border:1px #ccc solid;background:#fff;padding: 15px;margin-top:10px;<?=$showProd?>">
							<div class="search">
								<?=lang('search')?>
								<input type="text" id="product_search">
							</div>
							<h4><?=lang('br_add_products')?></h4>
							<div id="product_result" class="result_div">
								<table id="product_results" width="100%" cellpadding="0" cellspacing="0">
									<tr><td colspan="4" style="background-color:#fff"><?=lang('br_promo_product_search')?></td></tr>
								</table>
							</div>
							<p>&nbsp;</p>
							<table id="product_selected" width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<th><?=lang('br_promo_products')?></th>
									<th>&nbsp;</th>
								</tr>
								<?php
									$i = 1;
									foreach($products as $b){
										$class = ($i % 2 == 0) ? 'even' : 'odd' ;
										echo '	<tr class="'.$class.'">
													<td width="60%" style="width:auto;">
														'.$b["title"].'</td>
													<td width="10%">
														<a class="remove_product" href="#">remove</a><input type="hidden" value="'.$b["product_id"].'" name="product[]">
													</td>
												</tr>';
										$i++;
									}
								?>
							</table>
						</div></td>
						
		</tr>
<?php
		/*
		<tr>
			<td>
				<?=lang('br_min_quantity')?></td>
			<td>
				<input 	type="text" 
						name="min_quantity"
						value="<?=$promo[0]["min_quantity"]?>"
						title="<?=lang('br_min_quantity')?>" style="width:100px;" /></td>
						
		</tr>
		*/
		?>
	</table>
									<div class="b2r_clearboth"><!-- --></div>
					    			<div id="header_buttons">
									    <?=form_submit(array('name' => 'save_continue', 'value' => lang('br_save_continue'), 'class'=>'submit'))?>
										<?=form_submit(array('name' => 'save', 'value' => lang('save'), 'class'=>'submit'))?>
										<?php 
											if($promo[0]["promo_id"] != 0){
										?>
												<?=form_submit(array('name' => 'duplicate', 'value' => lang('br_duplicate'), 'class'=>'submit'))?>
												<?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'submit'))?>
										<?php
											}
										?>
										<div class="b2r_clearboth"><!-- --></div>
								    </div>

								
							<div class="b2r_clearboth"><!-- --></div>



                    </div> <!-- b2r_dashboard --> 
	
	
	
	</form>

                    
                </div> <!-- b2r_panel_int -->
            </div> <!-- b2r_panel -->

    	</div> <!-- b2r_main -->

        <div class="b2r_clearboth"><!-- --></div>
        
        <?=$br_footer?>
        
    </div> <!-- b2r_content -->
    
</div> <!-- #b2retail -->


<script type="text/javascript">
	$(function() {
		$('#promoForm').validate();
		$(".datepicker").datepicker();
		
		$('#promoTableEdit tr:odd').addClass('odd');
		$('#promoTableEdit tr:even').addClass('even');
		
		$('#discount_type').bind('change',function(){
			if($(this).val() == 'item'){
				$('.opt_items').show();
			}else{
				$('.opt_items').hide();
			}
		});
		
		<?php
			if($promo[0]["discount_type"] == 'cart'){
				echo "$('.opt_items').hide();";
			}
		?>
		
		$('#category_list').bind('click',function(){
			$('#category_opts').slideToggle();
		});
		$('#product_list').bind('click',function(){
			$('#product_opts').slideToggle();
		});

		var brSearch = $('#product_search');
		var brResult = $('#product_results');

		brSearch.keyup(function(e){
			var term = $(this).val();
			if(term.length <= 3){
				brResult.find('tr').remove();
				return;
			}
			$.getJSON("<?=$product_search?>",{'type':'product','term':term},
	        	function(data){
	            	brResult.find('tr').remove();
					$.each(data, function(i,item){
	            		$('	<tr id="product_'+item.product_id+'"><td>'+item.title+'</td><td width="10%"><a href="#" class="add_product {product_id:'+item.product_id+'}" ><?=lang('br_add')?></a></td></tr>').appendTo(brResult);
	            	});
	            	$('.add_product').unbind('click').bind('click',function(){
						_add_product($(this).metadata().product_id);
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
		
		_remove_product();
		
		$('#delete').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_promo')?>')){
				return true;
			}else{
				return false;
			}
		});
	});
	
	function _add_product(product_id){
		var productSelected = $('#product_selected');
		var row = $('#product_'+product_id);
		new_row = row.clone();
		new_row.attr({'id':''}).find('td:eq(3)').remove();
		new_row.find('td:eq(1)').remove();
		new_row.find('td:eq(0)').attr({'style':'width:auto','width':'60%'});
		$('<td width="10%"><a href="#" class="remove_product">remove</a><input type="hidden" name="product[]" value="'+product_id+'"></td>').appendTo(new_row);
		$(new_row).appendTo(productSelected);
		row.remove();
		productSelected.find('tr:even').addClass('even');
		productSelected.find('tr:odd').addClass('odd');
		_remove_product();
		return false;
	}
	
	function _remove_product(){
		$('.remove_product').unbind('click').bind('click',function(){
			var productSelected = $('#product_selected');
			productSelected.find('tr').removeClass('even').removeClass('odd');
			$(this).parent().parent().remove();
			productSelected.find('tr:even').addClass('even');
			productSelected.find('tr:odd').addClass('odd');
			return false;
		});
	}
	
</script>


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