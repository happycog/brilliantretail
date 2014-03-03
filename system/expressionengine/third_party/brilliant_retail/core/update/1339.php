<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2014						*/
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

	// First lets get all the attributes cause we are converting from text based to 
	// id based attribute storage! 
		$this->EE->db->from('exp_br_attribute_option');
		$rst = $this->EE->db->get();
		$option = array();
		foreach($rst->result_array() as $row)
		{
			$option[$row["attribute_id"]][$row["label"]] = $row["attr_option_id"];	
		}
		
	$start 		= 0;
	$processed 	= 0;
	$count = $this->EE->db->count_all('exp_br_product_configurable');
	
	// Work in batches of 100
	while ($processed < $count)
	{
		// Get a collection of the configurable products;
			$processed += 100;
			$stmt = "SELECT * FROM exp_br_product_configurable LIMIT ".$start.",100";
			// Set the start point for batches
				$start += 100;
			
			$qry = $this->EE->db->query($stmt);
			foreach($qry->result_array() as $rst)
			{
				$data 	= array();
				$a 		= unserialize($rst["attributes"]);
				unset($rst["attributes"]);
				$row_count = 0;
				if($a){
					foreach($a as $key => $val){
					
						if(trim($val) == ''){
							continue;
						}
					
						// Get the option_id from our array above
							if(isset($option[$key][trim($val)])){
								$opt = $option[$key][trim($val)];
							}else{
								$new = array(
												"attribute_id"	=> $key,
												"label"			=> trim($val),
												"sort"			=> 0,
												"created"		=> date("Y-n-d h:i:s")
											);
								$this->EE->db->insert("exp_br_attribute_option",$new);
								$opt = $option[$row["attribute_id"]][$row["label"]] = $this->EE->db->insert_id();
							}
					
						$data[$row_count]["configurable_id"] 	= $rst["configurable_id"];
						$data[$row_count]["product_id"] 		= $rst["product_id"];
						$data[$row_count]["attribute_id"] 		= $key;
						$data[$row_count]["option_id"] 			= $opt;
						$data[$row_count]["sort"] 				= $row_count;
						$row_count++;
					} 
					$this->EE->db->insert_batch("exp_br_product_configurable_attribute",$data);
				}
			}
	}