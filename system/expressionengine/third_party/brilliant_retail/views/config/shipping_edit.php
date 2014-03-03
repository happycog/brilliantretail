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
echo form_open_multipart(
                             $action,
            				 array(	'method' 	=> 'POST', 
            						'id' 		=> 'shipping_edit',
            						'class' 	=> 'b2r_category', 
            						'encrypt' 	=> 'multipart/form-data'),
            				 array(	'config_id' => $config_id)
        				 );
?>
<div id="b2r_page" class="b2r_category">
	<table id="shipping_tbl" class="product_edit" width="100%" cellspacing="0" cellpadding="0">
		<thead>
    		<tr>
    			<th width="35%">
    				<?=$title?></th>
    			<th>&nbsp;</th>
    		</tr>
    	</thead>
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
	    	<tr>
	        	<td class="cell_1">
	        		<?=lang('br_member_groups')?></td>
	        	<td>
	            	<select name="groups[]" multiple="multiple" style="width:200px;height:100px;">
                        <?php
                            $sel = '';
                            $all_groups = FALSE;
                            if(in_array(0,$groups))
                            {
                                $sel = 'selected="selected"';
                                $all_groups = TRUE;
                            }
                            
                            echo '<option value="0" '.$sel.'>'.lang('br_all_groups').'</option>';
                            
                            foreach($group_list as $key => $val)
                            {
                                $sel = (in_array($key,$groups) && $all_groups == FALSE) ? 'selected="selected"' : '';
                                echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
                            }
                        ?>
	            	</select></td>
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
							echo '<p><em>* '.$f["descr"].'</em></p>';					
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
		$('#shipping_edit').validate();
	});
</script>