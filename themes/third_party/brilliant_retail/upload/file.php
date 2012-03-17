<?php
if(!isset($_POST["PHPSESSID"])){ exit('access denied'); }
session_id($_POST["PHPSESSID"]);
session_start();
if(!isset($_SESSION["media_dir"])){
exit(0);
}
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2012						*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.2.3								*/
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
$media_dir = $_SESSION["media_dir"];

if(!file_exists($media_dir.'download')){
	mkdir($media_dir.'download');
}
$file = $_FILES["Filedata"]["name"];
$a = explode('.',$_FILES["Filedata"]["name"]); 
$ext = $a[count($a)-1];
$nm = md5($_FILES["Filedata"]["name"].time()).'.'.$ext;
move_uploaded_file($_FILES["Filedata"]["tmp_name"],$media_dir.'download/'.$nm);
echo $a[0].'|'.$file.'|'.$nm;
exit();