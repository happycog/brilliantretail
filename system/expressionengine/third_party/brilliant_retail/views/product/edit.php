<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
/* 	@license	http://opensource.org/licenses/OSL-3.0	*/
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

	<?php

	   echo $br_header;   
	   
	   echo form_open_multipart(
	                       $action,
	                       array('method' => 'POST', 'id' => 'productForm'),$hidden);
    ?>

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
    
    <?=$br_footer?>
        
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
<!-- /Modals -->
<?php
// We might need to validate additional custom fields so lets try that now. 
	$rule 		= array();
	$message 	= array();
	foreach($custom as $c){
		if($c["settings"]["field_required"] =='y'){
			$rule[] 	= $c["settings"]["field_name"];
			$message[$c["settings"]["field_name"]] 	= $c["settings"]["field_label"];
		}
	}
?>
<script type="text/javascript">
	var field = 10000;
	var imgCount = 10000;
	var swfu;
	
	$(function() {
		$('#productForm').validate({
										errorLabelContainer: $("#error"),
										rules: {
                									<?php
	                									foreach($rule as $r){
	                										echo $r.' : { required: true },';
														}
													?>
												},
									 	messages: {
                									<?php
                										foreach($message as $key => $val){
	                										echo $key.' : "'.$val.' is required",';
	                									}
													?>
                								},
										invalidHandler: function(form, validator){
											$('html, body').animate({
																		scrollTop: 0
																	}, 1000);
										} 
									});
		
		$('#delete').bind('click',function(){
			if(confirm('<?=lang('br_confirm_delete_product')?>')){
				return true;
			}else{
				return false;
			}
		});
	});
</script>
