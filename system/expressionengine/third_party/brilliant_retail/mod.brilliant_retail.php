<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
include_once(PATH_THIRD.'brilliant_retail/core/class/core.brilliant_retail.php');

class Brilliant_retail extends Brilliant_retail_core{
	
	public $return_data 		= '';
	public $range 				= '';
	public $switch_cnt 			= 0;

	function __construct(){
		parent::__construct();
		if(!isset($this->EE->session->cache['br_output_js'])){
			$this->EE->session->cache['br_output_js'] = '';
		}
	}

	/** 
	* Autoloading for integration methods. 
	* 
	* @author   David Dexter 
	* @access   public
	*/  
	    function __call($name,$args){
	        $this->EE->load->library('integrate');
	        return $this->EE->integrate->$name(); 
	    }
    
	/**
	* Show system messages and alerts
	* 
	* @author 	David Dexter 
	* @access	public
	* @param	void
	* @return	string
	*/	
		function show_message()
		{
			$output = '';
			if(isset($_SESSION["br_message"])){
				$snippetdata = $this->vars["snippets"]["br_system_message"];
				$vars[0] = array(
									'message' => $_SESSION["br_message"]
								);
				$output = $this->EE->TMPL->parse_variables($snippetdata, $vars);
				unset($_SESSION["br_message"]);
			}
			
			if(isset($_SESSION["br_alert"])){
				$snippetdata = $this->vars["snippets"]["br_system_alert"];
				$vars[0] = array(
									'alert' => $_SESSION["br_alert"]
									);
				$output = $this->EE->TMPL->parse_variables($snippetdata, $vars);
				unset($_SESSION["br_alert"]);
			}
			return $this->return_data = $output;
		}

	/**
	* Check to see if a front end message exists
	* 
	* @author 	David Dexter 
	* @access	public
	* @param	void
	* @return	string
	*/	
		function message_exists() 
		{
			if(isset($_SESSION['br_alert']) || isset($_SESSION["br_message"])){
				return 'y';
			}else{
				return '';
			}
		}

	/**
	* Show meta for given section
	* 
	* @author 	David Dexter 
	* @access	public
	* @param	void
	* @return	string
	*/
		public function meta()
		{
			// Start out with our defaults
				$title 		= $this->_config["store"][$this->site_id]["meta_title"];
				$keywords 	= $this->_config["store"][$this->site_id]["meta_keywords"];
				$descr 		= $this->_config["store"][$this->site_id]["meta_descr"];
				$canonical_url = FALSE;
			// Check for settings on this page
				if($meta = $this->_get_meta_info()){
					$title 		= ($meta["title"] != '') ? $meta["title"] : $title;
					$keywords 	= ($meta["keywords"] != '') ? $meta["keywords"] : $keywords;
					$descr 		= ($meta["descr"] != '') ? $meta["descr"] : $descr;
					$canonical_url = $meta["canonical_url"];
				}
			
			$output = '<title>'.$title.'</title>
						<meta name="description" content="'.$descr.'" />
						<meta name="keywords" content="'.$keywords.'" />';
			// Is it a canonical url?
				if($canonical_url !== FALSE){
					$output .= '<link rel="canonical" href="'.$canonical_url.'" />';
				}
			return $output;
		}
	
	/**
	* Show a specific product by id
	* 
	* @access	public
	* @param	int
	* @return	string
	*/	
		function product($product_id='')
		{
			include_once(APPPATH.'modules/channel/mod.channel.php');
			
			$product_id = $this->EE->TMPL->fetch_param('product_id');
			$products 	= $this->_get_product($product_id);
			$ajax 		= $this->EE->TMPL->fetch_param('ajax','no');
			$form 		= $this->EE->TMPL->fetch_param('form','yes');
			$form_class = $this->EE->TMPL->fetch_param('form_class','');
			$show_js 	= $this->EE->TMPL->fetch_param('show_js','yes');
			
			$pattern = "^".LD."no_results".RD."(.*?)".LD."/"."no_results".RD."^s";
			if(!$products){
				preg_match($pattern,$this->EE->TMPL->tagdata, $matches);
				if(isset($matches[1])){
					return trim($matches[1]);
				}else{
					return '';
				}
			}
			
			// Lets have some fun with custom tags
				$this->EE->TMPL->tagparams['entry_id'] 	= $products[0]["entry_id"];
				$this->EE->TMPL->tagparams['limit'] 	= '1';
				$this->EE->TMPL->tagparams['dynamic'] 	= 'no';
				$this->EE->TMPL->tagparams['show_future_entries'] = 'yes';
			
				$custom = new Channel();
				$tagdata = $custom->entries();
				
			// Now lets rock and roll on the standard BR fields	
			
			// Form post url
				$action = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_add');

				if($ajax == 'yes')
				{
					$action .= '&ajax=yes';
				}
				
			// Parse Tags	
				$tagdata = preg_replace($pattern,"",$tagdata);
				
				if($products[0]["configurable_js"] != ''){
					// IF THERE IS A CALL TO {configurable_js}
					// lets push the js from the product array into 
					// the session cache so that we can append it 
					// to the bottom of the page. 
						if(strpos(strtolower($tagdata),'{configurable_js}') != FALSE){
							$this->EE->session->cache['br_output_js'] .= $products[0]["configurable_js"];
						}
						$products[0]["configurable_js"] = '';
				}
				
				// Adding br_product_parse_before manipulate $products array prior to 
				// parsion. Useful for adding tags, etc. ADDED 1.1.5 by dpd
					if($this->EE->extensions->active_hook('br_product_parse_before') === TRUE){
						$products = $this->EE->extensions->call('br_product_parse_before', $products); 
					}

					$form_details = array(
						'action'     		=> $action,
	                  	'name'           	=> 'form_cart_add',
	                  	'id'             	=> "form_".$products[0]["product_id"],
	                  	'class'          	=> $form_class,
	                  	'hidden_fields'  	=> array($products[0]["product_id"].'_product_id' => $products[0]["product_id"]), 
	                  	'secure'         	=> TRUE
	                  );

					$form_open 	= $this->EE->functions->form_declaration($form_details);

					if($form != 'yes'){
						
						$products[0]["form_open"] 	= $form_open;
						$products[0]["form_close"]	= form_close();
						if($show_js == 'yes'){
							$this->EE->session->cache['br_output_js'] .= '$(function(){$(\'#form_'.$products[0]["product_id"].'\').validate();});';
						}
					}
					
					$output = $this->EE->TMPL->parse_variables($tagdata, $products);
				
				
				// We should add a configuration variable to remove the 
				// the blank default first option. 
					$br_hide_blank_option = ($this->EE->config->item('br_hide_blank_option')) ? $this->EE->config->item('br_hide_blank_option') : '';
					if($br_hide_blank_option === TRUE){
						$output = str_replace('<option value=""></option>',"",$output);
					}			
					
				// Build the form and add the js to the footer
					if($form == 'yes'){
						$output 	= $form_open.$output.form_close();
						$this->EE->session->cache['br_output_js'] .= '$(function(){$(\'#form_'.$products[0]["product_id"].'\').validate();});';
					}
							
			$this->switch_cnt = 0;
			$output = preg_replace_callback('/'.LD.'image_switch\s*=\s*([\'\"])([^\1]+)\1'.RD.'/sU', array(&$this, '_parse_switch'), $output);
			
			// Adding br_product_parse_after manipulate $output string
			// ADDED 1.1.5 by dpd
				if($this->EE->extensions->active_hook('br_product_parse_after') === TRUE){
					$output = $this->EE->extensions->call('br_product_parse_after', $output); 
				}

			return $output;
		}
		
	/*
	* Create a br wraper for displaying custom fields 
	*/
		function product_custom(){
			
			// We should have access to the TMPL class 
			// but if we run the product_custom parser 
			// from an ACT we need to load it.
				if(!isset($this->EE->TMPL)){
					$tmp = new EE_Template();
					$this->EE->TMPL = $tmp->EE->template;
				}
				
				// If they passed in the entry then we are set
					$entry_id = $this->EE->TMPL->fetch_param('entry_id');
					$product_id = $this->EE->TMPL->fetch_param('product_id');
				
				// Ut oh. We need something to hold on too. 
				if($entry_id == '' && $product_id == ''){
					$this->EE->TMPL->log_item('BrilliantRetail: product_custom is a no go. We need an entry_id or product_id');	
					return '';
				}
				
				if($entry_id == ''){
					$this->EE->TMPL->log_item('BrilliantRetail: product_custom has no entry_id. Trying to retrieve from product_id');
					$entry_id = $this->EE->product_model->get_product_entry($product_id);
				}

			if($entry_id == ''){
				$this->EE->TMPL->log_item('BrilliantRetail: product_custom is a no go. We have no entry_id to pair it too');	
				return '';
			}
			
			$this->EE->TMPL->log_item('BrilliantRetail: product_custom processing entry_id '.$entry_id);

			include_once(APPPATH.'modules/channel/mod.channel.php');
			$this->EE->TMPL->tagparams['entry_id'] 				= $entry_id;
			$this->EE->TMPL->tagparams['limit'] 				= '1';
			$this->EE->TMPL->tagparams['dynamic'] 				= 'no';
			$this->EE->TMPL->tagparams['show_future_entries'] 	= 'yes';
			
			// We may need to name space the field if they are in a custom field
				if(strpos($this->EE->TMPL->tagdata,'{br:') !== FALSE){
					
					$this->EE->TMPL->log_item('BrilliantRetail: found br: namespace in the product_custom tag for entry_id '.$entry_id);

					$original 	= array(LD."br:",LD."/br:");
					
					// we need to do some magic on the tmpl data 
						$this->EE->TMPL->tagdata = str_replace($original,LD,$this->EE->TMPL->tagdata);
						$this->EE->TMPL->tagchunk = str_replace($original,LD,$this->EE->TMPL->tagdata);
						foreach($this->EE->TMPL->tag_data as $key => $val)
						{
							$this->EE->TMPL->tag_data[$key]["block"] = str_replace($original,LD,$this->EE->TMPL->tag_data[$key]["block"]);
							$this->EE->TMPL->tag_data[$key]["chunk"] = str_replace($original,LD,$this->EE->TMPL->tag_data[$key]["chunk"]);
						}
					// Strip any name spaced single vars			
						foreach($this->EE->TMPL->var_single as $key => $val){
							$tmp[str_replace('br:','',$key)] = str_replace('br:','',$val);
						}
						if(isset($tmp)){
							$this->EE->TMPL->var_single = $tmp;
							unset($tmp);
						}
					
					// Strip any name spaced pair vars			
						foreach($this->EE->TMPL->var_pair as $key => $val){
							$tmp[str_replace('br:','',$key)] = str_replace('br:','',$val);
						}
						if(isset($tmp)){
							$this->EE->TMPL->var_pair = $tmp;
							unset($tmp);
						}
				}
			$custom = new Channel();
			$tagdata = $custom->entries();
			return $tagdata;
		}
	

	/**
	* Display add-on products to a specified product_id
	* 
	* @access	public
	* @param	int
	* @return	string
	*/	
		public function product_addon()
		{
			$product_id = $this->EE->TMPL->fetch_param('product_id');
			$products = $this->EE->product_model->get_products($product_id);
			$output = '';
			$addon = array();
			if(count($products[0]["addon"]) == 0){
				return $output;
			}
			$i = 0;
			foreach($products[0]["addon"] as $row){
				if(isset($row["product_id"])){
					if($add = $this->_get_product($row["product_id"])){
						// Set up the new addon prefixed array
							foreach($add[0] as $key => $val){
								$tmp['addon_'.$key] = $val;
							}
						$addon[$i] = $tmp;
						$i++; 
					}
				}
			}
			$vars[0] = array('addon_products' => $addon);
			$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata,$vars);
			return $output;
		}
		
	/**
	* Display products related to a specified product_id
	* 
	* @access	public
	* @param	int
	* @return	string
	*/	
	
		public function product_related()
		{
			$product_id = $this->EE->TMPL->fetch_param('product_id');
			$form_class = $this->EE->TMPL->fetch_param('form_class','');
			
			$products = $this->EE->product_model->get_products($product_id);
			$output = '';
			$related = array();
			if(count($products[0]["related"]) == 0){
				return $output;
			}
			
			// Form post url
				$action = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_add');

			$i = 0;
			foreach($products[0]["related"] as $row){
				if(isset($row["product_id"])){

					if($rel = $this->_get_product($row["product_id"])){

						// Set up the new related prefixed array
							foreach($rel[0] as $key => $val){
								$tmp['related_'.$key] = $val;
							}
						
						// Add form open / close 
							
							$form_details = array(
									'action'	   		=> $action,
								  	'id'             	=> 'form_'.$rel[0]["product_id"],
								  	'class' 			=> $form_class,
								  	'hidden_fields'  	=> array($rel[0]["product_id"].'_product_id' => $rel[0]["product_id"]), 
				                  	'secure'         	=> TRUE
								  );  

							$tmp["related_form_open"] 	= $this->EE->functions->form_declaration($form_details);
							$tmp["related_form_close"] 	= '</form>';
						
						// Add depreciated 
							$tmp['related_thumb'] = $rel[0]["image_thumb"];
									
						$related[$i] = $tmp;
						$i++; 
					}
				}
			}
			$vars[0] = array('related_products' => $related);
			$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata,$vars);
			return $output;
		}

	/**
	* Display products related to a specified url_title
	* 
	* 
	* @access	public
	* @param	int
	* @return	string
	*/	
			public function product_category(){
				// Accepts catgories by pipedelimited param 
				$url_title = $this->EE->TMPL->fetch_param('url_title');
				$products = $this->EE->product_model->get_category_by_key($url_title);
				$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata,$products);
				return $output;
			}

	/*
	 * Return the logo path for the current store
	 * 
	 * @access	public
	 * @return	string
	 */
		public function logo()
		{ 
			$store = $this->EE->store_model->get_store_by_id($this->site_id);
			$width = $this->EE->TMPL->fetch_param('width');
			return $this->_config["media_url"].$store[0]["logo"];
		}

	/**
	* Return store address and phone information
	* 
	* @access	public
	* @return	string
	*/		
		public function store(){
			$store = $this->EE->store_model->get_store_by_id($this->site_id);
			$vars[0] = array();
			foreach($store[0] as $key => $val){
    			$allowed = array("logo","phone","fax","address1","address2","city","state","country","zipcode");
    			if(in_array($key,$allowed)){
    				$vars[0][$key] = $val;
    			}
			}
			$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
			return $output;
		}

	/**
	* Caches and returns an image 
	* 
	* @access	public
	* @return	string
	*/		
		public function image()
		{	
			$this->EE->load->library('image_tools');
			
			// Where do we cache images? 
				$cache = $this->_config["media_dir"].'cache/';
				if(!file_exists($cache)){
					mkdir($cache,DIR_WRITE_MODE,TRUE);
				}
			
			// Get params 
				$src = $this->EE->TMPL->fetch_param('src');
			
				// Did they pass the full path to the media url? 
					if(strpos($src,$_SERVER["HTTP_HOST"]) !== -1){
						$this->EE->TMPL->log_item('BrilliantRetail: Image is the full path.');
						// lets make it relative to the media directory. 
						$src = str_replace($this->_config["media_url"],"",$src);
					}
			
				if(!file_exists($this->_config["media_dir"].$src) || $src == ''){
					$this->EE->TMPL->log_item('BrilliantRetail: IMAGE FILE SOURCE DOES NOT EXIST!');
					// Give back the original source but log it in the output and debugging
					return $src;
				}else{
					$this->EE->TMPL->log_item('BrilliantRetail: valid image source ('.$this->_config["media_dir"].$src.')');
				} 
					
				// Image attributes & sizing
					$id 			= $this->EE->TMPL->fetch_param('id');
					$class 			= $this->EE->TMPL->fetch_param('class');
					$title 			= $this->EE->TMPL->fetch_param('title');
					$alt 			= $this->EE->TMPL->fetch_param('alt');
					$width 			= (int) $this->EE->TMPL->fetch_param('width',250);
					$height 		= (int) $this->EE->TMPL->fetch_param('height',250);
					$mode 			= $this->EE->TMPL->fetch_param('mode','matte');
					$background 	= $this->EE->TMPL->fetch_param('bgcolor','#FFFFFF');
					
				// Image effects 
					## Reflection
						$reflect 		= ( $this->EE->TMPL->fetch_param('reflect')) ? (int) $this->EE->TMPL->fetch_param('reflect') : 0;
						$reflect_bg		= ( $this->EE->TMPL->fetch_param('reflect_bg')) ? $this->EE->TMPL->fetch_param('reflect_bg') : '#FFF' ;
						$reflect_space	= ( $this->EE->TMPL->fetch_param('reflect_space')) ? (int) $this->EE->TMPL->fetch_param('reflect_space') : 1;

					## Watermarking		

						$watermark = ( $this->EE->TMPL->fetch_param('watermark')) ? $this->EE->TMPL->fetch_param('watermark') : '';
						$watermark_vpos = ( $this->EE->TMPL->fetch_param('watermark_vpos')) ? strtoupper($this->EE->TMPL->fetch_param('watermark_vpos')) : "BOTTOM";
						$watermark_hpos = ( $this->EE->TMPL->fetch_param('watermark_hpos')) ? strtoupper($this->EE->TMPL->fetch_param('watermark_hpos')) : "RIGHT";
						
						// Vertical Spacing 
							$vPos = array(
											"TOP" 		=> image_tools::IMAGE_POSITION_TOP,
											"CENTER" 	=> image_tools::IMAGE_POSITION_CENTER,
											"BOTTOM" 	=> image_tools::IMAGE_POSITION_BOTTOM
										);
						// Horizontal Spacing	
							$hPos = array(
											"LEFT" 		=> image_tools::IMAGE_POSITION_LEFT,
											"CENTER" 	=> image_tools::IMAGE_POSITION_CENTER,
											"RIGHT" 	=> image_tools::IMAGE_POSITION_RIGHT
										);
										
				// Output Settings 
					$url_only 		= $this->EE->TMPL->fetch_param('url_only');
				
				
			// build the paths 
			$part = explode('.',$src);
			$ext = $part[count($part)-1];
			$filepath = substr($src,0,-(strlen($part[count($part)-1])+1));
			$a = explode("/",$filepath);
			$cache_file = $a[count($a)-1].'_'.$mode;
			if($reflect > 0){
				$cache_file = $cache_file.'_r'.$reflect;	
			}
			
			$use_watermark = FALSE;
			if($watermark != ''){
				if(file_exists($this->_config["media_dir"].$watermark)){
					$v = (isset($vPos[$watermark_vpos])) ? $vPos[$watermark_vpos] : $vPos["BOTTOM"];
					$h = (isset($hPos[$watermark_hpos])) ? $hPos[$watermark_hpos] : $hPos["RIGHT"];
					$cache_file = $cache_file.'_w'.$v.$h;
					$use_watermark = TRUE;
				}
			}
			
			$cache_file = $cache_file.'_'.$width.'_'.$height.'.jpg'; 
			
			// no cache file
				if(!file_exists($this->_config["media_dir"].'cache/'.$cache_file)){
					
					// What mode of sizing do we want to do?
					// # matte  	(default) scales the image to the height and width provided and 
					//			 	returns the image matted on the canvas widht / height provided. 
					// # scale  	scales to image to the height or width provided. If both are
					//				provided then it will fit within the dimensions and scale the 
					//				opposite size accordingly. 
					// # fit  		stretches the image to exactly the dimensions provided 
						$use_width = FALSE;
						if($mode == 'matte'){ 
							$size = getimagesize($this->_config["media_dir"].$src);
							// Calculate the resize side
								$w_ratio = $size[0] / $width; 
								$h_ratio = $size[1] / $height; 
							if($w_ratio >= $h_ratio){
								$use_width = TRUE;
								$n_width = $width; 
								$n_height = round(($width*$size[1])/$size[0]);	
							}else{
								$n_width = round(($height*$size[0])/$size[1]);
								$n_height = $height;
							}
						}elseif($mode == 'scale'){
							if($width > 0 && $height > 0){
								$size = getimagesize($this->_config["media_dir"].$src);
								// Need to calculate the 
								// Calculate the resize side
								$w_ratio = $size[0] / $width; 
								$h_ratio = $size[1] / $height; 
								if($w_ratio >= $h_ratio){
									$use_width = TRUE;
								}
							}elseif($width > 0){
								$use_width = TRUE;
							}
						}
						
						$this->EE->image_tools->create($this->_config["media_dir"].$src);

					// Do we have a relection?
						if($reflect > 0){
							$this->EE->image_tools->reflect($reflect, $reflect_bg, $reflect_space); // drop shadow percentage, background color, spacing
						}

					// Do resize pre watermarking
						
						if($mode == 'fit'){
							// stretch the image to the exact specs 
							// provided in the height and width fields 
								$this->EE->image_tools->resizeOriginal($width,$height);		
						}elseif($mode == 'scale'){
							// scale to height or width. If both are provided then fit within the box
							if($use_width === TRUE){
								$this->EE->image_tools->resizeWidth($width); // new width
							}else{
								$this->EE->image_tools->resizeHeight($height); // new height
							}
						}else{
							// scale and matte to size provided 
							if($use_width === TRUE){
								$this->EE->image_tools->resizeNewByWidth($width,$height,$n_width,$background); // new width, new height
							}else{
								$this->EE->image_tools->resizeNewByHeight($width,$height,$n_height,$background); // new width, new height
							}
						}
						
					// Set the image watermark 
						if($use_watermark === TRUE){
							$this->EE->image_tools->addWatermarkImage($this->_config["media_dir"].$watermark, $v, $h, 5); 
						}
						
					$this->EE->image_tools->save($this->_config["media_dir"].'cache/',$cache_file);
					
					if(strtolower($url_only) == "yes"){
						$this->EE->TMPL->log_item('BrilliantRetail: returning newly created cached image file ('.$this->_config["media_url"].'cache/'.$cache_file.')');
						return $this->_config["media_url"].'cache/'.$cache_file;
					}else{
						$this->EE->TMPL->log_item('BrilliantRetail: returning newly created cached image file ('.$this->_config["media_url"].'cache/'.$cache_file.')');
						return '<img src="'.$this->_config["media_url"].'cache/'.$cache_file.'" id="'.$id.'" class="'.$class.'" title="'.$title.'" alt="'.$alt.'" />';
					}
				}else{
					$this->EE->TMPL->log_item('BrilliantRetail: returning cached image file ('.$this->_config["media_url"].'cache/'.$cache_file.')');
					if(strtolower($url_only) == "yes"){
						return $this->_config["media_url"].'cache/'.$cache_file;
					}else{
						return '<img src="'.$this->_config["media_url"].'cache/'.$cache_file.'" id="'.$id.'" class="'.$class.'" title="'.$title.'" alt="'.$alt.'" />';
					}
				}
		}

	/**
	* Catalog - The power of catalog based searching! 
	* 
	* @access	public
	* @return	string
	*/	
		
		public function catalog()
		{ 
			
			$key 	= $this->EE->TMPL->fetch_param('url_title');
			$ajax 	= $this->EE->TMPL->fetch_param('ajax','no');
			$form_class = $this->EE->TMPL->fetch_param('form_class','');

			$memory_start = round(memory_get_usage()/1024/1024, 2);
			$this->EE->TMPL->log_item('BrilliantRetail Memory Pre Catalog: '.$memory_start.'MB');
			
			if($key == ''){
				$this->EE->TMPL->log_item('BrilliantRetail: No url_title provided. segment_2 assigned');
				$key = $this->EE->uri->segment(2);
			}
			
			if(!$category = $this->EE->product_model->get_category_by_key($key)){
				// Not a valid catalog page
				$this->EE->functions->redirect($this->EE->functions->create_url($this->EE->config->item('site_404')));
			}
			
			$this->EE->TMPL->log_item('BrilliantRetail Memory After get_category_by_key: '.round(memory_get_usage()/1024/1024, 2).'MB');

			// Lets do some price checking magic! 
				$i = 0;
				$products = array();
				foreach($category[0]["products"] as $p){
					$prod = $this->_get_product($p["product_id"]);
					if($price = $this->_check_product_price($prod[0])){
						$products[] = $prod[0];
					}
				}
				$category[0]["products"] = $products;
				
			$this->EE->TMPL->log_item('BrilliantRetail Memory After _check_product_price: '.round(memory_get_usage()/1024/1024, 2).'MB');
			
			// Get our category image
				$img = (trim($category[0]['image']) != '') ? $this->_config["media_url"].'images/'.$category[0]['image'] : '';
			
			// Build our variable data
				$vars[0] = array(
									'site_id' 			=>  $category[0]['site_id'],
									'category_id' 		=> 	$category[0]['category_id'],
									'category_image' 	=>	$img,
									'category_detail' 	=> 	$category[0]['detail'],
									'parent_id' 		=>  $category[0]['parent_id'],
									'category_title' 	=>  $category[0]['title'],
									'url_title' 		=>  $category[0]['url_title'],
									'meta_title' 		=>  $category[0]['meta_title'],
									'meta_keyword' 		=>  $category[0]['meta_keyword'],
									'meta_descr' 		=>  $category[0]['meta_descr'],
									'total_results' 	=> 	count($category[0]["products"]),
									'results' 			=> 	$category[0]["products"],
									'no_results' 		=> 	array(),
									'result_filter_set' => ''
								);
				
			// Filter the results
				if(count($category[0]["products"]) != 0){
					$vars = $this->_filter_results($vars,$key,true);
				}
			
			$this->EE->TMPL->log_item('BrilliantRetail Memory After _filter_results: '.round(memory_get_usage()/1024/1024, 2).'MB');
			
			// If there are no product
				if(count($vars[0]['results']) == 0 || !isset($vars[0]["results"])){
					$no_result = '';
					$key = 'no_results';
					preg_match("^".LD.$key.RD."(.*?)".LD."/".$key.RD."^s",$this->EE->TMPL->tagdata, $matches);
					if(isset($matches[1])){
						$no_result = trim($matches[1]);
					}
					$vars[0]['no_results'][0] 				= array(0 => $no_result);
					$vars[0]['result_paginate'][0] 			= array();
					$vars[0]['result_paginate_bottom'][0] 	= array();
					$vars[0]['result_filter_set'][0] 		= array();
					$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
					return $output;
				}
			
			// Doubled up the array offset. Lets fix that 
				$results = array();
				foreach($vars[0]['results'] as $rst){
					$results[] 	= $rst;
				}
				$vars[0]['results'] = $results;
				
				$this->EE->TMPL->log_item('BrilliantRetail Memory After offset fix: '.round(memory_get_usage()/1024/1024, 2).'MB');
			
			// Add form_open / form_close tags
				$action = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_add');

				if($ajax == 'yes')
				{
					$action .= '&ajax=yes';
				}

				$i = 0;
				foreach($vars[0]["results"] as $row){
					$vars[0]["results"][$i]["product_count"] = $i+1;
					$form_details = array(
						'action'     		=> $action,
	                  	'name'           	=> 'form_'.$row["product_id"],
	                  	'id'             	=> 'form_'.$row["product_id"],
	                  	'class'          	=> $form_class,
	                  	'hidden_fields'  	=> array($row["product_id"].'_product_id' => $row["product_id"]), 
	                  	'secure'         	=> TRUE
	                  );
					$vars[0]["results"][$i]["form_open"] 	= $this->EE->functions->form_declaration($form_details);
					$vars[0]["results"][$i]["form_close"] 	= '	</form>';
					$i++;
				}
			
			// Parse this thing
				$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
			
			// Set our switch
				$this->EE->TMPL->log_item('BrilliantRetail Memory before switch callback: '.round(memory_get_usage()/1024/1024, 2).'MB');

				$this->switch_cnt = 0;
				$output = preg_replace_callback('/'.LD.'product_switch\s*=\s*([\'\"])([^\1]+)\1'.RD.'/sU', array(&$this, '_parse_switch'), $output);
				
				$memory_end = round(memory_get_usage()/1024/1024, 2);
				$memory_total = $memory_end - $memory_start;
				$this->EE->TMPL->log_item('BrilliantRetail Memory Post Catalog: '.$memory_end.'MB - Total: '.$memory_total.'MB');
				
				return $output;
		}

	/**
	* Catalog Layered 
	* 
	* @access	public
	* @return	string
	*/	
		public function catalog_layered()
		{
			// Get the category key 
				$key = $this->EE->TMPL->fetch_param('url_title');
				if($key == ''){
					$key = $this->EE->uri->segment(2);
				}
				$layered = $this->EE->product_model->get_category_by_key($key);
	
				$products = array();
				
				foreach($layered[0]["products"] as $p){
					$prod = $this->_get_product($p["product_id"]);
					if($price = $this->_check_product_price($prod[0])){
						$products[] = $prod[0];
					}
				}
				
				$layered[0]["products"] = $products;
				
				$vars[0] = array('results' => $layered[0]["products"]);

				if($this->EE->config->item('br_catalog_multiple_filters') != 'y'){
					$vars = $this->_filter_results($vars,$key,false);
				}
				
				$layered = $this->_layered_navigation($vars[0]["results"],$key,$layered[0]["category_id"]);
				$output = '';
				if(count($layered) >= 1){
					$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $layered);
				}
				return $output;
		}

	/** 
	*
	*
	*
	*
	*/
		public function category(){
			$category = array();
			// By category_id is priority
				$category_id 	= $this->EE->TMPL->fetch_param('category_id');
				if($category_id != ''){
					$category = $this->EE->product_model->get_category($category_id);
				}
			// By url_title is a secondary option 
				$key = $this->EE->TMPL->fetch_param('url_title');
				if($key != ''){
					$category = $this->EE->product_model->get_category_by_key($key);
				}
				if($category == 0){ return; }

			// Is there a prefix in play? 
				$prefix = $this->EE->TMPL->fetch_param('prefix');
				if($prefix != ''){
					foreach($category[0] as $key=>$val){
						$tmp[$prefix.":".$key]=$val;
					}
					unset($category);
					$category[0] = $tmp;
				}
				return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $category);
		}
	
	/**
	* Category Menu 
	* 
	* @access	public
	* @return	string
	*/	
		public function category_menu()
		{
			// Parameters
				// ID for the UL tag		
					$id = $this->EE->TMPL->fetch_param('id','nav');
					
				// Class for the UL tag		
					$class = $this->EE->TMPL->fetch_param('class','');
					
				// Style allows for linear or nested
					$style = $this->EE->TMPL->fetch_param('style','nested');
					
				// exclude categories by url_title
					$exclude = $this->EE->TMPL->fetch_param("exclude");
				
				// Sort method (options: 'sort','title')
					$sort =  $this->EE->TMPL->fetch_param('sort','sort');
				
				// Show only the parent category
					$parent_only = $this->EE->TMPL->fetch_param('parent_only');
				
				// Base path
					$path = $this->EE->TMPL->fetch_param('path','catalog');
					
				// Active Category
					$product_selected = $this->EE->TMPL->fetch_param('active_cat',$this->EE->uri->segment(2));

				// Active Parent Category
					$parent_selected = $this->EE->TMPL->fetch_param('active_parent_cat','');
			
					// If the user didn't pass in the active or parent categories lets try to calculate it
					if($product_selected != ""){
						if($parent_selected == ""){
							// get parents recursively
							$parent_selected = $this->_get_parent_category($product_selected);
						}else{
							$parent_selected = explode("|",$parent_selected);
						}
					}
					
			// Get the categories
				$cat = $this->EE->product_model->get_categories(1,$sort);
	
			// Check to see if there are any list items to add before or after
				$before = "";
				$after = "";
				foreach ($this->EE->TMPL->var_pair as $key => $val) {
					if($key == 'before'){
						preg_match("^".LD.$key.RD."(.*?)".LD."/".$key.RD."^s",$this->EE->TMPL->tagdata, $matches);
						$before = isset($matches) ? $matches[1] : '' ;
					}
					if($key == 'after'){
						preg_match("^".LD.$key.RD."(.*?)".LD."/".$key.RD."^s",$this->EE->TMPL->tagdata, $matches);
						$after = isset($matches) ? $matches[1] : '' ;
					}
				}
			// Build the output 
				$output  = '';
				if($style == 'nested'){
					$output  .= "<ul id=\"".$id."\" class=\"".$class."\">\n";
				}
				$output .= $before;
				if(isset($cat[0])){
					$output .= $this->_menu_category_tree($cat[0],$cat,0,$product_selected,$parent_selected,$path,$parent_only,$exclude);
				}
				$output .= $after;
				if($style == 'nested'){
					$output .= "</ul>\n";
				}
			return $output;
		}
	
/********************/
/* CART FUNCTIONS 	*/
/********************/
	
		public function cart_clear_link()
		{
			return $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_clear');
		}
	
		public function cart()
		{
			$cart = $this->EE->product_model->cart_get();

			//We're going to check the inventory and if stock isn't correct - redirect the user
				$this->_check_inventory($cart);
			
			// Wrap items in form?
				$show_form = ($this->EE->TMPL->fetch_param('form') == '') ? 'yes' : $this->EE->TMPL->fetch_param('form') ;
	
			$id = '';
			$form_class = '';
			
			$update = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_update');
			$remove = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_remove');
			$output = '';
			$items = array();
			
			$i = 0;
			if(isset($cart["items"])){
				foreach($cart["items"] as $key => $v){
					// We want the entry_id for the product
						$p = $this->_get_product($v["product_id"]);
						$entry_id = $p[0]["entry_id"];
					
					// Lets split up the options
						$option = $opt_single = array();
						if($v["options"] != "")
						{
							$tmp = ltrim(rtrim($v["options"],"<br />"),"<h4>");
							$p = explode("<h4>",$tmp);
							
							$j=0;;
							foreach($p as $q)
							{
								$opt = explode("</h4>",$q);
								$opt_single['option:'.$j] = $option[] = array(	
													'label' => $opt[0],
													'value' => isset($opt[1]) ? $opt[1] : '' 
												);
								$j++;
							}	
						}
					// Build up the item array 
						$hash = md5($key);
						$items[$i] = $v;
						$items[$i]['option'] 		= $option;
						
						foreach($opt_single as $key => $val){
							$items[$i][$key] = $val;
						}
						
						$items[$i]['row_count'] 	= $i+1;
						$items[$i]['hash'] 			= $hash;
						$items[$i]['product_id'] 	= $v["product_id"];
						$items[$i]['entry_id'] 		= $entry_id;
						$items[$i]['remove_link'] 	= $remove.'&id='.$hash;
						$items[$i]['base'] 			= $this->_format_money($v['base']);
						$items[$i]['price']			= $this->_format_money($v['price']);
						$items[$i]['subtotal']		= $this->_format_money($v['subtotal']);
						$items[$i]['discount'] 		= $this->_format_money($v['discount']);
						$i++;
				}
			}
			$vars[0] = array(
								'items' => $items 
							);	
			$output = '';
			if(strtolower($show_form) == "yes"){ 

				$form_details = array(
						'action'	   		=> $update,
					  	'id'             	=> $id,
					  	'secure'         	=> TRUE, 
					  	'class' 			=> $form_class
					  );  
	
				$output = $this->EE->functions->form_declaration($form_details);

				
				// Check to see if the page is secure and use the right 
				// http(s) protocol
				if(is_secure())
				{
					$output = str_replace("http:","https:",$output);
				}
			}
			
			// Manipulate the cart items array prior to processing
			// Added 1.1.5 dpd 
				if($this->EE->extensions->active_hook('br_cart_parse_before') === TRUE){
					$vars = $this->EE->extensions->call('br_cart_parse_before', $vars); 
				}
			
			$output .= $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars); 
			if(strtolower($show_form) == "yes"){ 
				$output .= form_close();
			}
			return $this->return_data = $output;
		}

		function cart_items()
		{
			$cart = $this->EE->product_model->cart_get();
			$count = 0;
			if(!isset($cart["items"])){
				return $count;
			}else{
				foreach($cart["items"] as $item){
					$count += $item["quantity"];
				}
				return $count;
			}
		}
	
		function cart_add()
		{
			$ajax = $this->EE->input->get('ajax','no');
			
			// We get a post of inputs that are 
			// prepended with the product_id 
			// lets fancy magic it into a usable post array
				foreach($_POST as $key => $val){
					if(strpos($key,"_") === false){
						continue;
					}else{
						$a = explode('_',$key);
						$b = ltrim($key,$a[0].'_');
						if(is_numeric($a[0])){
							$post[$a[0]]['product_id'] = $a[0];
							$post[$a[0]][$b] = $this->EE->input->post($key,TRUE);
						}
					}
				}
		
		// Create an array of products that we have added
			$added = array();
			
		// Now add them
			foreach($post as $data){
				
				if(!isset($data["product_id"])){
					continue;
				}
				
				// Clean the quantity value 
					if(!isset($data["quantity"])) $data["quantity"] = 1;
					
					$data["quantity"] = round($data["quantity"] * 1);
					if($data["quantity"] == 0){
						$data["quantity"] = 1;
					}
				
				$product_id = $data["product_id"];
				$product = $this->EE->product_model->get_products($data["product_id"]);
				
				$amt = $this->_check_product_price($product[0]);
		
				// Set some defaults
					$configurable_id = '';
					$options = '';
					$sku = $product[0]["sku"];
			
				// Configurable product options  		
					if($product[0]["type_id"] == 3){
						$tmp = $this->_get_config_id($data,$product[0]);
						if($tmp == false){
							$_SESSION["br_alert"] = lang('br_product_configuration_required');
							$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["product_url"].'/'.$product[0]["url"]));
							exit();
						}
						$sku = $tmp["sku"];
						$configurable_id = $tmp["configurable_id"];
						
						$adjust = '';
						
						if($tmp["adjust"] != 0){
							if($tmp["adjust"] > 0){
								$dir = '+';
								$amt["price"] = $this->_currency_round(($amt["price"] + $tmp["adjust"]));
							}else{
								$dir = '-';
								$amt["price"] = $this->_currency_round(($amt["price"] - abs($tmp["adjust"])));			
							}
							$adjust = ' ('.$dir.' '.$this->_config["currency_marker"].$this->_currency_round(abs($tmp["adjust"])).')<br />';
						}
						$prod = $this->EE->product_model->get_config_product($configurable_id);
						$attributes = unserialize($prod[0]["attributes"]);
						$tmp = array();
						foreach($attributes as $a){
							$tmp[] = urldecode($a);
						}
						$list = join(" / ",$tmp);
						$options = $list.$adjust;
					}
		
				// Donations 
				if($product[0]["type_id"] == 7){
	
					if($data["donation_price"][0] == 'other'){
						$price = $data["donation_other"]*1;
						if($price < $product[0]["donation"][0]["min_donation"]){
							$price = $product[0]["donation"][0]["min_donation"];
						}
					}else{
						$price = $data["donation_price"][0];
					}
					$price = $this->_currency_round($price);
					$amt["base"]  = $price;
					$amt["price"] = $price;
					$amt["price_html"] = '<p class="price">'.$this->_format_money($price).'</p>';

				}
	
				// Add and adjust for options
					$tmp = '';
					foreach($data as $key => $val){
						if(strpos($key,"cAttribute_option_") !== false && trim($val) != ''){
							$a = explode('_',$key);
							$tmp .= '<h4>'.$product[0]["options"][$a[2]]["title"].':</h4>';
							if($product[0]["options"][$a[2]]["type"] == 'dropdown'){
								$adjust = '';
								$price = $product[0]["options"][$a[2]]["opts"][$val]["price"];
								if($price != 0){
									if($price > 0){
										$dir = '+';
										$amt["price"] = $this->_currency_round(($amt["price"] + $price));
									}else{
										$dir = '-';
										$amt["price"] = $this->_currency_round(($amt["price"] - abs($price)));
									}
									$adjust = ' ('.$dir.' '.$this->_config["currency_marker"].$this->_currency_round(abs($price)).')<br />';
								}	
								$tmp .= $product[0]["options"][$a[2]]["opts"][$val]["title"] . $adjust;
							}else{
								$tmp .= $val.'<br />';
							}
						}
					}
					$options .= $tmp;
				
				if($product[0]["image_large"] == ''){ $product[0]["image_large"] = 'products/noimage.jpg'; }
				if($product[0]["image_thumb"] == ''){ $product[0]["image_thumb"] = 'products/noimage.jpg'; }
				$content = array(
									'product_id'		=> 	$product[0]["product_id"],
									'type_id'			=> 	$product[0]["type_id"],
									'url_title' 		=> 	$product[0]["url"], 
									'sku' 				=> 	$sku,
									'configurable_id' 	=>  $configurable_id,
									'quantity'  		=> 	$data["quantity"], 
									'image_large' 		=> 	$product[0]["image_large"],
									'image_thumb' 		=> 	$product[0]["image_thumb"],
									'price_html' 		=> 	$amt["price_html"],
									'base'   			=> 	$this->_currency_round($amt["base"]),
									'price'   			=> 	$this->_currency_round($amt["price"]),
									'cost' 				=> 	$product[0]["cost"], 
									'discount'			=> 	$this->_discount_amount($amt["price"]), 
									'title'    			=> 	$product[0]["title"],
									'taxable' 			=> 	$product[0]["taxable"], 
									'weight' 			=> 	$product[0]["weight"],  
									'shippable' 		=> 	$product[0]["shippable"],  
									'options' 			=> 	$options,
									'subtotal' 			=> 	$this->_currency_round($amt["price"] * $data["quantity"]) 
					          	);
				$data = array(	'member_id' => $this->EE->session->userdata["member_id"],
								'session_id' => session_id(), 
								'content' => $content,
								'updated' => date("Y-n-d G:i:s"));
				
				// br_product_cartadd_before manipulate $data array
				// ADDED 1.1.5 by dpd
					if($this->EE->extensions->active_hook('br_product_cartadd_before') === TRUE){
						$data = $this->EE->extensions->call('br_product_cartadd_before', $data); 
					}				
					
					// Serialize the content to store it. 
						$data["content"] = serialize($data["content"]);
				
					$data["cart_id"] = $this->EE->product_model->cart_set($data);

				// br_product_cartadd_after
				// ADDED 1.1.5 by dpd
					if($this->EE->extensions->active_hook('br_product_cartadd_after') === TRUE){
						$data = $this->EE->extensions->call('br_product_cartadd_after', $data); 
					}

				$added[] = array(
									'product_id'	=> $product[0]["product_id"],
									'thumbnail'		=> $product[0]["image_thumb"], 
									'title' 		=> $product[0]["title"] 
								);

			} // End Data Loop


			if(isset($_SESSION["discount"])){
				$this->promo_check_code($_SESSION["discount"]["code"]);
				unset($_SESSION["br_message"]);
			}
			
			// Lets do a cart check to see if we can actually combine any of these:
			
				$cart = $this->EE->product_model->cart_get();
				$product_exists = array();
				$combine_count 	= 0; 
				foreach($cart["items"] as $key => $val)
				{
					$config_id = ($val['configurable_id'] != "") ? $val['configurable_id'] : 0;
					
					if(isset($product_exists[$val['product_id']][$config_id]))
					{
						$combine = TRUE;
						if($val["configurable_id"] != $product_exists[$val['product_id']][$config_id]["configurable_id"])
						{
							$combine = FALSE;
						}
						if($val["options"] != $product_exists[$val['product_id']][$config_id]["options"])
						{
							$combine = FALSE;
						}
						if($combine === TRUE)
						{
							// Keep a tally of the number of products combine so we can do an inventory 
							// check later on.
								$combine_count++;
							
							// Set the new product quantity
								$product_exists[$val['product_id']][$config_id]['quantity'] += $val["quantity"];
								$qty[md5($product_exists[$val['product_id']][$config_id]['key'])] = $product_exists[$val['product_id']][$config_id]['quantity'];
								$this->cart_update($qty,FALSE);
							// Unset the current row
								$this->EE->product_model->cart_unset(md5($key));
							// This product no longer exits so lets continue onto the next. 
								continue; 
						}
					}
					$product_exists[$val['product_id']][$config_id] = array(
																			'key'				=> $key, 
																			'quantity' 			=> $val['quantity'],
																			'options' 			=> $val['options'],
																			'configurable_id' 	=> $val['configurable_id']  
																			);
				}
				
				// If there were any combined products then we better check inventory again! 
					if($combine_count >= 1)
					{
						$cart = $this->EE->product_model->cart_get();
						$this->_check_inventory($cart);
					}
					
			// If its an AJAX post then return the cart array:
			if($ajax == 'yes'){
				$cart = $this->EE->product_model->cart_get();
				$return = array(
								'added'			=> $added, 
								'items' 		=> $cart["items"], 
								'subtotal'		=> $this->cart_subtotal(),
								'discount'		=> $this->cart_discount(),
								'total'			=> $this->cart_total()
								);
				exit(json_encode($return));
			}else{
				$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
			}		
		}
	
		function cart_remove()
		{
			$this->EE->product_model->cart_unset($this->EE->input->get('id',TRUE));
			$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
		}
	
		/* Cart Update
		 *
		 * Update the quantity of products in the cart. 
		 */
		function cart_update($qty='',$continue=TRUE)
		{
			// New Quantity 
				if($qty == '')
				{
					$qty = $this->EE->input->post('qty',TRUE);
				}
				$cart = $this->EE->product_model->cart_get();
			
			foreach($cart["items"] as $key => $val){
				if(isset($qty[md5($key)])){
					if(!is_integer($qty[md5($key)])){
						$qty[md5($key)] = round($qty[md5($key)] * 1);
					}
					if($qty[md5($key)] <= 0){
						$this->EE->product_model->cart_unset(md5($key));	
					}else{
						// Update the cart 
							$cart["items"][$key]["quantity"] = $qty[md5($key)];	
							$cart["items"][$key]["subtotal"] = $this->_currency_round(($cart["items"][$key]["price"] * $qty[md5($key)])); 	
							$content = serialize($cart["items"][$key]);
							$data = array(	'member_id' => $this->EE->session->userdata["member_id"],
											'session_id' => session_id(), 
											'content' => $content,
											'updated' => date("Y-n-d G:i:s"));
							$this->EE->product_model->cart_update($data,$key);
					}
				}
			}
			if($continue == TRUE)
			{
				$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
			}
		}
	
		function cart_subtotal()
		{
			$total = 0;
			$cart = $this->EE->product_model->cart_get();
			if(!isset($cart["items"])){
				return 0;
			}else{
				foreach($cart["items"] as $val){
					$total += ($val["quantity"] * $val["price"]);
				}
				return $this->_currency_round($total);
			}
		}
		
		/**
		* Check to see if a discount exits
		* 
		* @access	public
		* @param	void
		* @return	boolean 
		*/
				
		public function cart_has_discount() {
			if(isset($_SESSION["discount"])){
				if($_SESSION["discount"]["code"] != ''){
					return ($this->_get_cart_discount() > 0);
				}
			}
			return FALSE;
		}
		
		function cart_discount()
		{
			$discount = 0;
			if(isset($_SESSION["discount"])){
				if($_SESSION["discount"]["code"] != ''){
					$discount = $this->_get_cart_discount();
					$subtotal = $this->cart_subtotal();
					if($discount > $subtotal){
						$discount = $subtotal;
					}
					$vars[0]['remove_link'] = $action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'promo_check_code')).'&code=remove';
					$vars[0]['subtotal'] 	= $this->_config["currency_marker"].$this->_currency_round($subtotal);
					$vars[0]['discount'] 	= $this->_config["currency_marker"].$this->_currency_round($discount);
					$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
					$this->return_data = $output;
				}
			}
			return $this->return_data;
		}

		function cart_total()
		{
			// Simple function that adds the currency marker to the 
			// cart total. 
			return $this->_format_money($this->_get_cart_total());	
		} 
	
		function cart_related()
		{
			$vars[0] = array('items' => '');
			return '';
		}
	
		function cart_clear()
		{
			$this->EE->product_model->cart_clear();
			$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
		}
	
	/* Wishlist */
	
		/* Wishlist
		 *
		 * Display the wishlist items for the 
		 * current displayed user. 
		 */
			function wishlist(){
				// Load the wishlist model
					$this->EE->load->model('wishlist_model');
				
				// 
					$output = "";
				
				// 
					$member_id = $this->EE->session->userdata["member_id"];

					$wishlist = $this->EE->wishlist_model->wishlist_get($member_id);

				if(count($wishlist) == 0){
					$pattern = "^".LD."no_results".RD."(.*?)".LD."/"."no_results".RD."^s";
					preg_match($pattern,$this->EE->TMPL->tagdata, $matches);
					if(isset($matches[1])){
						return trim($matches[1]);
					}else{
						return '';
					}
				}else{
					
					// Update link
						$action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'wishlist_process'));
						$link = $action.AMP.'action=update';
						
					// Get the results
						$product = array();
						foreach($wishlist as $prod){
							$p = $this->_get_product($prod["product_id"]);
							
							// Cart Add 
								$cart_action = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_add');
							// Wishlist Edit
								$edit_action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'wishlist_process'));
							// Remove link
								$remove_link = $edit_action.AMP.'action=remove'.AMP.'product_id='.$prod["product_id"];

							$product[] = array(	
												'form_open' 	=> '<form action="'.$cart_action.'" method="POST">',
												'form_close' 	=> '</form>',
												'url_title'		=> $p[0]['url'],
												'product_id' 	=> $p[0]['product_id'],
												'title' 		=> $p[0]['title'], 
												'image_thumb'	=> $p[0]["image_thumb"],
												'is_public'		=> $prod["is_public"],
												'notes'			=> str_replace("{","&#123;",$prod["notes"]),
												'remove_link'	=> $remove_link   
											);
						}	
						
						$vars[0] = array(
										'share_hash'		=> $this->EE->wishlist_model->wishlist_get_hash($this->EE->session->userdata["member_id"]), 
										'update_link' 		=> $link,  
										'total_results' 	=> count($product),
										'results' 			=> $product,
										'no_results' 		=> array(),
										'result_filter_set' => ''
										);	
						$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars); 
				}
				return $output;
			}
			
			function wishlist_add(){
				// What is the product id ?
					$product_id = ($this->EE->TMPL->fetch_param('product_id')) ? ($this->EE->TMPL->fetch_param('product_id')) : "#product_id_required" ;
				
				// Which list does it go on (public or private) 
					$status = ($this->EE->TMPL->fetch_param('status')) ? strtolower($this->EE->TMPL->fetch_param('status')) : "private" ;
					$statuses = array('public','private');
					if(!in_array($status,$statuses)){
						$status = 'private';		
					}
					
				// Where we headed after the add?
					$_SESSION["wishlist_add_return"] = ($this->EE->TMPL->fetch_param('return')) ? $this->EE->TMPL->fetch_param('return') : "" ;
				
					$action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'wishlist_process'));
					$output = $action.AMP.'action=add'.AMP.'product_id='.$product_id.'&status='.$status;
				return $output;
			}
			
			function wishlist_remove(){
				$product_id = ($this->EE->TMPL->fetch_param('product_id')) ? ($this->EE->TMPL->fetch_param('product_id')) : "#product_id_required" ;
				$action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'wishlist_process'));
				$output = $action.AMP.'action=remove'.AMP.'product_id='.$product_id;
				return $output;
			}
			
			function wishlist_process(){
	
				// Load the wishlist model
					$this->EE->load->model('wishlist_model');
			
				// What are we doing
					$action = strtolower($this->EE->input->get('action',TRUE));
					$product_id = strtolower($this->EE->input->get('product_id',TRUE));
					$status =  strtolower($this->EE->input->get('status',TRUE));
					
				// Who are we?
					$member_id = $this->EE->session->userdata["member_id"];
					if($member_id == 0){
						$_SESSION["br_alert"] = lang('br_wishlist_login');
						$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["customer_url"].'/login'));
					}
				
				$target = $_SERVER["HTTP_REFERER"];
				if($action == 'add'){
					
					$is_public = ($status == 'public') ? 1 : 0;
					
					// Add it to the wishlist
						if(!$this->EE->wishlist_model->wishlist_add($product_id,$member_id,$is_public)){
							$_SESSION["br_alert"] = lang('br_wishlist_duplicate_error');
						}else{
							$_SESSION["br_message"] = lang('br_wishlist_add');
						}
						if($_SESSION["wishlist_add_return"] != ''){
							$this->EE->functions->redirect($this->EE->functions->create_url($_SESSION["wishlist_add_return"]));
						}
				}elseif($action == 'remove'){
					// Add it to the wishlist
						$this->EE->wishlist_model->wishlist_remove($product_id,$member_id);
						$_SESSION["br_message"] = lang('br_wishlist_remove');
				}elseif($action == 'update'){
					foreach($_POST["product_id"] as $p){
						$public = isset($_POST["is_public"][$p]) ? 1 : 0;
						$data = array(
										'notes' 	=> strip_tags($_POST["notes"][$p]),
										'is_public' => $public 
									);
						$this->EE->wishlist_model->wishlist_update($p,$member_id,$data);
					}
					$_SESSION["br_message"] = lang('br_wishlist_update');
				}
				$this->EE->functions->redirect($target);
			}
			
			public function wishlist_public()
			{
				// Load the wishlist model
					$this->EE->load->model('wishlist_model');

				// Hash is required
					$hash = ($this->EE->TMPL->fetch_param('hash')) ? $this->EE->TMPL->fetch_param('hash') : '';
					$member_id = $this->EE->wishlist_model->wishlist_get_member($hash);
					$wishlist = $this->EE->wishlist_model->wishlist_get($member_id,TRUE);
				
					if(count($wishlist) == 0){
						$pattern = "^".LD."no_results".RD."(.*?)".LD."/"."no_results".RD."^s";
						preg_match($pattern,$this->EE->TMPL->tagdata, $matches);
						if(isset($matches[1])){
							return trim($matches[1]);
						}else{
							return '';
						}
					}
					
					foreach($wishlist as $prod){
						$p = $this->_get_product($prod["product_id"]);
						$p[0]['notes']	= $prod["notes"];
						$product[] = $p[0];
					}

					$action = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_add');
				
				// Set up the parse array
					$vars[0] = array(	'no_results' 	=> array(),
										'results' 		=> $product,
										'form_open' 	=> '<form action="'.$action.'" method="POST">',
										'form_close' 	=> '</form>');
				
				// Finally get the member info 
					$member = $this->EE->customer_model->get_customer_profile($member_id);
					foreach($member as $key => $val){
						$vars[0][$key] = $val;		
					}
					
				$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars); 
				return $output;				
			} 
			
	/* CHECKOUT */

		function checkout_buttons()
		{
			$class = ($this->EE->TMPL->fetch_param('class')) ? ($this->EE->TMPL->fetch_param('class')) : "checkout" ;
			$output = '	<div class="'.$class.'">
		            		<a href="'.$this->_secure_url($this->_config["store"][$this->site_id]["checkout_url"]).'">Checkout</a>
		              	</div>';	
			$output .= $this->_payment_buttons();
			return $output;
		}
		
		function checkout_form()
		{
			// Cart is empty 
				$cnt = $this->cart_items();
				if($cnt == 0){
					$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
				}
			
			// Add a form class?
				$form_class = $this->EE->TMPL->fetch_param('form_class');
				
			// Are we showing our JS by default?
				$show_js = ($this->EE->TMPL->fetch_param('show_js')) ? ($this->EE->TMPL->fetch_param('show_js')) : TRUE;	
			
			// Check the inventory and if stock isn't correct - redirect the user
				$cart = $this->EE->product_model->cart_get();
				$this->_check_inventory($cart);

			// Need to be registered to purchase
				$member_id = $this->EE->session->userdata["member_id"];
				
				if($member_id == 0){
					// Guest checkout is not allowed. 
						if($this->_config["store"][$this->site_id]["guest_checkout"] == 0){
							$_SESSION["br_alert"] = lang('br_please_login_or_create_an_account_to_checkout');
							$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["customer_url"].'/login'));
							exit();
						}
				}

			// Does checkout require shipping?
				$shippable = md5(time().rand(100,50000));
				$_SESSION[$shippable] = 0;
				$cart = $this->EE->product_model->cart_get();
				foreach($cart["items"] as $c){
					$_SESSION[$shippable] += $c["shippable"];
				}
			// Create the checkout form
				// Get the checkout form action
					$action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'checkout'));
					
				// Shipping AJAX URL 
					$shipping_action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'checkout_shipping'));
		
				// Total AJAX URL 
					$total_action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'checkout_total'));
				
				$output = '';
				
				$form_details = array(
						'action'     		=> $action,
	                  	'name'           	=> 'form_checkoutform',
	                  	'id'             	=> "checkoutform",
	                  	'class'          	=> $form_class,
	                  	'hidden_fields'  	=> array('SID' => $shippable), 
	                  	'secure'         	=> TRUE
	                  );

				$form_open 	= $this->EE->functions->form_declaration($form_details);

				
				
				
				$vars[0] = array (
									'shipping_action' 	=> $shipping_action, 
									'total_action' 		=> $total_action, 
									'payment_options' 	=> '<div id="payment_container">&nbsp;</div>',
									'form_open'			=> $form_open 
								);
				
				$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars); 
				
				if($show_js === TRUE){
					$this->EE->load->library('javascript');
					$countries = $this->EE->product_model->get_countries();
					$map = $this->EE->javascript->generate_json($this->EE->product_model->get_states($countries));
					
					// Add the JavaScript to the output cache
						$this->EE->session->cache['br_output_js'] .= "	var ship_to;
																		var ship_note;
																		var cnt = 0;
																		var payment;
																		
																		$(function(){
																			ship_to = $('#ship_same_address');
																			
																			/* Add a timeout to refresh totals so the session stays alive */
																			setInterval('_update_cart_totals()',600000);
																			
																			$('#checkoutform').validate({
																											'ignore' : ':hidden',
																											submitHandler: function(form){
																												if(typeof window.after_validate_checkout == 'function') {
																													after_validate_checkout();
																												}else{
																													$('#checkout_button').attr('disabled', 'disabled');
																											        form.submit();
																												}
																											}
																										}); 
																			
																			
																			// Setup the javascript for the state/country selectors
																				var selects = $('select[data-br_country]'),
																					country_state_map = ".$map.";
																				
																				selects.each(function() {
																					var select = $(this),
																						country = $( '#'+select.data('br_country') );
																					
																					// when the country changes, populate the states
																					// trigger the first change right away to update
																					country.change(function() {
																						var str = '<option value=\"\">".lang('br_select_a_state')."</option>',
																							country = this.options[this.selectedIndex].text;
																						
																						$.each(country_state_map[country], function(k, v) {
																							str += '<option value=\"'+k+'\">'+v+'</option>';
																						});
																						
																						select.empty().append(str);
																						select.val(select.data('br_selected'));
																						
																					}).triggerHandler('change');
																				});
																			
																			
																			_bind_payment_options();
																			
																			$('#ship_same_address').bind('click',function(){
																				var a = $(this);
																				var b = $('#shipping_address');
																				if(a.is(':checked')){
																					b.slideUp();
																				}else{
																					b.slideDown();
																				}
																			});
																			
																			$('input[name=br_billing_address1],input[name=br_shipping_address1],input[name=br_billing_zip],input[name=br_shipping_zip],input[name=br_billing_country],input[name=br_shipping_country],input[name=br_billing_city],input[name=br_shipping_state]').bind('change',function(){
																				var ship_to = $('#ship_same_address');
																				if(ship_to.is(':checked')){
																					_get_shipping_quote('billing');
																				}else{
																					_get_shipping_quote('shipping');	
																				}
																			});
																			
																			if($('#br_billing_zip').val() != ''){
																				_get_shipping_quote('billing');
																			}else{
																				_get_shipping_quote('shipping');	
																			}
																			
																			$('#get_shipping_rates').bind('click',function(){
																				var ship_to = $('#ship_same_address');
																				if(	$('#br_billing_zip').val() == '' || 
																					$('#br_billing_country').val() == '' || 
																					$('#br_billing_state').val() == ''){
																					
																					alert('Please enter your shipping information before calculating rates');
																				
																				}else{
																					if(ship_to.is(':checked')){
																						_get_shipping_quote('billing');
																					}else{
																						_get_shipping_quote('shipping');	
																					}
																				}
																				return false;
																			});
																			
																		});
																	
																		function _bind_payment_options(){
																			$('.gateway').unbind().bind('click',function(){
																				$('.payment_form:visible').hide();
																				var a = $(this).parent().parent();
																				var b = $('.payment_form',a);
																				if(b.html() != ''){
																					b.show();
																				} 
																			});
																		}
																		
																		function _get_shipping_quote(address){
																			var url = '".$shipping_action."';
																			
																			var params = {	
																							'address1': $('input[name=br_'+address+'_address1]').val(),
																							'address2': $('input[name=br_'+address+'_address2]').val(),
																							'city':		$('input[name=br_'+address+'_city]').val(),
																							'state':	$('#br_'+address+'_state').val(),
																							'zip':		$('input[name=br_'+address+'_zip]').val(),
																							'country':	$('#br_'+address+'_country').val()
																						 }
																			var contain = $('#shipping_options div#options');
																			
																			ship_note = contain.html();
																			contain.html('&nbsp;Calculating Rates<br /><img src=\"".$this->_config["media_url"]."images/loading.gif\" />');
																			
																			$.post(url,params,function(data){
																	   			$('#shipping_options div#options').html(data);
																		 		_update_cart_totals();
																	 			$('input.shipping').bind('change',_update_cart_totals);
																	 		});
																	 	}
																	 	
																		function _update_cart_totals(){
																			var url = '".$total_action."';
																			var address = 'shipping';
																			
																			$('#checkout_btn').hide();
																			$('#tax_container,#shipping_container,#total_container').html(' - ');
																			if(ship_to.is(':checked')){
																				address = 'billing';
																			}
																			
																			$.post(	url,	
																						{
																							'address1': $('input[name=br_'+address+'_address1]').val(),
																							'address2': $('input[name=br_'+address+'_address2]').val(),
																							'city':		$('input[name=br_'+address+'_city]').val(),
																							'state':	$('#br_'+address+'_state').val(),
																							'zip':		$('input[name=br_'+address+'_zip]').val(),
																							'country':	$('#br_'+address+'_country').val(), 
																							'shipping':	$('input.shipping:checked').val()
																						},
																						function(returndata){
																							var data = $.parseJSON(returndata);
																							if(payment != data[0].payment){
																								payment = data[0].payment;
																								$('#payment_container').html(payment);
																								_bind_payment_options();
																							}
																							$('#tax_container').html(data[0].marker+data[0].tax);
																							$('#shipping_container').html(data[0].marker+data[0].shipping);
																							$('#total_container').html(data[0].marker+data[0].total);
																							// Only show the button if we have shipping options
																							if($('#shipping_options_available').val() == 1){
																								$('#checkout_btn').show();						
																							}
																							_checkout_callback(data[0]);
																						});
																		}

																		// Adding a beta js callback for doing something with the data after the 
																			function _checkout_callback(data){
																				if(typeof window.after_update_totals == 'function') {
																					after_update_totals(data);
																				}
																			}";
				}
				
				// Clear any form errors from session if they exist
				if (isset($_SESSION['br_form_errors']))
					unset($_SESSION['br_form_errors']);

				return $output;
		}
		
		function checkout()
		{
				$this->EE->load->model('customer_model');
				$this->EE->load->model('order_model');	

			// For order email 
				$has_donation 		= FALSE;
				$has_item 			= FALSE;
				
			// Some Defaults
				$member_id = $this->EE->session->userdata["member_id"];
				$email = $this->EE->session->userdata["email"];
			
			
			// Create the default container for all shipping fields 
			// in case the form ommits one of the required fields 
				$shipping_fields = array("br_fname","br_lname","br_billing_fname","br_billing_lname","br_billing_company","br_billing_address1","br_billing_address2","br_billing_city","br_billing_state","br_billing_zip","br_billing_country","br_billing_phone","br_shipping_fname","br_shipping_lname","br_shipping_company","br_shipping_address1","br_shipping_address2","br_shipping_city","br_shipping_state","br_shipping_zip","br_shipping_country","br_shipping_phone");
				foreach($shipping_fields as $f){
					$data[$f] = '';
				}
				
				foreach($_POST as $key => $val){
					$data[$key] = $this->EE->input->post($key,TRUE);
				}
				
				// If we don't have br_fname/lname then try to set them on the billing fname/lname 
					if($data["br_fname"] == ''){ $data["br_fname"] = $data["br_billing_fname"]; }
					if($data["br_lname"] == ''){ $data["br_lname"] = $data["br_billing_lname"]; }
				
				// Minimum required fields
					$required_fields = array(
												'br_fname'           	=> lang('br_fname'), 
												'br_lname'            	=> lang('br_lname'),
												'email'               	=> lang('br_email'),
												'br_billing_fname'    	=> lang('br_billing_fname'),
												'br_billing_lname'    	=> lang('br_billing_lname'),
												'br_billing_phone'    	=> lang('br_billing_phone'),
												'br_billing_address1' 	=> lang('br_billing_address1'),
												'br_billing_city'     	=> lang('br_billing_city'),
												'br_billing_zip'      	=> lang('br_billing_zip'),
												'br_billing_country'  	=> lang('br_billing_country')
											);
					
					// If the member is logged in we already have their email so we 
					// don't need to require it
						if($member_id != 0) 
							unset($required_fields['email']);
						
				// Do we need to require the shipping fields, too?
				$ship_same_address = (isset($data['ship_same_address']) && $data['ship_same_address'] != '');

				// Save this one for later
				$_SESSION['br_ship_same_address'] = $ship_same_address;

				if (!$ship_same_address)
				{
					// Add additional fields
					$required_fields = array_merge( $required_fields, 
						array(
							'br_shipping_fname'    => lang('br_shipping_fname'),
							'br_shipping_lname'    => lang('br_shipping_lname'),
							'br_shipping_address1' => lang('br_shipping_address1'),
							'br_shipping_city'     => lang('br_shipping_city'),
							'br_shipping_zip'      => lang('br_shipping_zip'),
							'br_shipping_country'  => lang('br_shipping_country'),
							'br_shipping_phone'    => lang('br_shipping_phone')
						) );
				}

				// Should we validate the State
					$country = (isset($data['br_billing_country'])) ? $data['br_billing_country'] : '';
					$validate_state = (strcasecmp($country, "US") == 0 || strcasecmp($country, "United States") == 0 || strcasecmp($country, "CA") == 0 || strcasecmp($country, "Canada") == 0);
					if($validate_state) 
					{
						array_merge( $required_fields, 
							array('br_billing_state' => lang('br_billing_state'))
						);

						if (!$ship_same_address)
						{
							array_merge( $required_fields, 
								array('br_shipping_state' => lang('br_shipping_state'))
							);
						}
					}


				// Let's do some validation...
				try {

					// Create new arrays on each request to hold form info
					$_SESSION['br_form_errors'] = array();
					$_SESSION['br_form_post_data'] = array();

					// Load our data into a temporary session store so we can redisplay it on error
					foreach($data as $key => $val)
						$_SESSION['br_form_post_data'][$key] = $this->EE->input->post($key);

					// ---------------------------------------------------
					// Handle required fields

					$missing_fields = array();

					foreach ($required_fields as $field => $name) 
						if (! isset($data[$field]) || trim($data[$field]) == '')
						{
							$_SESSION['br_form_errors'][$field] = lang('br_this_field_is_required');
							$missing_fields[] = $name;
						}
				
					if (count($missing_fields))
						throw new Exception(lang('br_the_following_fields_are_required')." ".implode(', ', $missing_fields));

					// ---------------------------------------------------
					// Well-formedness checks

					$validation_errors = array();

					// Handle email validity
					if($member_id == 0){
						$this->EE->load->library('email_validation');
						if (isset($data['confirm_email']) && $data['email'] != $data['confirm_email'])
						{
							$_SESSION['br_form_errors']['email'] = 'The email confirmation you entered doesn\'t match the email you entered';
							$_SESSION['br_form_errors']['confirm_email'] = 'The email confirmation you entered doesn\'t match the email you entered';
							$validation_errors[] = "The email confirmation you entered doesn't match the email you entered.";
						}
	
						if (!$this->EE->email_validation->check_email_address(trim($data['email'])))
						{
							$_SESSION['br_form_errors']['email'] = 'This doesn\'t look like a valid email. Try again or contact us for help placing your order';
							$validation_errors[] = "The email you entered doesn't appear to be valid.";
						}
					}

					// US-specific validation for shipping
					if (!$ship_same_address && $data['br_shipping_country'] == 'US') 
					{
	
						// Handle shipping phone validity
						if (! preg_match( "/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/", $data['br_shipping_phone'] ))
						{
							$_SESSION['br_form_errors']['br_shipping_phone'] = 'This doesn\'t look like a valid phone number. Try again or contact us for help placing your order';
							$validation_errors[] = "The shipping phone number you entered doesn't appear to be valid.";
						}

						// Handle shipping zip validity
						if (! preg_match("/^([0-9]{5})(-[0-9]{4})?$/", $data['br_shipping_zip'])) 
						{
							$_SESSION['br_form_errors']['br_shipping_zip'] = 'This doesn\'t look like a valid US zip code. Try again or contact us for help placing your order';
							$validation_errors[] = "The shipping zip code you entered doesn't appear to be valid.";
						}
					}

					if (count($validation_errors))
						throw new Exception("You'll need to fix these errors before you can complete your checkout: <ul><li>".implode('</li><li>', $validation_errors) . "</li></ul>");


				} catch (Exception $e) {
					
						//Caught an exception? Error out.
						$_SESSION["br_alert"] = $e->getMessage();
						$this->EE->functions->redirect($this->_secure_url($this->_config["store"][$this->site_id]["checkout_url"]));
						exit();

				}

			// Shipping Rate
				// We have a problem
					if(!isset($_POST["SID"])){
						$_SESSION["br_alert"] = lang('br_checkout_shipping_error');
						$this->EE->functions->redirect($this->_secure_url($this->_config["store"][$this->site_id]["checkout_url"]));
						exit();
					}
				// Check if shipping is even necessary
					$sid = $_POST["SID"];
					$shippable = isset($_SESSION[$sid]) ? $_SESSION[$sid] : 0;
					if($shippable == 0 && !isset($data["shipping"])){
						$data["shipping"] = 'free';
						$_SESSION["shipping"]['free'] = array(
																	"code" 	=> "N/A",
																	"rate" 	=> "0.00",
																	"label" => "N/A", 
																	"method" => "N/A"
																);
					}
				
					if($shippable > 0 && !isset($data["shipping"])){
						$_SESSION["br_alert"] = lang('br_checkout_shipping_error');
						$this->EE->functions->redirect($this->_secure_url($this->_config["store"][$this->site_id]["checkout_url"]));
						exit();
					}

					$data["cart_shipping"] = 0;
					if(isset($_SESSION["shipping"][$data["shipping"]]["rate"])){
						$data["cart_shipping"] = $_SESSION["shipping"][$data["shipping"]]["rate"];
					}
					
					if($data["cart_shipping"] == ''){
						$data["cart_shipping"] = 0;
					}
			
			// Set the shipping automatically
				if(isset($data["ship_same_address"])){
					unset($data["ship_same_address"]);
					$data["br_shipping_fname"] 		= $data["br_billing_fname"];
					$data["br_shipping_lname"] 		= $data["br_billing_lname"];
					$data["br_shipping_company"] 	= $data["br_billing_company"];
					$data["br_shipping_phone"] 		= $data["br_billing_phone"];
					$data["br_shipping_address1"] 	= $data["br_billing_address1"];
					$data["br_shipping_address2"] 	= $data["br_billing_address2"];
					$data["br_shipping_country"] 	= $data["br_billing_country"];
					$data["br_shipping_city"] 		= $data["br_billing_city"];
					$data["br_shipping_zip"] 		= $data["br_billing_zip"];
					$data["br_shipping_state"] 		= $data["br_billing_state"];
				}
			
			if($this->EE->extensions->active_hook('br_order_validate_after') === TRUE){
				$data = $this->EE->extensions->call('br_order_validate_after', $data); 
			}

			// End Shipping stuff 

			if($member_id == ''){
				if($mem = $this->EE->customer_model->get_customer_by_email($data["email"])){
					// user isn't logged in but they do have an account
						$email 		= $mem[0]["email"];
						$member_id 	= $mem[0]["member_id"];
				}else{
					// No matching email. Create customer
						$email = $data["email"];
						$group_id = $this->_config["store"][$this->site_id]["register_group"];
						$password = strtolower(substr(md5(time()),0,8));
						$member_id = $this->EE->customer_model->create_customer($data,$password,$group_id);
						$eml[0] = array(
											"fname" 	=> $data["br_fname"],
											"lname" 	=> $data["br_lname"],
											"email" 	=> $data["email"],
											"password" 	=> $password,
											"username" 	=> $data["email"], 
											"join_date" => $this->EE->localize->now 
										);
						// Call the member_member_register hook 
							$edata = $this->EE->extensions->call('member_member_register', $eml[0], $member_id);
							if ($this->EE->extensions->end_script === TRUE) return;

						// Send the email notice
						// Added a suppress flag in version 1.1.0.1						
							if($this->EE->config->item('br_suppress_new_account_email') !== TRUE)
							{
								$this->_send_email('customer-account-new', $eml);
							}
				}
			}
			
			$data["cart"] 				= $this->EE->product_model->cart_get();
			$data["cart_coupon_code"] 	= isset($_SESSION["discount"]["code"]) ? $_SESSION["discount"]["code"] : '';
			$data["cart_tax"] 			= $this->_get_cart_tax(	
																	$data["br_shipping_country"],
																	$data["br_shipping_state"],
																	$data["br_shipping_zip"],
																	$data["br_shipping_address1"],
																	$data["br_shipping_address2"],
																	$data["br_shipping_city"],
																	$data["cart_shipping"]
																);
			$data["cart_subtotal"] 		= $this->cart_subtotal();
			$data["cart_discount"] 		= $this->_get_cart_discount();
			$data["cart_total"] 		= $this->_get_cart_total();
			$data["order_total"] 		= ($data["cart_total"] + $data["cart_tax"] + $data["cart_shipping"]);

			// Get Custom Fields
				$tmp = $this->EE->customer_model->_get_custom_fields();
				foreach($tmp as $key => $val){
					$fields[$val] = $key;				
				}
			// Get Member  
				$member = $this->EE->customer_model->get_customer_profile($member_id);
				
				foreach($member as $key => $val){
					if(substr($key,0,3) == 'br_'){
						if(trim($val) == ''){
							// Exceptions for the first few parameters
								if($key == 'br_fname' && $member[$key] == '') $data[$key] = $data["br_billing_fname"];
								if($key == 'br_lname' && $member[$key] == '') $data[$key] = $data["br_billing_lname"];
								if($key == 'br_phone' && $member[$key] == '') $data[$key] = $data["br_billing_phone"];
							$update[$fields[$key]] = $data[$key];
						} 
					}
				}
				if(isset($update)){
					$this->EE->customer_model->update_member_profile('',$update,$member_id);
				}
				
			// Process the payment
				$data["transaction_id"] 	= md5(time().rand(1000,1000000).time());
				$data["email"] 				= $email;
				$data["member_id"] 			= $member_id;
 				$data["payment"] 			= $this->_process_payment($data);
				
				if(isset($data["payment"]["error"])){
					$_SESSION["br_alert"] = $data["payment"]["error"];
					$this->EE->functions->redirect($this->_secure_url($this->_config["store"][$this->site_id]["checkout_url"]));
				}
				
			// Create the order 
				$order = array (
					"site_id" 		=> $this->EE->session->userdata["site_id"], 
					"member_id" 	=> $member_id, 
					"status_id" 	=> $data["payment"]["status"],
					"base" 			=> $this->_currency_round($data["cart_subtotal"]),
					"tax" 			=> $this->_currency_round($data["cart_tax"]),
					"shipping" 		=> $this->_currency_round($data["cart_shipping"]), 
					"total" 		=> $this->_currency_round($data["cart_total"]),
					"discount" 		=> $this->_currency_round($data["cart_discount"]), 
					"cart_id"	 	=> $data["cart"]["cart_id"], 
					"merchant_id" 	=> $data["transaction_id"],  
					"coupon_code" 	=> $data["cart_coupon_code"], 
					"created" 		=> time()
				); 
			
				// Hook before we create the order
					
					// Only fire the hook on direct payment orders
						if($order["status_id"] != -1){
							if($this->EE->extensions->active_hook('br_order_create_before') === TRUE){
								$order = $this->EE->extensions->call('br_order_create_before', $order); 
							}
						}

					$order_id = $this->EE->order_model->create_order($order);

				// Order is a success, destory old form field values
					if (isset($_SESSION['br_form_post_data']))
						unset($_SESSION['br_form_post_data']);

			// Now that the order has been created lets create a shipment if 
			// a shipment is necessary	
				
				if(isset($_SESSION["shipping"][$data["shipping"]]["rate"])){
					$_SESSION["shipping"][$data["shipping"]]["order_id"] = $order_id;
					$this->EE->order_model->create_shipment($_SESSION["shipping"][$data["shipping"]]);
				}
				
				// Address 
					$address[0] = array(	
										"order_id" 			=> $order_id,
										"billing_fname" 	=> $data["br_billing_fname"],
										"billing_lname" 	=> $data["br_billing_lname"],
										"billing_company" 	=> $data["br_billing_company"],
										"billing_phone" 	=> $data["br_billing_phone"],
										"billing_address1" 	=> $data["br_billing_address1"],
										"billing_address2" 	=> $data["br_billing_address2"],
										"billing_city" 		=> $data["br_billing_city"],
										"billing_state" 	=> $data["br_billing_state"],
										"billing_zip" 		=> $data["br_billing_zip"], 
										"billing_country" 	=> $data["br_billing_country"],
										"shipping_fname" 	=> $data["br_shipping_fname"],
										"shipping_lname" 	=> $data["br_shipping_lname"],
										"shipping_company" 	=> $data["br_shipping_company"],
										"shipping_phone" 	=> $data["br_shipping_phone"],
										"shipping_address1" => $data["br_shipping_address1"],
										"shipping_address2" => $data["br_shipping_address2"],
										"shipping_state" 	=> $data["br_shipping_state"],
										"shipping_zip" 		=> $data["br_shipping_zip"],
										"shipping_city" 	=> $data["br_shipping_city"],
										"shipping_country" 	=> $data["br_shipping_country"] 
									);
					$this->EE->order_model->create_order_address($address[0]);

				// Add the payment info to the database now that we have
				// have an order_id for the item. 
					$payment[0] = array(
											'order_id' => $order_id, 
											'transaction_id' => $data["payment"]["transaction_id"],
											'payment_type' => $data["payment"]["payment_type"],
											'details' => $data["payment"]["details"],
											'amount' => $this->_currency_round($data["payment"]["amount"]),
											'approval' => $data["payment"]["approval"],
											'created' => $this->EE->localize->now
										);
					$this->EE->order_model->create_order_payment($payment[0]);
				
				// Add items to order
					$i = 0;
					foreach($data["cart"]["items"] as $items){
						
						if($items["type_id"] == 7)
						{
							$has_donation = TRUE;
						}
						else
						{
							$has_item = TRUE;
						}

						$item = array(
											'order_id' 			=> $order_id, 
											'product_id' 		=> $items["product_id"],
											'configurable_id' 	=> $items["configurable_id"],  
											'base' 				=> $this->_currency_round($items["base"]),
											'price' 			=> $this->_currency_round($items["price"]),
											'cost' 				=> $this->_currency_round($items["cost"]), 
											'discount' 			=> $this->_currency_round($items["discount"]),
											'quantity' 			=> $items["quantity"],
											'status' 			=> 1,
											'title' 			=> $items["title"],
											'taxable' 			=> $items["taxable"],
											'weight' 			=> $items["weight"],
											'shippable' 		=> $items["shippable"],
											'url' 				=> $items["url_title"],
											'sku' 				=> $items["sku"],
											'options' 			=> $items["options"],
										);
										
						$this->EE->order_model->create_order_item($item);
						
						// We need to create downloadable product links
						// If its a downloadable product or if its a bundle
						// containing a downloadable product

							if(	$items["type_id"] == 4 || $items["type_id"] == 2){
								// Create a file container 
									$file = array();

								// A download purchase is easy just go ahead and add it 
									if($items["type_id"] == 4){
										$file[] = $item;
									}else{
										$bundle = $this->EE->product_model->get_product_bundle($item["product_id"]);	
										foreach($bundle as $b){
											if($b["type_id"] == 4){
												$file[] = array(
																'quantity' 		=> $items["quantity"], 
																'order_id' 		=> $order_id,
																'product_id' 	=> $b["product_id"]
																);
											}
										}
									}
									foreach($file as $f){
										// Get the file info 
											$dl = $this->EE->order_model->_get_download_file($f);
											// Generate a uuid as the license key...
											unset($dl["title"]);
											unset($dl["filenm_orig"]);
											unset($dl["filenm"]);
											unset($dl["created"]);
											
										// Need to loop through based on item count
											for($j=0;$j<$f["quantity"];$j++){
												// Insert into the db 
												$dl["license"] = uuid();
												$dl["member_id"] = $member_id;
												// Hook to modify the $dl array prior to creating the order download record
												if($this->EE->extensions->active_hook('br_license_create_after') === TRUE){
													$dl = $this->EE->extensions->call('br_license_create_after', $dl); 
												}
												
												$this->EE->order_model->create_order_download($dl);
											}
											unset($dl);
									}
							}
						
						// Only reduce inventory on direct sales
							if($order["status_id"] >= 1){
								// Reduce the item inventory 
								$this->EE->order_model->reduce_item_inventory($item);
								remove_from_cache('product_'.$items["product_id"]);
							}
						
						// Setup the items array for the notification email
							$order_items[$i] 				= $item;
			               	$order_items[$i]["title"] 		= $items["title"];
							$order_items[$i]["url_title"] 	= $items["url_title"]; 
			               	$order_items[$i]["sku"] 		= $items["sku"];
			               	$order_items[$i]["image_large"] = $items["image_large"];
			               	$order_items[$i]["image_thumb"] = str_replace('products/','products/thumb/',$items["image_thumb"]);
			               	$order_items[$i]["price_html"] 	= $items["price_html"];
			               	$order_items[$i]["price"] 		= $this->_currency_round($items["price"]);
			               	$order_items[$i]["options"] 	= $items["options"];
			               	$order_items[$i]["subtotal"] 	= $this->_currency_round(($items["price"]*$items["quantity"]));
			               	$i++;
					}
											
				// Add a note to the order
					if(isset($data["instructions"])){
						if(trim($data["instructions"]) != ''){
							$arr = array(
								'order_id' => $order_id,
								'order_note' => $data["instructions"],
								'created' => time(),
								'member_id' => $member_id  
							);
							$this->EE->order_model->create_order_note($arr);
						}
					}				
				
				// Finish up successful direct payments
				
					if($order["status_id"] != -1){
					
						// Send the email! 
							
							$vars[0] = array(
												"fname" 			=> $data["br_fname"],
												"lname" 			=> $data["br_lname"],
												"email" 			=> $email, 
												"address" 			=> $address,
												"payment" 			=> $payment,
												"order_id" 			=> $order_id, 
												"order_num" 		=> $order_id, 
												"order_note" 		=> $data["instructions"], 
												"delivery_method" 	=> $_SESSION["shipping"][$data["shipping"]]["method"], 
												"delivery_label" 	=> $_SESSION["shipping"][$data["shipping"]]["label"], 
												"items" 			=> $order_items, 
												"order_subtotal" 	=> $this->_currency_round($data["cart_subtotal"]), 
												"discount_total" 	=> $this->_currency_round($data["cart_discount"]), 
												"tax_total" 		=> $this->_currency_round($data["cart_tax"]), 
												"shipping" 			=> $this->_currency_round($data["cart_shipping"]), 
												"order_total" 		=> $this->_currency_round($data["order_total"]),
												"has_item"			=> $has_item, 
												"has_donation"		=> $has_donation
											);
						if($this->EE->config->item('br_suppress_new_order_email') !== TRUE) {
							$this->_send_email('customer-order-new', $vars);
						}
							
						// Hook after we create the order before cleanup 
							$data["order_id"] = $order_id;
							if($this->EE->extensions->active_hook('br_order_create_after') === TRUE){
								$data = $this->EE->extensions->call('br_order_create_after', $data); 
							}
						// Clear out the cart!
							$this->EE->product_model->cart_clear();
							unset($_SESSION["discount"]);
					
						// Clear out the cache 
							$this->EE->functions->clear_caching('db');
		
						// Redirect to account				
							$_SESSION["order_id"] = $order_id;
							$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["thankyou_url"]));
							exit();		
					
					}else{
						
				// Deal with the IPN
						// Clear out the cart!
							$this->EE->product_model->cart_update_status(session_id(),2);
							unset($_SESSION["discount"]);
					
						// Clear out the cache 
							$this->EE->functions->clear_caching('db');
						
							$data["order_id"] = $order_id;	
							$data["email"] = $email;
						
						// Get the gateway id
							$code = $this->EE->order_model->_get_gateway($data["gateway"]); 
							$gid = $this->_config["gateway"][$this->site_id][$code]["config_id"];

						// build urls 
							$data["return"] 		= $this->EE->functions->fetch_site_index(0,0);
							$data["sagepay_return"] = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->core_model->get_aid('Brilliant_retail','gateway_ipn').'&GID='.$gid.'&tid='.$data['transaction_id']; 
							$data["cancel_return"] 	= $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->core_model->get_aid('Brilliant_retail', 'gateway_ipn').'&GID='.$gid.'&cancel=true'; 
							$data["notify_url"] 	= $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->core_model->get_aid('Brilliant_retail','gateway_ipn').'&GID='.$gid;

						// Fire ways
							$config = array();
							if(isset($this->_config["gateway"][$this->site_id][$code]["config_data"])){
								$config_data = $this->_config["gateway"][$this->site_id][$code]["config_data"]; 
								foreach($config_data as $c){
									$config[$c["code"]] = $c["value"];
								}
							}
							
						// We need to fire the process_payment_before hook so we can do any calculations 
						// before sending the user on to the third party payment processor. 
						// Added 1.3.1 - DPD
							if($this->EE->extensions->active_hook('br_process_payment_before') === TRUE){
								$data = $this->EE->extensions->call('br_process_payment_before', $data); 
							}
						
						// Start the IPN
						
							$str = 'Gateway_'.$code;
							$ipn = new $str();
							$ipn->start_ipn($data,$config);		
						
						exit();
					}
		}

		function checkout_shipping()
		{
			$cart 		= $this->EE->product_model->cart_get();
			$weight 	= 0;
			$shippable 	= 0;
			$opts		= '';
			
			foreach($cart["items"] as $key => $val){
				$weight += $cart["items"][$key]["weight"]*$cart["items"][$key]["quantity"];
				$shippable += $cart["items"][$key]["shippable"];
			}
			
			if($shippable == 0){
				$hash = md5(time().rand(100,1000).time()); 
				$_SESSION["shipping"][$hash] = array(
														"code" 	=> "N/A",
														"rate" 	=> "0.00",
														"label" => "N/A", 
														"method" => "N/A"
													);
				
				$vars[0]["shipping_id"]		= "";
				$vars[0]["shipping_label"] 	= "";
				$vars[0]["rates"][] 		= array(
														"rate"			=>	"",
														"rate_id"		=> 	"shipping_0", 
														"rate_label"	=> 	lang('br_no_shipping_required'),
														"rate_value" 	=> 	$hash,
														"rate_checked"	=> 	'checked="checked"' 
													);
			
				// Get the snippet data
					$snippetdata = $this->vars["snippets"]["br_shipping_layout"];
					$tmp = $this->EE->TMPL->parse_variables($snippetdata, $vars);
					$this->EE->TMPL->parse($tmp);
				
				$opts .= $tmp;
				$shipping_options_available = 1;
			
			}else{
			
				$data = array(
							"to_zip" 		=> $this->EE->input->post('zip',TRUE),
							"to_state" 		=> $this->EE->input->post('state',TRUE),
							"to_country" 	=> $this->EE->input->post('country',TRUE),
							"weight" 		=> $weight, 
							"total" 		=> $this->_get_cart_total() 
						);
				$opts .= $this->_shipping_options($data);
				if($opts == ''){
					$opts .= lang('br_no_shipping_options_available');
					$shipping_options_available = 0;
				}else{
					$shipping_options_available = 1;
				}
			}
			$opts .= '<input type="hidden" name="shipping_options_available" id="shipping_options_available" value="'.$shipping_options_available.'" />';
			echo $opts;
			exit();
		}
		
		function checkout_thankyou($vars = '')
		{
			if(!isset($_SESSION["order_id"])){
				$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["customer_url"]));
			}
			$this->EE->load->model('order_model');
			$order[0] = $this->EE->order_model->get_order($_SESSION["order_id"]);
			$order[0]["currency_marker"] = $this->_config["currency_marker"];
			
			// Put together the actual total
				$order[0]["total"] = $this->_currency_round($order[0]["base"]+$order[0]["tax"]+$order[0]["shipping"]-$order[0]["discount"]);
				
			$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $order); 
			unset($_SESSION["order_id"]);
			return $output;
		}
		
		function checkout_total()
		{
			$address1 	= $this->EE->input->post("address1",TRUE);
			$address2 	= $this->EE->input->post("address2",TRUE);
			$city		= $this->EE->input->post("city",TRUE);
			$state 		= $this->EE->input->post("state",TRUE);
			$zip 		= $this->EE->input->post("zip",TRUE);
			$country 	= $this->EE->input->post("country",TRUE);
			$shipping 	= $this->EE->input->post("shipping",TRUE);
			
			// Calculate Shipping 			
				$hash 			= $this->EE->input->post("shipping",TRUE);
				$rate 			= isset($_SESSION["shipping"][$hash]["rate"]) ? $_SESSION["shipping"][$hash]["rate"] : 0;
				$shipping 		= ($rate > 0) ? $rate : 0;
				$shipping_rate 	= $this->_currency_round($shipping);
			// Calculate Tax
				$tax 			= $this->_get_cart_tax($country,$state,$zip,$address1,$address2,$city,$shipping_rate);
				$tax_rate		= ($tax > 0) ? $this->_currency_round($tax) : $this->_currency_round(0) ;
			// Calculate Total 
				$sub_total 		= $this->cart_subtotal();
				$discount 		= $this->_get_cart_discount();
				$total_rate 	= $this->_currency_round(($tax + $rate + $sub_total - $discount));
			
			$arr = array(	
							"marker" 	=> '',
							"tax" 		=> $this->_format_money($tax_rate),
							"shipping" 	=> $this->_format_money($shipping_rate),
							"total" 	=> $this->_format_money($total_rate),
							"payment"	=> $this->_payment_options(true,$tax,$shipping),
							"address"	=> array(
													'address1'	=> $address1,
													'address2'	=> $address2,
													'city'		=> $city,
													'state'		=> $state, 
													'zip'		=> $zip,
													'country'	=> $country 	
												)
						);
							
			// Check the 
				if($this->EE->extensions->active_hook('br_checkout_total_after') === TRUE){
					$arr = $this->EE->extensions->call('br_checkout_total_after', $arr);
				}

			echo "[".json_encode($arr)."]";
			exit();
		}
		
		// Additional Checkout tags
		
			function form_error() 
			{
				$field = $this->EE->TMPL->fetch_param('for');
				$return = $this->EE->TMPL->fetch_param('return');
				
				if (! $field || 
						$field == '' || 
						! isset($_SESSION['br_form_errors']) || 
						! isset($_SESSION['br_form_errors'][$field]) )
					return '';
		
		
				if ($return)
					return $_SESSION['br_form_errors'][$field];
				else
					return 'y';
		
			}
	
			function ship_same_address() 
			{
				
				if (isset($_SESSION['br_ship_same_address']))
					return ($_SESSION['br_ship_same_address'] ? 'y' : '');
			}

		function form_value() 
		{
	
			$field = $this->EE->TMPL->fetch_param('for');
			
			if (! $field || 
					$field == '' || 
					! isset($_SESSION['br_form_post_data']) || 
					! isset($_SESSION['br_form_post_data'][$field]) )
				return '';
	
			return $_SESSION['br_form_post_data'][$field];
	
		}

		
		
		function states()
		{
			// Set the parameters
				$name = $this->EE->TMPL->fetch_param('name');
				$id =  $this->EE->TMPL->fetch_param('id');
				$class =  $this->EE->TMPL->fetch_param('class');
				$value =  $this->EE->TMPL->fetch_param('value');
				$country_select = $this->EE->TMPL->fetch_param('country_select'); // optional
			
			// first get the countries
				$countries = $this->EE->product_model->get_countries();

			// now get the states
				$states = $this->EE->product_model->get_states($countries, (bool) $country_select);
				
			// filter out countries without states
				if (count($states)){
					$states = array_filter($states, 'count');
				}
				
				$attr = 'id="'.$id.'" class="'.$class.'"';
				if ($country_select){
					$attr .= ' data-br_selected="'.$value.'" data-br_country="'.$country_select.'"';
				}
				
				$this->EE->load->helper('form');
				$output = form_dropdown($name, $states, $value, $attr);

			return $output;
		}

		function countries()
		{
			// Set the parameters
				$name = $this->EE->TMPL->fetch_param('name');
				$id =  $this->EE->TMPL->fetch_param('id');
				$class =  $this->EE->TMPL->fetch_param('class');
				$value =  $this->EE->TMPL->fetch_param('value');

			$countries = $this->EE->product_model->get_countries();
			$output =  '<select name="'.$name.'" id="'.$id.'" class="'.$class.'">';

			foreach($countries as $key => $val){
				$sel = ($key == $value) ? 'selected="selected"' : '' ;
				$output .=	'<option value="'.$key.'" class="{zone_id:'.$val["zone_id"].'}" '.$sel.'>'.$val["title"].'</option>';
			} 			
			$output .= '</select>';
			return $output;
		}
		
		function country_state_map()
		{
			// The template parser deals with tags by depth. Since this tag
			// will usually not be nested it would likely be run too early.
			// To avoid this, we push it back onto the template until all tags
			// that write to the cache have been called.
			if (strpos($this->EE->TMPL->template, '{exp:brilliant_retail:states') !== FALSE){
				return '{exp:brilliant_retail:country_state_map}';
			}
			
			$this->EE->load->library('javascript');
			return $this->EE->javascript->generate_json($this->EE->product_model->_get_cache('js_state_map'), TRUE);
		}
		
		function gateway_3dauth($postData)
		{
			// This is so that we know what Gateway ID we need
			$output = $this->EE->load->view('gateway/3dauth', $postData, TRUE);	
			return $output;	
		}		
		
		function gateway_ipn()
		{
			$gid = $this->EE->input->get('GID',TRUE);
			foreach($this->_config["gateway"][$this->site_id] as $gateway){
				if($gateway["config_id"] == $gid){
					foreach($gateway["config_data"] as $c){
							$config[$c["code"]] = $c["value"];
					}
					$str = 'Gateway_'.$gateway["code"];
					$tmp = new $str();
					$tmp->gateway_ipn($config);
				}
			}
			exit();
		}
		
		function process_ipn()
		{
			$gid = $this->EE->input->get('GID',TRUE);
			foreach($this->_config["gateway"][$this->site_id] as $gateway){
				if($gateway["config_id"] == $gid){
					// Prep the config code 
						$config["config_id"] = $gid;
						$config["ipn_url"] = rtrim($this->_secure_url(''),'/').QUERY_MARKER.'ACT='.$this->EE->core_model->get_aid('Brilliant_retail', 'gateway_ipn').'&GID='.$gid;
						foreach($gateway["config_data"] as $c){
							$config[$c["code"]] = $c["value"];
						}
					$str = 'Gateway_'.$gateway["code"];
					$tmp = new $str();
					$tmp->process('',$config);
				}
			}
			exit();
			// update the cart items 
			// get the url from the ipn
			// and go
		}
		
	/* END CHECKOUT */
	
	/* CUSTOMER */	
		
	// Create a register form
		function register_form()
		{
			$id 		= ($this->EE->TMPL->fetch_param('id')) ? ($this->EE->TMPL->fetch_param('id')) : "register_form" ;
			$action 	= $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'customer_register'));
			$form_class = $this->EE->TMPL->fetch_param('form_class','');

			$tagdata = $this->EE->TMPL->tagdata;

			// Should we add a captcha to the form?
			
			if (preg_match("/{if captcha}(.+?){\/if}/s", $tagdata, $match)){
				if ($this->EE->config->item('use_membership_captcha') == 'y'){
					$tagdata = preg_replace("/{if captcha}.+?{\/if}/s", $match['1'], $tagdata);
	
					// Bug fix.  Deprecate this later..
					$tagdata = str_replace('{captcha_word}', '', $tagdata);
	
					if ( ! class_exists('Template'))
					{
						$tagdata = preg_replace("/{captcha}/", $this->EE->functions->create_captcha(), $tagdata);
					}
				}
				else
				{
					$tagdata = preg_replace("/{if captcha}.+?{\/if}/s", "", $tagdata); 
				}
			}
			
			$form_details = array(
									'action'	   		=> $action,
								  	'id'             	=> $id,
								  	'secure'         	=> TRUE, 
								  	'class' 			=> $form_class
								  );  

			$output = $this->EE->functions->form_declaration($form_details);
			$output .= $tagdata;
			$output .= form_close();
			return $output;
		}
		
	// Register a customer		
		function customer_register()
		{
			// Load security helper for password hash
				#$this->EE->load->helper('security');
			
			// Verify the security hash 
				$xid = $this->EE->input->post('XID');
				$check = $this->EE->security->secure_forms_check($xid);
				if(!$check)
				{
					$_SESSION["br_alert"] = lang('br_invalid_form_id');
					$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["customer_url"].'/login'));
				}
				else
				{
					$this->EE->security->delete_xid($xid);
				}

			$this->EE->load->model('customer_model');	

			// Clean up the post 
				foreach($_POST as $key => $val){
					$data[$key] = trim($this->EE->input->post($key,TRUE));
				}

			// Check for existing user
				if($this->EE->customer_model->get_customer_by_email($data["email"])){
					$_SESSION["br_alert"] = lang('br_customer_exists');
					$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
				}
			
			// Make sure we have our required fields			
				$required_fields = array(
							'br_fname'           	=> lang('br_fname'), 
							'br_lname'            	=> lang('br_lname'),
							'email'               	=> lang('br_email'),
							'password'				=> lang('password'),
							'confirm_password'		=> lang('confirm_password')
							);
			
			// Create new arrays on each request to hold form info
					$_SESSION['br_form_errors'] = array();
					$_SESSION['br_form_post_data'] = array();

					// Load our data into a temporary session store so we can redisplay it on error
					foreach($_POST as $key => $val)
						$_SESSION['br_form_post_data'][$key] = $this->EE->input->post($key);


					// ---------------------------------------------------
					// Handle required fields
					
					$missing_fields = array();

					foreach ($required_fields as $field => $name) 
					if (! isset($data[$field]) || trim($data[$field]) == '')
					{
						$_SESSION['br_form_errors'][$field] = lang('br_this_field_is_required');
						$missing_fields[] = $name;
					}
				
					if (count($missing_fields)){
						$_SESSION["br_alert"] = lang('br_the_following_fields_are_required')." ".implode(', ', $missing_fields);
						$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["customer_url"].'/login'));
						exit();
					}
					
			// Do we require captcha?
				if ($this->EE->config->item('use_membership_captcha') == 'y')
				{
					$query = $this->EE->db->query("SELECT COUNT(*) AS count FROM exp_captcha WHERE word='".$this->EE->db->escape_str($data['captcha'])."' 
													AND ip_address = '".$this->EE->input->ip_address()."' 
													AND date > UNIX_TIMESTAMP()-7200");
					if ($query->row('count')  == 0)
					{
						$_SESSION["br_alert"] = lang('captcha_incorrect');
						$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["customer_url"].'/login'));
						exit();
					}
					$this->EE->db->query("DELETE FROM exp_captcha WHERE (word='".$this->EE->db->escape_str($data['captcha'])."' AND ip_address = '".$this->EE->input->ip_address()."') OR date < UNIX_TIMESTAMP()-7200");
				}

				$new['group_id'] 		= $this->_config["store"][$this->site_id]["register_group"];
				$new['username']		= $data['email'];
				$new['password']		= do_hash($data['password']);
				$new['ip_address']  	= $this->EE->input->ip_address();
				$new['unique_id']		= $this->EE->functions->random('encrypt');
				$new['join_date']		= $this->EE->localize->now;
				$new['email']			= $data['email'];
				$new['screen_name'] 	= ucwords($data['br_fname']).ucwords($data["br_lname"]);
				$new['url']		 		= isset($data["url"]) ? prep_url($data["url"]) : '' ;
				$new['location']	 	= isset($data["location"]) ? $data["location"] : '' ;
				$new['language']	= ($this->EE->config->item('deft_lang')) ? $this->EE->config->item('deft_lang') : 'english';
				$new['time_format'] = ($this->EE->config->item('time_format')) ? $this->EE->config->item('time_format') : 'us';
				$new['timezone']	= ($this->EE->config->item('default_site_timezone') && $this->EE->config->item('default_site_timezone') != '') ? $this->EE->config->item('default_site_timezone') : $this->EE->config->item('server_timezone');
				
				// Format for email 
					$data["fname"] = $data["br_fname"];
					$data["lname"] = $data["br_lname"];
	
				// Custom Fields
					$tmp = $this->EE->customer_model->_get_custom_fields();			
					foreach($tmp as $key => $val){
						$custom_fields[$val] = $key;
					}
					
					$member_data = array(
											$custom_fields["br_fname"] => $data["br_fname"],
											$custom_fields["br_lname"] => $data["br_lname"]
										);
					
					unset($data["br_fname"]);
					unset($data["br_lname"]);
					unset($data["confirm_password"]);
				
				$str = $this->EE->db->insert_string('members', $new);
				$this->EE->db->query($str);
				
				// Get the member ID 
					$member_id = $this->EE->db->insert_id();

				// If we are using the new auth library we need to 
				// resave the password 
					if(version_compare(APP_VER, '2.2', '<')){
						# Validate password post EE v.2.2
							// Load the Auth module
								$this->EE->load->library('Auth');
								$this->EE->auth->update_password($member_id,$data['password']);
					}

				// Call the member_member_register hook 
					if($this->EE->extensions->active_hook('member_member_register') === TRUE){
						$edata = $this->EE->extensions->call('member_member_register', $new, $member_id);
						if ($this->EE->extensions->end_script === TRUE) return;
					}
					
				// Send the email notification
					$vars[0] = $data;
					$this->_send_email('customer-account-new', $vars);
				
				// Insert custom fields
					$member_data['member_id'] = $member_id;
					$str = $this->EE->db->insert_string('member_data', $member_data);
					$this->EE->db->query($str);
				
				$_SESSION["br_message"] = lang('br_sign_up_thankyou');
				$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
			}
		
	// Create password reset form
		function customer_pw_form()
		{
			// Form ID 
			$id = $this->EE->TMPL->fetch_param('id','password_edit');
			$action = $this->EE->functions->fetch_site_index(0,0);
			if(is_secure())
			{
				$action = str_replace("http://","https://",$action);
			}
			$tagdata = $this->EE->TMPL->tagdata;
			
			$form_details = array(
						'action'     		=> $action,
	                  	'name'           	=> $id,
	                  	'id'             	=> $id,
	                  	'hidden_fields'  	=> array(
													"ACT" 		=> $this->EE->functions->fetch_action_id('Brilliant_retail', 'customer_pw_update'),
													"username" 	=> $this->EE->session->userdata['username'] 
													), 
	                  	'secure'         	=> TRUE
	                  );
			
			$output = $this->EE->functions->form_declaration($form_details);
			$output .= $tagdata;
			$output .= form_close();
			return $output;
		}

	// Update customer password
		function customer_pw_update()
		{
			// Load security helper for password hash
				#$this->EE->load->helper('security');

			// Validate that we got everything
				if(	(!isset($_POST["password"]) || trim($_POST["password"]) == '') ||
					(!isset($_POST["new_password"]) || trim($_POST["new_password"]) == '') || 
					(!isset($_POST["new_password_confirm"]) || trim($_POST["new_password_confirm"]) == '')){
					$_SESSION["br_alert"] = lang('br_password_fields_required');
					$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
				}
			
				if(version_compare(APP_VER, '2.2', '<')){
					# Validate password pre EE v.2.2
						$current_password = do_hash(stripslashes($this->EE->input->post('password',TRUE)));
						$data['password'] = do_hash(stripslashes($this->EE->input->post('new_password',TRUE)));
			
						$sql = $this->EE->db->update_string('exp_members',$data,"member_id = '".$this->EE->session->userdata('member_id')."' and password = '".$current_password."'");
						$this->EE->db->query($sql);
						if($this->EE->db->affected_rows() == 1){
							$_SESSION["br_message"] = lang('br_password_update_success');
							$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
						}else{
							$_SESSION["br_alert"] = lang('br_password_current_invalid');
							$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);					
						}	
				}else{
					# Validate password post EE v.2.2
						// Load the Auth module
							$this->EE->load->library('Auth');
	
							$member_id = $this->EE->session->userdata('member_id');
							$current_password = $this->EE->input->post('password',TRUE);
						
						// Validate the current email
							$validate = $this->EE->auth->authenticate_id($member_id,$current_password);
							
							if(!$validate){
								$_SESSION["br_alert"] = lang('br_password_current_invalid');
								$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
							}else{
								$rows = $this->EE->auth->update_password($member_id, $this->EE->input->post('new_password',TRUE));
								$_SESSION["br_message"] = lang('br_password_update_success');
								$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
							}	
				}
		}
			
		function customer_profile()
		{

			$form_class = $this->EE->TMPL->fetch_param('form_class');

			// By default we look for the logged in user 
			// but we can also passs the member_id param
				$member_id 	= ($this->EE->TMPL->fetch_param('member_id')) ? ($this->EE->TMPL->fetch_param('member_id')) : $this->EE->session->userdata["member_id"];
				
				// We don't have a member to get info for 
					if($member_id == 0){return '';}
				
				
				$this->EE->load->model('customer_model');
				$member = $this->EE->customer_model->get_customer_profile($member_id);
				if($member){
					$vars[0] = $member;
					$action = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'customer_profile_update');
					if(is_secure())
					{
						$action = str_replace("http://","https://",$action);
					}
					$vars[0]["form_open"] = form_open($action,array('id'=>'profile_edit',
																	'form_class' => $form_class
																	));
					$vars[0]["form_close"] = '</form>';
					$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
				
					// Are we showing our JS by default?
						$show_js = ($this->EE->TMPL->fetch_param('show_js')) ? ($this->EE->TMPL->fetch_param('show_js')) : TRUE;	

					if($show_js === TRUE){
						$this->EE->load->library('javascript');
						$countries = $this->EE->product_model->get_countries();
						$map = $this->EE->javascript->generate_json($this->EE->product_model->get_states($countries));
						$this->EE->session->cache['br_output_js'] .= "	$(function(){
																			$('#profile_edit').validate();
																			
																			// get all tied selects
																			var selects = $('select[data-br_country]'),
																				country_state_map = ".$map.";
																			
																			selects.each(function() {
																				var select = $(this),
																					country = $( '#'+select.data('br_country') );
																				
																				// when the country changes, populate the states
																				// trigger the first change right away to update
																				country.change(function() {
																					var str = '',
																						country = this.options[this.selectedIndex].text;
																					
																					$.each(country_state_map[country], function(k, v) {
																						str += '<option value=\"'+k+'\">'+v+'</option>';
																					});
																					
																					select.empty().append(str);
																					select.val(select.data('br_selected'));
																					
																				}).triggerHandler('change');
																			});
																		});";
					}
				
				
					$this->return_data = $output;
				}
			return $this->return_data;
		}
		
		function customer_profile_update()
		{
			$this->EE->load->model('customer_model');
			
			$member = '';
			$custom = '';
			$member_fields = array("username","screen_name","email","url","location","occupation","interests","bday_d","bday_m","bday_y","aol_im","yahoo_im","msn_im","icq","bio","signature","avatar_filename","avatar_width","avatar_height","photo_filename","photo_width","photo_height","sig_img_filename","sig_img_width","sig_img_height","private_messages","accept_messages","last_view_bulletins","last_bulletin_date","ip_address","join_date","last_visit","last_activity","total_entries","total_comments","total_forum_topics","total_forum_posts","last_entry_date","last_comment_date","last_forum_post_date","last_email_date","in_authorlist","accept_admin_email","accept_user_email","notify_by_default","notify_of_pm","display_avatars","display_signatures","parse_smileys","smart_notifications","language","timezone","localization_is_site_default","time_format","profile_theme","forum_theme");
			// Get the custom fields and reverse them 
				$tmp = $this->EE->customer_model->_get_custom_fields();			
				foreach($tmp as $key => $val){
					$custom_fields[$val] = $key;
				}

			// Run through the posts to see if we 
			foreach($_POST as $key => $val){
				if(in_array($key,$member_fields)){
					$member[$key] = $this->EE->input->post($key,TRUE);
				}elseif(isset($custom_fields[$key])){
					$custom[$custom_fields[$key]] = $this->EE->input->post($key,TRUE);
				}
			}
			
			// Update the members details
				$member_id = $this->EE->session->userdata["member_id"];
				if($this->EE->customer_model->update_member_profile($member,$custom,$member_id)){
					$_SESSION["br_message"] = lang('br_profile_updated_successfully');
					$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
				}else{
					$_SESSION["br_alert"] = lang('br_profile_update_error');
					$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
				}
		}
		
		function customer_orders()
		{
			$member_id = $this->EE->session->userdata["member_id"];
			if($member_id == ''){
				return '';
			}
			$this->EE->load->model('order_model');
			$order_id = $this->EE->TMPL->fetch_param('order_id');
			$orders = $this->EE->order_model->get_order_by_member($member_id,$order_id);
			
			$limit = 1 * ($this->EE->TMPL->fetch_param('limit'));
			
			rsort($orders);
			if($limit > 0){
				$cap = count($orders);
				for($i=$limit;$i<$cap;$i++){
					unset($orders[$i]);
				}
			}

			$cnt = count($orders);
			for($i=0;$i<$cnt;$i++){

				// Set the 'has notes' variable
					$orders[$i]["has_notes"] = (count($orders[$i]["notes"]) == 0) ? FALSE : TRUE ;

				// Need to adjust the total to include the 
				// shipping, tax and discount
					$orders[$i]["total"] = $this->_currency_round($orders[$i]["base"]+$orders[$i]["tax"]+$orders[$i]["shipping"]-$orders[$i]["discount"]);
				// Set the status
					$orders[$i]["status"] = $this->_config["status"][$orders[$i]["status_id"]];
				// add a row count
					$orders[$i]["row_count"] = $i+1;
			}
			$vars[0] = array(	'currency_marker'	=>$this->_config["currency_marker"],
								'total_orders'		=> count($orders),
								'orders'			=>$orders);
			
			$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
			$this->return_data = $output;
			return $this->return_data;
		}
		
		function customer_downloads()
		{
			$this->EE->load->model('order_model');
			$downloads = $this->EE->order_model->get_downloads_by_member(
				$this->EE->session->userdata["member_id"],
				'',
				$this->EE->TMPL->fetch_param('order_id')
			);

			$limit = 1 * ($this->EE->TMPL->fetch_param('limit'));
			if($limit > 0){
				$cap = count($downloads);
				for($i=$limit;$i<$cap;$i++){
					unset($downloads[$i]);
				}
			}
			for($i=0;$i<count($downloads);$i++){
				
				// Set the not for the download to be prefixed and unset;
					$downloads[$i]['download_note'] = $downloads[$i]['note'];
					unset($downloads[$i]['note']);

				// Create the note save action
					$downloads[$i]['download_note_action'] =  $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'customer_download_note').'&lic='.md5($downloads[$i]["license"]).'&id='.md5($downloads[$i]["order_download_id"]);

				if($downloads[$i]["download_limit"] == 0){
					$downloads[$i]["download_remaining"] = lang('br_download_unlimited');
				}else{
					$downloads[$i]["download_remaining"] = $downloads[$i]["download_limit"] - $downloads[$i]["cnt"];
					// If we reached the limit then lets go ahead and make things as complete. 
						if($downloads[$i]["download_remaining"] < 1)
						{
							$downloads[$i]["download_status_id"] 	= 0;
							$downloads[$i]["download_link"] 		= lang('br_download_link_unavailable');
							$downloads[$i]["download_status"] 		= lang('br_download_complete');
						}
				}

				// If we didn't set the download_status in the 'limit' section 
				// lets go ahead and take a look at it now. 
					if(!isset($downloads[$i]["download_status"]))
					{
						$downloads[$i]["order_status"] = $this->_config["status"][$downloads[$i]["status_id"]];
						if($downloads[$i]["status_id"] <= 2){
							$downloads[$i]["download_status_id"] 	= 0;
							$downloads[$i]["download_link"] 		= lang('br_download_link_unavailable');
							$downloads[$i]["download_status"] 		= lang('br_download_pending');
						}else{
							$downloads[$i]["download_status_id"] = 1;
							$action_id = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'customer_download_file');
							$link = $action_id.'&id='.md5($downloads[$i]["order_download_id"]);
							$downloads[$i]["download_link"] = $link;
							$downloads[$i]["download_status"] = lang('br_download_available');
						}
					}
			}
			$vars[0] = array('downloads'=>$downloads);
			$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
			$this->return_data = $output;
			return $this->return_data;
		}
		
		function customer_download_file()
		{
			// Get hash from url
				$hash = $this->EE->input->get('id',TRUE);
			// Check availability 
				$this->EE->load->model('order_model');
				
				$member_id = $this->EE->session->userdata["member_id"];
				if($member_id == 0){
					$_SESSION["br_alert"] = lang('br_download_unavailable');
					$this->EE->functions->redirect($this->EE->functions->create_url(''));
				}
				
				$downloads = $this->EE->order_model->get_downloads_by_member($member_id,$hash);	
				if(count($downloads) != 1){
					$_SESSION["br_alert"] = lang('br_download_unavailable');
					$this->EE->functions->redirect($this->EE->functions->create_url(''));
				}
				// Is the status not right?
					if($downloads[0]["download_limit"] >= 1){
						if(($downloads[0]["download_limit"] - $downloads[0]["cnt"]) <= 0){
							$_SESSION["br_alert"] = lang('br_download_unavailable');
							$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
						}
					}
				if($downloads[0]["status_id"] <= 2){
					$_SESSION["br_alert"] = lang('br_download_unavailable');
					$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
				}
				
				// Download the file
					$downloads[0]["cnt"]++;
					$data = array('cnt' => $downloads[0]["cnt"]);
					$this->EE->order_model->update_downloads_by_member($this->EE->session->userdata["member_id"],$downloads[0]["order_download_id"],$data);
					
				// Hook to modify the $downloads array prior to forcing the download	
				// added 1.1.5 dpd 
					if($this->EE->extensions->active_hook('br_customer_download_file_before') === TRUE){
						$downloads = $this->EE->extensions->call('br_customer_download_file_before', $downloads); 
					}
				
				// Is it local or S#?
					if($downloads[0]["download_source"] == 'S3')
					{
						$a = explode(' / ',$downloads[0]["filenm"]);
						$this->EE->load->library('aws');
						$this->EE->aws->AWSAccessKeyId 	= $this->_config["store"][$this->site_id]["downlaods_s3_access_key"];
						$this->EE->aws->AWSSecretKey	= $this->_config["store"][$this->site_id]["downlaods_s3_secret_key"];
						$dl_link = $this->EE->aws->createUrl($a[0],$a[1],$length=10);
						header('location:'.$dl_link);
					}else{
						// Load up the download helper and get the file
							$this->EE->load->helper('my_download');
							$path = $this->_config["media_dir"].'download/'.$downloads[0]["filenm"];
							$name = $downloads[0]["filenm_orig"];
							force_download($name, $path);
					}
					exit;	
		}

		function customer_download_note(){
			$license 	= $this->EE->input->get('lic');
			$id 		= $this->EE->input->get('id');
			$note 		= $this->EE->input->post('note');
			$ajax 		= $this->EE->input->post('ajax');
			
			$data = array('note' => $note);
			
			$this->EE->db->where('md5(order_download_id)',$id)
						->where('md5(license)',$license)
						->update('br_order_download',$data);
			
			if($ajax == TRUE){
				return true;exit;
			}else{
				$_SESSION["br_message"] = lang('br_download_note_updated');
				$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
			}
		}

	/* END CUSTOMER */

		function promo_check_code($inputCode='')
		{
			$this->EE->load->model('promo_model');
			
			if($inputCode == ''){
				$inputCode = $this->EE->input->post('code',TRUE);
			}
			
			// Check if there is a promo code in the url
				$getCode = $this->EE->input->get('code',TRUE);
				if($getCode != ''){
					$inputCode = $getCode;
				}
			
			if($inputCode == 'remove'){
				$cart = $this->EE->product_model->cart_get();
				foreach($cart["items"] as $key => $val){
					$cart["items"][$key]["discount"] = 0;
					$content = serialize($cart["items"][$key]);
					$data = array(	'member_id' => $this->EE->session->userdata["member_id"],
									'session_id' => session_id(), 
									'content' => $content,
									'updated' => date("Y-n-d G:i:s"));
					$this->EE->product_model->cart_update($data,$key);
				}
				$_SESSION["br_alert"] = lang('br_discount_removed');
				unset($_SESSION["discount"]);
				$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
			}
			$code = $this->EE->promo_model->get_promo_by_code($inputCode);
			if($code){
				$valid = $this->_validate_promo_code($code[0]);
			}else{
				$valid = false;
			}
			if($valid == true){
				$_SESSION["br_message"] = $code[0]["descr"];
				$_SESSION["discount"] = $code[0];
				$this->promo_check_items();
			}else{
				$_SESSION["br_alert"] = str_replace("%s",$inputCode,lang('br_discount_invalid'));
				unset($_SESSION["discount"]);
			}
			$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
		}
	
		function promo_check_items()
		{
			$cart = $this->EE->product_model->cart_get();
			$initial = true; // Initial checks some settings against the whole cart. 
			
			// Does the subtotal meet the minimum requirement
				$subtotal = $this->cart_subtotal();
				if($_SESSION["discount"]["min_subtotal"] > $subtotal){
					$initial = false;
				}
	
			// Are there enough items in the cart to match 
			// the request?
				$cnt = $this->cart_items();
				if($_SESSION["discount"]["min_quantity"] > $cnt){
					$initial = false;
				}

			// Is there a category restriction		
				if($_SESSION["discount"]["category_list"] != ''){
					$c = json_decode($_SESSION["discount"]["category_list"]);
					foreach($c as $row){
						$cats[$row] = $row;
					}
				}
	
			// Is there a product list restriction
				if($_SESSION["discount"]["product_list"] != ''){
					$p = json_decode($_SESSION["discount"]["product_list"]);
					foreach($p as $row){
						$prods[$row] = $row;
					}
				}
			
			// For item based discounts check the items in the cart
				if($_SESSION["discount"]["discount_type"] == 'item'){
					foreach($cart["items"] as $key => $val){
						if($initial == true){
							$discount = true;
							// Check category list
								if(isset($cats)){
									$discount = false;
									$cList = $this->EE->product_model->get_product_categories($val["product_id"]);
									foreach($cList as $c){
										if(in_array($c,$cats)){
											$discount = true;
											break;
										}
									}
								}
							// Check against product list			
								if(isset($prods)){
									if(!isset($prods[$val["product_id"]])){
										$discount = false;
									}
								}
						}else{
							$discount = false;	
						}
						if($discount == true){
							$cart["items"][$key]["discount"] = $this->_discount_amount($cart["items"][$key]["price"]);
						}else{
							$cart["items"][$key]["discount"] = 0;
						}
						$content = serialize($cart["items"][$key]);
						$data = array(	'member_id' => $this->EE->session->userdata["member_id"],
										'session_id' => session_id(), 
										'content' => $content,
										'updated' => date("Y-n-d G:i:s"));
						$this->EE->product_model->cart_update($data,$key);
					}
				}	
		}
	
		function promo_form()
		{
			$form = ($this->EE->TMPL->fetch_param('form')) ? $this->EE->TMPL->fetch_param('form') : 'yes';
			$output = "";
			if($form == 'yes'){
				$action = $this->_secure_url(QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'promo_check_code'));
				$output = form_open($action);
			}
			$code = isset($_SESSION["discount"]["code"]) ? $_SESSION["discount"]["code"] : '';
			$vars[0] = array('coupon_code' => $code);
			$output .= $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars); 
			if($form == 'yes'){
				$output .= form_close();
			}
			return $output;
		}

		function search_form()
		{
			$action 	= $this->EE->TMPL->fetch_param('action','search');
			$name 		= $this->EE->TMPL->fetch_param('form_name','search_form');
			$form_id 	= $this->EE->TMPL->fetch_param('form_id','search_form');
			$form_class = $this->EE->TMPL->fetch_param('form_class','search_form');

			$form_details = array(
							'action'		=> $action, 
							'name'			=> $name,
						  	'id'			=> $form_id, 
						  	'class'			=> $form_class
						  );  	
		
			$form = $this->EE->functions->form_declaration($form_details);
			return $form.$this->EE->TMPL->tagdata.form_close();
		
		}
		
		function search()
		{
			// Load native EE helper to sanitize search term
				$this->EE->load->helper('search');
		
			// Set the return location 
				$return 	= ($this->EE->TMPL->fetch_param('return')) ? ($this->EE->TMPL->fetch_param('return')) : 'catalog/result';
			
			// Get the product search collection
				$term = ($this->EE->TMPL->fetch_param('term')) ? $this->EE->TMPL->fetch_param('term') : $this->EE->input->post('search', TRUE);
				
				$term = sanitize_search_terms($term);
				
				$hits = $this->_search_index($term);
				$hash = sha1(time().$term);
				$i=0;
				$product = array();
				foreach($hits as $hit){
					$tmp =  $this->EE->product_model->get_products($hit->product_id);
					// Check to make sure that a product is returned 
						if(isset($tmp[0])){
							if($tmp[0]["site_id"] == $this->site_id){
								$product[$i] = $tmp[0];
								$product[$i]["score"] = round(100*$hit->score,2);
								$product[$i]["row_count"] = ($i +1);
								$i++;
							}
						}
				}
				
				// Count the products but set 
				// a reasonable search result 
				// limit
				$count = count($product);
				
				if($count > $this->_config["result_limit"]){
					$lim = $count - 1;
					for($i=$this->_config["result_limit"];$i<=$count;$i++){
						unset($product[$i]);
					}
					$count = $this->_config["result_limit"];
				}
				$vars[0] = array(
								'search_hash' => $hash, 
								'search_term' => $term,
								'total_results' => count($product),
								'results' => $product,
								'no_results' => array(),
								'result_filter_set' => ''
								);
		
				save_to_cache('search_'.$hash,serialize($vars));
		
				$this->EE->product_model->log_search($term,$hash,count($product),$this->EE->session->userdata["member_id"]);
				$this->EE->functions->redirect($this->EE->functions->create_url($return.'/id/'.$hash));
		}

		function results()
		{
			// Get the parameters for the form open tag
				$ajax 		= $this->EE->TMPL->fetch_param('ajax','no');
				$form_class = $this->EE->TMPL->fetch_param('form_class','');

			$url = $this->EE->uri->uri_to_assoc();
			$hash = $url["id"];
			if($str=read_from_cache('search_'.$hash)){
				$vars = unserialize($str);
			}else{
				$this->EE->functions->redirect($this->EE->functions->fetch_site_index(0,0));
			}
			// If there are no results then we'll return the 
			// the no result tag 
	
				if($vars[0]["total_results"] == 0){
					$no_result = true;
				}else{
					// We have results but lets filter them
					// and make sure we don't end up with no 
					// results
						$url = $this->EE->uri->uri_to_assoc();
						$hash = $url["id"];
						$vars = $this->_filter_results($vars,$hash);
					
						if($vars[0]["total_results"] == 0){
							$no_result = true;
						}else{
							$no_result = false;
						}
				}
				
			// Do the output
				if($no_result == true){
					$key = 'no_results';
					preg_match("^".LD.$key.RD."(.*?)".LD."/".$key.RD."^s",$this->EE->TMPL->tagdata, $matches);
					$result[0] = array(0 => trim($matches[1]));
					$output = $this->EE->TMPL->parse_variables($matches[1], $vars); 
				}else{
					
					$action = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Brilliant_retail', 'cart_add');
					if($ajax == 'yes')
					{
						$action .= '&ajax=yes';
					}

					$tmp = array();
					$i = 0;
					foreach($vars[0]["results"] as $v){
						$p = $this->_get_product($v["product_id"]);
						$tmp[$i] = $p[0];
						
						$tmp[$i]["product_count"] = $i+1;
						
						// Setup the form open tag
						$form_details = array(
												'action'		=> $action,
												'name'			=> 'form_'.$tmp[$i]["product_id"],
												'id'			=> 'form_'.$tmp[$i]["product_id"], 
												'class'			=> $form_class,
												'hidden_fields'	=> array(
																			$tmp[$i]["product_id"]."_product_id" 	=> $tmp[$i]["product_id"]
																		) 
											);  	

						$tmp[$i]["form_open"] 	= $this->EE->functions->form_declaration($form_details);
						$tmp[$i]["form_close"] 	= '</form>';
						$i++;
					}
					unset($vars[0]["results"]);
					$vars[0]["results"] = $tmp;
					$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars); 
				}		
			
			$this->switch_cnt = 0;
			$output = preg_replace_callback('/'.LD.'product_switch\s*=\s*([\'\"])([^\1]+)\1'.RD.'/sU', array(&$this, '_parse_switch'), $output);
			return $this->return_data = $output;
	
			return $output; 
		}
		
		function results_layered()
		{
			$url = $this->EE->uri->uri_to_assoc();
			$hash = $url["id"];
			if($str=read_from_cache('search_'.$hash)){
				$vars = unserialize($str);
			}else{
				$this->EE->functions->redirect($this->EE->functions->fetch_site_index(0,0));
			}
			$url = $this->EE->uri->uri_to_assoc();
			$hash = $url["id"];
			$vars = $this->_filter_results($vars,$hash,false);
			$layered = $this->_layered_navigation($vars[0]["results"],$hash);
			$output = '';
			if(count($layered) >= 1){
				$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $layered);
			}
			return $output;
		}
	
		function forgot_password()
		{
			$name 		= $this->EE->TMPL->fetch_param('name','password_form');
			$return 	= $this->EE->TMPL->fetch_param('return','customer/password_reset');
			$form_id 	= $this->EE->TMPL->fetch_param('form_id','password_form');
			$form_class = $this->EE->TMPL->fetch_param('form_class','password_form');
			$length		= $this->EE->TMPL->fetch_param('length',3600);
			$secure		= $this->EE->TMPL->fetch_param('secure','y');
			
			// We didn't have a standard for form class/id mechanisms. Need backward compatibility for old 'class' parameter
				$class 	= $this->EE->TMPL->fetch_param('form_class');
				if($class != ''){
					$form_class = $class;
				}
			
			// Get the action_id for the post
				$act 		= $this->EE->functions->fetch_action_id('Brilliant_retail', 'retrieve_password');
			
			$form_details = array(
									'action'		=> $this->_secure_url("?ACT=".$act),
								  	'name'			=> $name,
								  	'id'			=> $form_id, 
								  	'class'			=> $form_class,
								  	'hidden_fields'	=> array(
								  								'return' 	=> $return, 
								  								'length'	=> (int) $length, 
								  								'secure'	=> $secure
								  							) 
								  );  	
	
			$form = $this->EE->functions->form_declaration($form_details);
			return $form.$this->EE->TMPL->tagdata.form_close();
	    }

		function retrieve_password()
		{
	    	if (version_compare(APP_VER, '2.6.0', '>='))
			{
				// Load email validation library
					$this->EE->load->library('email_validation');
					$this->EE->load->library('Auth');
	
				// Get the inputs
					$email 	= $this->EE->input->post('email',TRUE);
					$email 	= trim($email);
					$secure = $this->EE->input->post('secure','y');
					$length = $this->EE->input->post('length',3600);
					$return = $this->EE->input->post('return','customer/password');
					
				// Validate that we have a valid email
					if($email == ''){
						$_SESSION["br_alert"] = lang('br_password_reset_email_required');
					}else if(!$this->EE->email_validation->check_email_address($email)){
						$_SESSION["br_alert"] = lang('br_password_reset_email_invalid');
					}else{
						// Make sure the email exists in the system
							$member = $this->EE->db->get_where('members', array('email' => $email));
							if($member->num_rows() != 1){
								$_SESSION["br_alert"] = lang('br_password_reset_email_not_found');
							}else{
								
								$m = $member->result_array();
								
								// Clean out any previous tokens
									$this->EE->db->delete('br_password_reset',array('member_id' => $m[0]["member_id"]));
								
								// Create the token 
									$customer = $this->EE->customer_model->get_customer_profile($m[0]["member_id"]);
									
									$token = md5(time().$m[0]["member_id"].rand(1,10000000));
									
									$data = array(
														'member_id' => $m[0]["member_id"],
														'token'		=> $token, 
														'created'	=> time(),
														'ip'		=> md5($_SERVER["REMOTE_ADDR"]),
														'secure'	=> $secure, 
														'length'	=> $length
													);
									
									$this->EE->db->insert('br_password_reset',$data);

								// Send the email
									$eml[0] = array(
														"link"		=> $this->EE->functions->create_url($return.'/'.$token),
														"email"		=> $email, 
														"fname" 	=> $customer["br_fname"],
														"lname" 	=> $customer["br_lname"]
													); 

									$this->_send_email('customer-password-reset', $eml);

									
								// Set the system message
									$_SESSION["br_message"] = lang('br_password_reset_email_sent');
							}
					}
				
				// Send them on back				
					$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
			}
			else
			{
		    	if ( ! class_exists('Member')){
		    		require PATH_MOD.'member/mod.member.php';
		    	}
		    	$member = new Member();
		    	foreach(get_object_vars($this) as $key => $value){
					$member->{$key} = $value;
				}
		        if ( isset($_POST['email'])){
					$query = $this->EE->db->query("SELECT language FROM exp_members WHERE email ='".$this->EE->db->escape_str($_POST['email'])."'");
					
					if ($query->num_rows() > 0)
					{
						$this->EE->session->userdata['language'] = $query->row('language');
					}
				}
		    	$member->retrieve_password();
			}
		}
		
		/** 
		* 
		*/
		
		function forgot_password_reset()
		{
			$name 		= $this->EE->TMPL->fetch_param('name','password_form');
			$return 	= $this->EE->TMPL->fetch_param('return','customer/login');
			$form_id 	= $this->EE->TMPL->fetch_param('form_id','password_form');
			$form_class = $this->EE->TMPL->fetch_param('form_class','password_form');
			$token		= $this->EE->TMPL->fetch_param('token',$this->EE->uri->segment(3));
			
			// Make sure we have a valid token 
				$reset 		= $this->EE->db->get_where('br_password_reset', array('token' => $token));
			
			// If no results then lets bail out. 
				if($reset->num_rows() != 1){
					$_SESSION["br_alert"] = lang('br_password_invalid_token');
					$this->EE->functions->redirect($this->EE->functions->create_url($return));
				}
			
			// We have a valid token lets do some time / security checks 
				$r = $reset->result_array();
				if((time() - $r[0]["created"]) >= $r[0]["length"])
				{
					$_SESSION["br_alert"] = lang('br_password_invalid_token');
					$this->EE->functions->redirect($this->EE->functions->create_url($return));
				}
				
				if($r[0]["secure"] == 'y')
				{
					if(md5($_SERVER["REMOTE_ADDR"]) != $r[0]["ip"]){
						$_SESSION["br_alert"] = lang('br_password_invalid_token');
						$this->EE->functions->redirect($this->EE->functions->create_url($return));
					}
				}
			
			
			// Get the url to actually reset the password
				$act = $this->EE->functions->fetch_action_id('Brilliant_retail', 'reset_password');
			
				$form_details = array(
										'action'		=> $this->_secure_url("?ACT=".$act),
									  	'name'			=> $name,
									  	'id'			=> $form_id, 
									  	'class'			=> $form_class,
									  	'hidden_fields'	=> array(
									  								'return' 	=> $return, 
									  								'token'		=> $token 
									  							) 
									  );  	
	
			$form = $this->EE->functions->form_declaration($form_details);
			return $form.$this->EE->TMPL->tagdata.form_close();
		}
		
		function reset_password()
		{
			$password = $this->EE->input->post('password',TRUE);
			$password_confirm = $this->EE->input->post('password_confirm',TRUE);
			$return 	= $this->EE->input->post('return',TRUE);
			$token	= $this->EE->input->post('token',TRUE);
			
			if(trim($password) == '' || trim($password_confirm) == '')
			{
				$_SESSION["br_alert"] = lang('br_password_fields_required');
				$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
			}
			
			if(trim($password) !== trim($password_confirm))
			{
				$_SESSION["br_alert"] = lang('br_password_dont_match');
				$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
			}
			
			if(strlen(trim($password)) < $this->EE->config->item('pw_min_len'))
			{
				$_SESSION["br_alert"] = str_replace("%x",$this->EE->config->item('pw_min_len'),lang('br_password_min_length'));
				$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
			}
			
			// Load the authorization library
				$this->EE->load->library('Auth');
				
				// Make sure we have a valid token 
					$reset 		= $this->EE->db->get_where('br_password_reset', array('token' => $token));
				
				// If no results then lets bail out. 
					if($reset->num_rows() != 1){
						$_SESSION["br_alert"] = lang('br_password_invalid_token');
						$this->EE->functions->redirect($this->EE->functions->create_url($return));
					}
				
				// We have a valid token lets do some time / security checks 
					$r = $reset->result_array();
					if((time() - $r[0]["created"]) >= $r[0]["length"])
					{
						$_SESSION["br_alert"] = lang('br_password_invalid_token');
						$this->EE->functions->redirect($this->EE->functions->create_url($return));
					}
					
					if($r[0]["secure"] == 'y')
					{
						if(md5($_SERVER["REMOTE_ADDR"]) != $r[0]["ip"]){
							$_SESSION["br_alert"] = lang('br_password_invalid_token');
							$this->EE->functions->redirect($this->EE->functions->create_url($return));
						}
					}
				
				// We can update the passwords! 
					$this->EE->auth->update_password($r[0]["member_id"],$password);
					
				// Delete the token
					$this->EE->db->delete('br_password_reset',array('member_id' => $r[0]["member_id"]));

				// Redirect to the return url
					$_SESSION["br_message"] = lang('br_password_update_success');
					$this->EE->functions->redirect($this->EE->functions->create_url($return));
		}

		/**
		 * Product Feed
		 *
		 * @return mixed product data
		 */
		function feed(){
			$code     = $this->EE->TMPL->fetch_param('code');
			$feed     = $this->EE->feed_model->get_feed_by_code($code);
		
			if($feed){
				$products = $this->EE->product_model->get_products_by_feed($feed['feed_id']);
				$rows     = array();
				$total    = count($products);
		
				foreach($products as $i => $product){
					$product  = $this->_get_product( $product['product_id'] );
					$rows[$i] = $product[0];
					$rows[$i]['price']  = strip_tags($product[0]['price']);
					$rows[$i]['count']  = $i + 1;
					$rows[$i]['total_results'] = $total;
				}
			}
		
			if($feed && count($rows) > 0){		    
				$this->switch_cnt = 0;
				$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $rows);
				$output = preg_replace_callback('/'.LD.'row_switch\s*=\s*([\'\"])([^\1]+)\1'.RD.'/sU', array(&$this, '_parse_switch'), $output); 
				return $this->return_data = $output;
			}else{
				return '';
			}
		}
	/* 
	*/
	
	function js(){
		$this->EE->session->cache['br_output_js'] .= $this->EE->TMPL->tagdata;
	}
	
	/* Get Url
	 * 
	 * Build a url for one of the 
	 * internal BR paths
	 * @returns string 
	 */
	
		function geturl()
		{
			$type = $this->EE->TMPL->fetch_param('type');
			return $this->EE->store_model->build_url_types($type,$this->site_id);
		}
	
	// Create our own path function so we can maintain 
	// secure paths throughout
		function path()
		{
			// Load the string helper
			$this->EE->load->helper('string');
			$src = $this->EE->TMPL->fetch_param('src');
			$src = str_replace(array("'", '"'), '', $src);
			$src = preg_replace("/(.+?(\/))index(\/)(.*?)/", "\\1\\2", $src);		
			$src = preg_replace("/(.+?(\/))index$/", "\\1", $src);
			$current = $this->EE->TMPL->fetch_param('use_current');

			// Use current protocol on includes like javascript / css
				if(strtolower($current) == 'true'){
					if(isset($_SERVER["HTTPS"])){
						$base = $this->_secure_url(trim_slashes($src));
						$out = $this->EE->functions->remove_double_slashes($base);			
						return $out;
					}else{
						return $this->EE->functions->create_url($src);
					}
				}
				
			// Else use http or https based on the target
				if(	strpos($src,$this->_config["store"][$this->site_id]["checkout_url"]) !== false || 
					strpos($src,$this->_config["store"][$this->site_id]["customer_url"]) !== false){
					$base = $this->_secure_url(trim_slashes($src));
					$out = $this->EE->functions->remove_double_slashes($base);			
					return $out;
				}elseif (strtolower($src) == 'logout'){
					$qs = ($this->EE->config->item('force_query_string') == 'y') ? '' : '?';		
					return $this->_secure_url($qs.'ACT='.$this->EE->functions->fetch_action_id('Member', 'member_logout'));
				}else{
					return $this->EE->functions->create_url($src);
				}
		}
	
	/**
	 * _output_js
	 *
	 * @param 
	 * @return 
	 */
		public function _output_js($tmp,$sub)
		{	
			if(strpos($tmp,'</body>') !== false){
				$script = '';
				$a = explode('</body>',$tmp);
				$output = $a[0].$script.'</body>'.$a[1];
				return $output;
			}else{
				return $tmp;
			}
		}	
	/*
	* Helper function for parsing internal switches 
	*/	
		public function _parse_switch($match)
		{
			$options = explode('|', $match[2]);
			$option = $this->switch_cnt % count($options);
			$this->switch_cnt++;
			return $options[$option];
		}
		
	/* 
	*/
		public function _get_parent_category($active){
			
			// Initiate a session array to hold the parents
				if(!isset($this->EE->session->cache['category_menu_parents'])){
					$this->EE->session->cache['category_menu_parents'] = array();
				}
			
			// 
			$this->EE->db->from('br_category')
						->where('url_title',$active);
			$qry = $this->EE->db->get();
		
			// test to make sure we matched a category
				if($qry->num_rows() == 0){
					return $this->EE->session->cache['category_menu_parents']; 
				}
			
			// We matched - lets start our walk up
				$rst = $qry->result_array();
				if($rst[0]["parent_id"] != 0){
					$this->EE->db->from('br_category')
								->where('category_id',$rst[0]["parent_id"]);
					$qry = $this->EE->db->get();
					$rst = $qry->result_array();
					
					// Whats the url title?
						$url_title = $rst[0]["url_title"];
	
					// Set it to the session array
						$this->EE->session->cache['category_menu_parents'][] = $rst[0]["url_title"];
					
					// Recursively walk on up 
						$this->_get_parent_category($url_title);
				}
			
			return $this->EE->session->cache['category_menu_parents']; 
		}
}