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

// Add all of the product prices and 
// sale prices to the new table

		$query = $this->EE->db->get('br_product');
		foreach ($query->result() as $row)
		{
			// all price rows
			    $sql[] 	= "	INSERT INTO exp_br_product_price 
				    			(product_id,type_id,group_id,price) 
				    		VALUES 
					    		(
						    		".$row->product_id.",
						    		1,
						    		0,
						    		".$row->price."
					    		)";	

			// Add sale price rows		
				if($row->sale_price != NULL){
					$sql[] 	= "	INSERT INTO exp_br_product_price 
							    			(product_id,type_id,group_id,price,start_dt,end_dt) 
							    		VALUES 
								    		(
									    		".$row->product_id.",
									    		2,
									    		0,
									    		".$row->sale_price.",
									    		'".$row->sale_start."', 
									    		'".$row->sale_end."'
								    		)";	
			    }
		}