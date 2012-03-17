<?php
if(!session_id()){
$sid = '';
if(isset($_POST["PHPSESSID"])){ $sid = $_POST["PHPSESSID"]; }
session_start($sid);
}
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

class Product {
	
	public $products = array();

	#public function __construct()
	#{
	#}

	public function createshell()
	{
		$products[0]["product_id"] 			= 0;
		$products[0]["bundle"] 				= array();
		$products[0]["addon"] 				= array();
		$products[0]["related"] 			= array();
		$products[0]["type_id"] 			= 1;
		$products[0]["attribute_set_id"] 	= 0;
		$products[0]["title"] 				= '';
		$products[0]["url"] 				= '';
		$products[0]["sku"] 				= '';
		$products[0]["weight"] 				= '';
		$products[0]["enabled"] 			= 1;
		$products[0]["detail"] 				= '';
		$products[0]["meta_title"] 			= '';
		$products[0]["meta_keyword"] 		= '';
		$products[0]["meta_descr"] 			= '';
		$products[0]["price"] 				= '';
		$products[0]["cost"] 				= '';
		$products[0]["taxable"] 			= 0;
		$products[0]["sale_price"] 			= '';
		$products[0]["sale_start"] 			= '';
		$products[0]["sale_end"] 			= '';
		$products[0]["quantity"] 			= '';
		$products[0]["shippable"] 			= 1;
		$products[0]["manage_inventory"] 	= '';
		$products[0]["featured"] 			= 0;
		$products[0]["sale_matrix"] 		= array();
		
		// Lets go ahead and put in a default price row
			$products[0]["price_matrix"][]		= array(	
															'group_id' 	=> 0,
															'price' 	=> '',
															'qty' 		=> 1,
															'start_dt' 	=> '',
															'end_dt' 	=> ''
														);
														
		
		
		return $products;
	}	
}