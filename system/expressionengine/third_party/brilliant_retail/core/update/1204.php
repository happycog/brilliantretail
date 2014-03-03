<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2014						*/
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

// Add US Armed Forces 'States' to state selector
	$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (1,'AE-A','Armed Forces Africa');"; 
	$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (1,'AA','Armed Forces Americas');";  
	$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (1,'AE-C','Armed Forces Canada');"; 	
	$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (1,'AE-E','Armed Forces Europe');"; 	
	$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (1,'AE-ME','Armed Forces Middle East');"; 
	$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (1,'AP','Armed Forces Pacific');"; 