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

	global $option;
	
	// First lets get all the attributes cause we are converting from text based to 
	// id based attribute storage! 
		$this->EE->db->from('exp_br_attribute_option');
		$qry = $this->EE->db->get();
		$option = array();
		foreach($qry->result_array() as $row)
		{
			$option[$row["attribute_id"]][$row["label"]] = $row["attr_option_id"];	
		}
		
	// Now lets convert the selections	
		$this->EE->db->from('exp_br_product_attributes');
		$qry = $this->EE->db->get();
		foreach($qry->result_array() as $row)
		{
			if(isset($option[$row["attribute_id"]]))
			{
				$insert=array();
				
				if(@$a = unserialize($row["descr"])){
					$j=0;
					foreach($a as $b)
					{
						if(trim($b) == ''){ continue; }
						$opt = _get_option_id($row["attribute_id"],$b);
						$insert[] = array(
										'pa_id' 		=> 	$row["pa_id"],
										'product_id'	=> 	$row["product_id"],
										'attribute_id'	=> 	$row["attribute_id"],
										'options'		=> 	$opt,
										'sort'			=>	$j 
										);
						$j++;
					}
				}else{
					$opt = _get_option_id($row["attribute_id"],$row["descr"]);
					$insert[] = array(
										'pa_id' 		=> 	$row["pa_id"],
										'product_id'	=> 	$row["product_id"],
										'attribute_id'	=> 	$row["attribute_id"],
										'options'		=> 	$opt,
										'sort'			=>	0 
										);
						
				}
				foreach($insert as $r){
					$this->EE->db->insert('exp_br_product_attributes_option',$r);
				}
			}
		}

		function _get_option_id($id,$label){

			global $option;
			
			$EE =& get_instance();
			
			$label = trim($label);
			if($label == '')
			{
				return FALSE;
			}
			if(!isset($option[$id][$label])){
				$new = array(
								"attribute_id"	=> $id,
								"label"			=> trim($label),
								"sort"			=> 0,
								"created"		=> date("Y-n-d h:i:s")
							);
				$EE->db->insert("exp_br_attribute_option",$new);
				$option[$id][$label] = $EE->db->insert_id();
			}
			
			return $option[$id][$label];
		}