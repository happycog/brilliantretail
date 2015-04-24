<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

// Add performance index to the order table
	$sql[] = "ALTER TABLE exp_br_order ADD INDEX order_member_id (member_id);";

// Add the action to the table for wishlist
	$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'wishlist_process')";

// Lets add the wishlist
	$sql[] = "DROP TABLE IF EXISTS exp_br_wishlist;";
	$sql[] = "CREATE TABLE exp_br_wishlist (
					wishlist_id int(11) NOT NULL AUTO_INCREMENT,
					member_id int(11) NOT NULL DEFAULT '0',
					is_public int(11) NOT NULL DEFAULT '0',
					created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					product_id int(11) NOT NULL,
					notes text,
					PRIMARY KEY (wishlist_id),
					KEY index_wishlist (member_id)
				) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
