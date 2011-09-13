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

/********************/
/* Images Tab 		*/
/********************/
$hide_header = (count($images) == 0) ? 'style="display:none"' : '';
?>	
<table id="imageTable" cellspacing="0" cellpadding="0" border="0" class="mainTable edit_form">
	<tr class="nodrag nodrop">
		<th colspan="7"><?=lang('br_images')?></th>
	</tr>
	<tr class="nodrag nodrop">
		<td colspan="7" align="right" style="text-align:right">
			<div id="img_upload_button" style="margin:0;"> 
				<div id="showprogress"><img src="<?=$theme?>images/loader.gif" /></div>
				<div id="divButtonPlaceholder">
					<span id="spanButtonPlaceholder"></span>
				</div>
				<div id="showimages"></div>
			</div></td>
	</tr>
	<tr id="image_header" class="nodrag nodrop" <?=$hide_header?>>
		<td width="120"><b><?=lang('br_image')?></b></td>
		<td width="*"><b><?=lang('br_title')?></b></td>
		<td width="65"><b><?=lang('br_large')?></b></td>
		<td width="65"><b><?=lang('br_thumbnail')?></b></td>
		<td width="65"><b><?=lang('br_exclude')?></b></td>
		<td width="10">&nbsp;</td>
		<td width="30">&nbsp;</td>
	</tr>
<?php
	$cnt = 0;
	
	foreach($images as $img){
		$large = ($img["large"] == 1) ? 'checked' : '';
		$thumb = ($img["thumb"] == 1) ? 'checked' : '';
		$exclude = ($img["exclude"] == 1) ? 'checked' : '';
		
		$a = explode(".",$img["filenm"]);
		$len = strlen($img["filenm"])-(strlen($a[count($a)-1])+1);
		$base = substr($img["filenm"],0,$len);
		echo '	<tr>
					<td style="text-align:center">
						<img src="'.$media_url.'products/thumb/'.$img["filenm"].'" />
						<input type="hidden" name="cImg_name['.$base.']" value="'.$img["filenm"].'" /></td>
					<td><input type="text" name="cImg_title['.$base.']" value="'.$img["title"].'" /></td>
					<td><input type="radio" name="cImg_large" value="'.$base.'" '.$large.' /></td>
					<td><input type="radio" name="cImg_thumb" value="'.$base.'" '.$thumb.' /></td>
					<td><input type="checkbox" name="cImg_exclude['.$base.']"  value="'.$img["filenm"].'" '.$exclude.' /></td>
					<td class="move_image_row"><img src="'.$theme.'images/icon_move.png" /></td>
					<td><a href="#" class="remove_img">'.lang('delete').'</a></td>
				</tr>';
		$cnt++;
	}
?>
</table>
<script type="text/javascript">
	$(function(){
		_restripe_images();
		create_image_uploader();
});
function create_image_uploader(){
	// Create the image 
		swfu = new SWFUpload({
			// Backend Settings
			upload_url: "<?=$image_upload?>",
			post_params: {
							"site_id" : <?=$site_id?>, 
							"PHPSESSID" : "<?=session_id()?>"
						},

			// File Upload Settings
			file_size_limit : "20 MB",	// 4MB
			file_types : "*.jpg;*.png;*.gif",
			file_types_description : "JPG, PNG, or GIF Images",
			file_upload_limit : 0,

			file_dialog_complete_handler : 	function fileDialogComplete(numFilesSelected, numFilesQueued) {
												try {
													if (numFilesQueued > 0) {
														$('#showprogress').show();
														this.startUpload();
													}
												} catch (ex) {
													this.debug(ex);
												}
											},
			upload_progress_handler : 	function uploadProgress(file, loaded, total) {
											/* var percent = Math.ceil((loaded / total) * 100); */
										}, 
			upload_success_handler : function uploadSuccess(file,serverData){
																var img = serverData.split('|');
																$('	<tr>'+ 
																	'	<td style="text-align:center"><img src="<?=$media_url?>products/thumb/'+img[1]+'" />'+
																	'		<input type="hidden" name="cImg_name['+img[0]+']" value="'+img[1]+'" /></td>'+
																	'	<td><input type="text" name="cImg_title['+img[0]+']" /></td>'+
																	'	<td><input type="radio" name="cImg_large" value="'+img[0]+'" /></td>'+
																	'	<td><input type="radio" name="cImg_thumb" value="'+img[0]+'" /></td>'+
																	'	<td><input type="checkbox" name="cImg_exclude['+img[0]+']"  value="'+img[1]+'" /></td>'+
																	'	<td class="move_image_row"><img src="<?=$theme?>images/icon_move.png" /></td>'+
																	'	<td><a href="#" class="remove_img"><?=lang('delete')?></a></td>'+
																	'</tr>').appendTo($('#imageTable tbody'));
																	
																	// Make sure the header is showing
																		$('#image_header').show();
																		
																	// Make sure that at least one radio button is selected
																	// For the large and the thumbnail image
																		_set_image_radio();
																	
																	// Restrip things and set the delete buttons
																		_restripe_images();
																	
															},
			upload_complete_handler :  function uploadComplete(file) {
											try {
												/*  I want the next upload to continue automatically so I'll call startUpload here */
												if (this.getStats().files_queued > 0) {
													this.startUpload();
												} else {
													$('#showprogress').hide();
												}
											} catch (ex) {
												this.debug(ex);
											}
										},
			// Button Settings
			button_image_url : "<?=$theme?>images/btn-img-upload.png",
			button_placeholder_id : "spanButtonPlaceholder",
			button_width: 180,
			button_height: 36,
			button_text : '<span class="button">Select Images <span class="buttonSmall">(4 MB Max)</span></span>',
			button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 14pt; color: #ffffff } .buttonSmall { font-size: 10pt; color: #ffffff }',
			button_text_top_padding: 9,
			button_text_left_padding: 16,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			
			// Flash Settings
			flash_url : "<?=$theme?>script/swfupload/swfupload.swf",
			flash9_url : "<?=$theme?>script/swfupload/swfupload_fp9.swf",
	
			custom_settings : {
				upload_target : "divFileProgressContainer"
			},
			
			// Debug Settings
			debug: false  
		});
}

function _set_image_radio(){
	if($('#imageTable tr').size() == 2){
		// No need to show the header
		$('#image_header').hide();	
	}else{
		// Set the radio button for 
		// the large image if needed
			if($('input[name="cImg_large"]:checked').size() == 0){
				$('input[name="cImg_large"]:eq(0)').attr('checked',true);
			}
		// Set the radio button for
		// the thumbnail if needed
			if($('input[name="cImg_thumb"]:checked').size() == 0){
				$('input[name="cImg_thumb"]:eq(0)').attr('checked',true);
			}
	}
}

function _restripe_images(){
	$('#imageTable tr').removeClass('even').removeClass('odd');
	$('#imageTable tr:even').addClass('even');
	$('#imageTable tr:odd').addClass('odd');
	$('#imageTable').tableDnD({
								dragHandle:'move_image_row',
								onDragClass: 'tDnD_whileDrag',  
								onDrop: stripe_table  
							});
							
	$('.remove_img').unbind('click').bind('click',function(){
																		$(this).parent().parent().remove();
																		_set_image_radio();
																		return false;
																	});
}		
</script>