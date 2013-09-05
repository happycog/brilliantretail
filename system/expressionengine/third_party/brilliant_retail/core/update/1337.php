<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

	$sql[] = "	CREATE TABLE exp_br_product_configurable_attribute (
					config_attr_id int(11) NOT NULL AUTO_INCREMENT,
					configurable_id int(11) DEFAULT NULL,
					product_id int(11) DEFAULT NULL,
					attribute_id int(11) DEFAULT NULL,
					option_id int(11) DEFAULT NULL,
					sort int(11) DEFAULT NULL,
					PRIMARY KEY (config_attr_id),
					KEY br_product_id (product_id),
					KEY br_attribute_id (attribute_id),
					KEY br_option_id (option_id) 
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
