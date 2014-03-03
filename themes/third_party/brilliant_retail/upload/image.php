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
/* 	@copyright	Copyright (c) 2010-2014						*/
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

if(!file_exists($media_dir.'products')){
	mkdir($media_dir.'products');
}
if(!file_exists($media_dir.'products/thumb')){
	mkdir($media_dir.'products/thumb');
}

$parts = pathinfo($_FILES["Filedata"]["name"]);

// Check the image name. 
	$base = product_img_exits($parts['filename'],$media_dir);
	$nm = $base.'.png';

// create a thumbnail
	$type = 1;
	if(strtolower($parts["extension"]) == 'png'){
		$img = imagecreatefrompng($_FILES["Filedata"]["tmp_name"]);
	}elseif(strtolower($parts["extension"]) == 'gif'){
		$img = imagecreatefromgif($_FILES["Filedata"]["tmp_name"]);
	}else{
		$type = 0;
		$img = imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
	}
	if (!$img) {
		echo "ERROR:could not create image handle ". $_FILES["Filedata"]["tmp_name"];
		exit(0);
	}
	
	$width = 100;
	$height = 100;
	$curr_width = imageSX($img);
	$curr_height = imageSY($img);
	$w_ratio = $curr_width / $width; 
	$h_ratio = $curr_height / $height; 

	// Create our thumbnail 
		$new_image = imagecreatetruecolor($width,$height);
    	$bg = imagecolorallocate($new_image, 255, 255, 255);
		imagefill($new_image, 0, 0, $bg);

	// Height / Width Ratios	
		if($w_ratio >= $h_ratio){
			$new_width = $width; 
			$new_height = round(($width*$curr_height)/$curr_width);	
			$dst_x = 0;
			$dst_y = floor(($height - $new_height) / 2); # update 
		}else{
			$new_width = round(($height*$curr_width)/$curr_height);
			$new_height = $height;
			$dst_x = floor(($width - $new_width) / 2); # update 
			$dst_y = 0;
		}
		if(!imagecopyresampled($new_image, $img, $dst_x, $dst_y, 0, 0, $new_width, $new_height, $curr_width, $curr_height)){
			echo 'ouch';
		}
		
	// Where does it go?
		$thumb = $media_dir.'products/thumb/'.$nm;
		imagepng($new_image,$thumb);
		imagedestroy($new_image);
		
	move_uploaded_file($_FILES["Filedata"]["tmp_name"],$media_dir.'products/'.$nm);
		
// Return the file info
	echo $base.'|'.$nm;
	exit();


	/************************/
	/* Image Management	 	*/
	/************************/
	
	// Check to see if the image already exists
		function product_img_exits($nm,$media,$cnt = 0){
			# Remove spaces
				$nm = str_replace(' ','_',$nm);
			# Remove craziness 
				$nm = strtolower(preg_replace('/[^A-Za-z0-9-_]/','',$nm));
			
			if($cnt == 0){
				$fl = $media.'products/'.$nm.'.png';
			}else{
				$fl = $media.'products/'.$nm.'-'.$cnt.'.png';
			}
			if(file_exists($fl)){
				$cnt++;
				return product_img_exits($nm,$media,$cnt);
			}else{
				if($cnt == 0){
					return $nm;
				}else{
					return $nm.'-'.$cnt;
				}
			}
		}