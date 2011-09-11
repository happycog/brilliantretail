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
echo form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_shipping_update',
				array(	'method' 	=> 'POST', 
						'id' 		=> 'shipping_edit',
						'class' 	=> 'b2r_category', 
						'encrypt' 	=> 'multipart/form-data'),
				array(	'config_id' => $config_id));
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
				<h3><?=lang('br_shipping_methods')?></h3>
				<p id="b2r_numprod"><span><b><?=$title?></b></span></p>
				<div class="b2r_clearboth"><!-- --></div>
    			<div id="header_buttons">
					<?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
					<p class="b2r_cancel"><a href="<?=$base_url.'&method=config_shipping'?>"><?= lang('br_cancel'); ?></a></p>
			    	<div class="b2r_clearboth"><!-- --></div>
			    </div>
    		</td>
		</tr>
    </table>

    <div class="b2r_clearboth"><!-- --></div>
    
	<table id="shipping_tbl" class="mainTable" style="clear:both">
    	<tbody>
	    	<tr class="odd">
	        	<td class="cell_1">
	        		<?=lang('br_title')?></td>
	        	<td>
	            	<input type="text" class="{required:true}" title="<?=lang('br_label')?> is required" value="<?=$label?>" name="label"></td>
	    	</tr>
	    	<tr>
	        	<td class="cell_1">
	        		<?=lang('br_enabled')?></td>
	        	<td>
	            	<select class="" title="" name="enabled">
	            		<?php
	            			if($enabled == 1){
	            				$yes = "selected";
	            				$no = "";
	            			}else{
	            				$yes = "";
	            				$no = "selected";
	            			}
	            		?>
	            		<option value="1" <?=$yes?>><?=lang('br_yes')?></option>
	            		<option value="0" <?=$no?>><?=lang('br_no')?></option>
	            	</select></td>
	    	</tr>
	    	<tr class="odd">
	        	<td class="cell_1">
	        		<?=lang('br_sort')?></td>
	        	<td>
	            	<input type="text" class="{required:true,digit:true}" title="<?=lang('br_sort')?> is required" value="<?=$sort?>" name="sort"></td>
	    	</tr>
	    	<?php
		    	$i=0;
		    	foreach($fields as $f){
					$class = ($i % 2 == 0) ? 'odd' : '' ;
					$req = ($f["required"] == 1) ? ' * ' : '' ;
					echo '	<tr class="'.$class.'">
					        	<td class="cell_1">
					        		'.$f["label"].$req.'</td>
					        	<td>
					            	'.$f["input"];
						if(trim($f["descr"]) != ''){
							echo '<p>'.$f["descr"].'</p>';					
						}
					echo '		</td>
					    	</tr>';
					$i++;
				}
	    	?>
	     	<tr>
	    		<td colspan="2" style="text-align:right">
	    			* <?=lang('br_required')?></td>
	    	</tr>
		</tbody>
    </table>
</div>
</form>                     

<script type="text/javascript">
	$(function(){
		$('#shipping_edit').validate();
	});
</script>