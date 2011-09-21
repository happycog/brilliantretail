<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010, Brilliant2.com 			*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.1							*/
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

class Report_best_products extends Brilliant_retail_report {
	public $title 		= 'Products Best Sellers';
	public $descr 		= 'Lists the best selling products and earnings to-date by each product';
	public $category	= 'products'; #(Options: sales, customers, products, general)
	public $version 	= 1.0;
	public $date_range 	= '';

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

		
		$this->EE->load->model('order_model');
		$products =  $this->EE->order_model->get_best_products($range["start"],$range["end"]);

		$header = array(lang('br_order_id'),lang('br_product_title'),lang('br_qty_sold'),lang('br_total_value'));
		
		// Results array 	
			$result = array();
			$ttl = 0;
			$qty = 0;
			
			if(count($products) == 0){
				$result = array();
				$graph = '';
			}else{
				$str = '_get_graph_'.$range_type;
				$graph = "";
			}
			
			$base_url = str_replace('&amp;','&',BASE).'&C=addons_modules&M=show_module_cp&module=brilliant_retail';
		
			foreach($products as $row){
					$result[] = array(
										'<a href="'.$base_url.'&method=product_edit&product_id='.$row['product_id'].'">'.$row['product_id'].'</a>',
										$row['title'],
										$row['qty'],										
										$this->_currency_round($row['total_sales'])										
									);
					$ttl += $row['total_sales'];
					$qty += $row['qty'];
			}

		$footer = array(
							'',
							strtoupper(lang('br_total')),
							$qty,
							$this->_currency_round($ttl)
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