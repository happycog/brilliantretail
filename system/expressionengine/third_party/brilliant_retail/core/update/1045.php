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

## ----------------------------
##  Table structure for exp_br_product_related
## ----------------------------
	$this->EE->db->query("DROP TABLE IF EXISTS exp_br_product_entry;");
	$this->EE->db->query("CREATE TABLE exp_br_product_entry (
					product_entry_id int(11) NOT NULL AUTO_INCREMENT,
					product_id int(11) NOT NULL,
					entry_id int(11) NOT NULL,
					PRIMARY KEY (product_entry_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	
	$this->EE->db->query("ALTER TABLE exp_br_store ADD COLUMN channel_id int(11) AFTER site_id");