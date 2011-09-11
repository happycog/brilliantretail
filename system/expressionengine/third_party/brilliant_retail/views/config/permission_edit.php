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
	
	$cp_pad_table_template["table_open"] = '<table cellspacing="0" id="site_edit" cellpadding="0" border="0" class="mainTable edit_form" style="clear:both">';

	$this->table->set_template($cp_pad_table_template);
	
	$this->table->set_heading(array('data' => $store[0]["site_label"],"colspan" => 2));

	$cell_1 = 	lang('br_permission');
	$permissions = '<a href="#" id="permission_select_all">'.lang('br_select_all').'</a> | <a href="#" id="permission_clear_all">'.lang('br_clear_all').'</a>
					<hr />
					'.$permissions;
    $this->table->add_row(array($cell_1,$permissions));
    
	$content = $this->table->generate();
	$this->table->clear();
	
	echo form_open_multipart('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_permission_update',array('method' => 'POST', 'id' => 'storeForm'),$hidden);
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
				<h3><?=lang('br_permission')?>
				</h3>
				<p id="b2r_numprod"><span><b><?=$group?></b></span></p>
				<div class="b2r_clearboth"><!-- --></div>
    			<div id="header_buttons">
				    <?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
					<p class="b2r_cancel"><a href="<?=$base_url.'&method=config_site'?>"><?= lang('br_cancel'); ?></a></p>
			    	<div class="b2r_clearboth"><!-- --></div>
			    </div>
    		</td>
		</tr>
    </table>

	<div class="b2r_clearboth"><!-- --></div>

	<?=$content?>

</div>
</form>
<script type="text/javascript">
	$(function(){
		$('.permmision_checkbox').change(function(){
			if($(this).is(':checked')){
				var a = $(this).val().split('|');
				alert(a[1]);
			}
		});
		$('#permission_select_all').click(function(){
			$('input[type=checkbox]','#permmision_tree').attr('checked','checked');
			return false;
		});
		$('#permission_clear_all').click(function(){
			$('input[type=checkbox]','#permmision_tree').removeAttr('checked');
			return false;
		});
		
	});
</script>