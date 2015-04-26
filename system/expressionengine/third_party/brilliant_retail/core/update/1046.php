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

## ----------------------------
##  New BR Ext Stuff
## ----------------------------
	$sql[] = "	INSERT INTO exp_extensions  
					(class,method,hook,settings,priority,version,enabled) 
				VALUES
					('Brilliant_retail_ext', 'br_edit_entries_additional_where', 'edit_entries_additional_where', '', '10', '1.0.4.6', 'y')";
	$sql[] = "	INSERT INTO exp_extensions  
					(class,method,hook,settings,priority,version,enabled) 
				VALUES
					('Brilliant_retail_ext', 'br_cp_menu_array', 'cp_menu_array', '', '10', '1.0.4.6', 'y')";
