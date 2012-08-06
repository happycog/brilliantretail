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

	$this->table->set_template($cp_pad_table_template);
	
	$this->table->set_heading(lang('br_general_configuration'),'');
	
	$this->table->add_row(	
							array(
								lang('br_site_logo'),
								'	<input type="hidden" name="max_file_size" value="10000000" />  
									<img src="'.rtrim($store["media_url"],'/').'/'.$store['logo'].'" style="border:1px #ccc solid;margin-bottom:10px" /><br />'.
									form_upload('logo')
								)
						);
						
	// Add the fields
		$fields = array('license','phone','fax','address1','address2','city','state','zipcode','country','secure_url','media_url','media_dir','cart_url','checkout_url','thankyou_url','customer_url','product_url');
	    foreach($fields as $f){
			$this->table->add_row(array(lang('br_site_'.$f),
							form_input(
										array(	'name' => $f, 
												'id' => $f,
												'value' => $store[$f],
												'class' => '',
												'title' => lang('br_site_'.$f))
										)
							)
					);
	    }
	    
	    $options = array();
		foreach($currencies as $c){
			$options[$c["currency_id"]] = $c["title"].' ('.$c["marker"].')'; 
		}
		$this->table->add_row(array(lang('br_site_currency'),
								form_dropdown(
												'currency_id', 
												$options, 
												$store["currency_id"]).'<br /><br />'.lang('br_currency_instructions')
							));
							
	    $options = array(
	    					0 => lang('br_no'),
	    					1 => lang('br_yes')
	    				);
	    $this->table->add_row(array(lang('br_guest_checkout'),
								form_dropdown(
												'guest_checkout', 
												$options, 
												$store["guest_checkout"])
							));
							
		foreach($groups as $g){
			$options[$g["group_id"]] = $g["group_title"];
		}
		
		$this->table->add_row(array(lang('br_register_group'),
								form_dropdown(
												'register_group', 
												$options, 
												$store["register_group"])
							));
							
		$options = array();
		$selected = array();
		foreach($countries as $c){
			$options[$c["zone_id"]] = $c["title"];
			if($c["enabled"] == 1){
				$selected[] = $c["zone_id"];
			}
		}
		
		$this->table->add_row(array(lang('br_countries'),
								form_multiselect(
												'countries[]', 
												$options, 
												$selected)
							));
		
		$general = $this->table->generate();
		$this->table->clear();
		
		// CATALOG TAB
			$this->table->set_template($cp_pad_table_template);

  			$this->table->set_heading(lang('br_product_config'),'');
  			
      		$fields = array("low_stock","result_limit","result_per_page","result_paginate");
			foreach($fields as $f){
				$this->table->add_row(array(lang('br_'.$f),
								form_input(
											array(	'name' => $f, 
													'id' => $f,
													'value' => $store[$f],
													'class' => '{required:true}',
													'title' => lang('br_'.$f))
											)
								)
						);
			}
				
			$catalog = $this->table->generate();
			$this->table->clear();
			
		// SEO TAB
			$this->table->set_template($cp_pad_table_template);

  			$this->table->set_heading(lang('br_seo_config'),'');
  			
			$fields = array('meta_title','meta_keywords');
		    foreach($fields as $f){
				$this->table->add_row(array(lang('br_site_'.$f),
								form_input(
											array(	'name' => $f, 
													'id' => $f,
													'value' => $store[$f],
													'class' => '',
													'title' => lang('br_site_'.$f))
											)
								)
						);
		    }
		    
		    $this->table->add_row(array(lang('br_site_meta_descr'),
									form_textarea(
											array(	'name' => 'meta_descr', 
													'id' => 'meta_descr',
													'value' => $store['meta_descr'],
													'class' => '',
													'title' => lang('br_meta_descr'))
											)
									)
								);			
		
		$seo = $this->table->generate();
		$this->table->clear();

		echo form_open_multipart('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_site_update',array('method' => 'POST', 'id' => 'storeForm'),$hidden);
?>

	
	<?=$general?>

	<p>&nbsp;</p>
	
	<?=$catalog?>

	<p>&nbsp;</p>

	<?=$seo?>
	
	<div id="header_buttons">
	    <?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
    	<div class="b2r_clearboth"><!-- --></div>
    </div>

</form>
<style type="text/css">
	.mainTable th:first-child {
		width: 35%;
	}
</style>
<script type="text/javascript">
	$(function() {
		$('#storeForm').validate();
	});
</script>