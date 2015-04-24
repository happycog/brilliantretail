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

class Brilliant_retail_shipping extends Brilliant_retail_core{
	
		public $rates = array(); 
	
		function __construct(){
			$this->EE =& get_instance();
		}	
		
		function _curl($url,$data = NULL) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0); // Suppress header for simplexml 
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			if($data) {
				curl_setopt($ch, CURLOPT_POST,1);  
				curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			}  
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$contents = curl_exec ($ch);
			curl_close ($ch);
			return $contents;
		}

		// Convert Weight
		function _convert_weight($weight,$old_unit,$new_unit) {
			$units['oz'] = 1;
			$units['lb'] = 0.0625;
			$units['gram'] = 28.3495231;
			$units['kg'] = 0.0283495231;
			
			// Convert to Ounces (if not already)
			if($old_unit != "oz") $weight = $weight / $units[$old_unit];
			
			// Convert to New Unit
			$weight = $weight * $units[$new_unit];
			
			// Minimum Weight
			if($weight < .1) $weight = .1;
			
			// Return New Weight
			return round($weight,2);
		}
		
		// Convert Size
		function _convert_size($size,$old_unit,$new_unit) {
			$units['in'] = 1;
			$units['cm'] = 2.54;
			$units['feet'] = 0.083333;
			
			// Convert to Inches (if not already)
			if($old_unit != "in") $size = $size / $units[$old_unit];
			
			// Convert to New Unit
			$size = $size * $units[$new_unit];
			
			// Minimum Size
			if($size < .1) $size = .1;
			
			// Return New Size
			return round($size,2);
		}
		
		// Sort by rate key. 
		function _rate_sort($a,$b){
			return ($a["rate"] > $b["rate"]) ? +1 : -1;
		}
		
		// Remove the shipping method
			function remove($config_id){
				return true;
			}

		// Update the shipping method		
			function update($current = '',$config_id = ''){
				return true;
			}
}