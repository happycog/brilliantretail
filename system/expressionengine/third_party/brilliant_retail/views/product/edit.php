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
?>
<style type="text/css">
.publish_field {
	border-right: 1px #D0D7DF solid;
}
</style>
<div id="b2retail">

	<?=$br_header?>
   
    <div id="b2r_content">

    	<?=$br_menu?>
        
        <div id="b2r_main">
        
            <?=$br_logo?>
            
			<?=form_open_multipart('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=product_update',array('method' => 'POST', 'id' => 'productForm'),$hidden)?>
					
            <div id="b2r_panel">
                
                <div id="b2r_panel_int">
                
                	<div id="b2r_settings">
                
						<div id="b2r_page" class="b2r_category">
					
                	        <div id="error" style="display:none">
								<p>
									<?=lang('br_form_error_message')?>
								</p>
							</div>

							<?php
								$i = 0;
								foreach($tab as $val){
									echo $val;
									$i++;
								}
							?>

							<ul id="publish_submit_buttons">
								<li><?=form_submit(array('name' => 'save_continue', 'value' => lang('br_save_continue'), 'class'=>'submit'))?></li>
								<li><?=form_submit(array('name' => 'save', 'value' => lang('save'), 'class'=>'submit'))?></li>
								<?php 
									if($products[0]["product_id"] != 0){ 
								?>
									<li><?=form_submit(array('name' => 'duplicate', 'value' => lang('br_duplicate'), 'class'=>'submit'))?></li>
									<li><?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'submit'))?></li>
								<?php
									}
								?>
							</ul>
						</div> <!-- b2r_dashboard --> 
                </div> <!-- b2r_panel_int -->
				<div class="b2r_clearboth"><!-- --></div>
            </div> <!-- b2r_panel -->
            <div class="b2r_clearboth"><!-- --></div>
    	</div> <!-- b2r_main -->
        <div class="b2r_clearboth"><!-- --></div>
					
		</form>	
        
        <?=$br_footer?>
        
    </div> <!-- b2r_content -->
	<div class="b2r_clearboth"><!-- --></div>    
</div> <!-- #b2retail -->

<!-- Modals -->
	<div id="write_mode_container">
		<div id="write_mode_close_container">
			<a href="#" class="publish_to_field close"><?=lang('wm_publish_to_field')?></a>&nbsp;
			<a href="#" class="discard_changes close"><?=lang('wm_discard_changes')?></a>
		</div>

		<div id="write_mode_writer">
			<textarea id="write_mode_textarea"></textarea>
		</div>
		<div id="write_mode_footer">
			<a href="#" class="publish_to_field close"><?=lang('wm_publish_to_field')?></a>&nbsp;
			<a href="#" class="discard_changes close"><?=lang('wm_discard_changes')?></a>
		</div>
	</div>
	
	<?php if ($this->session->userdata('group_id') == 1):?>
		<div id="new_tab_dialog" title="<?=lang('add_tab')?>" style="display: none;">
			<form action="#">
				<p>
					<label><?=lang('tab_name')?></label> 
					<input id="tab_name" type="text" value="" name="tab_name"/>
				</p>
			</form>
		</div>
	<?php endif;?>
<!-- /Modals -->

<script type="text/javascript">
	var field = 10000;
	var imgCount = 10000;
	var swfu;
	
	$(function() {
		$('#productForm').validate({'errorLabelContainer': $("#error")});
		
		$('#delete').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_product')?>')){
				return true;
			}else{
				return false;
			}
		});
	});
</script>
