<?php
/********************************/
/* GENERAL SETTINGS				*/
/********************************/

	$general = '<div id="config_general" class="configTable">
				<table cellpadding="0" cellspacing="0" class="mainTable padTable" style="width:100%">
					<thead>
						<tr>
							<th colspan="2">
								'.lang('br_general_configuration').'</th>
					</thead>
					<tbody>
						<tr>
							<td valign="top" width="35%" style="padding-top:15px;">
								'.lang('br_site_logo').'</td>
							<td>
								<input type="hidden" name="max_file_size" value="10000000" />  
								<img src="'.rtrim($store["media_url"],'/').'/'.$store['logo'].'" style="border:1px #ccc solid;margin-bottom:10px" /><br />'.
								form_upload('logo').'</td>
						</tr>';
	
						
	// Add the fields
		$fields = array('license','phone','fax','address1','address2','city','state','zipcode','country','secure_url','media_url','media_dir','cart_url','checkout_url','thankyou_url','customer_url','product_url');
	    foreach($fields as $f){
			
			$general .= '	<tr>
								<td valign="top" width="35%" style="padding-top:15px;">
									'.lang('br_site_'.$f).'</td>
								<td>
									'.
									form_input(array(	'name' => $f, 
														'id' => $f,
														'value' => $store[$f],
														'class' => '',
														'title' => lang('br_site_'.$f))
												).'</td>
							</tr>';
		}
	    
	    
	    // Currency Setting
	    
		    $options = array();
			foreach($currencies as $c){
				$options[$c["currency_id"]] = $c["title"].' ('.$c["marker"].')'; 
			}
		    $general .= '		<tr>
									<td valign="top" width="35%" style="padding-top:15px;">
										'.lang('br_site_currency').'</td>
									<td>
										'.form_dropdown(
															'currency_id', 
															$options, 
															$store["currency_id"]).'<br /><em>'.lang('br_currency_instructions').'</em></td>
								</tr>';
	    
							
							
		    $options = array(
		    					0 => lang('br_no'),
		    					1 => lang('br_yes')
		    				);
		    
		   	$general .= '		<tr>
									<td valign="top" width="35%" style="padding-top:15px;">
										'.lang('br_guest_checkout').'</td>
									<td>
										'.form_dropdown(
													'guest_checkout', 
													$options, 
													$store["guest_checkout"]).'</td>
								</tr>';

								
			foreach($groups as $g){
				$options[$g["group_id"]] = $g["group_title"];
			}
			
			$general .= '		<tr>
									<td valign="top" width="35%" style="padding-top:15px;">
										'.lang('br_register_group').'</td>
									<td>
										'.form_dropdown(
													'register_group', 
													$options, 
													$store["register_group"]).'</td>
								</tr>';

			$options = array();
			$selected = array();
			foreach($countries as $c){
				$options[$c["zone_id"]] = $c["title"];
				if($c["enabled"] == 1){
					$selected[] = $c["zone_id"];
				}
			}
			
			$general .= '		<tr>
									<td valign="top" width="35%" style="padding-top:15px;">
										'.lang('br_countries').'</td>
									<td>
										'.form_multiselect(	'countries[]', 
															$options, 
															$selected).'</td>
								</tr>
							</tbody>
						</table>
						</div>';
		
/********************************/
/* GENERAL SETTINGS				*/
/********************************/
			
			$catalog = '<div id="config_product" class="configTable" style="display:none">
							<table cellpadding="0" cellspacing="0" class="mainTable padTable" style="width:100%">
								<thead>
									<tr>
										<th colspan="2">
											'.lang('br_product_config').'</th>
								</thead>
								<tbody>';
	
	      		$fields = array("low_stock","result_limit","result_per_page","result_paginate");
				foreach($fields as $f){
					
					$catalog .= "	<tr>
										<td width='35%'>
											".lang('br_'.$f)."</td>
										<td>".form_input(
														array(	'name' => $f, 
																'id' => $f,
																'value' => $store[$f],
																'class' => 'required',
																'title' => lang('br_'.$f)
															)
												)."</td>
									</tr>";
				}
				
					$catalog .= '	<tr>
										<td valign="top" width="35%" style="padding-top:15px;">
											'.lang('br_display_out_of_stock').'</td>
										<td>
											'.form_dropdown(
																'display_out_of_stock', 
																array(
																		0 => lang('br_no'), 
																		1 => lang('br_yes')
																	),
																$store['display_out_of_stock'] 
															).'</td>
									</tr>
									</tbody>
							</table>
							<table cellpadding="0" cellspacing="0" class="mainTable padTable"  style="width:100%;">
								<thead>
									<tr>
										<th colspan="2">
											'.lang('br_downloadable').'</th>
								</thead>
								<tbody>
									<tr>
										<td valign="top" width="35%" style="padding-top:15px;">
											'.lang('br_downloads_use_local').'</td>
										<td>
											'.form_dropdown(
																'downloads_use_local', 
																array(
																		0 => lang('br_no'), 
																		1 => lang('br_yes')
																	),
																$store['downloads_use_local'] 
															).'</td>
									</tr>
									<tr>
										<td valign="top" width="35%" style="padding-top:15px;">
											'.lang('br_downloads_local').'</td>
										<td>
											'.form_input(
															'downloads_local',
															$store['downloads_local'] 
														).'</td>
									</tr>
									<tr>
										<td valign="top" width="35%" style="padding-top:15px;">
											'.lang('br_downloads_use_s3').'</td>
										<td>
											'.form_dropdown(
																'downloads_use_s3', 
																array(
																		0 => lang('br_no'), 
																		1 => lang('br_yes')
																	),
																$store['downloads_use_s3'] 
															).'</td>
									</tr>
									<tr>
										<td valign="top" width="35%" style="padding-top:15px;">
											'.lang('br_downlaods_s3_access_key').'</td>
										<td>
											'.form_input(
															'downlaods_s3_access_key',
															$store['downlaods_s3_access_key'] 
														).'</td>
									</tr>
									<tr>
										<td valign="top" width="35%" style="padding-top:15px;">
											'.lang('br_downlaods_s3_secret_key').'</td>
										<td>
											'.form_input(
															'downlaods_s3_secret_key',
															$store['downlaods_s3_secret_key'] 
														).'</td>
									</tr>
									<tr>
										<td valign="top" width="35%" style="padding-top:15px;">
											'.lang('br_downlaods_s3_length').'</td>
										<td>
											'.form_input(
															'downlaods_s3_length',
															$store['downlaods_s3_length'] 
														).'<br /><em>'.lang('br_downlaods_s3_length_description').'</em></td>
									</tr>
								</tbody>
							</table>
						</div>';					

/********************************/
/* SEO TAB						*/
/********************************/			

			$seo = '<div id="config_seo" class="configTable" style="display:none">
						<table cellpadding="0" cellspacing="0" class="mainTable padTable" style="width:100%;">
							<thead>
								<tr>
									<th colspan="2">
										'.lang('br_seo_config').'</th>
							</thead>
							<tbody>';
			
			
			$fields = array('meta_title','meta_keywords');
		    foreach($fields as $f){
				$seo .= "	<tr>
								<td width='35%'>
									".lang('br_site_'.$f)."</td>
								<td>
									".form_input(
												array(	'name' => $f, 
														'id' => $f,
														'value' => $store[$f],
														'class' => '',
														'title' => lang('br_site_'.$f))
												)."</td>
							</tr>";
		    }
		    
		    
		    	$seo .= "	<tr>
								<td>
									".lang('br_site_meta_descr')."</td>
								<td>
									".form_textarea(
											array(	'name' => 'meta_descr', 
													'id' => 'meta_descr',
													'value' => $store['meta_descr'],
													'class' => '',
													'title' => lang('br_meta_descr'))
											)."</td>
								</tr>
							</tbody>
						</table>
					</div>";
		
		echo form_open_multipart('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_site_update',array('method' => 'POST', 'id' => 'storeForm'),$hidden);
?>

	
	<table width="100%">
		<tr>
			<td valign="top" style="width:250px">
				<table cellpadding="0" cellspacing="0" class="mainTable padTable" style="width:240px">
					<thead>
						<tr>
							<th>
								<?=lang('nav_br_config_site')?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="active">
								<a href="#" class="config_menu" data-section="general"><?=lang('br_general_configuration')?></a></td>
						</tr>
						<tr>
							<td>
								<a href="#" class="config_menu" data-section="product"><?=lang('br_product_config')?></a></td>
						</tr>
						<tr>
							<td>
								<a href="#" class="config_menu" data-section="seo"><?=lang('br_seo_config')?></a></td>
						</tr>
					</tbody>
				</table> </td>
			<td valign="top">
				<?=$general?>
					
				<?=$catalog?>
			
				<?=$seo?></td>
		</tr>
	</table>
	
	<div id="bottom_buttons">
	    <?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
    	<div class="b2r_clearboth"><!-- --></div>
    </div>

</form>
<style tyle="text/css">
	table.mainTable td.active a {
		font-weight: bold;
	}
	table.mainTable td a {
		display:block;
		font-weight: normal;
	}
</style>
<script type="text/javascript">
	$(function() {
		$('.config_menu').bind('click',function(event) {
  			var section = $(this).data('section');
  			$('.configTable').hide();
  			$('#config_'+section).show();
  			$('table.mainTable td').removeClass('active');
  			$(this).parent().addClass('active');
  		});
		$('#storeForm').validate();
	});
</script>