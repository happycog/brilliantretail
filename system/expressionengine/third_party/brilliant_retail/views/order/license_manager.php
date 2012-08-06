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

echo $br_header;

$cp_pad_table_template["table_open"] = '<table id="order_tbl" cellpadding="0" cellspacing="0" class="mainTable" style="clear:both;">';
	
$this->table->set_template($cp_pad_table_template); 

$this->table->set_heading(
	array(
			'data' => lang('br_order_id'),
			'style' => 'width:40px'
		),
	array(
			'data' => lang('br_order_date'),
			'style' => 'width:60px'
		),
	array(	
			'data' => lang('br_order_customer'),
			'style' => ''
		),
	array(
			'data' => lang('br_order_amount'),
			'style' => 'width:60px'
		),
	array(
			'data' => lang('br_order_status'),
			'style' => 'width:60px'
		)
	/*
		,array(
    		'data' => '<input type="checkbox" id="toggle_check" />', 
			'style' => 'text-align:center',
			'width' => '5%')
	*/
);

echo $this->table->generate();

echo $br_footer;