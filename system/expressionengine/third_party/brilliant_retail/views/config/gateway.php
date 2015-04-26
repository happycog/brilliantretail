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

	$cp_pad_table_template["table_open"] = '<table id="gatewayTable" cellpadding="0" cellspacing="0" class="product_edit" width="100%">';
	
	$this->table->set_template($cp_pad_table_template); 
	
	$this->table->set_heading(
		    		lang('br_title'),
		   			lang('br_descr'),
					lang('version'),
					lang('status'),
					lang('action')
					);
	
	foreach($modules as $m){
		if($m["installed"] == 1){
			$enabled = ($m["enabled"] == 1)? lang('br_enabled') : '<span style="color:red">'.lang('br_disabled')."</span>";
			$class 	= '';
			$link 	= '<a href="'.$base_url.AMP.'method=config_gateway_remove&config_id='.$m["config_id"].'&code='.$m["code"].'&type='.$m["type"].'" class="remove"><img src="'.$theme.'images/delete.png" /></a>';
			$title 	= '<a href="'.$base_url.AMP.'method=config_gateway_edit&config_id='.$m["config_id"].'&code='.$m["code"].'">'.$m["title"].'</a>';	
		}else{
			$enabled = ' - ';
			$class 	= 'not_installed';
			$link 	= '<a href="'.$base_url.AMP.'method=config_gateway_install&type='.$m["type"].'&code='.$m["code"].'" class="install"><img src="'.$theme.'images/add.png" /></a>';
			$title 	= $m["title"];	
		}
		
		$last = array('data' => $link, 'style' => 'text-align:center');
		
		$this->table->add_row(
				        		'<span style="display:none">'.$m["title"].'</span>'.$title,
			        			$m["descr"],
			        			$m["version"],
			        			$enabled,
			        			$last
		        			);
	}
	echo $this->table->generate();
?>
<script type="text/javascript">
	$(function(){
		$('#gatewayTable').tablesorter({
			headers: {},
        	textExtraction: "complex",			
			widgets: ["zebra"]
		});
	});
</script>