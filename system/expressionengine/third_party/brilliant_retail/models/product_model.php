<?php
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

class Product_model extends CI_Model {

	protected $EE;
	private $cats;

	public function __construct()
	{
		$this->EE =& get_instance();
	}

	/**
	* Get products into an array
	*
	* The method attempts to get the products by individual cached files  
	*
	* @access	public
	* @param	int,int,int
	* @return	array of products
	*/
		public function get_products($product_id = '' , $disabled = '',$cat='')
		{
			// Now try to get it from 			
			if($product_id != ''){
				// Try and return it from session cache
					if (isset($this->session->cache['get_products'][$product_id])){
						
						$this->EE->TMPL->log_item('BrilliantRetail: return product_id ('.$product_id.') from session data '.round(memory_get_usage()/1024/1024, 2).'MB');

						return $this->session->cache['get_products'][$product_id];
					}

				// Try to return from cache	unless it is disabeled in the 
				// configuration file.
				
					$disable_cache = ($this->config->item('br_disable_product_cache') === TRUE) ? 1 : 0;
					if($disable_cache == 0){
						if($str=read_from_cache('product_'.$product_id)){
							$arr[0] = unserialize($str);
							// only return enabled cached products
								if($arr[0]["enabled"] == 1){
									$this->session->cache['get_products'][$product_id] = $arr;
									
									$this->EE->TMPL->log_item('BrilliantRetail: return product_id ('.$product_id.') from cache file '.round(memory_get_usage()/1024/1024, 2).'MB');

									return $arr;
								}
						}
					}

				// Get the specific product id
					$this->EE->db->where('product_id',$product_id);
			}
			
			if($disabled == ''){
				$this->EE->db->where('enabled >',0);
			}
			
			$this->EE->db->from('br_product p');
			
			if ($cat!="")
			{
				$this->EE->db->join("br_product_category c","p.product_id=c.product_id");
				$this->EE->db->where("c.category_id",$cat);
			}
			
			$this->EE->db->where('p.site_id',$this->config->item('site_id'));
			
			$this->EE->db->order_by('p.product_id','desc');
			
			$query = $this->EE->db->get();
			
			$products = array();
			$i = 0;
			foreach ($query->result_array() as $row){
				
				// General Product Details 
					$products[$i] = $row;
				
				// Get Product Entry Id (Experimental) 
					$products[$i]["entry_id"] = $this->get_product_entry($row["product_id"]);

				// Get Product Price
					$products[$i]["price_matrix"] = $this->get_product_price($row["product_id"],1);

				// Get Product Sale Price
					$products[$i]["sale_matrix"] = $this->get_product_price($row["product_id"],2);
				
				// Product Categories 
					$products[$i]["categories"] = $this->get_product_categories($row["product_id"]);
					
				// Product Attributes 
					$attributes = $this->get_attributes($row["attribute_set_id"],$row["product_id"]);
					$products[$i]["attributes"] = '<div id="product_attributes">';
					$j=0;
					foreach($attributes as $attr){
						
						$val = isset($attr["value"][0]) ? $attr["value"][0] : $attr["value"];
						
						$products[$i]["attributes"] .= ' 	<p class="label">'.$attr["title"].'</p>
															<p>'.$val.'<p>';
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
				
				// Product Addon 
					$products[$i]["addon"] 		= ''; #$this->get_product_addon($row["product_id"]);
					
				// Product Related 
					$products[$i]["related"] 	= $this->get_product_related($row["product_id"]);
	
				// Product Options 
					$products[$i]["options"] 	= $this->get_product_options($row["product_id"]);
				
				// Bundle Product Type
					if($row["type_id"] == 2){
						$products[$i]["bundle"] 	= $this->get_product_bundle($row["product_id"]);
					}
				
				// Configurable Product Type
					if($row["type_id"] == 3){
						$products[$i]["configurable"] = $this->get_product_configurable($row["product_id"]);
					}
				
				// Download Product Type
					if($row["type_id"] == 4){
						$products[$i]["download"] = $this->get_product_download($row["product_id"]);
					}
				
				// Donation Product Type
					if($row["type_id"] == 7){
						$products[$i]["donation"] = $this->get_product_donation($row["product_id"]);
					}
				
					save_to_cache('product_'.$row["product_id"],serialize($products[$i]));
					$i++;
			}
			
			// Save it to the session cache 
				if($product_id != ''){
					$this->session->cache['get_products'][$product_id] = $products;
				}

			$this->EE->TMPL->log_item('BrilliantRetail: return product_id ('.$product_id.') from database '.round(memory_get_usage()/1024/1024, 2).'MB');

			return $products;
		}
	
	/**
	 * get_product_collection function.
	 * 
	 * @access public
	 * @param mixed $search
	 * @param int $limit (default: 0)
	 * @param int $offset (default: 0)
	 * @param mixed $sort
	 * @param mixed $dir
	 * @param string $cat_id (default: '')
	 * @param string $type_id (default: '')
	 * @return array containing products and display totals
	 */
    	public function get_product_collection($search,$limit=0,$offset=0,$sort,$dir,$cat_id='',$type_id='')
    	{
    		
    		$site_id 	= $this->config->item('site_id');
    		$prefix 	= $this->EE->db->dbprefix;
    		 
    		// Get a simple count of all products
    			$sql = "SELECT 
    						count(product_id) as cnt 
    					FROM 
    						".$prefix."br_product ";
    			$query = $this->EE->db->query($sql);
    			$rst = $query->result_array();
    			$total = $rst[0]["cnt"];
    						
    		// Create a SQL statement
    			$sql = "SELECT 
    						SQL_CALC_FOUND_ROWS 
    						p.product_id,   
    						p.title, 
    						p.sku,
    						p.quantity,
    						p.type_id,   
    						p.enabled  
    					FROM 
    						".$prefix."br_product p "; 
    			if($cat_id != ''){
    				$sql .= ", 	".$prefix."br_product_category c 
    							WHERE 
    								p.product_id = c.product_id 
    							AND
    								c.category_id = ".$cat_id." ";
    			}else{
    				$sql .= "WHERE 1 = 1 ";
    			}
    			
    			// Are we filtering by type?
    				if($type_id != '')
    				{
    					$sql .= ' AND p.type_id = '.$type_id;
    				}
    			
    			$sql .= " AND p.site_id = ".$site_id;
    			
    			if(trim($search) != ''){
    				$sql .= " AND 
    							( 	p.product_id LIKE '%".mysql_real_escape_string($search)."%' 
    								||
    							  	p.title LIKE '%".mysql_real_escape_string($search)."%' 
    								||
    							  	p.sku LIKE '%".mysql_real_escape_string($search)."%'
    							)";
    			}
    			
    			$sql .= " ORDER BY ".($sort+1)." ".$dir;
    
    			if($limit != 0){
    				$sql .= " LIMIT ".$offset.",".$limit;
    			}
    		
    		// Run the sql
    			$query = $this->EE->db->query($sql);
    			$rst = $query->result_array();
    			
    			$products = array(
    								"total"		=> $total,
    								"results" 	=> $rst
    							);
    
    		// Get the total without LIMIT restrictions
    			$query = $this->EE->db->query("SELECT FOUND_ROWS() as dTotal");
    			$rst = $query->result_array();
    			$products["displayTotal"] = $rst[0]["dTotal"];
    
    		// Get the count of ALL 
    			return $products;
    	}


    /**
     * get_products_by_feed function.
     *
     * Methog to retrieve a list of products by a given 
     * feed_id 
     *  
     * @access public
     * @param mixed $feed_id
     * @param bool $nojoin (default: false)
     * @return void
     */
        public function get_products_by_feed($feed_id,$nojoin=false)
        	{
        	
        		if(!$nojoin){
        			$this->EE->db->select("p.*");
        			$this->EE->db->from("br_product_feeds f");
        			$this->EE->db->join("br_product p","f.product_id = p.product_id");
        		}
        		else
        		{
        		$this->EE->db->select("*");
        		$this->EE->db->from("br_product_feeds f");
        		}
        		$this->EE->db->where("f.feed_id",$feed_id);
        		
        		$query = $this->EE->db->get();
        		
        		return $query->result_array();
        	}

    /**
     * get_feed_id_by_product function.
     * 
     * @access public
     * @param mixed $product_id
     * @return void
     */
        public function get_feed_id_by_product($product_id) {
        	$this->EE->db->select("feed_id");
        	$this->EE->db->from("br_product_feeds");
        	$this->EE->db->where("product_id",$product_id);
        	
        	$query = $this->EE->db->get();
        	
        	return $query->result_array();
        }
	
	/**
	 * remove_product_from_feed function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
	   public function remove_product_from_feed($product_id)
    	{
    		$this->EE->db->delete('br_product_feeds', array('product_id' => $product_id));
    	}
	
	/**
	 * add_product_to_feed function.
	 * 
	 * @access public
	 * @param mixed $feed
	 * @return void
	 */
    	public function add_product_to_feed($feed)
    	{
    		$this->EE->db->insert('br_product_feeds',$feed);
    	}
	
	/**
	 * get_low_stock function.
	 * 
	 * @access public
	 * @param mixed $threshold
	 * @return void
	 */
    	public function get_low_stock($threshold)
    	{
    		$this->EE->db->select("	e.entry_id,
    							p.product_id,
    							p.title,
    							p.sku,
    							p.quantity")
    				->from("br_product p")
    				->join('br_product_entry e', 'p.product_id=e.product_id')
    				->where("p.type_id","1") 				// Only Basic Products
    				->where("p.enabled","1") 				// Only Enabled Products
    				->where("p.quantity <",$threshold); 	// Only Basic Products
    		$query = $this->EE->db->get();
    		return $query->result_array();
    	}
	
	/**
	 * get_product_basic function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_basic($product_id)
    	{
    		$this->EE->db->select('*');
    		$this->EE->db->where('product_id',$product_id);
    		$this->EE->db->where('enabled >=',0);
    		$this->EE->db->from('br_product');
    		$query = $this->EE->db->get();
    
    		$products = array();
    		foreach ($query->result_array() as $row){
    			// General Product Details 
    				$products = $row;
    		}
    		return $products;
    	}
	
	/**
	 * get_product_meta function.
	 * 
	 * @access public
	 * @return void
	 */
    	public function get_product_meta()
    	{
    		$products = array();
    		$this->EE->db->select("product_id,url,meta_title,meta_descr,meta_keyword");
    		$this->EE->db->from('br_product');
    		$query = $this->EE->db->get();
    		foreach ($query->result_array() as $val){
    			$products[$val["product_id"]] = $val;
    		}
    		return $products;
    	}
	
	/**
	 * get_product_entry function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_entry($product_id){
    		// Not quite ready for prime time
    			if (isset($this->session->cache['get_product_entry'])){
    				$product = $this->session->cache['get_product_entry'];
    			}else{
    				$this->EE->db->select('*');
    				$this->EE->db->from('br_product_entry');		
    				$query = $this->EE->db->get();
    				$product = array();
    				$i = 0;
    				foreach ($query->result_array() as $row){
    					$product[$row["product_id"]] =  $row["entry_id"];
    					$i++;
    				}
    				$this->session->cache['get_product_entry'] = $product;
    			}
    			if(!isset($product[$product_id])){
    				return 0;
    			}
    			return $product[$product_id];
    	}
	
	/**
	 * get_product_thumbnail function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_thumbnail($product_id)
    	{
    		$thumb = '';
    		$p = $this->get_product($product_id);
    		return $thumb;
    	}
	
	/**
	 * get_product_by_key function.
	 * 
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
    	public function get_product_by_key($key){ 
    		$this->EE->db->select('*');
    		$this->EE->db->where('url',$key);
    		$this->EE->db->where('enabled >',0);
    		$this->EE->db->from('br_product');
    		$this->EE->db->order_by('product_id','desc');
    		
    		$query = $this->EE->db->get();
    		if($query->num_rows() == 1){
    			$product_id = $query->row("product_id");
    			return $this->get_products($product_id);
    		}else{
    			return false;
    		}
    	}
	
	/**
	 * get_product_by_category function.
	 * 
	 * @access public
	 * @param mixed $category
	 * @param string $disabled (default: '')
	 * @return void
	 */
    	public function get_product_by_category($category,$disabled=''){ 
    		$this->EE->db->where('category_id',$category) 
    				->from('br_product_category')
    				->order_by('sort_order','asc')
    				->order_by('product_id','desc');
    		
    		$query = $this->EE->db->get();
    		$products = array();
    		$i = 0;
    		if($query->num_rows() == 0){
    			return $products;
    		}
    		foreach ($query->result_array() as $row){
    			$products[$i]['row_count'] = ($i + 1); 
    			$products[$i]['product_id'] = $row["product_id"];
    			$products[$i]['sort_order'] = $row['sort_order']; 
    			$i++;
    		}
    		return $products;
    	}
	
	/**
	 * get_config_product function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
    	public function get_config_product($id){
    		if(isset($this->session->cache['br_get_config_product'][$id])){
    			return $this->session->cache['br_get_config_product'][$id];
    		}
    			$products = array();
    			$i = 0;
    			$this->EE->db->where('br_product_configurable_attribute.configurable_id',$id)
    					->from('br_product_configurable_attribute') 
    					->join('br_product_configurable','br_product_configurable.configurable_id=br_product_configurable_attribute.configurable_id');
    			$query = $this->EE->db->get();
    			if($query->num_rows() == 0){
    				return $products;
    			}
    			
    			foreach ($query->result_array() as $key => $val){
    				$products[$key] = $val;
    			}
    		// Set it to cache
    			$this->session->cache['br_get_config_product'][$id] = $products;
    		return $products;
    	}
	
	/**
	 * search_products function.
	 * 
	 * @access public
	 * @param string $term
	 * @param string $type
	 * @return JSON product array
	 */
    	public function search_products($term,$type=''){
    		
    		$this->EE->load->helper('search');
    		
    		$products = array();
    		$term = "%".sanitize_search_terms($term)."%";
    		
    		if(strlen($term) < 2){
    			return $products;
    		}
    		
    		$site_id = $this->config->item('site_id');
    		
    		$sql = " SELECT 
    		             * 
    		         FROM 
    		             ".$this->EE->db->dbprefix."br_product 
    		         WHERE 
    		             enabled >= 0 
    		         AND 
    		             site_id = ".$site_id." 
    		         AND 
    		             (
    		                 title LIKE '".$term."'
    		                     OR 
    		                 detail LIKE '".$term."'
    		                     OR 
    		                 sku LIKE '".$term."'
    		             ) ";
    		// restrict the product types allowed 
    		// in a bundle search
    			if($type == 'bundle'){
    				$types = array(1,4,5,7);
    				$sql .= " AND type_id IN (".$types.")";
    			}
    			$sql .= " order by title desc";
    		
    		$query = $this->EE->db->query($sql);
    		foreach ($query->result_array() as $row){
    			$products[] = $row;
    		}
    		return $products;
    	}		
			
	/**
	 * log_search function.
	 * 
	 * @access public
	 * @param mixed $term
	 * @param mixed $hash
	 * @param mixed $count
	 * @param mixed $member_id
	 * @return void
	 */
    	public function log_search($term,$hash,$count,$member_id){
    		$data = array(
    						'search_term' => $term,
    						'hash' => $hash,
    						'result_count' => $count,
    						'ip' => $_SERVER["REMOTE_ADDR"], 
    						'member_id' => $member_id, 
    						'site_id' => $this->config->item('site_id') 
    						);
    		$this->EE->db->insert('br_search',$data);
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
			// Test to see if the product id 
			// was sent in the array data
				$product_id = $data['product_id'];
				unset($data["product_id"]);
				
			// Is it a new product? Then lets create it
				if($product_id == 0){
					$new = array('title' => $data["title"]);
					$this->EE->db->set($new);
					$this->EE->db->insert('br_product'); 
					$product_id = $this->EE->db->insert_id();
				}
				
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
							$configurable[$i]["attribute"] = $config;
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
					
					if($data["download_import"] == 1){
						$old = $media_dir.'import/'.$data["download_filenm"];
						$parts = pathinfo($old);
						
						$data["download_filenm"] = md5($old.rand(1,10000000)).'.'.strtolower($parts['extension']);
						$new = $media_dir.'download/'.$data["download_filenm"];
						
						// lets move the file into the right place with a new name
							rename($old,$new);
					}
					
					$source = ($data["download_import"] == 2) ? 'S3' : 'local';
					
					$download = array(
											'product_id' 		=> $product_id, 
											'title' 			=> $data["download_title"],
											'filenm' 			=> $data["download_filenm"],
											'filenm_orig' 		=> $data["download_filenm_orig"],
											'download_source'	=> $source,
											'download_limit' 	=> $data["download_limit"],
											'download_length' 	=> $data["download_length"],
											'download_version' 	=> $data["download_version"]
										);
					unset($data["require_download"]);
					unset($data["download_import"]);
					unset($data["download_title"]);
					unset($data["download_filenm"]);
					unset($data["download_filenm_orig"]);
					unset($data["download_limit"]);
					unset($data["download_length"]);
					unset($data["download_version"]);
				}
				unset($data["require_download"]);
				unset($data["asmSelect0"]);

			// Donation Products 
				if($data["type_id"] == '7'){
					$donation = array(
										'min_donation' 		=> $data["min_donation"],
										'allow_recurring' 	=> $data["allow_recurring"]
									);
				}
				unset($data["min_donation"]);
				unset($data["allow_recurring"]);
			
			// Product Addons
				if(isset($data["addon"])){
					if(is_array($data["addon"])){
						$addon = $data["addon"];
					}
					unset($data["addon"]);
				}
			
			// Related Items
				if(isset($data["related"])){
					$related = $data["related"];
					unset($data["related"]);
				}

			// Setup our price matrix
				// Delete any options that currently exist for this product
					$this->EE->db->delete('br_product_price', array('product_id' => $product_id)); 
				
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
												'product_id' 	=> $product_id,
												'type_id' 		=> 1, // This is the price type (reg=1/sale=2) 
												'group_id' 		=> $data['price_group'][$i],
												'price' 		=> $p,
												'qty' 			=> $data['price_qty'][$i],
												'end_dt' 		=> $data['price_end'][$i],
												'start_dt' 		=> $data['price_start'][$i],
												'sort_order' 	=> $i 
												);
									$i++;
								$this->EE->db->insert('br_product_price',$d);
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
								$this->EE->db->insert('br_product_price',$d);
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
			
			// Create a list of the default attributes. Some third_party fieldtypes
			// caused problems so we had to sanatize the post - dpd
				$allowed_attr = array(	
										"site_id","type_id","title","url",
										"attribute_set_id","detail","enabled","sku","shippable",
										"weight","featured","quantity","taxable",
										"cost","meta_title","meta_keyword","meta_descr"
									);

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
							if(in_array($key,$allowed_attr)){
								$attr[$key] = $val;
							}
						}
					}
					
					// Lets reset the attribute set id to zero 
					// if one didn't come through. 
						if(!isset($attr["attribute_set_id"])){
							$attr["attribute_set_id"] = 0;
						}
					
					// Clean up the options 
						$option = $this->_build_opts($option);
	
					// Serialize the data 
						$sOption = serialize($option);
	
			// Update the product
					$this->EE->db->where('product_id',$product_id);
					$this->EE->db->update('br_product',$attr); 
			
			// Update Custom Attributes
				
				$this->EE->db->delete('br_product_attributes', array('product_id' => $product_id)); 
				$this->EE->db->delete('br_product_attributes_option', array('product_id' => $product_id)); 

				if(isset($_FILES)){
					$file = rtrim($media_dir,'/').'/file';
					if(!file_exists($file)){
						mkdir($file,DIR_WRITE_MODE,TRUE);
					}
					
					foreach($_FILES as $key => $f){
						// Lets only deal with our files uploads. Others might want 
						// to upload files via Channel Fieldtypes
							if(strpos($key,'cAttribute_') != FALSE){
								$a = explode("_",$key);

								$title = $cAttr[$a[0]."_cAttribute_".$a[2]."_title"];
								unset($cAttr[$a[0]."_cAttribute_".$a[2]."_title"]);
								
								if($f["name"] !== ''){
									$filename = $f["name"];
									move_uploaded_file($f["tmp_name"],$file.'/'.$f["name"]);
								}else{
									// Get previous file name
									$prev = unserialize($cAttr[$a[0]."_cAttribute_".$a[2]]);
									$filename = $prev["file"];
								}
								$arr = array(	
												'title' => $title,
												'file' => $filename 
												);
								$cAttr[$a[0]."_cAttribute_".$a[2]] = $arr;	
							}
					}
				}

				foreach($cAttr as $key => $val){
					$a = explode('_',$key);
					// New Way
						$opts = $val;
					// Old Way
						if(is_array($val)){
							$val = serialize($val);
						}
						$data = array(	
											'descr' => $val, 
											'attribute_id' => $a[2], 
											'product_id' => $product_id
										);
						$this->EE->db->insert('br_product_attributes',$data);
						$pa_id = $this->EE->db->insert_id();
					
					//
						if(!is_array($opts)){
							$opts = array($opts);
						}
						$i=0;
						foreach($opts as $p)
						{
							$d = array(
											'pa_id'			=> $pa_id,
											'product_id'	=> $product_id,
											'attribute_id'	=> $a[2],
											'options'		=> $p,
											'sort'			=> $i
										);

							$this->EE->db->insert('br_product_attributes_option',$d);
							$i++;
						}	
				}
				
			// Update Product Options 
				$this->EE->db->delete('br_product_options', array('product_id' => $product_id)); 
				$data = array(	
								'options' => $sOption, 
								'product_id' => $product_id
							);
				$this->EE->db->insert('br_product_options',$data);
		
			// Update Product Images 
	
				$this->EE->db->delete('br_product_images', array('product_id' => $product_id)); 
				if(count($image) > 0){
					// Clean up the image array 
					$image["product_id"] = $product_id;
					$image = $this->_build_images($image);
					foreach($image as $img){
						$this->EE->db->insert('br_product_images',$img);
					}
				}
					
			// Update Categories
				// Need to get the current order before deleting so we maintain order
					$this->EE->db->from('br_product_category')->where('product_id',$product_id);
					$query = $this->EE->db->get();
					$cats = array();
					foreach ($query->result_array() as $row){
						$cats[$row["category_id"]] = $row["sort_order"];
					}

				// Remove the current entries
					$this->EE->db->delete('br_product_category', array(	'site_id' => $this->config->item('site_id'),
																	'product_id' => $product_id)); 
				// Put them all in
					foreach($category as $c){
						$sort = isset($cats[$c]) ? $cats[$c] : 0;  
						$data = array(	
							'site_id' 		=> $this->config->item('site_id'),
							'category_id' 	=> $c, 
							'product_id' 	=> $product_id,
							'sort_order' 	=> $sort
						);
						$this->EE->db->insert('br_product_category',$data);
					}
				
			// If its a bundle
				if(isset($bundle)){
					$this->EE->db->delete('br_product_bundle', array('parent_id' => $product_id)); 
					foreach($bundle as $b){
						$data = array(	
							'product_id' => $b, 
							'parent_id' => $product_id
						);
						$this->EE->db->insert('br_product_bundle',$data);
					}			
				}
			
			// If its a configurable product 
				if(isset($configurable)){
					$this->EE->db->delete('br_product_configurable', array('product_id' => $product_id)); 
					$this->EE->db->delete('br_product_configurable_attribute', array('product_id' => $product_id)); 
					foreach($configurable as $c){
						$config = $c;
						$config["product_id"] = $product_id;
						// Get the attributes
							$attr = $config["attribute"];
							unset($config["attribute"]);
						// Put in each configurable option row
							$this->EE->db->insert('br_product_configurable',$config);
							$configurable_id = $this->EE->db->insert_id();
						// Put in the options
						$i = 0;
						foreach($attr as $key => $val)
						{
							$arr = array(
											'configurable_id'	=> $configurable_id,
											'product_id'		=> $product_id,
											'attribute_id'		=> $key,
											'option_id'			=> $val,
											'sort'				=> $i
										);
							$this->EE->db->insert('br_product_configurable_attribute',$arr);
							$i++;
						}
					}
				}
			
			// If its a donation product 
				
			
			// If its a downloadable product 
				if(isset($download)){
					$this->EE->db->insert('br_product_download',$download);
				}
						
			// If its a donation
				if(isset($donation)){
					$this->EE->db->delete('br_product_donation', array('product_id' => $product_id)); 
					$donation["product_id"] = $product_id; 
					$this->EE->db->insert('br_product_donation',$donation);
				}
			
			// Addon Products 
				if(isset($addon)){
					$this->EE->db->delete('br_product_addon', array('parent_id' => $product_id)); 
					foreach($addon as $r){
						$data = array(	
							'product_id' => $r, 
							'parent_id' => $product_id
						);
						$this->EE->db->insert('br_product_addon',$data);
					}			
				}
				
			// Related Products
				$this->EE->db->delete('br_product_related', array('parent_id' => $product_id)); 
				if(isset($related)){
					foreach($related as $r){
						$data = array(	
							'product_id' => $r, 
							'parent_id' => $product_id
						);
						$this->EE->db->insert('br_product_related',$data);
					}			
				}	
			// Clear from cache
				remove_from_cache('product_'.$product_id);
			
			// Return our product_id
				return $product_id;
		}
	
	/**
	 * update_product_order function.
	 * 
	 * @access public
	 * @param mixed $entry_id
	 * @param mixed $cat_id
	 * @param mixed $sort_order
	 * @return void
	 */
    	public function update_product_order($entry_id,$cat_id,$sort_order)
    	{
    		$attr = array (
    					'sort_order' => $sort_order
    		);
    		
    		$this->EE->db->where('product_id',$entry_id);
    		$this->EE->db->where('category_id',$cat_id);
    		$this->EE->db->update('br_product_category',$attr); 
    	}
	
	/**
	 * delete_product function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function delete_product($product_id){
    		
    		$entry_id = $this->get_product_entry($product_id);
    		
    		$this->EE->db->delete('br_product', array('product_id' => $product_id));
    		$this->EE->db->delete('br_product_addon', array('parent_id' => $product_id)); 
    		$this->EE->db->delete('br_product_attributes', array('product_id' => $product_id)); 
    		$this->EE->db->delete('br_product_attributes_option', array('product_id' => $product_id));
    		$this->EE->db->delete('br_product_bundle', array('parent_id' => $product_id)); 
    		$this->EE->db->delete('br_product_category', array('product_id' => $product_id)); 
    		$this->EE->db->delete('br_product_configurable', array('product_id' => $product_id)); 
    		# DO NOT DELETE THIS 
    		# $this->EE->db->delete('br_product_images', array('product_id' => $product_id)); 
    		$this->EE->db->delete('br_product_entry', array('product_id' => $product_id)); 
    		$this->EE->db->delete('br_product_feeds', array('product_id' => $product_id)); 
    		$this->EE->db->delete('br_product_options', array('product_id' => $product_id)); 
    		$this->EE->db->delete('br_product_price', array('product_id' => $product_id)); 
    		$this->EE->db->delete('br_product_related', array('parent_id' => $product_id)); 
    		
    		// Added 1.1 
    		// To remove the 
    			$this->EE->db->delete('channel_titles', array('entry_id' => $entry_id)); 
    			$this->EE->db->delete('channel_data', array('entry_id' => $entry_id)); 
    
    		// Clear from cache
    			remove_from_cache('product_'.$product_id);
    		return true;
    	}

	/**
	 * update_product_status function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @param mixed $enabled
	 * @return void
	 */
    	public function update_product_status($product_id,$enabled){
    		$data = array('enabled' => $enabled);
    		$this->EE->db->where("product_id",$product_id);
    		$this->EE->db->update("br_product",$data);
    		return true;
    	}
	
	/**
	 * get_all_categories function.
	 * 
	 * @access public
	 * @return void
	 */
    	public function get_all_categories() {
    		
    		// Level 0
    		$this->EE->db->select('*');
    		$this->EE->db->from('br_category');
    		$this->EE->db->where('enabled','1');
    		$this->EE->db->where('site_id',$this->config->item('site_id'));	
    		$this->EE->db->where('parent_id',0);
    		$this->EE->db->order_by('sort','asc');
    				
    		$query = $this->EE->db->get();
    		$cat = array();
    		$i = 0;
    		foreach ($query->result_array() as $row){
    			$cat[$i]['title'] =  $row["title"];
    			$cat[$i]['category_id'] =  $row["category_id"];
    			
    			$i++;
    			
    			// Level 1
    			$this->EE->db->flush_cache();
    			
    			$this->EE->db->select('*');
    			$this->EE->db->from('br_category');
    			$this->EE->db->where('enabled','1');
    			$this->EE->db->where('site_id',$this->config->item('site_id'));	
    			$this->EE->db->where('parent_id',$row['category_id']);
    			
    			$subquery = $this->EE->db->get();
    			
    			foreach ($subquery->result_array() as $subrow){
    				
    				$cat[$i]['title'] =  ' - ' . $subrow["title"];
    				$cat[$i]['category_id'] =  $subrow["category_id"];
    			
    				$i++;
    				
    				// Level 2
    				$this->EE->db->flush_cache();
    			
    				$this->EE->db->select('*');
    				$this->EE->db->from('br_category');
    				$this->EE->db->where('enabled','1');
    				$this->EE->db->where('site_id',$this->config->item('site_id'));	
    				$this->EE->db->where('parent_id',$subrow['category_id']);
    				
    				$subsubquery = $this->EE->db->get();
    				
    				foreach ($subsubquery->result_array() as $subsubrow){
    					
    					$cat[$i]['title'] =  ' -- ' . $subsubrow["title"];
    					$cat[$i]['category_id'] =  $subsubrow["category_id"];
    				
    					$i++;			
    				}
    						
    			}			
    		}
    		return $cat;
    	}

	
	/**
	 * get_product_price function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @param int $type (default: 1)
	 * @return void
	 */
    	public function get_product_price($product_id,$type=1)
    	{
    		$price = array();
    			
    		$this->EE->db->from('br_product_price');
    		// Lets allow for passing multiples in an array
    		if(is_array($product_id)){
    			$this->EE->db->where_in('product_id',$product_id);
    		}else{
    			$this->EE->db->where('product_id',$product_id);
    		}
    		$this->EE->db->where('type_id',$type);
    		$this->EE->db->order_by('sort_order');
    		$query = $this->EE->db->get();
    			
    			foreach ($query->result_array() as $row){
    				// Lets make the array keys unique on the 
    				// group id and further on quantity
    					$price[$row["product_id"]][] = array(
    															'group_id' 	=> $row["group_id"],
    															'price' 	=> $row["price"],
    															'qty' 		=> $row["qty"],
    															'start_dt' 	=> $row["start_dt"],
    															'end_dt' 	=> $row["end_dt"],
    														);
    			}
    		if(is_array($product_id)){
    			foreach($product_id as $p){
    				if(!isset($price[$p])){
    					$price[$p] = array();
    				}
    			}
    			return $price;
    		}else{
    			if(!isset($price[$product_id])){
    				return $price;
    			}
    			return $price[$product_id];
    		}
    	}
	
	/**
	 * get_product_categories function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_categories($product_id)
    	{
    		if (isset($this->session->cache['get_product_categories'][$product_id])){
    			$cat = $this->session->cache['get_product_categories'][$product_id];
    		}else{
    			$this->EE->db->select('*');
    			$this->EE->db->where('product_id',$product_id);
    			$this->EE->db->from('br_product_category');		
    			$query = $this->EE->db->get();
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

	/**
	 * get_product_addon function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_addon($product_id){
    		if (isset($this->session->cache['get_product_addon'][$product_id])){
    			$addon = $this->session->cache['get_product_addon'][$product_id];
    		}else{
    			$this->EE->db->select('*');
    			$this->EE->db->where('parent_id',$product_id);
    			$this->EE->db->from('br_product_addon');		
    			$query = $this->EE->db->get();
    			$addon = array();
    			$i = 0;
    			foreach ($query->result_array() as $row){
    				if($r=$this->get_product_basic($row["product_id"])){
    					$addon[$i] = $r;
    					$i++;
    				}
    			}
    			$this->session->cache['get_product_addon'][$product_id] = $addon;		
    		}
    		return $addon;
    	}

	/**
	 * get_product_related function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_related($product_id){
    		if (isset($this->session->cache['get_product_related'][$product_id])){
    			$related = $this->session->cache['get_product_related'][$product_id];
    		}else{
    			$this->EE->db->where('parent_id',$product_id);
    			$this->EE->db->from('br_product_related');
    			$this->EE->db->order_by('related_id');		
    			$query = $this->EE->db->get();
    			$related = array();
    			$i = 0;
    			foreach ($query->result_array() as $row){
    				if($r=$this->get_product_basic($row["product_id"])){
    					$related[$i] = $r;
    					$i++;
    				}
    			}
    			$this->session->cache['get_product_related'][$product_id] = $related;		
    		}
    		return $related;
    	}

	/**
	 * get_product_bundle function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_bundle($product_id){
    		if(isset($this->session->cache['get_product_bundle'][$product_id])){
    			$products = $this->session->cache['get_product_bundle'][$product_id];
    		}else{
    			$this->EE->db->select('*');
    			$this->EE->db->where('parent_id',$product_id);
    			$this->EE->db->from('br_product_bundle');		
    			$query = $this->EE->db->get();
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

	/**
	 * get_product_configurable function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_configurable($product_id){
    		$this->EE->db->where('product_id',$product_id);
    		$this->EE->db->from('br_product_configurable');	
    		$this->EE->db->order_by('configurable_id','asc');
    		$query = $this->EE->db->get();
    		$products = array();
    		$i = 0;
    		foreach ($query->result_array() as $row){
    			
    			$products[$i] = $row;
    			
    			// Get the variants for the configurable product
    				if(!isset($this->session->cache['br_get_product_configurable'][$row["configurable_id"]])){
    					$this->EE->db->from('br_product_configurable_attribute');
    					$this->EE->db->where('configurable_id',$row["configurable_id"]);
    					$this->EE->db->order_by('sort');
    					$opt = $this->EE->db->get();
    					$this->session->cache['br_get_product_configurable'][$row["configurable_id"]] = $opt->result_array();
    				}
    				$products[$i]['attribute'] = $this->session->cache['br_get_product_configurable'][$row["configurable_id"]];
    
    			$i++;
    		}
    		return $products;
    	}

	/**
	 * get_product_download function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_download($product_id){
    		$this->EE->db->where('product_id',$product_id);
    		$this->EE->db->from('br_product_download');		
    		$this->EE->db->order_by('created','desc');
    		$this->EE->db->limit(1);
    		$query = $this->EE->db->get();
    		$files = array();
    		$i = 0;
    		foreach ($query->result_array() as $row){
    			$files[$i] = $row;
    			$i++;
    		}
    		return $files;
    	}
	
	/**
	 * get_product_donation function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_donation($product_id){
    		$this->EE->db->select('*');
    		$this->EE->db->where('product_id',$product_id);
    		$this->EE->db->from('br_product_donation');		
    		$this->EE->db->limit(1);
    		$query = $this->EE->db->get();
    		$arr = array();
    		$i = 0;
    		foreach ($query->result_array() as $row){
    			$arr[$i] = $row;
    			$i++;
    		}
    		return $arr;
    	}

	/**
	 * get_attributes function.
	 * 
	 * @access public
	 * @param string $set_id (default: '')
	 * @param string $product_id (default: '')
	 * @return void
	 */
    	public function get_attributes($set_id = '',$product_id = ''){
    
    			// Editing or new?
    				$isProduct 		= false;	
    				$attributes 	= array();
    				$all_attributes = array();
    				$pAttrs			= array();
    					
    			// If a product_id is supplied then 
    			// lets see if there are values
    						
    				if($product_id != ''){
    					$isProduct = true;
    					if(isset($this->session->cache['get_attributes_product_id'])){
    						$all_attributes = $this->session->cache['get_attributes_product_id'];
    					}else{
    						$this->EE->db->select('*');
    						$this->EE->db->from('br_product_attributes');
    						$this->EE->db->join('br_product_attributes_option','br_product_attributes.pa_id=br_product_attributes_option.pa_id');
    						$q = $this->EE->db->get();
    						foreach($q->result_array() as $row){
    							$all_attributes[$row["product_id"]][$row["attribute_id"]][] = $row["options"];
    						}
    						$this->session->cache['get_attributes_product_id'] = $all_attributes;
    					}
    					if(isset($all_attributes[$product_id])){
    						$pAttrs = $all_attributes[$product_id];
    					}
    				}
    
    			// Select the attributes for the type 
    			// to create a shell
    				
    				if($set_id == ''){
    					if(isset($this->session->cache['get_attributes'])){
    						$result = $this->session->cache['get_attributes'];
    					}else{
    						$this->EE->db->select('*');
    						$this->EE->db->from('br_attribute a');
    						$this->EE->db->where('a.site_id',$this->config->item('site_id'));
    						$query = $this->EE->db->get();
    						$result = $query->result_array();
    						$this->session->cache['get_attributes'] = $result;
    					}
    				}else{
    					$this->EE->db->select('*');
    					$this->EE->db->from('br_attribute a');
    					$this->EE->db->join('br_attribute_set_attribute asa', 'a.attribute_id = asa.attribute_id');
    					$this->EE->db->where('asa.attribute_set_id',$set_id);
    					$this->EE->db->order_by('asa.sort_order');
    					$this->EE->db->where('a.site_id',$this->config->item('site_id'));
    					$query = $this->EE->db->get();
    					$result = $query->result_array();
    				}
    
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
    				
    				$tmp = array();
    				foreach($attributes as $key => $val)
    				{
    					$a = $this->get_attribute_by_id($val["attribute_id"]);
    					$tmp[$key] = $val;
    					$tmp[$key]["options"] = $a["options"];
    				}
    			return $tmp;
    	}
	
	/**
	 * get_attribute_by_id function.
	 * 
	 * @access public
	 * @param mixed $attribute_id
	 * @return void
	 */
    	public function get_attribute_by_id($attribute_id){
    		if(isset($this->session->cache['get_attribute_by_id'][$attribute_id])){
    			$attributes[$attribute_id] = $this->session->cache['get_attribute_by_id'][$attribute_id];			
    		}else{
    			$this->EE->db->select('*')
    					->from('br_attribute a')
    					->join(	'br_attribute_option ao', 
    							'a.attribute_id = ao.attribute_id',
    							'left outer')
    					->where('a.attribute_id',$attribute_id)
    					->order_by('ao.sort');
    			
    			$query = $this->EE->db->get();
    			$attributes = array();
    			foreach ($query->result_array() as $row){
    				$attributes[$attribute_id] = $row;
    				$opts[] = array(
    									"attr_option_id" 	=> $row["attr_option_id"],
    									"label"				=> $row["label"],
    									"sort"				=> $row["sort"]
    								);
    				$attributes[$attribute_id]["options"] = $opts;
    			}
    			$this->session->cache['get_attribute_by_id'][$attribute_id] = $attributes[$attribute_id];
    		}
    		return $attributes[$attribute_id];
    	}

	// Get the attributes that could be used in the configurable 
	// products setup. Only dropdowns are valid. 	
		public function get_attribute_config(){
			$this->EE->db->select('*');
			$this->EE->db->from('br_attribute');
			$this->EE->db->where('fieldtype','dropdown');
			$query = $this->EE->db->get();
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

	/**
	 * get_attribute_sets function.
	 * 
	 * @access public
	 * @param string $attribute_set_id (default: '')
	 * @return void
	 */
    	public function get_attribute_sets($attribute_set_id = ''){
    		$sets = array();
    		$this->EE->db->select('*');
    		$this->EE->db->from('br_attribute_set');
    		if($attribute_set_id != ''){
    			$this->EE->db->where('attribute_set_id',$attribute_set_id);
    		}
    		$this->EE->db->where('site_id',$this->config->item('site_id'));
    		$query = $this->EE->db->get();
    		$i = 0;
    		foreach($query->result_array() as $row){
    			foreach($row as $key => $val){
    				$sets[$i][$key] = $val;
    			}
    			$i++;
    		}
    		return $sets;
    	}
	
	/**
	 * update_attribute function.
	 * 
	 * @access public
	 * @return void
	 */
    	public function update_attribute(){
    		foreach($_POST as $key => $val){
    			$data[$key] = $val;
    		}
    		
    		// Remove the attribute_id from the post
    			$attribute_id = $data["attribute_id"];
    			unset($data["attribute_id"]);
    
    		// Set some defaults
    			$data["site_id"] = $this->config->item('site_id');
    			$data["code"] = $this->_clean_code($data["code"]);
    
    		// Remove the options from the $data array so we can 
    		// update or create the parent attribute
    		
    			if(isset($data["option"])){
    				$options = $data["option"];
    				unset($data["option"]);
    			}
    			
    		// Insert / Update attrubute
    			if($attribute_id == 0){
    				$this->EE->db->insert("br_attribute",$data);
    				$attribute_id = $this->EE->db->insert_id();
    			}else{
    				$this->EE->db->where("attribute_id",$attribute_id);
    				$this->EE->db->update("br_attribute",$data);
    			}
    		
    		// Add / Remove Options
    			if(isset($options)){
    				$i = 1;
    				foreach($options["id"] as $key => $val){
    					if($options["remove"][$key] == 1){
    						// Check for existence and remove it if its not used
    							$this->EE->db->like('option_id', $options["id"][$key]);
    							$this->EE->db->from('br_product_configurable_attribute');
    							if($this->EE->db->count_all_results() > 0){
    								$_SESSION["alert"] = str_replace("%s",$options["label"][$key],lang('br_attribute_cant_remove'));		
    							}else{
    								$this->EE->db->where('attr_option_id',$options["id"][$key]);
    								$this->EE->db->delete('br_attribute_option');
    							}
    					}elseif(strpos($key,'new_') !== FALSE){
    						$d = array(
    										'attribute_id' 	=> $attribute_id,
    										'label'			=> $options["label"][$key],
    										'sort'			=> $options["sort"][$key]
    									);
    						$this->EE->db->insert('br_attribute_option',$d);
    					}else{
    						// Update it
    							$d = array(
    										'attribute_id' 	=> $attribute_id,
    										'label'			=> $options["label"][$key],
    										'sort'			=> $options["sort"][$key]
    										);
    							$this->EE->db->where('attr_option_id',$options["id"][$key])->update('br_attribute_option',$d);
    					}
    				}
    			}
    
    		return $attribute_id;
    	}
	
	/**
	 * delete_attribute function.
	 * 
	 * @access public
	 * @param mixed $attribute_id
	 * @return void
	 */
    	public function delete_attribute($attribute_id){
    		$this->EE->db->where('attribute_id', $attribute_id);
    		$this->EE->db->delete("br_attribute");
    		return true;
    	}
	
	/**
	 * get_attribute_set_list function.
	 * 
	 * Get the list of all attributes with the 
	 * ones for the given set selected
     * 
	 * @access public
	 * @param mixed $attribute_set_id
	 * @return void
	 */
    	public function get_attribute_set_list($attribute_set_id){
    		// Get them all
    			$attr = $this->get_attributes();
    		
    		// Get the selected ones
    			$this->EE->db->select('*');
    			$this->EE->db->from('br_attribute a');
    			$this->EE->db->join('br_attribute_set_attribute asa', 'a.attribute_id = asa.attribute_id');
    			$this->EE->db->where('asa.attribute_set_id',$attribute_set_id);
    			$this->EE->db->order_by('asa.sort_order');
    		
    		$query = $this->EE->db->get();
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
	
	/**
	 * update_attribute_set function.
	 * 
	 * @access public
	 * @return void
	 */
    	public function update_attribute_set(){
    
    		$attribute_set_id = $_POST["attribute_set_id"];
    		if($attribute_set_id != 0){
    			// Update the set title 
    				$this->EE->db->where('attribute_set_id', $attribute_set_id);
    				$this->EE->db->update('br_attribute_set', array('title' => $_POST["title"])); 
    				
    			// Remove the previously selected attributes
    				$this->EE->db->where('attribute_set_id', $attribute_set_id);
    				$this->EE->db->delete("br_attribute_set_attribute");
    		}else{
    			$data = array(
    							'site_id' => $this->config->item('site_id'),
    							'title' => $_POST["title"]
    						);
    			$this->EE->db->insert('br_attribute_set',$data);
    			$attribute_set_id = $this->EE->db->insert_id();
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
    				$this->EE->db->insert("br_attribute_set_attribute",$data);
    				$i++;
    			}
    			
    		return true;
    	}
	
	/**
	 * delete_attribute_set function.
	 * 
	 * @access public
	 * @param mixed $attribute_set_id
	 * @return void
	 */
    	public function delete_attribute_set($attribute_set_id){
    		$this->EE->db->where('attribute_set_id', $attribute_set_id);
    		$this->EE->db->delete("br_attribute_set");
    		$this->EE->db->where('attribute_set_id', $attribute_set_id);
    		$this->EE->db->delete("br_attribute_set_attribute");
    		return true;
    	}
	
	/**
	 * get_product_options function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
    	public function get_product_options($product_id){
    			$options = array();
    			$this->EE->db->select('*');
    			$this->EE->db->from('br_product_options');
    			$this->EE->db->where('product_id',$product_id);
    			$query = $this->EE->db->get();
    			foreach ($query->result_array() as $row){
    				$options = unserialize($row["options"]);
    			}
    			return $options;
    	}

	/**
	 * get_product_images function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @param bool $exclude (default: true)
	 * @return void
	 */
    	public function get_product_images($product_id,$exclude = true){
    			$images = array();
    			$this->EE->db->select('*');
    			$this->EE->db->from('br_product_images');
    			$this->EE->db->where('product_id',$product_id);
    			if($exclude === true){
    				$this->EE->db->where('exclude',0);
    			}
    			$this->EE->db->order_by('sort');
    			$query = $this->EE->db->get();
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

	/**
	 * get_categories function.
	 * 
	 * @access public
	 * @param int $enabled (default: 1)
	 * @param string $sort (default: 'sort')
	 * @return void
	 */
    	public function get_categories($enabled=1,$sort='sort'){
    		$possible_sort = array('sort','title');
    		if(!in_array($sort,$possible_sort)){
    			$sort = 'sort';
    		}
    		$cat = array();
    		$this->EE->db->select('*');
    		$this->EE->db->from('br_category');
    		if($enabled == 1){
    			$this->EE->db->where('enabled',1);
    		}
    		$this->EE->db->where('site_id',$this->config->item('site_id'));
    		$this->EE->db->order_by('parent_id');
    		$this->EE->db->order_by($sort);
    		$query = $this->EE->db->get();
    		foreach ($query->result_array() as $val){
    			$cat[$val["parent_id"]][$val["category_id"]] = $val;
    		}
    		return $cat;
    	}
	
	/**
	 * get_category_child function.
	 * 
	 * @access public
	 * @param mixed $category_id
	 * @return void
	 */
	public function get_category_child($category_id)
	{
		$cat = array();
		$this->EE->db->from('br_category');
		$this->EE->db->where('parent_id',$category_id);
		$this->EE->db->where('enabled',1);
		$this->EE->db->order_by('sort');
		$query = $this->EE->db->get();
		foreach ($query->result_array() as $val){
			$cat[$val["category_id"]] = $val;
		}
		return $cat;
	}
	
	/**
	 * get_category_list function.
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
	public function get_category_list($product_id)
	{
		$cat = array();
		$this->EE->db->select('*');
		$this->EE->db->from('br_product_category');
		$this->EE->db->where('product_id',$product_id);
		$query = $this->EE->db->get();
		foreach ($query->result_array() as $val){
			$cat[$val["category_id"]] = $val["category_id"];
		}
		return $cat;
	}
	
	/**
	 * get_category_meta function.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_category_meta()
	{
		$cat = array();
		$this->EE->db->select("category_id,url_title,meta_title,meta_descr,meta_keyword");
		$this->EE->db->from('br_category');
		$query = $this->EE->db->get();
		foreach ($query->result_array() as $val){
			$cat[$val["category_id"]] = $val;
		}
		return $cat;
	}
	
	
	/**
	 * get_category_collection function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function get_category_collection($id)
	{
		$products = array();
		
		if($id == ''){
			return $products;
		}
		
		if(!isset($this->session->cache['br_get_category_collection'][$id])){
			// Get the category products
					$sql = "SELECT 
								product_id 
							FROM 
								exp_br_product_category 
							WHERE 
								category_id IN (".$id.") 
							ORDER BY 
								sort_order, product_id DESC";
					$qry = $this->EE->db->query($sql);
					$rst = $qry->result_array();
					$c = array();
					foreach($rst as $r)
					{
						$c[] = $r["product_id"];	
					}
				
				if(count($c) == 0){
					return $products;
				}
				
				// Get the products	
					$sql = "SELECT 
									p.title, 
									p.product_id, 
									p.type_id, 
									p.quantity,  
									c.sort_order 
							FROM 
								exp_br_product p, 
								exp_br_product_category c 
							WHERE 
								p.product_id = c.product_id 
							AND 
								c.category_id = ".$id."
							AND 
								p.enabled = 1 
							AND 
								c.product_id IN (".join(",",$c).") 
							GROUP BY 
								p.product_id 
							ORDER BY 
								sort_order, product_id DESC";
					
					$qry = $this->EE->db->query($sql);
					$rst = $qry->result_array();
				
			#$this->EE->TMPL->log_item('BrilliantRetail Memory After get_category_by_key: '.round(memory_get_usage()/1024/1024, 2).'MB');
		
			// Get the 
				$this->EE->db->from('br_product_category');		
				$this->EE->db->where_in('product_id',$c);
				$query = $this->EE->db->get();
				$cat = array();
				$i = 0;
				foreach ($query->result_array() as $row){
					$cat[$row["product_id"]][] =  $row["category_id"];
					$i++;
				}
				
			// Start our product info array
				foreach($rst as $p){
					$products[$p["product_id"]] = array(
															"title"			=> $p["title"],
															"product_id" 	=> $p["product_id"],
															"type_id"		=> $p["type_id"], 
															"quantity"		=> $p["quantity"],
															"categories"	=> $cat[$p["product_id"]]
														);
					$p_ids[$p["product_id"]] = $p["product_id"];
				}
			
			// Get the prices
				$price 		= $this->get_product_price($p_ids,1);
				foreach($price as $key => $val){
					$products[$key]["price_matrix"] = $val;	
				}
				
			#$this->EE->TMPL->log_item('BrilliantRetail Memory After get_product_price [price]: '.round(memory_get_usage()/1024/1024, 2).'MB');
	
			// Get the sale prices
				$sale_price = $this->get_product_price($p_ids,2);
				foreach($sale_price as $key => $val){
					$products[$key]["sale_matrix"] = $val;	
				}	

			// Make sure they are in order!
				$tmp = array();
				foreach($rst as $p){
					if(isset($products[$p["product_id"]]))
					{
						$tmp[] = $products[$p["product_id"]];
					}
				}	
				$this->session->cache['br_get_category_collection'][$id] = $tmp;
		}
		
		return $this->session->cache['br_get_category_collection'][$id];
	}
	
	
	/**
	 * update_category function.
	 * 
	 * @access public
	 * @param mixed $category_id
	 * @param mixed $data
	 * @return void
	 */
	public function update_category($category_id,$data)
	{
		$this->EE->db->where('category_id', $category_id);
		$this->EE->db->update('br_category', $data); 
		return;
	}
	
	/**
	 * update_category_order function.
	 * 
	 * @access public
	 * @param mixed $category_id
	 * @param mixed $order
	 * @return void
	 */
	public function update_category_order($category_id,$order)
	{
		$data = array('sort' => $order);
		$this->EE->db->where('category_id', $category_id);
		$this->EE->db->update('br_category', $data); 
		return;
	}
	
	/**
	 * update_category_create function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	public function update_category_create($data)
	{
		$this->EE->db->insert('br_category', $data); 
		return $this->EE->db->insert_id();
	}
	
	/**
	 * update_category_delete function.
	 * 
	 * @access public
	 * @param mixed $category_id
	 * @return void
	 */
	public function update_category_delete($category_id)
	{
		// Delete the actual category 
			$this->EE->db->where('category_id', $category_id);
			$this->EE->db->or_where('parent_id', $category_id);
			$this->EE->db->delete('br_category');

		// Delete assigned products		
			$this->EE->db->where('category_id', $category_id);
			$this->EE->db->delete('br_product_category');
		return;
	}
	
	/**
	 * get_category function.
	 * 
	 * @access public
	 * @param mixed $category_id
	 * @return void
	 */
	public function get_category($category_id)
	{
		if (!isset($this->session->cache['get_category'][$category_id])){
			$cat = array();
			$this->EE->db->select('*');
			$this->EE->db->from('br_category');
			$this->EE->db->where('category_id',$category_id);
			$query = $this->EE->db->get();
			foreach ($query->result_array() as $key => $val){
				$cat[$key] = $val;
			}
			
			if(isset($cat[0]["category_id"])){
				$cat[0]["products"] = $this->get_product_by_category($cat[0]["category_id"]);
				$cat[0]["total_results"] = count($cat[0]["products"]);
			}
			// Set it in session
				$this->session->cache['get_category'][$category_id] = $cat;
			// Return array
				return $cat;
		}else{
			return $this->session->cache['get_category'][$category_id];
		}
	}
	
	/**
	 * get_category_by_key function.
	 * 
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function get_category_by_key($key)
	{
		$this->EE->db->from("br_category");
		$this->EE->db->where('url_title',$key);
		$query = $this->EE->db->get();
		if($query->num_rows() == 1){
			$row = $query->result_array();
			$category = $this->get_category($row[0]["category_id"]);
			return $category;
		}else{
			return false;
		}
	}
	
	/**
	 * cart_set function.
	 * 
	 * Set the the current cart data
	 * 
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
    	public function cart_set($data)
    	{
    		$data["ip"] = $_SERVER["REMOTE_ADDR"];
    		$this->EE->db->insert('br_cart',$data);
    		return $this->EE->db->insert_id();
    	}
	
	/**
	 * cart_update function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @param mixed $key
	 * @return void
	 */
	public function cart_update($data,$key)
	{
		$this->EE->db->where('cart_id',$key);
		$this->EE->db->update('br_cart',$data);
	}
	
	/**
	 * cart_unset function.
	 * 
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function cart_unset($key)
	{
		$this->EE->db->delete('br_cart', array('md5(cart_id)' => $key, 'status' => 0));
	}
	
	/**
	 * cart_clear function.
	 * 
	 * @access public
	 * @return void
	 */
	public function cart_clear()
	{
		$this->EE->db->delete('br_cart', array(	'session_id' => session_id(), 
											'status' => 0));
	}
	
	/**
	 * cart_get function.
	 * 
	 * @access public
	 * @param string $session_id (default: '')
	 * @return void
	 */
	public function cart_get($session_id = '')
	{
		$id = ($session_id == '') ? session_id() : $session_id;
		#if(isset($this->session->cache["cart_get"][$session_id])){
		#	$cart = $this->session->cache["cart_get"][$session_id];
		#}else{
			$cart = array();
			$this->EE->db->cache_off();
			$this->EE->db->from('br_cart');
			$this->EE->db->where('session_id',$id);
			$this->EE->db->where('status',0);
			$query = $this->EE->db->get();
			foreach ($query->result_array() as $key => $val){
				foreach($val as $k => $v){
					$cart[$k] = $v;
				}
				$cart["items"][$val["cart_id"]] = unserialize($val["content"]);
			}
			$this->session->cache["cart_get"][$session_id] = $cart;
		#}
		return $cart;
	}

	/**
	 * cart_promo function.
	 * 
	 * @access public
	 * @param string $code (default: '')
	 * @return void
	 */
	public function cart_promo($code = '')
	{
		$data = array('coupon_code' => $code);
		$this->EE->db->where('session_id',session_id());
		$this->EE->db->update('br_cart',$data);
	}
	
	/**
	 * cart_update_status function.
	 * 
     * update the cart status
     * 
	 * @access public
	 * @param mixed $id
	 * @param mixed $status
	 * @return void
	 */
	public function cart_update_status($id,$status)
	{
		$data = array('status' => $status);
		$this->EE->db->where('session_id',$id);
		$this->EE->db->update('br_cart',$data);
		return true;
	}
	
	/**
	 * cart_token function.
	 * 
     * @access public
	 * @param mixed $token
	 * @return void
	 */
	public function cart_token($token)
	{
		$data = array('token' => $token);
		$this->EE->db->where('session_id',session_id());
		$this->EE->db->update('br_cart',$data);		
		return true;
	}
	
	/**
	 * get_states function.
	 * 
	 * @access public
	 * @param string $countries (default: '')
	 * @param mixed $js_cache (default: FALSE)
	 * @return void
	 */
	public function get_states($countries = '', $js_cache = FALSE)
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
			$this->EE->db->where('enabled', 1);
			$this->EE->db->where_in('zone_id', $not_cached);
			$this->EE->db->order_by('title');
			$query = $this->EE->db->get('br_state');
			
			foreach($query->result() as $state){
				$c_title = $zone_ids[$state->zone_id];
				$cache[$c_title][$state->code] = $state->title;
			}
			
			$this->_set_cache('js_state_map', $cache);
		}
				
		return $js_cache ? array() : array_intersect_key($cache, array_flip($zone_ids));
	}
	
	/**
	 * get_countries function.
	 * 
	 * @access public
	 * @param int $enabled (default: 1)
	 * @return void
	 */
	public function get_countries($enabled=1)
	{
		$this->EE->db->from('br_zone');
		if($enabled == 1){
			$this->EE->db->where('enabled',1);
		}
		$this->EE->db->order_by('title');
		$query = $this->EE->db->get();
		$arr = array();
		foreach ($query->result_array() as $val){	
			$arr[$val["code"]] = $val;
		}
		return $arr;
	}
	
	/************************/
	/* Helper Functions 	*/
	
	/**
	 * _check_url function.
	 * 
	 * @access private
	 * @param mixed $str
	 * @param mixed $id
	 * @return void
	 */
	public function _check_url($str,$id)
	{
		$count = 0;
		// Check for duplicates in products 
			$this->EE->db->where('product_id !=',$id);
			$this->EE->db->where('url',$str);
			$this->EE->db->from('br_product');
			$count = $count + $this->EE->db->count_all_results();
		// Check for dumplicates in categories 
			$this->EE->db->where('category_id !=',$id);
			$this->EE->db->where('url_title',$str);
			$this->EE->db->from('br_category');
			$count = $count + $this->EE->db->count_all_results();
		return $count;
	}

	/**
	 * _check_sku function.
	 * 
	 * @access private
	 * @param mixed $str
	 * @param mixed $product_id
	 * @return void
	 */
	public function _check_sku($str,$product_id)
	{
		$this->EE->db->where('product_id !=',$product_id);
		$this->EE->db->where('sku',$str);
		$this->EE->db->from('br_product');
		return $this->EE->db->count_all_results();
	}
	
	/**
	 * _clean_code function.
	 * 
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	public function _clean_code($str)
	{
		$str = strtolower(preg_replace('/[^a-z_]/i','',trim(str_replace(" ","_",$str)))); 
		return $str;
	}
	
	/**
	 * _build_opts function.
	 * 
	 * @access private
	 * @param mixed $opts
	 * @return void
	 */
	public function _build_opts($opts)
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
	
	/**
	 * _build_images function.
	 * 
	 * @access private
	 * @param mixed $imgs
	 * @return void
	 */
	public function _build_images($imgs)
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
	
	/**
	 * _set_cache function.
	 * 
	 * @access private
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function _set_cache($key, $value)
	{
		$this->session->cache['br_'.$key] = $value;
	}
	
	/**
	 * _get_cache function.
	 * 
	 * @access private
	 * @param mixed $key
	 * @param mixed $default (default: FALSE)
	 * @return void
	 */
	public function _get_cache($key, $default = FALSE)
	{
		if (!isset($this->session->cache['br_'.$key])){
			return $default;
		}
		
		return $this->session->cache['br_'.$key];
	}
}