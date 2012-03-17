<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

$sql[] = "CREATE TABLE exp_br_product_feeds (
				  product_feed_id int(11) NOT NULL AUTO_INCREMENT,
				  product_id int(11) NOT NULL DEFAULT '0',
				  feed_id int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (product_feed_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$sql[] = "CREATE TABLE exp_br_feeds (
				  feed_id int(11) NOT NULL AUTO_INCREMENT,
				  feed_name varchar(128) NOT NULL DEFAULT '',
				  feed_description varchar(512),
				  feed_version int(11) NOT NULL DEFAULT '1',
				  PRIMARY KEY (feed_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$sql[] = "INSERT INTO exp_br_feeds (feed_name,feed_description,feed_version) VALUES ('Google Base','Generates RSS/XML Products Feed for Google Base','1');";

$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'pull_feed')";