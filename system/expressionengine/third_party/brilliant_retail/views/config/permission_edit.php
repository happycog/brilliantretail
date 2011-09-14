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
	
	$this->table->set_heading(array('data' => $group,"colspan" => 2));

	$cell_1 = 	lang('br_permission');
	$permissions = '<a href="#" id="permission_select_all">'.lang('select_all').'</a> | <a href="#" id="permission_clear_all">'.lang('br_clear_all').'</a>
					<hr />
					'.$permissions;
					
    $this->table->add_row(array($cell_1,$permissions));
    
	$content = $this->table->generate();
	$this->table->clear();
	
	echo form_open_multipart('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_permission_update',array('method' => 'POST', 'id' => 'storeForm'),$hidden);
?>
<div id="b2r_page" class="b2r_category">
	<?=$content?>
	<div id="header_buttons">
	    <?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
    	<div class="b2r_clearboth"><!-- --></div>
    </div>
</div>
</form>
<script type="text/javascript">
	$(function(){
		$('.permmision_checkbox').change(function(){
			if($(this).is(':checked')){
				var a = $(this).val().split('|');
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