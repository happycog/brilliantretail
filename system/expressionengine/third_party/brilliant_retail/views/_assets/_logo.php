<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2013						*/
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
<div id="b2r_top">
<?php
if($message != ''){
  echo '<div id="b2r_status">
	    	<p id="b2r_status_msg">'.$message.'</p>
	        <a href="#" id="br_status_link">
	        	<img id="b2r_status_close" src="'.$theme.'/images/0.gif" alt="Close" title="Close" /></a>
	        <div class="b2r_clearboth"><!-- --></div>
	    </div> <!-- b2r_status -->';
}    
?>
<?php
if($alert != ''){
  echo '<div id="b2r_status">
	    	<p id="b2r_status_alert">'.$alert.'</p>
	        <a href="#" id="br_status_link">
	        	<img id="b2r_status_close" src="'.$theme.'/images/0.gif" alt="Close" title="Close" /></a>
	        <div class="b2r_clearboth"><!-- --></div>
	    </div> <!-- b2r_status -->';
}    
/*
?>

	<h1 id="b2r_title"><?=$site_name?></h1>
	<p id="b2r_viewsite"><a href="<?=base_url()?>" target="_blank"><b><?=lang('br_visit_website')?></b></a></p>
*/
?>	<div class="b2r_clearboth"><!-- --></div>
</div> <!-- b2r_top -->