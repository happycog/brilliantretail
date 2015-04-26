<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

// Clean up the product_subscription table
	$sql[] = "ALTER TABLE exp_br_product_subscription DROP COLUMN trial_length";
	$sql[] = "ALTER TABLE exp_br_product_subscription DROP COLUMN trial_period";
	
// Create the associated donation product table
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_donation";
	$sql[] = "CREATE TABLE exp_br_product_donation (
					donation_id int(11) NOT NULL AUTO_INCREMENT,
					product_id int(11) NOT NULL,
					allow_recurring int(11) NOT NULL DEFAULT '0',
					min_donation float NOT NULL DEFAULT '10',
					PRIMARY KEY (donation_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";