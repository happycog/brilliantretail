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

/****************/
/* Details Tab	*/
/****************/
?>
<table id="product_detail_tbl" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="margin-top:5px;">
		<tr>
			<th>
				<?php
					if($products[0]["product_id"] == 0){
						echo lang('br_new_product');
					}else{
						echo $products[0]["title"].' ['.$hidden["product_id"].']';
					}
				?></th>
		</tr>
		<tr>
			<td class="custom_field">
				<label><em class="required">*</em> <?=lang('br_product_type')?> - <?=$type?></label>
				<?=$sub_type?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><em class="required">*</em> <?=lang('br_product_title')?></label>
				<br />
				<?=form_input(
								array(	'name' => 'title', 
										'id' => 'title',
										'value' => $products[0]["title"],
										'class' => '{required:true}',
										'title' => lang('br_details').' - '.lang('br_product_title').' '.lang('br_is_required'))
							)?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><em class="required">*</em> <?=lang('br_url_title')?></label>
				<br />
				<?=form_input(
								array(	'name' => 'url', 
										'id' => 'url',
										'value' => $products[0]["url"],
										'class' => '',
										'title' => lang('br_details').' - '.lang('br_url_title').' '.lang('br_is_required'))
								)?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('br_product_detail')?></label>
				<br />
				<?=form_textarea(
											array(	'name' => 'detail', 
													'value' => $products[0]["detail"],
													'title' => lang('br_details').' - '.lang('br_description').' '.lang('br_is_required'),
													'class' => 'ckeditor',
													'style' => 'width:400px')
										)?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('status')?></label>
				<br />
				<?=form_dropdown('enabled', array(1 => lang('br_enabled'), 0 => lang('br_disabled')), $products[0]["enabled"])?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('br_product_sku').' *'?></label>
				<br />
				<?=form_input(
										array(	'name' => 'sku', 
												'value' => $products[0]["sku"],
												'class' => '{required:true}',
												'title' => lang('br_details').' - '.lang('br_product_sku').' '.lang('br_is_required'))
										)?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('br_shippable')?></label>
				<br />
				<?=form_dropdown('shippable', array(1 => lang('br_yes'), 0 => lang('br_no')), $products[0]["shippable"])?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('br_product_weight')?></label>
				<br />
				<?=form_input(	array(	'name' => 'weight', 
										'value' => $products[0]["weight"],
										'class' => '',
										'title' => lang('br_details').' - '.lang('br_product_weight'))
								)?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('br_featured')?></label>
				<br />
				<?=form_dropdown('featured', array(1 => lang('br_yes'), 0 => lang('br_no')), $products[0]["featured"])?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('br_quantity')?></label>
				<br />
				<?=form_input(
								array(	'name' => 'quantity', 
										'value' => $products[0]["quantity"],
										'title' => lang('br_details').' - '.lang('br_quantity'))
								)?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('br_taxable')?></label>
				<br />
				<?=form_dropdown(	
									'taxable', 
									array(	1 => lang('br_yes'), 
											0 => lang('br_no')), 
									$products[0]["taxable"])
								?></td>
		</tr>
		<tr>
			<td class="custom_field">
				<label><?=lang('br_cost')?></label>
				<br />
				<?=form_input(	array(	'name' => 'cost', 
										'class' => '',
										'title' => lang('br_cost'),
										'value' => $products[0]["cost"])
								)?></td>
		</tr>
</table>		
		<?php
			foreach($custom as $c){
				echo '	<p>&nbsp;</p>
						<table cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form" style="margin-top:5px;">
							<tr>
								<th>'.$c["settings"]["field_label"].'</th>
							</tr>';
				if($c["settings"]["field_instructions"] != ""){
					echo '	<tr>
								<td class="custom_field">'.$c["settings"]["field_instructions"].'</td>
							</tr>';
				}			
					
				echo '		<tr>
								<td class="custom_field">'.$c["display_field"].'</td>
							</tr>
						</table>';
			}
		?>

<script type="text/javascript">
	$(function(){
		<?php
			if($hidden["product_id"] == 0){
		?>
				$('#title').bind('keyup',function(){
					var a = $(this);
					var str = a.val().toLowerCase();
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
			
					$('#url').val(str);
				});
		<?php	
			}
		?>
	})
</script>