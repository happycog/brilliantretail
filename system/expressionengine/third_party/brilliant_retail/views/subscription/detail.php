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
					   <table id="admin_header" cellpadding="0" cellspacing="0">
					    	<tr>
								<td>
									<h3><?=lang('br_promotion')?></h3>
									<?php
										if($promo[0]["promo_id"] != 0){
											echo '<p id="b2r_numprod"><span><b>'.$promo[0]["title"].'</b></span></p>';
										}
									?>
									<div class="b2r_clearboth"><!-- --></div>
					    			<div id="header_buttons">
									    <?=form_submit(array('name' => 'save_continue', 'value' => lang('br_save_continue'), 'class'=>'submit'))?>
										<?=form_submit(array('name' => 'save', 'value' => lang('save'), 'class'=>'submit'))?>
										<?php 
											if($promo[0]["promo_id"] != 0){
										?>
												<?=form_submit(array('name' => 'duplicate', 'value' => lang('br_duplicate'), 'class'=>'submit'))?>
												<?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'delete'))?>
										<?php
											}
										?>
										<p class="b2r_cancel"><a href="<?=$base_url.'&method=promo'?>"><?= lang('br_cancel'); ?></a></p>
								    	<div class="b2r_clearboth"><!-- --></div>
								    </div>
					    		</td>
								<td style="width:72px;">    			
									<img src="<?=$theme?>images/icon_promo_sm.png" />
								</td>
							</tr>
					    </table>

<?php
	$start = ($promo[0]["start_dt"] > 0) ? date("n/d/y",strtotime($promo[0]["start_dt"])) : '';
	$end = ($promo[0]["end_dt"] > 0) ? date("n/d/y",strtotime($promo[0]["end_dt"])) : '';
?>

	<table id="promoTableEdit" cellspacing="0" cellpadding="0" border="0" class="b2r_product_tbl edit_form" style="clear:both">
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
				<?=lang('br_status')?></td>
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
						title="<?=lang('br_amount')?>" /></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_start_dt')?></td>
			<td>
				<input 	type="text" 
						name="start_dt"
						value="<?=$start?>" 
						class="datepicker" 
						title="<?=lang('br_start_dt')?>" /></td>
		</tr>
		<tr>
			<td>
				<?=lang('br_end_dt')?></td>
			<td>
				<input 	type="text" 
						name="end_dt"
						value="<?=$end?>" 
						class="datepicker" 
						title="<?=lang('br_end_dt')?>" /></td>
		</tr>
		<tr>
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
		<tr>
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
								<?=lang('br_search')?>
								<input type="text" id="product_search">
							</div>
							<h4><?=lang('br_add_products')?></h4>
							<div id="product_result" class="result_div">
								<table id="product_results" width="100%" cellpadding="0" cellspacing="0">
									<tr><td colspan="4" style="background-color:#fff"><?=lang('br_promo_product_search')?></td></tr>
								</table>
							</div>
							<p>&nbsp;</p>
							<h4><?=lang('br_promo_products')?></h4>
							<table id="product_selected" width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<th><?=lang('br_product')?></th>
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
		<tr>
			<td>
				<?=lang('br_uses_per')?></td>
			<td>
				<input 	type="text" 
						name="min_quantity"
						value="<?=$promo[0]["uses_per"]?>"
						title="<?=lang('br_user_per')?>" style="width:100px;" /></td>
						
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
		<tr>
			<td>
				<?=lang('br_min_quantity')?></td>
			<td>
				<input 	type="text" 
						name="min_quantity"
						value="<?=$promo[0]["min_quantity"]?>"
						title="<?=lang('br_min_quantity')?>" style="width:100px;" /></td>
						
		</tr>
	</table>

								
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