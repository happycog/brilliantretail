<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010-2011, Brilliant2.com 	*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.4								*/
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

class Gateway_ideal extends Brilliant_retail_gateway {

	// Required variables
		public $title 	= 'iDeal';
		public $label 	= 'iDeal Checkout';
		public $descr 	= 'Accept payments with ';
		public $enabled = true;
		public $version = '1.0';
		
	// 
		function process($data,$config){
		}
		
		function form(){
		}
	
	// Install the gateway
		function install($config_id){
			$sql[] = "DROP TABLE IF EXISTS exp_br_ideal";
			$sql[] = "CREATE TABLE exp_br (`id` int(11) unsigned NOT NULL auto_increment, `order_id` varchar(100) default NULL, `order_code` varchar(100) default NULL, `transaction_id` varchar(100) default NULL, `transaction_code` varchar(100) default NULL, `transaction_method` varchar(100) default NULL, `transaction_date` int(11) unsigned default NULL, `transaction_amount` decimal(10,2) unsigned default NULL, `transaction_description` varchar(100) default NULL, `transaction_status` varchar(16) default NULL, `transaction_url` varchar(255) default NULL, `transaction_payment_url` varchar(255) default NULL, `transaction_success_url` varchar(255) default NULL, `transaction_pending_url` varchar(255) default NULL, `transaction_failure_url` varchar(255) default NULL, `transaction_params` text, `transaction_log` text, PRIMARY KEY (`id`));";
			foreach ($sql as $query)
			{
				$this->EE->db->query($query);
			}
		}
	
	// 
		function remove($config_id){
			$sql[] = "DROP TABLE IF EXISTS exp_br_ideal";
			foreach ($sql as $query)
			{
				$this->EE->db->query($query);
			}
			return true;		
		}
}