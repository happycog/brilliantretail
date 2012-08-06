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
								lang('br_zone'),
								lang('br_state'),
								lang('br_zip_code'),
								lang('br_rate') 
							);

	$i = 1;
	foreach($tax as $t){
		$zone = ($t["zone"] == '') ? '<em>'.lang('br_all_zones').'</em>' : $t["zone"];
		$state = ($t["state"] == '') ? '<em>'.lang('br_all_states').'</em>' : $t["state"];
		$this->table->add_row(
								'<a href="'.$base_url.'&method=config_tax_edit&tax_id='.$t["tax_id"].'">'.$t["title"],
								$zone,
								$state,
								($t["zipcode"] != '') ? $t["zipcode"] : ' - ',
								$t["rate"].'%'
							);
		$i++;
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