<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010, Brilliant2.com 			*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.0 Beta							*/
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

class Report_search_terms extends Brilliant_retail_report {
	public $title 	= 'Customer Searches';
	public $descr 	= 'Search Terms by customer and number of results returned';
	public $category	= 'general'; #(Options: sales, customers, products, general)
	public $version 	= 1.0;
	public $date_range = '';

	function get_report(){	

		// Inputs for our report are passed in the following format : Format (type, label, name)
		// Currently supported include: 
		// 		date - 	a standard date range element which displays the BR system report ranges and 
		//				including a 'custom' option for enterying a start and end date. Start and end 
		// 				dates are passed to the post array with the date field name as prepended with 
		//				_st for start and _end for end 
		// 		input - a standard input field. 
		//		category - a category tree field 
		// 		member_group - a member group drop down field
		
		$input[] = array('date','Select Date','date_range');

		// If there are values in the post array 
		// we can use them
		
		$range_type = ($this->date_range == '') ? $this->EE->input->post("date_range", TRUE) : $this->date_range;
		
		if($range_type == ''){
			$range_type = 'week';
		}
		if($range_type == 'custom'){
			$range["start"] = $this->EE->input->post("date_range_st", TRUE);
			$range["end"] 	= $this->EE->input->post("date_range_end", TRUE);
		}else{
			$range = get_range($range_type);		
		}
		
		$this->EE->load->model('search_model');
		$search =  $this->EE->search_model->get_search_results($range["start"],$range["end"]);
		
		// Header row 
			
		$header = array(lang('br_customer_email'),lang('br_search_phrase'),lang('br_products_found'),lang('br_search_created'));
	
		// Results array 	
			$base = 0;
			$tax = 0;
			$total = 0;
			$shipping = 0;
			$discount = 0;
			$result = array();
			
			if(count($search) == 0){
				$result = array();
				$graph = '';
			}else{
				$str = '_get_graph_'.$range_type;
				$graph = "";
			}
			
			foreach($search as $row){
				
				// Is it member or guest ? 
					if(isset($row["email"])){
						$member = '<a href="'.BASE.'&C=myaccount&id='.$row["member_id"].'">'.$row["email"]."</a>";
					}else{
						$member = ' - ';
					}
						
				// only add orders if they are not canceled
					$result[] = array(
						$member, 
						$row['search_term'],
						$row['result_count'],
						$row["created"]
					);			
			}

		$footer = array(
							'',
							'',
							'',
							''
						);
						
		$report = array(
							'input' => $input,
							'range' => $range,   
							'graph' => $graph,
							'header' => $header,
							'results' => $result,
							'footer' => $footer
						);
		return $report;
	}
}