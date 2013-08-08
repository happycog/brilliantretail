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
				<td colspan="6" id="download_buttons">
					<div id="download_add_button">
						<div id="showDownloadProgress"><img src="<?=$theme?>images/loader.gif" /><span id="showPercent"></span></div>
						<span id="spanDownloadPlaceholder"></span></span>
						<input type="hidden" name="require_download" title="<?=lang('br_details').' - '.lang('br_download_file_required').' '.lang('br_is_required')?>" id="sub_type_req_4"  value="1" class="{required:true} sub_type_req" />
					</div>
					
					<?php
						if(count($imports) > 0){
					?>
							<div id="download_browse_button" >
								<?=lang('br_local_files')?>
							</div>
					<?php
						}
						if($has_s3){
					?>
							<div id="s3_import_button" >
								<?=lang('br_s3_files')?>
							</div>
					<?php
						}
					?>
						
						<div style="clear:both"></div>

					<?php
						if(count($imports) > 0){
					?>
							<div id="download_options_wrap">
								<table id="s3_import_buckets">
									<tr>
										<td width="100%">
											<div class="s3_wrap">
												<h4><?=lang('br_filename')?></h4>
												<div id="download_options">
													<ul>
														<?php
															foreach($imports as $fl){
																echo '	<li>
																			<a href="#" data-filename="'.$fl.'|'.$fl.'|'.$fl.'" class="download_options_add"><img src="'.$theme.'images/add.png" /></a> <span>'.$fl.'</span>
																		</li>';
															}
														?>					
													</ul>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</div>
					<?php
						}
						
						if($has_s3){
					?>
							<div id="s3_import_wrap">
								<table id="s3_import_buckets">
									<tr>
										<td width="50%">
											<div class="s3_wrap">
												<h4>Buckets</h4>
												<div id="s3_bucket_list">
													<ul id="s3_bucket_list_ul">
														<?php
															foreach($s3_buckets as $s3)
															{
																echo '<li><span>'.$s3.'</span></li>';
															}
														?>
													</ul>
												</div>
											</div></td>
										<td width="50%">
											<div class="s3_wrap">
												<h4>Files</h4>
												<div id="s3_file_list">
													<div id="s3_file_loader">
														<p>
															<?=lang('br_file_loader')?>
														</p>
													</div>
													<div id="s3_file_loading_image">
														<img src="<?=$theme?>images/loader.gif" />
													</div>
													<ul id="s3_file_list_ul">
													</ul>
												</div>
											</div></td>
									</tr>
								</table>
							</div>
					<?php
						}
					?></td>
			</tr>
			<tr>
				<th><?=lang('br_title')?></th>
				<th><?=lang('br_file_name')?></th>
				<th><?=lang('br_download_source')?></th>
				<th><?=lang('br_download_limit')?></th>
				<th><?=lang('br_download_length')?></th>
				<th><?=lang('br_download_version')?></th>
			</tr>
		</thead>
		<tbody id="download_file">
		<?php
			if(isset($products[0]["download"])){
				$a = $products[0]["download"][0];
				$source = ($a['download_source']=='S3') ? 2 : 0;
				echo '	<tr class="odd">
							<td>
								<input type="text" name="download_title" value="'.$a['title'].'" style="width:96%" /></td>
								<td>
									<input type="hidden" name="download_import" value="'.$source.'" />
									<input type="hidden" name="download_filenm" value="'.$a['filenm'].'" />
									<input type="hidden" name="download_filenm_orig" value="'.$a['filenm_orig'].'" />'.$a['filenm_orig'].'</td> 
								<td>
									'.$a['download_source'].'</td>
								<td>
									<input type="text" name="download_limit" value="'.$a['download_limit'].'" style="width:30px" /> *</td>
								<td>
									<input type="text" name="download_length" value="'.$a['download_length'].'" style="width:30px" /> *</td>
								<td>
									<input type="text" name="download_version" value="'.$a['download_version'].'" style="width:50px" /></td>
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
<style type="text/css">
	#download_options {
		height: 250px;
		overflow: auto;
	}
	
	#download_options_wrap,
	#s3_import_wrap {
		display: none;	
	}
	
	.download_options_add,
	.s3_import_add {
		border: 1px solid #c7ced3;
		border-radius: 5px;
		display: inline-block;
		padding: 5px 5px 2px;
		background: #ecf1f4;
		float:right;
	}

	#s3_import_buckets {
		width: 100%;
	}
	
	#s3_import_buckets td {
		border: 0 !important;
	}
	
	#s3_import_buckets div.s3_wrap {
		border: 1px solid #b6c0c2 !important;
		border-radius: 3px;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
	}
	
	#s3_import_buckets div h4 {
		border-bottom: 1px solid #b6c0c2 !important;
		padding: 5px 10px;
		background-color: #f5f6f5;
	}
	
	#s3_bucket_list,
	#s3_file_list {
		border: 0;
		height:200px;
		overflow: auto;
	}
	
	.s3_wrap ul li {
		padding: 4px 10px;
		border-bottom: #E7E7E7 solid 1px;
		cursor: pointer;
	}
	
	.s3_wrap ul li span,
	#download_options span {
		line-height: 24px;
	}
	
	.s3_wrap ul li.active {
		background: #999;
		color: #E7E7E7;
	}
	
	#s3_bucket_loader,
	#s3_file_loader,
	#s3_file_loading_image {
		text-align: center;
		padding: 20px;
	}
	
	#s3_file_loading_image {
		display: none;
	}
		
</style>
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
																						$('#s3_import_wrap,#download_options_wrap').slideUp();
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
									button_text_top_padding: 4,
									button_text_left_padding: 25,
									button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
									button_cursor: SWFUpload.CURSOR.HAND,
									
									// Flash Settings
									flash_url : "<?=$theme?>script/swfupload/swfupload.swf",
									flash9_url : "<?=$theme?>script/swfupload/swfupload_fp9.swf",
							
									// Debug Settings
									debug: false
								});
			
			// We also need to account local import buttons
				$('#download_browse_button').bind('click',function(){
					$('#s3_import_wrap').slideUp();
					$('#download_options_wrap').slideToggle();
					return false;
				});
				
				$('.download_options_add').bind('click',function(){
					var serverData = $(this).attr('data-filename');
					update_download(serverData,1);
					$('#download_options_wrap').slideUp();
					return false;
				});
			
			// We also need to account S3 import buttons
				$('#s3_import_button').bind('click',function(){
					$('#download_options_wrap').slideUp();
					$('#s3_import_wrap').slideToggle();
					return false;
				});

			// 
				$('#s3_bucket_list_ul li').click(function(){
					$('#s3_bucket_list_ul li').removeClass('active');
					$(this).addClass('active');
					$('#s3_file_list_ul li').remove();
					$('#s3_file_loading_image').show();
	            	$('#s3_file_loader').hide();
					
					var bucketname = $(this).find('span').html();
					
					$.getJSON(	'<?=$s3_get_files?>',
								{ 
									bucket: 	bucketname,
									timestamp : new Date().getTime()   
								}, 
								function(data){
									$('#s3_file_loading_image').hide();

					            	$.each(data, function(i,item){
										$('<li><a href="#" data-filename="'+item.filename+'|'+bucketname+' / '+item.filename+'|'+bucketname+' / '+item.filename+'" class="s3_import_add"><img src="<?=$theme?>images/add.png" /></a> <span>'+item.filename+'</span></li>').appendTo($('#s3_file_list_ul'));
					            	});
							
					            	$('.s3_import_add').unbind().bind('click',function(){
										var serverData = $(this).attr('data-filename');
										update_download(serverData,2);
										$('#s3_import_wrap').slideUp();
										return false;
									});

								});
				});
		});
		
		function update_download(serverData,b){
			var a = serverData.split('|');
			var source = (b == 2) ? 'S3' : 'local';
			$('#download_selected tbody#download_file tr').remove();
			$(	'<tr>'+
					'<td><input type="text" name="download_title" value="'+a[0]+'" style="width:96%" /></td>'+
					'<td> 	<input type="hidden" name="download_import" value="'+b+'" />'+
					'		<input type="hidden" name="download_filenm" value="'+a[2]+'" />'+
					'		<input type="hidden" name="download_filenm_orig" value="'+a[1]+'" />'+a[1]+'</td>'+
					'<td>'+source+'</td>'+
					'<td><input type="text" name="download_limit" value="0" style="width:30px" /> *</td>'+
					'<td><input type="text" name="download_length" value="0" style="width:30px" /> *</td>'+
					'<td><input type="text" name="download_version" value="1.0" style="width:50px" /></td>'+
				'<tr>').appendTo($('#download_selected tbody#download_file'));
			$('.remove_img').unbind('click').bind('click',function(){
			$(this).parent().parent().remove();
				return false;
			});
			$('#sub_type_req_4').val(1);

		}
</script>