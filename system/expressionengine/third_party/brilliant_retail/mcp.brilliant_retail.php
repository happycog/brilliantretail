<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010, Brilliant2.com 			*/
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
include_once(PATH_THIRD.'brilliant_retail/core/class/core.brilliant_retail.php');

class Brilliant_retail_mcp extends Brilliant_retail_core {

	/************************/
	/* Variables 			*/
	/************************/

		public $version		= '1.0.3.5'; 
		public $vars 		= array();
		public $site_id 	= '';
		
		public $base_url 	= ''; 
		public $media_dir 	= '';
		public $media_url 	= '';
		
		public $module 		= '';
		public $method 		= '';
		public $group_access = '';
		public $submenu 	= '';
		
		// Ajax methods ignore list for 
		// member group permissions list
		
			private $method_ignore = array(	'order_update_status','order_add_note','order_remove_note',
											'product_batch','product_update','product_add_atributes',
											'product_configurable_create_options','product_search',
											'product_index_search','promo_update','config_attribute_update',
											'config_attributeset_update','config_category_update',
											'config_email_update','config_gateway_update','config_permission_update',
											'config_shipping_update','config_site_update','config_tax_update',
											"order_ajax",
											"customer_ajax",
											"product_ajax",
											"index_products",
											"order_detail",
											"customer_orders",
											"product_edit", 
											"product_new",
											"promo_new",
											"promo_edit",
											"report_detail",
											"config_feeds_edit",
											"config_attribute_create",
											"config_attribute_edit",
											"config_attributeset_create",
											"config_attributeset_edit",
											"config_attributeset_delete",
											"config_category_edit",
											"config_email_edit",
											"config_gateway_install",
											"config_gateway_edit",
											"config_gateway_remove",
											"config_permission_edit",
											"config_shipping_install",
											"config_shipping_edit",
											"config_shipping_remove",
											"config_tax_new",
											"config_tax_edit",
											"subscription_update",
											"subscription_detail");
	/**
	 * Constructor
	 *
	 */	
	function __construct($switch = TRUE,$extended = FALSE)
	{
				parent::__construct();
				
				$this->vars["media_dir"] = $this->_config["media_dir"];
				$this->vars["media_url"] = $this->_config["media_url"];
				$this->vars["site_url"] = $this->EE->config->item('site_url');
				$this->vars["site_id"] = $this->site_id;
				
				// Load the access model for the control panel
					$this->EE->load->model('access_model');
						
				// Security for our filemanager integration
					$_SESSION["filemanager"] = true;
				
			if($switch == TRUE){
					
						$this->module = $this->EE->input->get("module",true);
						$this->method = ($this->EE->input->get("method",true)) ? ($this->EE->input->get("method",true)) : "index" ;
			
						$this->base_url = str_replace('&amp;','&',BASE).'&C=addons_modules&M=show_module_cp&module='.$this->module;
						$this->vars["base_url"] = $this->base_url;

					// Do an admin access check 
						$access = $this->_check_admin_access($this->method);
					
	## IMPORTANT ADD HOOK HERE 
						
					// Generate the menu
						$this->_create_admin_menu();

					// Put in some messaging
						$this->vars['message'] = ''; 	// Container for BR system messages
						$this->vars['alert'] = '';		// Container for BR system alerts 
	
						$message = br_get('message');
						if($message){
							$this->vars['message'] = br_get('message');	
							br_unset('message');
						}
						
						$alert = br_get('alert');
						if($alert){
							$this->vars['alert'] = br_get('alert');	
							br_unset('alert');
						}

					// BrilliantRetail Version Number
						$this->vars['version'] = $this->version;
	
					// Product Types
						$this->vars['product_type'] = $this->_config['product_type'];	
					
					// Breadcrumb title
						$this->vars['cp_page_title'] = 'BrilliantRetail';
									
					// Current Site ID
						$this->site_id = $this->EE->config->item('site_id');				
						$this->vars['site_id'] = $this->site_id;

						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/br.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/jquery.dataTables.min.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/jquery.dataTables.clear.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/jquery.validate.pack.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/jquery.metadata.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/ckeditor/ckeditor.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/swfupload/swfupload.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/jquery.form.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/jquery.blockui.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/jquery.asmselect.js').'"></script>');
						$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme('/script/jquery.tableDnD.js').'"></script>');

						$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$this->_theme('/css/style.css').'" />');
						
						$this->vars['theme'] = $this->_theme();	
						
						$this->vars["site_name"] 	= $this->EE->config->item('site_name');
						$this->vars["br_header"] 	= $this->EE->load->view('_assets/_header', $this->vars, TRUE);
						$this->vars["br_logo"] 		= $this->EE->load->view('_assets/_logo', $this->vars, TRUE);
						$this->vars["br_footer"] 	= $this->EE->load->view('_assets/_footer', $this->vars, TRUE);
					
					// Set the acton url for uploading 
					// images in the product detail tab
					 	// Have to set the upload path based on the control panel 
					 	// url so we don't have crossdomain issus
				 		$this->vars['image_upload'] = $this->_theme('upload/image.php');
				 		$this->vars['download_upload'] = $this->_theme('upload/file.php');
						$_SESSION["media_dir"] = $this->vars["media_dir"];
			}else{
				$this->EE->lang->loadfile('brilliant_retail');
			}	
		}
	
	/**
	 * Index 
	 *
	 * @return method	Dashboard
	 */	
	function index()
	{
		return $this->dashboard();
	}

	/**
	 * Dashboard 
	 *
	 */	
	function dashboard() 
	{
		$this->vars['cp_page_title'] = lang('br_dashboard');

		$dir = rtrim(dirname(__FILE__),'/').'/core/report/report.sales.php';
		include_once($dir);
		
		$report = new Report_sales();
		$db_reports = array('today','week','month','quarter','year');
		foreach($db_reports as $rep){
			$report->date_range = $rep;
			$data = $report->get_report();
			$total = $data["footer"][6];
			
			$this->vars["reports"][] = array(	
											'title' => lang('br_sales_for').' '.lang($rep),
											'total' => $total,
											'link' => '#',  
											'graph' => $data["graph"] 
											); 
		}
		
		// List orders on the bottom
		$orders = $this->EE->order_model->get_order_collection('','',6);
		$i = 0;
		foreach($orders["results"] as $s){
			$orders["results"][$i]["total"] = $this->_currency_round($orders["results"][$i]["total"]);
			$i++;
		}
		$this->vars['order_collection'] = $orders["results"];
		
		$this->vars["selected"] = 'dashboard';
		$this->vars["sidebar_help"] = $this->_get_sidebar_help();
		$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
		$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

		$this->vars["stat"] = array(
										'orders' => '',
										'products' => '',
										'customers' => '',
										'promotions' => '',
										'reports' => '',
										'settings' => ''
									); 
		return $this->EE->load->view('dashboard/dashboard', $this->vars, TRUE);
	}

	/************************/
	/* Order Tab		 	*/
	/************************/
	
		function order()
		{
			$this->vars['cp_page_title'] = lang('br_order');
			$this->vars["selected"] = 'order';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			
			// 
				$this->vars["ajax_url"] = BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=order_ajax';
			
			return $this->EE->load->view('order/order', $this->vars, TRUE);	
		}
		
		function order_ajax(){
			$status = $this->_config["status"];	
			$orders = $this->EE->
							order_model->
							get_order_collection('',
												'',
												$_GET["iDisplayLength"],
												$_GET["sSearch"], 
												$_GET["iDisplayStart"],
												$_GET["iSortCol_0"],
												$_GET["sSortDir_0"]);
			
			$order = array();
			
			foreach ($orders["results"] as $row){
				// Build the member array
					$order[] = array('	<a href="'.BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=order_detail&order_id='.$row["order_id"].'">'.$row["order_id"].'</a>', 
										date('n/d/y',$row["created"]),
										'<a href="'.BASE.'&C=myaccount&id='.$row["member_id"].'">'.$row["customer"].'</a>',
										$row["total"],
										$status[$row["status_id"]],
										array('data' => '<input type="checkbox" name="batch['.$row["order_id"].']" />', 'style' => 'text-align:center')
								);
			}
			// Build the response array
				$output = array(
					"sEcho" => $_GET["sEcho"],
					"iTotalRecords" => $orders["total"],
					"iTotalDisplayRecords" => $orders["displayTotal"],
					"aaData" => $order  
				);
			// Return the json data 
				@header("HTTP/1.1 200 OK");
				echo json_encode($output);
				exit();
		}
		
		
		
		function order_detail()
		{
			$this->vars['cp_page_title'] = lang('br_order');
			$order_id = $this->EE->input->get("order_id");
			$print = $this->EE->input->get("print",true);
			$this->vars["status"] = $this->_config["status"];			
			$this->vars['order'] = $this->EE->order_model->get_order($order_id);
			
			if($this->vars['order']['photo_filename'] != ''){
				$this->vars['member_photo'] = '<img src="'.$this->EE->config->slash_item('photo_url').$this->vars['order']['photo_filename'].'" />';
			}else{
				$this->vars['member_photo'] = '<img src="/media/images/profile-pic.jpg" />';
			}
			$this->vars["selected"] = 'order';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["items"] = '';

			$this->vars["hidden"] = array(
											'order_id' => $order_id
										);
			if($print == 'true'){
				$this->vars["site_name"] = $this->EE->config->item('site_name');
				$this->vars["company"] = $this->_config["store"][$this->site_id];
				$this->vars["print_css"] = $this->_theme('css/print.css');
				$view = $this->EE->load->view('order/print', $this->vars, TRUE);	
				echo $view;
				exit();
			}else{
				return $this->EE->load->view('order/detail', $this->vars, TRUE);	
			}
		}
		
		function order_update_status()
		{
			foreach($_POST as $key => $val){
				$data[$key] = $this->EE->input->post($key);
			}

			// Is the notify flag set?
				$notify = false;
				if(isset($data["notify"])){
					$notify = true;
					unset($data["notify"]);
				}
			
			// Update the order status		
									
				// Hook before we update the order
					if($this->EE->extensions->active_hook('br_order_update_before') === TRUE){
						$data = $this->EE->extensions->call('br_order_update_before', $data); 
					}

					$this->EE->order_model->update_order_status($data);
									
				// Hook after we update the order
					if($this->EE->extensions->active_hook('br_order_update_after') === TRUE){
						$data = $this->EE->extensions->call('br_order_update_after', $data); 
					}

				$tmp = $this->EE->order_model->get_order($data["order_id"]);
			
			// 
			if($notify == true){
				$eml[0]["email"] = $tmp["member"]["email"];
				$eml[0]["order_id"] = $data["order_id"];
				$eml[0]["order_status"] = $this->_config["status"][$data["status_id"]];
				
				foreach($tmp["member"] as $key => $val){
					if(substr($key,0,3) == 'br_'){
						$eml[0][str_replace("br_","",$key)] = $val;
					}
				}
				$this->_send_email('customer-order-status', $eml);
			}
				
			br_set('message',lang('br_order_status_success'));
			$this->EE->functions->redirect($_SERVER["HTTP_REFERER"]);
			exit();
		}
		
		function order_add_note()
		{
			foreach($_POST as $key => $val){
				$data[$key] = $this->EE->input->post($key);
			}
			
			
			if(isset($_FILES)){
				$attachment = $this->vars["media_dir"].'attachments';
				if(!file_exists($attachment)){
					mkdir($attachment);
				}
				$config['upload_path'] 	= $attachment;
				$config['allowed_types'] = $this->_config["allowed_filetypes"];
				$this->EE->load->library('upload',$config);
				if($this->EE->upload->do_upload('order_note_file')){
					$result = array('upload_data' => $this->EE->upload->data()); 
					$data["filenm"] = $result["upload_data"]["file_name"];
				}
			}
			
			// 
			
			// Get Order Details
			$tmp = $this->EE->order_model->get_order($data["order_id"]);
			// Did we specify User Notification?
			$notify = false;
				if(isset($data["order_note_notify"])){
					$notify = true;
					unset($data["order_note_notify"]);
				}
			
			if($notify == true){
				$eml[0]["email"] 	= $tmp["member"]["email"];
				$eml[0]["order_id"] = $data["order_id"];
				$eml[0]["order_note"] = $data["order_note"];
				$eml[0]["fname"] 	= $tmp["member"]["br_fname"];
				$this->_send_email('customer-order-note', $eml);
			}
			
			unset($data["order_note_notify"]);
			$data["member_id"] = $this->EE->session->userdata["member_id"];
			$data["created"] = time();
			$this->EE->order_model->create_order_note($data);
			br_set('message',lang('br_order_add_note_success'));
			header('location: '.$this->base_url.'&method=order_detail&order_id='.$data["order_id"]);
			exit();
		}
		
		function order_remove_note()
		{
			$order_id = $_GET["order_id"]; 
			$order_note_id = $_GET["note_id"]; 
			$this->EE->order_model->remove_order_note($order_note_id);
			br_set('message',lang('br_order_remove_note_success'));
			header('location: '.$this->base_url.'&method=order_detail&order_id='.$order_id);
			exit();
		}
		
	/************************/
	/* Customer Tab		 	*/
	/************************/
	
		function customer()
		{
			
			$this->vars['cp_page_title'] = lang('br_customer');

			$this->vars["selected"] = 'customer';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["ajax_url"] = BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=customer_ajax';
			
			return $this->EE->load->view('customer/customer', $this->vars, TRUE);	

		}
		
		/**
		* Control panel customer live filter
		*
		* @access	public
		* @param	get 
		* @return	json 
		*/	
			function customer_ajax(){
				
				$members = $this->EE->
								customer_model->
								get_customers('',
											$_GET["iDisplayLength"],
											$_GET["iDisplayStart"],
											$_GET["sSearch"], 
											$_GET["iSortCol_0"],
											$_GET["sSortDir_0"]);
				
				$member = array();
				
				foreach ($members["results"] as $row){
					// Build the member array
						$member[] = array(	'<a href="'.BASE.'&C=myaccount&id='.$row["member_id"].'">'.$row["customer"].'</a>',
										'<a href="mailto:'.$row["email"].'">'.$row["email"].'</a>',
										date("n/d/Y",$row["join_date"]), 
										$row["group_title"],
										number_format($row["total"],2),
										'<a href="'.BASE.'&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=customer_orders&memberid='.$row["member_id"].'">'.lang('view').'</a>'
									);
				}
				// Build the response array
					$output = array(
						"sEcho" => $_GET["sEcho"],
						"iTotalRecords" => $members["total"],
						"iTotalDisplayRecords" => $members["displayTotal"],
						"aaData" => $member  
					);
				// Return the json data 
					@header("HTTP/1.1 200 OK");
					echo json_encode($output);
					exit();
			}
		
		function customer_orders()
		{
			$this->vars['cp_page_title'] = lang('br_customer_orders');
			
			$this->vars["selected"] = 'customer';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->vars["status"] = $this->_config["status"];
			
			// Get the orders
			
			$query = $this->EE->order_model->get_order_by_member($_GET['memberid']);
			
			if(count($query)==0)
			{
				$this->vars['order_collection']=array();
				$this->vars['member_info']='Customer';
			}
			else
			{
				$this->vars['member_info']=$query[0]['member']['br_fname'] . ' ' . $query[0]['member']['br_lname'];
			}
			
			$line_items='';
			
			foreach ($query as $row){
				foreach($row['items'] as $item){
					$line_items .= $item['quantity'].' x '.$item['title'].' (SKU: '.$item['sku'].')<br />';
				}
				
				$this->vars['order_collection'][] = array(
													'order_id' => $row["order_id"],
													'created' 		=> $row['created'],
													'line_items' 	=> $line_items,
													'total' 	=> $this->_currency_round($row["base"]+$row["shipping"]+$row["tax"]-$row["discount"]),
													'status_id'	=> $row["status_id"] 
												);
				$line_items='';
			}
			
			return $this->EE->load->view('customer/customer_orders', $this->vars, TRUE);	

		}	 
	/************************/
	/* Product Tab		 	*/
	/************************/
	
		function product()
		{
			// Generate the list of products based 
			// on the search terms provided. 
			
			$_SESSION["catid"] = (isset($_GET["cat_id"])) ? $_GET["cat_id"] : "";

			// Get the products 
			$this->vars['categories'] = $this->EE->product_model->get_all_categories();
			$this->vars['catid'] = $_SESSION["catid"];
			
			$this->vars['cp_page_title'] = lang('br_products');
			
			$this->EE->cp->set_right_nav(array(
				'br_new_product' => BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=product_new'
			));
			
			$this->vars["ajax_url"] = BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=product_ajax';
			
			
			$this->vars["selected"] = 'product';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			
			return $this->EE->load->view('product/product', $this->vars, TRUE);	
		}

		function product_ajax(){
			
			$prefix = $this->EE->db->dbprefix;

			$products = $this->EE
								->product_model
								->get_product_collection(	
															$_GET["sSearch"],
															$_GET["iDisplayLength"],
															$_GET["iDisplayStart"],
															$_GET["iSortCol_0"],
															$_GET["sSortDir_0"],
															$_SESSION["catid"], 
															$prefix 
														);
			
			$row = array();
			$i = 0;
			foreach($products["results"] as $p){
				$enabled = ($p['enabled'] == 1) ? 'status_on' : 'status_off' ;
				$row[] = array(	'<img src="'.$this->_theme('images/icon_'.$enabled.'.png').'" />',
								'<a href="'.$this->vars["base_url"].'&method=product_edit&product_id='.$p['product_id'].'">'.$p['title'].'</a>',
								$p['quantity'],
								$this->vars["product_type"][$p['type_id']],
								'<input type="checkbox" name="batch['.$p['product_id'].']" style="text-align:center" />');
			}
			
			$output = array(
							"sEcho" => $_GET["sEcho"],
							"iTotalRecords" => $products["total"],
							"iTotalDisplayRecords" => $products["displayTotal"],
							"aaData" => $row 
						);

			// Return the json data 
				@header("HTTP/1.1 200 OK");
				echo json_encode($output);
				exit();
		}
		
		function product_batch()
		{
			foreach($_POST as $key => $val){
				$data[$key] = $this->EE->input->post($key);
			}
			
			if(isset($data["batch"])){
				if($data["action"] == 0){
					// Delete Products 
						foreach($data["batch"] as $key => $val){
							// delete this product
								$this->EE->product_model->delete_product($key);
							// fire the delete hook
								$data["product_id"] = $key;
								if($this->EE->extensions->active_hook('br_product_delete') === TRUE){
									$data = $this->EE->extensions->call('br_product_delete', $data); 
								}
							// Log it
								$this->EE->logger->log_action("Product #".$key." deleted by ".$this->EE->session->userdata["username"]." (member_id: ".$this->EE->session->userdata["member_id"].")");
							
							remove_from_cache('product_'.$key);
						}
						br_set('message',lang('br_product_delete_success'));

				}elseif($data["action"] == 1){
					
					// Enable Products 
						foreach($data["batch"] as $key => $val){
							$this->EE->product_model->update_product_status($key,1);
							$this->EE->logger->log_action("Product #".$key." enabled by ".$this->EE->session->userdata["username"]." (member_id: ".$this->EE->session->userdata["member_id"].")");
							remove_from_cache('product_'.$key);
						}
						br_set('message',lang('br_product_update_success'));

				}elseif($data["action"] == 2){

					// Disable Products
						foreach($data["batch"] as $key => $val){
							$this->EE->product_model->update_product_status($key,0);
							$this->EE->logger->log_action("Product #".$key." disabled by ".$this->EE->session->userdata["username"]." (member_id: ".$this->EE->session->userdata["member_id"].")");
							remove_from_cache('product_'.$key);
						}
						br_set('message',lang('br_product_update_success'));
				}
				$this->_index_products();
			}
			header('location: '.$this->base_url.'&method=product');
			exit();
		}
		
		function product_new()
		{
			
			$this->EE->cp->add_js_script( array(
												'ui' => 'accordion,datepicker' 
												));
			
			$this->vars["selected"] = 'product';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

				// Build an empty product shell 
					$p = new Product();
					$products = $p->createshell();
					$this->vars["products"] = $products;
					
				// Generate the list of products based 
				// on the search terms provided. 
				$this->vars["type_id"] = 0;
				
				// Set the type options 
					$options = '<select id="type_id" name="type_id">';
					foreach($this->vars['product_type'] as $key => $val){
						$options .= '<option value="'.$key.'">'.$val.'</option>';
					}
					$options .= '</select>';
					
					$this->vars["type"] = $options;
					// member groups for the price matrix / subscriptions
						$qry = $this->EE->member_model->get_member_groups();
						$groups = array();
						foreach($qry->result_array() as $row){
							$groups[] = $row;
						}
						$this->vars["groups"] = $groups;

					// some defaults for configurable products 
						$this->vars["config_opts"] = $this->EE->product_model->get_attribute_config();
						$this->vars["config_opts_link"] =  $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->cp->fetch_action_id('Brilliant_retail_mcp', 'product_configurable_create_options');
					// some defaults for subscriptions 
						$this->vars["products"][0]["subscription"][0] = array(
																				'length' => 1,
																				'period' => 3,
																				'trial_offer' => 0,
																				'trial_price' => '',
																				'trial_period' => 1, 
																				'trial_occur' => 1,
																				'group_id' => 0,  
																				'cancel_group_id' => 0
																			);
					// get the sub_type	
						$this->vars["sub_type"] = $this->_get_sub_type();
					
				// No Attributes by default				
				$this->vars["attribute_sets"] = $this->EE->product_model->get_attribute_sets();
				$this->vars['add_attributes'] = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->cp->fetch_action_id('Brilliant_retail_mcp', 'product_add_atributes');
				$this->vars["attrs"] = '';
				$this->vars["options"] = array();
				$this->vars["images"] = array();
				
				$products = array();
				$this->vars['title'] 	= lang('br_new_product');
				$this->vars['hidden'] 	= array(
													'site_id' => $this->site_id,
													'product_id' => 0,
													'type_id' => 0 
												);
					// Get Categories
		
						$cat = $this->EE->product_model->get_categories();
				
					// Create a tree 
						if(isset($cat[0])){
							$categories = $this->_product_category_tree($cat[0],$cat,0);
						}else{
							$categories = array();
						}
						$this->vars["categories"] = $categories;
						
			$this->vars['product_feeds'] = array();
			$this->vars['feeds'] = $this->EE->feed_model->get_feeds();
			$this->vars["tab_detail"] 	= $this->EE->load->view('product/tabs/detail', $this->vars, TRUE);
			$this->vars["tab_attributes"] 	= $this->EE->load->view('product/tabs/attributes', $this->vars, TRUE);
			$this->vars["tab_price"] 	= $this->EE->load->view('product/tabs/price', $this->vars, TRUE);
			$this->vars["tab_sale_price"] 	= $this->EE->load->view('product/tabs/sale_price', $this->vars, TRUE);
			$this->vars["tab_option"] 	= $this->EE->load->view('product/tabs/option', $this->vars, TRUE);
			$this->vars["tab_category"] = $this->EE->load->view('product/tabs/category', $this->vars, TRUE);
			$this->vars["tab_image"] 	= $this->EE->load->view('product/tabs/image', $this->vars, TRUE);
			$this->vars["tab_related"] 	= $this->EE->load->view('product/tabs/related', $this->vars, TRUE);
			$this->vars["tab_seo"] 		= $this->EE->load->view('product/tabs/seo', $this->vars, TRUE);
			$this->vars["tab_feed"] = $this->EE->load->view('product/tabs/feed', $this->vars, TRUE);

			return $this->EE->load->view('product/edit', $this->vars, TRUE);	
			
		}
		
	
		function product_edit()
		{
			$this->vars["selected"] = 'product';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->EE->cp->add_js_script(  array(
												'ui' => 'datepicker' 
												));
	
			if(!isset($_GET["product_id"])){
				return $this->product();
			}
			
			$product_id = $_GET["product_id"];
			
			// Generate the list of products based 
			// on the search terms provided. 
			
			// Get the products 
			$products = $this->EE->product_model->get_products($product_id,1);
			
			$product_feeds = $this->EE->product_model->get_feed_id_by_product($product_id);
			
			if($products[0]["sale_start"] == '0000-00-00 00:00:00'){
				$products[0]["sale_start"] = '';
			}else{
				if($products[0]["sale_start"] != null){
					$products[0]["sale_start"] = date("m/d/y",strtotime($products[0]["sale_start"]));
				}
			}
			
			if($products[0]["sale_end"] == '0000-00-00 00:00:00'){
				$products[0]["sale_end"] = '';
			}else{
				if($products[0]["sale_end"] != null){
					$products[0]["sale_end"] = date("m/d/y",strtotime($products[0]["sale_end"]));
				}
			}
			
			$this->vars['product_feeds'] = $product_feeds;
			$this->vars['products'] = $products;
			
			$this->vars["title"] 	= $products[0]["title"];
			$this->vars["hidden"] 	= array(
												'site_id' => $this->site_id,
												'type_id' => $products[0]["type_id"], 
												'product_id' => $products[0]["product_id"]
												);
			
			$this->vars["type"] = $this->vars['product_type'][$this->vars['products'][0]['type_id']];
			
			$values = '';
			$fields = array();
			
			$values .= '<tr>';
			
			if(isset($products[0]["configurable"])){
				foreach($products[0]["configurable"] as $c){
					$tmp = unserialize($c["attributes"]);
					foreach($tmp as $key => $val){
						$fields[$key] = $key;	
						// build the configurable attributes into the row
							$values .= '<td><input type="hidden" name="config_attr_'.$key.'[]" value="'.urlencode($val).'" />'.urldecode($val).'</td>';
					}
					
					// Basic inputs
					
					$values .= '	<td class="w50">
										<input type="text" name="config_sku[]" value="'.$c["sku"].'" /></td>
									<td class="w50">
										<input type="text" name="config_qty[]" value="'.$c["qty"].'" /></td>
									<td>
										<select style="display:none" name="config_adjust_type[]">';
	
										$sel = ($c["adjust_type"] == 'fixed') ? 'selected="selected"' : '' ;
											$values .= '<option '.$sel.'>fixed</option>';
										$sel = ($c["adjust_type"] == 'percent') ? 'selected="selected"' : '' ;
											$values .= '<option '.$sel.'>percent</option>';
	
					$values .= '		</select>
										<input type="text" name="config_adjust[]" style="width:50px" value="'.$c["adjust"].'" /></td>
									<td class="move_config_row">
										<img src="'.$this->_theme('images/icon_move.png').'" /></td>
									<td class="w50">
										<a href="#" class="config_item_remove">'.lang('delete').'</a></td>
								</tr>';
				}
			}	
			
			// if its configurable 
				if($products[0]["type_id"] == 3){
					$this->vars["config_products"] = $this->_build_configurable_form($fields,$values);
				}
				
			// Get the available member groups
				$qry = $this->EE->member_model->get_member_groups();
				$groups = array();
				foreach($qry->result_array() as $row){
					$groups[] = $row;
				}
				$this->vars["groups"] = $groups;
			
			$this->vars["sub_type"] = $this->_get_sub_type($this->vars['products'][0]['type_id']);
			$this->vars["attrs"] = $this->_product_attrs($this->vars['products'][0]["attribute_set_id"],$this->vars['products'][0]["product_id"]);
			$this->vars["attribute_sets"] = $this->EE->product_model->get_attribute_sets();
			$this->vars['add_attributes'] = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->cp->fetch_action_id('Brilliant_retail_mcp', 'product_add_atributes');
			
			$this->vars["options"] = $this->_product_options($this->vars['products'][0]["product_id"]);
			
			// Get the images 
				$images = $this->EE->product_model->get_product_images($this->vars['products'][0]["product_id"],false);
				// Remove the large / thumb values
					unset($images["image_large"]);
					unset($images["image_large_title"]);
					unset($images["image_thumb"]);
					unset($images["image_thumb_title"]);
					
					$this->vars["images"] = $images;
			
			// Get Categories
			
				// First get the categories that 
				// apply to this product 
					$selected = $this->EE->product_model->get_category_list($this->vars['products'][0]["product_id"]);

				// Get them all 
					$cat = $this->EE->product_model->get_categories();
			
				// Create a tree 
					$categories = lang('br_no_product_categories');
					if(isset($cat[0])){
						$categories = $this->_product_category_tree($cat[0],$cat,0,$selected);
					}
					
					$this->vars["categories"] = $categories;
			$this->vars['feeds'] = $this->EE->feed_model->get_feeds();

			$this->vars["tab_detail"] = $this->EE->load->view('product/tabs/detail', $this->vars, TRUE);
			$this->vars["tab_attributes"] = $this->EE->load->view('product/tabs/attributes', $this->vars, TRUE);
			$this->vars["tab_price"] = $this->EE->load->view('product/tabs/price', $this->vars, TRUE);
			$this->vars["tab_sale_price"] 	= $this->EE->load->view('product/tabs/sale_price', $this->vars, TRUE);
			$this->vars["tab_option"] = $this->EE->load->view('product/tabs/option', $this->vars, TRUE);
			$this->vars["tab_category"] = $this->EE->load->view('product/tabs/category', $this->vars, TRUE);
			$this->vars["tab_image"] = $this->EE->load->view('product/tabs/image', $this->vars, TRUE);
			$this->vars["tab_related"] 	= $this->EE->load->view('product/tabs/related', $this->vars, TRUE);
			$this->vars["tab_seo"] = $this->EE->load->view('product/tabs/seo', $this->vars, TRUE);
			$this->vars["tab_feed"] = $this->EE->load->view('product/tabs/feed', $this->vars, TRUE);
			
			return $this->EE->load->view('product/edit', $this->vars, TRUE);	
		}
	
		function product_add_atributes()
		{
			$set_id = $this->EE->input->post('set_id');
			$attr = $this->_product_attrs($set_id);
			$i = 0;
			foreach($attr as $a){
				$req = ($a["required"] == 1) ? ' *' : '';
				echo '	<tr>
							<td>';
				if($i==0){
					echo '<input type="hidden" name="attribute_set_id" value="'.$set_id.'" />';
					$i++;
				}
					echo $a['title'].$req.'</td><td>'.$a['input'].'
							</td>
						</tr>';
			}
			exit();
		}
		
		function product_update()
		{
			// Clean up the post
				foreach($_POST as $key => $val){
					$data[$key] = $this->EE->input->post($key);
				}
				$continue = false; // Go back to the product after update?

			// Check for delete
				if(isset($data["delete"])){
					// Delete the product
					$this->EE->product_model->delete_product($data["product_id"]);
					
					// Remove the feed entries
					$this->EE->product_model->remove_product_from_feed($data['product_id']);
			
					if($this->EE->extensions->active_hook('br_product_delete') === TRUE){
						$data = $this->EE->extensions->call('br_product_delete', $data); 
					}
					
					// Reindex the products
					$this->_index_products();
					br_set('message',lang('br_product_delete_success'));
					header('location: '.$this->base_url.'&method=product');
					exit();
				}
			
			if(isset($data["duplicate"])){
				unset($data["duplicate"]);
				$data["product_id"] = 0;
				$data["title"] .= ' [copy]';
				$data["url"] .= '-copy';
				$continue = true;
			}
			
			if(isset($data["save_continue"])){
				$continue = true;
				unset($data["save_continue"]);
			}elseif(isset($data["save"])){
				$continue = false;
				unset($data["save"]);
			}
			
			// Clean up the SKU and sure it is unique
				$data["sku"] = $this->_check_product_sku($data);
			
			// Clean up the product url and make sure its unique
				$data["url"] = $this->_check_product_url($data);

			// Feeds			
				$prod_feed= array();
				if (isset($data['feed_id'])){
					$prod_feed = $data['feed_id'];
				}
			
			unset($data['feed_id']);
			
			// If product_id is 0 then its a new product
			if($data["product_id"] == 0){

				// Hook before we create the product
					if($this->EE->extensions->active_hook('br_product_create_before') === TRUE){
						$data = $this->EE->extensions->call('br_product_create_before', $data); 
					}

				// Create the product 
					$data["product_id"] = $this->EE->product_model->update_product($data,'',$this->vars["media_dir"]);
				
				// Hook after we create the product 
					if($this->EE->extensions->active_hook('br_product_create_after') === TRUE){
						$data = $this->EE->extensions->call('br_product_create_after', $data); 
					}
			}else{
				// Hook before we create the product
					if($this->EE->extensions->active_hook('br_product_update_before') === TRUE){
						$data = $this->EE->extensions->call('br_product_update_before', $data); 
					}
					
					$this->EE->product_model->update_product($data,'',$this->vars["media_dir"]);
				
				// Hook after we create the product
					if($this->EE->extensions->active_hook('br_product_update_after') === TRUE){
						$data = $this->EE->extensions->call('br_product_update_after', $data); 
					}
			}
			
			// We need to remove all the entries that exist for this product from the feed;
			$this->EE->product_model->remove_product_from_feed($data['product_id']);
			
			// Now we add all the selected feeds to the table
			if(isset($prod_feed)){
				foreach($prod_feed as $feed_id)
				{
					$feed = array(
						'feed_id' => $feed_id,
						'product_id' => $data['product_id']		
					);
					$this->EE->product_model->add_product_to_feed($feed);
				}
			}
							
			//Reindex product search
				$this->_index_products();
			
			// Clear the meta cache
				remove_from_cache('meta_info');

			// Clear the db cache 
				$this->EE->functions->clear_caching('db');

			br_set('message',lang('br_product_update_success'));
			if($continue == true){
				header('location: '.$this->base_url.'&method=product_edit&product_id='.$data["product_id"]);
			}else{
				header('location: '.$this->base_url.'&method=product');
			}
			exit();
		}

		function product_configurable_create_options()
		{
			// Get the products 
			$fields = array();
			foreach($_POST["fields"] as $f){
				$fields[] = $f;
			}
			$form = $this->_build_configurable_form($fields);
			echo $form;
			exit();
		}
		
		function product_search()
		{
			// Get the products 
			$term = isset($_GET["term"]) ? trim($_GET["term"]) : '';
			$type = isset($_GET["type"]) ? trim($_GET["type"]) : '';
			$products = $this->EE->product_model->search_products($term,$type);
			echo json_encode($products);
			exit();
		}
		
		function product_index_search()
		{
			// Reindex the search data
			$this->_index_products();
		}

	/************************/
	/* Subscriptions	 	*/
	/************************/
			function subscription()
			{ 
				// Generate the list of products based 
				// on the search terms provided. 
				
				// Get the products 
				$this->EE->load->model('subscription_model');
				$this->vars['subscriptions'] = $this->EE->subscription_model->get_subscriptions();
				$this->vars["selected"] = 'subscriptions';
				$this->vars["sidebar_help"] = $this->_get_sidebar_help();
				$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
				$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
	
				return $this->EE->load->view('subscription/subscription', $this->vars, TRUE);	
			}
	
			function subscription_detail(){ 
				// Get the subscription
					$subscription_id = $_GET["subscription_id"];
					$this->EE->load->model('subscription_model');
					$this->vars['subscriptions'] = $this->EE->subscription_model->get_subscriptions($subscription_id);
			}
	
			function subscription_update(){ 
			
			}
		
	/************************/
	/* Promotions Tab	 	*/
	/************************/
	
		function promo()
		{
			$this->vars["promo"] = $this->EE->promo_model->get_promo();
			
			// Set the header/breadcrumb 
				$this->vars['cp_page_title'] = lang('br_promotion');
			
				$this->EE->cp->set_right_nav(array(
					'br_new_promo' => BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=promo_new'
				));
			
			$this->vars["selected"] = 'promo';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->EE->cp->add_js_script( array(
												'ui' => 'tabs,datepicker' 
												));
															
			return $this->EE->load->view('promo/promo', $this->vars, TRUE);	
		}
	
		function promo_new()
		{
			// Search Url for selecting individual products
			$this->vars['product_search'] = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->cp->fetch_action_id('Brilliant_retail_mcp', 'product_search');			
			
			$this->vars["selected"] = 'promo';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);


			$this->EE->cp->add_js_script(  array(
										'ui' => 'datepicker' 
									));
			
			$cat = $this->EE->product_model->get_categories();
			
			$this->vars["categories"] = '';
			if(isset($cat[0])){
				$this->vars["categories"] = $this->_promo_category_tree($cat[0],$cat,0);
			}
			$this->vars["products"] = array();					

			$this->vars["promo"][0] = array(
											"promo_id" => 0,
											"title" => '',
										    "code" => '',
										    "start_dt" => '',
										    "end_dt" => '',
										    "code_type" => 'fixed',
										    "amount" => '',
										    "enabled" => '1',
										    "descr" => '',
										    "category_list" => '',
										    "product_list" => '',
										    "min_subtotal" => '1.00',
										    "min_quantity" => '1',
										    "uses_per" => '0'
										    );


			$this->vars["hidden"] = array('promo_id' => 0);
			return $this->EE->load->view('promo/edit', $this->vars, TRUE);
		}
		
		
		function promo_edit()
		{
			$promo_id = $this->EE->input->get('promo_id');

			if(!is_numeric($promo_id)){
				br_set('alert',lang('br_error_invalid_id'));
				header('location: '.$this->base_url.'&method=config_promo');
			}
			$this->vars['product_search'] = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->cp->fetch_action_id('Brilliant_retail_mcp', 'product_search');			
			
			$this->EE->cp->add_js_script(  array(
									'ui' => 'accordion,datepicker' 
									));
									
			$this->vars["selected"] = 'promo';
			
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			// Get the promo details 
				$this->vars["promo"] = $this->EE->promo_model->get_promo($promo_id);
				
			// Get Categories
		
				$cat = $this->EE->product_model->get_categories();
				
				$cat_list = $this->vars["promo"][0]["category_list"];
				$selected = array();
				if($cat_list != ''){
					$cat_array = json_decode($this->vars["promo"][0]["category_list"]);
					foreach ($cat_array as $c){
						$selected[$c] = $c;	
					}
				}

			// Create a tree 
				$this->vars["categories"] = $this->_promo_category_tree($cat[0],$cat,0,$selected);
			
			// Get the products 
				$this->vars["products"] = array();
				$product_list = $this->vars["promo"][0]["product_list"];
				if($product_list != ''){
					$list = json_decode($product_list);
					foreach($list as $product_id){
						$this->vars["products"][] = $this->EE->product_model->get_product_basic($product_id);
					}
				} 
			$this->vars["hidden"] = array('promo_id' => $promo_id);
			
			return $this->EE->load->view('promo/edit', $this->vars, TRUE);	
		}
		
		function promo_update()
		{
			// Check for delete
				if(isset($_POST["duplicate"])){
					$_POST["promo_id"] = 0;
					$_POST["title"] .= ' [copy]';
					unset($_POST["duplicate"]);
				}
			
			// Check for delete
				if(isset($_POST["delete"])){
					$this->EE->promo_model->delete_promo($_POST["promo_id"]);
					br_set('message',lang('br_promo_delete_success'));
					header('location: '.$this->base_url.'&method=promo');
					exit();
				}
			
			// Format the dates 
				$_POST["start_dt"] = ($_POST["start_dt"] > 0) ? date("Y-m-d 00:00:00",strtotime($_POST["start_dt"])) : '';
				$_POST["end_dt"] = ($_POST["end_dt"] > 0) ? date("Y-m-d 23:59:59",strtotime($_POST["end_dt"])) : '';

			// Format the category list
				if(isset($_POST["category_list"])){
					$_POST["category_list"] = '';
				}else{
					if(isset($_POST["category_title"])){
						$_POST["category_list"] = json_encode($_POST["category_title"]);
					}else{
						$_POST["category_list"] = '';
					}
				}
				
			// Format the category list
				if(isset($_POST["product_list"])){
					$_POST["product_list"] = '';
				}else{
					if(isset($_POST["product"])){
						$_POST["product_list"] = json_encode($_POST["product"]);
					}else{
						$_POST["product_list"] = '';
					}
				}
	
			$continue = isset($_POST["save_continue"]) ? 1 : 0 ;
			unset($_POST["category_title"]);
			unset($_POST["product"]);
			unset($_POST["save"]);
			unset($_POST["save_continue"]);
			if($_POST["promo_id"] == 0){
				$promo_id = $this->EE->promo_model->create_promo($_POST);
			}else{
				$this->EE->promo_model->update_promo($_POST);
				$promo_id = $_POST["promo_id"];
			}
			br_set('message',lang('br_promo_update_success'));
			if($continue == 1){
				header('location: '.$this->base_url.'&method=promo_edit&promo_id='.$promo_id);
				exit();
			}
			header('location: '.$this->base_url.'&method=promo');
			exit();
		}
		
	/************************/
	/* Reports Tab  		*/
	/************************/
	
		function report()
		{
			// Set the header/breadcrumb 
				$this->vars['cp_page_title'] = lang('br_report');
				
			$list = array();
			// Get the core reports
				$dir = rtrim(dirname(__FILE__),'/').'/core/report';
				$files = read_dir_files($dir);
				foreach($files as $f){
					if(substr($f,0,7) == 'report.'){
						$rem = array('report.','.php');
						$nm = strtolower(str_replace($rem,'',$f));
						$list[$f] = array(
											'name' => $nm,
											'file' => $f,
											'path' => $dir.'/'.$f);
					}
				}		
			// Get the local reports
				$dir = rtrim(dirname(__FILE__),'/').'/local/report';
				$files = read_dir_files($dir);
				foreach($files as $f){
					if(substr($f,0,7) == 'report.'){
						$rem = array('report.','.php');
						$nm = strtolower(str_replace($rem,'',$f));
						$list[$f] = array(
											'name' => $nm,
											'file' => $f,
											'path' => $dir.'/'.$f);
					}
				}		
			
			// Grab them
				$i = 0; 	
				foreach($list as $inc){
					// Include the file
						include_once($inc["path"]);
						$str = 'Report_'.$inc["name"];
						$report = new $str();
						$reports[$i++] = array(
												'title' 	=> '<a href="'.$this->base_url.'&method=report_detail&report='.$inc["name"].'">'.$report->title.'</a>',
												'type' 		=> $report->category,  
												'descr' 	=> $report->descr,
												'version' 	=> $report->version
												);
						$i++;
				}
			$this->vars["reports"] = $reports;
			$this->vars["selected"] = 'report';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			return $this->EE->load->view('report/report', $this->vars, TRUE);	
		}
		
		function report_detail()
		{

			$this->EE->cp->add_js_script( array(
												'ui' => 'datepicker' 
												));
			$list = array();
			// Get the core reports
				$dir = rtrim(dirname(__FILE__),'/').'/core/report';
				$files = read_dir_files($dir);
				foreach($files as $f){
					if(substr($f,0,7) == 'report.'){
						$rem = array('report.','.php');
						$nm = strtolower(str_replace($rem,'',$f));
						$list[$f] = array(
											'name' => $nm,
											'file' => $f,
											'path' => $dir.'/'.$f);
					}
				}		
			// Get the local reports
				$dir = rtrim(dirname(__FILE__),'/').'/local/report';
				$files = read_dir_files($dir);
				foreach($files as $f){
					if(substr($f,0,7) == 'report.'){
						$rem = array('report.','.php');
						$nm = strtolower(str_replace($rem,'',$f));
						$list[$f] = array(
											'name' => $nm,
											'file' => $f,
											'path' => $dir.'/'.$f);
					}
				}		
			
			foreach($list as $inc){
				$this->vars["report"] = $this->EE->input->get('report');
				if($inc["name"] == $this->vars["report"]){
					include_once($inc["path"]);
					$str = 'Report_'.$inc["name"];	
				}
			}
			$report = new $str();
			$this->vars["parent"] = $report->category;
			$this->vars["title"] = $report->title;
			$this->vars["detail"] = $report->get_report();

			$this->vars["input"] = '';
			
			foreach($this->vars["detail"]["input"] as $in){
				$this->vars["input"] .= $this->_build_report_input($in);
			}
				
			$this->vars["selected"] = 'report';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			if(isset($_POST["export"]) && $_POST["export"] == 1){
				$this->_build_report_csv($this->vars["detail"]);
				return;
			}
			return $this->EE->load->view('report/detail', $this->vars, TRUE);	
		}
	
	/************************/
	/* Configuration Tab 	*/
	/************************/
		
		function config()
		{
			$this->vars['cp_page_title'] = lang('br_config');
			// Set the selected menu
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = '';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->vars["content"] = '';
			
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_attribute()
		{
			$this->vars['cp_page_title'] = lang('br_config_attribute');
			
			$this->vars["attributes"] = (array)$this->EE->product_model->get_attributes();
			// Set the selected menu
			
			$this->EE->cp->set_right_nav(array(
				'br_new_attribute' => BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_attribute_create'
			));
			
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_attribute';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/attribute', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}

		function config_attribute_update()
		{
			$continue = false;
			
			// Check for duplicate
				if(isset($_POST["duplicate"])){
					$_POST["attribute_id"] = 0;
					$_POST["title"] .= ' [copy]';
					$_POST["code"] .= 'copy';
					unset($_POST["duplicate"]);
					$continue = true;
				}
			
			// Check for delete
				if(isset($_POST["delete"])){
					$this->EE->product_model->delete_attribute($_POST["attribute_id"]);
					remove_from_cache('config');
					br_set('message',lang('br_attribute_delete_success'));
					header('location: '.$this->base_url.'&method=config_attribute');
					exit();
				}
			
			// Check for Save Buttons
				if(isset($_POST["submit"])){
					if($_POST["submit"] == 'Save'){
						$continue = false;	
					}else{
						$continue = true;
					}
					unset($_POST["submit"]);
				}
			
			if($_POST["fieldtype"] == 'dropdown'){
				$_POST["options"] = $_POST["dropdown_options"];
			}elseif($_POST["fieldtype"] == 'multiselect'){
				$_POST["options"] = $_POST["multiselect_options"];
			}
			unset($_POST["dropdown_options"]);
			unset($_POST["multiselect_options"]);

			$attribute_id = $this->EE->product_model->update_attribute($_POST);
			remove_from_cache('config');
			br_set('message',lang('br_attribute_update_success'));
			if($continue == true){
				header('location: '.$this->base_url.'&method=config_attribute_edit&attribute_id='.$attribute_id);
			}else{
				header('location: '.$this->base_url.'&method=config_attribute');
			}
			exit();
		}
		
		function config_attribute_create()
		{
			$this->vars["attributes"] = array(
												'attribute_id' => 0,
												'title' => '',
												'code' => '',
												'fieldtype' => 'text',
												'required' => 0,
												'filterable' => 1,
												'default_text' => '',
												'options' => ''
												);
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_attribute';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/attribute_edit', $this->vars, TRUE);
			
			return $this->EE->load->view('config/index', $this->vars, TRUE);			
		}

		function config_attribute_edit()
		{
			$attribute_id = $this->EE->input->get('attribute_id');
			$this->vars["attributes"] = $this->EE->product_model->get_attribute_by_id($attribute_id);
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_attribute';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/attribute_edit', $this->vars, TRUE);
			br_set('message',lang('br_attribute_update_success'));
			return $this->EE->load->view('config/index', $this->vars, TRUE);			
		}
		
		
		function config_attributeset()
		{
			$this->vars["attributes"] = (array)$this->EE->product_model->get_attribute_sets();
			
				$this->EE->cp->set_right_nav(array(
					'br_new_attribute_set' => BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_attributeset_create'
				));

			// Set the selected menu
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_attributeset';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/attribute_set', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);	
		}

		function config_attributeset_create()
		{
			$attribute_set_id = 0;
			$this->vars["attributes"] = $this->EE->product_model->get_attribute_set_list($attribute_set_id);
			$attribute_set = $this->EE->product_model->get_attribute_sets($attribute_set_id);
			
			$this->vars["attribute_set_id"] = $attribute_set_id;
			$this->vars["title"] = '';

			// Set the selected menu
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_attribute';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/attribute_set_edit', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);	
		}
		
		function config_attributeset_edit()
		{
			$attribute_set_id = $this->EE->input->get('attribute_set_id');
			$this->vars["attributes"] = $this->EE->product_model->get_attribute_set_list($attribute_set_id);
			$attribute_set = $this->EE->product_model->get_attribute_sets($attribute_set_id);
			
			$this->vars["attribute_set_id"] = $attribute_set_id;
			$this->vars["title"] = $attribute_set[0]["title"];

			// Set the selected menu
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_attributeset';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/attribute_set_edit', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);	
		}	
		
		function config_attributeset_update()
		{	
			$this->EE->product_model->update_attribute_set();
			remove_from_cache('config');
			br_set('message',lang('br_attribute_set_update_success'));
			return $this->config_attributeset();
		}

		function config_attributeset_delete()
		{
			$attribute_set_id = $this->EE->input->get('attribute_set_id');
			$this->vars["attributes"] = $this->EE->product_model->delete_attribute_set($attribute_set_id);
			remove_from_cache('config');
			br_set('message',lang('br_attribute_set_delete_success'));
			header('location: '.$this->base_url.'&method=config_attributeset');
			exit();
		}
		
		function config_category()
		{
			// Set the selected menu
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_category';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			
			$this->EE->cp->add_js_script( array(
												'ui' => 'droppable' 
												));

			// Get the list of categories
				$cat = $this->EE->product_model->get_categories(0);
			// Create a tree 
				if(isset($cat[0])){
					$this->vars["categories"] = $this->_config_category_tree($cat[0],$cat,0);
				}else{
					$this->vars["categories"] = array();
				}
			$this->vars["content"] = $this->EE->load->view('config/category', $this->vars, TRUE);

			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_category_edit()
		{
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_category';
			$cat = $this->EE->product_model->get_category($this->EE->input->get('cat_id'));
			
			$prod = $this->EE->product_model->get_product_by_category($this->EE->input->get('cat_id'),"true");
			
			$cnt = 0;
			$prod_ary = array();
			
			foreach($prod as $product)
			{
				$prod_ary[$cnt]['id']=$product['product_id'];
				$prod_ary[$cnt]['title']=$product['title'];
				$prod_ary[$cnt]['sort_order']=$product['sort_order'];
				$cnt++;
			}
			
			if($cat[0]["image"] != ''){
				$cat[0]["image"] = '<input type="hidden" id="remove_image" name="remove_image" value="0" />
									<div id="cat_image_container">
										<img src="/media/images/'.$cat[0]["image"].'" width="300" style="border:1px #ccc solid;margin-bottom: 10px;" /><br />
										<a href="#" id="remove_image_link">'.lang('br_remove_image').'</a>
										<br />
										<br />
										</div>
									<script type="text/javascript">
										$(function(){
											$(\'#remove_image_link\').bind(\'click\',function(){
												$(\'#remove_image\').val(1);
												$(\'#cat_image_container\').remove();
												return false;
											});
										});
									</script>';
			}
			$this->vars['products'] = $prod_ary;
			$this->vars["category"] = $cat[0];
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->vars["content"] = $this->EE->load->view('config/category_edit', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_category_update()
		{
			if($_POST["action"] == 'order'){
				$order = explode("&",$_POST["order"]);
				$i = 0;
				foreach($order as $row){
					$category_id = str_replace("cat[]=","",$row);
					$this->EE->product_model->update_category_order($category_id,$i);
					$i++;
				}
				echo 1;
			}elseif($_POST["action"] == 'update'){
				$image = '';
				if(isset($_FILES)){
						$config['upload_path'] 	= $this->vars["media_dir"].'images';
						$config['allowed_types'] = 'gif|jpg|png';
						#$config['max_size'] = '4096'; 
						#$config['max_width'] = '1024';
						#$config['max_height'] = '768';
						$this->EE->load->library('upload',$config);
						if($this->EE->upload->do_upload('image')){
							$result = array('upload_data' => $this->EE->upload->data()); 
							$image = $result["upload_data"]["file_name"];
						}
				}
				
				$data = array(
								"category_id" => $_POST["category_id"],
								"title" => $_POST["title"], 
								"detail" => $_POST["detail"],  
					     		"url_title" => $_POST["url_title"], 
					     		"enabled" => $_POST["enabled"], 
					     		"template_path" => $_POST["template_path"],
					     		"meta_title" => $_POST["meta_title"], 
					     		"meta_descr" => $_POST["meta_descr"], 
					     		"meta_keyword" => $_POST["meta_keyword"] 
							);
				
				// If the remove was passed remove it.
					if(isset($_POST["remove_image"]) && $_POST["remove_image"] == 1){
						$data["image"] = $image;
						unset($_POST["remove_image"]);
					}
				// If a new image was set add it.
					if($image != ''){
						$data["image"] = $image;
					}
				// lets deal with the product ordering process
				if(!empty($_POST['items']))
				{
					foreach($_POST['items'] as $key => $val)
					{
						$this->EE->product_model->update_product_order($key,$_POST['category_id'],$val);
					}
				}
				
				$data["url_title"] = $this->_check_category_url($data,$_POST["category_id"]);
				$this->EE->product_model->update_category($_POST["category_id"],$data);
				$this->EE->functions->clear_caching('db');
				
				// Clear the meta cache
				remove_from_cache('meta_info');
				
				return $this->config_category();
			}elseif($_POST["action"] == 'create'){
				$data = array(
								"site_id" => $this->site_id,
								"title" => trim($_POST["title"]), 
								"url_title" => $_POST["title"], 
								"parent_id" => $_POST["parent_id"],
								"sort" => -time() 
							);
				$data["url_title"] = $this->_check_category_url($data,0);
				$cat_id = $this->EE->product_model->update_category_create($data);
				$this->EE->functions->clear_caching('db');
				echo $cat_id;			
			}elseif($_POST["action"] == 'delete'){
				$cat_id = $this->EE->product_model->update_category_delete($_POST["category_id"]);
				$this->EE->functions->clear_caching('db');
				return $this->config_category();
			}
			exit();
		}

		/*
			function config_catalog()
			{
				// Set the selected menu
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_catalog';
				$this->vars["sidebar_help"] = $this->_get_sidebar_help();
				$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
				$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
				$this->vars["content"] = $this->EE->load->view('config/catalog', $this->vars, TRUE);
				return $this->EE->load->view('config/index', $this->vars, TRUE);
			}
		*/

		function config_email()
		{
			// Check available templates against 
			// templates in the DB for the site id
				
				$path = PATH_THIRD.'brilliant_retail/core/notifications';
				$files = read_dir_files($path);
				
				$list = $this->EE->email_model->get_emails_by_site_id($this->site_id);
				$emails = array();
			
				foreach($files as $f){
					$nm = substr($f,0,-4);
					if(isset($list[$nm])){
						$emails[$list[$nm]["title"]] = array(
																'email_id' => $list[$nm]["email_id"], 
																'title' => lang($nm),
																'version' => $list[$nm]["version"] 
															);
					}else{
						include_once(rtrim($path,"/")."/".$f);
						$url = rtrim($this->EE->config->item('site_url'),"/");
						$a = explode('/',$url);
						$email = rtrim('contact@'.$a[count($a)-1]);
						$data = array(
										'site_id' => $this->EE->config->item('site_id'),
										'title' => $nm,
										'version' => $msg["version"],
										'content' => $msg["content"],
										'subject' => isset($msg["subject"]) ? $msg["subject"] : lang($nm),   
										'from_name' => $this->EE->config->item('site_name'), 
										'from_email' => $email 
									);
						$data["email_id"] = $this->EE->email_model->create_email($data);							
						$emails[$nm] = $data;
					}
				}
			// Send all templates to the view
				$this->vars["emails"] = $emails;
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_email';
				$this->vars["sidebar_help"] = $this->_get_sidebar_help();
				$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
				$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
				$this->vars["content"] = $this->EE->load->view('config/email', $this->vars, TRUE);
				return $this->EE->load->view('config/index', $this->vars, TRUE);	
		}

		function config_email_edit()
		{
			$email_id = $this->EE->input->get('email_id');
			$this->vars["email"] = $this->EE->email_model->get_email_by_id($email_id);
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_email';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/email_edit', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_email_update()
		{
			$email_id = $_POST["email_id"];
			$data = array(
							'subject' 		=> $_POST['subject'],
							'content' 		=> $_POST['content'],
							'from_name' 	=> $_POST['from_name'],
							'from_email'	=> $_POST['from_email'],
							'bcc_list' 		=> $_POST['bcc_list'] 
						);
			$this->EE->email_model->update_email($email_id,$data);
			br_set('message',lang('br_email_update_success'));
			header('location: '.$this->base_url.'&method=config_email');
			exit();
		}
		
		function config_feeds()
		{
			$this->vars['cp_page_title'] = lang('br_config_feeds');
			
			$this->vars["selected"]     = 'config';
			$this->vars["sub_selected"] = 'config_feeds';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"]         = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"]      = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->vars["feeds"]        = $this->EE->feed_model->get_feeds();
			$this->vars['feed_aid']     = $this->EE->core_model->get_aid('Brilliant_retail','pull_feed');
			$this->vars["content"]      = $this->EE->load->view('config/feed', $this->vars, TRUE);
			
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_feeds_edit()
		{		  
  			// Load Libraries & Helpers
  				$this->EE->load->library( array('form_validation') );

			$feed_data = array(
								'feed_title'  => '',
								'feed_code'   => '',
								'feed_id'     => '',
		 						);

			$feed_id    = $this->EE->input->get('feed_id');
			
			if ( $feed_id != '' ){
				$feed_data  = $this->EE->feed_model->get_feeds($feed_id); 
			}
			
  	  
	  		// Configure Form Validation
		  	  $rules  = array(
		        array(
		          'field'   => 'feed_title',
		          'label'   => lang( 'feed_title' ),
		          'rules'   => 'required'
		        ),
		        array(
		          'field'   => 'feed_code',
		          'label'   => lang( 'feed_code' ),
		          'rules'   => 'required|alpha_dash' . ($this->EE->input->post('feed_id') == '' ? '|callback__feed_code_exists' : '')
		        )
		      );
  	  		
  	  		$this->EE->form_validation->set_rules( $rules )->set_error_delimiters('<div class="notice">', '</div>'); 
			$this->EE->form_validation->set_message('_feed_code_exists', lang('br_feed_code_exists'));
      
	      // Get Feed Data
	      $feed_data = array(
	        'feed_title'  => $this->EE->input->post('feed_title') != '' ? $this->EE->input->post('feed_title') : $feed_data['feed_title'],
	        'feed_code'   => $this->EE->input->post('feed_code') != '' ? $this->EE->input->post('feed_code') : $feed_data['feed_code'],
	        'feed_id'     => $this->EE->input->post('feed_id') != '' ? $this->EE->input->post('feed_id') : $feed_data['feed_id'],
	      );
		  
		  // Form Validation
		  if ( $this->EE->input->post('submit') )
		  {
  		  if ( $this->EE->form_validation->run() )
  		  {
  		    // Create or Update Feed
		      $feed_id = $this->EE->feed_model->update_feed($feed_data);
		      
  		    if ( $this->EE->input->post('feed_id') != '' )
  		    {
  				  br_set( 'message', lang('br_feed_update_success') );
  		    }
  		    else
  		    {
  				  br_set( 'message', lang('br_feed_create_success') ); 
  		    }
		      
  				// Redirect User
  				if( $this->EE->input->post('submit') == 'Save' )
  				{
  				  $this->EE->functions->redirect( $this->base_url . AMP . 'method=config_feeds'); 
  				}
  				else
  				{
  				  $this->EE->functions->redirect( $this->base_url . AMP . 'method=config_feeds_edit' . AMP . 'feed_id=' . $feed_id ); 
  				}
  		  }
  		  else
  		  {
  				br_set( 'message', lang('br_feed_update_failure') );
  		  } 
		  }
		  
		  // Delete Feed
  		if( $this->EE->input->post('delete') ){
  			$this->EE->feed_model->delete_feed($this->EE->input->post('feed_id'));
  			remove_from_cache('config');
  			br_set('message',lang('br_feed_delete_success'));
  			$this->EE->functions->redirect( $this->base_url . AMP . 'method=config_feeds'); 
  		}
			
			// Prepare Interface
			$this->vars['feed']         = $feed_data;
			$this->vars['categories']   = $this->EE->product_model->get_all_categories();
			$this->vars["selected"]     = 'config';
			$this->vars["sub_selected"] = 'config_feeds';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"]         = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"]      = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->vars['products']     = $this->EE->product_model->get_products_by_feed($feed_id);
			$this->vars["content"]      = $this->EE->load->view('config/feed_edit', $this->vars, TRUE);
			
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function _feed_code_exists( $code )
		{
		  $feeds = $this->EE->feed_model->get_feed_by_code( $code );
		  return (count( $feeds ) == 0 ? TRUE : FALSE);
		}

		function config_gateway()
		{
			
			// Set the selected menu
				$this->vars['cp_page_title'] = lang('br_config_gateway');
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_gateway';
				$this->vars["sidebar_help"] = $this->_get_sidebar_help();
				$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
				$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);


			// Load the content
				
				$files = read_system_files('gateway');		
				$gateway = array();
				
				foreach($files as $f){
					if(isset($this->_config["gateway"][$this->site_id][$f["code"]])){
						include_once($f["path"]);
						$str = 'Gateway_'.$f["code"];
						$class = new $str();
						$version = $class->version;
						$installed = $this->_config["gateway"][$this->site_id][$f["code"]]["version"];
						if($version > $installed){
							$class->update($installed,$this->_config["gateway"][$this->site_id][$f["code"]]["config_id"]);
						}else{
							$version = $installed;
						}
						$gateway[$this->_config["gateway"][$this->site_id][$f["code"]]["title"]] = array(
												'config_id'	=> $this->_config["gateway"][$this->site_id][$f["code"]]["config_id"],
												'title' 	=> $this->_config["gateway"][$this->site_id][$f["code"]]["title"],
												'code' 		=> $f["code"],
												'version' 	=> $version,
												'descr' 	=> $this->_config["gateway"][$this->site_id][$f["code"]]["descr"],
												'type' 		=> $f["type"], 												
												'enabled' 	=> $this->_config["gateway"][$this->site_id][$f["code"]]["enabled"], 
												'has_options' => isset($this->_config["gateway"][$this->site_id][$f["code"]]["config_data"]) ? 1 : 0, 
												'installed' => 1
											);
					}else{
						include_once($f["path"]);
						$str = 'gateway_'.$f["code"];
						$class = new $str();
						$gateway[$class->title] = array(
												'title' 	=> $class->title,
												'code'		=> $f["code"],
												'version' 	=> $class->version,
												'descr' 	=> $class->descr,
												'type' 		=> $f["type"],												
												'enabled' 	=> 0, 
												'installed' => 0  											
											);
					}
				}
				ksort($gateway);
				$this->vars["modules"] = $gateway;
			$this->vars["content"] = $this->EE->load->view('config/gateway', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_gateway_install()
		{
			$type = trim(strtolower($_GET["type"]));
			$code = trim(strtolower($_GET["code"]));
			$path = rtrim(dirname(__FILE__),'/').'/'.$type.'/gateway/gateway.'.$_GET["code"].'.php';

			if(!file_exists($path)){ 
				br_set('alert',lang('br_module_install_error'));
				header('location: '.$this->base_url.'&method=config_gateway');
				exit();
			}else{
				include_once($path);
				$str = 'Gateway_'.$code;
				$class = new $str();
				$config_id = $this->EE->core_model->module_install(
																		'gateway',
																		$class->title,
																		$class->label,
																		$code,
																		$class->descr,
																		$class->version
																	);
				br_set('message',lang('br_module_install_success'));
				$class->install($config_id);
			}
			remove_from_cache('config');
			header('location: '.$this->base_url.'&method=config_gateway_edit&config_id='.$config_id.'&code='.$code);
			exit();
		}
		
		function config_gateway_edit()
		{
			// Set the selected menu
				$this->vars['cp_page_title'] = lang('br_config_gateway');
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_gateway';
				
				$code = $_GET["code"];

				$fields = array();

				if(isset($this->_config["gateway"][$this->site_id][$code]["config_data"])){
					foreach($this->_config["gateway"][$this->site_id][$code]["config_data"] as $f){
						// Use our input functions
							$fx = '_producttype_'.$f["type"];
							
							$input = $this->$fx($f["config_data_id"],
												'',
												$f["label"],
												$f["required"],
												$f["value"],
												$f["options"]); 
	
						// Create a fields array
						// for the module settings
							$fields[] = array(
												'label' 	=> $f["label"],
												'input' 	=> $input,
												'descr' 	=> $f["descr"],
												'required' 	=> $f["required"]
											);
					}
				}
			
			$this->vars["config_id"] = $this->_config["gateway"][$this->site_id][$code]["config_id"];
			$this->vars["title"] 	= $this->_config["gateway"][$this->site_id][$code]["title"];
			$this->vars["label"] 	= $this->_config["gateway"][$this->site_id][$code]["label"];
			$this->vars["sort"] 	= $this->_config["gateway"][$this->site_id][$code]["sort"];
			$this->vars["enabled"] 	= $this->_config["gateway"][$this->site_id][$code]["enabled"];
			$this->vars["fields"] 	= $fields;

			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/gateway_edit', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_gateway_update()
		{
			remove_from_cache('config');
			foreach($_POST as $key => $val){
				$data[$key] = $val;
			}
			$this->EE->core_model->module_update($data);
			br_set('message',lang('br_module_update_success'));
			header('location: '.$this->base_url.'&method=config_gateway');
			exit();
		}

		function config_gateway_remove()
		{
			$config_id = strtolower($this->EE->input->get("config_id",true));
			$code = strtolower($this->EE->input->get("code",true));
			$str = 'Gateway_'.$code;
			$class = new $str();
			$class->remove($config_id);
			$this->EE->core_model->module_remove($_GET["config_id"]);
			br_set('message',lang('br_module_remove_success'));
			remove_from_cache('config');
			header('location: '.$this->base_url.'&method=config_gateway');
			exit();
		}

		function config_permission()
		{
			$this->vars["groups"] = $this->EE->access_model->get_member_groups();

			// Set the selected menu
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_permission';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->vars["content"] = $this->EE->load->view('config/permission', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}

		function config_permission_edit()
		{
			$group_id = $this->EE->input->get("group_id",true);
			$this->vars["permissions"] = $this->_admin_permission_tree($group_id);
			$this->vars["group"] = $this->EE->access_model->get_group_title($group_id);
			
			// Set the selected menu
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_permission';
				$this->vars["sidebar_help"] = $this->_get_sidebar_help();
				$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			// Form stuff 
				$this->vars["hidden"] = array(
												'site_id' => $this->site_id,
												'group_id' => $group_id 
											);
				$this->vars["store"] = $this->EE->store_model->get_store_by_id($this->site_id);

			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->vars["content"] = $this->EE->load->view('config/permission_edit', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}		

		function config_permission_update()
		{
			// Remove any previous group records
				$this->EE->access_model->delete_admin_access($_POST["group_id"]);
			
			// Add each of the permissions 
				if(isset($_POST["permissions"])){
					foreach($_POST["permissions"] as $p){
						$a = explode("|",$p);
						$data = array(
										'site_id' => $this->site_id, 
										'group_id' => $_POST["group_id"],
										'class' => $a[0],
										'method' => $a[1] 
									);
						$this->EE->access_model->create_admin_access($data);
					}
				}
		
			// Set a message and return to overview
				br_set('message',lang('br_permission_update_success'));
				header('location: '.$this->base_url.'&method=config_permission');
				exit();
		}

		function config_shipping()
		{
			
			// Set the selected menu
				$this->vars['cp_page_title'] = lang('br_config_shipping');
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_shipping';

				$this->vars["sidebar_help"] = $this->_get_sidebar_help();
				$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
				$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);


			// Load the content
				
				$files = read_system_files('shipping');		
				
				$shipping = array();
				
				foreach($files as $f){
					if(isset($this->_config["shipping"][$this->site_id][$f["code"]])){
						$str = 'Shipping_'.$f["code"];
						if(!class_exists($str)){
							include_once($f["path"]);
						}
						$class = new $str();
						$version = $class->version;
						$installed = $this->_config["shipping"][$this->site_id][$f["code"]]["version"];
						if($version > $installed){
							$class->update($installed,$this->_config["shipping"][$this->site_id][$f["code"]]["config_id"]);
						}else{
							$version = $installed;
						}
						$shipping[$this->_config["shipping"][$this->site_id][$f["code"]]["title"]] = array(
												'config_id'	=> $this->_config["shipping"][$this->site_id][$f["code"]]["config_id"],
												'title' 	=> $this->_config["shipping"][$this->site_id][$f["code"]]["title"],
												'code' 		=> $f["code"],
												'version' 	=> $version,
												'descr' 	=> $this->_config["shipping"][$this->site_id][$f["code"]]["descr"],
												'type' 		=> $f["type"], 												
												'enabled' 	=> $this->_config["shipping"][$this->site_id][$f["code"]]["enabled"], 
												'has_options' => isset($this->_config["shipping"][$this->site_id][$f["code"]]["config_data"]) ? 1 : 0
											);
					}else{
						include_once($f["path"]);
						$str = 'Shipping_'.$f["code"];
						$class = new $str();
						$shipping[$class->title] = array(
												'title' 	=> $class->title,
												'code'		=> $f["code"],
												'version' 	=> $class->version,
												'descr' 	=> $class->descr,
												'type' 		=> $f["type"],												
												'enabled' 	=> 0 											
											);
					}
				}
				ksort($shipping);
				$this->vars["modules"] = $shipping;

			$this->vars["content"] = $this->EE->load->view('config/shipping', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_shipping_install()
		{
			$type = trim(strtolower($_GET["type"]));
			$code = trim(strtolower($_GET["code"]));
			$path = rtrim(dirname(__FILE__),'/').'/'.$type.'/shipping/shipping.'.$_GET["code"].'.php';
			if(!file_exists($path)){ 
				br_set('alert',lang('br_module_install_error'));
				header('location: '.$this->base_url.'&method=config_shipping');
				exit();
			}else{
				include_once($path);
				$str = 'Shipping_'.$code;
				$class = new $str();
				$config_id = $this->EE->core_model->module_install(
																		'shipping',
																		$class->title,
																		$class->label,
																		$code,
																		$class->descr,
																		$class->version
																	);
				br_set('message',lang('br_module_install_success'));
					$data[] = array(
								'config_id' => $config_id, 
								'label'	 	=> lang('br_label'), 
								'code' 		=> 'label',
								'type' 		=> 'text',
								'sort' 		=> 0, 
								'value' 	=> $class->label
								);
				/*
					// Depreciating internal enable setting.
					$data[] = array(
								'config_id' => $config_id, 
								'label'	 	=> lang('br_enabled'), 
								'code' 		=> 'enabled',
								'type' 		=> 'dropdown',
								'options' 	=> '1:'.lang('br_yes').'|0:'.lang('br_no'), 	
								'sort' 		=> 0, 
								'value' 	=> 1
								);
				*/
				foreach($data as $d){
					$this->EE->db->insert('br_config_data',$d);
				}
				$class->install($config_id);
			}
			remove_from_cache('config');
			header('location: '.$this->base_url.'&method=config_shipping_edit&config_id='.$config_id.'&code='.$code);
			exit();
		}
		
		function config_shipping_edit()
		{
			// Set the selected menu
				$this->vars['cp_page_title'] = lang('br_config_shipping');
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_shipping';
				
				$code = $_GET["code"];
				foreach($this->_config["shipping"][$this->site_id][$code]["config_data"] as $f){
					// Use our input functions
						$fx = '_producttype_'.$f["type"];
						
						$input = $this->$fx($f["config_data_id"],
											'',
											$f["label"],
											$f["required"],
											$f["value"],
											$f["options"]); 

					// Create a fields array
					// for the module settings
						$fields[] = array(
											'label' 	=> lang($f["label"]),
											'input' 	=> $input,
											'descr' 	=> $f["descr"],
											'required' 	=> $f["required"]
										);
					$this->vars["config_id"] = $this->_config["shipping"][$this->site_id][$code]["config_id"];
					$this->vars["title"] 	= $this->_config["shipping"][$this->site_id][$code]["title"];
					$this->vars["fields"] 	= $fields;
				}
	
			$this->vars["label"] 	= $this->_config["shipping"][$this->site_id][$code]["label"];
			$this->vars["enabled"] 	= $this->_config["shipping"][$this->site_id][$code]["enabled"];
			$this->vars["sort"] 	= $this->_config["shipping"][$this->site_id][$code]["sort"];
			
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			$this->vars["content"] = $this->EE->load->view('config/shipping_edit', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}
		
		function config_shipping_update()
		{
			remove_from_cache('config');
			foreach($_POST as $key => $val){
				if(is_array($val)){
					$data[$key] = serialize($_POST[$key]);
				}else{
					$data[$key] = $val;
				}
			}
			$this->EE->core_model->module_update($data);
			br_set('message',lang('br_module_update_success'));
			header('location: '.$this->base_url.'&method=config_shipping');
			exit();
		}

		function config_shipping_remove()
		{
			$config_id = $this->EE->input->get("config_id",true);
			$code 	= $this->EE->input->get("code",true);
			$str 	= 'Shipping_'.$code;
			$class 	= new $str();
			$class->remove($config_id);
			$this->EE->core_model->module_remove($config_id);
			br_set('message',lang('br_module_remove_success'));
			remove_from_cache('config');
			header('location: '.$this->base_url.'&method=config_shipping');
			exit();
		}
		
		function config_site()
		{
			// Load the accordion ui plugin
			$this->EE->cp->add_js_script( array(
									'ui' => 'accordion' 
									));

			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_site';
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			
			$this->vars["hidden"] = array(
											'site_id' => $this->site_id 
										);
			$query = $this->EE->member_model->get_member_groups();
			$groups = $query->result_array();
			$this->vars["groups"] = $groups;
			
			$this->vars["store"] = $this->EE->store_model->get_store_by_id($this->site_id);
			$this->vars["currencies"] = $this->EE->store_model->get_currencies();
			$this->vars["countries"] = $this->EE->product_model->get_countries(0);
			$this->vars["content"] = $this->EE->load->view('config/site_edit', $this->vars, TRUE);
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}		
		
		function config_site_update()
		{ 
			// Set the selected menu
			foreach($_POST as $key => $val){
				$data[$key] = $this->EE->input->post($key,TRUE);
			}
			// Unset some unwanted post variables
				unset($data["submit"]);
				unset($data["max_file_size"]);
			
			// Update the countries			
				if(!isset($data["countries"])) $data["countries"] = array();
				$this->EE->store_model->update_countries($data);
				unset($data["countries"]);
			
			if(isset($_FILES)){
					$config['upload_path'] 	= $this->vars["media_dir"];
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size'] = '1000'; 
					$config['max_width'] = '1024';
					$config['max_height'] = '768';
					$this->EE->load->library('upload',$config);
					if($this->EE->upload->do_upload('logo')){
						$result = array('upload_data' => $this->EE->upload->data()); 
						$data["logo"] = $result["upload_data"]["file_name"];
					}
			}
			if(!uuid_validate($data["license"])){
				$data["license"] = '';
				br_set('alert',lang('br_invalid_license'));
			}
			$this->EE->store_model->update_store($data);
			br_set('message',lang('br_store_update_success'));
			header('location: '.$this->base_url.'&method=config_site');
			exit();
		}
		
		function config_tax()
		{ 
			$this->vars["selected"] = 'config';
			$this->vars["sub_selected"] = 'config_tax';
			
			$this->EE->cp->set_right_nav(array('br_new_tax' => BASE.AMP.'C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_tax_new'));
			
			$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);
			$this->vars["tax"] = $this->EE->tax_model->list_taxes();
			$this->vars["content"] = $this->EE->load->view('config/tax', $this->vars, TRUE);	
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}

		function config_tax_new()
		{
			// GET THE TAX ID 
				$tax_id = 0;

			// Load Menu			
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_tax';
				$this->vars["sidebar_help"] = $this->_get_sidebar_help();
				$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
				$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			// 
				$this->vars["hidden"] = array('tax_id' => $tax_id);
			// Get the tax info 				
				$this->vars["states"] = $this->EE->tax_model->get_state();
				$this->vars["zones"] = $this->EE->tax_model->get_zone();
				$this->vars["tax"] = array(
												'tax_id' 	=> 0,
												'title' 	=> '',
												'zone_id' 	=> '',
												'state_id' 	=> '',
												'zipcode' 	=> '',
												'rate' 		=> '10.00'
											);
				
			$this->vars["content"] = $this->EE->load->view('config/tax_edit', $this->vars, TRUE);	
			
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}

		function config_tax_edit()
		{
			// GET THE TAX ID 
				$tax_id = ($_GET["tax_id"]) * 1;
			// Load Menu			
				$this->vars["selected"] = 'config';
				$this->vars["sub_selected"] = 'config_tax';
				$this->vars["sidebar_help"] = $this->_get_sidebar_help();
			$this->vars["help"] = $this->EE->load->view('_assets/_help', $this->vars, TRUE);
			$this->vars["br_menu"] = $this->EE->load->view('_assets/_menu', $this->vars, TRUE);

			// 
				$this->vars["hidden"] = array('tax_id' => $tax_id);
			// Get the tax info 				
				$this->vars["states"] = $this->EE->tax_model->get_state();
				$this->vars["zones"] = $this->EE->tax_model->get_zone();
				$this->vars["tax"] = $this->EE->tax_model->get_tax_by_id($tax_id);

			$this->vars["content"] = $this->EE->load->view('config/tax_edit', $this->vars, TRUE);	
			
			return $this->EE->load->view('config/index', $this->vars, TRUE);
		}

		function config_tax_update()
		{
			// Check for delete
				if(isset($_POST["duplicate"])){
					$_POST["tax_id"] = 0;
					$_POST["title"] .= ' [copy]';
					unset($_POST["duplicate"]);
				}
			
			// Check for delete
				if(isset($_POST["delete"])){
					$this->EE->tax_model->delete_tax($_POST["tax_id"]);
					br_set('message',lang('br_tax_delete_success'));
					header('location: '.$this->base_url.'&method=config_tax');
					exit();
				}
			
			$continue = isset($_POST["save_continue"]) ? 1 : 0 ;
			unset($_POST["save"]);
			unset($_POST["save_continue"]);
			if($_POST["tax_id"] == 0){
				$tax_id = $this->EE->tax_model->create_tax($_POST);
			}else{
				$this->EE->tax_model->update_tax($_POST);
				$tax_id = $_POST["tax_id"];
			}
			br_set('message',lang('br_tax_update_success'));
			if($continue == 1){
				header('location: '.$this->base_url.'&method=config_tax_edit&tax_id='.$tax_id);
				exit();
			}
			header('location: '.$this->base_url.'&method=config_tax');
			exit();
		}

		
	/* Helper Functions */
		
		function _get_sub_type($type = '') 
		{
			$str = '';

			$this->vars['product_search'] = $this->EE->functions->fetch_site_index(0,0).QUERY_MARKER.'ACT='.$this->EE->cp->fetch_action_id('Brilliant_retail_mcp', 'product_search');			
			
			if($type != ''){
				if($type == 2 || $type == 3 || $type == 4 || $type == 6 || $type == 7){
					$file = array(
									2 => 'bundle',
									3 => 'configurable',
									4 => 'downloadable', 
									6 => 'subscription', 
									7 => 'donation'
									);
					$str = $this->EE->load->view('product/sub_types/'.$file[$type],$this->vars,TRUE);
					$str .= "	<script type=\"text/javascript\">
									$(function(){
										$('.subtypes').show();
									})
								</script>";
				}
			}else{
				$a["bundle"] = $this->EE->load->view('product/sub_types/bundle',$this->vars,TRUE);
				$a["configurable"] = $this->EE->load->view('product/sub_types/configurable',$this->vars,TRUE);
				$a["downloadable"] = $this->EE->load->view('product/sub_types/downloadable',$this->vars,TRUE);
				$a["subscription"] = $this->EE->load->view('product/sub_types/subscription',$this->vars,TRUE);
				$a["donation"] = 	$this->EE->load->view('product/sub_types/donation',$this->vars,TRUE);
				$str = 	$a["bundle"].$a["configurable"].$a["downloadable"].$a["subscription"].$a["donation"];
				$str .= "	<script type=\"text/javascript\">
								$(function(){
									$('#type_id').bind('change',function(){
										var a = $(this).val();
										$('.subtypes').hide();
										$('#sub_type_'+a).show();
										
										// First we set reset all requireds to 1
											$('.sub_type_req').val(1);
										
										// Then we set the selected required to empty. 
											$('#sub_type_req_'+a).val('');
									});
								})
							</script>"; 
			}
			return $str;
		}
		
		/**
		 * Check for access to the admin sections 
		 */		
			function _check_admin_access($method){
	
				// Get the Group ID
					$group_id = $this->EE->session->userdata['group_id'];
	
				// Reset the index method to dashboard
					if($method == 'index'){
						$method = 'dashboard';
					}
				
				// Super Admins can go anywhere
					if($group_id == 1){
						// We are going to give the super admin access to 
						// all sections
						$f = get_class_methods('Brilliant_retail_mcp');
						foreach($f as $m){
							if(substr($m,0,1) != '_' && $m != 'index'){
								$this->group_access['brilliant_retail'][$m] = $m;
							}
						}
						return TRUE;
					}else{
						// all other memeber groups are validated
						if(!in_array($method,$this->method_ignore)){
							$this->group_access = $this->EE->access_model->get_admin_access($group_id);
							if(in_array($method,$this->group_access[$this->module])){
								return TRUE;
							}else{
								return FALSE;					
							}
						}else{
							$this->group_access = $this->EE->access_model->get_admin_access($group_id);
						} 
					}
				return TRUE;
			}
		
		/**
		 *
		 */
		public function _admin_permission_tree($group_id)
		{
			$group = $this->EE->access_model->get_admin_access($group_id);
			
			// Get all our methods
				$f = get_class_methods('Brilliant_retail_mcp');
			
				foreach($f as $m){
					if(substr($m,0,1) != '_' && $m != 'index'){
						$methods[] = $m;
					}
				}
				#sort($methods);
			
			// Define a list of safe methods
			$tree = '<ul id="permmision_tree">';
			foreach($methods as $m){
				if(!in_array($m,$this->method_ignore)){
					$a = explode('_',$m);
					// Setup the css so that we can indent
						$b = count($a); 
						if($b == 1){
							$class = 'permission_parent';
							$level = 1;
						}elseif($b == 2){
							$class = 'permission_child' ;
							$level = 2;
						}else{
							$class = 'permission_child-child' ;
							$level = 3;
						}
					// Set input to checked if permission 
						$chk = '';
						if(isset($group[$this->module])){
							if(in_array($m,$group[$this->module])){
								$chk = 'checked="checked"';
							}
						}
					$tree .= '	<li class="'.$class.' level_'.$level.'">
									<input type="checkbox" name="permissions[]" class="permmision_checkbox" value="'.$this->module.'|'.$m.'" '.$chk.' />&nbsp;'.lang('br_'.$m).'
								</li>';
				}
			}
			$tree .= '</ul>';
			return $tree;
		}
		
		function _create_admin_menu(){
			
			/* Create the primary menu */
				$this->vars["menu"]["dashboard"] 	= $this->base_url.AMP.'method=dashboard';
				if(isset($this->group_access["brilliant_retail"]["customer"])){
					$this->vars["menu"]["customer"]		= $this->base_url.AMP.'method=customer';
				}
				if(isset($this->group_access["brilliant_retail"]["order"])){
					$this->vars["menu"]["order"] 		= $this->base_url.AMP.'method=order';
				}
				if(isset($this->group_access["brilliant_retail"]["product"])){
					$this->vars["menu"]["product"]		= $this->base_url.AMP.'method=product';
				}
				if(isset($this->group_access["brilliant_retail"]["subscription"])){
					$this->vars["menu"]["subscription"] = $this->base_url.AMP.'method=subscription';
				}
				if(isset($this->group_access["brilliant_retail"]["promo"])){
					$this->vars["menu"]["promo"]	= $this->base_url.AMP.'method=promo';
				}
				if(isset($this->group_access["brilliant_retail"]["report"])){
					$this->vars["menu"]["report"]	= $this->base_url.AMP.'method=report';
				}
				if(isset($this->group_access["brilliant_retail"]["config"])){
					$this->vars["menu"]["config"]	= $this->base_url.AMP.'method=config';
			
					/* Create the submenu for configuration */
						
						$config_subs = array(	'config_email',
												'config_tax',
												'config_gateway',
												'config_shipping',
											 	'config_tax',
											 	'config_attribute',
											 	'config_attributeset',
											 	'config_category',
											 	'config_site', 
											 	'config_permission', 
											 	'config_feeds'
											 );
						foreach($config_subs as $sub){
							if(isset($this->group_access["brilliant_retail"][$sub])){
								$this->vars["submenu"][$sub]= $this->base_url.AMP.'method='.$sub;
							}
						}						
						ksort($this->vars["submenu"]);
				}
		}
		
		function _check_product_sku($data,$cnt=1)
		{
				if(trim($data["sku"]) == ''){
					$data["sku"] = str_replace(" ","-",strtolower($data["title"]));
				}
				$data["sku"] = strtolower(preg_replace('/[^A-Za-z0-9-_]/','',$data["sku"]));
				$count = $this->EE->product_model->_check_sku($data["sku"],$data["product_id"]);
				if($count == 0){
					return $data["sku"];
				}else{
					$data["sku"] = $data["sku"].'-'.$cnt;
					return $this->_check_product_sku($data,$cnt++);
				}
			}
			
		function _check_product_url($data,$cnt=1)
		{
			if(trim($data["url"]) == ''){
				$data["url"] = str_replace(" ","-",strtolower($data["title"]));
			}
			$data["url"] = strtolower(preg_replace('/[^A-Za-z0-9-_]/','',$data["url"]));
			$count = $this->EE->product_model->_check_url($data["url"],$data["product_id"]);
			if($count == 0){
				return $data["url"];
			}else{
				$data["url"] = $data["url"].'-'.$cnt;
				return $this->_check_product_url($data,$cnt++);
			}			
		}
			
		function _check_category_url($data,$cnt=1)
		{
			if(trim($data["url_title"]) == ''){
				$data["url_title"] = str_replace(" ","-",strtolower($data["title"]));
			}
			$data["url_title"] = strtolower(preg_replace('/[^A-Za-z0-9-_]/','',$data["url_title"]));
			if(!isset($data["category_id"])){
				$data["category_id"] = 0;
			}
			$count = $this->EE->product_model->_check_url($data["url_title"],$data["category_id"]);
			
			if($count == 0){
				return $data["url_title"];
			}else{
				$data["url_title"] = $data["url_title"].'-'.$cnt;
				return $this->_check_category_url($data,$cnt++);
			}
		}
			
		function _build_report_input($in)
		{
			$val = $this->EE->input->post($in[2]);
			$str = '';
			if($in[0] == 'date'){
				// Set the post variables
					$range = $this->EE->input->post($in[2]);
					$st = $this->EE->input->post($in[2].'_st');
					$end = $this->EE->input->post($in[2].'_end');
				
				// Do we hide the custom date fields?
					$class = ($range != 'custom') ? 'nodisplay' : '';
				
				$str .= '	<label>'.$in[1].'</label><br />
							<select name="'.$in[2].'" id="'.$in[2].'">';
							
					// Check for a selection in the post (i.e. $range) 
					
					$options = array('week','month','year','l_week','l_month','l_year','all','custom');
					
					foreach($options as $opt){
						$sel = ($opt == $range) ? 'selected' : '' ;
						$str .= '<option value="'.$opt.'" '.$sel.'>'.lang($opt).'</option>';
					}

				$str .= '	</select>
							<br />
							<div id="'.$in[2].'_custom" class="'.$class.'">
								<label>'.lang('br_start').'</label><input type="text" name="'.$in[2].'_st" class="datepicker" value="'.$st.'" />
								<label>'.lang('br_end').'</label><input type="text" name="'.$in[2].'_end" class="datepicker" value="'.$end.'" />
							</div>
						<script type="text/javascript">
							$(function(){
								$(\'#'.$in[2].'\').change(function(){
									var a = $(\'#'.$in[2].'_custom\');
									if($(this).val() == \'custom\'){
										a.show();
									}else{
										a.hide();
									}	
								});
							});
						</script>';
			}else{
				$str .= '<label>'.$in[1].'</label><input type="text" name="'.$in[2].'" value="'.$val.'" />';
			}
			return $str;
		}
			
		function _build_report_csv($data)
		{
			$csvoutput = '';
			$csvoutput .= join(",",$data["header"])."\n";
			foreach($data["results"] as $r){
				$csvoutput .= strip_tags(join(",",$r))."\n";
			}
			header ( "Content-Type: application/force-download" );
			header ( "Content-Type: application/octet-stream" );
			header ( "Content-Type: application/download" );
			header ( "Content-Type: text/csv" );
			header ( 'Content-Disposition: attachment; filename="Report_'.time().'.csv"');
			header ( "Content-Transfer-Encoding: binary" );
			header ( "Accept-Ranges: bytes" );
			header ( "Content-Length: ".strlen ( $csvoutput ) );
			echo $csvoutput;
			exit();
		}
		
		function _build_configurable_form($fields,$values = '')
		{
			$str = '<input id="config_attr" name="config_attr" type="hidden" value="'.join($fields,',').'" />
					<table id="configurable_form" class="subTable" cellpadding="0" cellpacing="0">
						<tr>
							<th colspan="2">
								'.lang('br_create_config_options').'</th>
						</tr>';
			$headings = '';
			foreach($fields as $f){
				$attr = $this->EE->product_model->get_attribute_by_id($f);
				$headings .= '<th>'.$attr["title"].'</th>';
				$opt = $this->_configurable_dropdown($attr["attribute_id"],$attr["title"],'',0,'',$attr["options"]);
				$str .= '<tr><td>'.$attr["title"].'</td><td>'.$opt.'</td></tr>';
			}		
						
			$str .=	'	<tr>
							<td>&nbsp;</td>
							<td>
								<div id="configurableCreate">'.lang('create').'</div></td>
						</tr>
						</table>
								<h4 style="margin-bottom:5px">'.lang('br_products').'</h4>
								<table id="config_selected" class="subTable" width="100%" cellpadding="0" cellspacing="0"><thead>'
						.$headings.
						'	<th>'.lang('br_sku').'</th>
							<th>'.lang('br_quantity').'</th>
							<th>'.lang('br_price_adjust').'</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>';
												
			$str .= '			</thead><tbody>'.$values.'</tbody></table>';
			return $str;
		}
}