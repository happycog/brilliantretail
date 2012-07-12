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

	// GET A COLLECTION OF THE order download records that are 0 
	$stmt="SELECT * FROM exp_br_order_download WHERE member_id = 0";
	$qry = $this->EE->db->query($stmt);
	$order = array();
	foreach($qry->result_array() as $rst)
	{
		if(isset($order[$rst["order_id"]])){
			$member_id = $order[$rst["order_id"]];
		}else{
			$stmt = "SELECT member_id FROM exp_br_order WHERE order_id = ".$rst["order_id"];
			$query = $this->EE->db->query($stmt);
			$row = $query->result_array();
			// Shouldn't be any rows in here without order_ids but lets 
			// add this just so we dont crash and burn if there are. 
				if(isset($row[0]))
				{
					$member_id = $row[0]["member_id"];
				}else{
					$member_id = 0;
				}
		}
		// Build the update statement
			$stmt = "UPDATE exp_br_order_download set member_id = ".$member_id." WHERE order_download_id = ".$rst["order_download_id"];
			$this->EE->db->query($stmt);
		// Save a query if we are doing multiple 
		// downloads per order
			$order[$rst["order_id"]]=$member_id;
	}
	