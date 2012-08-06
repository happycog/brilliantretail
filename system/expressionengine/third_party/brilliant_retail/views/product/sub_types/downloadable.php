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
<div id="sub_type_4" class="subtypes">
	<table id="download_selected" class="product_edit" width="100%" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td colspan="5" id="download_buttons">
					<div id="download_add_button">
						<div id="showDownloadProgress"><img src="<?=$theme?>images/loader.gif" /><span id="showPercent"></span></div>
						<span id="spanDownloadPlaceholder"></span></span>
						<input type="hidden" name="require_download" title="<?=lang('br_details').' - '.lang('br_download_file_required').' '.lang('br_is_required')?>" id="sub_type_req_4"  value="1" class="{required:true} sub_type_req" />
					</div>
					<?php
						if(count($imports) > 0){
					?>
							<div id="download_browse_button" >
								<?=lang('br_browse')?>
							</div>
							<div style="clear:both"></div>
							<div id="download_options_wrap">
								<div id="download_options_header">
									<?=lang('br_filename')?>
								</div>
								<div id="download_options">
									<table width="100%" cellspacing="0" border="0">
										<?php
											foreach($imports as $fl){
												echo '	<tr>
															<td>
																'.$fl.'</td>
															<td align="right">
																<a href="#" data-filename="'.$fl.'|'.$fl.'|'.$fl.'" class="download_options_add">'.lang('br_add').'</a></td>
														</tr>';
											}
										?>					
									</table>
								</div></td>
							</div>
					<?php
						}
					?></td>
			</tr>
			<tr>
				<th><?=lang('br_title')?></th>
				<th><?=lang('br_file_name')?></th>
				<th><?=lang('br_download_limit')?></th>
				<th><?=lang('br_download_length')?></th>
				<th><?=lang('br_download_version')?></th>
			</tr>
		</thead>
		<tbody id="download_file">
		<?php
			if(isset($products[0]["download"])){
				$a = $products[0]["download"][0];
				echo '	<tr class="odd">
							<td>
								<input type="text" name="download_title" value="'.$a['title'].'" style="width:96%" /></td>
								<td>
									<input type="hidden" name="download_import" value="0" />
									<input type="hidden" name="download_filenm" value="'.$a['filenm'].'" />
									<input type="hidden" name="download_filenm_orig" value="'.$a['filenm_orig'].'" />'.$a['filenm_orig'].'</td> 
								<td>
									<input type="text" name="download_limit" value="'.$a['download_limit'].'" style="width:30px" /> *</td>
								<td>
									<input type="text" name="download_length" value="'.$a['download_length'].'" style="width:30px" /> *</td>
								<td>
									<input type="text" name="download_version" value="'.$a['download_version'].'" style="width:30px" /></td>
						<tr>';
			}else{
				echo '	<tr class="odd"> 
							<td colspan="5">'.lang('br_upload_download_message').'</td>
						</tr>';
			}
		?>
		</tbody>
	</table>
	<div style="text-align:right;padding: 5px">
		<em>*<?=lang('br_download_limit_instruction')?></em>
	</div>
</div>

<script type="text/javascript">

	$(function(){

		uploads = new SWFUpload({
			// Backend Settings
			upload_url: "<?=$download_upload?>",
			post_params: {
							"site_id" : <?=$site_id?>, 
							"PHPSESSID" : "<?=session_id()?>"
						},

			// File Upload Settings
			file_size_limit : "100 MB",	// 4MB
			file_upload_limit : 0,
			
			file_dialog_complete_handler : function fileDialogComplete(numFilesSelected, numFilesQueued) {
												if (numFilesQueued > 0) {
													$('#showDownloadProgress').show();
													this.startUpload(this.getFile(0).ID);
												}
											},
			upload_progress_handler : 	function uploadProgress(file, loaded, total) {
											 var percent = Math.ceil((loaded / total) * 100);
											 $('#showPercent').html(percent);
										}, 
			upload_success_handler : function uploadSuccess(file,serverData){
																update_download(serverData,0);
															},
			upload_complete_handler : function uploadComplete(file,serverData){
											if (this.getStats().files_queued > 0) {
												this.startUpload(this.getFile(0).ID);
											}else{
												$('#showDownloadProgress').hide();
											}
										},
	
			// Button Settings
			button_image_url : "<?=$theme?>images/btn-add.png",
			button_placeholder_id : "spanDownloadPlaceholder",
			button_width: 100,
			button_height: 24,
			button_text : '<span class="button"><?=lang('br_upload')?></span>',
			button_text_style : '.button { font-family: Helvetica, Arial, sans-serif;font-weight:bold;font-size: 12px; color: #666666 }',
			button_text_top_padding: 6,
			button_text_left_padding: 25,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			
			// Flash Settings
			flash_url : "<?=$theme?>script/swfupload/swfupload.swf",
			flash9_url : "<?=$theme?>script/swfupload/swfupload_fp9.swf",
	
			// Debug Settings
			debug: false
			});
			
			// We also need to account for 
				$('#download_browse_button').bind('click',function(){
					$('#download_options_wrap').slideToggle();
					return false;
				});
				
				$('.download_options_add').bind('click',function(){
					var serverData = $(this).attr('data-filename');
					update_download(serverData,1);
					$('#download_options_wrap').slideUp();
					return false;
				});
			
		});
		
		function update_download(serverData,b){
			var a = serverData.split('|');
			$('#download_selected tbody#download_file tr').remove();
			$(	'<tr>'+
					'<td><input type="text" name="download_title" value="'+a[0]+'" style="width:96%" /></td>'+
					'<td> 	<input type="hidden" name="download_import" value="'+b+'" />'+
					'		<input type="hidden" name="download_filenm" value="'+a[2]+'" />'+
					'		<input type="hidden" name="download_filenm_orig" value="'+a[1]+'" />'+a[1]+'</td>'+
					'<td><input type="text" name="download_limit" value="0" style="width:30px" /> *</td>'+
					'<td><input type="text" name="download_length" value="0" style="width:30px" /> *</td>'+
					'<td><input type="text" name="download_version" value="1.0" style="width:30px" /></td>'+
				'<tr>').appendTo($('#download_selected tbody#download_file'));
			$('.remove_img').unbind('click').bind('click',function(){
			$(this).parent().parent().remove();
				return false;
			});
			$('#sub_type_req_4').val(1);

		}
</script>