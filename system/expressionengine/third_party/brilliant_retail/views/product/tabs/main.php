<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
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

/****************/
/* Details Tab	*/
/****************/
?>
	<style type="text/css">
		#url_display {
			font-size: 11px;
			padding: 3px;
		}
		#url_display span {
			font-size: 		11px;
			font-weight: 	bold;
			cursor: 		pointer;
		}
		#url_input {
			display: none;
		}
	</style>
	<div class="publish_field" id="hold_br_title">
	
		<label class="hide_field">
			<span>
				<em class="required">*</em> <?=lang('br_title')?>
			</span>
		</label>
	
		<div id="sub_hold_br_title">
			<fieldset class="holder">
				<?=form_input(
							array(	'name' => 'title', 
									'id' => 'title',
									'value' => $products[0]["title"],
									'class' => '{required:true}',
									'title' => lang('br_details').' - '.lang('br_product_title').' '.lang('br_is_required'))
						)?>
				<div id="url_display">
					<?=strtolower(lang('br_url_title'))?>: <span><?=$products[0]["url"]?></span>
					<span id="url_input">
						<input 	type="text"
								name="url" 
								id="url"  
								value="<?=$products[0]["url"]?>" 
								style="width:200px;" /> 
					</span>
				</div>
			</fieldset>
		</div> <!-- /sub_hold_field -->
	
	</div>
	
	<div class="publish_field" id="hold_br_type">
	
		<label class="hide_field">
			<span>
				<em class="required">*</em> <?=lang('br_product_type')?> - <?=$type?>
			</span>
		</label>
	
		<div class="b2r_clearboth"><!-- --></div>
		
		<div id="sub_hold_br_type">
			<fieldset class="holder custom_field">
				<?=$sub_type?>
			</fieldset>
		</div> <!-- /sub_hold_field -->
	
	</div>