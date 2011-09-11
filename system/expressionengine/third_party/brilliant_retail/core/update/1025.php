<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 20211, Brilliant2.com 		*/
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

$sql[] = "ALTER TABLE exp_br_store ADD COLUMN `thankyou_url` varchar(100) DEFAULT 'checkout/thankyou'"; 