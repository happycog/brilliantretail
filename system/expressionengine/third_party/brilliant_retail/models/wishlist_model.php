<?php
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

class Wishlist_model extends CI_Model {
	
	public function wishlist_get($member_id,$public=FALSE){
		$this->db->from('br_wishlist')
				->where('member_id',$member_id);
		
		if($public == TRUE){
			$this->db->where('is_public',1);
		}
		
		$query = $this->db->get();	
		return $query->result_array();
	}
	public function wishlist_add($product_id,$member_id,$is_public=0){
		
		$data = array(
						'product_id' 	=> $product_id,
						'member_id' 	=> $member_id, 
						'is_public'		=> $is_public 
					);
		
		$this->db->from('br_wishlist')
					->where('product_id',$product_id)
					->where('member_id',$member_id); 
		$qry = $this->db->get();
		if($qry->num_rows() != 0){
			return false;
		}
		$this->db->insert('br_wishlist',$data);
		return true;
	}

	public function wishlist_remove($product_id,$member_id){
		$data = array(
						'product_id' => $product_id,
						'member_id' => $member_id 
						);
		$this->db->delete('br_wishlist',$data);
		return true;
	}
	public function wishlist_update($product_id,$member_id,$data){
		$this->db->update('br_wishlist',$data,array('member_id'=>$member_id,'product_id'=>$product_id));
		return true;
	}
	
	public function wishlist_get_hash($member_id){
		$this->db->where('member_id',$member_id)->from('br_wishlist_hash');
		$query = $this->db->get();
		$cnt = $query->num_rows();
		if($cnt == 0){
			// need to create a hash for the user
			$hash = md5(time().rand(100,1000).time()); 
			$arr = array(
							'member_id' => $member_id,
							'hash' 		=> $hash
						);
			$this->db->insert('br_wishlist_hash',$arr);	
		}else{
			// return the users hash
			$rst = $query->result_array();
			$hash = $rst[0]["hash"];
		}
		return $hash;
	}
	
	public function wishlist_get_member($hash){
		$this->db->where('hash',$hash)->from('br_wishlist_hash');
		$query = $this->db->get();
		$cnt = $query->num_rows();
		if($cnt == 0){
			return 0;
		}else{
			// return the users hash
			$rst = $query->result_array();
			$member_id = $rst[0]["member_id"];
		}
		return $member_id;
	}
}