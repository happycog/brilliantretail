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

$sql[] = "DROP TABLE IF EXISTS exp_br_cron";
$sql[] = "DROP TABLE IF EXISTS exp_br_feeds;";
$sql[] = "DROP TABLE IF EXISTS exp_br_product_feeds;";
$sql[] = "DROP TABLE IF EXISTS exp_br_product_price;";

## ----------------------------
##  Table structure for exp_br_product_feeds
## ----------------------------
		
	$sql[] = "CREATE TABLE exp_br_product_feeds (
				  product_feed_id int(11) NOT NULL AUTO_INCREMENT,
				  product_id int(11) NOT NULL DEFAULT '0',
				  feed_id int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (product_feed_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	$sql[] = "INSERT INTO exp_br_product_feeds (product_id,feed_id) VALUES ('2519','1'),('2523','1'),('2516','1'),('2517','1');";
	
## ----------------------------
##  Table structure for exp_br_feeds
## ----------------------------

	$sql[] = "CREATE TABLE exp_br_feeds (
				  feed_id int(11) NOT NULL AUTO_INCREMENT,
				  feed_title varchar(128) NOT NULL DEFAULT '',
				  feed_code varchar(128) NOT NULL DEFAULT '',
				  PRIMARY KEY (feed_id),
				  UNIQUE (feed_code)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";	
	
	$sql[] = "INSERT INTO exp_br_feeds (feed_title,feed_code) VALUES ('Google Base','google_base');";

## ----------------------------
##  Table structure for exp_br_cron
## ----------------------------

	$sql[] = "CREATE TABLE exp_br_cron (
				  cron_id int(11) NOT NULL AUTO_INCREMENT,
				  status int(11) NOT NULL DEFAULT '0',
				  class varchar(128) NOT NULL DEFAULT '',
				  method varchar(128) NOT NULL DEFAULT '',
				  params text,
				  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (cron_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				
## ----------------------------
##  Table structure for exp_br_product_price
## ----------------------------

// Create the new pricing table
	$sql[] = "	CREATE TABLE exp_br_product_price (
					price_id int(11) NOT NULL AUTO_INCREMENT,
					product_id int(11) NOT NULL,
					type_id int(11) NOT NULL,
					group_id int(11) NOT NULL,
					price decimal(10,2) NOT NULL,
					qty int(11) DEFAULT '1',
					end_dt datetime DEFAULT NULL,
					start_dt datetime DEFAULT NULL,
					sort_order int(11) NOT NULL,
					PRIMARY KEY (price_id),
					KEY product_price_index (product_id,group_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";