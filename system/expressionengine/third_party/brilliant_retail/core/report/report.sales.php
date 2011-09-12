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

class Report_sales extends Brilliant_retail_report {
	public $title 	= 'General Sales';
	public $descr 	= 'General sales report for orders over a given time period';
	public $category	= 'sales'; #(Options: sales, customers, products, general)
	public $version 	= .5;
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
		
		$this->EE->load->model('order_model');
		$orders =  $this->EE->order_model->get_order_collection($range["start"],$range["end"]);

		// Header row 
			
			$header = array(lang('br_order_id'),
							lang('date'),
							lang('br_base'),
							lang('br_tax'),
							lang('br_shipping'),
							lang('br_discount'),
							lang('br_total'));
	
		// Results array 	
			$base = 0;
			$tax = 0;
			$total = 0;
			$shipping = 0;
			$discount = 0;
			$result = array();
			
			if(count($orders) == 0){
				$result = array();
				$graph = '';
			}else{
				$str = '_get_graph_'.$range_type;
				$graph = $this->$str($range_type,$orders["results"]);
			}
		
			foreach($orders["results"] as $row){
				// only add orders if they are not canceled
				if($row["status_id"] >= 1){
					$result[] = array(
										$row["order_id"],
										date("m/d/Y",$row["created"]),
										$this->_currency_round($row["base"]),
										$this->_currency_round($row["tax"]),
										$this->_currency_round($row["shipping"]),
										$this->_currency_round($row["discount"]),  
										$this->_currency_round($row["total"])
									);
					$base 	+= $row["base"];
					$tax 	+= $row["tax"];
					$shipping += $row["shipping"];
					$discount += $row["discount"];
					$total 	+= $row["total"];
				}
			}

		$footer = array(	'',
							'',
							$this->_currency_round($base),
							$this->_currency_round($tax),
							$this->_currency_round($shipping),
							$this->_currency_round($discount), 
							$this->_currency_round($total) 
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
	
	function _get_graph_today($type,$orders){
		for($i=0;$i<=23;$i++){
			$hours[$i] = 0;
		}
		foreach($orders as $row){
			// Set a max hour
				$max_hour = date("G",$row["created"]);

			// Calculate the running total			
				$hours[date("G",$row["created"])] += $row["total"];
		}

		// Remove empty right times
			$trim = true;
			for($i=23;$i>=0;$i--){
				if($trim == true){
					if($i > $max_hour){
						unset($hours[$i]);
					}else{
						$trim = false;
					}
				}
			}
			
		$max = 0;
		foreach($hours as $key => $val){
			$axis_x[] = date("ga",strtotime("5-9-1977 ".$key.":00:00"));
			$vals[] = $val;
			if($val > $max){
				$max = $val;
			}
		} 
		$axis_y = $this->_get_y($max);
		return 'http://chart.apis.google.com/chart?chxt=x,y&chbh=a&chs=700x300&chf=bg,lg,90,b4dcec,0,e6f3f8,0.5&chma=40,30,20,30&cht=bvg&chxl=0:|'.join('|',$axis_x).'|1:|'.join('|',$axis_y).'&chco=116d88|5db9d4&chd=t:'.join(',',$vals);
	}
	
	function _get_graph_week($type,$orders){
		$range = get_range('week');
		for($i=0;$i<=6;$i++){
			$days[date('D',strtotime($range["start"].' +'.$i.' days'))] = 0;
		}
		foreach($orders as $row){
			$days[date("D",$row["created"])] += $row["total"];
		}
		$max = 0;
		foreach($days as $key => $val){
			$axis_x[] = $key;
			$vals[] = $val;
			if($val > $max){
				$max = $val;
			}
		} 
		$ratio = ($max == 0) ? 0 : 100/$max;
		foreach($vals as $v){
			$values[] = $v * $ratio;
		}
		$axis_y = $this->_get_y($max);
		return 'http://chart.apis.google.com/chart?chxt=x,y&chbh=a&chs=700x300&chg=14.3,-1,2,2&chf=bg,lg,90,b4dcec,0,e6f3f8,0.5&chma=40,30,20,30&cht=bvg&chxl=0:|'.join('|',$axis_x).'|1:|'.join('|',$axis_y).'&chco=116d88|5db9d4&chd=t:'.join(',',$values);
	}
	function _get_graph_month($type,$orders){
		$range = get_range('month');
		for($i=1;$i<=date('j',strtotime($range["end"]));$i++){
			$days[$i] = 0;
		}
		foreach($orders as $row){
			$days[date('j',$row["created"])] += $row["total"];
		}
		$max = 0;
		foreach($days as $key => $val){
			$axis_x[] = $key;
			$vals[] = $val;
			if($val > $max){
				$max = $val;
			}
		} 
		$ratio = ($max == 0) ? 0 : 100/$max;
		foreach($vals as $v){
			$values[] = $v * $ratio;
		}
		$axis_y = $this->_get_y($max);
		return 'http://chart.apis.google.com/chart?chxt=x,y&chbh=a&chg=14.3,-1,2,2&chs=700x300&chf=bg,lg,90,b4dcec,0,e6f3f8,0.5&chma=40,30,20,30&cht=bvg&chxl=0:|'.join('|',$axis_x).'|1:|'.join('|',$axis_y).'&chco=116d88|5db9d4&chd=t:'.join(',',$values);
	}
	
	function _get_graph_quarter($type,$orders){
		$range = get_range('quarter');
		for($i=date("n",strtotime($range["start"]));$i<=date("n",strtotime($range["end"]));$i++){
			$months[$i] = 0;
		}
		foreach($orders as $row){
			if(isset($months[date("n",$row["created"])])){
				$months[date("n",$row["created"])] += $row["total"];
			}
		}
		$max = 0;
		foreach($months as $key => $val){
			$axis_x[] = date('F',strtotime('2010-'.$key.'-01'));
			$vals[] = $val;
			if($val > $max){
				$max = $val;
			}
		} 
		$ratio = ($max == 0) ? 0 : 100/$max;
		foreach($vals as $v){
			$values[] = $v * $ratio;
		}
		$axis_y = $this->_get_y($max);
		return 'http://chart.apis.google.com/chart?cht=p3&chs=700x300&chf=bg,lg,90,b4dcec,0,e6f3f8,0.5&chma=40,125,20,30&chdl='.join('|',$axis_x).'&chco=116d88|5db9d4|baecfb&chd=t:'.join(',',$values);
	}
	
	function _get_graph_year($type,$orders){
		for($i=1;$i<=date('n');$i++){
			$months[date("M",strtotime('2010-12-15 +'.$i.' Months'))] = '';
		}
		
		foreach($orders as $row){
			if(isset($months[date("M",$row["created"])]))
			{
			$months[date("M",$row["created"])] += $row["total"];
			}
		}
		$max = 0;
		foreach($months as $key => $val){
			$axis_x[] = $key;
			$vals[] = $val;
			if($val > $max){
				$max = $val;
			}
		} 
		$ratio = ($max == 0) ? 0 : 100/$max;
		foreach($vals as $v){
			$values[] = $v * $ratio;
		}
		$axis_y = $this->_get_y($max);
		return 'http://chart.apis.google.com/chart?chxt=x,y&chbh=a&chg=14.3,-1,2,2&chs=700x300&chf=bg,lg,90,b4dcec,0,e6f3f8,0.5&chma=40,30,20,30&cht=bvg&chxl=0:|'.join('|',$axis_x).'|1:|'.join('|',$axis_y).'&chco=116d88|5db9d4&chd=t:'.join(',',$values);
	}
	function _get_graph_l_week($type,$orders){
		return;
	}
	function _get_graph_l_month($type,$orders){
		return;
	}
	function _get_graph_l_year($type,$orders){
		return;
	}
	function _get_graph_all($type,$orders){
		return;
	}
	function _get_graph_custom($type,$orders){
		return;
	}
	
	function _get_y($max){
		$axis_y[0] = 0;
		$axis_y[4] = round($max);
		
		$axis_y[2] = round($max / 2);
		$axis_y[1] = round($axis_y[2] / 2);
		$tmp = round(($axis_y[4] - $axis_y[2]) / 2);
		$axis_y[3] = $axis_y[2] + $tmp;
		ksort($axis_y);
		return $axis_y;
	}
		
	
}