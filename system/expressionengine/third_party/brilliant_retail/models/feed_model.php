<?php
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

class Feed_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->helper('brilliant_retail');
	}
	/**
	 * Get Feeds
	 *
	 * @param string $feed_id 
	 * @return mixed feed object/array
	 */
	function get_feeds( $feed_id = '' )
	{			
		$query = $this->db->query("
		  SELECT f.*, 
		    (SELECT COUNT(*) FROM exp_br_product_feeds AS pf WHERE pf.feed_id = f.feed_id) AS feed_product_count 
		  FROM exp_br_feeds AS f" . ($feed_id != '' ? " WHERE f.feed_id = '" . $feed_id . "'" : "")
		);
		
		return $feed_id != '' ? $query->row_array() : $query->result_array();
	}
	
	/**
	 * Get Feed by Code
	 *
	 * @param string $code 
	 * @return array feed data
	 */
	function get_feed_by_code( $code = '' )
	{
	  $query  = $this->db->select('feed_id')->get_where('br_feeds','feed_code = "' . $code . '"');
	  return $query->row_array();
	}
	
	/**
	 * Update Feed
	 *
	 * @param array feed data
	 * @return int feed id
	 */
	function update_feed ( $feed_data )
	{	  
	  if ( isset($feed_data['feed_id']) && $feed_data['feed_id'] != '')
	  {
	    $this->db->where('feed_id', $feed_data['feed_id'])->update('br_feeds',$feed_data);
	    $feed_id = $feed_data['feed_id'];
	  }
	  else
	  {
	    $this->db->insert('br_feeds', $feed_data);
			$feed_id = $this->db->insert_id();
	  }
	  
	  return $feed_id;
	}
	
	/**
	 * Delete Feed
	 *
	 * @param int feed id
	 * @return null
	 */
	function delete_feed ( $feed_id )
	{	 
	  if ( $feed_id != '' )
	  {
	    $this->db->delete('br_feeds', array('feed_id' => $feed_id)); 
	    $this->db->delete('br_product_feeds', array('feed_id' => $feed_id)); 
	  }
	}
}	
