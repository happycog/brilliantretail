<?php
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

class Report_customer_sales extends Brilliant_retail_report {

	public $title 		= 'Customer Sales';
	public $descr 		= 'List of orders by customer over a given time period';
	public $category	= 'sales'; #(Options: sales, customers, products, general)
	public $version 	= '1.0';
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
		
		// Get the orders
			$orders =  $this->EE->order_model->get_order_collection($range["start"],$range["end"]);
		
		// Header row 
			$header = array(lang('br_order_id'),lang('br_customer_email'),lang('br_date'),lang('br_base'),lang('br_tax'),lang('br_shipping'),lang('br_discount'),lang('br_total'));
	
		// Results array 	
			$base 		= 0;
			$tax 		= 0;
			$total 		= 0;
			$shipping 	= 0;
			$discount 	= 0;
			$result 	= array();
			
			if(count($orders["results"]) == 0){
				$result = array();
				$graph = '';
			}else{
				$str = '_get_graph_'.$range_type;
				$graph = "";
			}
		
			foreach($orders["results"] as $row){
				// only add orders if they are not canceled
				if($row["status_id"] >= 1){
					
					$created = $this->EE->localize->format_date('%m/%d/%Y', $row["created"]);
					
					$result[] = array(
										"<strong>".$row['order_id']."</strong>",
										"<strong>".$row["customer"]."</strong>",
										"<strong>".$created."</strong>",
										"<strong>".$this->_currency_round($row["base"])."</strong>",
										"<strong>".$this->_currency_round($row["tax"])."</strong>",
										"<strong>".$this->_currency_round($row["shipping"])."</strong>",
										"<strong>".$this->_currency_round($row["discount"])."</strong>",
										"<strong>".$this->_currency_round($row["total"])."</strong>" 
									);
					
					$orders = $this->EE->order_model->get_order($row["order_id"]);

					// Display the individual rows
					   	foreach($orders['items'] as $item)
					   		{
					   			$result[] = array(
					   				"",
					   				$item['quantity']." x ". $item['title'],
					   				"",
					   				$this->_currency_round($item['price']*$item['quantity']),
					   				"",
					   				"",
					   				$this->_currency_round($item['discount']),
					   				""				   		
					   			);
					   		}				
					
					// Create the running totals
						$base 		+= $row["base"];
						$tax 		+= $row["tax"];
						$shipping 	+= $row["shipping"];
						$total 		+= $row["total"];
						$discount 	+= $row["discount"];
				}
			}

		$footer = array(
							'',
							strtoupper(lang('br_order_totals')),
							'',
							$this->_currency_round($base),
							$this->_currency_round($tax),
							$this->_currency_round($shipping),
							$this->_currency_round($discount),
							$this->_currency_round($total) 
						);
						
		$report = array(
							'input' 	=> $input,
							'range' 	=> $range,   
							'graph' 	=> $graph,
							'header' 	=> $header,
							'results' 	=> $result,
							'footer' 	=> $footer
						);
		return $report;
	}
}
// End report