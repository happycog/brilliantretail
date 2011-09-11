<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 20211, Brilliant2.com 		*/
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

// Create the wishlist table
	$sql[] = "CREATE TABLE exp_br_wishlist (
				  wishlist_id int(11) NOT NULL AUTO_INCREMENT,
				  member_id int(11) NOT NULL DEFAULT '0',
				  session_id varchar(100) NOT NULL,
				  is_public int(11) NOT NULL DEFAULT '0',
				  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  updated timestamp NULL DEFAULT NULL,
				  status int(11) NOT NULL DEFAULT '0',
				  ip varchar(100) NOT NULL,
				  PRIMARY KEY (wishlist_id),
				  KEY index_wishlist (member_id,session_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8";
				
$sql[] = "ALTER TABLE exp_br_admin_access ADD INDEX index_admin_access (site_id,group_id);";
$sql[] = "ALTER TABLE exp_br_attribute ADD INDEX index_attribute (site_id);";
$sql[] = "ALTER TABLE exp_br_attribute_set ADD INDEX index_attribute_set (site_id);";
$sql[] = "ALTER TABLE exp_br_attribute_set_attribute ADD INDEX index_attribute_set_attribute (attribute_id,attribute_set_id);";
$sql[] = "ALTER TABLE exp_br_cart ADD INDEX index_cart (member_id,session_id);";
$sql[] = "ALTER TABLE exp_br_category ADD INDEX index_category (site_id,parent_id);";
$sql[] = "ALTER TABLE exp_br_config ADD INDEX index_config (site_id);";
$sql[] = "ALTER TABLE exp_br_config_data ADD INDEX index_config_data (config_id);";
$sql[] = "ALTER TABLE exp_br_order ADD INDEX index_order (subscription_id,site_id,member_id,status_id);";
$sql[] = "ALTER TABLE exp_br_order_address ADD INDEX index_order_address (order_id);";
$sql[] = "ALTER TABLE exp_br_order_download ADD INDEX index_order_download (downloadable_id,product_id,order_id);";
$sql[] = "ALTER TABLE exp_br_order_item ADD INDEX index_order_item (order_id,product_id);";
$sql[] = "ALTER TABLE exp_br_order_note ADD INDEX index_order_note (order_id,member_id);";
$sql[] = "ALTER TABLE exp_br_order_options ADD INDEX index_order_options (order_item_id,product_id);";
$sql[] = "ALTER TABLE exp_br_order_payment ADD INDEX index_payment (order_id,transaction_id);";
$sql[] = "ALTER TABLE exp_br_order_ship ADD INDEX index_ship (order_id);";
$sql[] = "ALTER TABLE exp_br_order_subscription ADD INDEX index_subscription (order_id,subscription_id,status_id,product_id,group_id);";
$sql[] = "ALTER TABLE exp_br_product ADD INDEX index_product (site_id,type_id);";
$sql[] = "ALTER TABLE exp_br_product_attributes ADD INDEX index_product_attributes (product_id,attribute_id);";
$sql[] = "ALTER TABLE exp_br_product_bundle ADD INDEX index_product_bundle (parent_id,product_id);";
$sql[] = "ALTER TABLE exp_br_product_category ADD INDEX index_product_category (site_id,category_id,product_id,sort_order);";
$sql[] = "ALTER TABLE exp_br_product_configurable ADD INDEX index_product_configurable (product_id,sku);";
$sql[] = "ALTER TABLE exp_br_product_download ADD INDEX index_product_download (product_id);";
$sql[] = "ALTER TABLE exp_br_product_images ADD INDEX index_product_images (product_id);";
$sql[] = "ALTER TABLE exp_br_product_options ADD INDEX index_product_options (product_id);";
$sql[] = "ALTER TABLE exp_br_product_related ADD INDEX index_product_related (product_id,parent_id);";
$sql[] = "ALTER TABLE exp_br_product_subscription ADD INDEX index_product_subscriptions (product_id);";
$sql[] = "ALTER TABLE exp_br_product_subscription_price ADD INDEX index_product_subscriptions_price (subscription_id);";
$sql[] = "ALTER TABLE exp_br_promo ADD INDEX index_promo (site_id,code);";
$sql[] = "ALTER TABLE exp_br_search ADD INDEX index_search (site_id,hash);";
$sql[] = "ALTER TABLE exp_br_state ADD INDEX index_state (zone_id,code);";
$sql[] = "ALTER TABLE exp_br_store ADD INDEX index_store (site_id);";
$sql[] = "ALTER TABLE exp_br_tax ADD INDEX index_tax (site_id,zone_id,state_id);";

// Unused Table 
$sql[] = "DROP TABLE IF EXISTS exp_br_cart_item;";
$sql[] = "DROP TABLE IF EXISTS exp_br_product_type;";