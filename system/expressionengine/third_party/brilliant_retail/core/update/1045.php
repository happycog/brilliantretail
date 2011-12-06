<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter 								*/
/* 	@copyright	Copyright (c) 2011, Brilliant2.com 			*/
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

## ----------------------------
##  Table structure for exp_br_product_related
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_entry;";
	$sql[] = "CREATE TABLE exp_br_product_entry (
					product_entry_id int(11) NOT NULL AUTO_INCREMENT,
					product_id int(11) NOT NULL,
					entry_id int(11) NOT NULL,
					PRIMARY KEY (product_entry_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


## We need to load up some 

	$this->EE->load->library('api'); 
	$this->EE->api->instantiate('channel_entries');

## Create a field group

## We need a BrilliantRetail channel

	// We should check to make sure that there isn't an existing BR 
	// Channel. If so lets create a new channel. 

## Create an entry for every product

	$this->EE->db->from('br_product');
	$qry = $this->EE->db->get();
	foreach($qry->result_array() as $rst){
		$data = array(
		        'title'         => $rst["title"],
		        'entry_date'    => time() 
		);
		$this->EE->api_channel_entries->submit_new_entry(4,$data);	
		$qry = $this->EE->db->query("SELECT entry_id FROM exp_channel_titles ORDER BY entry_id DESC LIMIT 1");
		$result = $qry->result_array();
	
		// 
		$sql[] = "	INSERT INTO 
						exp_br_product_entry 
							(product_id, entry_id) 
								VALUES 
							(".$rst["product_id"].",".$result[0]["entry_id"].")";
	}