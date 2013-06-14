<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2013						*/
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

## ----------------------------
##  Table structure for exp_br_password_reset
## ----------------------------
## ----------------------------
##  Table structure for exp_br_password_reset
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS `exp_br_password_reset";
	$sql[] = "	CREATE TABLE `exp_br_password_reset` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`member_id` int(11) NOT NULL,
					`token` varchar(255) DEFAULT NULL,
					`created` int(11) DEFAULT NULL,
					`ip` varchar(255) DEFAULT NULL,
					`secure` varchar(255) DEFAULT NULL,
					`length` int(11) DEFAULT NULL,
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";