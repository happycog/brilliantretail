<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2013						*/
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

	$qry = $this->EE->db->query("CREATE TABLE exp_br_attribute_option (
									attr_option_id int(11) NOT NULL AUTO_INCREMENT,
									attribute_id int(11) DEFAULT NULL,
									label varchar(100) DEFAULT NULL,
									sort int(11) DEFAULT NULL,
									created datetime DEFAULT NULL,
									PRIMARY KEY (attr_option_id),
									KEY br_attribute_id (attribute_id)
								) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
				
	$start 		= 0;
	$processed 	= 0;
	$count = $this->EE->db->count_all('exp_br_attribute');
	
	// Work in batches of 100
	while ($processed < $count)
	{
		// Get a collection of the configurable products;
			$processed += 100;
			$stmt = "SELECT * FROM exp_br_attribute WHERE fieldtype IN ('dropdown','multiselect') LIMIT ".$start.",100";
			// Set the start point for batches
				$start += 100;
			
			$qry = $this->EE->db->query($stmt);
			foreach($qry->result_array() as $rst)
			{
				$a = explode("\n",$rst["options"]);
				$sort_count = 0;
				foreach($a as $b)
				{
					if(trim($b) != ''){
						$row = array(
											'attribute_id' 	=> $rst["attribute_id"],
											'label' 		=> trim($b),
											'sort' 			=> $sort_count++, 
											'created' 		=> date('Y-n-d h:i:s') 
										);
						$this->EE->db->insert('br_attribute_option',$row);
					}
				}
			}
	}				