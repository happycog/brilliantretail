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

if(!isset($_SESSION["cart"])){
	$_SESSION["cart"] = array();
}

	/************************/
	/* Cache 	 			*/
	/************************/

		function save_to_cache($type,$str){
			$path = APPPATH.'cache/brilliant_retail/'.$_SERVER["HTTP_HOST"];
			if(!file_exists($path)){
				mkdir($path);
			}
			$nm = $path.'/'.md5($type);
			$file = fopen($nm, 'w');
			fwrite($file,base64_encode($str));
			fclose($file);
		}
		
		function read_from_cache($type){
			$nm = APPPATH.'cache/brilliant_retail/'.$_SERVER["HTTP_HOST"].'/'.md5($type);
			if(!file_exists($nm)){
				return false;
			}else{
				$file = fopen($nm,'r');
				$str = fread($file,filesize($nm));
				$str = base64_decode($str);
				fclose($file);
				return $str;
			}
		}
		
		function remove_from_cache($type){
			$nm = APPPATH.'cache/brilliant_retail/'.$_SERVER["HTTP_HOST"].'/'.md5($type);
			if(file_exists($nm)){
				unlink($nm);
			}
		}
		
		function delete_file_cache()
		{
			$path = APPPATH.'cache/brilliant_retail/'.$_SERVER["HTTP_HOST"].'/';
			$file = read_dir_files($path);
			foreach($file as $f)
			{
				if(!is_dir($path.$f)){
					unlink($path.$f);
				}
			}
		}

	/************************/
	/* Session Helpers		*/
	/************************/

		function br_set($key,$val){
			$_SESSION[$key] = $val;
			return;
		}
		function br_unset($key){
			// have to be able to 
			// bounce a part of a 
			// multipledimensional 
			// session array 
				if(is_array($key)){
					$str = '$_SESSION';
					foreach($key as $v){
						$str .= '["'.$v.'"]';
					}
					$str = 'unset('.$str.');';
					eval($str);
				}else{
					unset($_SESSION[$key]);
				}
			return;
		}
		
		function br_get($key){
			if(isset($_SESSION[$key])){
				return $_SESSION[$key];
			}else{
				return false;
			}
		}

	/************************/
	/* XML 2 Array 			*/
	/************************/

		function xml2array($xml) {
			$arXML=array();
			$arXML['name']=trim($xml->getName());
			$arXML['value']=trim((string)$xml);
			$t=array();
			foreach($xml->attributes() as $name => $value) $t[$name]=trim($value);
			$arXML['attr']=$t;
			$t=array();
			foreach($xml->children() as $name => $xmlchild) $t[$name]=xml2array($xmlchild);
			$arXML['children']=$t;
			return($arXML);
		}

	/************************/
	/* File / Directory 	*/
	/************************/
	
		function read_dir_files($directory){
			$arr = array();
			if(file_exists($directory)){
				$dir = opendir($directory);
				while(false != ($file = readdir($dir))){
					if(($file != ".") and ($file != "..")){
						$arr[] = $file;
					}
				}
			}
			return $arr;
		}
		
		function read_system_files($type){
			// List Core Files
				
				$dir = PATH_THIRD.'brilliant_retail/core/'.$type;
				$files = read_dir_files($dir); 
		
			// List Local Files
				$local_dir = PATH_THIRD.'_local/brilliant_retail/'.$type;
				$local = read_dir_files($local_dir);
				
			// Merge
				foreach($files as $f){
					if(substr($f,0,strlen($type)+1) == $type.'.'){
						// Whats the module name based on the 
						// file naming convention
						$rem= array($type.'.','.php');
						$nm = strtolower(str_replace($rem,'',$f));
						$list[$f]['path'] = $dir.'/'.$f;
						$list[$f]['code'] = $nm;
						$list[$f]['type'] = 'core';
					}
				}
				foreach($local as $loc){
					if(substr($loc,0,strlen($type)+1) == $type.'.'){
						if(isset($files[$loc])){
							unset($files[$loc]);
						}
						$rem= array($type.'.','.php');
						$nm = strtolower(str_replace($rem,'',$loc));
						$list[$loc]['path'] = $local_dir.'/'.$loc;
						$list[$loc]['code'] = $nm;
						$list[$loc]['type'] = 'local';
					}
				}		
				sort($list);
				return $list;
		}
		
	/************************/
	/* Time Range 			*/
	/************************/
	
	function get_range($type = ''){
		if($type == ''){
			$type = 'week';
		}
		$arr = array();
		$day[0] = -6; 	# Sunday 
		$day[1] = 0; 	# Monday 
		$day[2] = -1; 	# Tuesday
		$day[3] = -2; 	# Wednesday
		$day[4] = -3; 	# Thursday 
		$day[5] = -4; 	# Friday 
		$day[6] = -5; 	# Saturday 

		if($type == 'today'){
			$arr["start"] 	= date("Y-m-d 00:00:00");
			$arr["end"] 	= date("Y-m-d 23:59:59");
		}
		if($type == 'week'){
			$d = date("w");
			$offset = $day[$d];
			if($offset != 0){
				$arr["start"] 	= date("Y-m-d",strtotime($day[$d]." days"));
			}else{
				$arr["start"] 	= date("Y-m-d");
			}
			$arr["end"] 	= date("Y-m-d",strtotime($arr["start"]."+ 6 days")); 
		}elseif($type == 'month'){
			$arr["start"] 	= date("Y-m-01");
			$arr["end"] 	= date("Y-m-d 23:59:59");
		}elseif($type == 'quarter'){
			$offset[0] = 2;
			$offset[1] = 0;
			$offset[2] = 1;
			$s = date('n') % 3;
			$arr["start"] = date("Y-n-01",strtotime(' -'.$offset[$s].' months'));
			$arr["end"] 	= date("Y-m-d 23:59:59");
		}elseif($type == 'year'){
			$arr["start"] 	= date("Y")."-01-01";
			$arr["end"] 	= date("Y-m-d 23:59:59");
		}elseif($type == 'l_week'){
			$d = date("w");
			$offset = $day[$d]-7;
			$arr["start"] 	= date("Y-m-d",strtotime($offset." days"));
			$arr["end"] 	= date("Y-m-d",strtotime($arr["start"]."+ 6 days")); 
		}elseif($type == 'l_month'){
			$arr["start"] 	= date("Y-m-01",strtotime("-1 month"));
			$arr["end"] 	= date("Y-m-t",strtotime("-1 month"));
		}elseif($type == 'l_year'){
			$arr["start"] 	= date("Y-01-01",strtotime("-1 year"));
			$arr["end"] 	= date("Y-12-31 23:59:59",strtotime("-1 year"));
		}elseif($type == 'all'){
			$arr["start"] 	= date("2010-01-01");
			$arr["end"] 	= date("Y-m-d 23:59:59");
		}
		return $arr;	
	}
	
	/************************/
	/* Test for https 		*/
	/************************/
	
	function is_secure() {
  		return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == TRUE)) ? TRUE : FALSE;
	}
	
	/************************/
	/* Image Management	 	*/
	/************************/
	
	function cache_image($src,$cache_file,$ext,$width,$height,$media){
		if(file_exists($media.'cache/'.$cache_file)){
			return true;
		}else{
			$src = ltrim($src,'/');
			if(!file_exists($media.$src)){
				return false;
			}
			$size = getimagesize($media.$src); 
			$ext = $size['mime'];
			if($ext == 'image/png'){
				$img = imagecreatefrompng($media.$src);
				$image_type = 1;
			}elseif($ext == 'image/gif'){
				$img = imagecreatefromgif($media.$src);
				$image_type = 2;
			}else{
				$img = imagecreatefromjpeg($media.$src);
				$image_type = 3;
			}
			
			$data["size"] = getimagesize($media.$src);

			$data["ratio"] = $data["size"][0] / $data["size"][1];
			
			#floor($width / $data["ratio"]);
			
			// Create our new image. 
				$newImg = imagecreatetruecolor($width,$height);
			
			// If it has a transparent bg fill it
			if(($image_type == 1 || $image_type==2)){
		        imagealphablending($newImg, false);
		        imagesavealpha($newImg,true);
		        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		        imagefilledrectangle($newImg, 0, 0, $width, $height, $transparent);
		    }else{
		    	$bg = imagecolorallocate($newImg, 255, 255, 255);
				imagefill($newImg, 0, 0, $bg);
		    }
			
			// Calculate the resize side
			$w_ratio = $data["size"][0] / $width; 
			$h_ratio = $data["size"][1] / $height; 

			if($w_ratio >= $h_ratio){
				$new_width = $width; 
				$new_height = round(($width*$data["size"][1])/$data["size"][0]);	
				$dst_x = 0;
				$dst_y = floor(($height - $new_height) / 2); # update 
			}else{
				$new_width = round(($height*$data["size"][0])/$data["size"][1]);
				$new_height = $height;
				$dst_x = floor(($width - $new_width) / 2); # update 
				$dst_y = 0;
			}

			imagecopyresampled($newImg, $img, $dst_x, $dst_y, 0, 0, $new_width, $new_height, $data["size"][0], $data["size"][1]);
   
			$new_img = $media.'cache/'.$cache_file;

    		imagejpeg($newImg,$new_img,100);
    		
    		imagedestroy($newImg);
    		
		    return true;
		}
	}

	function convert2png($src,$dest){
		$info = pathinfo($src); 
		if($info["extension"] == 'png'){
			copy($src,$dest);
		}elseif($info["extension"] == 'gif'){
			imagepng(imagecreatefromgif($src),$dest);
		}else{
			imagepng(imagecreatefromjpeg($src),$dest);
		}
		return true;
	}

	/************************/
	/* UUID 				*/
	/************************/
		
	function uuid_validate($uuid){  
		return preg_match('#^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$#', $uuid);  
	}  
	
	function uuid() {
	    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		      // 32 bits for "time_low"
		      mt_rand(0, 0xffff), mt_rand(0, 0xffff),
		
		      // 16 bits for "time_mid"
		      mt_rand(0, 0xffff),
		
		      // 16 bits for "time_hi_and_version",
		      // four most significant bits holds version number 4
		      mt_rand(0, 0x0fff) | 0x4000,
		
		      // 16 bits, 8 bits for "clk_seq_hi_res",
		      // 8 bits for "clk_seq_low",
		      // two most significant bits holds zero and one for variant DCE1.1
		      mt_rand(0, 0x3fff) | 0x8000,
		
		      // 48 bits for "node"
		      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
	    );
	}