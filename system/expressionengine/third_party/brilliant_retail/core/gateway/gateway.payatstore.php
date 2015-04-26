<?php
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

class Gateway_payatstore extends Brilliant_retail_gateway {
	public $title 	= 'Pay at Store';
	public $label 	= 'Make Payment at Store';
	public $descr 	= 'Allow users to make payment after the purchase when they collect.';
	public $enabled = true;
	public $version = .5;
	
	function form(){
		return '';
	}
	
	function process($data){
		$id =  md5(time()); 
		$details = array(
							"Method" => "Collect",
							"Transaction ID" => $id 
						);
										
		$trans = array(
							'status' => 2, 
							'amount' => 0,
							'transaction_id' =>$id, 
							'payment_card' => 'Collect', 
							'payment_type' => 'Collect', 
							'details' => serialize($details), 
							'approval' => '' 
						);
		return $trans;
	}
	
	function install(){
		return true;
	}
	function remove(){
		
	}
	function update($current = '',$config_id = ''){
		return true;
	}
}
/* End of file gateway.payatstore.php */