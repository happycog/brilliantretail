<?php

	require("ImageTools.interface.php");
	
	class ImageTools implements ImageToolsInterface
	{
		public $watermark_font_path;
		
		private $im;
		
		private $image_type;
		
		private $tmp_im;
		
		private $image_output_type;
		
		
		
		/**
		 * Constructor
		 *
		 * @param $path_to_image - Image path
		 */
		 
		public function ImageTools($path_to_image)
		{
			if( file_exists($path_to_image) && is_file($path_to_image) )
			{
				// Reserve memory allocation size
				ini_set('memory_limit', ImageToolsInterface::ALLOCATE_MEMORY);
				
				// Check Type
				$split = explode(".", $path_to_image);
				$extension = strtoupper($split[ count($split)-1 ]);
				
				switch( $extension )
				{
					case "PNG":
						$this->image_type = $this->image_output_type = "PNG";
						$this->im = imagecreatefrompng($path_to_image);											
						imagealphablending($this->im, false);
						imagesavealpha($this->im, true);
						break;
						
					case "JPG":
					case "JPEG":
						$this->image_type = $this->image_output_type = "JPG";
						$this->im = imagecreatefromjpeg($path_to_image);
						break;
					
					case "GIF":
						$this->image_type = $this->image_output_type = "GIF";
						$this->im = imagecreatefromgif($path_to_image);											
						imagealphablending($this->im, false);
						imagesavealpha($this->im, true);
						break;
				}
				
				if( ! $this->image_type )
				{
					$this->parseError("Invalid image type ($extension) :: $path_to_image");
				}
			}
			else
			 $this->parseError("Invalid Image Path :: <b>$path_to_image</b>");
		}
		
		
		/**
		 * Get Image width
		 *
		 * @return image width
		 */
		
		public function getX()
		{
			$x = imagesx($this->im);
			
			return $x;
		}
		
		
		/**
		 * Get Image height
		 *
		 * @return image height
		 */
		
		public function getY()
		{
			$y = imagesy($this->im);
			
			return $y;
		}
		
		
		/**
		 * Image Reflection
		 *
		 * @param $percent - The drop reflection of image in percentage (from 0-100 percent)
		 * @param $bg_color - Hexadecimal color format for applying as bacground of transparent area below the image
		 * @param $spacing - Drop reflection after some pixels under the original image
		 */
		
		public function reflect($percent = 35, $bg_color = "#FFF", $spacing = -1)
		{			
			// Check Percentage
			if( is_numeric($percent) )
			{
				$percent = $percent > 100 ? 100 : ($percent < 0 ? 0 : $percent);
			}
			else
				$percent = 35;
			
			if( $percent == 0 )
				return;
			
			// Check Bg Color
			if( !$bg_color )
				$bg_color = "#FFF";
			
			$ref_h = ceil(($percent / 100) * $this->getY()); // Percent
			$total_height = $this->getY() + $ref_h;
			
			$this->tmp_im = imagecreatetruecolor($this->getX(), $total_height);
			
			imagefill($this->tmp_im, 0, 0, $this->getColor($bg_color));
			
			imagecopymerge($this->tmp_im, $this->im, 0, 0, 0, 0, $this->getX(), $this->getY(), 100);
			
			$from = $this->getY();
			$to = $total_height;
			
			$trans_unit = 100 / $ref_h;
			
			$current_trans = 100;
			
			for($i=$from, $j=0; $i<$to; $i++, $j++)
			{
				imagecopymerge($this->tmp_im, $this->im, 0, $i+$spacing, 0, $this->getY() - $j -1, $this->getX(), 1, floor($current_trans));
				
				$current_trans -= $trans_unit;
				
				if( $current_trans < 0 )
					continue;
			}
			
			$this->im = $this->tmp_im;
		}
		
		
		/**
		 * Set Image Output type
		 *
		 * @param $image_type - After performing actions on image this will needed if you want to specify which format must be outputted to the browsers
		 * @return void
		 */
		
		public function setOutputType($image_type)
		{
			switch( $image_type )
			{
				case self::IMAGE_TYPE_JPG:
				case self::IMAGE_TYPE_JPEG:
					$this->image_output_type = "JPG";
					break;
				
				case self::IMAGE_TYPE_PNG:
					$this->image_output_type = "PNG";
					break;
				
				case self::IMAGE_TYPE_GIF:
					$this->image_output_type = "GIF";
					break;
			}
		}
		
		
		/**
		 * Rotate Image
		 *
		 * @param $degree - Rotate image for given degree value
		 * @param $bg - After rotating image there will some extra space and imagesize will change. The $bg will fill that space with specified hexadecimal format color
		 * @return void
		 */
		
		public function rotateImage($degree, $bg = null)
		{
			$degree = $degree < 0 ? pow($degree, 2) : $degree;
			$degree %= 361;
			
			$bg = $this->getColor($bg ? $bg : "#FFF");
			
			$resampled = imagecreatetruecolor($this->getX(), $this->getY());
			
			$this->im = imagerotate($this->im, -$degree, $bg);
		}
		
		
		/**
		 * Rotate image to left for 90 degree
		 *
		 * @return void
		 */
		
		public function rotateLeft()
		{
			$this->rotateImage(90);
		}
		
		
		/**
		 * Rotate image to the right for 90 degree
		 *
		 * @return void
		 */
		
		public function rotateRight()
		{
			$this->rotateImage(270);
		}
		
		
		/**
		 * Show Current Image
		 *
		 * @return Image output
		 */
		
		public function showImage()
		{
			if( $this->im )
			{
				imageantialias($this->im, true);
				imagealphablending($this->im, true);
				
				switch( $this->image_output_type )
				{
					case "PNG":
						header("Content-Type: image/png");
						imagepng($this->im);
						break;
					
					case "JPG":
						header("Content-Type: image/jpeg");
						imagejpeg($this->im);
						break;
					
					case "GIF":
						header("Content-Type: image/gif");
						imagegif($this->im);
						break;
				}
			}
			else
				$this->parseError("No image to show!");
		}

		
		/**
		 * Set Background Color
		 *
		 * @param $hex - Fill transparent background with specified color
		 * @return void
		 */
		
		public function setTransparency($hex)
		{
			$rgb = null;
			
			if( is_array($hex) && count($hex) == 3 )
			{
				$rgb = $hex;
			}
			else
			{
				$rgb = $this->hexToRGB($hex);
			}
			
			$color = imagecolorallocate($this->im, $rgb[0], $rgb[1], $rgb[2]);
			imagecolortransparent($this->im, $color);
		}
		
		/**
		 * Set Transparent Background - Make background trasnparent where it is originally
		 * 
		 * @return void
		 */
		
		public function setTransparentBg()
		{
			$this->setTransparency("#000");
		}
		
		
		/**
		 * Destroy Img - Deallocate reserved bits on memory after finished working on image
		 *
		 * @return void
		 */
		
		public function destroy()
		{
			if( $this->im )
				imagedestroy($this->im);
				
			if( $this->tmp_im )
				imagedestroy($this->tmp_im);
		}
		
		
		/**
		 * Get Color imagecolorallocate
		 *
		 * @param $hex - Get specified color as function imagecolorallocate($im, $r, $g, $b);
		 * @return imagecolorallocate($im, $r, $g, $b)
		 */
		
		private function getColor($hex)
		{
			$rgb = $this->hexToRGB($hex);
			
			return imagecolorallocate($this->im, $rgb[0], $rgb[1], $rgb[2]);
		}
		
		
		/**
		 * Conversion of hexadecimal format of color
		 *
		 * @param $hex - Get hexadecimal format of color and convert it on RGB system by splitting it on three parts in array
		 * @return RGB array
		 */
		
		public function hexToRGB($hex)
		{
			$hex = strtoupper($hex);
			
			$color_format_pattern = "/^\#?([A-F0-9]{2,6})$/";
			
			if( preg_match($color_format_pattern, $hex, $arr) )
			{
				$rgb = $this->html2rgb($arr[1]);
				return $rgb;
			}
			else
				$this->parseError("Invalid Format of Hexadecimal Color");
		}
		
		
		/**
		 * Set Brightness
		 *
		 * @param $brightness - Brightness level
		 * @return void
		 */
		
		public function setBrightness($brightness = 0)
		{
			if( is_numeric($brightness) )
			{
				imagefilter($this->im, IMG_FILTER_BRIGHTNESS, $brightness);
			}
		}
		
		
		/**
		 * Set Contrast
		 *
		 * @param $contrast - Contrast level
		 * @return void
		 */
		
		public function setContrast($contrast = 0)
		{
			if( is_numeric($contrast) )
			{
				imagefilter($this->im, IMG_FILTER_CONTRAST, $contrast);
			}
		}
		
		
		/**
		 * Grayscale Image - Make image black & white
		 *
		 * @return void
		 */
		
		public function grayscaleImage()
		{
			imagefilter($this->im, IMG_FILTER_GRAYSCALE);
		}
		
		
		/**
		 * Resize Image from original size
		 *
		 * @param $new_width - New width to transform image
		 * @param $new_height - New height to transform image
		 * @return void
		 */
		
		public function resizeOriginal($new_width, $new_height)
		{
			if( !is_numeric($new_width) || !is_numeric($new_height) || $new_width <= 0 || $new_height <= 0 )
			{
				$this->parseError("Resize new size must be numeric and positive for each one width and height");
			}
			else
			{
				$this->tmp_im = imagecreatetruecolor($new_width, $new_height);
				
				$origin = $this->getX();
				$res_to = $new_width;
				
				// This algorithm is taken from Ryan Rud (http://adryrun.com)
				$res_to	= $res_to * (750.0 / $origin);
				$a		= 52;
				$b		= -0.27810650887573124;
				$c		= .00047337278106508946;
				
				$result = $a + $b * $res_to + $c * $res_to * $res_to;
				
				$sharpness = max(round($result), 0);
				
				$matrix	= array(
					array(-1, -2, -1),
					array(-2, $sharpness + 12, -2),
					array(-1, -2, -1)
				);
				// End of algorithm
				
				$div	= $sharpness;
				$offset	= 0;
				
				@imageconvolution($this->tmp_im, $matrix, $div, $offset);
				imagecopyresampled($this->tmp_im, $this->im, 0, 0, 0, 0, $new_width, $new_height, $this->getX(), $this->getY());
				
				$this->im = $this->tmp_im;
			}
		}
		
		
		/**
		 * Resize image to new size by specifying new height
		 *
		 * @param $new_width - Get specified width, then generate height based on current image size and new width
		 * @return void
		 */
		
		public function resizeWidth($new_width)
		{
			if( !is_numeric($new_width) || $new_width <= 0 )
			{
				$this->parseError("Invalid resize width value. Must be numeric and positive value");
			}
			else
			{
				$x = $this->getX();
				$y = $this->getY();
				
				$w = $new_width;
				$h = floor($w * ($y / $x));
				
				$this->resizeOriginal($w, $h);
			}
		}
		
		
		/**
		 * Resize image to new size by specifying new height
		 *
		 * @param $new_height - Get specified height, then generate width based on current image size and new height
		 * @return void
		 */
		
		public function resizeHeight($new_height)
		{
			if( !is_numeric($new_height) || $new_height <= 0 )
			{
				$this->parseError("Invalid resize height value. Must be numeric and positive value");
			}
			else
			{
				$x = $this->getX();
				$y = $this->getY();
				
				$w = floor($new_height * ($x / $y));
				$h = $new_height;
				
				$this->resizeOriginal($w, $h);
			}
		}
		
		
		/**
		 * Resize image to specified width and set new size of entire block
		 *
		 * @param $width - Create image by specified width
		 * @param $height - Create image by specified height
		 * @param $resize_width - Resize image by specified width then center it on the new created image by $width, $height
		 * @param $bgcolor - Means that if width (or height) of resized image doesn't fill entire block then will apply the specified background color on empty area
		 * @return void
		 */
		
		public function resizeNewByWidth($width, $height, $resize_width, $bgcolor = "#FFF")
		{
			$bgcolor = $this->getColor($bgcolor);
			
			if( !$bgcolor ||  !is_numeric($width) || !is_numeric($width) || !is_numeric($resize_width) || $width <= 0 || $height <= 0 || $resize_width <= 0 )
			{
				$this->parseError("Resize new size must be numeric and positive for each one width and height");
			}
			else
			{
				$tmp_im = imagecreatetruecolor($width, $height);
				
				imagefill($tmp_im, 0, 0, $bgcolor);
				
				$this->resizeWidth($resize_width);
				
				$horizontal_shifting = floor(($width - $this->getX())/2);
				$vertical_shifting = floor(($height - $this->getY())/2);
				
				imagecopy($tmp_im, $this->im, $horizontal_shifting, $vertical_shifting, 0, 0, $this->getX(), $this->getY());
				
				$this->im = $tmp_im;
			}
		}
		
		
		/**
		 * Resize image to specified height and set new size of entire block
		 *
		 * @param $width - Create image by specified width
		 * @param $height - Create image by specified height
		 * @param $resize_height - Resize image by specified height then center it on the new created image by $width, $height
		 * @param $bgcolor - Means that if width (or height) of resized image doesn't fill entire block then will apply the specified background color on empty area
		 */
		
		public function resizeNewByHeight($width, $height, $resize_height, $bgcolor = "#FFF")
		{
			$bgcolor = $this->getColor($bgcolor);
			
			if( !$bgcolor ||  !is_numeric($width) || !is_numeric($width) || !is_numeric($resize_height) || $width <= 0 || $height <= 0 || $resize_height <= 0 )
			{
				$this->parseError("Resize new size must be numeric and positive for each one width and height");
			}
			else
			{
				$tmp_im = imagecreatetruecolor($width, $height);
				
				imagefill($tmp_im, 0, 0, $bgcolor);
				
				$this->resizeHeight($resize_height);
				
				$horizontal_shifting = floor(($width - $this->getX())/2);
				$vertical_shifting = floor(($height - $this->getY())/2);
				
				imagecopy($tmp_im, $this->im, $horizontal_shifting, $vertical_shifting, 0, 0, $this->getX(), $this->getY());
				
				$this->im = $tmp_im;
			}
		}
		
		
		/**
		 * Add Blur to Image
		 *
		 * @param $gausian - If true apply gaussian blur to the image, otherwise apply basic blur
		 * @return void
		 */
		
		public function addBlur($gausian = false)
		{
			if( $this->im )
			{
				imagefilter($this->im, $gausian ? IMG_FILTER_GAUSSIAN_BLUR : IMG_FILTER_SELECTIVE_BLUR);
			}
		}
		
		
		/**
		 * Add Gaussian blur to image
		 *
		 * @return void
		 */
		
		public function addGaussianBlur()
		{
			$this->addBlur(true);
		}
		
		
		/**
		 * HTML to RGB
		 *
		 * @param $color - Return RGB system, the array of 3 elements ordered array($r, $g, $b);
		 * @return void
		 */
		
		private function html2rgb($color)
		{
			// Regulate Color String
			switch( strlen($color) )
			{
				case 2:
				case 5:
					$color .= "F";
					break;
				
				case 4:
					$color .= "FF";
					break;
				
				case 3:
				case 6:
					break;
				
				default:
					$color = "FFFFFF";
			}
			
			if ($color[0] == '#')
				$color = substr($color, 1);
			
			if (strlen($color) == 6)
			list($r, $g, $b) = array($color[0].$color[1],
									 $color[2].$color[3],
									 $color[4].$color[5]);
			
			elseif (strlen($color) == 3)
				list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
			else
				return false;
			
			$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
			
			return array($r, $g, $b);
		}
		
		
		/**
		 * Crop Image
		 *
		 * @param $x - Locate on $x coordinate of original image
		 * @param $y - Locate on $y coordinate of original image
		 * @param $width - Starting from $x position create width
		 * @param $height - Starting from $y position create height
		 * @return void
		 */
		
		public function cropImage($x, $y, $width, $height)
		{
			if( !is_numeric($x) || !is_numeric($y)  || !is_numeric($width)  || !is_numeric($height) || ($x * $y * $width * $height) <= 0 )
			{
				$this->parseError("Invalid input values for cropImage(x, y, width, height), all parameters are required to be positive numbers");
			}
			else
			{
				$this->tmp_im = imagecreatetruecolor($width, $height);
				
				imagecopy($this->tmp_im, $this->im, 0, 0, $x, $y, $width, $height);
				
				$this->im = $this->tmp_im;
			}
		}
		
		
		/**
		 * Add Watermark text to the image
		 *
		 * @param $text - Text to add on the image
		 * @param $vertical_position - Set vertical position of the text that will be placed on image
		 * @param $horizontal_position - Set horizonal position of the text that will be placed on image
		 * @param $font_size - Specify font size of watermark
		 * @param $fontcolor - Hexadecimal color format for the watermark text
		 * @param $angle - Rotate text by specified angle degree
		 * @param $margin - Margin text by spacing from corners
		 * @return void
		 */
		
		public function addWatermark($text, $vertical_position, $horizontal_position, $font_size = 12, $fontcolor = "#FFF", $angle = 0, $margin = 5)
		{
			$font = $this->watermark_font_path;
			
			$valid_vpositions = array(self::IMAGE_POSITION_TOP, self::IMAGE_POSITION_CENTER, self::IMAGE_POSITION_BOTTOM);
			$valid_hpositions = array(self::IMAGE_POSITION_LEFT, self::IMAGE_POSITION_CENTER, self::IMAGE_POSITION_RIGHT);
			
			if( !is_numeric($font_size) || $font_size <= 0 )
			{
				$this->parseError("Font size must be valid integer");
				return;
			}
			else
			if( !is_numeric($angle) )
			{
				$this->parseError("Angle must be valid integer numer (positive or negative)");
				return;
			}
			else if( !is_numeric($margin) || $margin < 0 )
			{
				$this->parseError("Invalid margin! Must be positive integer");
				return;
			}
			else if( !$this->hexToRGB($fontcolor) )
			{
				return;
			}
			
			if( in_array($vertical_position, $valid_vpositions, true) && in_array($horizontal_position, $valid_hpositions, true) )
			{
				$x_padd = 0;
				$y_padd = 0;
				
				$watermark = @imagettfbbox($font_size, $angle, $font, $text);

				if( !$watermark )
					$this->parseError("Font directory doesn't exists or invalid font pack! Check class ImageTools and modify the instance variable \$watermark_font_path that is responsible for font pack");
				
				
				$w_width = abs($watermark[2] - $watermark[0]);
				$w_height = abs($watermark[7] - $watermark[1]);
				
				
				$im_width = $this->getX();
				$im_height = $this->getY();
				
				// Set X-Position
				if( $horizontal_position == self::IMAGE_POSITION_LEFT )
				{
					$x_padd = $margin;
				}
				else
				if( $horizontal_position == self::IMAGE_POSITION_CENTER )
				{
					$x_padd = floor($im_width/2) - floor($w_width/2);
				}
				else // Right
				{
					$x_padd = $im_width - $w_width - $margin;
				}
				
				
				// Set Y-Position
				if( $vertical_position == self::IMAGE_POSITION_CENTER )
				{
					$y_padd = floor($im_height/2) - floor($w_height/2);
				}
				else
				if( $vertical_position == self::IMAGE_POSITION_BOTTOM )
				{
					$y_padd = $im_height - $margin;
				}
				else // TOP
				{
					$y_padd += floor($w_height) + $margin;
				}
				
				imagettftext($this->im, $font_size, $angle, $x_padd, $y_padd, $this->getColor($fontcolor), $font, $text);
			}
			else
			{
				$this->parseError("Invalid watermark text position!");
			}
		}
		
		
		/**
		 * Add Watermark as Image
		 *
		 * @param $image_path - Image path to place as watermark on image
		 * @param $vertical_position - Set vertical position of the text that will be placed on image
		 * @param $horizontal_position - Set horizonal position of the text that will be placed on image
		 * @param $margin - Margin text by spacing from corners
		 * @return void
		 */
		
		public function addWatermarkImage($image_path, $vertical_position, $horizontal_position, $margin = 5)
		{
			if( file_exists($image_path) )
			{
				$valid_vpositions = array(self::IMAGE_POSITION_TOP, self::IMAGE_POSITION_CENTER, self::IMAGE_POSITION_BOTTOM);
				$valid_hpositions = array(self::IMAGE_POSITION_LEFT, self::IMAGE_POSITION_CENTER, self::IMAGE_POSITION_RIGHT);
				
				$watermark = new ImageTools($image_path);
				
				if( in_array($vertical_position, $valid_vpositions, true) && in_array($horizontal_position, $valid_hpositions, true) )
				{
					$x_padd = 0;
					$y_padd = 0;
						
					$w_width = $watermark->getX();
					$w_height = $watermark->getY();
					
					$im_width = $this->getX();
					$im_height = $this->getY();
					
					// Set X-Position
					if( $horizontal_position == self::IMAGE_POSITION_LEFT )
					{
						$x_padd = $margin;
					}
					else
					if( $horizontal_position == self::IMAGE_POSITION_CENTER )
					{
						$x_padd = floor($im_width/2) - floor($w_width/2);
					}
					else // Right
					{
						$x_padd = $im_width - $w_width - $margin;
					}
					
					
					// Set Y-Position
					if( $vertical_position == self::IMAGE_POSITION_CENTER )
					{
						$y_padd = floor($im_height/2) - floor($w_height/2);
					}
					else
					if( $vertical_position == self::IMAGE_POSITION_BOTTOM )
					{
						$y_padd = $im_height - floor($w_height) - $margin;
					}
					else // TOP
					{
						$y_padd += $margin;
					}
				
					imagecopy($this->im, $watermark->getIm(), $x_padd, $y_padd, 0, 0, imagesx($watermark->getIm()), imagesy($watermark->getIm()));
					//$watermark->destroy();
				}
				else
					$this->parseError("Invalid Watermark image position!");
			}
			else
			{
				$this->parseError("Invalid image path!");
			}
		}
		
		
		/**
		 * Get Im
		 * 
		 * @return Image Resource
		 */
		
		public function getIm()
		{
			return $this->im;
		}
		
		/**
		 * Save Image to file
		 *
		 * @param $path - Select path that image will be saved
		 * @param $name - Specify image name that will be saved (valid image format otherwise will be selected by Image Output Type)
		 * @param $quality - If image is JPEG (JPG) format specify the quality that will be saved
		 * @param $overwrite - Select if existing image will be overwrited or not
		 */
		
		public function save($path, $name, $quality = 90, $overwrite = true)
		{
			if( file_exists($path) )
			{
				$split = explode(".", $name);
				$extension = strtoupper( $split[ count($split) - 1 ] );
				
				if( !is_numeric($quality) || $quality < 0 || $quality > 100 )
					$quality = 90;
				
				if( $overwrite != 1 && file_exists($path . $name) )
				{
					$this->parseError("Cannot overwrite file '$name'");
				}
				else
				{
					switch( $extension )
					{
						case "PNG":
							imagepng($this->im, $path . $name);
							break;
						
						case "JPG":
							imagejpeg($this->im, $path . $name, $quality);
							break;
							
						case "JPEG":
							imagejpeg($this->im, $path . $name, $quality);
							break;
						
						case "GIF":
							imagegif($this->im, $path . $name);
							break;
						
						default:
							$this->save($path, $name . "." . strtolower($this->image_output_type), $quality, $overwrite);
					}
				}
				
			}
			else
			{
				$this->parseError("Invalid save path, it does not exists! :: <b>$path</b>");
			}
		}
		
		
		/**
		 * This methods serves for parsing errors directly on image
		 */
		
		public function parseError($error_msg)
		{
			$word_wrap = wordwrap(strip_tags($error_msg), 65, "\n");
			
			$exp = explode("\n", $word_wrap);
			
			$img_height = count($exp) * 30;
			$img_width = 520;
			
			$tmp_im = imagecreatetruecolor($img_width, $img_height);
			
			$color = imagecolorallocate($tmp_im, 255, 255, 255);
			
			for($i=0; $i<count($exp); $i++)
			{
				imagestring($tmp_im, 4, 5, 6+($i)*30, $exp[$i], $color);
			}
			
			
			header("Content-Type: image/jpeg");
			imagejpeg($tmp_im);
			imagedestroy($tmp_im);
		}
	}
?>