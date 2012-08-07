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

	echo form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_gateway_update',
					array(	'method' 	=> 'POST', 
							'id' 		=> 'gateway_edit',
							'class' 	=> 'b2r_category', 
							'encrypt' 	=> 'multipart/form-data'),
					array(	'config_id' => $config_id));
?>
<div id="b2r_page" class="b2r_category">
	<table id="gateway_tbl" class="product_edit" width="100%" cellpadding="0" cellspacing="0">
		<thead>
    		<tr>
    			<th colspan="2">
    				<?=$title?></th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php
    			if($instructions != ''){
    		?>
					<tr>
		    			<td colspan="2" style="line-height: 18px;font-weight:bold;padding:15px;" valign="top">
		    				<?=$instructions?></td>
		    		</tr>
			<?php	    		
    			}
    		?>
    		<tr>
	        	<td class="cell_1">
	        		<?=lang('label')?></td>
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
	    	<tr>
	        	<td class="cell_1">
	        		<?=lang('br_sort')?></td>
	        	<td>
	            	<input type="text" class="{required:true,digit:true}" title="<?=lang('br_sort')?> is required" value="<?=$sort?>" name="sort"></td>
	    	</tr>
	    	<?php
		    	$i=1;
		    	foreach($fields as $f){
					$req = ($f["required"] == 1) ? ' * ' : '' ;
					echo '	<tr>
					        	<td class="cell_1">
					        		'.$f["label"].$req.'</td>
					        	<td>
					            	'.$f["input"];
						if(trim($f["descr"]) != ''){
							echo '<div style="width:85%"><p>'.$f["descr"].'</p></div>';					
						}
					echo '		</td>
					    	</tr>';
					$i++;
				}
	    	?>
		</tbody>
    </table>
	<div id="bottom_buttons">
	    <?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
		<div class="b2r_clearboth"><!-- --></div>
    </div>
</div>
</form>                     
<script type="text/javascript">
	$(function(){
		$('#gateway_edit').validate();
		$('#gateway_tbl tr:odd').addClass('odd');
	});
</script>