<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2014						*/
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

/****************/
/* Details Tab	*/
/****************/
?>
				<div class="publish_field" id="hold_br_details">
					
					<label class="hide_field">
						<span>
							<?=lang('br_details')?>
						</span>
					</label>
	
					<div id="sub_hold_br_featured">
						<fieldset class="holder">
							<table cellspacing="0" cellpadding="0" border="0" width="100%" class="product_edit">
								<thead>
									<tr>
										<th width="20%"><?=lang('br_title')?></th>
										<th><?=lang('br_value')?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<?=lang('status')?> <em class="required">*</em> </td>
										<td>
											<?=form_dropdown('enabled', array(1 => lang('br_enabled'), 0 => lang('br_disabled')), $products[0]["enabled"])?></td>
									</tr>
									<tr>
										<td>
											<?=lang('br_product_sku')?> <em class="required">*</em> </td>
										<td>
											<?=form_input(
															array(	'name' => 'sku', 
																	'value' => $products[0]["sku"],
																	'class' => '{required:true}',
																	'title' => lang('br_details').' - '.lang('br_product_sku').' '.lang('br_is_required'))
															)?></td>
									</tr>
									<tr>
										<td>
											<?=lang('br_quantity')?> <em class="required">*</em> </td>
										<td>
											<?=form_input(
												array(	'name' => 'quantity', 
														'value' => $products[0]["quantity"],
														'title' => lang('br_details').' - '.lang('br_quantity'))
												)?></td>
									</tr>
									<tr>
										<td>
											<?=lang('br_taxable')?></td>
										<td>
											<?=form_dropdown(	
																'taxable', 
																array(	1 => lang('br_yes'), 
																		0 => lang('br_no')), 
																$products[0]["taxable"])
															?></td>
									</tr>
									<tr>
										<td>
											<?=lang('br_shippable')?></td>
										<td>
											<?php
												// Setup a rule for showing or hiding 
												// the weight label and input 
												// if the product is not shippable
												
												$wClass = ($products[0]["shippable"] == 0) ? 'shipping_opts nodisplay' : 'shipping_opts';
											?>
											<table border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td style="padding:3px 0 0;border:0" id="has_shipping">
														<?=form_dropdown('shippable', array(1 => lang('br_yes'), 0 => lang('br_no')), $products[0]["shippable"])?></td>
													<td style="padding:5px 5px 0;border:0" class="<?=$wClass?>">
														<?=lang('br_product_weight')?></td>
													<td style="padding:0;border:0" class="<?=$wClass?>">
														<?=form_input(	array(	'name' => 'weight', 
																	'value' => $products[0]["weight"],
																	'class' => '',
																	'title' => lang('br_details').' - '.lang('br_product_weight'),
																	'style'	=> 'width:100px;')
															)?></td>
												</tr>
											</table></td>
									</tr>
									<tr>
										<td>
											<?=lang('br_featured')?></td>
										<td>
											<?=form_dropdown('featured', array(1 => lang('br_yes'), 0 => lang('br_no')), $products[0]["featured"])?></td>
									</tr>
								</tbody>
							</table>
						</fieldset>
					</div>
					
				</div>
				
				
				
				<div class="publish_field" id="hold_br_detail">

					<label class="hide_field">
						<span>
							<?=lang('br_product_detail')?>
						</span>
					</label>
	
					<div id="sub_hold_br_detail">
						<fieldset class="holder">
							<?=$detail_field?>
						</fieldset>
					</div> <!-- /sub_hold_field -->

				</div>
				
				

		<?php
			foreach($custom as $c){
		?>			
				
				<div class="publish_field publish_<?=$c["settings"]['field_type']?>" id="hold_field_<?=$c["settings"]['field_id']?>">

				<label class="hide_field">
					<span>
						<?=$c["settings"]['field_label']?>
						<?php 
							if($c["settings"]['field_required'] == 'y'){ 
								echo '<em class="required">*</em>';
							}
						?>
					</span>
				</label>

				<div id="sub_hold_field_<?=$c["settings"]['field_id']?>">
										
							<?php if($c["settings"]['field_instructions'] != ''):?>
								<div class="instruction_text">
									<?=auto_typography('<strong>'.$this->lang->line('instructions').'</strong>'.NBS.$c["settings"]['field_instructions'])?>
								</div>
							<?php endif;?>
							
							<fieldset class="holder">
								<?=isset($c["settings"]['string_override']) ? $c["settings"]['string_override'] : $c["display_field"]?>
								<?=form_error($c["settings"]["field_name"])?>

							<?php if ($c["settings"]['has_extras']): ?>
								<p class="spellcheck markitup">

									<?php if ($c["settings"]['field_show_writemode'] == 'y'):?>
										<a href="#" class="write_mode_trigger" id="id_<?=$c["settings"]['field_id']?>" title="<?=lang('write_mode')?>"><img alt="<?=lang('write_mode')?>" width="22" height="21" src="<?=$cp_theme_url?>images/publish_write_mode.png" /></a> 
									<?php endif;?>

									<?php if ($c["settings"]['field_show_file_selector'] == 'y' && count($file_list) > 0):?>
										<a href="#" class="markItUpButton">
										<img class="file_manipulate js_show" src="<?=$cp_theme_url?>images/publish_format_picture.gif" alt="<?=lang('file')?>" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php endif;?>

									<?php if($spell_enabled && $c["settings"]['field_show_spellcheck'] == 'y'):?>
										<a href="#" class="spellcheck_link" id="spelltrigger_<?=(ctype_digit($c["settings"]['field_id']))?'field_id_':''?><?=$c["settings"]['field_id']?>" title="<?=lang('check_spelling')?>"><img src="<?=$cp_theme_url.'images/spell_check_icon.png'?>" style="margin-bottom: -8px;" alt="<?=lang('check_spelling')?>" /></a>
									<?php endif;?>

									<?php if($c["settings"]['field_show_glossary'] == 'y'):?>
										<a href="#" class="glossary_link" title="<?=lang('glossary')?>"><img src="<?=$cp_theme_url.'images/spell_check_glossary.png'?>" style="margin-bottom: -8px;" alt="<?=lang('glossary')?>" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php endif;?>

									<?php if ($smileys_enabled && $c["settings"]['field_show_smileys'] == 'y'):?>
										<a href="#" class="smiley_link" title="<?=lang('emoticons')?>"><?=lang('emoticons')?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php endif;?>
									
									<?php if ($c["settings"]['field_show_fmt'] == 'y' && count($c["settings"]['field_fmt_options']) > 0):?>
										<?=lang('formatting')?>
										<?=form_dropdown('field_ft_'.$c["settings"]['field_id'], $c["settings"]['field_fmt_options'], $c["settings"]['field_fmt'])?> 
									<?php endif;?>

								</p>

								<?php if($spell_enabled && $c["settings"]['field_show_spellcheck'] == 'y'):
									echo build_spellcheck($c["settings"]["field_name"]);
								endif;?>

								<?php if($c["settings"]['field_show_glossary'] == 'y'):
									echo $glossary_items;
								endif;?>
								<?php if(isset($c["settings"]['smiley_table'])):
									echo $c["settings"]['smiley_table'];
								endif;?>
							<?php endif; ?>
							
							</fieldset>

						</div> <!-- /sub_hold_field -->
				</div>

		<?php
			}
		?>
<div><!-- End Cap !--></div>
<?php /*<script type="text/javascript" src="<?=$detail_ckeditor_url?>"></script>*/ ?>
<script type="text/javascript">
	$(function(){
		
		// Lets do some stuff to make the url_title 
		// edit a little less in your face
	
			// Bind the url title click
				$('#url_display span:eq(0)').bind('click',function(){
					$(this).hide();
					$('#url_input').show();
					$('#url_input input')
						.focus()
						.bind('keypress',function(e){
							var code = (e.keyCode ? e.keyCode : e.which);
							if(code == 13) { //Enter keycode
								var a = $(this);
								var b = a.parent();
								var str = a.val().toLowerCase();
								str = clean_url_title(str);
								$('#url_display span:eq(0)').html(str);
								a.val(str);
								b.hide();
								b.prev().show();
								return false;
							}
						})
						.bind('keyup',function(e){
							var a = $(this);
							var str = a.val().toLowerCase();
							str = clean_url_title(str);
							$('#url_display span:eq(0)').html(str);
						});
				});
			
				$('#url_input input').bind('blur',function(){
					var a = $(this);
					var b = a.parent();
					var str = a.val().toLowerCase();
					str = clean_url_title(str);
					a.val(str);
					b.hide();
					b.prev().show();
						
				});

			// Lets bind the keyup on the
			// product naming of new products 
			// so we can build a url_title 
			// on the fly	
					
				<?php
					if($hidden["product_id"] == 0){
				?>
						$('#title').bind('keyup',function(){
							var a = $(this);
							var str = a.val().toLowerCase();
							
							str = clean_url_title(str);
					
							$('#url').val(str);
							$('#url_display span:eq(0)').html(str);
						});
				<?php	
					}
				?>

	// We need to bind to the has_shipping input so we can 

	 $('#has_shipping select').bind('change',
	 								function()
	 								{
	 									var a = $('.shipping_opts');
	 									if($(this).val() == 0){
	 										a.hide();
	 									}else{
	 										a.show();
	 									}
	 								});
	});

	
	function clean_url_title(str){
		var sep = "-";
		var tmp = '';
		for(var pos=0; pos < str.length; pos++)
		{
			var c = str.charCodeAt(pos);
			
			if (c >= 32 && c < 128)
			{
				tmp += str.charAt(pos);
			}
			else
			{
				if (c == '223') {tmp += 'ss'; continue;}
				if (c == '224') {tmp += 'a'; continue;}
				if (c == '225') {tmp += 'a'; continue;}
				if (c == '226') {tmp += 'a'; continue;}
				if (c == '229') {tmp += 'a'; continue;}
				if (c == '227') {tmp += 'ae'; continue;}
				if (c == '230') {tmp += 'ae'; continue;}
				if (c == '228') {tmp += 'ae'; continue;}
				if (c == '231') {tmp += 'c'; continue;}
				if (c == '232') {tmp += 'e'; continue;}
				if (c == '233') {tmp += 'e'; continue;}
				if (c == '234') {tmp += 'e'; continue;}
				if (c == '235') {tmp += 'e'; continue;}
				if (c == '236') {tmp += 'i'; continue;}
				if (c == '237') {tmp += 'i'; continue;}
				if (c == '238') {tmp += 'i'; continue;}
				if (c == '239') {tmp += 'i'; continue;}
				if (c == '241') {tmp += 'n'; continue;}
				if (c == '242') {tmp += 'o'; continue;}
				if (c == '243') {tmp += 'o'; continue;}
				if (c == '244') {tmp += 'o'; continue;}
				if (c == '245') {tmp += 'o'; continue;}
				if (c == '246') {tmp += 'oe'; continue;}
				if (c == '249') {tmp += 'u'; continue;}
				if (c == '250') {tmp += 'u'; continue;}
				if (c == '251') {tmp += 'u'; continue;}
				if (c == '252') {tmp += 'ue'; continue;}
				if (c == '255') {tmp += 'y'; continue;}
				if (c == '257') {tmp += 'aa'; continue;}
				if (c == '269') {tmp += 'ch'; continue;}
				if (c == '275') {tmp += 'ee'; continue;}
				if (c == '291') {tmp += 'gj'; continue;}
				if (c == '299') {tmp += 'ii'; continue;}
				if (c == '311') {tmp += 'kj'; continue;}
				if (c == '316') {tmp += 'lj'; continue;}
				if (c == '326') {tmp += 'nj'; continue;}
				if (c == '353') {tmp += 'sh'; continue;}
				if (c == '363') {tmp += 'uu'; continue;}
				if (c == '382') {tmp += 'zh'; continue;}
				if (c == '256') {tmp += 'aa'; continue;}
				if (c == '268') {tmp += 'ch'; continue;}
				if (c == '274') {tmp += 'ee'; continue;}
				if (c == '290') {tmp += 'gj'; continue;}
				if (c == '298') {tmp += 'ii'; continue;}
				if (c == '310') {tmp += 'kj'; continue;}
				if (c == '315') {tmp += 'lj'; continue;}
				if (c == '325') {tmp += 'nj'; continue;}
				if (c == '352') {tmp += 'sh'; continue;}
				if (c == '362') {tmp += 'uu'; continue;}
				if (c == '381') {tmp += 'zh'; continue;}
			}
		}
		
		var multiReg = new RegExp(sep + '{2,}', 'g');
		
		str = tmp;
		
		str = str.replace('/<(.*?)>/g', '');
		str = str.replace(/\s+/g, sep);
		str = str.replace(/\//g, sep);
		str = str.replace(/[^a-z0-9\-\._]/g,'');
		str = str.replace(/\+/g, sep);
		str = str.replace(multiReg, sep);
		str = str.replace(/-$/g,'');
		str = str.replace(/_$/g,'');
		str = str.replace(/^_/g,'');
		str = str.replace(/^-/g,'');
		str = str.replace(/\.+$/g,'');
		
		return str;
	}
</script>