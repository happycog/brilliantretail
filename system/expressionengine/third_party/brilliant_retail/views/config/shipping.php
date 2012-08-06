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

	$this->table->set_template($cp_pad_table_template); 
	
	$this->table->set_heading(
					    		lang('br_title'),
					   			lang('br_descr'),
								lang('version'),
								lang('status'),
								lang('action')
							);
	
	foreach($modules as $m){
			if(isset($m["config_id"])){
				$enabled = ($m["enabled"] == 1)? lang('br_enabled') : '<span style="color:red">'.lang('br_disabled')."</span>";
				$class = 'installed';
				$link 	= '<a href="'.$base_url.AMP.'method=config_shipping_remove&config_id='.$m["config_id"].'&code='.$m["code"].'" class="remove">'.lang('delete').'</a>';
				if($m['has_options'] == 0){
					$title 	= '<b>'.$m["title"].'</b>';	
				}else{
					$title 	= '<a href="'.$base_url.AMP.'method=config_shipping_edit&config_id='.$m["config_id"].'&code='.$m["code"].'">'.$m["title"].'</a>';	
				}
			}else{
				$enabled = ' - ';
				$class 	= 'not_installed';
				$link 	= '<a href="'.$base_url.AMP.'method=config_shipping_install&type='.$m["type"].'&code='.$m["code"].'" class="install">'.lang('install').'</a>';
				$title 	= $m["title"];	
			}
			
			
			$this->table->add_row(
				        		'<span style="display:none">'.$m["title"].'</span>'.$title,
			        			$m["descr"],
			        			$m["version"],
			        			$enabled, 
			        			$link
		        			);
	}

	echo $this->table->generate();
?>
<script type="text/javascript">
	$(function(){
		$('.mainTable').tablesorter({
			headers: {},
        	textExtraction: "complex",			
			widgets: ["zebra"]
		});
	});
</script>