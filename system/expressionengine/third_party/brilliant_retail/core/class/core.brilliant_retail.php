<?php
if(!session_id()){
if(isset($_POST["PHPSESSID"])){
session_id($_POST["PHPSESSID"]);
}
session_start();
}
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

include_once(PATH_THIRD.'brilliant_retail/core/class/shipping.brilliant_retail.php');
include_once(PATH_THIRD.'brilliant_retail/core/class/gateway.brilliant_retail.php');
include_once(PATH_THIRD.'brilliant_retail/core/class/report.brilliant_retail.php');
include_once(PATH_THIRD.'brilliant_retail/core/class/product.brilliant_retail.php');


class Brilliant_retail_core {
	
	public $_config = array();
	public $site_id = '';
	public $br_channel_id = '';
	
	private $cat_tree 	= 0;
	private $cat = array();
	private $cat_count = 0;
	private $attr_single = array();	//Used to call individual products

	function __construct(){

		$this->EE =& get_instance();
		
		$this->EE->load->add_package_path(PATH_THIRD.'brilliant_retail/'); // This is required so we can create third party extensions! 

		// Load libraries we use throughout
			
			$this->EE->load->library('logger');
			$this->EE->load->library('table');

		// Load Helpers
			$this->EE->load->helper('form');
			$this->EE->load->helper('brilliant_retail'); 

		// Load all BR models 
		// We do this so we can access them in via extension
			$this->EE->load->model('core_model');
			$this->EE->load->model('customer_model');
			$this->EE->load->model('feed_model');
			$this->EE->load->model('email_model');
			$this->EE->load->model('order_model');
			$this->EE->load->model('product_model');
			$this->EE->load->model('promo_model');
			$this->EE->load->model('store_model');
			$this->EE->load->model('tax_model');
			
			$this->EE->lang->loadfile('brilliant_retail');
			
		// Create a language overloader! We want to be able to extend 
		// Brilliant Retail with custom language files so lets locally 
		// pick any file in the _local/language/[language]/directory 
		// that follow the lang.XXXX.php pattern

			$this->_load_lang_files();

			$this->site_id = $this->EE->config->item('site_id');

		// Get the BR _config variable & Set the site_id

			$sites 	= $this->EE->core_model->get_sites();
			$stores = $this->EE->core_model->get_stores();
			
			if(count($sites) != count($stores)){
				foreach($sites as $key => $val){
					if(!isset($stores[$key])){
						$this->EE->core_model->create_store($key);
					}
				}
				// Need to delete the cache
				remove_from_cache('config');
			}
			
			$this->_config = $this->EE->core_model->get_config();
			
			// Check for local configuration of path / url settings
				$local_config_opts = array('br_media_url','br_secure_url','br_media_dir','br_license');
				foreach($local_config_opts as $opts){
					$c = $this->EE->config->item($opts);
					if($c != ''){
						$this->_config["store"][$this->site_id][ltrim($opts,'br_')] = $c;
					}
				}
			
			$this->br_channel_id = $this->_config["store"][$this->site_id]["channel_id"];

			$this->_config["currency"] 			= $this->_config["store"][$this->site_id]["currency"];
			$this->_config["currency_id"] 		= $this->_config["store"][$this->site_id]["currency_id"];
			$this->_config["currency_marker"] 	= $this->_config["store"][$this->site_id]["currency_marker"];
			$this->_config["result_limit"] 		= $this->_config["store"][$this->site_id]["result_limit"]; # Limit the number of records returned by search
			$this->_config["result_per_page"] 	= $this->_config["store"][$this->site_id]["result_per_page"];  # Limit the number of records per search result page
			$this->_config["result_paginate"] 	= $this->_config["store"][$this->site_id]["result_paginate"]; # Number to list in the pagination links
			$this->_config["register_group"] 	= $this->_config["store"][$this->site_id]["register_group"]; # Number to list in the pagination links

		// Set the product types 
			$this->_config['product_type'] = array(
														1 => lang('br_basic'),
														2 => lang('br_bundle'),
														3 => lang('br_configurable'),
														4 => lang('br_downloadable'),
														5 => lang('br_virtual'), 
													);

			// Add some additional $this->_config["donation_enabled"]=>
				if($this->_config["store"][$this->site_id]["subscription_enabled"] == 1){
					$this->_config['product_type'][6] = lang('br_subscription');
				}
				if($this->_config["store"][$this->site_id]["donation_enabled"] == 1){
					$this->_config['product_type'][7] = lang('br_donation');
				}
												
		// Check the license
			$lic = $this->_config["store"][$this->site_id]["license"];
			$this->_validate_license($lic);	

		// Set the statuses in a more usable format	
			foreach($this->_config["system"][$this->site_id]["status"]["config_data"] as $key => $val){
				$this->_config["status"][$val["value"]] = $val["label"];
			}
		
		// Set allowed filetypes for order notes
			$this->_config["allowed_filetypes"] = 'doc|docx|pdf|ppt|pptx|zip|gif|jpg|png';
		
		// Set the media path 
			$this->_config["media_dir"] = rtrim($this->_config["store"][$this->site_id]["media_dir"],'/').'/';
			$this->_config["media_url"] = rtrim($this->_config["store"][$this->site_id]["media_url"],'/').'/';
			
		// Load Gateway / Shipping Files
			$this->_load_files('gateway');
			$this->_load_files('shipping');
			
		// Build search index if it is not 
		// present
		if(!file_Exists(APPPATH.'cache/brilliant_retail/search')){
			$this->_index_products();
		}

		// Make a global reference to the currency_marker variable that we 
		// can use in all of our view files
			$this->vars["currency_marker"] = $this->_config["currency_marker"];
	}
	
	
	// Check if there is enough inventory in stock for the item
	// thats in the cart - just in case it's been sold/removed.
		function _check_inventory($cart) {	
			// If the array is empty - simply return and don't do anything!
			if (empty($cart)){return;}
			$reduce = 0;
			foreach($cart["items"] as $key => $val){
				if($val["type_id"] == 1 || $val["type_id"] == 3){
					$qty = $val["quantity"];
					if($val["type_id"] == 1){
						// Simple products quantity is 
						// in the basic quantity
							$id = $val["product_id"];
							$product = $this->EE->product_model->get_products($id);
							$qty_available = $product[0]["quantity"];
					}else{
						// Config products quantity is 
						// in the items
							$id = $val["configurable_id"];
							$product = $this->EE->product_model->get_config_product($id);
							if(!isset($product[0])){
								$qty_available = 0;
							}else{
								$qty_available = $product[0]["qty"];
							}
					}
					if($qty > $qty_available){
						if($qty_available <= 0){
							// remove the item
							$this->EE->product_model->cart_unset(md5($key));
						}else{
							// Update the quantity
								$qty = $qty_available; 
								$cart["items"][$key]["quantity"] = $qty;
								$cart["items"][$key]["subtotal"] = $this->_currency_round($cart["items"][$key]["price"] * $qty); 	
			
								// Updagte the cart
									$content = serialize($cart["items"][$key]);
									$data = array(	'member_id' => $this->EE->session->userdata["member_id"],
													'session_id' => session_id(), 
													'content' => $content,
													'updated' => date("Y-n-d G:i:s"));
									$this->EE->product_model->cart_update($data,$key);
						}
						$reduce++;
					}
				}
			}
			if($reduce != 0){
				// Set a message
					$_SESSION["br_alert"] = lang('br_stock_inventory_exceeded');
				// Send back to cart
					$this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
			}
			return true;
		}
			
	function _get_meta_info(){
		// Check for a cached meta file
			if(!$arr = read_from_cache('meta_info')){
				$arr = array();
				// Check Categories
					$cats = $this->EE->product_model->get_categories();
					foreach($cats as $cat){
						foreach($cat as $c){
							$arr[$c["url_title"]]  = array(
															'type' => 'category',
															'title' => $c["meta_title"],
															'descr' => $c["meta_descr"],
															'keywords' => $c["meta_keyword"]);
						}
					}
	
				// Check Products 
					$products = $this->EE->product_model->get_products();
					foreach($products as $p){
						$arr[$p["url"]]  = array(
												'type' => 'product',
												'title' => $p["meta_title"],
												'descr' => $p["meta_descr"],
												'keywords' => $p["meta_keyword"]);
					}
	
				// Check Channels
				$arr = serialize($arr);
				save_to_cache('meta_info',$arr);
			}

		$arr = unserialize($arr);
		
		// Check for url_title in the uri
		$uri = $this->EE->uri->segments;
		$i = 1;
		foreach($uri as $row){
			if(isset($arr[$row])){
				$info = $arr[$row];
				if(count($uri) == $i)
				{
					$info["canonical_url"] = FALSE;
				}else{
					$info["canonical_url"] = rtrim($this->EE->config->item('site_url'),"/");
					for($j=1;$j<=$i;$j++){	
						$info["canonical_url"] .= "/".$uri[$j]; 	
					}
				}
				return $info; 
			}
			$i++;
		}
		return false;
	}
	
	function _load_files($type){
		$list = array();
		
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

					// Only Load it if its in the 
					// configuration table. If its not 
					// then the admin needs to install it 
					// from the cp
						if(isset($this->_config[$type][$this->site_id][$nm])){
							if($this->_config[$type][$this->site_id][$nm]['enabled']){
								$this->_load[$type][] = $nm; 	
								$list[$f] = $dir.'/'.$f;
							}
						}
				}
			}
			foreach($local as $loc){
				if(substr($loc,0,strlen($type)+1) == $type.'.'){
					if(isset($files[$loc])){
						unset($files[$loc]);
					}
					$rem= array($type.'.','.php');
					$nm = strtolower(str_replace($rem,'',$loc));
					if(isset($this->_config[$type][$this->site_id][$nm])){
						if($this->_config[$type][$this->site_id][$nm]['enabled']){
							$this->_load[$type][] = $nm;
							$list[$loc] = $local_dir.'/'.$loc;
						}
					}
				}
			}	
			sort($list);
			foreach($list as $inc){
				include_once($inc);
			}
	}
	
	/*
	* Load _local language files
	*/
		public function _load_lang_files(){
			// Doing our own magic to load in any custom language files. 
			$lang_dir = $this->EE->session->userdata["language"];
			if($lang_dir == ''){
				$lang_dir = 'english';
			}
			$local_path = PATH_THIRD.'_local/brilliant_retail/language/'.$lang_dir;
			$local = read_dir_files($local_path);
			foreach($local as $loc){
				$path = $local_path.'/'.$loc;
				$a = explode(".",basename($path));
				if(strtolower($a[0]) == 'lang' && strtolower($a[2]) == 'php'){
					include($path);
					if(isset($lang)){
						foreach($lang as $key => $val){
							$this->EE->lang->language[$key] = $val;
						}
						unset($lang);
					}
				}
			}
		}
		
		function _theme($str = '',$trailing_slash=FALSE){ 
			$path = $this->EE->config->item('theme_folder_url').'third_party/brilliant_retail/'.trim($str,'/');
			if($trailing_slash === TRUE){
				$path .= '/';
			}
			return $path;
		}
				
		function _currency_round($amt){
			return number_format($amt,2,'.','');
		}
		
		function _format_money($amt)
		{
			return $this->_config["currency_marker"].number_format($amt,2);
		}
		
		function _secure_url($path){
			$str = rtrim($this->_config["store"][$this->site_id]["secure_url"],"/").'/'.$path;
			return $str;
		}

		function _get_product($product_id){
			if($product_id == ''){
				// Get product by param or dynamically 
				$product_id = $this->EE->TMPL->fetch_param('product_id');
				$url_title = $this->EE->TMPL->fetch_param('url_title');
				if($product_id != ''){
					$products = $this->EE->product_model->get_products($product_id);			
				}else{
					// get by url key 
					$key = ($url_title == '') ? $this->EE->uri->segment(2) : $url_title;
					if(!$products = $this->EE->product_model->get_product_by_key($key)){
						 // Not a product page 
						 return false;
					}
				}
			}else{
				if(!$products = $this->EE->product_model->get_products($product_id)){
					return false;
				}			
			}
			
			// Set an in_stock flag
				$products[0]["in_stock"] = ($products[0]["quantity"] > 0) ? TRUE : FALSE;

			// Set Category information for the breadcrumbs
				$products[0]["category_title"] = "";
				$products[0]["category_url"] = "";
				if(isset($products[0]["categories"][0])){
					$cat = $this->EE->product_model->get_category($products[0]["categories"][0]); 
					if(isset($cat[0])){
						$products[0]["category_title"] 	= $cat[0]["title"];
						$products[0]["category_url"] 	= $cat[0]["url_title"];
					}
				}

			// Build the price html
				if($amt = $this->_check_product_price($products[0])){
					foreach($amt as $key => $val){
						$products[0][$key] = $val;	
					}
				}else{
					return false;
				}
			
			// Configurable product selectors
				if($products[0]["type_id"] == 3){
					$config_opts = $this->_build_config_opts($products[0]);
					$products[0]["configurable"] = $config_opts["config"];
					$products[0]["configurable_js"] = $config_opts["js"];
				}else{
					$products[0]["configurable"][0] = array();
					$products[0]["configurable_js"] = '';
				}
			
			// Subscriptions
				if($products[0]["type_id"] == 6){
					$periods = array(
										1=>strtolower(lang('br_days')),
										2=>strtolower(lang('br_months')) 
									);
					$products[0]["subscription"][0]["renewal"] = $periods[$products[0]["subscription"][0]["period"]];
				}else{
					$products[0]["subscription"][0] = array();				
				}
			
			// Donation
				if($products[0]["type_id"] == 7){
					// Setup the radio buttons
						
						$group_id = $this->EE->session->userdata["group_id"];
						$radio = '<ul id="donation_options">';
						$i = 0;
						foreach($products[0]["price_matrix"] as $price){
						 	$selected = ($i == 0) ? 'checked' : '';
						 	if(	$price["group_id"] == 0 || 
						 		$price["group_id"] == $group_id){
						 		$radio .= '	<li><input type="radio" '.$selected.' name="'.$products[0]["product_id"].'_donation_price[]" id="donation_price_'.$i.'" value="'.$price["price"].'"  />
        										'.$this->_config["currency_marker"].$this->_currency_round($price["price"]).'</li>';
						 	}
							$i++;
						}
						$radio .= '		<li>
											<input type="radio" name="'.$products[0]["product_id"].'_donation_price[]" id="donation_price_'.$i.'" value="other"  /> '.lang('br_other').' '.$this->_config["currency_marker"].' <input type="text" name="'.$products[0]["product_id"].'_donation_other" class="number" id="donation_other" />
											<em>'.$this->_config["currency_marker"].$this->_currency_round($products[0]["donation"][0]["min_donation"]).' '.lang('br_minimum').'</em></li>
									</ul>';
						
						$products[0]["donation"][0]['price_options'] = $radio;	
						
					// Lets hide the price since it would show the highest one
						$products[0]["price"] = '';
						$products[0]["price_html"] = '';
				}else{
					$products[0]["donation"][0] = array();				
				}
			
			// Set default images
			 	if($products[0]["image_large"] == ''){
			 		$products[0]["image_large"] = 'products/noimage.jpg';
			 		$products[0]["image_large_title"] = '';
			 	}
			 	if($products[0]["image_thumb"] == ''){
			 		$products[0]["image_thumb"] = 'products/noimage.jpg';
			 		$products[0]["image_thumb_title"] = '';
			 	}
				
				// Image Counts 
					$products[0]["image_total"] = 0;			# Total images
				
				if(!isset($products[0]["images"])){
			 		$products[0]["images"][0] = array(    
				 										"image_count"	=> 0, 
				 										"image_id"		=> 0,
														"product_id" 	=> $products[0]["product_id"],
														"filenm" 		=> 'products/noimage.jpg',
													    "title" 		=> '',
													    "large"			=> 0,
													    "thumb" 		=> 0, 
													    "exclude" 		=> 0, 
													    "sort" 			=> 0
													);
			 	}else{
					$products[0]["image_total"] = count($products[0]["images"]);
			 		for($i=0;$i<count($products[0]["images"]);$i++){
			 			$products[0]["images"][$i]["filenm"] = 'products/'.$products[0]["images"][$i]["filenm"];
			 			$products[0]["images"][$i]["image_title"] = $products[0]["images"][$i]["title"];
			 			$products[0]["images"][$i]["image_count"] = $i + 1;
			 		}
			 	}

				if(!isset($products[0]["images_excluded"])){
			 		$products[0]["images_excluded"][0] = array();
			 	}else{
			 		for($i=0;$i<count($products[0]["images_excluded"]);$i++){
			 			$products[0]["images_excluded"][$i]["filenm"] = 'products/'.$products[0]["images_excluded"][$i]["filenm"];
			 			$products[0]["images_excluded"][$i]["image_title"] = $products[0]["images_excluded"][$i]["title"];
			 		}
			 	}
				
			// Options
				$products[0]["has_options"] = FALSE;
				if(isset($products[0]["options"])){
					$i = 0;
					$option_list = array();
					foreach($products[0]["options"] as $opt){
						// It has options
							$products[0]["has_options"] = TRUE;
							
						if($opt["type"] == 'text'){
							$option = $this->_producttype_text($products[0]["product_id"],'option_'.$i,$opt["title"],$opt["title"],$opt["required"],'','');
						}elseif($opt["type"] == 'textarea'){
							$option = $this->_producttype_textarea($products[0]["product_id"],'option_'.$i,$opt["title"],$opt["title"],$opt["required"],'','');
						}elseif($opt["type"] == 'dropdown'){
							$options = array();
							$j = 0;
							foreach($opt["opts"] as $o){
								$val = '';
								if($o["price"] != 0){
									$dir = ($o["price"] > 0) ? '+' : '-';
									$val = ' ('.$dir.' '.$this->_config["currency_marker"].$this->_currency_round(abs($o["price"])).')';
								}	
								$options[] = $j.':'.$o["title"].$val;
								$j++;
							}
							$list = join("|",$options);
							$option = $this->_producttype_dropdown($products[0]["product_id"],'option_'.$i,$opt["title"],$opt["title"],$opt["required"],'',$list);
						}
						$option_list[] = array( 
												'option_count' => ($i+1),
												'option_label' => $opt["title"],
												'option_input' => $option 
												);
					
						$i++;
					}
					$products[0]["options"] = $option_list;
				}else{
					$products[0]["options"][0] = array();
				}
				
			// Set the attributes
				$attr = $this->_build_product_attributes($products[0]);
				if($attr){
					$products[0]["attribute"] = $attr;
				}else{
					$products[0]["attribute"][0] = array();
				}
				$products[0] = array_merge($products[0],$this->attr_single);
				$this->attr_single = array();
				
			// Create a quantity selector 
				$products[0]["quantity_select"] = '	<select id="product_quantity" name="'.$products[0]["product_id"].'_quantity">
								                  		<option value="1">1</option>
								                  		<option value="2">2</option>
								                  		<option value="3">3</option>
								                  		<option value="4">4</option>
								                  		<option value="5">5</option>
								                	</select>';
			return $products;
			
		}
		
		
		// Build a list of the product attributes for the 
		// supplied product type

			function _product_attrs($set_id,$product_id = 0){
				$attributes = array();
				if($set_id == 0){
					return $attributes;
				}
				// Get the attributes
					$this->EE->load->model('product_model');
					$attrs = $this->EE->product_model->get_attributes($set_id,$product_id);
 				
 				// Cycle through and build the input 
				// based on the helpper funtions for 
				// each available attr type. 
				
					$i = 0;

					foreach($attrs as $a){
						foreach($a as $key => $val){
							if($key == 'fieldtype'){
								$f = '_producttype_'.$val;
								$attributes[$i]['input'] = $this->$f(	$product_id,
																		$attrs[$i]["attribute_id"],
																		$attrs[$i]["code"],
																		$attrs[$i]["title"],
																		$attrs[$i]["required"],
																		$attrs[$i]["value"],
																		$attrs[$i]["options"] 
																		);
							}
							$attributes[$i][$key] = $val;
						}
						$i++;
					}
				
				// return attributes
				return $attributes;
			}
			
			function _product_options($product_id){
				// Get the attributes
					$this->EE->load->model('product_model');
					$options = $this->EE->product_model->get_product_options($product_id);

				// return attributes
					return $options;
			}
			
			function _product_category_tree($arr,$cat,$level,$selected = ''){
				foreach($arr as $key => $val){
					$sel 	= '';
					$state 	= 'collapsed';
					if(isset($selected[$key]))
					{
						$sel 	= 'checked="checked"';
						$state 	= 'expanded';
					}
					 
					if($this->cat_count == 0 ){
						$this->cats = '<ul id="product_category_tree">';
						$this->cat_count = 1;
					}
					$this->cats .= '<li class="'.$state.'">
										<input id="product_cat_'.$val["category_id"].'" name="category_title[]" value="'.$key.'" type="checkbox"  '.$sel.' /><span>'.$val['title'].'</span>';
					if(isset($cat[$key])){
						$level++;
						$this->cats .= '<ul>';
							$this->_product_category_tree($cat[$key],$cat,$level,$selected);	
						$level--;
					}
					$this->cats .= '</li>';
				}
				$this->cats .= '</ul>';
				return $this->cats;
			}

			function _config_category_tree($arr,$cat,$level,$selected = ''){
				if(isset($arr)){
					foreach($arr as $key => $val){
						$sel = isset($selected[$key]) ? 'checked="checked"' : ''; 
						$this->cat[$level][$this->cat_tree][$key] = $val;
						$this->cat_tree++;
						if(isset($cat[$key])){
							$level++;
							$this->_config_category_tree($cat[$key],$cat,$level,$selected);	
							$level--; 
						}
					}
				}
				return $this->cat;
			}
			
		function _menu_category_tree($arr,$cat,$level,$product_selected='',$parent_selected='',$path,$parent='',$exclude=''){

			$exclude_list = explode("|",$exclude);
			
			foreach($arr as $key => $val){
			
			if (!in_array($val["url_title"],$exclude_list)){	
				
				if(trim($val["template_path"]) != ""){
					$url = $this->EE->functions->create_url(trim($val["template_path"],"/")."/".$val["url_title"]);
				}else{
					$url = $this->EE->functions->create_url($path."/".$val["url_title"]);
				}

				if ($val['url_title']==$product_selected){
					$class="class=\"active\"";
				}
				elseif ($val['url_title']==$parent_selected)
				{
					$class="class=\"active_parent\"";
				}
				else
				{
					$class="";
				}
				
				$this->cats .= "<li ".$class."><a href=\"".$url."\">".$val['title']."</a>\n";
				
				if($parent == ''){
					if(isset($cat[$key])){
						$level++;
						$this->cats .= "<ul>\n";
						$this->_menu_category_tree($cat[$key],$cat,$level,$product_selected,$parent_selected,$path,$parent,$exclude);	
						$this->cats .= "</ul>\n";
						$level--;
					}
				}
				$this->cats .= "</li>\n";
			}
			}
			return $this->cats;
		}
	
	// Product Type Attribute Fields
	
		function _producttype_text($product_id,$attribute_id,$title,$label,$required,$val,$opts = ''){
			$class = ($required == 1) ? '{required:true}' : '' ;
			$input_title = ($required == 1) ? $label.' '.lang('br_is_required') : $label ;
			return '<input name="'.$product_id.'_cAttribute_'.$attribute_id.'" value="'.$val.'" title="'.$input_title.'" type="text" class="'.$class.'" />';
		}
		
		function _producttype_password($product_id,$attribute_id,$title,$label,$required,$val,$opts = ''){
			$class = ($required == 1) ? '{required:true}' : '' ;
			$input_title = ($required == 1) ? $label.' '.lang('br_is_required') : $label ;
			$val = ($val != '') ? '************************' : '' ;
			return '<input name="'.$product_id.'_cAttributePW_'.$attribute_id.'" value="'.$val.'" title="'.$input_title.'" type="password" class="'.$class.' cleartext" />';
		}
		
		function _producttype_file($product_id,$attribute_id,$title,$label,$required,$val,$opts = ''){
			$class = ($required == 1) ? '{required:true}' : '' ;
			$input_title = ($required == 1) ? $label.' '.lang('br_is_required') : $label ;
			
			// Values  
				$title 	= '';
				$link 	= '';
				$values = unserialize($val);
				$contain	= '';
				 
			if(isset($values)){
				$title = $values["title"];
				if(trim($values["file"]) != ''){
					$link = '<span id="div_cAttribute_'.$attribute_id.'">
								<a href="'.$this->_config["media_url"].'file/'.$values["file"].'" target="_blank">'.$values["file"].'</a>&nbsp;
							 	<a href="#" onclick="$(\'#cAttribute_'.$attribute_id.'\').val(\'\');$(\'#div_cAttribute_'.$attribute_id.'\').remove();$(\'#div_cAttribute_'.$attribute_id.'_file_contain\').show();return false">(<b>'.strtolower(lang('delete')).'</b>)</a>
							 	<br />
							 </span>';
					$contain = 'nodisplay';
				}
			}
			return 	'	<input name="'.$product_id.'_cAttribute_'.$attribute_id.'" id="cAttribute_'.$attribute_id.'" value=\''.$val.'\' type="hidden" />
						'.lang('br_title').': <input name="'.$product_id.'_cAttribute_'.$attribute_id.'_title" value="'.$title.'" type="text" class="'.$class.'" style="width:50%" />
						<br />
						<br />
						'.lang('br_file').': '.$link.'
						<span id="div_cAttribute_'.$attribute_id.'_file_contain" class="'.$contain.'"> 
							<input name="'.$product_id.'_cAttribute_'.$attribute_id.'_file" type="file" class="'.$class.'" />
						</span>';
		}
		
		function _producttype_textarea($product_id,$attribute_id,$title,$label,$required,$val,$opts = ''){
			$class = ($required == 1) ? '{required:true}' : '' ;
			$input_title = ($required == 1) ? $label.' '.lang('br_is_required') : $label ;
			return '<textarea name="'.$product_id.'_cAttribute_'.$attribute_id.'" title="'.$input_title.'" class="'.$class.'">'.$val.'</textarea>';
		}
		
		function _producttype_dropdown($product_id,$attribute_id,$title,$label,$required,$val,$opts = ''){
			$class = ($required == 1) ? '{required:true}' : '' ;
			$input_title = ($required == 1) ? $label.' '.lang('br_is_required') : $label ;
			
			$options = '<option value=""></option>';
			if(strpos($opts,"|") !== false){
				$a = explode("|",$opts);
			}else{
				$a = explode("\n",$opts);
			}
			
			foreach($a as $opt){
				if(strpos($opt,':') !== false){
					$b = explode(":",$opt);
					$sel = ($b[0] == $val) ? 'selected' : '' ;
					$options .= '<option value="'.$b[0].'" '.$sel.'>'.$b[1].'</option>';
				}else{
					$sel = ($opt == $val) ? 'selected' : '' ;
					$options .= '<option '.$sel.'>'.$opt.'</option>';
				}
			}
			$sel = 	'<select name="'.$product_id.'_cAttribute_'.$attribute_id.'" id="cAttribute_'.$attribute_id.'" title="'.$input_title.'" class="'.$class.'">'
						.$options.
					'</select>';
			return $sel;
		}
		function _producttype_table($product_id,$attribute_id,$title,$label,$required,$val,$opts = ''){
			// Create the table
				$str = "<a href='#' id='add_row_".$attribute_id."'>".lang('br_add_row')."</a><br />
						<table id='table_".$attribute_id."' cellpadding='0' cellspacing='0' width=\"100%\" class=\"ft_table\">
							<thead>
								<tr>
									<th>&nbsp;</th>";
	
			// Insert the header rows
				$theads = explode("|",$opts);
				foreach($theads as $head){
					$str .= "<th>".lang('br_'.$head)."</th>";
				}		
			
			$str .= "				<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>";

				$rows = unserialize($val);
				$i = 1;
				if($rows){
					foreach($rows as $r){
						$str .= "<tr>
									<td class='dragHandle'>&nbsp;</td>";
						foreach($r as $cell){
							$str .= "<td><input type='text' name='0_cAttribute_".$attribute_id."[".$i."][]' value='".$cell."' /></td>";
						}
						$str .= "	<td class='remove'>&nbsp;</td>
								</tr>";
						$i++;
					}
				}
			$str.= "		</tbody>
						</table>";
			// Now we have to jquery the thing to make it magical
			$str .= "<script type='text/javascript'>
						<!--
						$(function(){
							var row_cnt = ".$i.";
							var tbl_".$attribute_id." = $('#table_".$attribute_id."');
							var col_size = $('#table_".$attribute_id." th').size();
							
							$('#table_".$attribute_id." tbody').sortable({
																		axis:'y', 
																		cursor:'move', opacity:0.6, handle:'.dragHandle',
																		helper:function(e, ui) {
																			ui.children().each(function() {
																				$(this).width($(this).width());
																			});		
																			return ui;
																		}
																	});
							
							$('.remove', tbl_".$attribute_id.").bind('click',function(){
								$(this).parent().remove();
							});
							$('#add_row_".$attribute_id."').bind('click',function(){
								var str = '<tr><td class=\"dragHandle\">&nbsp;</td>';
								for(i=1;i<(col_size-1);i++){
									str += '<td><input type=\"text\" name=\"0_cAttribute_".$attribute_id."['+row_cnt+'][]\" /></td>';
								}
								str += '<td class=\"remove\">&nbsp;</td></tr>';
								
								$(str).appendTo(tbl_".$attribute_id.");
								
								row_cnt++;
								$('#table_".$attribute_id." tbody').sortable({
																					axis:'y', 
																					cursor:'move', opacity:0.6, handle:'.dragHandle',
																					helper:function(e, ui) {
																						ui.children().each(function() {
																							$(this).width($(this).width());
																						});		
																						return ui;
																					}
																				});
																
								$('.remove', tbl_".$attribute_id.").unbind().bind('click',function(){
									$(this).parent().remove();
								});
								return false;
							});
						});
						-->
					</script>";
			return $str;
		}
		
		function _producttype_multiselect($product_id,$attribute_id,$title,$label,$required,$val,$opts = ''){
			$class = ($required == 1) ? '{required:true}' : '' ;
			$input_title = ($required == 1) ? $label.' '.lang('br_is_required') : $label ;
			
			$options = '';
			if(strpos($opts,"|") !== false){
				$a = explode("|",$opts);
			}else{
				$a = explode("\n",$opts);
			}
			
			$val = (array)unserialize($val);

			foreach($a as $opt){
				if(strpos($opt,':') !== false){
					$b = explode(":",$opt);
					$sel = (in_array($b[0],$val)) ? 'selected' : '' ;
					$options .= '<option value="'.$b[0].'" '.$sel.'>'.$b[1].'</option>';
				}else{
					$sel = (in_array($opt,$val)) ? 'selected' : '' ;
					$options .= '<option '.$sel.'>'.$opt.'</option>';
				}
			}
			$sel = 	'<select size="8" multiple="multiple" style="min-width:200px;" name="'.$product_id.'_cAttribute_'.$attribute_id.'[]" id="cAttribute_'.$attribute_id.'" title="'.$input_title.'" class="'.$class.'">'
						.$options.
					'</select>';
			return $sel;
		}

		
		function _producttype_checkbox($product_id,$attribute_id,$title,$label,$required,$val,$opts = ''){
			$options = '';
			if(strpos($opts,"|") !== false){
				$a = explode("|",$opts);
			}else{
				$a = explode("\n",$opts);
			}
			if($val != ''){
				$checked = unserialize($val);
				if(isset($checked)){
					foreach($checked as $c){
						$sel[$c] = $c;
					}
				}
			}
			foreach($a as $opt){
				$b = explode(":",$opt);
				$chk = (isset($sel[$b[0]])) ? 'checked="checked"' : '' ;
				$options .= '<input type="checkbox" name="'.$product_id.'_cAttribute_'.$attribute_id.'[]" value="'.$b[0].'" '.$chk.' /> '.$b[1].'<br />';
			}
			return $options;
		}
		
	// End Product Type Fields 
	
		function _configurable_dropdown($attribute_id,$title,$label,$required,$val,$opts = ''){
			$class = ($required == 1) ? '{required:true}' : '' ;
			$input_title = ($required == 1) ? $label.' '.lang('br_is_required') : $label ;
			
			$options = '';
			if(strpos($opts,"|") !== false){
				$a = explode("|",$opts);
			}else{
				$a = explode("\n",$opts);
			}
			
			foreach($a as $opt){
				if(strpos($opt,':') !== false){
					$b = explode(":",$opt);
					$sel = ($b[0] == $val) ? 'selected' : '' ;
					$options .= '<option value="'.$b[0].'" '.$sel.'>'.$b[1].'</option>';
				}else{
					$sel = ($opt == $val) ? 'selected' : '' ;
					$options .= '<option '.$sel.'>'.$opt.'</option>';
				}
			} 
			$sel = 	'<select name="configurable_'.$attribute_id.'" title="'.$input_title.'" class="'.$class.'">'
						.$options.
					'</select>';
			return $sel;
		}
		function index_products(){
			echo 'Begin product index';
			echo '<hr />';
			echo $st = microtime();
			$this->_index_products();
			echo '<hr />';
			echo $end = microtime();
			echo '<hr />';
			echo $total = $end - $st;
			exit();
		}
		
		function _index_products($product_id=''){
			$this->EE->load->model('product_model');
			ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.PATH_THIRD.'brilliant_retail'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.PATH_SEPARATOR);
			include_once(PATH_THIRD.'brilliant_retail'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'Zend'.DIRECTORY_SEPARATOR.'Search'.DIRECTORY_SEPARATOR.'Lucene.php');
			
			Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive());
			
			$path = APPPATH.'cache'.DIRECTORY_SEPARATOR.'brilliant_retail'.DIRECTORY_SEPARATOR.'search';
			if(!file_exists($path)){
				mkdir($path,DIR_WRITE_MODE,TRUE);
			}

			// Lets open or create depending on the existance of the index
				try
				{
				    $index = Zend_Search_Lucene::open($path);
				}
				catch (Zend_Search_Lucene_Exception $e)
				{
				    $index = Zend_Search_Lucene::create($path);
				}
				
			// Give the index writable permissions
				Zend_Search_Lucene_Storage_Directory_Filesystem::setDefaultFilePermissions(0755);
			
				$products = $this->EE->product_model->get_products($product_id);
				if(count($products) >= 1){
					$hits = $index->find('product_id:' . $products[0]["product_id"]);
					foreach ($hits as $hit) {
					   	$index->delete($hit->id);
					}
				
					// Reindex the result set. If it was an update this will be a single 
					// product_id. 
					foreach($products as $p){
						$doc = new Zend_Search_Lucene_Document();
						$doc->addField(Zend_Search_Lucene_Field::UnStored('title',$p["title"],'UTF-8'));
						$doc->addField(Zend_Search_Lucene_Field::UnStored('detail',$p["detail"],'UTF-8'));
						$doc->addField(Zend_Search_Lucene_Field::UnStored('keywords',$p["meta_keyword"],'UTF-8'));
						$doc->addField(Zend_Search_Lucene_Field::UnStored('sku',$p["sku"],'UTF-8'));
						$doc->addField(Zend_Search_Lucene_Field::Keyword('product_id',$p["product_id"],'UTF-8'));
						$index->addDocument($doc);
					}
				}
				#$index->commit();
				#$index->optimize();
				return TRUE;
		}
		
		// Remove a single product from the product index 
		// Should refactor this under DRY.  
			function _index_delete_product($product_id){
				ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.PATH_THIRD.'brilliant_retail'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.PATH_SEPARATOR);
				include_once(PATH_THIRD.'brilliant_retail'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'Zend'.DIRECTORY_SEPARATOR.'Search'.DIRECTORY_SEPARATOR.'Lucene.php');
				
				Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive());
				
				$path = APPPATH.'cache'.DIRECTORY_SEPARATOR.'brilliant_retail'.DIRECTORY_SEPARATOR.'search';
				if(!file_exists($path)){
					mkdir($path,DIR_WRITE_MODE,TRUE);
				}
	
				// Lets open or create depending on the existance of the index
					try
					{
					    $index = Zend_Search_Lucene::open($path);
					}
					catch (Zend_Search_Lucene_Exception $e)
					{
					    $index = Zend_Search_Lucene::create($path);
					}
					
				// Give the index writable permissions
					Zend_Search_Lucene_Storage_Directory_Filesystem::setDefaultFilePermissions(0755);
				
					$hits = $index->find('product_id:' . $product_id);
					foreach ($hits as $hit) {
					   	$index->delete($hit->id);
					}
			}

		function _validate_license($lic){
			if(uuid_validate($lic)){
				$this->vars["system_message"] = '';
			}else{
				if(isset($this->EE->session->cache['br__validate_license'])){
					$rst = $this->EE->session->cache['br__validate_license'];
				}else{
					$this->EE->db->where('config_id',1);
					$this->EE->db->from('br_config');
					$query = $this->EE->db->get();
					$rst = $query->result_array();
					$this->EE->session->cache['br__validate_license'] = $rst;
				}
				$timestamp = date('U',strtotime($rst[0]["created"]));
				$len = round(30 - ((time() - $timestamp) / 60 / 60 / 24));
				if($len >= 0){
					$this->vars["system_message"] = '<script type="text/javascript">var _0xd45f=["\x3C\x70\x20\x69\x64\x3D\x22\x62\x32\x72\x5F\x62\x75\x79\x22\x3E\x3C\x62\x3E\x54\x72\x69\x61\x6C\x20\x4D\x6F\x64\x65\x3A\x3C\x2F\x62\x3E\x20\x3C\x61\x20\x68\x72\x65\x66\x3D\x22\x68\x74\x74\x70\x3A\x2F\x2F\x77\x77\x77\x2E\x62\x72\x69\x6C\x6C\x69\x61\x6E\x74\x72\x65\x74\x61\x69\x6C\x2E\x63\x6F\x6D\x22\x20\x74\x61\x72\x67\x65\x74\x3D\x22\x5F\x62\x6C\x61\x6E\x6B\x22\x3E"];_0xd45f[0];document.write(_0xd45f);</script>'.lang('br_buy_license').'</a> '.$len.' '.lang('br_days_remain').'</p>';
				}else{
					$this->vars["system_message"] = '<script type="text/javascript">var _0xd45f=["\x3C\x70\x20\x69\x64\x3D\x22\x62\x32\x72\x5F\x62\x75\x79\x22\x3E\x3C\x62\x3E\x54\x72\x69\x61\x6C\x20\x4D\x6F\x64\x65\x3A\x3C\x2F\x62\x3E\x20\x3C\x61\x20\x68\x72\x65\x66\x3D\x22\x68\x74\x74\x70\x3A\x2F\x2F\x77\x77\x77\x2E\x62\x72\x69\x6C\x6C\x69\x61\x6E\x74\x72\x65\x74\x61\x69\x6C\x2E\x63\x6F\x6D\x22\x20\x74\x61\x72\x67\x65\x74\x3D\x22\x5F\x62\x6C\x61\x6E\x6B\x22\x3E"];_0xd45f[0];document.write(_0xd45f);</script>'.lang('br_buy_license').'</a> '.lang('br_invalid_expired_license').'</p>';
				}
			}
		}

	function _discount_amount($total = 0){
		if(!isset($_SESSION["discount"])){
			return 0;
		}
		if($_SESSION["discount"]["code_type"] == 'percent'){
			return $total * ($_SESSION["discount"]["amount"] / 100);		
		}elseif($_SESSION["discount"]["code_type"] == 'fixed'){
			return $_SESSION["discount"]["amount"];		
		} 
		exit();
	}
	
	function _validate_promo_code($code){

		if($code["enabled"] == 0){ return false; }		
		
		if($code["uses_per"] > 0){
			$cnt = $this->EE->promo_model->get_promo_use_count($code["code"]);
			if($cnt >= $code["uses_per"]){
				return false;
			}
		}
		if(date('U',strtotime($code["start_dt"])) > time()){
			return false;
		}
		if(time() > date('U',strtotime($code["end_dt"])) && $code["end_dt"] != null){
			return false;
		}
		return true;
	
	}
	
	function _search_index($queryStr){
		// Need at least 3 characters for wildcard searches 
		if(strlen($queryStr) >= 3){
			// Dashes don't play nicely with our wildcard search
			$queryStr = str_replace("-"," ",$queryStr).'*';
		}
		ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.PATH_THIRD.'brilliant_retail'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.PATH_SEPARATOR);
		include_once(PATH_THIRD.'brilliant_retail'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'Zend'.DIRECTORY_SEPARATOR.'Search'.DIRECTORY_SEPARATOR.'Lucene.php');
		
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive());
		
		$path = APPPATH.'cache/brilliant_retail/search';
		$index = Zend_Search_Lucene::open($path);
		$query = Zend_Search_Lucene_Search_QueryParser::parse($queryStr, 'utf-8');
	
		$hits = $index->find($query);
		return $hits;
	}
	
	function _payment_options($osc_enabled=TRUE,$tax=0,$shipping=0,$admin_order=FALSE){
		
		$output = '';
		$this->EE->load->model('product_model'); 
		$cart = $this->EE->product_model->cart_get();
		// see if there are subscriptions in the cart
			$subscriptions = 0;
			if(isset($cart["items"])){
				foreach($cart["items"] as $c){
					if(count($c["subscription"]) > 0){
						$subscriptions = 1;
					}
				}
			}
		$i = 0;

		if(isset($this->_config["gateway"][$this->site_id])){ // Check if any gateways are enabled
			
			foreach($this->_config["gateway"][$this->site_id] as $gateway){
				if($gateway["enabled"] == 1){
					
					$str = 'Gateway_'.$gateway["code"];
					
					$total = $this->_get_cart_total() + $tax + $shipping;
					$tmp = new $str($total);
					$tmp->admin_order = $admin_order;
					$proceed = TRUE;
					if($total == 0 && $admin_order === FALSE){
						if($tmp->zero_checkout != TRUE){
							$proceed = FALSE;
						}					
					}
					
					if($proceed === TRUE){
						// Check if the gateway supports 
						// subscription checkouts 
							$sub_available = 1;
							if($subscriptions == 1){
								if($tmp->subscription_enabled != 1){
									$sub_available = 0;
								}
							}
						
						if($tmp->osc_enabled == $osc_enabled && $sub_available == 1){
							
							$form = $tmp->form();
							if($form !== false){
								$sel = ($i == 0) ? 'checked="checked"' : '';
								$output .= ' <div id="gateway_'.$gateway["code"].'" class="gateways">
												<label>
							                    <input type="radio" name="gateway" value="'.md5($gateway["config_id"]).'" class="gateway required" id="gateway_'.$i.'" '.$sel.' />
							                    '.$gateway["label"].'</label>
							                    <div class="payment_form">'.trim($form).'</div>
							               	</div>';
								$i++;
							}
						}
					}
				}
			}
		
		}

		// This will allow us to add stuff directly into the payment option response form
		// Added 1.0.5.1 - dpd
			if($this->EE->extensions->active_hook('br_payment_options_after') === TRUE){
				$output = $this->EE->extensions->call('br_payment_options_after', $output,$osc_enabled=TRUE,$tax=0,$shipping=0,$admin_order=FALSE); 
			}
		
		return $output;
	}
	
	function _payment_buttons(){
		$output = '';
		$this->EE->load->model('product_model'); 
		$cart = $this->EE->product_model->cart_get();
		if(!isset($cart["items"]) || count($cart["items"]) == 0) return $output;
		
		$subscriptions = 0;
		foreach($cart["items"] as $c){
			if($c["type_id"] == 6){
				$subscriptions = 1;
			}	
		}
		$i = 0;
		
		$total = $this->_get_cart_total();
		
		if(isset($this->_config["gateway"][$this->site_id])){ // Check if any gateways are enabled
			foreach($this->_config["gateway"][$this->site_id] as $gateway){
				
				if($gateway["enabled"] == 1){
					$str = 'Gateway_'.$gateway["code"];
					$tmp = new $str($total);
					// Check if the gateway supports 
					// subscription checkouts 
						
						$sub_available = 1;
						if($subscriptions == 1){
							if($tmp->subscription_enabled != 1){
								$sub_available = 0;
							}
						}
					
					if($tmp->cart_button == true && $sub_available == 1){
						// Pass config data to the button
							$config = array();
							if(isset($gateway["config_data"])){
								$config_data = $gateway["config_data"]; 
								$config["config_id"] = $gateway["config_id"];
								foreach($config_data as $c){
									$config[$c["code"]] = $c["value"];
								}
							}
							$output .= $tmp->cart_button($config);
					}
					$i++;
				}
			}
		}
		return $output;
	}
	
	function _shipping_options($data){
		
		$this->EE->load->model('product_model'); 
		$cart 	= $this->EE->product_model->cart_get();
		$output = '';
		$i 		= 0;
		$rates 	= array();
		
		foreach($this->_config["shipping"][$this->site_id] as $ship){
			
			if($ship["enabled"] == 1){
				$config = array();
				foreach($ship["config_data"] as $d){
					$config[$d["code"]] = $d["value"];
				}
				$str = 'Shipping_'.$ship["code"];
				$class = new $str();	
				$quote = $class->quote($data,$config);
				if($quote){
					$rates[] = 	array(
										'label' => $ship["label"], 
										'code' 	=> $ship["code"],
										'quote' => $quote
									);
				}
			}
		}
	
		// Adding a hook to modify the $rates array v1.1.0.1
			if($this->EE->extensions->active_hook('br_cart_shipping_rate') === TRUE){
				$rates = $this->EE->extensions->call('br_cart_shipping_rate', $rates); 
			}
		
		foreach($rates as $q)
		{
			$output .= '<p class="shipping">
							<label>'.$q["label"].'</label>';
			foreach($q["quote"] as $q){
				$hash = md5($ship["code"].$q["rate"].$i.time());
				$_SESSION["shipping"][$hash] = $q;
				$_SESSION["shipping"][$hash]["method"] = $ship["code"];
				$price = ($q["rate"] > 0) ? $this->_config["currency_marker"].$q["rate"].' - ' : '' ;
				$chk = ($i == 0) ? 'checked="checked"' : '';
				$output .= '<br />
							<input type="radio" name="shipping" class="shipping" value="'.$hash.'" id="shipping_'.$i.'" '.$chk.' />&nbsp;'.
							$price.$q["label"];
				$i++;
			}
			$output .= '</p>';
		}

		return $output;
	}

	/* 
	*  Create the price range size and buckets 
	 * 
	 * @param array product 
	 * @return array amount 
	 */
	
		function _price_range($prices,$hash){

			// If there is a filer applied then 
			// pull from session cache cause we've already 
			// run this routine
				if (isset($this->session->cache['_price_range'][$hash])){
					return $this->session->cache['_price_range'][$hash];
				}
			
			$max = max($prices);
			
			$power = ($this->EE->config->item('br_filter_power')) ? $this->EE->config->item('br_filter_power') : 10;
			
			if($max < 10){
				$power = 5;
			}elseif($max < 5){
				$power = 1;
			}
			
			$range = pow($power,(strlen(floor($max))-1));
			
			foreach($prices as $p){
				$key = floor($p/$range);
				if(isset($bucket[$key])){
					$bucket[$key]++;
				}else{
					$bucket[$key] = 1;
				}
			}
			ksort($bucket);
			$arr = array(
							'range' => $range,
							'bucket' => $bucket
						);

			// Set to session cache
				$this->session->cache['_price_range'][$hash] = $arr;
			
			return $arr;
		}
	
	function _layered_navigation($product,$hash,$category_id = 0){
		$this->EE->load->model('product_model');
		$url = $this->EE->uri->uri_to_assoc();
		$layered = array();
		
		$children = array();

		$child_cats = $this->EE->product_model->get_category_child($category_id);
		foreach($child_cats as $key => $val){
			$children[$key] = $val;
			$children[$key]["cnt"] = 0;
		}

		foreach($product as $p){
			if($amt = $this->_check_product_price($p)){
				$prices[] = $amt["price"];
				foreach($p["categories"] as $cat){
					if(isset($children[$cat])){
						$children[$cat]["cnt"]++;
					}
				}
			}
		}

		// Setup any disabled sort by options

		$disable = $this->EE->TMPL->fetch_param('disable');
		$disable = explode("|",$disable);

		// Include the categories unless disabled
			if(!in_array('category',$disable)){
				// All Categories
					$cat_list = array();
					if(count($children) > 0){
						foreach($children as $key => $val){
							if($val["cnt"] > 0){
								$cat_count[$key] = $val["cnt"];
								$cat_list[] = $this->EE->product_model->get_category($val["category_id"]); 
							}
						}
					}
					
				// Set the category filters 
					if(!isset($_SESSION[$hash]["category"])){
						$i = 0;
						$tmp = array();
						foreach($cat_list as $cat){
							if(isset($cat[0]["parent_id"])){
								#if($cat[0]["parent_id"] == 0){
									$tmp[$cat[0]["title"]] = array(
																"result_layered_title" => $cat[0]["title"],
																"result_layered_count" => "(".$cat_count[$cat[0]["category_id"]].")",
																"result_layered_link" => $this->_set_link($hash)."/category/".$cat[0]["category_id"] 
															);
									$i++;
								#}
							}
						}
						if(count($tmp) >= 1){
							ksort($tmp);
							$i = 0;
							foreach($tmp as $t){
								$items[$i] = $t;
								$i++;
							}
							$layered[] = array(
												'result_layered_label' => 'Category',
												"result_layered_item" => $items
											);	
						}
					}
			}

		// Include the prices unless disabled
			if(!in_array('price',$disable)){
				// Set the price ranges
					if(!isset($_SESSION[$hash]["range"])){
						if(isset($prices)){
							$price = $this->_price_range($prices,$hash);
							#if(count($price["bucket"]) > 1){
								$i = 0;
								$items = array();
								foreach($price["bucket"] as $key => $val){
									$lower = $this->_config["currency_marker"].floor($this->_currency_round($price["range"]*$key)).' '.lang('br_filter_to');
									if(($price["range"]*$key) == 0){
										$lower = lang('br_filter_under');
									}
																						
									$items[$i] = array(
															"result_layered_title" => 	$lower.
																						' '.
																						$this->_config["currency_marker"].
																						floor($this->_currency_round(($price["range"]*($key+1)))),
															"result_layered_count" => "(".$val.")",
															"result_layered_link" => $this->_set_link($hash)."/range/".$key 
														);
									$i++;
								}
					
								$layered[] = array(
													'result_layered_label' => 'Price',
													"result_layered_item" => $items
												);	
							#}
						}
					}
			}

		// Attributes 
			foreach($product as $p){
				if(isset($p["attribute"])){
					foreach($p["attribute"] as $val){
						if($val["filterable"] == 1){
							if($val["fieldtype"] == 'dropdown'){
								$value = $val["value"];
								if($value != ''){
									$attr_id = $val["attribute_id"];
									$attr[$attr_id][$value] = $value;
									$count[$attr_id][$value][] = true;
								}
							}
						}
					}

					if(isset($p["configurable"])){
						foreach($p["configurable"] as $c){
							$s = unserialize($c["attributes"]);
							foreach($s as $key=> $val){
								$filterable = $this->_check_attribute_filterable($key);
								if($filterable === TRUE){
									$attr[$key][$val] = $val;
									$count[$key][$val][] = true;
								}
							}
						}
					}
				}
			}
			
			if(isset($attr)){
				// sort things 
				$tmp = array();
				foreach($attr as $key => $val){
					sort($val);
					$tmp[$key] = $val; 
				}
				$attr = $tmp;

				$items = array();
				foreach($attr as $key => $val){
					$a = $this->EE->product_model->get_attribute_by_id($key);
					$i = 0;
					foreach($val as $v){
						if(isset($_SESSION[$hash]["code"][urlencode($v)])){
							$remItems[$key] = $key;
						}
						$items[$key]["label"] = $a["title"];
						$items[$key][$i] = array(
											"result_layered_title" => $v,
											"result_layered_count" => '', #'('.count($count[$key][$v]).')',
											"result_layered_link" => $this->_set_link($hash)."/code/".md5($a["attribute_id"]."_".$v)."/title/".urlencode($v)
										);
						$i++;
					}
				}

				// Here is where we weed out the selections
					if(isset($remItems)){
						foreach($remItems as $rem){
							unset($items[$rem]);
						}
					}

				if(isset($items)){
					foreach($items as $item){
						$label = $item["label"];
						unset($item["label"]);
						$layered[] = array(
												'result_layered_label' => $label,
												'result_layered_item' => $item
											);
					}
				}
			}
		return $layered;
	}
	
	/* 
		Filter search results down by page / sort / price / attribute
		Also sets the mode for viewing (grid|list) 
	*/ 

	function _filter_results($vars,$hash,$paginate = true){
		
		$url = $this->EE->uri->uri_to_assoc();
		$filters = array(); # Container for filters
		
		// If the url past the hash is empty then we'll reset
		// the filters
		
		$uri = $_SERVER["REQUEST_URI"];
		$check = explode($hash,$uri);

		if(isset($check[1]) && trim($check[1],"/") == ''){
			unset($_SESSION[$hash]);
		}
		
		// Set the defaulst for this hash
			if(!isset($_SESSION[$hash])){
				// Allow for parametersin the catalog tag
					$mode 	= ($this->EE->TMPL->fetch_param('mode')) ? $this->EE->TMPL->fetch_param('mode') : 'grid';
					$sort 	= ($this->EE->TMPL->fetch_param('sort')) ? $this->EE->TMPL->fetch_param('sort')	: 'relevance';
					$dir 	= ($this->EE->TMPL->fetch_param('dir'))  ? $this->EE->TMPL->fetch_param('dir')	: 'desc';
					$_SESSION[$hash] = array(
														'mode' 	=> $mode,
														'sort'	=> $sort,
														'dir' 	=> $dir
													);
			}
		
		if(isset($url["remove"])){
			if($url["remove"] == 'code'){
				unset($_SESSION[$hash]["code"][$url["title"]]);
			}else{
				unset($_SESSION[$hash][$url["remove"]]);
			}
		}
		
		// Set the mode 
			if(isset($url["mode"])){
				$mode = array('grid','list');
				if(in_array($url["mode"],$mode)){
					$_SESSION[$hash]["mode"] = $url["mode"];
				}
			}
			$vars[0]['mode'] = $_SESSION[$hash]["mode"];
			$vars[0]['link_grid'] = $this->_set_link($hash).'/mode/grid';
			$vars[0]['link_list'] = $this->_set_link($hash).'/mode/list';

		// Set the Sort 
			if(isset($url["sort"])){
				$sort_opts = array('relevance','price','name');
				if(in_array($url["sort"],$sort_opts)){
					$_SESSION[$hash]["sort"] = $url["sort"];
					$curr_page = 1; //Changed the sort so send page to page 1
				}
			}

			if(isset($url["dir"])){
				$dir = array('asc','desc');
				if(in_array($url["dir"],$dir)){
					$_SESSION[$hash]["dir"] = $url["dir"];
					$curr_page = 1; //Changed the sort so send page to page 1
				}
			}
			$vars[0]['sort_selected'] = $_SESSION[$hash]["sort"];
			$vars[0]['link_sort_relevance'] = $this->_set_link($hash).'/sort/relevance';
			$vars[0]['link_sort_price'] = $this->_set_link($hash).'/sort/price';
			$vars[0]['link_sort_name'] = $this->_set_link($hash).'/sort/name';
			
			// If sort by relevance 
				if($vars[0]['sort_selected'] == 'relevance'){
					if($_SESSION[$hash]["dir"] == 'asc'){
						// Reverse out the indexes
						$i = count($vars[0]["results"])-1;
						foreach($vars[0]["results"] as $r){
							$tmp[$i] = $r;
							$i--;
						}
						ksort($tmp);
						unset($vars[0]["results"]);
						$vars[0]["results"] = $tmp;
						$dir_link = 'desc';
					}else{
						// The results are naturally 
						// stored desc... Do nothin.
						$dir_link = 'asc';
					}
					$vars[0]['link_sort_relevance'] = $this->_set_link($hash).'/sort/relevance/dir/'.$dir_link;
				}
				
			// If sort by price 
				if($vars[0]['sort_selected'] == 'price'){
					foreach($vars[0]["results"] as $r){
						$amt = $this->_check_product_price($r);
						$tmp[$amt["price"]][$r["product_id"]] = $r;
					}
					if($_SESSION[$hash]["dir"] == 'asc'){
						$dir_link = 'desc';
						ksort($tmp);
					}else{
						$dir_link = 'asc';
						krsort($tmp);
					}
					$new = array();
					$i = 0;
					foreach($tmp as $t){
						foreach($t as $z){
							$new[$i] = $z;
							$i++;
						}
					}
					unset($vars[0]["results"]);
					$vars[0]["results"] = $new;
					// Reset the price link since we are price 
					// seleceted. We want to have the user toggle the 
					// direction by clicking the price again
						$vars[0]['link_sort_price'] = $this->_set_link($hash).'/sort/price/dir/'.$dir_link;
				}
			
			// If sort by relevance 
				if($vars[0]['sort_selected'] == 'name'){
					foreach($vars[0]["results"] as $r){
						$tmp[$r["title"]][$r["product_id"]] = $r;
					}
					if($_SESSION[$hash]["dir"] == 'asc'){
						$dir_link = 'desc';
						ksort($tmp);
					}else{
						$dir_link = 'asc';
						krsort($tmp);
					}
					$new = array();
					$i = 0;
					foreach($tmp as $t){
						foreach($t as $z){
							$new[$i] = $z;
							$i++;
						}
					}
					unset($vars[0]["results"]);
					$vars[0]["results"] = $new;
					// Reset the price link since we are price 
					// seleceted. We want to have the user toggle the 
					// direction by clicking the price again
						$vars[0]['link_sort_name'] = $this->_set_link($hash).'/sort/name/dir/'.$dir_link;
				
				}
	
		// Filter if there was no price for the member group
			foreach($vars[0]["results"] as $key => $val){
				$amt = $this->_check_product_price($val);
				if($amt === FALSE){
					unset($vars[0]["results"][$key]);	
				}
			}		
				
		// Filter down quantities 
			if($this->EE->config->item('br_display_out_of_stock') !== TRUE)
			{
				foreach($vars[0]["results"] as $key => $val){
					if($val["quantity"] <= 0){
						unset($vars[0]["results"][$key]);	
					}
				}
			}
			
			
		// Filter down categories
	
			$this->EE->load->model('product_model');
				
			if(isset($url["category"])){
				$sel = $url["category"] * 1;
				if($sel != 0){
					$_SESSION[$hash]["category"] = $sel;
				}
			}
			
			if(isset($_SESSION[$hash]["category"])){
				$i = 0;
				$tmp = array();
				foreach($vars[0]["results"] as $r){
					$filter = 0;
					foreach($r["categories"] as $cat){
						if($cat == $_SESSION[$hash]["category"]){
							$filter = 1;
						}
					}
					if($filter == 1){
						$tmp[$i] = $r;
						$i++;
					}
				}
				unset($vars[0]["results"]);
				$vars[0]["results"] = $tmp;
								
				$cat = $this->EE->product_model->get_category($_SESSION[$hash]["category"]);
				$filters[] = array(
															'filter_set_section' => 'Category',
															'filter_set_label' => $cat[0]["title"],
															'filter_set_remove' => $this->_set_link($hash).'/remove/category'
														);
			}


		// Filter price range 
			if(isset($url["range"])){
				$sel = $url["range"] * 1;
				$_SESSION[$hash]["range"] = $sel;
			}
			if(isset($_SESSION[$hash]["range"])){
				foreach($vars[0]["results"] as $p){
					if($amt = $this->_check_product_price($p)){
						$prices[] = $amt["price"];
					}
				}				
				
				$price = $this->_price_range($prices,$hash);

				$lower = $_SESSION[$hash]["range"] * $price["range"];
				$upper = ($_SESSION[$hash]["range"] * $price["range"]) + $price["range"];
				$i=0;
				$tmp = array();
				
				foreach($vars[0]["results"] as $r){
					if($amt = $this->_check_product_price($r)){
						if($amt["price"] >= $lower && $amt["price"] < $upper){
							$tmp[$i] = $r;
							$i++;
						}
					}
				}
				
				unset($vars[0]["results"]);
				$vars[0]["results"] = $tmp;
				
				if($lower == 0){
					$lower = lang('br_filter_under');
				}else{
					$lower = $this->_config["currency_marker"].$lower.' '.lang('br_filter_to');
				}
				
				$filters[] = array(
															'filter_set_section' 	=> 'Price',
															'filter_set_label' 		=> 	$lower.
																						' '.
																						$this->_config["currency_marker"].
																						$this->_currency_round($upper),
															'filter_set_remove' 	=> $this->_set_link($hash).'/remove/range'
														);
			}

		// Filter down attributes 
			
			// We allow for passing attributes via the catalog tag
			
			if(isset($url["code"])){
				$_SESSION[$hash]["code"][$url["title"]] = $url["code"];
			}	

			if(isset($_SESSION[$hash]["code"])){
				foreach($_SESSION[$hash]["code"] as $code){
					foreach($vars[0]["results"] as $key => $val){
						$attr = $this->_check_attr($val);
						if(!isset($attr[$code])){
							unset($vars[0]["results"][$key]);
						}else{
							$filters[$code] = array(
													'filter_set_section' 	=> $attr[$code]["title"],
													'filter_set_label' 		=> $attr[$code]["label"],
													'filter_set_remove' 	=> $this->_set_link($hash).'/remove/code/hash/'.$code.'/title/'.urlencode($attr[$code]["label"])
												);
						}
					}	
				}
			}	
				
		// Add Filters
			$i = 0;
			$tmp = array();
			foreach($filters as $f){
				$tmp[$i] = $f;
				$i++;
			}
			if(isset($tmp)){
				$vars[0]["result_filter_set"] = $tmp;
			}
			
			// Set a variable so we know if we have filters in place
				$vars[0]["result_has_filter"] = (count($vars[0]["result_filter_set"]) == 0) ? 0 : 1;
			
		// Set the new total_results number 
			$vars[0]["total_results"] = count($vars[0]["results"]);

	// PAGINATE // 
	// The Pagination is only set for the display loop
	// it is not set on the layered nav loop so we get a 
	// full result set.
		
		if($paginate == true){
			// Filter down pagination
				$vars[0]['link_show_all'] = $this->_set_link($hash).'/page/all';
				if(isset($url["page"])){
					if($url["page"] == 'all'){
						$_SESSION[$hash]["page"] = 'all';
					}else{
						$_SESSION[$hash]["page"] = (1*$url["page"] == 0) ? 1 : $url["page"];
					}
				}else{
					$_SESSION[$hash]["page"] = 1;
				}
				
				// 
					$curr_page = $_SESSION[$hash]["page"];
					$lim_total = $vars[0]["total_results"];
					$total_pages = ceil($lim_total / $this->_config["result_per_page"]);
					$back[0] = array(); // container for the back button
					$next[0] = array(); // container for the next button
					$pages[0] = array(); 
					
				if($curr_page != 'all'){
					$lim_lower = ($curr_page - 1) * $this->_config["result_per_page"];
					$lim_upper = ($curr_page) * $this->_config["result_per_page"];
					for($i=0;$i<$lim_lower;$i++){
						unset($vars[0]["results"][$i]);
					}
					for($i=$lim_upper;$i<$lim_total;$i++){
						unset($vars[0]["results"][$i]);
					}
					
					if($total_pages > 1){
						for($i=0;$i<$total_pages;$i++){
							$link_active = (($i+1) == $curr_page) ? 'yes' : 'no' ;
							$pages[$i] = array(
												'link_page' => $this->_set_link($hash).'/page/'.($i+1),
												'link_active' => $link_active,  
												'page_number' => ($i+1));
						}
						if(count($pages > $this->_config["result_paginate"])){
							$pad = floor(($this->_config["result_paginate"]-1)/2);
							$high_limit = count($pages)-1;
							
							if($curr_page < $total_pages){
								$next[0] = array('link_next' => $this->_set_link($hash).'/page/'.($curr_page+1));
							}
							
							if($curr_page > 1){
								$back[0] = array('link_back' => $this->_set_link($hash).'/page/'.($curr_page-1));
							}
							
							if($curr_page > 3){
								$low_index = ($curr_page - $pad)-1;
								$high_index = ($curr_page + $pad)-1;
								if($high_index > $high_limit){
									$low_index = $high_limit - ($this->_config["result_paginate"]-1);
									$high_index = $high_limit; 
								}
							}else{
								$low_index = 0;
								$high_index = $this->_config["result_paginate"]-1;
							}
							if($low_index >= 1){
								# Remove lower pages
								for($i=0;$i<$low_index;$i++){
									unset($pages[$i]);
								}
							}
							for($i=$high_index;$i<$total_pages;$i++){
								unset($pages[$i+1]);
							}
						}
						// Rework the indexes 
							$tmp = array();
							$i = 0;
							foreach($pages as $p){
								$tmp[$i] = $p;
								$i++;
							}
							unset($pages);
							$pages = $tmp;
						
						$show_paginate = 'yes';
					}else{
						$show_paginate = 'no';
					}
					
					$vars[0]['result_paginate'][0] = array(	
															'show_paginate' => $show_paginate,
															'back'=> $back, 
															'next'=> $next, 
															'pages' => $pages
															);
					// Set the page range 
						if($lim_upper > $lim_total){
							$lim_upper = $lim_total;
						}
						$vars[0]['result_range'] = ($lim_lower+1).'-'.$lim_upper;
				
				}else{
					$pages = array();
					for($i=0;$i<$total_pages;$i++){
						$pages[$i] = array(
											'link_page' => $this->_set_link($hash).'/page/'.($i+1),
											'link_active' => 'no',  
											'page_number' => ($i+1));
					}
					$vars[0]['result_paginate'][0] = array(	
															'show_paginate' => 'no',
															'back'=> $back, 
															'next'=> $next, 
															'pages' => $pages
															);
					$vars[0]['result_range'] = count($vars[0]["results"]);
				}
	
				$vars[0]['result_paginate_bottom'][0] = $vars[0]['result_paginate'][0];

			// We need to reset the count 
			// on the array keys to play 
			// nicely with the parse function
				$tmp = array();
				$i = 0;
				foreach($vars[0]["results"] as $val){
					$tmp[$i] = $val;
					$i++;
				}
				unset($vars[0]["results"]);
				$vars[0]["results"] = $tmp;
		}
		
		foreach($vars[0]["results"] as $key => $val){
			// Check the price setup 
				if($amt = $this->_check_product_price($val)){
					$vars[0]["results"][$key]["price_html"] = '<p class="price">'.$this->_format_money($amt["price_html"]).'</p>';
				}
			// Set default images
			 	if($vars[0]["results"][$key]["image_large"] == ''){
			 		$vars[0]["results"][$key]["image_large"] = 'products/noimage.jpg';
			 		$vars[0]["results"][$key]["image_large_title"] = '';
			 	}
			 	if($vars[0]["results"][$key]["image_thumb"] == ''){
			 		$vars[0]["results"][$key]["image_thumb"] = 'products/noimage.jpg';
			 		$vars[0]["results"][$key]["image_thumb_title"] = '';
			 	}
		}
		return $vars;
	}
	
	/* 
	 * Check product price 
	 * 
	 * @param array product 
	 * @return array amount 
	 */
		function _check_product_price($p)
		{
			$group_id = $this->EE->session->userdata["group_id"];
			$amt = array(
							'on_sale' 			=> FALSE, 
							'base' 				=> '',
							'price' 			=> '',
							'price_html'		=> '',
							'price_start'		=> NULL,
							'price_end'			=> NULL,
							'sale_price_start'	=> NULL,
							'sale_price_end'	=> NULL 
						);

			// Deal with our price matrix 
				foreach($p["price_matrix"] as $price){
				 	if(	$price["group_id"] == 0 || 
				 		$price["group_id"] == $group_id){

						$valid 	= 1;
						
						// Check Start time
							if($price["start_dt"] != "0000-00-00 00:00:00" && $price["start_dt"] != "")
							{
								$start 	= date("U",strtotime($price["start_dt"]));
								if(time() < $start){
									$valid = 0;
								}
							}
						// Check End Time 
							if($price["end_dt"] != "0000-00-00 00:00:00" && $price["end_dt"] != "")
							{
								$end 	= date("U",strtotime($price["end_dt"]));
								if($end != 0 && time() > $end){
									$valid = 0;
								}
							}
						
						if($valid == 1){
							$amt['base'] 		= $price["price"];
							$amt['price'] 		= $price["price"];
							$amt['price_html']	= '<p class="price">'.$this->_format_money($price["price"]).'</p>';
				 			$amt['price_start'] = ($price["start_dt"] == "0000-00-00 00:00:00") ? null : strtotime($price["start_dt"]);
				 			$amt['price_end'] 	= ($price["end_dt"] == "0000-00-00 00:00:00") ? null : strtotime($price["end_dt"]);
				 		}
				 	}
				 }	
			
			// 
			foreach($p["sale_matrix"] as $sale){
				
				$valid 	= 1;
				
				// Check Start time
					if($sale["start_dt"] != "0000-00-00 00:00:00" && $sale["start_dt"] != "")
					{
						$start 	= date("U",strtotime($sale["start_dt"]));
						if(time() < $start){
							$valid = 0;
						}
					}
				// Check End Time 
					if($sale["end_dt"] != "0000-00-00 00:00:00" && $sale["end_dt"] != "")
					{
						$end 	= date("U",strtotime($sale["end_dt"]));
						if($end != 0 && time() > $end){
							$valid = 0;
						}
					}
				
				// Setup a sale price if valid		 
				 	if(($valid == 1) && ($sale["group_id"] == 0 || $sale["group_id"] == $group_id) && ($amt["price"] > $sale["price"]))
				 	{
						// Container for the original price
				 			$original 					= $amt['price'];
				 		// 
					 		$amt['on_sale'] 			= TRUE; 
							$amt['base'] 				= $amt["price"];
							$amt['price'] 				= $sale["price"]; 
							$amt['price_html'] 			= '	<p class="price">
																<span class="original">'.$this->_format_money($original).'</span>
																<span class="sale">'.$this->_format_money($sale["price"]).'</span>
															</p>';  
							$amt['sale_price'] 			= $sale["price"];
							$amt['sale_price_start'] 	= ($sale["start_dt"] == "0000-00-00 00:00:00") ? null : strtotime($sale["start_dt"]);
				 			$amt['sale_price_end'] 		= ($sale["end_dt"] == "0000-00-00 00:00:00") ? null : strtotime($sale["end_dt"]);
					}
			}
			
			// Insert Check Product 
			if($this->EE->extensions->active_hook('br_check_product_price_end') === TRUE){
				$amt = $this->EE->extensions->call('br_check_product_price_end', $amt); 
			}
			
			if(!isset($amt["price"])){
				return false;
			}

			return $amt;		
		}

	/* 
	 * 
	 * 
	*/
		function _check_attr($p)
		{
			$this->EE->load->model('product_model');
			$attr = array();
			foreach($p["attribute"] as $val){
				if($val["fieldtype"] == 'dropdown'){
					$value = $val["value"];
					if($value != ''){
						$hash = md5($val["attribute_id"].'_'.$value);
						$attr[$hash] = array(
										"hash" => $hash,
										"title" => $val["title"],
										"label" => $value
										);
						
					}
				}
			}
			
			if(isset($p["configurable"])){
				foreach($p["configurable"] as $c){
					$s = unserialize($c["attributes"]);
					foreach($s as $key=> $val){
						$b = $this->EE->product_model->get_attribute_by_id($key);
						$hash = md5($key.'_'.$val);
						$attr[$hash] = array(
											"hash" => $hash,
											"title" => $b["title"],
											"label" => $val 
											);
					}
				}
			}
			return $attr;
		}
	
		function _set_link($hash)
		{
			$url = $this->EE->uri->uri_to_assoc();
			for($i=1;$i<10;$i++){
				$seg = $this->EE->uri->segment($i);
				if($seg != $hash){
					$part[] = $seg;
				}else{
					$i=10;
				}
			}
			$part[] = $hash;
			return join($part,"/");
		}
	
	function _build_config_opts($p)
	{
		$this->EE->load->model('product_model');
		$first = array();
		$row = '';
		$js = '';
		foreach($p["configurable"] as $c){
			// Make sure that we have it
			// in stock before building it as 
			// an option
				if($c["qty"] >= 1){
					$tmp = unserialize($c["attributes"]);
					$i = 0;
					foreach($tmp as $key => $val){
						$label[$i] = $key;
						if($c["adjust"] != 0 && $i == (count($tmp) - 1)){
							$attr[$i] = urldecode($val).' +'.$c["adjust"];
						}else{
							$attr[$i] = urldecode($val);
						}
						$i++;
					}
					
					$js = '';
					$cnt = 0;
					if(count($attr) == 1){
						// There is only one option
						$first[$c["configurable_id"]] = urldecode($attr[0]);
					}else{
						// Multiple config array
						$first[$attr[0]] = urldecode($attr[0]);
						foreach($attr as $a){
							if($cnt != 0){
								$row .= "configOpts";
								for($j=0;$j<$cnt;$j++){
									// I'm sure this can be done better 
									// but we need to make sure that a quote 
									// doesn't break our javascript
										if(strpos($attr[$j],"'") !== FALSE){
											$row .= '.forValue("'.$attr[$j].'")';
										}else{
											$row .= ".forValue('".$attr[$j]."')";
										}
								}
								if($cnt == (count($attr)-1)){
									$val = $c["configurable_id"];
								}else{
									$val = $a;
								}
								// Same as above (quotes in javascript)
									$row .= ".addOptionsTextValue(\"".$a."\",\"".$val."\");\n";
								
								$js .= $row;
							}
							$cnt++;
						}
					}
				}
		}
		$i = 0;
		$list = array();
		$config = array();
		if(isset($label)){
			foreach($label as $l){
				$a = $this->EE->product_model->get_attribute_by_id($l);
				$labels[] = $a["title"];
				
				// if its the first one then put in the default values 
					if($i ==0){
						$opts = '<option value=""></option>';
						foreach($first as $key => $val){
							$opts .= '<option value="'.$key.'">'.$val.'</option>'; 
						}
						$select = '<select name="'.$p["product_id"].'_configurable_'.$i.'" class="required">'.$opts.'</select>';
					}else{
						$select = '<select name="'.$p["product_id"].'_configurable_'.$i.'" class="required"><option>-</option></select>';
					}
				$list[] = '"'.$p["product_id"].'_configurable_'.$i.'"';
				$config[$i] = array(
									'configurable_label' => $a['title'],
									'configurable_select' => $select 
									);
				$i++;
			}
		}
		// If we didn't build js then its possibly sold out
		if($js == ''){
			if(count($config) == 0){
				$js = "$(function(){ $('.btn,.fancybox').hide(); });";
			}
		}else{
			$js = '	$(function(){
						var configOpts = new DynamicOptionList('.join(',',$list).');
						'.$js.'
						initDynamicOptionLists();
					});';
		}
		$config_opts = array(	
								'js' => $js,
								'config' => $config
							);
		return $config_opts;
	}
	
	function _build_product_attributes($p){
		$attr = array();
		if(isset($p["attribute"])){
			foreach($p["attribute"] as $p){
				if(trim($p["value"] != '')){
					if($p["fieldtype"] == 'file'){
						$values = unserialize($p["value"]);
						if(isset($values)){
							$link = '<a href="'.$this->_config["media_url"].'file/'.$values["file"].'" target="_blank">'.$values["title"].'</a>';
						}
						$tmp  = array(
									'label' => $p["label"],
									'value' => $link,
									'file'	=> $this->_config["media_url"].'file/'.$values["file"]
									);
					}elseif($p["fieldtype"] == 'multiselect'){
						$values = unserialize($p["value"]);
						$list = join(", ",$values);
						$tmp  = array(
									'label' => $p["label"],
									'value' => $list,
									'file' 	=> '' 
									);
					}else{
						$tmp = array(
									'label' => $p["label"],
									'value' => $p["value"],
									'file' 	=> '' 
									);
					}
					$attr[] = $tmp;
					$this->attr_single["attr:".$p["code"]][] = $tmp;
				}
				
			}
		}
		// set empty attr 
			$all_attributes = $this->EE->product_model->get_attributes();
			foreach($all_attributes as $a){
				if(!isset($this->attr_single["attr:".$a["code"]])){
					$this->attr_single["attr:".$a["code"]][] = array(
																		'label' => '',
																		'value' => '',
																		'file' 	=> '' 
																	);  
				}
			}
		return $attr;
	}
	
	function _get_config_id($data,$product){
		$configurable_id = 0;
		$options = '';
		$ksu = '';
		foreach($data as $key => $val){
			if(strpos($key,"configurable_") !== false){
				$attr[str_replace("configurable_","",$key)] = $val;
			}
		}
		if(!isset($attr)){
			return false;
		}
		foreach($product["configurable"] as $c){
			if($c["configurable_id"] == $attr[count($attr)-1]){
				$adjust = $c["adjust"];
				$sku = $c["sku"];
				$configurable_id = $c["configurable_id"];
			}
		}
		if($configurable_id == 0){
			return false;
		}
		
		// Build the return array
			$arr = array(
							'sku' => $sku, 
							'configurable_id' => $configurable_id,
							'adjust' => $adjust 
						);
		return $arr;
	}
	
	// Get Cart Tax & Total 
		function _get_cart_tax($country,$state,$zip,$address1='',$address2=''){
			$this->EE->load->model('product_model');
			$cart = $this->EE->product_model->cart_get();
			
			$this->EE->load->model('tax_model');
			$rate = $this->EE->tax_model->get_tax($country,$state,$zip);
			
			// Add extension hook to manipulate emails before they are sent out
			if($this->EE->extensions->active_hook('br_cart_tax_rate') === TRUE){
				$data = array(
									"cart" 		=> $cart,
									"country"	=> $country,
									"state"		=> $state, 
									"zip"		=> $zip,
									"address1"	=> $address1,
									"address2"	=> $address2 
								);
				$rate = $this->EE->extensions->call('br_cart_tax_rate', $data); 
			}

			$taxable = 0;
			foreach($cart["items"] as $item){
				if($item["taxable"] == 1){
					$sub = $item["quantity"] * ($item["price"] - $item["discount"]);
					$taxable = $taxable + $sub;
				}
			} 
			$tax = $taxable * $rate / 100;
			return $this->_currency_round($tax);
		}
	
		function _get_cart_total(){
			$total = 0;
			$this->EE->load->model('product_model');
			$cart = $this->EE->product_model->cart_get();
			if(!isset($cart["items"])){
				return 0;
			}else{
				foreach($cart["items"] as $val){
					$total += ($val["quantity"] * $val["price"]);
				}
				if(isset($_SESSION["discount"])){
					$discount = $this->_get_cart_discount();
					if($total < $discount){
						$discount = $total;
					}
					$total = $total - $discount;
				}
				if(isset($_SESSION["tax"])){
					$tax = $_SESSION["tax"];
				}else{
					$tax = 0;
				}
				$total = $total + $tax;
				return $this->_currency_round($total);
			}
		}		

	// Get Cart Discount 
	
		function _get_cart_discount(){
			$this->EE->load->model('product_model');
			$cart = $this->EE->product_model->cart_get();
			$discount = 0;
			// Cart is empty
				if(!isset($cart["items"])){
					return 0;
				}
			if(isset($_SESSION["discount"])){
				if($_SESSION["discount"]["discount_type"] == 'item'){
					foreach($cart["items"] as $item){
						$discount += ($item["discount"] * $item["quantity"]);
					}
				}else{
					// The discount is based on the cart total
						if($_SESSION["discount"]["code_type"] == 'percent'){
							$subtotal = 0;
							foreach($cart["items"] as $val){
								$subtotal += ($val["quantity"] * $val["price"]);
							}
							$discount = $subtotal * ($_SESSION["discount"]["amount"] / 100);
						}else{
							$discount = $_SESSION["discount"]["amount"];
						}
				}
			}
			return $this->_currency_round($discount);
		}
	
	// Checkout Function to process payment
		function _process_payment($data){
			
			$this->EE->load->model('order_model');
			
			// Hook option to modify the $data array before the 
			// payment is processed
				if($this->EE->extensions->active_hook('br_process_payment_before') === TRUE){
					$data = $this->EE->extensions->call('br_process_payment_before', $data); 
				}
		
				// Get the gateway code
					$code = $this->EE->order_model->_get_gateway($data["gateway"]);
				
				// Config data for the given code
					$config = array();
					if(isset($this->_config["gateway"][$this->site_id][$code]["config_data"])){
						$config_data = $this->_config["gateway"][$this->site_id][$code]["config_data"]; 
						foreach($config_data as $c){
							$config[$c["code"]] = $c["value"];
						}
					}
									
				// Process Gateway
					$str = 'Gateway_'.$code;
					$tmp = new $str();
					$trans = $tmp->process($data,$config);
			
			// Hook option to modify the $trans array after the 
			// payment has been processed
				if($this->EE->extensions->active_hook('br_process_payment_after') === TRUE){
					$trans = $this->EE->extensions->call('br_process_payment_after', $trans); 
				}

			// Return Response
				return $trans;
		}

	// Checkout Function to process subscriptions
		function _process_subscription($data){
			
			$this->EE->load->model('order_model');
			$this->EE->load->model('subscription_model');
			
			// Get the gateway code
				$code = $this->EE->order_model->_get_gateway($data["gateway"]);
			
			// Config data for the given code
				$config = array();
				if(isset($this->_config["gateway"][$this->site_id][$code]["config_data"])){
					$config_data = $this->_config["gateway"][$this->site_id][$code]["config_data"]; 
					foreach($config_data as $c){
						$config[$c["code"]] = $c["value"];
					}
				}
								
			// Process Gateway
				$str = 'Gateway_'.$code;
				$tmp = new $str();
				$trans = array();
				$i = 0;
				foreach($data["cart"]["items"] as $item){
					if(count($item["subscription"]) > 0){
						$trans[$i] = $tmp->process_subscription($item,$data,$config);
						$i++;
					}
				}

			// Return Response
				return $trans;
		}

	/* Get the array information for the help files */
	
		function _get_sidebar_help(){
			// set a flag for returning from cache
				$use_cache = 0;
			if($resp = read_from_cache('dashboard_help')){
				$a = explode('|',$resp);
				$life = time() - $a[0];
				if($life < (60*60*24*30)){
					$response = ltrim($resp,$a[0].'|');
					$use_cache = 1;
				}
			}
			if($use_cache == 0){
				$post = array('host'=>$_SERVER["HTTP_HOST"],'license'=>$this->_config["store"][$this->site_id]["license"]);
				$post_str = '';
				foreach($post as $key => $val){
					$post_str .= $key.'='.$val.'&';
				}
				$post_str = rtrim($post_str,'$');
				$ch = curl_init('http://www.brilliantretail.com/dashboard_help.php');
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$post_str);
				$response = urldecode(curl_exec($ch));
				save_to_cache('dashboard_help',time().'|'.$response);
			}	
			return json_decode($response,true);	
		}
	
	/* Send Emails */	
	
		function _send_email($temp, $vars){

			// Load the libraries
				$this->EE->load->model('email_model');
				$this->EE->load->library('email');
				$this->EE->load->library('extensions');
				$this->EE->load->library('template'); 
				
			// Set some default variables
				$vars[0]["media"] 			= rtrim($this->_config["media_url"],'/');
				$vars[0]["site_name"] 		= $this->EE->config->item('site_name');
				$vars[0]["site_url"] 		= rtrim($this->EE->config->item('site_url'),'/').'/'.rtrim($this->EE->config->item('site_index'));
				$vars[0]["currency_marker"] = $this->_config["currency_marker"];
				
			// Get the email 			
				$email 		= $this->EE->email_model->get_email($temp);
				$subject 	= $this->EE->template->parse_variables($email["subject"], $vars);
				
					// Do we allow locate notifications?
						if($this->EE->config->item('br_save_notificaion_files') == TRUE){
							// Do we have a local version?
								$short_name = $this->EE->config->item("site_short_name");
								$fl = PATH_THIRD.'_local/brilliant_retail/notifications/'.$short_name.'/'.$temp.'.html';
								if(file_exists($fl)){
									// File helper
									$this->EE->load->helper('file');
									$email["content"] = read_file($fl);
								}
						}
				
				$output 	= $this->EE->template->parse_variables($email["content"], $vars);
				
				// Pass output to parse method by reference 
					$this->EE->template->parse($output);

			// Add extension hook to manipulate emails before they are sent out
				if($this->EE->extensions->active_hook('br_email_send_before') === TRUE){
					$output = $this->EE->extensions->call('br_email_send_before', $output); 
				}
				
			// Send it
				$this->EE->email->mailtype = 'html';	
				$this->EE->email->debug = TRUE;	
				$this->EE->email->from($email["from_email"],$email["from_name"]);
				$this->EE->email->to($vars[0]["email"]); 
				if($email["bcc_list"] != ''){
					$list = explode(',',$email["bcc_list"]);
					$this->EE->email->bcc($list);
				}
				$this->EE->email->subject($subject);
				$this->EE->email->message($output);
				if($this->EE->email->Send()){
					return true;
				}
		}
		
	/**
	* Check to see if the attribute is filterable
	* 
	* @access	private
	* @param	int
	* @return	true or false
	*/	
		function _check_attribute_filterable($attr_id){
			$attr = $this->EE->product_model->get_attribute_by_id($attr_id);
			if($attr["filterable"] == 1){
				return TRUE;
			}else{
				return FALSE;
			}
		}
}