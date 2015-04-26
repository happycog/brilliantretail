<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

    // Clean up all action_ids
            $sql[] = "DELETE FROM exp_actions WHERE class IN ('Brilliant_retail_mcp','Brilliant_retail');";

    // Reinsert them all    
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_img_update')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_add_atributes')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_search')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_download_update')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_configurable_create_options')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_img_update')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'download_upload')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 's3_get_files')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'cart_add')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'cart_remove')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'cart_clear')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'cart_update')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'checkout')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'checkout_shipping')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'checkout_total')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'promo_check_code')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_register')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_profile_update')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_pw_update')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_download_file')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'gateway_ipn')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'process_ipn')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'pull_feed')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'retrieve_password')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'wishlist_process')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_download_note')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'reset_password')";