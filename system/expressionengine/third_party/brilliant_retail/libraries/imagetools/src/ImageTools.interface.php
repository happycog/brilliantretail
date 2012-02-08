<?php
	interface ImageToolsInterface
	{
		/* Memory size to allocate per image */
		
		const ALLOCATE_MEMORY = '50M';
		
		/* Constants */
	 
		const IMAGE_TYPE_JPG  = 1;
		
		const IMAGE_TYPE_JPEG = 1;
		
		const IMAGE_TYPE_PNG  = 2;
		
		const IMAGE_TYPE_GIF  = 3;
		
		
		const IMAGE_POSITION_TOP = 1;
		
		const IMAGE_POSITION_CENTER = 2;
		
		const IMAGE_POSITION_BOTTOM = 3;
		
		const IMAGE_POSITION_LEFT = 4;
		
		const IMAGE_POSITION_RIGHT = 5;
		
		
		/* Methods */
		
		// Effects
		
		public function reflect($percent = 35, $bg_color = "#FFF", $spacing = -1);
		
		public function setBrightness($brightness = 0);
		
		public function setContrast($contrast = 0);
		
		public function grayscaleImage();
		
		public function addBlur($gausian = false);
		
		public function addGaussianBlur();
		
		
		// Resizing
		
		public function resizeOriginal($new_width, $new_height);
		
		public function resizeWidth($new_width);
		
		public function resizeHeight($new_height);
		
		public function resizeNewByWidth($width, $height, $resize_width, $bgcolor = "#FFF");
		
		public function resizeNewByHeight($width, $height, $resize_height, $bgcolor = "#FFF");
		
		
		// Watermarking
		
		public function addWatermark($text, $vertical_position, $horizontal_position, $font_size = 12, $fontcolor = "#FFF", $angle = 0, $margin = 5);
		
		public function addWatermarkImage($image_path, $vertical_position, $horizontal_position, $margin = 5);
		
		
		// Cropping
		
		public function cropImage($x, $y, $width, $height);
		
		
		// Rotate Image
		
		public function rotateLeft();
		
		public function rotateRight();
		
		public function rotateImage($degree, $bg = null);
		
		
		// Other
		
		public function setTransparentBg();
		
		public function hexToRGB($hex);
		
		
		// Output & Output Type
		
		public function setOutputType($image_type);
		
		public function save($path, $name, $quality = 90, $overwrite = true);
		
		public function showImage();
	}
?>