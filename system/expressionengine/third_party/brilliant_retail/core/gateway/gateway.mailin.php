<?php
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

class Gateway_mailin extends Brilliant_retail_gateway {
	public $title 	= 'Mail In';
	public $label 	= 'Mail in Payment';
	public $descr 	= 'Allow users to mail in payment after the purchase.';
	public $enabled = true;
	public $version = .5;
	
	function form(){
		return '';
	}
	
	function process($data){
		$id =  md5(time()); 
		$details = array(
							"Method" => "Mail In",
							"Transaction ID" => $id 
						);
		
		if($this->admin_order === FALSE){
			$amount = 0;
		}else{
			$amount = $data["order_total"];
		}
									
		$trans = array(
							'status' => 2, 
							'amount' => $amount,
							'transaction_id' =>$id, 
							'payment_card' => 'Mail In', 
							'payment_type' => 'Mail In', 
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
/* End of file gateway.mailin.php */