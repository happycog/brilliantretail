<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010-2011, Brilliant2.com 	*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.0 Beta							*/
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

class Product_model extends CI_Model {

	private $cats;
	/**
	* Get products into an array
	*
	* The method attempts to get the products by individual cached files  
	*
	* @access	public
	* @param	int,int,int
	* @return	array
	*/
		public function get_products($product_id = '' , $disabled = '',$cat='')
		{
			if($product_id != ''){
				// Try to return from cache	
					if($str=read_from_cache('product_'.$product_id)){
						$arr[0] = unserialize($str);
						// only return enabled cached products
							if($arr[0]["enabled"] == 1){
								return $arr;
							}
					}
				
				// Get the specific product id
					$this->db->where('product_id',$product_id);
			}
			if($disabled == ''){
				$this->db->where('enabled >',0);
			}
			
			$this->db->from('br_product p');
			
			if ($cat!="")
			{
				$this->db->join("br_product_category c","p.product_id=c.product_id");
				$this->db->where("c.category_id",$cat);
			}
			
			$this->db->where('p.site_id',$this->config->item('site_id'));
			
			$this->db->order_by('p.product_id','desc');
			
			$query = $this->db->get();
			
			$products = array();
			$i = 0;
			foreach ($query->result_array() as $row){
				
				// General Product Details 
					$products[$i] = $row;
				
				// Get Product Price
					$products[$i]["price_matrix"] = $this->get_product_price($row["product_id"],1);

				// Get Product Price
					$products[$i]["sale_matrix"] = $this->get_product_price($row["product_id"],2);
				
				// Product Categories 
					$products[$i]["categories"] = $this->get_product_categories($row["product_id"]);
					
				// Product Attributes 
					$attributes = $this->get_attributes($row["attribute_set_id"],$row["product_id"]);
					$products[$i]["attributes"] = '<div id="product_attributes">';
					$j=0;
					foreach($attributes as $attr){
						$products[$i]["attributes"] .= ' 	<p class="label">'.$attr["title"].'</p>
															<p>'.$attr["value"].'<p>';
						$products[$i]["attribute"][$j] = $attr;
						$products[$i]["attribute"][$j]["label"] = $attr["title"];
						$j++;
					}
					$products[$i]["attributes"] .= '</div>';
				
				// Product Images 	
					$img = $this->get_product_images($row["product_id"]);
					$k = 0;
					$products[$i]["image_large"] = '';
					$products[$i]["image_thumb"] = '';
					foreach($img as $key => $val){
						// Set the name based on the key 
						// Integers are the individual images 
						// Non-int's are the thumb and large files
							if(is_integer($key)){
								$products[$i]['images'][$k] = $val;
								$k++;
							}else{
								$products[$i][$key] = $val;
							}
					}
					
				// Product Images 	
					$img = $this->get_product_images($row["product_id"],false);
					$k = 0;
					foreach($img as $key => $val){
						if(is_integer($key)){
							if($val["exclude"] == 1){
								$products[$i]['images_excluded'][$k] = $val;
								$k++;
							}
						}
					}
				
				// Product Related 
					$products[$i]["related"] = $this->get_product_related($row["product_id"]);
	
				// Product Options 
					$products[$i]["options"] = $this->get_product_options($row["product_id"]);
				
				if($row["type_id"] == 2){
					$products[$i]["bundle"] = $this->get_product_bundle($row["product_id"]);
				}
				
				if($row["type_id"] == 3){
					$products[$i]["configurable"] = $this->get_product_configurable($row["product_id"]);
				}
				
				if($row["type_id"] == 4){
					$products[$i]["download"] = $this->get_product_download($row["product_id"]);
				}
				
				if($row["type_id"] == 6){
					$products[$i]["subscription"] = $this->get_product_subscription($row["product_id"]);
				}
				
				save_to_cache('product_'.$row["product_id"],serialize($products[$i]));
				$i++;
			}
			return $products;
		}
	
	function get_product_collection($search,$limit=0,$offset=0,$sort,$dir,$cat,$prefix='exp_'){
		// Get a simple count of all products
			$sql = "SELECT 
						count(product_id) as cnt 
					FROM 
						".$prefix."br_product ";
			$query = $this->db->query($sql);
			$rst = $query->result_array();
			$total = $rst[0]["cnt"];
						
		// Create a SQL statement
			$sql = "SELECT 
						SQL_CALC_FOUND_ROWS 
						p.enabled, 
						p.title, 
						p.quantity,
						p.type_id,   
						p.product_id  
					FROM 
						".$prefix."br_product p "; 
			if($cat != ''){
				$sql .= ", 	".$prefix."br_product_category c 
							WHERE p.product_id = c.product_id 
							AND
								c.category_id = ".$cat." ";
			}else{
				$sql .= "WHERE 1 = 1 ";
			}
			
			if(trim($search) != ''){
				$sql .= " AND 
							( 	title LIKE '%".mysql_real_escape_string($search)."%' 
								||
							  	sku LIKE '%".mysql_real_escape_string($search)."%'
							)";
			}
			
			$sql .= " ORDER BY ".($sort+1)." ".$dir;

			if($limit != 0){
				$sql .= " LIMIT ".$offset.",".$limit;
			}
		
		// Run the sql
			$query = $this->db->query($sql);
			$rst = $query->result_array();
			
			$products = array(
								"total"		=> $total,
								"results" 	=> $rst
							);

		// Get the total without LIMIT restrictions
			$query = $this->db->query("SELECT FOUND_ROWS() as dTotal");
			$rst = $query->result_array();
			$products["displayTotal"] = $rst[0]["dTotal"];

		// Get the count of ALL 
			return $products;
	}


	/**
	* 
	*
	* @access	public
	* @param	int,boolean
	* @return	void
	*/
		function get_products_by_feed($feed_id,$nojoin=false)
		{
		
			if(!$nojoin){
				$this->db->select("p.*");
				$this->db->from("br_product_feeds f");
				$this->db->join("br_product p","f.product_id = p.product_id");
			}
			else
			{
			$this->db->select("*");
			$this->db->from("br_product_feeds f");
			}
			$this->db->where("f.feed_id",$feed_id);
			
			$query = $this->db->get();
			
			return $query->result_array();
		}

	/**
	* Get feed by product
	*
	* @access	public
	* @param	int
	* @return	array
	*/	
		function get_feed_id_by_product($product_id)
		{
			$this->db->select("feed_id");
			$this->db->from("br_product_feeds");
			$this->db->where("product_id",$product_id);
			
			$query = $this->db->get();
			
			return $query->result_array();
		}
	
	function remove_product_from_feed($product_id)
	{
		$this->db->delete('br_product_feeds', array('product_id' => $product_id));
	}
	
	function add_product_to_feed($feed)
	{
		$this->db->insert('br_product_feeds',$feed);
	}
	
	function get_low_stock($threshold)
	{
		$this->db->select("product_id,title,sku,quantity");
		$this->db->from("br_product");
		$this->db->where("type_id","1"); //Only Basic Products
		$this->db->where("enabled","1"); //Only Enabled Products
		$this->db->where("quantity <",$threshold); //Only Basic Products
		
		$query = $this->db->get();
	
			$products = array();
			
			return $query->result_array();
	}
	
	function get_product_basic($product_id)
	{
		$this->db->select('*');
		$this->db->where('product_id',$product_id);
		$this->db->where('enabled >=',0);
		$this->db->from('br_product');
		$query = $this->db->get();

		$products = array();
		foreach ($query->result_array() as $row){
			// General Product Details 
				$products = $row;
		}
		return $products;
	}

	function get_product_thumbnail($product_id)
	{
		$thumb = '';
		$p = $this->get_product($product_id);
		return $thumb;
	}
	
	function get_product_by_key($key){ 
		$this->db->select('*');
		$this->db->where('url',$key);
		$this->db->where('enabled >',0);
		$this->db->from('br_product');
		$this->db->order_by('product_id','desc');
		
		$query = $this->db->get();
		if($query->num_rows() == 1){
			$product_id = $query->row("product_id");
			return $this->get_products($product_id);
		}else{
			return false;
		}
	}
	
	function get_product_by_category($category,$disabled=''){ 
		$this->db->where('category_id',$category) 
				->from('br_product_category')
				->order_by('sort_order','asc')
				->order_by('product_id','desc');
		
		$query = $this->db->get();
		$products = array();
		$i = 0;
		if($query->num_rows() == 0){
			return $products;
		}
		foreach ($query->result_array() as $row){
			$p = $this->get_products($row["product_id"],$disabled);
			if(isset($p[0])){
				foreach($p[0] as $key => $val){
					$products[$i]['row_count'] = ($i + 1); 
					$products[$i][$key] = $val;
					$products[$i]['sort_order'] = $row['sort_order']; 
				}	
				$i++;
			}
		}
		return $products;
	}
	
	function get_config_product($id){
		$product = array();
		$this->db->where('configurable_id',$id)
				->from('br_product_configurable');
		$query = $this->db->get();
		$products = array();
		$i = 0;
		if($query->num_rows() == 0){
			return $products;
		}
		foreach ($query->result_array() as $key => $val){
			$products[$key] = $val;
		}
		return $products;
	}
	
	// search_products for quick search 
	// in the admin 
		function search_products($term,$type=''){
			$products = array();
			if(strlen($term) < 2){
				return $products;
			}
			
			$this->db->like('title',$term);
			$this->db->or_like('detail',$term);
			$this->db->or_like('sku',$term);
			$this->db->where('enabled >=',0);
			// restrict the product types allowed 
			// in a bundle search
				if($type == 'bundle'){
					$types = array(1,4,5,7);
					$this->db->where_in('type_id',$types);
				}
			$this->db->from('br_product');
			$this->db->order_by('title','desc');
			$query = $this->db->get();
			foreach ($query->result_array() as $row){
				$products[] = $row;
			}
			return $products;
		}
	
	function log_search($term,$hash,$count,$member_id){
		$data = array(
						'search_term' => $term,
						'hash' => $hash,
						'result_count' => $count,
						'ip' => $_SERVER["REMOTE_ADDR"], 
						'member_id' => $member_id, 
						'site_id' => $this->config->item('site_id') 
						);
		$this->db->insert('br_search',$data);
		return true;
	}
	
	/**
	* Update / Create a New Product
	*
	* @access	public
	* @param	array,int,string
	* @return	void
	*/
		public function update_product($data,$product_id = '',$media_dir)
		{
			// Bundle Products 
				if($data["type_id"] == '2'){
					$bundle = $data["bundle"];	
					unset($data["bundle"]);
				}
	
			// Configurable Products
				
				if($data["type_id"] == '3'){
					$attr = $data["config_attr"];
					$a = explode(",",$attr);
					
					// remove the configurable attribute post vars
						foreach($a as $b){
							$str = "configurable_".$b;
							unset($data[$str]);
						}
						unset($data["config_attr"]);
					
					// build configurable 
						$cnt = count($data["config_sku"]);
						$data["quantity"] = 0;
						for($i = 0; $i < $cnt ; $i++){
							// Set the quantity
							$data["quantity"] += $data["config_qty"][$i];
							
							$configurable[$i] = array(
														"sku" => $data["config_sku"][$i],
														"qty" => $data["config_qty"][$i],
														"adjust_type" => $data["config_adjust_type"][$i],
														"adjust" =>	$data["config_adjust"][$i]										 		
											 		);
							$config = array();
							foreach($a as $b){
								$config[$b] = $data["config_attr_".$b][$i];
								unset($data["config_attr_".$b][$i]);
							}
							$configurable[$i]["attributes"] = serialize($config);
						}
						
					// Remove fields
						foreach($a as $b){
							unset($data["configurable_".$b]);
							unset($data["config_attr_".$b]);
						}
						unset($data["require_configurable"]);
						unset($data["config_sku"]);
						unset($data["config_qty"]);
						unset($data["config_adjust_type"]);
						unset($data["config_adjust"]);											 		
				}
				unset($data["require_configurable"]);
	
			// Downloadable Products 
				if($data["type_id"] == '4'){
					$download = array(
											'product_id' => $data["product_id"], 
											'title' => $data["download_title"],
											'filenm' => $data["download_filenm"],
											'filenm_orig' => $data["download_filenm_orig"],
											'download_limit' => $data["download_limit"],
											'download_length' => $data["download_length"],
											'download_version' => $data["download_version"]
										);
					unset($data["require_download"]);
					unset($data["download_title"]);
					unset($data["download_filenm"]);
					unset($data["download_filenm_orig"]);
					unset($data["download_limit"]);
					unset($data["download_length"]);
					unset($data["download_version"]);
				}
				unset($data["require_download"]);
				unset($data["asmSelect0"]);
	
		
			// Subscription Products 
				if($data["type_id"] == '6'){
					$trial_offer = isset($data["trial_offer"]) ? 1 : 0;
					$subscription = array(
											'length' => $data["length"],
											'period' => $data["period"], 
											'group_id' => $data["group_id"], 
											'trial_offer' => $trial_offer,
											'trial_price' => $data["trial_price"],
											'trial_occur' => $data["trial_occur"],
											'cancel_group_id' => $data["cancel_group_id"]
										);
					
					// check for discount pricing
						if(isset($data["sub_price_period"])){
							$sub_price["periods"] = $data["sub_price_period"];
							$sub_price["discount"] = $data["sub_price_adjust"]; 	
							unset($data["sub_price_period"]);
							unset($data["sub_price_adjust"]); 	
						}
				}
				
				unset($data["length"]);
				unset($data["period"]);
				unset($data["group_id"]);
				unset($data["trial_offer"]);
				unset($data["trial_price"]);
				unset($data["trial_occur"]);
				unset($data["cancel_group_id"]);
	
			if(isset($data["related"])){
				$related = $data["related"];
				unset($data["related"]);
			}
			
			// Test to see if the product id 
			// was sent in the array data
				$product_id = $data['product_id'];
				unset($data["product_id"]);
				
			// Is it a new product? Then lets create it
				if($product_id == 0){
					$new = array('title' => $data["title"]);
					$this->db->set($new);
					$this->db->insert('br_product'); 
					$product_id = $this->db->insert_id();
				}
				
			// Setup our price matrix
				// Delete any options that currently exist for this product
					$this->db->delete('br_product_price', array('product_id' => $product_id)); 
				
				// Create the data array for the post 
					$i = 0;
					foreach($data["price"] as $p){
						// We have an extra post for the template on the end
						// so only post length - 1
							if($i < (count($data["price"]) - 1)){
								// Format the prices 
									if($data['price_start'][$i] != ''){
										$data['price_start'][$i] = date("Y-m-d 00:00:00",strtotime($data['price_start'][$i]));
									}
									if($data['price_end'][$i] != ''){
										$data['price_end'][$i] = date("Y-m-d 23:59:59",strtotime($data['price_end'][$i]));
									}
			
								// Build the array
									$d = array(	
												'product_id' => $product_id,
												'type_id' => 1, // This is the price type (reg=1/sale=2) 
												'group_id' => $data['price_group'][$i],
												'price' => $p,
												'qty' => $data['price_qty'][$i],
												'end_dt' => $data['price_end'][$i],
												'start_dt' => $data['price_start'][$i],
												'sort_order' => $i 
												);
									$i++;
								$this->db->insert('br_product_price',$d);
							}
					}
	
				// Create the data array for the post 
					$i = 0;
					foreach($data["sale_price"] as $p){
						// We have an extra post for the template on the end
						// so only post length - 1
							if($i < (count($data["sale_price"]) - 1)){
							
								// Format the sale_prices 
									if($data['sale_price_start'][$i] != ''){
										$data['sale_price_start'][$i] = date("Y-m-d 00:00:00",strtotime($data['sale_price_start'][$i]));
									}
									if($data['sale_price_end'][$i] != ''){
										$data['sale_price_end'][$i] = date("Y-m-d 23:59:59",strtotime($data['sale_price_end'][$i]));
									}
			
								// Build the array
									$d = array(	
												'product_id' => $product_id,
												'type_id' => 2, // This is the sale_price type (reg=1/sale=2) 
												'group_id' => $data['sale_price_group'][$i],
												'price' => $p,
												'qty' => $data['sale_price_qty'][$i],
												'end_dt' => $data['sale_price_end'][$i],
												'start_dt' => $data['sale_price_start'][$i],
												'sort_order' => $i 
												);
								$i++;
								$this->db->insert('br_product_price',$d);
							}
					}
						
					// remove price posts
						unset($data["price"]);
						unset($data["price_qty"]);
						unset($data["price_group"]);
						unset($data["price_end"]);
						unset($data["price_start"]);
						unset($data["sale_price"]);
						unset($data["sale_price_qty"]);
						unset($data["sale_price_group"]);
						unset($data["sale_price_end"]);
						unset($data["sale_price_start"]);
	
						
			// Setup array containers
				$cAttr = array();
				$option = array();
				$image = array();
				$attr = array();
				$category = array();
				
			// Knock Knock.... Housekeeping
				unset($data["submit"]);
			
			// Break the inputs into fields which we can 
			// update individually
					foreach($data as $key => $val){
						if(strpos($key,'cAttribute') !== false){ 
							// Custom Attributes
							$cAttr[$key] = $val;
						}elseif(strpos($key,'cOptions_') !== false){
							$option[$key] = $val;
						}elseif(strpos($key,'cImg_') !== false){
							// Images
							$image[$key] = $val;
						}elseif(strpos($key,'category_') !== false){
							$category = $val;
						}else{
							// Default Attributes
							$attr[$key] = $val;
						}
					}
					
					// Clean up the options 
						$option = $this->_build_opts($option);
	
					// Serialize the data 
						$sOption = serialize($option);
	
			// Update the product
					$this->db->where('product_id',$product_id);
					$this->db->update('br_product',$attr); 
			
			// Update Custom Attributes
				
				$this->db->delete('br_product_attributes', array('product_id' => $product_id)); 
				
				if(isset($_FILES)){
					$file = rtrim($media_dir,'/').'/file';
					if(!file_exists($file)){
						mkdir($file);
					}
					foreach($_FILES as $key => $f){
						$a = explode("_",$key);
						$title = $cAttr["cAttribute_".$a[1]."_title"];
						unset($cAttr["cAttribute_".$a[1]."_title"]);
						
						if($f["name"] !== ''){
							$filename = $f["name"];
							move_uploaded_file($f["tmp_name"],$file.'/'.$f["name"]);
						}else{
							// Get previous file name
							$prev = unserialize($cAttr["cAttribute_".$a[1]]);
							$filename = $prev["file"];
						}
						
						$arr = array(	
										'title' => $title,
										'file' => $filename 
										);
						
						$cAttr["cAttribute_".$a[1]] = $arr;	
					}
				}
				
				foreach($cAttr as $key => $val){
					$a = explode('_',$key);
					if(is_array($val)){
						$val = serialize($val);
					}
					$data = array(	
										'descr' => $val, 
										'attribute_id' => $a[1], 
										'product_id' => $product_id
									);
					$this->db->insert('br_product_attributes',$data);
				}
				
			// Update Product Options 
				$this->db->delete('br_product_options', array('product_id' => $product_id)); 
				$data = array(	
								'options' => $sOption, 
								'product_id' => $product_id
							);
				$this->db->insert('br_product_options',$data);
		
			// Update Product Images 
	
				$this->db->delete('br_product_images', array('product_id' => $product_id)); 
				if(count($image) > 0){
					// Clean up the image array 
					$image["product_id"] = $product_id;
					$image = $this->_build_images($image);
					foreach($image as $img){
						$this->db->insert('br_product_images',$img);
					}
				}
					
			// Update Categories
				$this->db->delete('br_product_category', array(	'site_id' => $this->config->item('site_id'),
																'product_id' => $product_id)); 
				foreach($category as $c){
					$data = array(	
						'site_id' => $this->config->item('site_id'),
						'category_id' => $c, 
						'product_id' => $product_id
					);
					$this->db->insert('br_product_category',$data);
				}
			
			// If its a bundle
				if(isset($bundle)){
					$this->db->delete('br_product_bundle', array('parent_id' => $product_id)); 
					foreach($bundle as $b){
						$data = array(	
							'product_id' => $b, 
							'parent_id' => $product_id
						);
						$this->db->insert('br_product_bundle',$data);
					}			
				}
			
			// If its a configurable product 
				if(isset($configurable)){
					$this->db->delete('br_product_configurable', array('product_id' => $product_id)); 
					foreach($configurable as $c){
						$config = $c;
						$config["product_id"] = $product_id;
						$this->db->insert('br_product_configurable',$config);
					}
				}
				
			// If its a downloadable product 
				if(isset($download)){
					$this->db->insert('br_product_download',$download);
				}
			
			// If its a subscription
				if(isset($subscription)){
					$this->db->delete('br_product_subscription', array('product_id' => $product_id)); 
					$subscription["product_id"] = $product_id; 
					$this->db->insert('br_product_subscription',$subscription);
					$subscription_id = $this->db->insert_id();
					
					// Do we have any discount pricing?
						if(isset($sub_price)){
							$i = 0;
							foreach($sub_price["periods"] as $p){
								$sPrice[] = array(
													'subscription_id' => $subscription_id,
													'periods' => $p,
													'discount' => $sub_price["discount"][$i] 
													);
								$i++; 
							}
							$this->db->delete('br_product_subscription_price', array('subscription_id' => $subscription_id)); 
							foreach($sPrice as $row){
								$this->db->insert('br_product_subscription_price',$row);
							}
						}
				}
				
			// Related Products 
				if(isset($related)){
					$this->db->delete('br_product_related', array('parent_id' => $product_id)); 
					foreach($related as $r){
						$data = array(	
							'product_id' => $r, 
							'parent_id' => $product_id
						);
						$this->db->insert('br_product_related',$data);
					}			
				}	
		
			// Clear from cache
				remove_from_cache('product_'.$product_id);
			
			// Return our product_id
				return $product_id;
		}
	
	function update_product_order($entry_id,$cat_id,$sort_order)
	{
		$attr = array (
					'sort_order' => $sort_order
		);
		
		$this->db->where('product_id',$entry_id);
		$this->db->where('category_id',$cat_id);
		$this->db->update('br_product_category',$attr); 
	}
	
	function delete_product($product_id){
		$this->db->delete('br_product', array('product_id' => $product_id));
		$this->db->delete('br_product_attributes', array('product_id' => $product_id)); 
		$this->db->delete('br_product_bundle', array('parent_id' => $product_id)); 
		$this->db->delete('br_product_category', array('product_id' => $product_id)); 
		$this->db->delete('br_product_configurable', array('product_id' => $product_id)); 
		#$this->db->delete('br_product_download', array('product_id' => $product_id)); 
		$this->db->delete('br_product_images', array('product_id' => $product_id)); 
		$this->db->delete('br_product_options', array('product_id' => $product_id)); 
		$this->db->delete('br_product_related', array('parent_id' => $product_id)); 
		$this->db->delete('br_product_subscription', array('product_id' => $product_id)); 
		
		// Clear from cache
			remove_from_cache('product_'.$product_id);
		return true;
	}

	function update_product_status($product_id,$enabled){
		$data = array('enabled' => $enabled);
		$this->db->where("product_id",$product_id);
		$this->db->update("br_product",$data);
		return true;
	}
	
	function get_all_categories() {
		
		// Level 0
		$this->db->select('*');
		$this->db->from('br_category');
		$this->db->where('enabled','1');
		$this->db->where('site_id',$this->config->item('site_id'));	
		$this->db->where('parent_id',0);
		$this->db->order_by('sort','asc');
				
		$query = $this->db->get();
		$cat = array();
		$i = 0;
		foreach ($query->result_array() as $row){
			$cat[$i]['title'] =  $row["title"];
			$cat[$i]['category_id'] =  $row["category_id"];
			
			$i++;
			
			// Level 1
			$this->db->flush_cache();
			
			$this->db->select('*');
			$this->db->from('br_category');
			$this->db->where('enabled','1');
			$this->db->where('site_id',$this->config->item('site_id'));	
			$this->db->where('parent_id',$row['category_id']);
			
			$subquery = $this->db->get();
			
			foreach ($subquery->result_array() as $subrow){
				
				$cat[$i]['title'] =  ' - ' . $subrow["title"];
				$cat[$i]['category_id'] =  $subrow["category_id"];
			
				$i++;
				
				// Level 2
				$this->db->flush_cache();
			
				$this->db->select('*');
				$this->db->from('br_category');
				$this->db->where('enabled','1');
				$this->db->where('site_id',$this->config->item('site_id'));	
				$this->db->where('parent_id',$subrow['category_id']);
				
				$subsubquery = $this->db->get();
				
				foreach ($subsubquery->result_array() as $subsubrow){
					
					$cat[$i]['title'] =  ' -- ' . $subsubrow["title"];
					$cat[$i]['category_id'] =  $subsubrow["category_id"];
				
					$i++;			
				}
						
			}			
		}
		return $cat;
	}

	/* 
	* Product Price
	*	
	* @param product id	
	* @return array	
	* 
	*/
	function get_product_price($product_id,$type=1)
	{
		$this->db->from('br_product_price');
		$this->db->where('product_id',$product_id);
		$this->db->where('type_id',$type);
		$this->db->order_by('sort_order');
		$query = $this->db->get();
		$price = array();
		foreach ($query->result_array() as $row){
			// Lets make the array keys unique on the 
			// group id and further on quantity
				$price[] = array(
									'group_id' 	=> $row["group_id"],
									'price' 	=> $row["price"],
									'qty' 		=> $row["qty"],
									'start_dt' 	=> $row["start_dt"],
									'end_dt' 	=> $row["end_dt"],
									);
		}
		return $price;
	}
	
	function get_product_categories($product_id)
	{
		if (isset($this->session->cache['get_product_categories'][$product_id])){
			$cat = $this->session->cache['get_product_categories'][$product_id];
		}else{
			$this->db->select('*');
			$this->db->where('product_id',$product_id);
			$this->db->from('br_product_category');		
			$query = $this->db->get();
			$cat = array();
			$i = 0;
			foreach ($query->result_array() as $row){
				$cat[$i] =  $row["category_id"];
				$i++;
			}
			$this->session->cache['get_product_categories'][$product_id] = $cat;
		}
		return $cat;
	}
	
	function get_product_related($product_id){
		if (isset($this->session->cache['get_product_related'][$product_id])){
			$related = $this->session->cache['get_product_related'][$product_id];
		}else{
			$this->db->select('*');
			$this->db->where('parent_id',$product_id);
			$this->db->from('br_product_related');		
			$query = $this->db->get();
			$related = array();
			$i = 0;
			foreach ($query->result_array() as $row){
				$related[$i] =  $this->get_product_basic($row["product_id"]);
				$i++;
			}
			$this->session->cache['get_product_related'][$product_id] = $related;		
		}
		return $related;
	}
	function get_product_bundle($product_id){
		if(isset($this->session->cache['get_product_bundle'][$product_id])){
			$products = $this->session->cache['get_product_bundle'][$product_id];
		}else{
			$this->db->select('*');
			$this->db->where('parent_id',$product_id);
			$this->db->from('br_product_bundle');		
			$query = $this->db->get();
			$products = array();
			$i = 0;
			foreach ($query->result_array() as $row){
				$products[$i] =  $this->get_product_basic($row["product_id"]);
				$i++;
			}
			$this->session->cache['get_product_bundle'][$product_id] = $products;
		}
		
		return $products;
	}

	function get_product_configurable($product_id){
		$this->db->select('*');
		$this->db->where('product_id',$product_id);
		$this->db->from('br_product_configurable');	
		$this->db->order_by('configurable_id','asc');
		$query = $this->db->get();
		$products = array();
		$i = 0;
		foreach ($query->result_array() as $row){
			$products[$i] = $row;
			$i++;
		}
		return $products;
	}


	function get_product_download($product_id){
		$this->db->where('product_id',$product_id);
		$this->db->from('br_product_download');		
		$this->db->order_by('created','desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$files = array();
		$i = 0;
		foreach ($query->result_array() as $row){
			$files[$i] = $row;
			$i++;
		}
		return $files;
	}
	
	function get_product_subscription($product_id){
		$this->db->select('*');
		$this->db->where('product_id',$product_id);
		$this->db->from('br_product_subscription');		
		$this->db->limit(1);
		$query = $this->db->get();
		$arr = array();
		$i = 0;
		foreach ($query->result_array() as $row){
			$arr[$i] = $row;
			$i++;
		}
		return $arr;
	}

	function get_attributes($set_id = '',$product_id = ''){
			
			// Editing or new?
				$isProduct = false;	
			
			// If a product_id is supplied then 
			// lets see if there are values
			
				if($product_id != ''){
					$isProduct = true;
					$this->db->select('*');
					$this->db->from('br_product_attributes');
					$this->db->where('product_id',$product_id);
					$q = $this->db->get();
					foreach($q->result_array() as $row){
						$pAttrs[$row["attribute_id"]] = $row["descr"];
					}
				}
			
			// Select the attributes for the type 
			// to create a shell
				
				if($set_id == ''){
					if(isset($this->session->cache['get_attributes'])){
						$result = $this->session->cache['get_attributes'];
					}else{
						$this->db->select('*');
						$this->db->from('br_attribute a');
						$this->db->where('a.site_id',$this->config->item('site_id'));
						$query = $this->db->get();
						$result = $query->result_array();
						$this->session->cache['get_attributes'] = $result;
					}
				}else{
					$this->db->select('*');
					$this->db->from('br_attribute a');
					$this->db->join('br_attribute_set_attribute asa', 'a.attribute_id = asa.attribute_id');
					$this->db->where('asa.attribute_set_id',$set_id);
					$this->db->order_by('asa.sort_order');
					$this->db->where('a.site_id',$this->config->item('site_id'));
					$query = $this->db->get();
					$result = $query->result_array();
				}
				$attributes = array();
				$i = 0;
				foreach ($result as $row){
					foreach($row as $key => $val){
						$attributes[$i][$key] = $val;
					}
					// Check for product values or
					// default values
						$attributes[$i]['value'] = '';
						if($isProduct == false){
							$attributes[$i]['value'] = $attributes[$i]['default_text'];
						}elseif(isset($pAttrs[$attributes[$i]['attribute_id']])){
							$attributes[$i]['value'] = $pAttrs[$attributes[$i]['attribute_id']];
						}
					$i++;
				}
	
			return $attributes;
	}
	
	function get_attribute_by_id($attribute_id){
		if(isset($this->session->cache['get_attribute_by_id'][$attribute_id])){
			$attributes = $this->session->cache['get_attribute_by_id'][$attribute_id];			
		}else{
			$this->db->select('*');
			$this->db->from('br_attribute');
			$this->db->where('attribute_id',$attribute_id);
			$query = $this->db->get();
			$attributes = array();
			foreach ($query->result_array() as $row){
				foreach($row as $key => $val){
					$attributes[$key] = $val;
				}
			}
			$this->session->cache['get_attribute_by_id'][$attribute_id] = $attributes;
		}
				
			return $attributes;
	}

	// Get the attributes that could be used in the configurable 
	// products setup. Only dropdowns are valid. 	
		function get_attribute_config(){
			$this->db->select('*');
			$this->db->from('br_attribute');
			$this->db->where('fieldtype','dropdown');
			$query = $this->db->get();
			$attributes = array();
			$i=0;
			foreach ($query->result_array() as $row){
				foreach($row as $key => $val){
					$attributes[$i][$key] = $val;
				}
				$i++;
			}
			return $attributes;
		}


	function get_attribute_sets($attribute_set_id = ''){
		$sets = array();
		$this->db->select('*');
		$this->db->from('br_attribute_set');
		if($attribute_set_id != ''){
			$this->db->where('attribute_set_id',$attribute_set_id);
		}
		$this->db->where('site_id',$this->config->item('site_id'));
		$query = $this->db->get();
		$i = 0;
		foreach($query->result_array() as $row){
			foreach($row as $key => $val){
				$sets[$i][$key] = $val;
			}
			$i++;
		}
		return $sets;
		
	}
	
	function update_attribute(){
		foreach($_POST as $key => $val){
			$data[$key] = $val;
		}
		$attribute_id = $data["attribute_id"];
		unset($data["attribute_id"]);
		$data["site_id"] = $this->config->item('site_id');
		$data["code"] = $this->_clean_code($data["code"]);
		if($attribute_id == 0){
			$this->db->insert("br_attribute",$data);
			$attribute_id = $this->db->insert_id();
		}else{
			$this->db->where("attribute_id",$attribute_id);
			$this->db->update("br_attribute",$data);
		}
		return $attribute_id;
	}
	
	function delete_attribute($attribute_id){
		$this->db->where('attribute_id', $attribute_id);
		$this->db->delete("br_attribute");
		return true;
	}
	
	// Get the list of all attributes with the 
	// ones for the given set selected

	function get_attribute_set_list($attribute_set_id){
		// Get them all
			$attr = $this->get_attributes();
		
		// Get the selected ones
			$this->db->select('*');
			$this->db->from('br_attribute a');
			$this->db->join('br_attribute_set_attribute asa', 'a.attribute_id = asa.attribute_id');
			$this->db->where('asa.attribute_set_id',$attribute_set_id);
			$this->db->order_by('asa.sort_order');
		
		$query = $this->db->get();
		$attributes = array();
		$i = 0;
		foreach ($query->result_array() as $row){
			$attributes[$row["attribute_id"]] = array(
														'attribute_id' => $row["attribute_id"],
														'title' => $row["title"],
														'code' 	=> $row["code"],
														'selected' => 1 
													);
		}
		foreach ($attr as $row){
			if(!isset($attributes[$row["attribute_id"]])){
				$attributes[$row["attribute_id"]] = array(
														'attribute_id' => $row["attribute_id"],
														'title' => $row["title"],
														'code' 	=> $row["code"],
														'selected' => 0 
													);
			}
		}
		return $attributes;
	}
	
	function update_attribute_set(){

		$attribute_set_id = $_POST["attribute_set_id"];
		if($attribute_set_id != 0){
			// Update the set title 
				$this->db->where('attribute_set_id', $attribute_set_id);
				$this->db->update('br_attribute_set', array('title' => $_POST["title"])); 
				
			// Remove the previously selected attributes
				$this->db->where('attribute_set_id', $attribute_set_id);
				$this->db->delete("br_attribute_set_attribute");
		}else{
			$data = array(
							'site_id' => $this->config->item('site_id'),
							'title' => $_POST["title"]
						);
			$this->db->insert('br_attribute_set',$data);
			$attribute_set_id = $this->db->insert_id();
		}

		// Add the newly sorted newly selected 
		// attributes 
			$i = 0;
			foreach($_POST["attr"] as $a){
				$data = array(
								'attribute_id' => $a,
								'attribute_set_id' => $attribute_set_id, 
								'sort_order' => $i 			
							);
				$this->db->insert("br_attribute_set_attribute",$data);
				$i++;
			}
			
		return true;
	}
	
	function delete_attribute_set($attribute_set_id){
		$this->db->where('attribute_set_id', $attribute_set_id);
		$this->db->delete("br_attribute_set");
		$this->db->where('attribute_set_id', $attribute_set_id);
		$this->db->delete("br_attribute_set_attribute");
		return true;
	}
	
	function get_product_options($product_id){
			$options = array();
			$this->db->select('*');
			$this->db->from('br_product_options');
			$this->db->where('product_id',$product_id);
			$query = $this->db->get();
			foreach ($query->result_array() as $row){
				$options = unserialize($row["options"]);
			}
			return $options;
	}

	function get_product_images($product_id,$exclude = true)
	{
			$images = array();
			$this->db->select('*');
			$this->db->from('br_product_images');
			$this->db->where('product_id',$product_id);
			if($exclude === true){
				$this->db->where('exclude',0);
			}
			$this->db->order_by('sort');
			$query = $this->db->get();
			foreach ($query->result_array() as $key => $val){
				if($val["large"] == 1){
					$images["image_large"] = 'products/'.$val["filenm"];
					$images["image_large_title"] = $val["title"];
				}
				if($val["thumb"] == 1){
					$images["image_thumb"] = 'products/'.$val["filenm"];
					$images["image_thumb_title"] = $val["title"];
				}
				$images[$key] = $val;
			}
			return $images;
	}

	function get_categories($enabled=1,$sort='sort')
	{
		$possible_sort = array('sort','title');
		if(!in_array($sort,$possible_sort)){
			$sort = 'sort';
		}
		$cat = array();
		$this->db->select('*');
		$this->db->from('br_category');
		if($enabled == 1){
			$this->db->where('enabled',1);
		}
		$this->db->where('site_id',$this->config->item('site_id'));
		$this->db->order_by('parent_id');
		$this->db->order_by($sort);
		$query = $this->db->get();
		foreach ($query->result_array() as $val){
			$cat[$val["parent_id"]][$val["category_id"]] = $val;
		}
		return $cat;
	}
	
	function get_category_child($category_id)
	{
		$cat = array();
		$this->db->from('br_category');
		$this->db->where('parent_id',$category_id);
		$this->db->where('enabled',1);
		$this->db->order_by('sort');
		$query = $this->db->get();
		foreach ($query->result_array() as $val){
			$cat[$val["category_id"]] = $val;
		}
		return $cat;
	}
	
	function get_category_list($product_id)
	{
		$cat = array();
		$this->db->select('*');
		$this->db->from('br_product_category');
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		foreach ($query->result_array() as $val){
			$cat[$val["category_id"]] = $val["category_id"];
		}
		return $cat;
	}
	
	function update_category($category_id,$data)
	{
		$this->db->where('category_id', $category_id);
		$this->db->update('br_category', $data); 
		return;
	}
	
	function update_category_order($category_id,$order)
	{
		$data = array('sort' => $order);
		$this->db->where('category_id', $category_id);
		$this->db->update('br_category', $data); 
		return;
	}
	
	function update_category_create($data)
	{
		$this->db->insert('br_category', $data); 
		return $this->db->insert_id();
	}
	
	function update_category_delete($category_id)
	{
		// Delete the actual category 
			$this->db->where('category_id', $category_id);
			$this->db->or_where('parent_id', $category_id);
			$this->db->delete('br_category');

		// Delete assigned products		
			$this->db->where('category_id', $category_id);
			$this->db->delete('br_product_category');
		return;
	}
	
	function get_category($category_id)
	{
		if (!isset($this->session->cache['get_category'][$category_id])){
			$cat = array();
			$this->db->select('*');
			$this->db->from('br_category');
			$this->db->where('category_id',$category_id);
			$query = $this->db->get();
			foreach ($query->result_array() as $key => $val){
				$cat[$key] = $val;
			}
			// Set it in session
				$this->session->cache['get_category'][$category_id] = $cat;
			// Return array
				return $cat;
		}else{
			return $this->session->cache['get_category'][$category_id];
		}
	}
	
	function get_category_by_key($key)
	{
		$this->db->from("br_category");
		$this->db->where('url_title',$key);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			$row = $query->result_array();
			$category = $this->get_category($row[0]["category_id"]);
			$category[0]["products"] = $this->get_product_by_category($row[0]["category_id"]);
			$category[0]["total_results"] = count($category[0]["products"]);
			return $category;
		}else{
			return false;
		}
	}
	
	/* 	Cart Functions 
	 * 
	 *	Set the the current cart data
	 */
	 
	function cart_set($data)
	{
		$data["ip"] = $_SERVER["REMOTE_ADDR"];
		$this->db->insert('br_cart',$data);
	}
	
	function cart_update($data,$key)
	{
		$this->db->where('cart_id',$key);
		$this->db->update('br_cart',$data);
	}
	
	function cart_unset($key)
	{
		$this->db->delete('br_cart', array('md5(cart_id)' => $key, 'status' => 0));
	}
	
	function cart_clear()
	{
		$this->db->delete('br_cart', array(	'session_id' => session_id(), 
											'status' => 0));
	}
	
	function cart_get($session_id = '')
	{
		$id = ($session_id == '') ? session_id() : $session_id;
		if(isset($this->session->cache["cart_get"][$session_id])){
			$cart = $this->session->cache["cart_get"][$session_id];
		}else{
			$cart = array();
			$this->db->cache_off();
			$this->db->from('br_cart');
			$this->db->where('session_id',$id);
			$this->db->where('status',0);
			$query = $this->db->get();
			foreach ($query->result_array() as $key => $val){
				foreach($val as $k => $v){
					$cart[$k] = $v;
				}
				$cart["items"][$val["cart_id"]] = unserialize($val["content"]);
			}
			$this->session->cache["cart_get"][$session_id] = $cart;
		}
		return $cart;
	}

	function cart_promo($code = '')
	{
		$data = array('coupon_code' => $code);
		$this->db->where('session_id',session_id());
		$this->db->update('br_cart',$data);
	}
	
	// update the cart status
		function cart_update_status($id,$status)
		{
			$data = array('status' => $status);
			$this->db->where('session_id',$id);
			$this->db->update('br_cart',$data);
			return true;
		}
	
	// update the status to 
		function cart_token($token)
		{
			$data = array('token' => $token);
			$this->db->where('session_id',session_id());
			$this->db->update('br_cart',$data);		
			return true;
		}
	
	function get_states($countries = '', $js_cache = FALSE)
	{
		$not_cached = array('0');
		$cache = $this->_get_cache('js_state_map', array());
		
		foreach ($countries as $key => $c){
			if (!isset($cache[$c['title']])){
				$cache[$c['title']] = array();
				$not_cached[] = $c['zone_id'];
			}
			
			$zone_ids[$c['zone_id']] = $c['title'];
		}
		
		if(count($not_cached) > 1){
			$this->db->where_in('zone_id', $not_cached);
			$this->db->order_by('title');
			$query = $this->db->get('br_state');
			
			foreach($query->result() as $state){
				$c_title = $zone_ids[$state->zone_id];
				$cache[$c_title][$state->code] = $state->title;
			}
			
			$this->_set_cache('js_state_map', $cache);
		}
				
		return $js_cache ? array() : array_intersect_key($cache, array_flip($zone_ids));
	}
	
	function get_countries($enabled=1)
	{
		$this->db->from('br_zone');
		if($enabled == 1){
			$this->db->where('enabled',1);
		}
		$this->db->order_by('title');
		$query = $this->db->get();
		$arr = array();
		foreach ($query->result_array() as $val){	
			$arr[$val["code"]] = $val;
		}
		return $arr;
	}
	
	/************************/
	/* Helper Functions 	*/
	
	function _check_url($str,$id)
	{
		$count = 0;
		// Check for duplicates in products 
			$this->db->where('product_id !=',$id);
			$this->db->where('url',$str);
			$this->db->from('br_product');
			$count = $count + $this->db->count_all_results();
		// Check for dumplicates in categories 
			$this->db->where('category_id !=',$id);
			$this->db->where('url_title',$str);
			$this->db->from('br_category');
			$count = $count + $this->db->count_all_results();
		return $count;
	}

	function _check_sku($str,$product_id)
	{
		$this->db->where('product_id !=',$product_id);
		$this->db->where('sku',$str);
		$this->db->from('br_product');
		return $this->db->count_all_results();
	}
	
	function _clean_code($str)
	{
		$str = strtolower(preg_replace('/[^a-z_]/i','',trim(str_replace(" ","_",$str)))); 
		return $str;
	}
	
	function _build_opts($opts)
	{
		// Clean up the post and put the 
		// values into an array we can serialize
			
			if(isset($opts["cOptions_title"])){
				$sort = 0;
				foreach($opts["cOptions_title"] as $key=>$val){
					if(trim($val) != ''){
						// setup the array on the sort value 
						// multiple by 1 to keep things clean 
							
						// Main option fields
							$arr[$sort][$key]["title"] = $val;	
							$arr[$sort][$key]["type"] = $opts["cOptions_type"][$key];
							$arr[$sort][$key]["required"] = $opts["cOptions_required"][$key];
							$arr[$sort][$key]["sort"] = $sort;	
							
						// Dropdown options
						if($opts["cOptions_type"][$key] == 'dropdown'){
							if(isset($opts["cOptions_opt_title"][$key])){
								$tmp = array();
								foreach($opts["cOptions_opt_title"][$key] as $a => $b){
									$opt_sort = $opts["cOptions_opt_sort"][$key][$a] * 1;
									$tmp[$opt_sort][$a]['title'] 	= $b;
									$tmp[$opt_sort][$a]['type'] 	= 'fixed'; #$opts["cOptions_opt_type"][$key][$a];
									$tmp[$opt_sort][$a]['price'] 	= $opts["cOptions_opt_price"][$key][$a];
									$tmp[$opt_sort][$a]['sort'] 	= $opt_sort;
								}
								
								// Sort the options for the dropdowns
								ksort($tmp);
								$i = 0;
								foreach($tmp as $a => $b){
									foreach($b as $c){
										$arr[$sort][$key]["opts"][$i] = $c;
										$i++;
									}
								}
							}
						}
					}
					$sort++;
				}
			if(isset($arr)){
				// Sort the options at the 
				// primary level
					ksort($arr);
					$i = 0;
					foreach($arr as $key => $val){
						foreach($val as $a => $b){
							$brr[$i] = $b;	
							$i++;
						}
					}
				return $brr;
			}else{
				return;
			}
		}else{
			return $opts;
		}
			
	}
	
	function _build_images($imgs)
	{
		if(isset($imgs["cImg_name"])){
			$sort = 0;
			foreach ($imgs["cImg_name"] as $key => $val){
				
				// Get the exclude / large / thumb values
					$large = ($imgs["cImg_large"] == $key) ? 1 : 0;
					$thumb = ($imgs["cImg_thumb"] == $key) ? 1 : 0;
					if($large == 1 || $thumb == 1){
						$exclude = 0;
					}else{
						$exclude = isset($imgs["cImg_exclude"][$key]) ? 1 : 0;
					}
					
				// Set the full array
					$arr[$sort]["product_id"] = $imgs["product_id"];
					$arr[$sort]['filenm']  = $val;
					$arr[$sort]['title']   = $imgs["cImg_title"][$key];
					$arr[$sort]['large']   = $large;
					$arr[$sort]['thumb']   = $thumb;
					$arr[$sort]['exclude'] = $exclude;
					$arr[$sort]['sort']    = $sort;
				$sort++;
			}
			return $arr;
		}else{
			return $imgs;
		}
	}
	
	function _set_cache($key, $value)
	{
		$this->session->cache['br_'.$key] = $value;
	}
	
	function _get_cache($key, $default = FALSE)
	{
		if (!isset($this->session->cache['br_'.$key])){
			return $default;
		}
		
		return $this->session->cache['br_'.$key];
	}
}