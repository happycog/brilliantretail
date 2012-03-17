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

// Secure URL Path
$sql[] = "ALTER TABLE exp_br_store ADD COLUMN `secure_url` varchar(150) NOT NULL";
$sql[] = "UPDATE exp_br_store SET secure_url = 'http://".$_SERVER["HTTP_HOST"]."' WHERE 1 = 1";

// Add some url  
$sql[] = "ALTER TABLE exp_br_store ADD COLUMN `cart_url` varchar(100) NOT NULL DEFAULT 'cart'";
$sql[] = "ALTER TABLE exp_br_store ADD COLUMN `checkout_url` varchar(100) NOT NULL DEFAULT 'checkout'";
$sql[] = "ALTER TABLE exp_br_store ADD COLUMN `customer_url` varchar(100) NOT NULL DEFAULT 'customer'";
$sql[] = "ALTER TABLE exp_br_store ADD COLUMN `product_url` varchar(100) NOT NULL DEFAULT 'product'";

// Add a new cost variable to the br_product and br_order_item tables
$sql[] = "ALTER TABLE exp_br_product ADD COLUMN `cost` decimal(10,2) NOT NULL DEFAULT '0.00'";
$sql[] = "ALTER TABLE exp_br_order_item ADD COLUMN `cost` decimal(10,2) NOT NULL DEFAULT '0.00'";

// Remove and items in carts. Sorry but we need to add new cost variable into cart array
$sql[] = "DELETE FROM exp_br_cart WHERE status = 0";