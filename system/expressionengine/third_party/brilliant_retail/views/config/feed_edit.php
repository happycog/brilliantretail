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
	if( isset($products) && count($products) > 0)
	{	
  	$cp_pad_table_template["table_open"] = '<table id="productTable" class="mainTable">';
	
  	$this->table->set_template($cp_pad_table_template);

  	$this->table->set_heading(
		  array(
	    	'data' => '', 
	    	'width' => '5%'),
	    array(
	    	'data' => lang('br_title'), 
	    	'width' => '*'), 
		  array(
	    	'data' => lang('br_price'), 
	    	'width' => '20%'),
	    array(
	    	'data' => lang('br_qty'), 
				'width' => '15%'),
	   	array(
	    	'data' => lang('br_type'), 
				'width' => '15%'),
	   	array(
	    	'data' => '<input type="checkbox" id="toggle_check" />', 
				'style' => 'text-align:center',
				'width' => '5%'
			)
	  );
	  foreach($products as $p)
  	{
  		$enabled = ($p['enabled'] == 1) ? 'status_on' : 'status_off' ;
  		$this->table->add_row(
				'<img src="'.$theme.'images/icon_'.$enabled.'.png" />', 
				'<a href="'.$base_url.'&method=product_edit&product_id='.$p['product_id'].'">'.$p['title'].'</a>',
				$p['price'],
				$p['quantity'],
				$product_type[$p['type_id']],
				array('data' => '<input type="checkbox" name="batch['.$p['product_id'].']" />', 'style' => 'text-align:center')
			);
  	}  
	
	  $product_table = $this->table->generate();
	}
	
                    $hidden = array();
                    if( isset($feed['feed_id']) )
                    {
                      $hidden['feed_id'] = $feed['feed_id'];
                    }
                  	echo form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_feed_edit',
          					  array(	
          					    'method'  => 'POST', 
                    		'id' 		  => 'feed_edit',
                    		'encrypt' => 'multipart/form-data'
                    	),
                    	$hidden
                    );
                    ?>
        						<div id="b2r_page" class="b2r_category">
        						    <table id="admin_header" cellpadding="0" cellspacing="0">
        						    	<tr>
        									<td>
        										<?php
        					          echo '	<select id="select_config">';
                  			    foreach($submenu as $key => $val){
                      		  	$sel = ($key == $sub_selected) ? 'selected="selected"' : '' ; 
                        			echo '	<option value="'.$key.'" '.$sel.'>'.lang($key).'</option>'; 
                        		}
                    		    echo '	</select>
                        			<script type="text/javascript">
                        				$(function(){
                        					$(\'#select_config\').change(function(){
        										        window.location = \''.$base_url.'&&method=\'+$(this).val();
                        					});
                        				});	
                        			</script>';
        				            ?>
        										<h3><?=lang('br_config_feeds')?></h3>
			                    	<?php
                    					if(isset($feed['feed_title']) && $feed['feed_title'] != ''){
                    						echo '<p id="b2r_numprod"><span><b>'.$feed['feed_title'].'</b></span></p>';
                    					}
                    				?>			
                    				<div class="b2r_clearboth"><!-- --></div>
                      			<div id="header_buttons">
                  				    <?=form_submit(array('name' => 'submit', 'value' => lang('br_save_continue'), 'class'=>'submit'))?>
                    					<?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
                    					<?php 
                    						if(isset($feed['feed_id']) && $feed['feed_id'] != ''){
                    					?>
                    							<?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'delete'))?>
                    					<?php
                    						}
                    					?>
                  					  <p class="b2r_cancel"><a href="<?=$base_url.'&method=config_feeds'?>"><?= lang('br_cancel'); ?></a></p>
                  			    	<div class="b2r_clearboth"><!-- --></div>
                  			    </div>
        						    		<p class="b2r_addprod"></p>
        									</td>
        								</tr>
        						    </table>
        						    
        						    <table id="feed_tbl" class="mainTable" style="clear:both">
                      		<thead>
                      			<tr class="odd">
                      				<th colspan="2"><?=lang('br_feed_settings')?></th>
                      			</tr>
                      		</thead>
                          <tbody>
                            <tr>
                              <td class="cell_1" width="10%"><?=lang('br_title')?> *</td>
                              <td class="cell_2">
                                <?= form_input('feed_title', set_value( 'feed_title', isset($feed['feed_title']) ? $feed['feed_title'] : ''), 'class="{required:true}"') ?>
                                <?= form_error('feed_title') ?>
                              </td>
                            </tr>
                            <tr>
                              <td class="cell_1"><?=lang('br_code')?> *</td>
                              <td class="cell_2">
                                <?= form_input('feed_code', set_value( 'feed_code', isset($feed['feed_code']) ? $feed['feed_code'] : ''), 'class="{required:true}"') ?>
                                <?= form_error('feed_code') ?>
                              </td>
                            </tr>
                          </tbody>
                      	</table>
                        <?=form_close()?>
                            
                        <?php if ( isset( $product_table ) && $product_table != '' ): ?>
                          <br />
                          <hr />
                          <br />
        						    
          						    <table class="mainTable" style="clear:both">
                        		<thead>
                        			<tr class="odd">
                        				<th colspan="2"><?=lang('br_products')?></th>
                        			</tr>
                        		</thead>
                        	</table>
                          
                          <?= form_open_multipart('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=product_batch',array('method' => 'POST', 'id' => 'productForm')) ?>
                          <?= $product_table ?>
                          <?= form_close() ?>
                        <?php endif; ?>

<script type="text/javascript">
	$(function(){
	  <?php if (isset($feed['feed_id']) && $feed['feed_id'] != '' ): ?>
	  $('#delete_button').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_feed')?>')){
				window.location = '<?=$base_url?>&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_feed_delete&feed_id=<?=$feed['feed_id']?>';
			}
			return false;
		});
		
		$('#delete').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_feed')?>')){
				return true;
			}else{
				return false;
			}
		});
	  <?php endif; ?>
	  
		$('#feed_tbl tr:even').addClass('b2r_status');
		$('#feed_edit').validate();		
		
		$('#fieldtype').bind('change',function(){
			var a = $(this);
			$('.type_opts').hide();
			if(a.val() == 'dropdown'){
				$('#filterable').show();
				$('#dropdown_options').show();
			}else if(a.val() == 'multiselect'){
				$('#multiselect_options').show();
			}else if(a.val() == 'text'){
				$('#default').show();
			}
			
		});
	  
		var oTable = $('#productTable').dataTable({
  	"bStateSave": true,
  	"aoColumns": [
  			{ "bSortable": false }, 
  			null,
  			null,
  			null,
  			null,
  			{ "bSortable": false }
  		]
  	});
		$('<p class="b2r_search_btn"><a href="#" id="clear"><b>Clear</b></a></p>').insertBefore('#productTable_filter input');
		$('<div style="clear:both"></div>').insertAfter('#productTable_filter');
		$('#clear').click(function(){
  		oTable.fnFilterClear();
  		return false
  	});
	});
</script>