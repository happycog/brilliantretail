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
	$this->EE->db->query("DROP TABLE IF EXISTS exp_br_product_entry;");
	$this->EE->db->query("CREATE TABLE exp_br_product_entry (
					product_entry_id int(11) NOT NULL AUTO_INCREMENT,
					product_id int(11) NOT NULL,
					entry_id int(11) NOT NULL,
					PRIMARY KEY (product_entry_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

## We need to load up some 
	$this->EE->load->library('api'); 
	$this->EE->api->instantiate('channel_structure');
	$this->EE->api->instantiate('channel_entries');
	$this->EE->api->instantiate('channel_fields');
	
## Create BrilliantRetail fieldgroup and channel per site

	// First thing we need to do is modify the store 
	// table to make sure that we track the new channel_id 
	// per site/store
	
	$this->EE->db->query("ALTER TABLE exp_br_store ADD COLUMN channel_id int(11) AFTER site_id");
		
	$qry = $this->EE->db->get('sites');
	foreach($qry->result() as $rst){
		
		// Create a field group for the site
			$data = array(	
							"site_id" 		=> $rst->site_id,
							"group_name" 	=> "[BrilliantRetail]"
						);
			$this->EE->db->insert("field_groups",$data);
			$field_group = $this->EE->db->insert_id();
		
		// Create a channel for the site
			$channel = array(
								"site_id"		=> $rst->site_id,
								"field_group"	=> $field_group, 
								"channel_title" => "[BrilliantRetail]",
								"channel_name"	=> "brilliantretail_".$rst->site_id,
							);
			
			$channel_id = $this->EE->api_channel_structure->create_channel($channel);
			
			$this->EE->session->userdata['assigned_channels'][$channel_id] = $channel['channel_title'];
			$site[$rst->site_id] = $channel_id;
	}

## Add the channel_id to each store
	foreach($site as $key => $val){
		$data = array(
               			'channel_id' => $val
               		);

		$this->EE->db->where('site_id', $key);
		$this->EE->db->update('br_store', $data);
	}
	
## Create an entry for every product

	$this->EE->db->from('br_product');
	$qry = $this->EE->db->get();
	foreach($qry->result_array() as $rst){
		$data = array(
		        'title'         => $rst["title"],
		        'entry_date'    => time() 
		);
		$this->EE->api_channel_entries->submit_new_entry($site[$rst["site_id"]],$data);	
		$qry = $this->EE->db->query("SELECT entry_id FROM exp_channel_titles ORDER BY entry_id DESC LIMIT 1");
		$result = $qry->result_array();
	
		// 
		$this->EE->db->query("	INSERT INTO 
									exp_br_product_entry 
								(product_id, entry_id) 
									VALUES 
								(".$rst["product_id"].",".$result[0]["entry_id"].")");
	}