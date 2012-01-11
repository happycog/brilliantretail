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

class Brilliant_retail_upd {

	public $version	= '1.0.4.5';
	
	function Brilliant_retail_upd()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}


	// --------------------------------------------------------------------

	/**
	 * Module Installer
	 *
	 * @access	public
	 * @return	bool
	 */	
	function install()
	{
		// Required for updating member fields
			$this->EE->load->dbforge();
		
			$sql[] = "INSERT INTO exp_modules 
					  (module_name, module_version, has_cp_backend) 
					  VALUES 
					  ('Brilliant_retail', '$this->version', 'y')";
		
		## ----------------------------
		##  Records of exp_actions
		## ----------------------------
		 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_img_update')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_add_atributes')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_search')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_download_update')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_configurable_create_options')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'product_img_update')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail_mcp', 'download_upload')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'cart_add')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'cart_remove')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'cart_clear')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'cart_update')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'checkout')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'checkout_shipping')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'checkout_total')"; 
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'promo_check_code')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_register')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_profile_update')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_pw_update')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'customer_download_file')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'gateway_ipn')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'process_ipn')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'pull_feed')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'retrieve_password')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'subscription_update')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'subscription_cancel')";
			$sql[] = "INSERT INTO exp_actions (class, method) VALUES ('Brilliant_retail', 'wishlist_process')";
			
		## ----------------------------
		##  Records of exp_member_fields
		## ----------------------------
		
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_fname', 'First Name', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '1')";
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_lname', 'Last Name', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '2')";
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_phone', 'Phone Number', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '3')";
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_lname', 'Billing Last Name', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '100')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_fname', 'Billing First Name', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '101')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_company', 'Billing Company', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '102')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_address1', 'Billing Address 1', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '103')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_address2', 'Billing Address 2', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '104')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_city', 'Billing City', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '105')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_state', 'Billing State', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '106')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_zip', 'Billing Zip', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '107')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_phone', 'Billing Phone', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '108')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_billing_country', 'Billing Country', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '109')";
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_fname', 'Shipping First Name', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '200')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_lname', 'Shipping Last Name', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '201')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_company', 'Shipping Company', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '202')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_address1', 'Shipping Address 1', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '203')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_address2', 'Shipping Address 2', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '204')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_city', 'Shipping City', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '205')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_state', 'Shipping Address State', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '206')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_zip', 'Shipping Address Zip', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '207')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_phone', 'Shipping Phone', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '208')"; 
			$field[] = "INSERT INTO exp_member_fields (m_field_name,m_field_label,m_field_description,m_field_type,m_field_list_items,m_field_ta_rows,m_field_maxl,m_field_width,m_field_search,m_field_required,m_field_public,m_field_reg,m_field_fmt,m_field_order) VALUES ('br_shipping_country', 'Shipping Country', '', 'text', '', '10', '100', '100%', 'n', 'n', 'n', 'y', 'none', '209')";	

			foreach ($field as $query)
			{
				$this->EE->db->query($query);
				$id = $this->EE->db->insert_id();
				$this->EE->dbforge->add_column('member_data', array('m_field_id_'.$id => array(
																									'type' => 'VARCHAR', 
												    	                                            'constraint' => '100'
												        	                                       	)));
			} 

		## ----------------------------
		##  Records of exp_global_variables
		## ----------------------------

			$sql[] = "	INSERT INTO 
							exp_global_variables 
							(site_id, variable_name, variable_data)
  						VALUES 
							('1', 'theme', '/themes/site_themes/brilliant_retail')";
			
			
		## ----------------------------
		##  Table structure for exp_br_admin_access
		## ----------------------------
		
		
			$sql[] = "DROP TABLE IF EXISTS exp_br_admin_access;";
			$sql[] = "CREATE TABLE exp_br_admin_access (
						  admin_access_id int(11) NOT NULL AUTO_INCREMENT,
						  site_id int(11) NOT NULL DEFAULT '1',
						  group_id int(11) NOT NULL DEFAULT '1',
						  class varchar(255) NOT NULL,
						  method varchar(255) NOT NULL,
						  created datetime NOT NULL,
						  PRIMARY KEY (admin_access_id)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


## ----------------------------
##  Table structure for exp_br_attribute
## ----------------------------


	$sql[] = "DROP TABLE IF EXISTS exp_br_attribute;";
	$sql[] = "CREATE TABLE exp_br_attribute (
				  attribute_id int(11) NOT NULL AUTO_INCREMENT,
				  site_id int(11) NOT NULL DEFAULT '1',
				  title varchar(50) NOT NULL,
				  code varchar(50) NOT NULL,
				  required int(11) NOT NULL DEFAULT '0',
				  fieldtype varchar(255) NOT NULL,
				  filterable int(11) NOT NULL DEFAULT '1',
				  default_text varchar(255) DEFAULT NULL,
				  options text,
				  PRIMARY KEY (attribute_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_attribute
## ----------------------------
	$sql[] = "	INSERT INTO 
					exp_br_attribute 
				VALUES 
					('22', '1', 'Notes', 'notes', '0', 'textarea', '1', '', ''), 
					('19', '1', 'Color', 'color', '0', 'dropdown', '1', '', 'Black\nBlue\nBrown\nGray\nGreen\nOrange\nRed\nWhite\nYellow'), 
					('27', '1', 'File', 'fle', '0', 'file', '1', '', ''), 
					('21', '1', 'Size', 'size', '0', 'dropdown', '1', '', 'X-Small\nSmall\nMedium\nLarge\nX-Large\nXX-Large');";

## ----------------------------
##  Table structure for exp_br_attribute_set
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_attribute_set;";
	$sql[] = "CREATE TABLE exp_br_attribute_set (
				  attribute_set_id int(11) NOT NULL AUTO_INCREMENT,
				  site_id int(11) NOT NULL DEFAULT '1',
				  title varchar(255) NOT NULL,
				  created datetime NOT NULL,
				  sort_order int(11) NOT NULL,
				  PRIMARY KEY (attribute_set_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_attribute_set
## ----------------------------
	$sql[] = "INSERT INTO exp_br_attribute_set VALUES 
				('1', '1', 'Default', '2010-06-22 13:15:18', '0'), 
				('6', '1', 'Attachment', '0000-00-00 00:00:00', '0');";

## ----------------------------
##  Table structure for exp_br_attribute_set_attribute
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_attribute_set_attribute;";
	$sql[] = "CREATE TABLE exp_br_attribute_set_attribute (
				  atrribute_set_attribte_id int(11) NOT NULL AUTO_INCREMENT,
				  attribute_id int(11) NOT NULL,
				  attribute_set_id int(11) NOT NULL,
				  sort_order int(11) NOT NULL,
				  PRIMARY KEY (atrribute_set_attribte_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_attribute_set_attribute
## ----------------------------
	$sql[] = "INSERT INTO exp_br_attribute_set_attribute VALUES ('80', '27', '6', '1'), 
				('79', '22', '6', '0'), 
				('72', '22', '1', '0');";

## ----------------------------
##  Table structure for exp_br_cart
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_cart;";
	$sql[] = "CREATE TABLE exp_br_cart (
			  cart_id int(11) NOT NULL AUTO_INCREMENT,
			  member_id int(11) NOT NULL DEFAULT '0',
			  session_id varchar(100) NOT NULL,
			  order_id int(11) NOT NULL,
			  content text NOT NULL,
			  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  updated timestamp NULL DEFAULT NULL,
			  status int(11) NOT NULL DEFAULT '0',
			  ip varchar(100) NOT NULL,
			  coupon_code varchar(255) NOT NULL,
			  token varchar(150) NULL,
			  PRIMARY KEY (cart_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_category
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_category;";
	$sql[] = "CREATE TABLE exp_br_category (
  category_id int(11) NOT NULL AUTO_INCREMENT,
  site_id int(11) NOT NULL DEFAULT '1',
  parent_id int(11) NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL,
  url_title varchar(100) NOT NULL,
  image varchar(100) NOT NULL,
  meta_title varchar(255) NOT NULL,
  meta_descr varchar(255) NOT NULL,
  enabled int(11) NOT NULL DEFAULT '1',
  meta_keyword varchar(255) NOT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  sort int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (category_id),
  template_path varchar(100) DEFAULT NULL, 
  detail text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_category
## ----------------------------
	$sql[] = "INSERT INTO exp_br_category VALUES ('57', '1', '92', 'Electronics', 'electronics', 'cat_banner_02.jpg', 'Electronics', 'High quality modern electronics for home and business.', '1', 'electronics, home, business', '2010-08-21 13:03:11', '1',NULL,NULL), 
				('58', '1', '92', 'Beauty', 'beauty', 'cat_banner_03.jpg', 'Beauty Products', 'Healthy beauty products for everyday use', '1', 'health, beauty', '2010-08-21 13:03:39', '4',NULL,NULL),
				('63', '1', '92', 'Clothing  »', 'clothing', 'cat_banner_012.jpg', 'Clothing & Apparel', 'Casual and fashionable clothing for men, women and children', '1', 'clothing, men, women, children', '2010-09-10 16:17:43', '5',NULL,NULL),
				('64', '1', '63', 'Men', 'clothing-men', 'cat_banner_011.jpg', 'Clothing for Men', 'clothing for men', '1', 'clothing, men', '2010-09-10 16:21:07', '3',NULL,NULL),
				('79', '1', '0', 'Services', 'services', 'cat_banner_05.jpg', 'My Service Title', 'My Description', '1', 'My Keywords', '2010-10-28 08:06:59', '0',NULL,NULL), 
				('69', '1', '63', 'Women', 'clothing-women', '', 'Clothing for women', 'Clothing for women', '1', 'clothing, women', '2010-10-21 14:28:13', '4',NULL,NULL),
				('75', '1', '0', 'Downloads', 'downloads', 'cat_banner_04.jpg', '', '', '1', '', '2010-10-28 07:56:32', '2',NULL,NULL),
				('76', '1', '75', 'Clothing  Guides »', 'guides-clothing', '', '', '', '1', '', '2010-10-28 07:57:18', '7',NULL,NULL),
				('78', '1', '76', 'Fall Fashion', 'fall-fashion', '', '', '', '1', '', '2010-10-28 07:59:51', '-1288277991',NULL,NULL),
				('92', '1', '0', 'Products', 'products', '', '', '', '1', '', '2010-10-29 15:29:22', '-1288391362',NULL,NULL);";

## ----------------------------
##  Table structure for exp_br_config
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_config;";
	$sql[] = "CREATE TABLE exp_br_config (
  config_id int(11) NOT NULL AUTO_INCREMENT,
  site_id int(11) NOT NULL DEFAULT '1',
  title varchar(255) NOT NULL,
  label varchar(100) NOT NULL,
  code varchar(100) NOT NULL,
  type varchar(100) NOT NULL,
  enabled int(11) NOT NULL DEFAULT '0',
  descr varchar(255) NOT NULL,
  version float(10,1) NOT NULL,
  sort int(11) NOT NULL DEFAULT '1',
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (config_id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_config
## ----------------------------
	$sql[] = "INSERT INTO exp_br_config (title,label,code,type,enabled,descr,version,sort) VALUES ('Status Codes', '', 'status', 'system', '1', '', '0.0', '1')";
	$sql[] = "INSERT INTO exp_br_config (title,label,code,type,enabled,descr,version,sort) VALUES ('Free Shipping', 'Free Shipping', 'free', 'shipping', '1', 'Free shipping with a minimum purchase amount', '1.0', '1')";
	$sql[] = "INSERT INTO exp_br_config (title,label,code,type,enabled,descr,version,sort) VALUES ('Mail In', 'Mail in Payment', 'mailin', 'gateway', '1', 'Allow users to mail in payment after the purchase.', '0.5', '1')";

## ----------------------------
##  Table structure for exp_br_config_data
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_config_data;";
	$sql[] = "CREATE TABLE exp_br_config_data (
				  config_data_id int(11) NOT NULL AUTO_INCREMENT,
				  config_id int(11) NOT NULL,
				  label varchar(100) NOT NULL,
				  code varchar(50) NOT NULL,
				  type varchar(30) NOT NULL,
				  value text,
				  options text,
				  descr text,
				  required int(11) NOT NULL DEFAULT '0',
				  sort int(11) NOT NULL,
				  PRIMARY KEY (config_data_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_config_data
## ----------------------------
	$sql[] = "INSERT INTO exp_br_config_data VALUES 
				('1', '1', 'New Order', '', '', '1', null, null, '0', '0'), 
				('2', '1', 'Pending', '', '', '2', null, null, '0', '0'), 
				('3', '1', 'Processing', '', '', '3', null, null, '0', '0'), 
				('4', '1', 'Shipping', '', '', '4', null, null, '0', '0'), 
				('5', '1', 'Complete', '', '', '5', null, null, '0', '0'), 
				('6', '2', 'Enabled', 'enabled', 'dropdown', '1', '1:Yes|0:No', null, '0', '0'), 
				('7', '2', 'Label', 'label', 'text', 'Free Shipping', null, null, '0', '0'), 
				('8', '2', 'Countries', 'country', 'multiselect', 'a:1:{i:0;s:2:\"US\";}', 'AF:Afghanistan|AX:Aland Islands|AL:Albania|DZ:Algeria|AS:American Samoa|AD:Andorra|AO:Angola|AI:Anguilla|AQ:Antarctica|AG:Antigua and Barbuda|AR:Argentina|AM:Armenia|AW:Aruba|AU:Australia|AT:Austria|AZ:Azerbaijan|BS:Bahamas|BH:Bahrain|BD:Bangladesh|BB:Barbados|BY:Belarus|BE:Belgium|BZ:Belize|BJ:Benin|BM:Bermuda|BT:Bhutan|BO:Bolivia|BA:Bosnia and Herzegovina|BW:Botswana|BV:Bouvet Island|BR:Brazil|IO:British Indian Ocean Territory|VG:British Virgin Islands|BN:Brunei|BG:Bulgaria|BF:Burkina Faso|BI:Burundi|KH:Cambodia|CM:Cameroon|CA:Canada|CV:Cape Verde|KY:Cayman Islands|CF:Central African Republic|TD:Chad|CL:Chile|CN:China|CX:Christmas Island|CC:Cocos [Keeling] Islands|CO:Colombia|KM:Comoros|CG:Congo - Brazzaville|CD:Congo - Kinshasa|CK:Cook Islands|CR:Costa Rica|CI:Cote d|HR:Croatia|CU:Cuba|CY:Cyprus|CZ:Czech Republic|DK:Denmark|DJ:Djibouti|DM:Dominica|DO:Dominican Republic|EC:Ecuador|EG:Egypt|SV:El Salvador|GQ:Equatorial Guinea|ER:Eritrea|EE:Estonia|ET:Ethiopia|FK:Falkland Islands|FO:Faroe Islands|FJ:Fiji|FI:Finland|FR:France|GF:French Guiana|PF:French Polynesia|TF:French Southern Territories|GA:Gabon|GM:Gambia|GE:Georgia|DE:Germany|GH:Ghana|GI:Gibraltar|GR:Greece|GL:Greenland|GD:Grenada|GP:Guadeloupe|GU:Guam|GT:Guatemala|GN:Guinea|GW:Guinea-Bissau|GY:Guyana|HT:Haiti|HM:Heard Island and McDonald Islands|HN:Honduras|HK:Hong Kong SAR China|HU:Hungary|IS:Iceland|IN:India|ID:Indonesia|IR:Iran|IQ:Iraq|IE:Ireland|IM:Isle of Man|IL:Israel|IT:Italy|JM:Jamaica|JP:Japan|JO:Jordan|KZ:Kazakhstan|KE:Kenya|KI:Kiribati|KW:Kuwait|KG:Kyrgyzstan|LA:Laos|LV:Latvia|LB:Lebanon|LS:Lesotho|LR:Liberia|LY:Libya|LI:Liechtenstein|LT:Lithuania|LU:Luxembourg|MO:Macau SAR China|MK:Macedonia|MG:Madagascar|MW:Malawi|MY:Malaysia|MV:Maldives|ML:Mali|MT:Malta|MH:Marshall Islands|MQ:Martinique|MR:Mauritania|MU:Mauritius|YT:Mayotte|MX:Mexico|FM:Micronesia|MD:Moldova|MC:Monaco|MN:Mongolia|MS:Montserrat|MA:Morocco|MZ:Mozambique|MM:Myanmar [Burma]|NA:Namibia|NR:Nauru|NP:Nepal|NL:Netherlands|AN:Netherlands Antilles|NC:New Caledonia|NZ:New Zealand|NI:Nicaragua|NE:Niger|NG:Nigeria|NU:Niue|NF:Norfolk Island|KP:North Korea|MP:Northern Mariana Islands|NO:Norway|OM:Oman|PK:Pakistan|PW:Palau|PS:Palestinian Territories|PA:Panama|PG:Papua New Guinea|PY:Paraguay|PE:Peru|PH:Philippines|PN:Pitcairn Islands|PL:Poland|PT:Portugal|PR:Puerto Rico|QA:Qatar|RE:Reunion|RO:Romania|RU:Russia|RW:Rwanda|SH:Saint Helena|KN:Saint Kitts and Nevis|LC:Saint Lucia|PM:Saint Pierre and Miquelon|VC:Saint Vincent and the Grenadines|WS:Samoa|SM:San Marino|ST:Sao Tome and Principe|SA:Saudi Arabia|SN:Senegal|SC:Seychelles|SL:Sierra Leone|SG:Singapore|SK:Slovakia|SI:Slovenia|SB:Solomon Islands|SO:Somalia|ZA:South Africa|GS:South Georgia and the South Sandwich Islands|KR:South Korea|ES:Spain|LK:Sri Lanka|SD:Sudan|SR:Suriname|SJ:Svalbard and Jan Mayen|SZ:Swaziland|SE:Sweden|CH:Switzerland|SY:Syria|TW:Taiwan|TJ:Tajikistan|TZ:Tanzania|TH:Thailand|TL:Timor-Leste|TG:Togo|TK:Tokelau|TO:Tonga|TT:Trinidad and Tobago|TN:Tunisia|TR:Turkey|TM:Turkmenistan|TC:Turks and Caicos Islands|TV:Tuvalu|UM:U.S. Minor Outlying Islands|VI:U.S. Virgin Islands|UG:Uganda|UA:Ukraine|AE:United Arab Emirates|GB:United Kingdom|US:United States|UY:Uruguay|UZ:Uzbekistan|VU:Vanuatu|VA:Vatican City|VE:Venezuela|VN:Vietnam|WF:Wallis and Futuna|EH:Western Sahara|YE:Yemen|ZM:Zambia|ZW:Zimbabwe', 'Select countries where free shipping is available. Control + click to add multiple countries', '0', '1'), 
				('9', '2', 'Amount', 'amount', 'text', '0', null, 'Minimum amount for free shipping', '0', '2'), 
				('10', '1', 'Canceled', '', '', '0', null, null, '0', '0')";


## ----------------------------
##  Table structure for exp_br_currencies
## ----------------------------
	$sql[] = " SET NAMES utf8;";
	$sql[] = " DROP TABLE IF EXISTS exp_br_currencies;";
	$sql[] = " CREATE TABLE exp_br_currencies (
					currency_id int(11) unsigned NOT NULL AUTO_INCREMENT,
					title varchar(50) NOT NULL,
					code varchar(5) NOT NULL,
					marker varchar(10) NOT NULL,
					value float(10,10) NOT NULL,
					updated varchar(50) NOT NULL,
					PRIMARY KEY (currency_id)
				) 
				ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;";

## ----------------------------
##  Records of exp_br_currencies
## ----------------------------
	$sql[] = "	INSERT INTO exp_br_currencies 
				VALUES 
					('1', 'US Dollar', 'USD', '$', '1.0000000000', ''), 
					('2', 'Australian Dollar', 'AUD', '$', '1.0000000000', ''), 
					('3', 'Canadian Dollar', 'CAD', '$', '1.0000000000', ''), 
					('4', 'Euro', 'EUR', 0xE282AC , '1.0000000000', ''), 
					('5', 'GB Pound', 'GBP', 0xc2a3, '1.0000000000', '')";

## ----------------------------
##  Table structure for exp_br_email
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_email;";
	$sql[] = "CREATE TABLE exp_br_email (
			  email_id int(11) NOT NULL AUTO_INCREMENT,
			  title varchar(255) NOT NULL,
			  version float(10,2) NOT NULL,
			  content blob NOT NULL,
			  site_id int(11) NOT NULL DEFAULT '1',
			  subject varchar(100) NOT NULL,
			  bcc_list varchar(255) DEFAULT NULL,
			  from_name varchar(100) NOT NULL,
			  from_email varchar(100) NOT NULL,
			  PRIMARY KEY (email_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_log
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_log;";
	$sql[] = "CREATE TABLE exp_br_log (
				  log_id int(11) NOT NULL AUTO_INCREMENT,
				  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  content text NOT NULL,
				  owner varchar(100) NOT NULL,
				  type varchar(100) NOT NULL,
				  PRIMARY KEY (log_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_order
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order;";
	$sql[] = "CREATE TABLE exp_br_order (
				  order_id int(11) NOT NULL AUTO_INCREMENT,
				  subscription_id int(11) NOT NULL default '0',
				  site_id int(11) NOT NULL default '1',
				  member_id int(11) NOT NULL default '0',
				  status_id int(11) NOT NULL default '1',
				  base decimal(10,2) NOT NULL,
				  tax decimal(10,2) NOT NULL,
				  shipping decimal(10,2) NOT NULL,
				  total decimal(10,2) NOT NULL,
				  discount decimal(10,2) NOT NULL,
				  cart_id varchar(100) NOT NULL,
				  merchant_id varchar(100) NOT NULL,
				  coupon_code varchar(50) NOT NULL,
				  created int(10) unsigned NOT NULL default '0',
				  PRIMARY KEY  (order_id),
				  KEY order_member_id (member_id) 
				) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_order_address
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order_address;";
	$sql[] = "CREATE TABLE exp_br_order_address (
			  order_address_id int(11) NOT NULL AUTO_INCREMENT,
			  order_id int(11) NOT NULL,
			  shipping_fname varchar(50) NOT NULL,
			  shipping_lname varchar(50) NOT NULL,
			  shipping_address1 varchar(100) NOT NULL,
			  shipping_address2 varchar(100) NOT NULL,
			  shipping_state varchar(50) NOT NULL,
			  shipping_zip varchar(50) NOT NULL,
			  shipping_city varchar(50) NOT NULL,
			  billing_fname varchar(50) NOT NULL,
			  billing_lname varchar(50) NOT NULL,
			  billing_address1 varchar(100) NOT NULL,
			  billing_address2 varchar(100) NOT NULL,
			  billing_city varchar(50) NOT NULL,
			  billing_state varchar(50) NOT NULL,
			  billing_zip varchar(50) NOT NULL,
			  billing_country varchar(5) NOT NULL,
			  shipping_country varchar(5) NOT NULL,
			  billing_company varchar(100) NOT NULL,
			  shipping_company varchar(100) NOT NULL,
			  billing_phone varchar(100) NOT NULL,
			  shipping_phone varchar(100) NOT NULL,
			  PRIMARY KEY (order_address_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_order_download
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order_download;";
	$sql[] = "CREATE TABLE exp_br_order_download (
					order_download_id int(11) NOT NULL AUTO_INCREMENT,
					downloadable_id int(11) NOT NULL,
					product_id int(11) NOT NULL,
					order_id int(11) NOT NULL,
					cnt int(11) NOT NULL DEFAULT '0',
					created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					download_limit int(11) NOT NULL DEFAULT '0',
					download_length int(11) NOT NULL,
					download_version varchar(50) DEFAULT NULL,
					license varchar(100) NOT NULL,
					PRIMARY KEY (order_download_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_order_item
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order_item;";
	$sql[] = "CREATE TABLE exp_br_order_item (
			  order_item_id int(11) NOT NULL AUTO_INCREMENT,
			  order_id int(11) NOT NULL,
			  product_id int(11) NOT NULL,
			  base decimal(10,2) NOT NULL DEFAULT '0.00',
			  price decimal(10,2) NOT NULL DEFAULT '0.00',
			  cost decimal(10,2) NOT NULL DEFAULT '0.00', 
			  discount decimal(10,2) NOT NULL DEFAULT '0.00',
			  quantity int(11) NOT NULL DEFAULT '1',
			  status int(11) NOT NULL DEFAULT '0',
			  configurable_id int(11) NOT NULL DEFAULT '0',
			  title varchar(100) NOT NULL,
			  taxable int(11) NOT NULL,
			  weight decimal(10,2) DEFAULT NULL,
			  shippable int(11) NOT NULL,
			  url varchar(100) NOT NULL,
			  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  sku varchar(100) NOT NULL,
			  options text,
			  PRIMARY KEY (order_item_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_order_note
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order_note;";
	$sql[] = "CREATE TABLE exp_br_order_note (
  order_note_id int(11) NOT NULL AUTO_INCREMENT,
  order_note text NOT NULL,
  filenm varchar(100) NOT NULL,
  created int(10) unsigned NOT NULL default '0',
  member_id int(11) NOT NULL,
  order_id int(11) NOT NULL,
  isprivate int(11) NOT NULL,
  PRIMARY KEY (order_note_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


## ----------------------------
##  Table structure for exp_br_order_options
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order_options;";
	$sql[] = "CREATE TABLE exp_br_order_options (
  order_item_option_id int(11) NOT NULL AUTO_INCREMENT,
  order_id int(11) NOT NULL,
  order_item_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  options text NOT NULL,
  PRIMARY KEY (order_item_option_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_order_payment
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order_payment;";
	$sql[] = "CREATE TABLE exp_br_order_payment (
  order_payment_id int(11) NOT NULL AUTO_INCREMENT,
  order_id int(11) NOT NULL,
  transaction_id varchar(100) NOT NULL,
  payment_type varchar(50) NOT NULL,
  amount decimal(10,2) NOT NULL default '0.00',
  details text,
  approval varchar(100) default NULL,
  created int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (order_payment_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


## ----------------------------
##  Table structure for exp_br_order_ship
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order_ship;";
	$sql[] = "	CREATE TABLE exp_br_order_ship (
					order_ship_id int(11) NOT NULL AUTO_INCREMENT,
					order_id int(11) NOT NULL,
					status int(11) NOT NULL,
					code varchar(50) NOT NULL,
					rate decimal(10,2) NOT NULL,
					cost decimal(10,2) NULL,
					label varchar(100) NOT NULL,
					method varchar(50) NOT NULL,
					tracknum varchar(255) NOT NULL,
					PRIMARY KEY (order_ship_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


## ----------------------------
##  Table structure for exp_br_order_subscription
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_order_subscription;";
	$sql[] = "	CREATE TABLE exp_br_order_subscription (
					order_subscription_id int(11) NOT NULL AUTO_INCREMENT,
					order_id int(11) NOT NULL,
					subscription_id int(11) NOT NULL,
					code varchar(100) DEFAULT NULL,
					cc_last_four varchar(50),
					cc_month varchar(50),
					cc_year varchar(50),
					status_id int(11) NOT NULL DEFAULT '0',
					product_id int(11) NOT NULL,
					group_id int(11) NOT NULL,
					cancel_group_id int(11) NOT NULL,
					length int(11) NOT NULL DEFAULT '30',
					period int(11) NOT NULL,
					start_dt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					end_dt timestamp NULL DEFAULT NULL,
					trial_price decimal(10,2) NOT NULL DEFAULT '0.00',
					trial_occur int(11) NOT NULL DEFAULT '0',
					renewal_price decimal(10,2) NOT NULL,
					next_renewal timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
					result varchar(255) DEFAULT NULL,
					message varchar(255) DEFAULT NULL,
					created timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  PRIMARY KEY (order_subscription_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_product
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product;";
	$sql[] = "CREATE TABLE exp_br_product (
			  product_id int(11) NOT NULL AUTO_INCREMENT,
			  site_id int(11) NOT NULL DEFAULT '1',
			  type_id int(11) NOT NULL,
			  title varchar(100) NOT NULL,
			  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  enabled int(11) NOT NULL DEFAULT '0',
			  taxable int(11) NOT NULL DEFAULT '0',
			  sku varchar(50) NOT NULL,
			  weight decimal(10,2) DEFAULT NULL,
			  shippable int(11) NOT NULL DEFAULT '0',
			  url varchar(100) DEFAULT NULL,
			  manage_inventory int(11) NOT NULL DEFAULT '1',
			  quantity int(11) NOT NULL DEFAULT '0',
			  price decimal(10,2) NOT NULL,
			  sale_price decimal(10,2) DEFAULT NULL,
			  sale_start datetime DEFAULT NULL,
			  sale_end datetime DEFAULT NULL,
			  meta_title varchar(100) DEFAULT NULL,
			  meta_descr varchar(255) DEFAULT NULL,
			  meta_keyword varchar(100) DEFAULT NULL,
			  detail text,
			  attribute_set_id int(11) DEFAULT NULL,
			  cost decimal(10,2) NOT NULL DEFAULT '0.00',
			  featured int(11) DEFAULT 0, 
			  PRIMARY KEY (product_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_product
## ----------------------------
	$sql[] = "INSERT INTO exp_br_product VALUES 
				('2519', '1', '1', '2020 Clothing Guide', '2010-10-28 07:55:46', '1', '0', '10107752', '0.00', '0', '2020-clothing-guide', '1', '9997', '15.00', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 'Know what is in style with the latest in fashion this year. ', null,'0.00',0),
				('2523', '1', '1', 'Shea Butter Hand Lotion', '2010-10-28 12:11:59', '1', '1', '10109875', '1.50', '1', 'shea-butter-hand-lotion', '1', '2000', '14.99', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Shea Butter Hand Lotion', 'Use Shea butter hand lotion to soften even the driest hands.', 'Shea butter, butter, hand, lotion, hand lotion, dry skin', 'Lotion with 40% Shea Butter, 10% honey and sweet walnut extract, blended with the light and sweet smells of lavender and peppermint extracts. This lotion with a nice texture, heals and protects dry or dehydrated skin by locking in moisture. Fresh vitamin E helps!\n', null,'0.00',0),
				('2516', '1', '1', 'Capture RX20 Digital SLR Camera', '2010-10-27 13:15:02', '1', '0', '10102887', '10.00', '1', 'capture-rx20-digital-slr-camera', '1', '459', '349.00', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 'Amazing photos can be taken with this camera', '1','0.00',0),
				('2517', '1', '2', 'Capture RX20 Digital SLR Camera + 2 Year Service Plan', '2010-10-27 13:17:17', '1', '0', '1011236', '10.00', '1', 'capture-rx20-digital-slr-camera-2-year-service-plan', '1', '481', '450.00', '400.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 'GREAT 20% PACKAGE DISCOUNT\nSave big when you buy the RX20 Camera and the 2 Year Service pack.', '1','0.00',0),
				('2524', '1', '1', 'Lemon Peppermint Perfume Oil', '2010-10-28 14:06:31', '1', '1', '10108293', '2.50', '1', 'lemon-peppermint-perfume-oil', '1', '85', '60.00', '40.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Lemon Pepper Mint Oil', 'Use Lemon Pepper Mint Oil to smell good!', 'lemon, peppermint, oil, perfume, scents', 'Peppermint can also be used as a ladies fragrance. You will smell of intoxicating peppermint wherever you go.  Although it smells strong it first, the peppermint becomes subdued for more of a citrusy smell. Lemon Peppermint oil provides satisfaction for everyone. ', '1','0.00',0),
				('2525', '1', '1', 'Oil Free Sunscreen SPF 30', '2010-10-28 14:40:15', '1', '1', '10101876', '1.00', '1', 'oil-free-sunscreen-spf-30', '1', '3000', '5.99', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Oil Free Sunscreen SPF 30', 'use Oil Free Sunscreen SPF 30 when you are on vacation!', 'Oil free, sunscreen, waterproof, sun, lotion, sun tan,', 'Scent free. Doesn\'t log pours. Protect yourself from powerful sun rays. UVA/UVB protection. It\'s Waterproof. It wont feel greasy. It also moisturizers your skin with added age defying nutrients. SPF 30', '1','0.00',0),
				('2526', '1', '1', 'Stunning Eyeliner ', '2010-10-28 15:00:14', '1', '1', '10105647', '0.50', '1', 'stunning-eyeliner', '1', '2500', '12.50', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Stunning Eyeliner', 'This stunning eyeliner wont smeer! ', 'maskera, eyeliner, makeup, easy, wont smear', '<p>\n	This black eyeliner wont smear or run. It&#39;s lightweight, portable, and comes only in black. Don&#39;t worry about fading, because the eyeliner is built with a compound that resists the normal wear and tear.</p>\n', '1','0.00',0),
				('2527', '1', '1', 'Silky Shampoo', '2010-10-28 15:13:41', '1', '1', '101019090', '1.50', '1', 'silky-shampoo', '1', '4210', '50.99', '34.99', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Silky Shampoo', 'Use this Silky Shampoo to make your hair shine', 'shampoo, hair, scalp, energy,', '<p>\n	This silky Shampoo is made for everyones hair. <em><strong>Use it every day to make your hairy shiny and smooth</strong></em>. It wil leave your hair soft and clean your head of any oil, dirt, or dry skyn. Use this shampoo to rejuvenate your hair!</p>\n', '1','0.00',0),
				('2507', '1', '3', 'Classic Cotton T-shirt', '2010-10-24 17:56:32', '1', '0', '10106453', '1.00', '1', 'classic-cotton-t-shirt', '1', '1000', '25.00', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 'A timeless classic, the cotton t-shirt. Wear it for work or play, it will never to out of style and of colors for the price. The shirts are made of 18-single open-ended soft cotton but rugged in construction. The price reflects  value with  a high  thread count. The cut is that of a basic t-shirt, boxy and full. ', null,'0.00',0),
				('2508', '1', '3', 'Classic Womens Cotton T-shirt', '2010-10-24 21:48:29', '1', '0', '10106720', '1.00', '1', 'classic-womens-cotton-t-shirt', '1', '1000', '20.00', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 'A timeless classic cotton t-shirt for adult and young women. A classic cut of 100% pure cotton.', null,'0.00',0),
				('2509', '1', '3', 'Long Sleeve Shirt', '2010-10-25 06:36:36', '1', '0', '10109781', '1.00', '1', 'long-sleeve-shirt', '1', '959', '30.00', '25.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Long Sleeve Cotton Shirt', 'Comfortable and strong preshrunk 100% ring-spun cotton with high-stitch-density fabric with shoulder-to-shoulder taping, double-needle cover-seamed neck and ribbed knit cuffs.', 'long sleeve, cotton shirt, ring spun, double needle, ribbed', '<p>\n	Comfortable and strong preshrunk 100% ring-spun cotton with high-stitch-density fabric with shoulder-to-shoulder taping, double-needle cover-seamed neck and ribbed knit cuffs.</p>\n', null,'0.00',0),
				('2510', '1', '1', 'Safari Easy-Stretch Mens Cargo Pants', '2010-10-25 07:02:22', '1', '0', '10103854', '5.00', '1', 'safari-twill-cargo-pants', '1', '1000', '75.00', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Safari Cargo Pants', 'Fitted safari style cargo pants', 'safari, cargo pants, fitted, jeans\n', 'Go on safari in your back yard. Amazing Easy-Stretch fabric makes for a strong construction and easy fit. Safari style makes these pants casually fashionable.\n', '1','0.00',0),
				('2511', '1', '3', 'Suit Blazer Jacket', '2010-10-25 08:05:52', '1', '0', '10103117', '5.00', '1', 'suit-blazer-jacker', '1', '972', '279.00', '200.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Suit Blazer Jacket', 'Men\'s sport coats suit blazer has modern young appeal with a cross weave of different color threads. Classic, clean front with decorative breast and patch pockets. Interior elements are lined in silver grey. ', 'suit, jacket, blazer, sports coat', '<p>\n	Men&#39;s sport coat blazer jacket has modern young appeal with a cross weave of different color threads. Classic, clean front with decorative breast and patch pockets. Interior elements are lined in silver grey. Cuffs with 2 overlapping buttons with two interior pockets. 50% cotton, 50% linen.</p>\n', '6','0.00',0),
				('2520', '1', '1', 'Smooth Face Cream', '2010-10-28 09:57:07', '1', '1', '10101357', '1.00', '1', 'smooth-face-cream', '1', '992', '29.99', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'creams, face cream, ', 'smooth face cream', 'face cream, smooth, wrinkles, face, cream', 'Smooth Face Cream removes the old skin cells from rough, dry skin; smoothes the appearance of fine lines and imperfections; and helps restore proper water balance. Dermatologist recommended. Advanced smoothing therapy.', '1','0.00',0),
				('2521', '1', '1', 'Banana Foot Cream', '2010-10-28 11:29:26', '1', '1', '10102468', '1.00', '1', 'banana-foot-cream', '1', '1000', '14.99', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Banana Foot Cream', 'Use Banana foot cream to relieve your dry skin!', 'Foot Cream, Banana, Cream, Foot, Dry skin', 'This scented and organic foot cream combines banana oil, vitamin E, and fruit glycerin to moisturize the driest feet. There\'s also a hint of natural lavender and spearmint oils to refresh tired feet.', '1','0.00',0),
				('2522', '1', '1', 'Age-Defying Body Lotion', '2010-10-28 11:52:40', '1', '0', '10104560', '1.00', '1', 'age-defying-body-lotion', '1', '997', '8.99', '6.99', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Age Defying Body Cream', 'age defying body cream', 'age defying, wrinkles, body cream, cream, face', 'Loaded with organic elements, it moisturizes your skin and keeps the moisture locked in your skin for over 10 hours. Its one of a kind texture make sure your skin is protected from any nutrient loss ', '1','0.00',0),
				('2515', '1', '5', '2-Year Electronics Standard Service Plan', '2010-10-27 13:09:33', '1', '0', '10109873', '0.00', '0', '2-year-electronics-standard-service-plan', '1', '956', '80.00', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '2-Year Electronics Standard Service Plan<br />\n<br />\nElectronic Services<br />\nYou can take your damaged electronics into a store location or mail to us for repair. To ship for repair your electronics item(s) must be under 25 Lbs. Domestic and International shipping rates will only be refunded on defective electronics.<br />\n<br />\nAccidental Damage<br />\nWater spills, dropping and other accidents happen. Now you don\'t have to worry about ruining your electronics product. It\'s covered. If we can\'t fix it we will send you a refurbished or new model.<br />\n<br />\nBattery Replacement<br />\nIf your battery stops working you will get a one-time, free battery replacement by stopping in a store or via mail. You will be responsible for shipping.<br />\n<br />\nPower Surges<br />\nStorms happen. We will repair or replace products if damaged by a power surge or fluctuation.<br />\n<br />\nMaintenance<br />\nElectronics products need to be maintained even if they are not being used all the time. Take your product into one of our stores and we will be happy to give it a good cleaning.<br />', '1','0.00',0),
				('2536', '1', '1', 'Wellington Leather Hobo Bag ', '2010-11-02 16:59:50', '1', '1', '10102299', '2.20', '1', 'wellington-leather-hobo-bag', '1', '199', '199.00', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Hobo Bag', '', 'bag, hobo', '<p>\n	Both stylish and useful, this cross-body Wellington style hog bag is constructed with fine stitching and a black and tan stripped leather lining. Platinum hardware adorns this beautiful bag.&nbsp;</p>\n', '1','0.00',0);";

## ----------------------------
##  Table structure for exp_br_product_attributes
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_attributes;";
	$sql[] = "CREATE TABLE exp_br_product_attributes (
				  pa_id int(11) NOT NULL AUTO_INCREMENT,
				  product_id int(11) NOT NULL,
				  attribute_id int(11) NOT NULL,
				  label varchar(30) NOT NULL,
				  descr text NOT NULL,
				  PRIMARY KEY (pa_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_product_attributes
## ----------------------------
	$sql[] = "INSERT INTO exp_br_product_attributes VALUES ('896', '2510', '22', '', 'Easy-Stretch Fabric<br />\nSize: Fits Medium to Large<br />\nWaist: Fits 32-34<br />\nInseam: Fits 30-34<br />\nColor: Khak<br />\n<ul>\n<li>Sits low on hips</li>\n<li>Big belt loops</li>\n<li>Hidden pockets</li>\n<li>Loose comfortable fit</li>\n<li> Hemmed straight leg</li>\n</ul>'), 
				('872', '2517', '22', '', '2 Year Service Plan Includes:<br />\n<ul>\n<li>Any electronics purchased online </li>\n<li>Accidental Damage</li>\n<li>Battery Replacment</li>\n<li>Power Surges</li>\n<li>Maintenance</li>\n</ul>\n<br />\n<h3>Camera Features:</h3>\n<ul>\n<li>20 Megapixel, video output </li>\n<li>Eye level, single-reflex viewfinder</li>\n<li>Rechargable Lithium-ion battery</li>\n<li>5\" LCD Screen</li>\n<li>5x Optical Zoom Lens</li>\n</ul>'), 
				('897', '2515', '22', '', '2 Year Service Plan Includes:<br />\n<ul>\n<li>Any electronics purchased online </li>\n<li>Accidental Damage</li>\n<li>Battery Replacment</li>\n<li>Power Surges</li>\n<li>Maintenance</li>\n</ul>'), 
				('864', '2516', '22', '', 'Camera Features:<br />\n<ul>\n<li>20 Megapixel, video output </li>\n<li>Eye level, single-reflex viewfinder</li>\n<li>Rechargable Lithium-ion battery</li>\n<li>5\" LCD Screen</li>\n<li>5x Optical Zoom Lens</li>\n</ul>\n'), 
				('884', '2520', '22', '', 'Item Weight: <br />\n3.6 ounces\n<br />\n<br />\nIngredients: <br />\nWater Purified, Cetyl Ricinoleate, Ceresin, Ammonium Glycolate, Glyceryl Stearate (and), PEG 50 Stearate, Sorbitan Stearate, Sorbitol, Methylparaben, Propylparaben, Magnesium Aluminum Silicate, Dimethicone, Gum, Trisodium FDTE <br />'), 
				('891', '2521', '22', '', 'Ingredients: glycerin, olea europaea (olive) fruit oil, avena sativa (oat) kernel protein, lanolin, cocos nucifera (coconut) oil, tocopherol, mentha piperita (peppermint) oil, rosmarinus officinalis (rosemary) leaf oil, fragrance<br />\n<br />\nHow to Use It:\nRub cream on foot and let it absorb. Optional: soak feet in hot water for 5-10 minutes and use a pumice stone on your heel. Massage Banana Foot Creme into your feet. After, you use the cream, put a sock on your foot and keep them on overnight to lock in the moisture. '), 
				('892', '2522', '22', '', 'Overview:<br />\nSize:  10.5 oz.<br />\n<br />\nPrevents Moisture Loss\n<br />\nWith vitamin E and Aloe\n<br />\nDermatologically tested'), 
				('880', '2524', '22', '', 'Ingredients:  oil<br />\n<br />\nOther Oils: Aloe Vera, Peppermint Leaves,<br />\n<br />\nFragrant Oil: Lemon, alderwood<br />\n<br />'), 
				('881', '2525', '22', '', '8 ounce bottle of sunscreen<br />\n<br />\nHypoallergenic, Doesn\'t contain Oil,  Won\'t Clog Pours<br />\n<br />\nProtection from Harmful Rays <br />\n<br />\nWaterproof!'), 
				('937', '2526', '22', '', 'Shipping Weight: <br />\n<br />\n0.1 LBS\nContainer Type: <br />\n<br />\nSmall Peg'), 
				('927', '2527', '22', '', 'Scent: <br />\n<br /> \nVanilla<br />\n<br />\nSize:\n34 mL\n\n'), 
				('934', '2511', '27', '', 'a:2:{s:5:\"title\";s:22:\"Suit Jacket Size Guide\";s:4:\"file\";s:0:\"\";}'), 
				('933', '2511', '22', '', 'Please download the PDF to view sizing quidelines'), 
				('939', '2536', '22', '', 'Specs:<br />\n<ul>\n<li>Model: WHB55003 </li>\n<li>Construction: Leather</li>\n<li>Lining: Stripped tan and black</li>\n<li>Hardware: Platinum</li>\n<li>Handles: Flat </li>\n<li>Access: Zipper</li>\n<li>Dimensions: 13\"Height x 15\"Width x 5\"Depth x 18\"Drop</li>\n</ul>\n');";

## ----------------------------
##  Table structure for exp_br_product_bundle
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_bundle;";
	$sql[] = "CREATE TABLE exp_br_product_bundle (
  bundle_id int(11) NOT NULL AUTO_INCREMENT,
  parent_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  PRIMARY KEY (bundle_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_product_bundle
## ----------------------------
	$sql[] = "INSERT INTO exp_br_product_bundle VALUES ('100', '2517', '2516'), 
				('99', '2517', '2515');";

## ----------------------------
##  Table structure for exp_br_product_category
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_category;";
	$sql[] = "CREATE TABLE exp_br_product_category (
  pc_id int(11) NOT NULL AUTO_INCREMENT,
  site_id int(11) DEFAULT '1'  NOT NULL, 
  category_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (pc_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_product_category
## ----------------------------
	$sql[] = "INSERT INTO exp_br_product_category VALUES ('1482','1', '64', '2507',0), 
				('1481','1', '63', '2507',0), 
				('1484','1', '69', '2508',0), 
				('1483','1', '63', '2508',0), 
				('1713','1', '64', '2511',0), 
				('1626','1', '64', '2510',0), 
				('1735','1', '64', '2509',0), 
				('1734','1', '63', '2509',0), 
				('1625','1', '63', '2510',0), 
				('1712','1', '63', '2511',0), 
				('1578','1', '78', '2519',0), 
				('1577','1', '76', '2519',0), 
				('1641','1', '57', '2515',0), 
				('1640','1', '80', '2515',0), 
				('1550','1', '70', '2516',0), 
				('1549','1', '57', '2516',0), 
				('1591','1', '70', '2517',0), 
				('1590','1', '57', '2517',0), 
				('1602','1', '58', '2524',0), 
				('1576','1', '75', '2519',0), 
				('1639','1', '79', '2515',0), 
				('1608','1', '58', '2520',0), 
				('1617','1', '58', '2521',0), 
				('1619','1', '58', '2522',0), 
				('1603','1', '58', '2525',0), 
				('1738','1', '58', '2526',0), 
				('1666','1', '58', '2523',0), 
				('1618','1', '86', '2521',0), 
				('1620','1', '86', '2522',0), 
				('1733','1', '92', '2509',0), 
				('1648','1', '92', '2510',0), 
				('1711','1', '92', '2511',0), 
				('1650','1', '92', '2515',0), 
				('1651','1', '92', '2516',0), 
				('1652','1', '92', '2517',0), 
				('1653','1', '92', '2520',0), 
				('1654','1', '92', '2521',0), 
				('1655','1', '92', '2522',0), 
				('1665','1', '92', '2523',0), 
				('1657','1', '92', '2524',0), 
				('1658','1', '92', '2525',0), 
				('1737','1', '92', '2526',0), 
				('1698','1', '58', '2527',0), 
				('1697','1', '92', '2527',0), 
				('1750','1', '71', '2536',0), 
				('1749','1', '69', '2536',0), 
				('1748','1', '63', '2536',0);";

## ----------------------------
##  Table structure for exp_br_product_configurable
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_configurable;";
	$sql[] = "CREATE TABLE exp_br_product_configurable (
			  configurable_id int(11) NOT NULL AUTO_INCREMENT,
			  sku varchar(50) NOT NULL,
			  qty int(10) NOT NULL,
			  adjust_type varchar(50) NOT NULL,
			  adjust decimal(10,2) NOT NULL,
			  attributes text NOT NULL,
			  product_id int(11) NOT NULL,
			  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (configurable_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_product_configurable
## ----------------------------
	$sql[] = "INSERT INTO exp_br_product_configurable VALUES ('192', '', '0', 'fixed', '0.00', 'a:1:{i:19;s:11:\"Bittersweet\";}', '2499', '2010-10-24 10:15:36'), 
				('193', '', '10', 'fixed', '0.00', 'a:1:{i:19;s:7:\"Apricot\";}', '2505', '2010-10-24 14:45:51'), 
				('194', '', '0', 'fixed', '0.00', 'a:2:{i:19;s:7:\"Apricot\";i:21;s:8:\"xx-small\";}', '2506', '2010-10-24 17:26:54'), 
				('195', '', '0', 'fixed', '0.00', 'a:2:{i:19;s:7:\"Apricot\";i:21;s:9:\"xxx-small\";}', '2506', '2010-10-24 17:26:54'), 
				('196', '10107645', '500', 'fixed', '0.00', 'a:2:{i:19;s:5:\"White\";i:21;s:6:\"Medium\";}', '2507', '2010-10-24 17:56:32'), 
				('197', '10107522', '300', 'fixed', '0.00', 'a:2:{i:19;s:5:\"White\";i:21;s:5:\"Large\";}', '2508', '2010-10-24 21:48:29'), 
				('198', '101023198', '300', 'fixed', '0.00', 'a:2:{i:19;s:5:\"White\";i:21;s:5:\"Small\";}', '2508', '2010-10-24 21:48:29'), 
				('199', '10102378', '300', 'fixed', '0.00', 'a:2:{i:19;s:5:\"White\";i:21;s:6:\"Medium\";}', '2508', '2010-10-24 21:48:29'), 
				('391', '10107642', '100', 'fixed', '150.00', 'a:2:{i:19;s:4:\"Gray\";i:21;s:5:\"Small\";}', '2511', '2010-11-01 23:55:15'), 
				('392', '10108874', '276', 'fixed', '0.00', 'a:2:{i:19;s:4:\"Gray\";i:21;s:6:\"Medium\";}', '2511', '2010-11-09 09:33:52'), 
				('393', '10102245', '98', 'fixed', '300.00', 'a:2:{i:19;s:4:\"Gray\";i:21;s:5:\"Large\";}', '2511', '2010-11-03 11:44:29'), 
				('394', '10102486', '298', 'fixed', '0.00', 'a:2:{i:19;s:3:\"Red\";i:21;s:5:\"Small\";}', '2509', '2010-11-05 17:34:31'), 
				('395', '10106534', '300', 'fixed', '0.00', 'a:2:{i:19;s:3:\"Red\";i:21;s:6:\"Medium\";}', '2509', '2010-11-05 17:34:31'), 
				('396', '10106112', '298', 'fixed', '0.00', 'a:2:{i:19;s:3:\"Red\";i:21;s:5:\"Large\";}', '2509', '2010-11-05 17:34:31'), 
				('397', '10107625', '199', 'fixed', '0.00', 'a:2:{i:19;s:3:\"Red\";i:21;s:8:\"X-Large \";}', '2509', '2010-11-05 17:34:31'), 
				('398', '10104523', '50', 'fixed', '0.00', 'a:2:{i:19;s:3:\"Red\";i:21;s:8:\"XX-Large\";}', '2509', '2010-11-05 17:34:31'), 
				('399', '10108739', '100', 'fixed', '0.00', 'a:2:{i:19;s:5:\"Black\";i:21;s:7:\"X-Small\";}', '2509', '2010-11-05 17:34:31'), 
				('400', '10105239', '300', 'fixed', '0.00', 'a:2:{i:19;s:5:\"Black\";i:21;s:5:\"Small\";}', '2509', '2010-11-05 17:34:31'), 
				('401', '10106428', '300', 'fixed', '0.00', 'a:2:{i:19;s:5:\"Black\";i:21;s:6:\"Medium\";}', '2509', '2010-11-05 17:34:31'), 
				('402', '10106745', '300', 'fixed', '0.00', 'a:2:{i:19;s:5:\"Black\";i:21;s:5:\"Large\";}', '2509', '2010-11-05 17:34:31'), 
				('403', '10105637', '300', 'fixed', '0.00', 'a:2:{i:19;s:5:\"Black\";i:21;s:8:\"X-Large \";}', '2509', '2010-11-05 17:34:31'), 
				('404', '10107632', '65', 'fixed', '0.00', 'a:2:{i:19;s:5:\"Black\";i:21;s:8:\"XX-Large\";}', '2509', '2010-11-05 17:34:31'), 
				('405', '10107537', '199', 'fixed', '0.00', 'a:2:{i:19;s:5:\"White\";i:21;s:5:\"Small\";}', '2509', '2010-11-05 17:34:31'), 
				('406', '10109952', '200', 'fixed', '0.00', 'a:2:{i:19;s:5:\"White\";i:21;s:6:\"Medium\";}', '2509', '2010-11-05 17:34:31'), 
				('407', '10103429', '200', 'fixed', '0.00', 'a:2:{i:19;s:5:\"White\";i:21;s:5:\"Large\";}', '2509', '2010-11-05 17:34:31');";

## ----------------------------
##  Table structure for exp_br_product_donation
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_donation";
	$sql[] = "CREATE TABLE exp_br_product_donation (
					donation_id int(11) NOT NULL AUTO_INCREMENT,
					product_id int(11) NOT NULL,
					allow_recurring int(11) NOT NULL DEFAULT '0',
					min_donation float NOT NULL DEFAULT '10',
					PRIMARY KEY (donation_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_product_download
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_download;";
	$sql[] = "	CREATE TABLE exp_br_product_download (
					downloadable_id int(11) NOT NULL AUTO_INCREMENT,
					product_id int(11) NOT NULL,
					title varchar(100) NOT NULL,
					filenm_orig varchar(255) NOT NULL,
					filenm varchar(100) NOT NULL,
					created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					download_limit int(10) NOT NULL,
					download_length int(10) NOT NULL,
					download_version varchar(50) DEFAULT NULL,
					PRIMARY KEY (downloadable_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_product_images
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_images;";
	$sql[] = "CREATE TABLE exp_br_product_images (
  image_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL,
  filenm varchar(100) NOT NULL,
  title varchar(100) DEFAULT NULL,
  large int(11) NOT NULL DEFAULT '0',
  thumb int(11) NOT NULL DEFAULT '0',
  exclude int(11) NOT NULL DEFAULT '0',
  sort int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (image_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_product_images
## ----------------------------
	$sql[] = "INSERT INTO exp_br_product_images VALUES ('2595', '2509', 'd5244a3bda940d3a1f5b6869ec9eff7a.png', 'long sleeve shirt detail 2', '0', '0', '0', '3'), 
				('2594', '2509', '2a7a1ac80d977ce2bc15929138056d78.png', 'long sleeve shirt detail 3', '0', '0', '0', '2'), 
				('2385', '2508', '6257e4e866018880a18179ddde662762.png', '', '1', '1', '0', '0'), 
				('2384', '2507', '2640292cc7e6d3b0dbb6e20ed470dda7.png', '', '1', '1', '0', '0'), 
				('2593', '2509', '962d8a97e97a731058310b7fe61ae851.png', 'long sleeve shirt detail 1', '0', '0', '0', '1'), 
				('2592', '2509', '139a95a728fd4cee11bfbd5bafc98a5a.png', 'long sleeve shirt', '1', '1', '0', '0'), 
				('2485', '2510', '954f3c231c3cb75a448cb780b30476ae.png', 'Safari cargo pants', '1', '1', '0', '0'), 
				('2585', '2511', '661d07200a6556a8047580b9719c336c.png', 'Suit Jacket - Detail 2', '0', '0', '0', '4'), 
				('2584', '2511', '02ab70d16d679eb73ae019cb0ff0134d.png', 'Suit Jacket - Gray', '0', '0', '0', '3'), 
				('2583', '2511', '17b1e209ec50e1fb8e6241a1aff0e8a3.png', 'Suit Jacket - Blue', '0', '1', '0', '2'), 
				('2582', '2511', '5485bc016204c6fd406d418b16153014.png', 'Suit Jacket Detail', '0', '0', '0', '1'), 
				('2460', '2520', '7fb875cd2b702264ba286af7c8dd09b8.png', 'Smooth Face Cream', '1', '1', '0', '0'), 
				('2452', '2524', '9bbe55de299f6a7ba96cbd03160fdec3.png', 'Lemon Pepper Mint Oil', '1', '1', '0', '0'), 
				('2423', '2516', '53d170e027d361408bf6c2d0bd60e561.png', '', '1', '1', '0', '0'), 
				('2439', '2517', '5d5d6cb830b8030674694834c89eea75.png', '', '1', '1', '0', '0'), 
				('2509', '2515', '28ef631f877a87e3b9e27ef399b15ecb.png', '', '1', '1', '0', '0'), 
				('2424', '2516', '571a1c7b4082754310b2edcbad7a1ee2.png', '', '0', '0', '0', '0'), 
				('2436', '2519', '619765c91e30b4d617b067137a669fdc.png', 'Clothing Guide', '1', '1', '0', '0'), 
				('2477', '2521', '56e2316cdc862e58bbdbe05d4f473dac.png', 'Banana Foot Cream', '1', '1', '0', '0'), 
				('2478', '2522', '22ffe4692cd1e21b8e7bf02e9a1d3e20.png', 'Age Defying Body Lotion', '1', '1', '0', '0'), 
				('2517', '2523', '773146bd3137830baaf0df2546ddca52.png', 'Shea Butter Hand Lotion', '1', '1', '0', '0'), 
				('2453', '2525', 'dba7c7f6bda94d74160d63cdac47c460.png', 'Oil Free Sunscreen SPF 30', '1', '1', '0', '0'), 
				('2596', '2526', 'f74d56a87889cf0be269d694501f7ac6.png', 'Stunning Eyeliner ', '1', '1', '0', '0'), 
				('2568', '2527', '444fe8987f70b56fe81f0098653ea07d.png', 'Silky Shampoo', '1', '1', '0', '0'), 
				('2581', '2511', 'c993a2a0a017648c0bfebb8c8fd77bbe.png', 'Suit Jacket - Black', '1', '0', '0', '0'), 
				('2602', '2536', '61e4044fc25bf124b9387acd1b987071.png', 'Wellington Hobo Bag ', '1', '1', '0', '0');";

## ----------------------------
##  Table structure exp_br_product_price
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_price;";
	$sql[] = "CREATE TABLE exp_br_product_price (
					price_id int(11) NOT NULL AUTO_INCREMENT,
					product_id int(11) NOT NULL,
					type_id int(11) NOT NULL,
					group_id int(11) NOT NULL,
					price decimal(10,2) NOT NULL,
					qty int(11) DEFAULT '1',
					end_dt datetime DEFAULT NULL,
					start_dt datetime DEFAULT NULL,
					sort_order int(11) NOT NULL,
					PRIMARY KEY (price_id),
					KEY product_price_index (product_id,group_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

	$sql[] = "INSERT INTO `exp_br_product_price` VALUES ('1', '2519', '1', '0', '15.00', '1', null, null, '0'), ('2', '2523', '1', '0', '14.99', '1', null, null, '0'), ('3', '2516', '1', '0', '349.00', '1', null, null, '0'), ('4', '2517', '1', '0', '450.00', '1', null, null, '0'), ('5', '2517', '2', '0', '400.00', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'), ('6', '2524', '1', '0', '60.00', '1', null, null, '0'), ('7', '2524', '2', '0', '40.00', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'), ('8', '2525', '1', '0', '5.99', '1', null, null, '0'), ('9', '2526', '1', '0', '12.50', '1', null, null, '0'), ('10', '2527', '1', '0', '50.99', '1', null, null, '0'), ('11', '2527', '2', '0', '34.99', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'), ('12', '2507', '1', '0', '25.00', '1', null, null, '0'), ('13', '2508', '1', '0', '20.00', '1', null, null, '0'), ('14', '2509', '1', '0', '30.00', '1', null, null, '0'), ('15', '2509', '2', '0', '25.00', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'), ('16', '2510', '1', '0', '75.00', '1', null, null, '0'), ('17', '2511', '1', '0', '279.00', '1', null, null, '0'), ('18', '2511', '2', '0', '200.00', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'), ('19', '2520', '1', '0', '29.99', '1', null, null, '0'), ('20', '2521', '1', '0', '14.99', '1', null, null, '0'), ('21', '2522', '1', '0', '8.99', '1', null, null, '0'), ('22', '2522', '2', '0', '6.99', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'), ('23', '2515', '1', '0', '80.00', '1', null, null, '0'), ('24', '2536', '1', '0', '199.00', '1', null, null, '0');";

## ----------------------------
##  Table structure for exp_br_product_options
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_options;";
	$sql[] = "CREATE TABLE exp_br_product_options (
  po_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL,
  options text,
  PRIMARY KEY (po_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_product_options
## ----------------------------
	$sql[] = "INSERT INTO exp_br_product_options VALUES ('460', '2521', 'N;'), 
				('461', '2522', 'N;'), 
				('483', '2523', 'N;'), 
				('451', '2525', 'N;'), 
				('504', '2511', 'a:1:{i:0;a:5:{s:5:\"title\";s:11:\"Jacket Size\";s:4:\"type\";s:8:\"dropdown\";s:8:\"required\";s:1:\"1\";s:4:\"sort\";i:1;s:4:\"opts\";a:7:{i:0;a:4:{s:5:\"title\";s:8:\"44 Short\";s:4:\"type\";s:5:\"fixed\";s:5:\"price\";s:1:\"0\";s:4:\"sort\";i:1;}i:1;a:4:{s:5:\"title\";s:7:\"46 Long\";s:4:\"type\";s:5:\"fixed\";s:5:\"price\";s:1:\"0\";s:4:\"sort\";i:2;}i:2;a:4:{s:5:\"title\";s:10:\"48 Regular\";s:4:\"type\";s:5:\"fixed\";s:5:\"price\";s:1:\"0\";s:4:\"sort\";i:3;}i:3;a:4:{s:5:\"title\";s:7:\"50 Long\";s:4:\"type\";s:5:\"fixed\";s:5:\"price\";s:1:\"0\";s:4:\"sort\";i:4;}i:4;a:4:{s:5:\"title\";s:10:\"50 Regular\";s:4:\"type\";s:5:\"fixed\";s:5:\"price\";s:1:\"0\";s:4:\"sort\";i:5;}i:5;a:4:{s:5:\"title\";s:7:\"52 Long\";s:4:\"type\";s:5:\"fixed\";s:5:\"price\";s:1:\"0\";s:4:\"sort\";i:6;}i:6;a:4:{s:5:\"title\";s:9:\"52 Reglar\";s:4:\"type\";s:5:\"fixed\";s:5:\"price\";s:1:\"0\";s:4:\"sort\";i:7;}}}}'), 
				('391', '2507', 'N;'), 
				('392', '2508', 'N;'), 
				('514', '2509', 'N;'), 
				('464', '2510', 'N;'), 
				('455', '2520', 'N;'), 
				('473', '2515', 'N;'), 
				('422', '2516', 'N;'), 
				('440', '2517', 'N;'), 
				('450', '2524', 'N;'), 
				('434', '2519', 'N;'), 
				('516', '2526', 'N;'), 
				('499', '2527', 'N;'), 
				('478', '2531', 'N;'), 
				('480', '2532', 'N;'), 
				('481', '2533', 'N;'), 
				('484', '2534', 'N;'), 
				('485', '2535', 'N;'), 
				('520', '2536', 'N;');";

## ----------------------------
##  Table structure for exp_br_product_related
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_addon;";
	$sql[] = "CREATE TABLE exp_br_product_addon (
  related_id int(11) NOT NULL AUTO_INCREMENT,
  parent_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  PRIMARY KEY (related_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_product_related
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_related;";
	$sql[] = "CREATE TABLE exp_br_product_related (
  related_id int(11) NOT NULL AUTO_INCREMENT,
  parent_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  PRIMARY KEY (related_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_product_related
## ----------------------------
	$sql[] = "INSERT INTO exp_br_product_related VALUES ('288', '2509', '2507'), 
				('287', '2509', '2508'), 
				('214', '2510', '2509'), 
				('213', '2510', '2507'), 
				('275', '2511', '2510'), 
				('274', '2511', '2509'), 
				('273', '2511', '2507'), 
				('272', '2511', '2508'), 
				('167', '2516', '2515'), 
				('226', '2515', '2517'), 
				('225', '2515', '2516'), 
				('204', '2521', '2520'), 
				('206', '2522', '2521'), 
				('205', '2522', '2520'), 
				('231', '2523', '2522'), 
				('184', '2525', '2523'), 
				('185', '2525', '2522'), 
				('188', '2520', '2521'), 
				('189', '2520', '2523'), 
				('190', '2520', '2522'), 
				('296', '2536', '2509'), 
				('295', '2536', '2508');";

## ----------------------------
##  Table structure for exp_br_product_subscription
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_product_subscription;";
	$sql[] = "CREATE TABLE exp_br_product_subscription (
  subscription_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL,
  length int(11) NOT NULL DEFAULT '30',
  period int(11) NOT NULL,
  group_id int(11) NOT NULL DEFAULT '0',
  trial_price decimal(10,2) DEFAULT NULL,
  trial_occur int(11) NOT NULL DEFAULT '1',
  cancel_group_id int(11) NOT NULL,
  PRIMARY KEY (subscription_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_promo
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_promo;";
	$sql[] = "CREATE TABLE exp_br_promo (
  promo_id int(11) NOT NULL AUTO_INCREMENT,
  site_id int(11) NOT NULL DEFAULT '1',
  title varchar(50) NOT NULL,
  code varchar(30) NOT NULL,
  start_dt timestamp NULL DEFAULT NULL,
  end_dt timestamp NULL DEFAULT NULL,
  code_type varchar(10) NOT NULL DEFAULT 'fixed',
  discount_type varchar(50) DEFAULT 'item',
  max_discount decimal(10,2) DEFAULT 0,
  amount decimal(10,2) NOT NULL DEFAULT '0.00',
  enabled int(11) NOT NULL DEFAULT '0',
  descr varchar(200) NOT NULL,
  category_list text,
  product_list text,
  min_subtotal decimal(10,2) NOT NULL DEFAULT '1.00',
  min_quantity int(11) NOT NULL DEFAULT '1',
  uses_per int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (promo_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_promo
## ----------------------------
	$sql[] = "INSERT INTO exp_br_promo VALUES ('21', '1', 'Default Save 20% All Products', '20', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'percent', 'item', 0, '20.00', '1', 'All customers save 20% on all products in all categories. ', '', '', '1.00', '1', '0'), 
				('22', '1', 'Default Save 10% on SLR Camera', '10', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'percent', 'item', 0, '10.00', '1', 'All customers save 10% on Capture RX20 Digital SLR', '[\"70\"]', '[\"2516\"]', '1.00', '1', '0'), 
				('23', '1', 'Jacket Discount', '25JACK', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'percent', 'item', 0, '25.00', '1', '25% off jackets', '[\"64\"]', '[\"2511\"]', '1.00', '1', '0');";

## ----------------------------
##  Table structure for exp_br_search
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_search;";
	$sql[] = "CREATE TABLE exp_br_search (
  search_id int(11) NOT NULL AUTO_INCREMENT,
  site_id int(11) NOT NULL DEFAULT '1',
  hash varchar(100) NOT NULL,
  search_term varchar(100) NOT NULL,
  result_count int(11) NOT NULL,
  member_id int(11) NOT NULL,
  ip varchar(100) NOT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (search_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_state
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_state;";
	$sql[] = "CREATE TABLE exp_br_state (
  state_id int(11) NOT NULL AUTO_INCREMENT,
  zone_id int(11) NOT NULL,
  title varchar(100) NOT NULL,
  code varchar(10) NOT NULL,
  PRIMARY KEY (state_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_state
## ----------------------------
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1,233, 'Abu Zaby', 'AZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2,233, '''Ajman', 'AJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (3,233, 'Al Fujayrah', 'FU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (4,233, 'Ash Shariqah', 'SH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (5,233, 'Dubayy', 'DU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (6,233, 'R''as al Khaymah', 'RK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (7,233, 'Umm al Qaywayn', 'UQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (8,2, 'Badakhshan', 'BDS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (9,2, 'Badghis', 'BDG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (10,2, 'Baghlan', 'BGL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (11,2, 'Balkh', 'BAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (12,2, 'Bamian', 'BAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (13,2, 'Farah', 'FRA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (14,2, 'Faryab', 'FYB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (15,2, 'Ghazni', 'GHA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (16,2, 'Ghowr', 'GHO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (17,2, 'Helmand', 'HEL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (18,2, 'Herat', 'HER')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (19,2, 'Jowzjan', 'JOW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (20,2, 'Kabul', 'KAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (21,2, 'Kandahar', 'KAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (22,2, 'Kapisa', 'KAP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (23,2, 'Konar', 'KNR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (24,2, 'Kondoz', 'KDZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (25,2, 'Laghman', 'LAG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (26,2, 'Lowgar', 'LOW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (27,2, 'Nangrahar', 'NAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (28,2, 'Nimruz', 'NIM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (29,2, 'Oruzgan', 'ORU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (30,2, 'Paktia', 'PIA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (31,2, 'Paktika', 'PKA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (32,2, 'Parwan', 'PAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (33,2, 'Samangan', 'SAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (34,2, 'Sar-e Pol', 'SAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (35,2, 'Takhar', 'TAK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (36,2, 'Wardak', 'WAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (37,2, 'Zabol', 'ZAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (38,3, 'Berat', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (39,3, 'Durrës', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (40,3, 'Elbasan', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (41,3, 'Fier', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (42,3, 'Gjirokastër', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (43,3, 'Korçë', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (44,3, 'Kurkës', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (45,3, 'Lezhë', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (46,3, 'Dibër', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (47,3, 'Shkodër', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (48,3, 'Tiranë', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (49,3, 'Vlorë', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (50,12, 'Erevan', 'ER')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (51,12, 'Aragacotn', 'AG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (52,12, 'Ararat', 'AR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (53,12, 'Armavir', 'AV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (54,12, 'Gegark''unik''', 'GR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (55,12, 'Kotayk''', 'KT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (56,12, 'Lory', 'LO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (57,12, 'Sirak', 'SH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (58,12, 'Syunik''', 'SU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (59,12, 'Tavus', 'TV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (60,12, 'Vayoc Jor', 'VD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (61,7, 'Bengo', 'BGO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (62,7, 'Benguela', 'BGU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (63,7, 'Bié', 'BIE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (64,7, 'Cabinda', 'CAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (65,7, 'Cuando-Cubango', 'CCU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (66,7, 'Cuanza Norte', 'CNO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (67,7, 'Cuanza Sul', 'CUS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (68,7, 'Cunene', 'CNN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (69,7, 'Huambo', 'HUA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (70,7, 'Huíla', 'HUI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (71,7, 'Luanda', 'LUA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (72,7, 'Lunda Norte', 'LNO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (73,7, 'Lunda Sul', 'LSU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (74,7, 'Malange', 'MAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (75,7, 'Moxico', 'MOX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (76,7, 'Namibe', 'NAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (77,7, 'Uíge', 'UIG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (78,7, 'Zaire', 'ZAI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (79,11, 'Salta', 'A')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (80,11, 'Buenos Aires Province', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (81,11, 'Distrito Federal', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (82,11, 'San Luis', 'D')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (83,11, 'Entre Rios', 'E')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (84,11, 'La Rioja', 'F')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (85,11, 'Santiago del Estero', 'G')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (86,11, 'Chaco', 'H')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (87,11, 'San Juan', 'J')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (88,11, 'Catamarca', 'K')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (89,11, 'La Pampa', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (90,11, 'Mendoza', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (91,11, 'Misiones', 'N')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (92,11, 'Formosa', 'P')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (93,11, 'Neuquen', 'Q')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (94,11, 'Rio Negro', 'R')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (95,11, 'Santa Fe', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (96,11, 'Tucuman', 'T')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (97,11, 'Chubut', 'U')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (98,11, 'Tierra del Fuego', 'V')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (99,11, 'Corrientes', 'W')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (100,11, 'Cordoba', 'X')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (101,11, 'Jujuy', 'Y')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (102,11, 'Santa Cruz', 'Z')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (103,15, 'Burgenland', '1')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (104,15, 'Karnten', '2')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (105,15, 'Niederosterreich', '3')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (106,15, 'Oberosterreich', '4')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (107,15, 'Salzburg', '5')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (108,15, 'Steiermark', '6')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (109,15, 'Tirol', '7')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (110,15, 'Vorarlberg', '8')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (111,15, 'Wien', '9')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (112,14, 'Australian Capital Territory', 'CT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (113,14, 'New South Wales', 'NS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (114,14, 'Northern Territory', 'NT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (115,14, 'Queensland', 'QL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (116,14, 'South Australia', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (117,14, 'Tasmania', 'TS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (118,14, 'Victoria', 'VI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (119,14, 'Western Australia', 'WA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (120,16, 'Naxçivan', 'MM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (121,16, 'Ali Bayramli', 'AB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (122,16, 'Bäki', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (123,16, 'Gäncä', 'GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (124,16, 'Länkäran', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (125,16, 'Mingäçevir', 'MI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (126,16, 'Naftalan', 'NA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (127,16, 'Säki', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (128,16, 'Sumqayit', 'SM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (129,16, 'Susa', 'SS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (130,16, 'Xankandi', 'XA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (131,16, 'Yevlax', 'YE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (132,16, 'Abseron', 'ABS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (133,16, 'Agcabädi', 'AGC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (134,16, 'Agdam', 'AGM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (135,16, 'Agdas', 'AGS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (136,16, 'Agstafa', 'AGA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (137,16, 'Agsu', 'AGU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (138,16, 'Astara', 'AST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (139,16, 'Babäk', 'BAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (140,16, 'Balakän', 'BAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (141,16, 'Bärdä', 'BAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (142,16, 'Beylägan', 'BEY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (143,16, 'Biläsuvar', 'BIL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (144,16, 'Cäbrayll', 'CAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (145,16, 'Cälilabad', 'CAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (146,16, 'Culfa', 'CUL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (147,16, 'Daskäsän', 'DAS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (148,16, 'Däväçi', 'DAV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (149,16, 'Fuzuli', 'FUZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (150,16, 'Gädäbäy', 'GAD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (151,16, 'Goranboy', 'GOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (152,16, 'Göyçay', 'GOY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (153,16, 'Haciqabul', 'HAC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (154,16, 'Imisli', 'IMI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (155,16, 'Ismayilli', 'ISM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (156,16, 'Kälbäcär', 'KAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (157,16, 'Kurdämir', 'KUR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (158,16, 'Laçin', 'LAC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (159,16, 'Länkäran', 'LAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (160,16, 'Lerik', 'LER')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (161,16, 'Masalli', 'MAS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (162,16, 'Neftcala', 'NEF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (163,16, 'Oguz', 'OGU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (164,16, 'Ordubad', 'ORD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (165,16, 'Qäbälä', 'QAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (166,16, 'Qax', 'QAX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (167,16, 'Qazax', 'QAZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (168,16, 'Qobustan', 'QOB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (169,16, 'Quba', 'QBA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (170,16, 'Qubadli', 'QBI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (171,16, 'Qusar', 'QUS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (172,16, 'Saatli', 'SAT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (173,16, 'Sabirabad', 'SAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (174,16, 'Sadarak', 'SAD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (175,16, 'Sahbuz', 'SAH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (176,16, 'Säki', 'SAK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (177,16, 'Salyan', 'SAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (178,16, 'Samaxi', 'SMI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (179,16, 'Sämkir', 'SKR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (180,16, 'Samux', 'SMX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (181,16, 'Särur', 'SAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (182,16, 'Siyäzän', 'SIY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (183,16, 'Susa', 'SUS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (184,16, 'Tartar', 'TAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (185,16, 'Tovuz', 'TOV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (186,16, 'Ucar', 'UCA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (187,16, 'Xacmaz', 'XAC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (188,16, 'Xanlar', 'XAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (189,16, 'Xizi', 'XIZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (190,16, 'Xocali', 'XCI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (191,16, 'Xocavand', 'XVD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (192,16, 'Yardimli', 'YAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (193,16, 'Yevlax', 'YEV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (194,16, 'Zängilan', 'ZAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (195,16, 'Zaqatala', 'ZAQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (196,16, 'Zärdab', 'ZAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (197,28, 'Federacija Bosna i Hercegovina', 'BIH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (198,28, 'Republika Srpska', 'SRP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (199,19, 'Bagerhat', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (200,19, 'Bandarban', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (201,19, 'Barguna', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (202,19, 'Barisal', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (203,19, 'Bhola', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (204,19, 'Bogra', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (205,19, 'Brahmanbaria', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (206,19, 'Chandpur', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (207,19, 'Chittagong', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (208,19, 'Chuadanga', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (209,19, 'Comilla', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (210,19, 'Cox''s Bazar', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (211,19, 'Dhaka', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (212,19, 'Dinajpur', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (213,19, 'Faridpur', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (214,19, 'Feni', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (215,19, 'Gaibandha', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (216,19, 'Gazipur', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (217,19, 'Gopalganj', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (218,19, 'Habiganj', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (219,19, 'Jaipurhat', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (220,19, 'Jamalpur', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (221,19, 'Jessore', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (222,19, 'Jhalakati', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (223,19, 'Jhenaidah', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (224,19, 'Khagrachari', '29')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (225,19, 'Khulna', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (226,19, 'Kishorganj', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (227,19, 'Kurigram', '28')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (228,19, 'Kushtia', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (229,19, 'Lakshmipur', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (230,19, 'Lalmonirhat', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (231,19, 'Madaripur', '36')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (232,19, 'Magura', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (233,19, 'Manikganj', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (234,19, 'Meherpur', '39')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (235,19, 'Moulvibazar', '38')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (236,19, 'Munshiganj', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (237,19, 'Mymensingh', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (238,19, 'Naogaon', '48')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (239,19, 'Narail', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (240,19, 'Narayanganj', '40')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (241,19, 'Narsingdi', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (242,19, 'Natore', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (243,19, 'Nawabganj', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (244,19, 'Netrakona', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (245,19, 'Nilphamari', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (246,19, 'Noakhali', '47')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (247,19, 'Pabna', '49')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (248,19, 'Panchagarh', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (249,19, 'Patuakhali', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (250,19, 'Pirojpur', '50')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (251,19, 'Rajbari', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (252,19, 'Rajshahi', '54')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (253,19, 'Rangamati', '56')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (254,19, 'Rangpur', '55')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (255,19, 'Satkhira', '58')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (256,19, 'Shariatpur', '62')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (257,19, 'Sherpur', '57')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (258,19, 'SirajOanj', '59')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (259,19, 'SunamOanj', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (260,19, 'Sylhet', '60')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (261,19, 'Tangail', '63')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (262,19, 'Thakurgaon', '64')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (263,22, 'Antwerpen', 'VAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (264,22, 'Vlaams Brabant', 'VBR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (265,22, 'Limburg', 'BE-VLI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (266,22, 'Oost-Vlaanderen', 'VOV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (267,22, 'West-Vlaanderen', 'VWV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (268,22, 'Brabant Wallon', 'WBR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (269,22, 'Hainaut', 'WHT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (270,22, 'Liège', 'WLG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (271,22, 'Luxembourg', 'WLX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (272,22, 'Namur', 'WNA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (273,35, 'Blagoevgrad', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (274,35, 'Burgas', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (275,35, 'Varna', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (276,35, 'Veliko Turnovo', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (277,35, 'Vidin', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (278,35, 'Vratsa', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (279,35, 'Gabrovo', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (280,35, 'Dobrich', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (281,35, 'Kurdzhali', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (282,35, 'Kyustendil', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (283,35, 'Lovech', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (284,35, 'Montana', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (285,35, 'Pazardzhik', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (286,35, 'Pernik', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (287,35, 'Pleven', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (288,35, 'Plovdiv', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (289,35, 'Razgrad', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (290,35, 'Ru', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (291,35, 'Silistra', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (292,35, 'Sliven', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (293,35, 'Smolyan', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (294,35, 'Sofia', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (295,35, 'Sofia Region', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (296,35, 'Stara Zagora', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (297,35, 'Turgovishte', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (298,35, 'Khaskovo', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (299,35, 'Shumen', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (300,35, 'Yambol', '28')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (301,18, 'Al Hadd', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (302,18, 'Al Manamah', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (303,18, 'Al Mintaqah al Gharbiyah', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (304,18, 'Al Mintagah al Wustá', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (305,18, 'Al Mintaqah ash Shamaliyah', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (306,18, 'Al Muharraq', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (307,18, 'Ar Rifa', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (308,18, 'Jidd Hafs', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (309,18, 'Madluat Jamad', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (310,18, 'Madluat Isã', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (311,18, 'Mintaqat Juzur tawar', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (312,18, 'Sitrah', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (313,37, 'Bubanza', 'BB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (314,37, 'Bujumbura', 'BJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (315,37, 'Bururi', 'BR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (316,37, 'Cankuzo', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (317,37, 'Cibitoke', 'CI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (318,37, 'Gitega', 'GI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (319,37, 'Karuzi', 'KR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (320,37, 'Kayanza', 'KY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (321,37, 'Kirundo', 'KI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (322,37, 'Makamba', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (323,37, 'Muramvya', 'MU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (324,37, 'Muyinga', 'MY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (325,37, 'Mwaro', 'MW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (326,37, 'Ngozi', 'NG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (327,37, 'Rutana', 'RT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (328,37, 'Ruyigi', 'RY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (329,24, 'Alibori', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (330,24, 'Atakora', 'AK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (331,24, 'Atlantique', 'AQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (332,24, 'Borgou', 'BO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (333,24, 'Collines', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (334,24, 'Donga', 'DO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (335,24, 'Kouffo', 'KO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (336,24, 'Littoral', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (337,24, 'Mono', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (338,24, 'Ouémé', 'OU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (339,24, 'Plateau', 'PL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (340,24, 'Zou', 'ZO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (341,34, 'Belait', 'BE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (342,34, 'Brunei-Muara', 'BM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (343,34, 'Temburong', 'TE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (344,34, 'Tutong', 'TU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (345,27, 'Cochabamba', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (346,27, 'Chuquisaca', 'H')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (347,27, 'El Beni', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (348,27, 'La Paz', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (349,27, 'Oruro', 'O')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (350,27, 'Pando', 'N')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (351,27, 'Potosi', 'P')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (352,27, 'Santa Cruz', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (353,27, 'Tarija', 'T')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (354,31, 'Acre', 'AC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (355,31, 'Alagoas', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (356,31, 'Amazonas', 'AM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (357,31, 'Amapa', 'AP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (358,31, 'Bahia', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (359,31, 'Ceara', 'CE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (360,31, 'Distrito Federal', 'DF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (361,31, 'Espirito Santo', 'ES')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (362,31, 'Goias', 'GO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (363,31, 'Maranhao', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (364,31, 'Minas Gerais', 'MG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (365,31, 'Mato Grosso do Sul', 'MS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (366,31, 'Mato Grosso', 'MT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (367,31, 'Para', 'PA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (368,31, 'Paraiba', 'PB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (369,31, 'Pernambuco', 'PE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (370,31, 'Piaui', 'PI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (371,31, 'Parana', 'PR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (372,31, 'Rio de Janeiro', 'RJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (373,31, 'Rio Grande do Norte', 'RN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (374,31, 'Rondonia', 'RO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (375,31, 'Roraima', 'RR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (376,31, 'Rio Grande do Sul', 'RS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (377,31, 'Santa Catarina', 'SC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (378,31, 'Sergipe', 'BR-SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (379,31, 'Sao Paulo', 'SP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (380,31, 'Tocantins', 'TO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (381,17, 'Acklins and Crooked Islands', 'AC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (382,17, 'Bimini', 'BI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (383,17, 'Cat Island', 'CI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (384,17, 'Exuma', 'EX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (385,17, 'Freeport', 'FP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (386,17, 'Fresh Creek', 'FC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (387,17, 'Governor''s Harbour', 'GH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (388,17, 'Green Turtle Cay', 'GT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (389,17, 'Harbour Island', 'HI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (390,17, 'High Rock', 'HR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (391,17, 'Inagua', 'IN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (392,17, 'Kemps Bay', 'KB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (393,17, 'Long Island', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (394,17, 'Marsh Harbour', 'MH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (395,17, 'Mayaguana', 'MG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (396,17, 'New Providence', 'NP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (397,17, 'Nicholls Town and Berry Islands', 'NB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (398,17, 'Ragged Island', 'RI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (399,17, 'Rock Sound', 'RS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (400,17, 'Sandy Point', 'SP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (401,17, 'San Salvador and Rum Cay', 'SR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (402,26, 'Bumthang', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (403,26, 'Chhukha', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (404,26, 'Dagana', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (405,26, 'Gasa', 'GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (406,26, 'Ha', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (407,26, 'Lhuentse', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (408,26, 'Monggar', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (409,26, 'Paro', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (410,26, 'Pemagatshel', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (411,26, 'Punakha', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (412,26, 'Samdrup Jongkha', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (413,26, 'Samtee', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (414,26, 'Sarpang', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (415,26, 'Thimphu', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (416,26, 'Trashigang', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (417,26, 'Trashi Yangtse', 'TY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (418,26, 'Trongsa', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (419,26, 'Tsirang', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (420,26, 'Wangdue Phodrang', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (421,26, 'Zhemgang', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (422,29, 'Central', 'CE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (423,29, 'Chobe', 'CH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (424,29, 'Ghanzi', 'GH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (425,29, 'Kgalagadi', 'KG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (426,29, 'Kgatleng', 'KL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (427,29, 'Kweneng', 'KW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (428,29, 'Ngamiland', 'NG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (429,29, 'North-East', 'NE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (430,29, 'South-East', 'SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (431,29, 'Southern', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (432,21, 'Brest voblast', 'BR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (433,21, 'Homyel voblast', 'HO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (434,21, 'Hrodna voblast', 'HR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (435,21, 'Mahilyow voblast', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (436,21, 'Minsk voblast', 'MI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (437,21, 'Vitsebsk voblast', 'VI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (438,23, 'Belize', 'BBZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (439,23, 'Cayo', 'CY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (440,23, 'Corozal', 'CZL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (441,23, 'Orange Walk', 'OW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (442,23, 'Stann Creek', 'SC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (443,23, 'Toledo', 'TOL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (444,40, 'Alberta', 'AB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (445,40, 'British Columbia', 'BC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (446,40, 'Manitoba', 'MB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (447,40, 'New Brunswick', 'NB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (448,40, 'Newfoundland and Labrador', 'NL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (449,40, 'Nova Scotia', 'NS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (450,40, 'Northwest Territories', 'NT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (451,40, 'Nunavut', 'NU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (452,40, 'Ontario', 'ON')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (453,40, 'Prince Edward Island', 'PE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (454,40, 'Quebec', 'QC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (455,40, 'Saskatchewan', 'SK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (456,40, 'Yukon Territory', 'YT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (457,52, 'Bas-Congo', 'BC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (458,52, 'Bandundu', 'BN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (459,52, 'Equateur', 'EQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (460,52, 'Katanga', 'KA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (461,52, 'Kasai-Oriental', 'KE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (462,52, 'Kinshasa', 'KN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (463,52, 'Kasai-Occidental', 'KW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (464,52, 'Maniema', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (465,52, 'Nord-Kivu', 'NK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (466,52, 'Orientale', 'OR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (467,52, 'Sud-Kivu', 'SK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (468,43, 'Bangui', 'BGF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (469,43, 'Bamingui-Bangoran', 'BB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (470,43, 'Baase-Kotto', 'BK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (471,43, 'Haute-Kotto', 'HK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (472,43, 'Haut-Mbomou', 'HM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (473,43, 'Kémo', 'KG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (474,43, 'Lobaye', 'LB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (475,43, 'Mambéré-Kadéï', 'HS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (476,43, 'Mbomou', 'MB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (477,43, 'Nana-Grébizi', 'KB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (478,43, 'Nana-Mambéré', 'NM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (479,43, 'Ombella-Mpoko', 'MP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (480,43, 'Ouaka', 'UK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (481,43, 'Ouham', 'AC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (482,43, 'Ouham-Pendé', 'OP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (483,43, 'Sangha-Mbaéré', 'SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (484,43, 'Vakaga', 'VR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (485,51, 'Brazzaville', 'BZV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (486,51, 'Bouenza', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (487,51, 'Cuvette', '8')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (488,51, 'Cuvette-Ouest', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (489,51, 'Kouilou', '5')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (490,51, 'Lékoumou', '2')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (491,51, 'Likouala', '7')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (492,51, 'Niari', '9')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (493,51, 'Plateaux', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (494,51, 'Pool', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (495,51, 'Sangha', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (496,212, 'Zurich', 'ZH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (497,212, 'Bern', 'BE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (498,212, 'Lucerne', 'LU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (499,212, 'Uri', 'UR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (500,212, 'Schwyz', 'SZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (501,212, 'Obwalden', 'OW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (502,212, 'Nidwalden', 'NW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (503,212, 'Glarus', 'GL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (504,212, 'Zug', 'ZG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (505,212, 'Fribourg', 'FR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (506,212, 'Solothurn', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (507,212, 'Basel-Stadt', 'BS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (508,212, 'Basel-Landschaft', 'BL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (509,212, 'Schaffhausen', 'SH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (510,212, 'appenzell Innerrhoden', 'AR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (511,212, 'St. Gallen', 'SG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (512,212, 'Graubünden', 'GR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (513,212, 'Aargau', 'AG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (514,212, 'Thurgau', 'TG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (515,212, 'Ticino', 'TI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (516,212, 'Vaud', 'VD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (517,212, 'Valais', 'VS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (518,212, 'Neuchatel', 'NE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (519,212, 'Geneva', 'GE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (520,212, 'Jura', 'JU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (521,45, 'Aisén del General Carlos Ibáñez del Campo', 'AI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (522,45, 'Antofagasta', 'AN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (523,45, 'Araucanía', 'AR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (524,45, 'Atacama', 'AT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (525,45, 'Bío-Bío', 'BI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (526,45, 'Coquimbo', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (527,45, 'Libertador General Bernardo O''Higgins', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (528,45, 'Los Lagos', 'LL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (529,45, 'Magallanes', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (530,45, 'Maule', 'ML')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (531,45, 'Region Metropolitana de Santiago', 'RM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (532,45, 'Tarapacá', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (533,45, 'Valparaíso', 'VS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (534,39, 'Adamaoua', 'AD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (535,39, 'Centre', 'CE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (536,39, 'East', 'ES')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (537,39, 'Far North', 'EN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (538,39, 'Littoral', 'LT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (539,39, 'North', 'NO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (540,39, 'North-West', 'NW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (541,39, 'South', 'SW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (542,39, 'South-Weat', 'SW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (543,39, 'West', 'OU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (544,46, 'Anhui', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (545,46, 'Fujian', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (546,46, 'Gansu', '62')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (547,46, 'Guangdong', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (548,46, 'Guizhou', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (549,46, 'Hainan', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (550,46, 'Hebei', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (551,46, 'Heilongjiang', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (552,46, 'Henan', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (553,46, 'Hubei', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (554,46, 'Hunan', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (555,46, 'Jiangsu', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (556,46, 'Jiangxi', '36')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (557,46, 'Jilin', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (558,46, 'Liaoning', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (559,46, 'Qinghai', '63')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (560,46, 'Shaanxi', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (561,46, 'Shandong', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (562,46, 'Shanxi', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (563,46, 'Sichuan', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (564,46, 'Yunnan', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (565,46, 'Zhejiang', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (566,46, 'Guangxi', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (567,46, 'Nei Mongol', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (568,46, 'Ningxia', '64')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (569,46, 'Xinjiang', '65')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (570,46, 'Xizang', '54')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (571,46, 'Guangxi', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (572,46, 'Hong Kong', '91')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (573,46, 'Macau', '92')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (574,49, 'Distrito Capltal de Santa Fe de Bogotã', 'DC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (575,49, 'Amazonea', 'AMA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (576,49, 'Antioquia', 'ANT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (577,49, 'Arauca', 'ARA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (578,49, 'Atlántico', 'ATL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (579,49, 'Bolívar', 'BOL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (580,49, 'Boyacá', 'BOY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (581,49, 'Caldea', 'CAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (582,49, 'Caquetá', 'CAQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (583,49, 'Casanare', 'CAS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (584,49, 'Cauca', 'CAU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (585,49, 'Cesar', 'CES')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (586,49, 'Córdoba', 'COR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (587,49, 'Cundinamarca', 'CUN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (588,49, 'Chocó', 'CHO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (589,49, 'Guainía', 'GUA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (590,49, 'Guaviare', 'GUV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (591,49, 'Huila', 'HUI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (592,49, 'La Guajira', 'LAG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (593,49, 'Magdalena', 'MAG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (594,49, 'Meta', 'MET')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (595,49, 'Nariño', 'NAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (596,49, 'Norte de Santander', 'NSA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (597,49, 'Putumayo', 'PUT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (598,49, 'Quindío', 'QUI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (599,49, 'Risaralda', 'RIS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (600,49, 'San Andrés', 'SAP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (601,49, 'Santander', 'SAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (602,49, 'Sucre', 'SUC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (603,49, 'Tolima', 'TOL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (604,49, 'Valle del Cauca', 'VAC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (605,49, 'Vaupés', 'VAU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (606,49, 'Vichada', 'VID')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (607,54, 'Alajuela', 'A')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (608,54, 'Cartago', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (609,54, 'Guanacaste', 'G')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (610,54, 'Heredia', 'H')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (611,54, 'Limón', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (612,54, 'Puntarenas', 'P')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (613,54, 'San José', 'SJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (614,56, 'Camagüey', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (615,56, 'Ciego de `vila', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (616,56, 'Cienfuegos', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (617,56, 'Ciudad de La Habana', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (618,56, 'Granma', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (619,56, 'Guantánamo', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (620,56, 'Holquin', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (621,56, 'La Habana', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (622,56, 'Las Tunas', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (623,56, 'Matanzas', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (624,56, 'Pinar del Río', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (625,56, 'Sancti Spiritus', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (626,56, 'Santiago de Cuba', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (627,56, 'Villa Clara', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (628,56, 'Isla de la Juventud', '99')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (629,41, 'Ilhas de Barlavento', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (630,41, 'Ilhas de Sotaventoa BV Boa Vista', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (631,41, 'Brava', 'BR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (632,41, 'Calheta de São Miguel', 'CS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (633,41, 'Maio', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (634,41, 'Mosteiros', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (635,41, 'Paúl', 'PA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (636,41, 'Porto Novo', 'PN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (637,41, 'Praia', 'PR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (638,41, 'Ribeira Grande', 'RG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (639,41, 'Sal', 'SL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (640,41, 'Santa Catarina', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (641,41, 'Santa Cruz', 'CR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (642,41, 'São Domingos', 'SD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (643,41, 'São Filipe', 'SF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (644,41, 'São Nicolau', 'SN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (645,41, 'São Vicente', 'SV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (646,41, 'Tarrafal', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (647,57, 'Ammochostos Magusa', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (648,57, 'Keryneia', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (649,57, 'Larnaka', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (650,57, 'Lefkosia', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (651,57, 'Lemesos', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (652,57, 'Pafos', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (653,58, 'South Bohemian', 'JC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (654,58, 'South Moravian', 'JM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (655,58, 'Karlovy Vary', 'KA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (656,58, 'Vysocina', 'VY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (657,58, 'Hradec Kralove', 'KR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (658,58, 'Liberec', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (659,58, 'Moravian-Silesian', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (660,58, 'Olomouc', 'OL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (661,58, 'Pardubice', 'PA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (662,58, 'Plzen', 'PL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (663,58, 'Prague', 'PR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (664,58, 'Central Bohemian', 'ST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (665,58, 'Usti nad Labem', 'US')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (666,58, 'Zlin', 'ZL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (667,82, 'Berlin (city-state)', 'BE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (668,82, 'Brandenburg', 'BR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (669,82, 'Baden-Württemberg', 'BW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (670,82, 'Bavaria (Bayern)', 'BY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (671,82, 'Bremen (city-state)', 'HB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (672,82, 'Hes (Hesn)', 'HE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (673,82, 'Hamburg (city-state)', 'HH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (674,82, 'Mecklenburg-Western Pomerania (Mecklenburg-Vorpommern)', 'MV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (675,82, 'Lower Saxony (Niedersachn)', 'NI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (676,82, 'North Rhine-Westphalia (Nordrhein-Westfalen)', 'NW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (677,82, 'Rhineland-Palatinate (Rheinland-Pfalz)', 'RP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (678,82, 'Saxony-Anhalt (Sachn-Anhalt)', 'ST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (679,82, 'Schleswig-Holstein', 'SH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (680,82, 'Saarland', 'SL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (681,82, 'Saxony (Sachn)', 'SN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (682,82, 'Thuringia (Thüringen)', 'TH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (683,61, 'Ali Sabiah', 'AS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (684,61, 'Dikhil', 'DI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (685,61, 'Djibouti', 'DJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (686,61, 'Obock', 'OB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (687,61, 'Tadjoura', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (688,60, 'Frederiksberg', '147')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (689,60, 'København', '101')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (690,60, 'Bornholm', '040')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (691,60, 'Frederiksborg', '020')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (692,60, 'Fyn', '042')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (693,60, 'København', '015')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (694,60, 'Nordjylland', '080')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (695,60, 'Ribe', '055')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (696,60, 'Ringkøbing', '065')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (697,60, 'Roskilde', '025')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (698,60, 'Storstrøm', '035')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (699,60, 'Sønderjylland', '050')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (700,60, 'Vejle', '060')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (701,60, 'Vestsjælland', '030')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (702,60, 'Viborg', '076')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (703,60, 'Arhus', '070')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (704,60, 'Nordjylland', '080')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (705,63, 'Distrito Nacional (Santo Domingo)', 'DN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (706,63, 'Azua', 'AZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (707,63, 'Bahoruco', 'BR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (708,63, 'Barahona', 'BH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (709,63, 'Dajabon', 'DA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (710,63, 'Duarte', 'DU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (711,63, 'El Seibo', 'SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (712,63, 'Espaillat', 'EP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (713,63, 'Hato Mayor', 'HM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (714,63, 'Independencia', 'IN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (715,63, 'La Altagracia', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (716,63, 'La Estrelleta', 'EP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (717,63, 'La Romana', 'RO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (718,63, 'La Vega', 'VE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (719,63, 'María Trinidad Sánchez', 'MT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (720,63, 'Monseñor Nouel', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (721,63, 'Monte Cristi', 'MC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (722,63, 'Monte Plata', 'MP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (723,63, 'Pedernales', 'PN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (724,63, 'Peravia', 'PR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (725,63, 'Puerto Plata', 'PP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (726,63, 'Salcedo', 'SC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (727,63, 'Samaná', 'SM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (728,63, 'Sánchez Ramírez', 'SZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (729,63, 'San Cristóbal', 'CR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (730,63, 'San Juan', 'JU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (731,63, 'San Pedro de Macorís', 'PM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (732,63, 'Santiago', 'ST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (733,63, 'Santiago Rodríguez', 'SR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (734,63, 'Valverde', 'VA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (735,4, 'Adrar', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (736,4, 'Ain Defla', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (737,4, 'Aïn T6mouchent', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (738,4, 'Alger', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (739,4, 'Annaba', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (740,4, 'Batna', 'DZ-05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (741,4, 'Béchar', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (742,4, 'Béjaïa', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (743,4, 'Biskra', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (744,4, 'Blida', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (745,4, 'Bordj Bou Arréridj', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (746,4, 'Bouira', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (747,4, 'Boumerdès', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (748,4, 'Chlef', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (749,4, 'Constantine', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (750,4, 'Djelfa', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (751,4, 'El Bayadh', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (752,4, 'El Oued', '39')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (753,4, 'El Tarf', '36')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (754,4, 'Ghardaïa', '47')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (755,4, 'Guelma', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (756,4, 'Illizi', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (757,4, 'Jijel', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (758,4, 'Khenchela', '40')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (759,4, 'Laghouat', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (760,4, 'Mascara', '29')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (761,4, 'Médéa', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (762,4, 'Mila', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (763,4, 'Mostaganem', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (764,4, 'Msila', '28')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (765,4, 'Naama', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (766,4, 'Oran', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (767,4, 'Ouargla', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (768,4, 'Oum el Bouaghi', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (769,4, 'Relizane', '48')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (770,4, 'Saïda', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (771,4, 'Sétif', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (772,4, 'Sidi Bel Abbès', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (773,4, 'Skikda', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (774,4, 'Souk Ahras', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (775,4, 'Tamanghasset', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (776,4, 'Tébessa', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (777,4, 'Tiaret', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (778,4, 'Tindouf', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (779,4, 'Tipaza', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (780,4, 'Tissemsilt', '38')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (781,4, 'Tizi Ouzou', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (782,4, 'Tlemcen', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (783,64, 'Azuay', 'A')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (784,64, 'Bolívar', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (785,64, 'Carchi', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (786,64, 'Orellana', 'D')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (787,64, 'Esmeraldas', 'E')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (788,64, 'Cañar', 'F')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (789,64, 'Guayas', 'G')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (790,64, 'Chimborazo', 'H')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (791,64, 'Imbabura', 'I')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (792,64, 'Loja', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (793,64, 'Manabí', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (794,64, 'Napo', 'N')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (795,64, 'El Oro', 'O')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (796,64, 'Pichincha', 'P')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (797,64, 'Los Ríos', 'R')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (798,64, 'Morona-Santiago', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (799,64, 'Tungur', 'T')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (800,64, 'Sucumbíos', 'U')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (801,64, 'Galápagos', 'W')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (802,64, 'Cotopaxi', 'X')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (803,64, 'Pastaza', 'Y')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (804,64, 'Zamora-Chinchipe', 'Z')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (805,69, 'Harju', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (806,69, 'Hiiu', '39')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (807,69, 'Ida-Viru', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (808,69, 'Jõgeva', '49')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (809,69, 'Järva', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (810,69, 'Lääne', '57')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (811,69, 'Lääne-Viru', '59')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (812,69, 'Põlva', '65')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (813,69, 'Pärnu', '67')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (814,69, 'Rapla', '70')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (815,69, 'Saare', '74')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (816,69, 'Tartu', '78')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (817,69, 'Valga', '82')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (818,69, 'Viljandi', '84')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (819,69, 'Võru County', '86')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (820,65, 'Ad Daqahllyah', 'DK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (821,65, 'Al Bahr al Ahmar', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (822,65, 'Al Buhayrah', 'BH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (823,65, 'Al FayyÜm', 'FYM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (824,65, 'Al Gharbîyah', 'GH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (825,65, 'Al Iskandarlyah', 'ALX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (826,65, 'Al Isma îllyah', 'IS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (827,65, 'Al Jîzah', 'GZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (828,65, 'Al Minuflyah', 'MNF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (829,65, 'Al Minya', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (830,65, 'Al Qahirah', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (831,65, 'Al Qalyûblyah', 'KB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (832,65, 'Al Wadi al Jadîd', 'WAD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (833,65, 'Ash Sharqiyah', 'SHR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (834,65, 'As Suways', 'SUZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (835,65, 'Aswan', 'ASN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (836,65, 'Asyut', 'AST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (837,65, 'Bani Suwayf', 'BNS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (838,65, 'Bûr Sa''îd', 'PTS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (839,65, 'Dumyàt', 'DT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (840,65, 'Janûb Sîna''', 'JS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (841,65, 'Kafr ash Shaykh', 'KFS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (842,65, 'Matrûh', 'MT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (843,65, 'Qinå', 'KN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (844,65, 'Shamâl Sinã''', 'SIN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (845,65, 'Suhâj', 'SHG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (846,68, 'Anseba', 'AN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (847,68, 'Southern Red Sea (Debub-Keih-Bahri)', 'DK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (848,68, 'Southern (Debub)', 'DU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (849,68, 'Gash-Barka', 'GB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (850,68, 'Central (Maekel)', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (851,68, 'Northern Red Sea (Semien-Keih-Bahri)', 'SK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (852,205, 'Alava', 'VI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (853,205, 'Albacete', 'AB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (854,205, 'Alicante', 'A')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (855,205, 'Almería', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (856,205, 'Asturias', 'O')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (857,205, 'Avila', 'AV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (858,205, 'Badajoz', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (859,205, 'Baleares', 'PM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (860,205, 'Barcelona', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (861,205, 'Burgos', 'BU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (862,205, 'Cáceres', 'CC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (863,205, 'Cádiz', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (864,205, 'Cantabria', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (865,205, 'Castellón', 'CS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (866,205, 'Ciudad', 'CR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (867,205, 'Córdoba', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (868,205, 'Cuenca', 'CU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (869,205, 'Girona', 'GI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (870,205, 'Granada', 'GR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (871,205, 'Guadalajara', 'GU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (872,205, 'Guipúzcoa', 'SS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (873,205, 'Huelva', 'H')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (874,205, 'Huesca', 'HU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (875,205, 'Jaén', 'J')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (876,205, 'A Coruña', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (877,205, 'La Rioja', 'LO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (878,205, 'Las Palmas', 'GC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (879,205, 'León', 'LE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (880,205, 'Lleida', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (881,205, 'Lugo', 'LU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (882,205, 'Madrid', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (883,205, 'Málaga', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (884,205, 'Murcia', 'MU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (885,205, 'Navarra', 'NA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (886,205, 'Ourense', 'OR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (887,205, 'Palencia', 'P')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (888,205, 'Pontevedra', 'PO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (889,205, 'Salamanca', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (890,205, 'Santa Cruz De Tenerife', 'TF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (891,205, 'Segovia', 'SG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (892,205, 'Sevilla', 'SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (893,205, 'Soria', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (894,205, 'Tarragona', 'T')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (895,205, 'Teruel', 'TE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (896,205, 'Toledo', 'TO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (897,205, 'Valencia', 'V')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (898,205, 'Valladolid', 'VA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (899,205, 'Vizcaya', 'BI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (900,205, 'Zamora', 'ZA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (901,205, 'Zaragoza', 'Z')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (902,70, 'Addis Ababa', 'AA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (903,70, 'Dire Dawa', 'DD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (904,70, 'Afar', 'ET-AF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (905,70, 'Amara', 'ET-AM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (906,70, 'Benshangul-Gumaz', 'ET-BE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (907,70, 'Gambela Peoples', 'ET-GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (908,70, 'Harari People', 'ET-HA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (909,70, 'Oromia', 'ET-OR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (910,70, 'Somali', 'ET-SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (911,70, 'Southern Nations Nationalities and Peoples', 'ET-SN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (912,70, 'Tigrai', 'ET-TI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (913,74, 'Aland Islands', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (914,74, 'Eastern Finland', 'IS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (915,74, 'Lapland', 'LL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (916,74, 'Western Finland', 'LS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (917,74, 'Oulu', 'OL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (918,73, 'Central', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (919,73, 'Eastern', 'E')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (920,73, 'Northern', 'N')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (921,73, 'Western', 'W')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (922,73, 'Rotuma', 'R')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (923,141, 'Chuuk', 'TRK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (924,141, 'Kosrae', 'KSA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (925,141, 'Pohnpei', 'PNI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (926,141, 'Yap', 'YAP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (927,75, 'Ain', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (928,75, 'Aisne', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (929,75, 'Allier', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (930,75, 'Alpes-de-Haute-Provence', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (931,75, 'Hautes-Alpes', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (932,75, 'Alpes-Maritimes', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (933,75, 'Ardèche', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (934,75, 'Ardennes', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (935,75, 'Ariège', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (936,75, 'Aube', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (937,75, 'Aude', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (938,75, 'Aveyron', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (939,75, 'Bouches-du-Rhône', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (940,75, 'Calvados', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (941,75, 'Cantal', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (942,75, 'Charente', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (943,75, 'Charente-Maritime', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (944,75, 'Cher', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (945,75, 'Corrèze', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (946,75, 'Corse-du-Sud', '2A')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (947,75, 'Haute-Corse', '2B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (948,75, 'Côte-d''Or', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (949,75, 'Côtes-d''Armor', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (950,75, 'Creuse', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (951,75, 'Dordogne', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (952,75, 'Doubs', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (953,75, 'Drôme', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (954,75, 'Eure', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (955,75, 'Eure-et-Loir', '28')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (956,75, 'Finistère', '29')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (957,75, 'Gard', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (958,75, 'Haute-Garonne', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (959,75, 'Gers', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (960,75, 'Gironde', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (961,75, 'Hérault', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (962,75, 'Ille-et-Vilaine', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (963,75, 'Indre', '36')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (964,75, 'Indre-et-Loire', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (965,75, 'Isère', '38')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (966,75, 'Jura', '39')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (967,75, 'Landes', '40')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (968,75, 'Loir-et-Cher', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (969,75, 'Loire', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (970,75, 'Haute-Loire', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (971,75, 'Loire-Atlantique', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (972,75, 'Loiret', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (973,75, 'Lot', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (974,75, 'Lot-et-Garonne', '47')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (975,75, 'Lozère', '48')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (976,75, 'Maine-et-Loire', '49')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (977,75, 'Manche', '50')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (978,75, 'Marne', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (979,75, 'Haute-Marne', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (980,75, 'Mayenne', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (981,75, 'Meurthe-et-Moselle', '54')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (982,75, 'Meuse', '55')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (983,75, 'Morbihan', '56')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (984,75, 'Moselle', '57')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (985,75, 'Nièvre', '58')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (986,75, 'Nord', '59')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (987,75, 'Oise', '60')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (988,75, 'Orne', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (989,75, 'Pas-de-Calais', '62')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (990,75, 'Puy-de-Dôme', '63')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (991,75, 'Pyrénées-Atlantiques', '64')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (992,75, 'Hautes-Pyrénées', '65')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (993,75, 'Pyrénées-Orientales', '66')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (994,75, 'Bas-Rhin', '67')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (995,75, 'Haut-Rhin', '68')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (996,75, 'Rhône', '69')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (997,75, 'Haute-Saône', '70')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (998,75, 'Saône-et-Loire', '71')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (999,75, 'Sarthe', '72')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1000,75, 'Savoie', '73')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1001,75, 'Haute-Savoie', '74')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1002,75, 'Paris', '75')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1003,75, 'Seine-Maritime', '76')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1004,75, 'Seine-et-Marne', '77')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1005,75, 'Yvelines', '78')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1006,75, 'Deux-Sèvres', '79')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1007,75, 'Somme', '80')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1008,75, 'Tarn', '81')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1009,75, 'Tarn-et-Garonne', '82')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1010,75, 'Var', '83')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1011,75, 'Vaucluse', '84')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1012,75, 'Vendée', '85')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1013,75, 'Vienne', '86')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1014,75, 'Haute-Vienne', '87')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1015,75, 'Vosges', '88')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1016,75, 'Yonne', '89')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1017,75, 'Territoire-de-Belfort', '90')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1018,75, 'Essonne', '91')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1019,75, 'Hauts-de-Seine', '92')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1020,75, 'Seine-Saint-Denis', '93')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1021,75, 'Val-de-Marne', '94')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1022,75, 'Val-d''Oise', '95')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1023,75, 'Mayotte', 'YT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1024,75, 'Saint-Pierre and Miquelon', 'PM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1025,75, 'New Caledonia', 'NC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1026,75, 'French Polynesia', 'PF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1027,75, 'French Southern Territories', 'TF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1028,75, 'Wallis and Futuna', 'WF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1029,79, 'Estuaire', '1')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1030,79, 'Haut-Ogooué', '2')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1031,79, 'Moyen-Ogooué', '3')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1032,79, 'Ngounié', '4')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1033,79, 'Nyanga', '5')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1034,79, 'Ogooué-Ivindo', '6')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1035,79, 'Ogooué-Lolo', '7')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1036,79, 'Ogooué-Maritime', '8')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1037,79, 'Woleu-Ntem', '9')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1038,234, 'Barnet (London borough)', 'BNE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1039,234, 'Barnsley (South Yorkshire district)', 'BNS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1040,234, 'Bath and North East Somerset (unitary authority)', 'BAS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1041,234, 'Bedfordshire (county)', 'BDF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1042,234, 'Bexley (London borough)', 'BEX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1043,234, 'Birmingham (West Midlands district)', 'BIR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1044,234, 'Blackburn with Darwen', 'BBD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1045,234, 'Blackpool', 'BPL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1046,234, 'Bolton (Manchester borough)', 'BOL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1047,234, 'Bournemouth', 'BMH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1048,234, 'Bracknell Forest', 'BRC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1049,234, 'Bradford (West Yorkshire district)', 'BRD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1050,234, 'Brent (London borough)', 'BEN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1051,234, 'Brighton and Hove', 'BNH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1052,234, 'Bristol', 'BST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1053,234, 'Bromley (London borough)', 'BRY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1054,234, 'Buckinghamshire (county)', 'BKM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1055,234, 'Bury (Manchester borough)', 'BUR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1056,234, 'Calderdale (West Yorkshire district)', 'CLD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1057,234, 'Cambridgeshire (county)', 'CAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1058,234, 'Camden (London borough)', 'CMD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1059,234, 'Cheshire (county)', 'CHS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1060,234, 'Cornwall (county)', 'CON')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1061,234, 'Coventry (West Midlands district)', 'COV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1062,234, 'Croydon (London borough)', 'CRY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1063,234, 'Cumbria (county)', 'CMA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1064,234, 'Darlington (unitary authority)', 'DAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1065,234, 'Derby', 'DER')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1066,234, 'Derbyshire (county)', 'DBY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1067,234, 'Devon (county)', 'DEV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1068,234, 'Doncaster (South Yorkshire district)', 'DNC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1069,234, 'Dorset (county)', 'DOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1070,234, 'Dudley (West Midlands district)', 'DUD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1071,234, 'Durham', 'DUR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1072,234, 'Ealing (London borough)', 'EAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1073,234, 'East Riding of Yorkshire', 'ERY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1074,234, 'East Sussex (county)', 'ESX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1075,234, 'Enfield (London borough)', 'ENF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1076,234, 'Essex (county)', 'ESS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1077,234, 'Gateshead', 'GAT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1078,234, 'Gloucestershire (county)', 'GLS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1079,234, 'Greenwich (London borough)', 'GRE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1080,234, 'Hackney (London borough)', 'HCK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1081,234, 'Halton (unitary authority)', 'HAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1082,234, 'Hammersmith and Fulham (London borough)', 'HMF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1083,234, 'Hampshire (county)', 'HAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1084,234, 'Haringey (London borough)', 'HRY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1085,234, 'Harrow (London borough)', 'HRW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1086,234, 'Hartlepool (unitary authority)', 'HPL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1087,234, 'Havering (London borough)', 'HAV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1088,234, 'Herefordshire', 'HEF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1089,234, 'Hertfordshire (county)', 'HRT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1090,234, 'Hillingdon (London borough)', 'HIL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1091,234, 'Hounslow (London borough)', 'HNS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1092,234, 'Isle of Wight (county)', 'IOW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1093,234, 'Isles of Scilly', 'IOS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1094,234, 'Islington (London borough)', 'ISL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1095,234, 'Kensington and Chelsea (London borough)', 'KEC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1096,234, 'Kent (county)', 'KEN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1097,234, 'Kingston upon Hull', 'KHL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1098,234, 'Kingston upon Thames (London borough)', 'KTT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1099,234, 'Kirklees (West Yorkshire district)', 'KIR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1100,234, 'Knowsley (metropolitan borough of Merseyside)', 'KWL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1101,234, 'Lambeth (London borough)', 'LBH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1102,234, 'Lancashire (county)', 'LAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1103,234, 'Leeds (West Yorkshire district)', 'LDS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1104,234, 'Leicester (unitary authority)', 'LCE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1105,234, 'Leicestershire (county)', 'LEC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1106,234, 'Lewisham (London borough)', 'LEW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1107,234, 'Lincolnshire (county)', 'LIN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1108,234, 'Liverpool', 'LIV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1109,234, 'London', 'LND')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1110,234, 'Luton (unitary authority)', 'LUT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1111,234, 'Manchester (Manchester borough)', 'MAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1112,234, 'Medway (unitary authority)', 'MDW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1113,234, 'Merton (London borough)', 'MRT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1114,234, 'Middlesbrough (unitary authority)', 'MDB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1115,234, 'Milton Keynes', 'MIK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1116,234, 'Newcastle upon Tyne', 'NET')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1117,234, 'Newham (London borough)', 'NWM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1118,234, 'Norfolk (county)', 'NFK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1119,234, 'North East Lincolnshire (unitary authority)', 'NEL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1120,234, 'North Lincolnshire (unitary authority)', 'NLN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1121,234, 'North Somerset (unitary authority)', 'NSM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1122,234, 'North Tyneside (unitary authority)', 'NTY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1123,234, 'North Yorkshire (unitary authority)', 'NYK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1124,234, 'Northamptonshire (county)', 'NTH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1125,234, 'Northumberland', 'NBL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1126,234, 'Nottingham', 'NGM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1127,234, 'Nottinghamshire (county)', 'NTT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1128,234, 'Oldham (Manchester borough)', 'OLD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1129,234, 'Oxfordshire (county)', 'OXF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1130,234, 'Peterborough (unitary authority)', 'PTE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1131,234, 'Plymouth', 'PLY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1132,234, 'Poole', 'POL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1133,234, 'Portsmouth', 'POR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1134,234, 'Reading', 'RDG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1135,234, 'Redbridge (London Borough)', 'RDB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1136,234, 'Redcar and Cleveland', 'RCC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1137,234, 'Richmond upon Thames (London Borough)', 'RIC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1138,234, 'Rochdale (Manchester borough)', 'RCH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1139,234, 'Rotherham (South Yorkshire district)', 'ROT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1140,234, 'Rutland (county)', 'RUT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1141,234, 'St Helens (Merseyside borough)', 'SHN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1142,234, 'Salford (Manchester borough)', 'SLF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1143,234, 'Sandwell (West Midlands district)', 'SAW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1144,234, 'Sefton (Merseyside borough)', 'SFT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1145,234, 'Sheffield (South Yorkshire district)', 'SHF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1146,234, 'Shropshire (county)', 'SHR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1147,234, 'Slough (unitary authority)', 'SLG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1148,234, 'Solihull (West Midlands district)', 'SOL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1149,234, 'Somerset (county)', 'SOM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1150,234, 'South Gloucestershire (county)', 'SGC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1151,234, 'South Tyneside', 'STY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1152,234, 'Southampton', 'STH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1153,234, 'Southend-on-Sea', 'SOS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1154,234, 'Southwark (London borough)', 'SWK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1155,234, 'Staffordshire (county)', 'STS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1156,234, 'Stockport (Manchester borough)', 'SKP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1157,234, 'Stockton-on-Tees', 'STT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1158,234, 'Stoke-on-Trent (unitary authority)', 'STE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1159,234, 'Suffolk (county)', 'SFK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1160,234, 'Sunderland', 'GB-SND')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1161,234, 'Surrey (county)', 'SRY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1162,234, 'Sutton (London borough)', 'STN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1163,234, 'Swindon', 'SWD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1164,234, 'Tameside (Manchester borough)', 'TAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1165,234, 'Telford and Wrekin (unitary authority)', 'TFW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1166,234, 'Thurrock (unitary authority)', 'THR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1167,234, 'Torbay', 'TOB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1168,234, 'Tower Hamlets (London borough)', 'TWH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1169,234, 'Trafford (Manchester borough)', 'TRF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1170,234, 'Wakefield (West Yorkshire district)', 'WKF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1171,234, 'Walsall (West Midlands district)', 'WLL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1172,234, 'Waltham Forest (London borough)', 'WFT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1173,234, 'Wandsworth (London borough)', 'WND')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1174,234, 'Warrington (unitary authority)', 'WRT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1175,234, 'Warwickshire (county)', 'WAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1176,234, 'West Berkshire (unitary authority)', 'WBK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1177,234, 'West Sussex (county)', 'WSX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1178,234, 'Westminster (London borough)', 'WSM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1179,234, 'Wigan (Manchester borough)', 'WGN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1180,234, 'Wiltshire (county)', 'WIL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1181,234, 'Windsor and Maidenhead (unitary authority)', 'WNM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1182,234, 'Wirral (metropolitan borough of Merseyside)', 'WRL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1183,234, 'Wokingham (unitary authority)', 'WOK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1184,234, 'Wolverhampton (West Midlands district)', 'WLV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1185,234, 'Worcestershire (county)', 'WOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1186,234, 'York (unitary authority)', 'YOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1187,234, 'Aberdeen City', 'ABE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1188,234, 'Aberdeenshire', 'ABD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1189,234, 'Angus', 'ANS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1190,234, 'Argyll and Bute', 'AGB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1191,234, 'Clackmannanshire', 'CLK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1192,234, 'Dumfries and Galloway', 'DGY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1193,234, 'Dundee City', 'DND')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1194,234, 'East Ayrshire', 'EAY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1195,234, 'East Dunbartonshire', 'EDU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1196,234, 'East Lothian', 'ELN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1197,234, 'East Renfrewshire', 'ERW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1198,234, 'Edinburgh', 'EDH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1199,234, 'Eilean Siar', 'ELS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1200,234, 'Falkirk', 'FAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1201,234, 'Fife', 'FIF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1202,234, 'Glasgow City', 'GLG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1203,234, 'Highland', 'HLD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1204,234, 'Inverclyde', 'IVC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1205,234, 'North Ayrshire', 'NAY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1206,234, 'North Lanarkshire', 'NLK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1207,234, 'Orkney Islands', 'ORK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1208,234, 'Perth and Kinross', 'PKN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1209,234, 'Midlothian', 'MLN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1210,234, 'Moray', 'MRY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1211,234, 'Renfrewshire', 'RFW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1212,234, 'Scottish Borders', 'SCB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1213,234, 'Shetland Islands', 'ZET')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1214,234, 'South Ayrshire', 'SAY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1215,234, 'South Lanarkshire', 'SLK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1216,234, 'Stirling', 'STG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1217,234, 'West Dunbartonshire', 'WDU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1218,234, 'West Lothian', 'WLN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1219,234, 'Antrim', 'ANT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1220,234, 'Ards', 'ARD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1221,234, 'Armagh', 'ARM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1222,234, 'Ballymena', 'BLA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1223,234, 'Ballymoney', 'BLY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1224,234, 'Banbridge', 'BNB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1225,234, 'Belfast', 'BFS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1226,234, 'Carrickfergus', 'CKF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1227,234, 'Castlereagh', 'CSR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1228,234, 'Coleraine', 'CLR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1229,234, 'Cookstown', 'CKT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1230,234, 'Craigavon', 'CGV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1231,234, 'Derry', 'DRY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1232,234, 'Down', 'DOW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1233,234, 'Dungannon', 'DGN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1234,234, 'Fermanagh', 'FER')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1235,234, 'Larne', 'LRN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1236,234, 'Limavady', 'LMV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1237,234, 'Lisburn', 'LSB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1238,234, 'Magherafelt', 'MFT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1239,234, 'Moyle', 'MYL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1240,234, 'Newry and Mourne', 'NYM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1241,234, 'Newtownabbey', 'NTA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1242,234, 'North Down NIR', 'NDN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1243,234, 'Omagh', 'OMH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1244,234, 'Strabane', 'STB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1245,234, 'Blaenau Gwent', 'BGW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1246,234, 'Bridgend', 'BGE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1247,234, 'Caerphilly', 'CAY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1248,234, 'Cardiff', 'CRF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1249,234, 'Carmarthenshire', 'CMN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1250,234, 'Ceredigion', 'CGN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1251,234, 'Conwy', 'CWY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1252,234, 'Denbighshire', 'DEN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1253,234, 'Flintshire', 'FLN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1254,234, 'Gwynedd', 'GWN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1255,234, 'Isle of Anglesey', 'AGY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1256,234, 'Merthyr Tydfil', 'MTY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1257,234, 'Monmouthshire', 'MON')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1258,234, 'Neath Port Talbot', 'NTL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1259,234, 'Newport', 'NWP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1260,234, 'Pembrokeshire', 'PEM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1261,234, 'Powys', 'POW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1262,234, 'Rhondda Cynon Taf', 'RCT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1263,234, 'Swansea', 'SWA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1264,234, 'Torfaen', 'TOF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1265,234, 'Vale of Glamorgan', 'VGL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1266,234, 'Wrexham', 'WRX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1267,81, 'Guria', 'GU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1268,81, 'Imereti', 'IM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1269,81, 'Kakheti', 'KA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1270,81, 'Kvemo Kartli', 'KK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1271,81, 'Mtskheta-Mtianeti', 'MM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1272,81, 'Racha Lechkhumi and Kvemo Svaneti', 'RL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1273,81, 'Samegrelo-Zemo Svaneti', 'SZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1274,81, 'Samtskhe-Javakheti', 'SJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1275,81, 'Shida Kartli', 'SK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1276,83, 'Ashanti', 'AH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1277,83, 'Brong-Ahafo', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1278,83, 'Central', 'CP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1279,83, 'Eastern', 'EP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1280,83, 'Greater Accra', 'AA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1281,83, 'Northern', 'NP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1282,83, 'Upper East', 'UE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1283,83, 'Upper West', 'UW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1284,83, 'Volta', 'TV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1285,83, 'Western', 'WP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1286,80, 'Banjul', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1287,80, 'Lower River', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1288,80, 'MacCarthy Island', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1289,80, 'North Bank', 'N')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1290,80, 'Upper River', 'U')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1291,80, 'Western', 'W')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1292,92, 'Beyla', 'BE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1293,92, 'Boffa', 'BF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1294,92, 'Boké', 'BK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1295,92, 'Coyah', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1296,92, 'Dabola', 'DB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1297,92, 'Dalaba', 'DL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1298,92, 'Dinguiraye', 'DI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1299,92, 'Dubréka', 'DU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1300,92, 'Faranah', 'FA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1301,92, 'Forécariah', 'FO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1302,92, 'Fria', 'FR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1303,92, 'Gaoual', 'GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1304,92, 'Guékédou', 'GU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1305,92, 'Kankan', 'KA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1306,92, 'Kérouané', 'KE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1307,92, 'Kindia', 'KD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1308,92, 'Kissidougou', 'KS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1309,92, 'Koubia', 'KB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1310,92, 'Koundara', 'KD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1311,92, 'Kouroussa', 'KO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1312,92, 'Labé', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1313,92, 'Lélouma', 'LE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1314,92, 'Lola', 'LO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1315,92, 'Macenta', 'MC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1316,92, 'Mali', 'ML')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1317,92, 'Mamou', 'MM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1318,92, 'Mandiana', 'MD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1319,92, 'Nzérékoré', 'NZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1320,92, 'Pita', 'PI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1321,92, 'Siguiri', 'SI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1322,92, 'Télimélé', 'TE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1323,92, 'Tougué', 'TO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1324,92, 'Yomou', 'YO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1325,67, 'Regiôn Continental', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1326,67, 'Region Insular', 'I')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1327,67, 'Annobón', 'AN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1328,67, 'Bioko Norte', 'BN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1329,67, 'Bioko Sur', 'BS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1330,67, 'Centro Sur', 'CS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1331,67, 'Kie-Ntem', 'KN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1332,67, 'Litoral', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1333,67, 'Wele-Nzas', 'WN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1334,85, 'Achaïa', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1335,85, 'Aitolia-Akarnania', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1336,85, 'Argolis', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1337,85, 'Arkadia', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1338,85, 'Arta', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1339,85, 'Attiki', 'A1')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1340,85, 'Chalkidiki', '64')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1341,85, 'Chania', '94')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1342,85, 'Chios', '85')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1343,85, 'Dodekanisos', '81')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1344,85, 'Drama', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1345,85, 'Evros', '71')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1346,85, 'Evrytania', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1347,85, 'Evvoia', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1348,85, 'Florina', '63')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1349,85, 'Fokis', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1350,85, 'Fthiotis', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1351,85, 'Grevena', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1352,85, 'Ileia', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1353,85, 'Imathia', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1354,85, 'Ioannina', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1355,85, 'Irakleion', '91')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1356,85, 'Karditsa', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1357,85, 'Kastoria', '56')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1358,85, 'Kavalla', '55')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1359,85, 'Kefallinia', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1360,85, 'Kerkyra', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1361,85, 'Kilkis', '57')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1362,85, 'Korinthia', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1363,85, 'Kozani', '58')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1364,85, 'Kyklades', '82')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1365,85, 'Lakonia', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1366,85, 'Larisa', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1367,85, 'Lasithion', '92')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1368,85, 'Lefkas', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1369,85, 'Lesvos', '83')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1370,85, 'Magnisia', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1371,85, 'Messinia', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1372,85, 'Pella', '59')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1373,85, 'Preveza', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1374,85, 'Rethymnon', '93')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1375,85, 'Rodopi', '73')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1376,85, 'Samos', '84')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1377,85, 'Serrai', '62')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1378,85, 'Thesprotia', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1379,85, 'Thessaloniki', '54')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1380,85, 'Trikala', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1381,85, 'Voiotia', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1382,85, 'Xanthi', '72')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1383,85, 'Zakynthos', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1384,85, 'Agio Oros', '69')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1385,90, 'Alta Verapez', 'AV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1386,90, 'Baja Verapez', 'BV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1387,90, 'Chimaltenango', 'CM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1388,90, 'Chiquimula', 'CQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1389,90, 'El Progreso', 'PR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1390,90, 'Escuintla', 'ES')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1391,90, 'Guatemala', 'GU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1392,90, 'Huehuetenango', 'HU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1393,90, 'Izabal', 'IZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1394,90, 'Jalapa', 'JA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1395,90, 'Jutíapa', 'JU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1396,90, 'Petén', 'PE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1397,90, 'Quezaltenango', 'QZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1398,90, 'Quiché', 'QC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1399,90, 'Reta.thuleu', 'RE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1400,90, 'Sacatepéquez', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1401,90, 'San Marcos', 'SM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1402,90, 'Santa Rosa', 'SR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1403,90, 'Solol6', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1404,90, 'Suchitepéquez', 'SU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1405,90, 'Totonicapán', 'TO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1406,90, 'Zacapa', 'ZA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1407,93, 'Bissau', 'BS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1408,93, 'Bafatá', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1409,93, 'Biombo', 'BM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1410,93, 'Bolama', 'BL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1411,93, 'Cacheu', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1412,93, 'Gabú', 'GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1413,93, 'Oio', 'OI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1414,93, 'Quloara', 'QU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1415,93, 'Tombali S', 'TO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1416,94, 'Barima-Waini', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1417,94, 'Cuyuni-Mazaruni', 'CU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1418,94, 'Demerara-Mahaica', 'DE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1419,94, 'East Berbice-Corentyne', 'EB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1420,94, 'Essequibo Islands-West Demerara', 'ES')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1421,94, 'Mahaica-Berbice', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1422,94, 'Pomeroon-Supenaam', 'PM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1423,94, 'Potaro-Siparuni', 'PT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1424,94, 'Upper Demerara-Berbice', 'UD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1425,94, 'Upper Takutu-Upper Essequibo', 'UT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1426,97, 'Atlántida', 'AT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1427,97, 'Colón', 'CL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1428,97, 'Comayagua', 'CM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1429,97, 'Copán', 'CP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1430,97, 'Cortés', 'CR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1431,97, 'Choluteca', 'CH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1432,97, 'El Paraíso', 'EP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1433,97, 'Francisco Morazán', 'FM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1434,97, 'Gracias a Dios', 'GD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1435,97, 'Intibucá', 'IN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1436,97, 'Islas de la Bahía', 'IB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1437,97, 'La Paz', 'LP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1438,97, 'Lempira', 'LE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1439,97, 'Ocotepeque', 'OC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1440,97, 'Olancho', 'OL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1441,97, 'Santa Bárbara', 'SB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1442,97, 'Valle', 'VA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1443,97, 'Yoro', 'YO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1444,55, 'Zagreb', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1445,55, 'Krapina-Zagorje', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1446,55, 'Sisak-Moslavina', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1447,55, 'Karlovac', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1448,55, 'Varazdin', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1449,55, 'Koprivnica-Krizevci', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1450,55, 'Bjelovar-Bilogora', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1451,55, 'Primorje-Gorski Kotar', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1452,55, 'Lika-Senj', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1453,55, 'Virovitica-Podravina', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1454,55, 'Pozega-Slavonia', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1455,55, 'Brod-Posavina', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1456,55, 'Zadar', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1457,55, 'Osijek-Baranja', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1458,55, 'Sibenik-Knin', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1459,55, 'Vukovar-Srijem', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1460,55, 'Split-Dalmatia', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1461,55, 'Istria', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1462,55, 'Dubrovnik-Neretva', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1463,55, 'Medjimurje', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1464,55, 'Zagreb', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1465,95, 'Centre', 'CE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1466,95, 'Grande-Anse', 'GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1467,95, 'Nord', 'ND')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1468,95, 'Nord-Eat', 'NE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1469,95, 'Nord-Ouest', 'NO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1470,95, 'Ouest', 'OU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1471,95, 'Sud', 'SD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1472,95, 'Sud-Est', 'SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1473,99, 'Budapest', 'BU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1474,99, 'Bács-Kiskun', 'BK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1475,99, 'Baranya', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1476,99, 'Békés', 'BE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1477,99, 'Borsod-Abaúj-Zemplén', 'BZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1478,99, 'Csongrád', 'CS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1479,99, 'Fejér', 'FE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1480,99, 'Gyõr-Moson-Sopron', 'GS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1481,99, 'Hajdû-Bihar', 'HB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1482,99, 'Heves', 'HE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1483,99, 'Jász-Nagykun-Szolnok', 'JN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1484,99, 'Komárom-Esztergom', 'KE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1485,99, 'Nógrád', 'NO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1486,99, 'Pest', 'PE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1487,99, 'Somogy', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1488,99, 'Szabolcs-Szatmár-Bereg', 'SZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1489,99, 'Tolna', 'TO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1490,99, 'Vas', 'VA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1491,99, 'Veszprém', 'VE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1492,99, 'Zala', 'ZA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1493,99, 'Békéscsaba', 'BC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1494,99, 'Debrecen', 'DE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1495,99, 'Dunaújváros', 'DU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1496,99, 'Eger', 'EG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1497,99, 'Gyór', 'GY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1498,99, 'Hôdmezóvásárhely', 'HV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1499,99, 'Kaposvár', 'KV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1500,99, 'Kecékemét', 'KM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1501,99, 'Miskolc', 'MI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1502,99, 'Nagykanizaa', 'NK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1503,99, 'Nyíregyháza', 'NY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1504,99, 'Pécs', 'PS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1505,99, 'Salgótarján', 'ST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1506,99, 'Sopron', 'SN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1507,99, 'Szaged', 'SD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1508,99, 'Szakeafahérvár', 'SF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1509,99, 'Szakszárd', 'SS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1510,99, 'Szolnok', 'SK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1511,99, 'Szombathely', 'SH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1512,99, 'Tatabinya', 'TB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1513,99, 'Veezprém', 'VM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1514,99, 'Zalaegerszeg', 'ZE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1515,102, 'Bali', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1516,102, 'Bangka-Belitung', 'BB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1517,102, 'Banten', 'BT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1518,102, 'Bengkulu', 'BE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1519,102, 'Gorontalo', 'GO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1520,102, 'Jambi', 'JA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1521,102, 'Jawa Barat', 'JR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1522,102, 'Jawa Tengah', 'JT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1523,102, 'Jawa Timur', 'JI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1524,102, 'Kalimantan Barat', 'KB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1525,102, 'Kalimantan Selatan', 'KS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1526,102, 'Kalimantan Tengah', 'KT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1527,102, 'Kalimantan Timur', 'KI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1528,102, 'Lampung', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1529,102, 'Maluku', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1530,102, 'Maluku Utara', 'MU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1531,102, 'Nusa Tenggara Barat', 'NB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1532,102, 'Nusa Tenggara Timur', 'NT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1533,102, 'Papua', 'IJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1534,102, 'Riau', 'RI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1535,102, 'Sulawesi Selatan', 'SN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1536,102, 'Sulawesi Tengah', 'ST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1537,102, 'Sulawesi Tenggara', 'SG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1538,102, 'Sulawesi Utara', 'SW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1539,102, 'Sumatera Barat', 'SB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1540,102, 'Sumatera Selatan', 'SL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1541,102, 'Sumatera Utara', 'SU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1542,105, 'Carlow', 'CW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1543,105, 'Cavan', 'CN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1544,105, 'Clare', 'CE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1545,105, 'Cork', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1546,105, 'Donegal', 'DL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1547,105, 'Dublin', 'D')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1548,105, 'Galway', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1549,105, 'Kerry', 'KY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1550,105, 'Kildare', 'KE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1551,105, 'Kilkenny', 'KK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1552,105, 'Laois', 'LS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1553,105, 'Leitrim', 'LM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1554,105, 'Limerick', 'LK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1555,105, 'Longford', 'LD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1556,105, 'Louth', 'LH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1557,105, 'Mayo', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1558,105, 'Meath', 'MH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1559,105, 'Monaghan', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1560,105, 'Offaly', 'OY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1561,105, 'Roscommon', 'RN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1562,105, 'Sligo', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1563,105, 'Tipperary', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1564,105, 'Waterford', 'WD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1565,105, 'Westmeath', 'WH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1566,105, 'Wexford', 'WX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1567,105, 'Wicklow', 'WW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1568,107, 'HaDarom', 'D')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1569,107, 'HaMerkaz', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1570,107, 'HaZafon', 'Z')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1571,107, 'Hefa', 'HA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1572,107, 'Tel-Aviv', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1573,107, 'Yerushalayim', 'JM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1574,101, 'Andhra Pradesh', 'AP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1575,101, 'Arunachal Pradesh', 'AR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1576,101, 'Assam', 'AS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1577,101, 'Bihar', 'BR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1578,101, 'Chhattisgarh', 'CT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1579,101, 'Goa', 'GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1580,101, 'Gujarat', 'GJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1581,101, 'Haryana', 'HR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1582,101, 'Himachal Pradesh', 'HP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1583,101, 'Jammu and Kashmir', 'JK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1584,101, 'Jharkhand', 'JH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1585,101, 'Karnataka', 'KA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1586,101, 'Kerala', 'KL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1587,101, 'Madhya Pradesh', 'MP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1588,101, 'Maharashtra', 'MM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1589,101, 'Manipur', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1590,101, 'Meghalaya', 'ML')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1591,101, 'Mizoram', 'MZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1592,101, 'Nagaland', 'NL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1593,101, 'Orissa', 'OR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1594,101, 'Punjab', 'PB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1595,101, 'Rajasthan', 'RJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1596,101, 'Sikkim', 'SK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1597,101, 'Tamil Nadu', 'TN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1598,101, 'Tripura', 'TR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1599,101, 'Uttaranchal', 'UL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1600,101, 'Uttar Pradesh', 'UP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1601,101, 'West Bengal', 'WB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1602,103, 'East Azerbaijan', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1603,103, 'West Azerbaijan', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1604,103, 'Ardabil', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1605,103, 'Esfahan', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1606,103, 'Ilam', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1607,103, 'Bushehr', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1608,103, 'Tehran', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1609,103, 'Chahar Mahaal and Bakhtiari', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1610,103, 'Khorasan', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1611,103, 'Khuzestan', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1612,103, 'Zanjan', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1613,103, 'Semnan', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1614,103, 'Sistan and Baluchistan', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1615,103, 'Fars', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1616,103, 'Kerman', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1617,103, 'Kurdistan', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1618,103, 'Kermanshah', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1619,103, 'Kohkiluyeh and Buyer Ahmad', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1620,103, 'Gilan', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1621,103, 'Lorestan', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1622,103, 'Mazandaran', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1623,103, 'Markazi', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1624,103, 'Hormozgan', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1625,103, 'Hamada', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1626,103, 'Yazd', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1627,103, 'Qom', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1628,103, 'Golestan', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1629,103, 'Qazvin', '28')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1630,100, 'Austurland', '7')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1631,100, 'Höfuoborgarsvæöi utan Reykjavíkur', '1')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1632,100, 'Noröurland eystra', '6')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1633,100, 'Noröurland vestra', '5')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1634,100, 'Reykjavík', '0')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1635,100, 'Suöurland', '8')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1636,100, 'Suöurnes', '2')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1637,100, 'Vestfirölr', '4')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1638,100, 'Vesturland', '3')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1639,108, 'Agrigento', 'AG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1640,108, 'Alessandria', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1641,108, 'Ancona', 'AN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1642,108, 'Aosta', 'AO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1643,108, 'Arezzo', 'AR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1644,108, 'Ascoli Piceno', 'AP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1645,108, 'Asti', 'AT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1646,108, 'Avellino', 'AV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1647,108, 'Bari', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1648,108, 'Belluno', 'BL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1649,108, 'Benevento', 'BN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1650,108, 'Bergamo', 'BG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1651,108, 'Biella', 'BI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1652,108, 'Bologna', 'BO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1653,108, 'Bolzano', 'BZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1654,108, 'Brescia', 'BS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1655,108, 'Brindisi', 'BR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1656,108, 'Cagliari', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1657,108, 'Caltanissetta', 'CL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1658,108, 'Campobasso', 'CB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1659,108, 'Caserta', 'CE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1660,108, 'Catania', 'CT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1661,108, 'Catanzaro', 'CZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1662,108, 'Chieti', 'CH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1663,108, 'Como', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1664,108, 'Cosenza', 'CS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1665,108, 'Cremona', 'CR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1666,108, 'Crotone', 'KR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1667,108, 'Cuneo', 'CN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1668,108, 'Enna', 'EN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1669,108, 'Ferrara', 'FE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1670,108, 'Florence', 'FI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1671,108, 'Foggia', 'FG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1672,108, 'Forli', 'FO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1673,108, 'Frosinone', 'FR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1674,108, 'Genova', 'GE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1675,108, 'Gorizia', 'GO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1676,108, 'Grosseto', 'GR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1677,108, 'Imperia', 'IM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1678,108, 'Isernia', 'IS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1679,108, 'L''Aquila', 'AQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1680,108, 'La Spezia', 'SP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1681,108, 'Latina', 'LT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1682,108, 'Lecce', 'LE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1683,108, 'Lecco', 'LC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1684,108, 'Livorno', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1685,108, 'Lodi', 'LO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1686,108, 'Lucca', 'LU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1687,108, 'Macerata', 'MC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1688,108, 'Mantua', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1689,108, 'Massa-Carrara', 'MS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1690,108, 'Matera', 'MT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1691,108, 'Messina', 'ME')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1692,108, 'Milan', 'MI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1693,108, 'Modena', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1694,108, 'Napoli', 'NA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1695,108, 'Novara', 'NO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1696,108, 'Nuoro', 'NU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1697,108, 'Oristano', 'OR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1698,108, 'Padua', 'PD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1699,108, 'Palermo', 'PA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1700,108, 'Parma', 'PR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1701,108, 'Pavia', 'PV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1702,108, 'Perugia', 'PG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1703,108, 'Pesaro e Urbino', 'PS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1704,108, 'Pescara', 'PE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1705,108, 'Piacenza', 'PC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1706,108, 'Pisa', 'PI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1707,108, 'Pistoia', 'PT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1708,108, 'Pordenone', 'PN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1709,108, 'Potenza', 'PZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1710,108, 'Prato', 'PO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1711,108, 'Ragusa', 'RG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1712,108, 'Ravenna', 'RA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1713,108, 'Reggio di Calabria', 'RC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1714,108, 'Reggio nell''Emilia', 'RE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1715,108, 'Rieti', 'RI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1716,108, 'Rimini', 'RN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1717,108, 'Rome Roma', 'RM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1718,108, 'Rovigo', 'RO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1719,108, 'Salerno', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1720,108, 'Sassari', 'SS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1721,108, 'Savona', 'SV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1722,108, 'Siena', 'SI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1723,108, 'Sondrio', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1724,108, 'Syracuse', 'SR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1725,108, 'Taranto', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1726,108, 'Teramo', 'TE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1727,108, 'Terni', 'TR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1728,108, 'Trapani', 'TP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1729,108, 'Trento', 'TN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1730,108, 'Treviso', 'TV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1731,108, 'Trieste', 'TS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1732,108, 'Turin', 'TO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1733,108, 'Udine', 'UD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1734,108, 'Varese', 'VA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1735,108, 'Venice', 'VE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1736,108, 'Verbano-Cusio-Ossola', 'VB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1737,108, 'Vercelli', 'VC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1738,108, 'Verona', 'VR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1739,108, 'Vibo Valentia', 'VV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1740,108, 'Vicenza', 'VI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1741,108, 'Viterbo', 'VT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1742,112, 'Ajlün', 'AJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1743,112, 'Al ''Aqaba', 'AQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1744,112, 'Al Balqa''', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1745,112, 'Al Karak', 'KA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1746,112, 'Al Mafraq', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1747,112, '''Ammãn', 'AM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1748,112, 'At Tafîlah', 'AT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1749,112, 'Az Zargã''', 'AZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1750,112, 'Irbid', 'JR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1751,112, 'Jarash', 'JA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1752,112, 'Ma''ãn', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1753,112, 'Madaba', 'MD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1754,110, 'Hokkaido', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1755,110, 'Aomori', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1756,110, 'Iwate', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1757,110, 'Miyaga', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1758,110, 'Akita', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1759,110, 'Yamagata', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1760,110, 'Hukusima (Fukushima)', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1761,110, 'Ibaraki', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1762,110, 'Totigi (Tochigi)', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1763,110, 'Gunma', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1764,110, 'Saitama', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1765,110, 'Tiba (Chiba)', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1766,110, 'Tokyo', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1767,110, 'Kanagawa', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1768,110, 'Niigata', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1769,110, 'Toyama', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1770,110, 'Isikawa (Ishikawa)', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1771,110, 'Hukui (Fukui)', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1772,110, 'Yamanasi (Yamanashi)', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1773,110, 'Nagano', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1774,110, 'Gihu (Gifu)', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1775,110, 'Sizuoka (Shizuoka)', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1776,110, 'Aiti (Aichi)', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1777,110, 'Mie', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1778,110, 'Siga (Shiga)', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1779,110, 'Kyoto', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1780,110, 'Osaka', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1781,110, 'Hyogo', '28')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1782,110, 'Nara', '29')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1783,110, 'Wakayama', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1784,110, 'Tottori', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1785,110, 'Simane (Shimane)', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1786,110, 'Okayama', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1787,110, 'Hirosima (Hiroshima)', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1788,110, 'Yamaguti (Yamaguchi)', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1789,110, 'Tokusima (Tokushima)', '36')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1790,110, 'Kagawa', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1791,110, 'Ehime', '38')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1792,110, 'Koti (Kochi)', '39')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1793,110, 'Hukuoka (Fukuoka)', '40')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1794,110, 'Saga', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1795,110, 'Nagasaki', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1796,110, 'Kumamoto', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1797,110, 'Oita', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1798,110, 'Miyazaki', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1799,110, 'Kagosima (Kagoshima)', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1800,110, 'Okinawa', '47')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1801,114, 'Nairobi Municipality', '110')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1802,114, 'Central', '200')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1803,114, 'Coast', '300')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1804,114, 'Eastern', '400')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1805,114, 'North-Eastern Kaskazini Mashariki', '500')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1806,114, 'Rift Valley', '700')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1807,114, 'Western Magharibi', '900')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1808,117, 'Batken', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1809,117, 'Chü', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1810,117, 'Jalal-Abad', 'J')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1811,117, 'Naryn', 'N')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1812,117, 'Osh', 'O')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1813,117, 'Talas', 'T')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1814,117, 'Ysyk-Köl', 'Y')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1815,38, 'Baat Dambang', '2')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1816,38, 'Banteay Mean Chey', '1')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1817,38, 'Rampong Chaam', '3')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1818,38, 'Kampong Chhnang', '4')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1819,38, 'Kampong Spueu', '5')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1820,38, 'Kampong Thum', '6')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1821,38, 'Kampot', '7')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1822,38, 'Kandaal', '8')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1823,38, 'Kach Kong', '9')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1824,38, 'Krachoh', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1825,38, 'Mondol Kiri', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1826,38, 'Otdar Mean Chey', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1827,38, 'Pousaat', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1828,38, 'Preah Vihear', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1829,38, 'Prey Veaeng', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1830,38, 'Rotanak Kiri', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1831,38, 'Siem Reab', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1832,38, 'Stueng Traeng', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1833,38, 'Svaay Rieng', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1834,38, 'Taakaev', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1835,115, 'Gilbert Islands', 'G')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1836,115, 'Line Islands', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1837,115, 'Phoenix Islands', 'P')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1838,162, 'Chagang-do', 'CHA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1839,162, 'Hamgyong-bukto', 'HAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1840,162, 'Hamgyong-namdo', 'HAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1841,162, 'Hwanghae-bukto', 'HWB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1842,162, 'Hwanghae-namdo', 'HWN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1843,162, 'Kangwon-do', 'KAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1844,162, 'P''yongan-bukto', 'PYB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1845,162, 'P''yongan-namdo', 'PYN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1846,162, 'Yanggang-do', 'YAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1847,204, 'Gyeonggi-do', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1848,204, 'Gangwon-do', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1849,204, 'Chungcheongbuk-do', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1850,204, 'Chungcheongnam-do', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1851,204, 'Jeollabuk-do', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1852,204, 'Jeollanam-do', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1853,204, 'Gyeongsangbuk-do', '47')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1854,204, 'Gyeongsangnam-do', '48')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1855,204, 'Jeju-do', '49')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1856,116, 'Al Ahmadî', 'AH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1857,116, 'Al Farwanlyah', 'FA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1858,116, 'Al Jahrah', 'JA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1859,116, 'Al Kuwayt', 'KU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1860,116, 'Hawallî', 'HA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1861,113, 'Almaty', 'ALM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1862,113, 'Aqmola', 'AKM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1863,113, 'Aqtobe', 'AKT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1864,113, 'Atyrau', 'ATY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1865,113, 'Baty Qazaqstan', 'ZAP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1866,113, 'Mangghystau', 'MAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1867,113, 'Ongtustik Qazaqstan', 'YUZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1868,113, 'Paylodar', 'PAV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1869,113, 'Qaraghandy', 'KAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1870,113, 'Qustanay', 'KUS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1871,113, 'Qyzylorda', 'KZY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1872,113, 'Shyghys Qazaqstan', 'VOS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1873,113, 'Soltustik Qazaqstan', 'SEV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1874,113, 'Zhambyl', 'ZHA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1875,118, 'Attapeu', 'AT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1876,118, 'Bokeo', 'BK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1877,118, 'Borikhamxay', 'BL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1878,118, 'Champassack', 'CH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1879,118, 'Houaphan', 'HO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1880,118, 'Khammouane', 'KH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1881,118, 'Louang Namtha', 'LM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1882,118, 'Louang Phabang', 'LP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1883,118, 'Oudomxay', 'OU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1884,118, 'Phongsaly', 'PH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1885,118, 'Saravane', 'SL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1886,118, 'Savannakhet', 'SV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1887,118, 'Vientiane', 'VI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1888,118, 'Sayaboury', 'XA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1889,118, 'Xekong', 'XE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1890,118, 'Xieng Khouang', 'XI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1891,206, 'Ampara', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1892,206, 'Anuradhapura', '71')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1893,206, 'Badulla', '81')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1894,206, 'Batticaloa', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1895,206, 'Colombo', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1896,206, 'Galle', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1897,206, 'Gampaha', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1898,206, 'Hambantota', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1899,206, 'Jaffna', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1900,206, 'Kalutara', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1901,206, 'Kandy', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1902,206, 'Kegalla', '92')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1903,206, 'Kilinochchi', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1904,206, 'Kurunegala', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1905,206, 'Mannar', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1906,206, 'Matale', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1907,206, 'Matara', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1908,206, 'Monaragala', '82')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1909,206, 'Mullaittivu', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1910,206, 'Nuwara Eliya', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1911,206, 'Polonnaruwa', '72')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1912,206, 'Puttalum', '62')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1913,206, 'Ratnapura', '91')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1914,206, 'Trincomalee', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1915,206, 'VavunLya', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1916,122, 'Bomi', 'BM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1917,122, 'Bong', 'BG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1918,122, 'Grand Basaa', 'GB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1919,122, 'Grand Cape Mount', 'CM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1920,122, 'Grand Gedeh', 'GG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1921,122, 'Grand Kru', 'GK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1922,122, 'Lofa', 'LO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1923,122, 'Margibi', 'MG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1924,122, 'Maryland', 'MY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1925,122, 'Montserrado', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1926,122, 'Nimba', 'NI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1927,122, 'Rivercess', 'RI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1928,122, 'Sinoe', 'SI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1929,125, 'Alytaus Apskritis', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1930,125, 'Kauno Apskritis', 'KU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1931,125, 'Klaipedos Apskritis', 'KL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1932,125, 'Marijampoles Apskritis', 'MR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1933,125, 'Panevezio Apskritis', 'PN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1934,125, 'Sisuliu Apskritis', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1935,125, 'Taurages Apskritis', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1936,125, 'Telsiu Apskritis', 'TE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1937,125, 'Utenos Apskritis', 'UT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1938,125, 'Vilniaus Apskritis', 'VL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1939,126, 'Diekirch', 'D')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1940,126, 'Grevenmacher', 'G')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1941,126, 'Luxemburg', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1942,142, 'Stânga Nistrului', 'SN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1943,138, 'Black River', 'BL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1944,138, 'Flacq', 'FL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1945,138, 'Grand Port', 'GP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1946,138, 'Moka', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1947,138, 'Pamplemousses', 'PA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1948,138, 'Plaines Wilhems', 'PW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1949,138, 'Port Louis', 'PL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1950,138, 'Riviere du Rempart', 'RR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1951,138, 'Savanne', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1952,140, 'Aguascalientes', 'AGU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1953,140, 'Baja California', 'BCN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1954,140, 'Baja California Sur', 'BCS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1955,140, 'Campeche', 'CAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1956,140, 'Chihuahua', 'CHH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1957,140, 'Chiapas', 'CHP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1958,140, 'Coahuila', 'COA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1959,140, 'Colima', 'COL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1960,140, 'Distrito Federal', 'DIF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1961,140, 'Durango', 'DUR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1962,140, 'Guerrero', 'GRO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1963,140, 'Guanajuato', 'GUA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1964,140, 'Hidalgo', 'HID')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1965,140, 'Jalisco', 'JAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1966,140, 'Mexico', 'MEX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1967,140, 'Michoacán', 'MIC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1968,140, 'Morelos', 'MOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1969,140, 'Nayarit', 'NAY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1970,140, 'Nuevo Leon', 'NLE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1971,140, 'Oaxaca', 'OAX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1972,140, 'Puebla', 'PUE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1973,140, 'Querétaro', 'QUE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1974,140, 'Quintana Roo', 'ROO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1975,140, 'Sinaloa', 'SIN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1976,140, 'San Luis Potosí', 'SLP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1977,140, 'Sonora', 'SON')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1978,140, 'Tabasco', 'TAB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1979,140, 'Tamaulipas', 'TAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1980,140, 'Tlaxcala', 'TLA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1981,140, 'Veracruz', 'VER')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1982,140, 'Yucatan', 'YUC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1983,140, 'Zacatecas', 'ZAC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1984,131, 'Johor', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1985,131, 'Kedah', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1986,131, 'Kelantan', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1987,131, 'Melaka', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1988,131, 'Negeri Sembilan', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1989,131, 'Pahang', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1990,131, 'Perak', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1991,131, 'Perlis', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1992,131, 'Pulau Pinang', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1993,131, 'Sabah', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1994,131, 'Sarawak', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1995,131, 'Selangor', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1996,131, 'Terengganu', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1997,159, 'Abuja Capital Tercltory', 'FC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1998,159, 'Abia', 'AB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (1999,159, 'Adamawa', 'AD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2000,159, 'Akwa Ibom', 'AK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2001,159, 'Anambra', 'AN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2002,159, 'Bauchi', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2003,159, 'Benue', 'BE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2004,159, 'Borno', 'BO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2005,159, 'Cross River', 'CR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2006,159, 'Delta', 'DE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2007,159, 'Edo', 'ED')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2008,159, 'Enugu', 'EN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2009,159, 'Imc', 'IM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2010,159, 'Jigawa', 'JI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2011,159, 'Kaduna', 'KD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2012,159, 'Kano', 'KN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2013,159, 'Rataina', 'KT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2014,159, 'Kebbi', 'KE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2015,159, 'Kogi', 'KO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2016,159, 'Kwara', 'KW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2017,159, 'Lagos', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2018,159, 'Niger', 'NI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2019,159, 'Ogun', 'OG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2020,159, 'Ondo', 'ON')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2021,159, 'Osun', 'OS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2022,159, 'Oyo', 'OY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2023,159, 'Plateau', 'PL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2024,159, 'Rivers', 'RI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2025,159, 'Sokoto', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2026,159, 'Taraba', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2027,159, 'Yobe', 'YO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2028,157, 'Boaco', 'BO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2029,157, 'Carazo', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2030,157, 'Chinandega', 'CI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2031,157, 'Chontales', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2032,157, 'Estelí', 'ES')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2033,157, 'Granada', 'GR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2034,157, 'Jinotega', 'JI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2035,157, 'Leon', 'LE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2036,157, 'Madriz', 'MD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2037,157, 'Managua', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2038,157, 'Masaya', 'MS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2039,157, 'Matagalpa', 'MT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2040,157, 'Nueva Segovia', 'NS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2041,157, 'Río San Juan', 'SJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2042,157, 'Rivas', 'RI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2043,157, 'Zelaya', 'ZE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2044,153, 'Drenthe', 'DR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2045,153, 'Flevoland', 'FL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2046,153, 'Friesland', 'FR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2047,153, 'Gelderland', 'GE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2048,153, 'Groningen', 'GR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2049,153, 'Limburg', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2050,153, 'Noord Brabant', 'NB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2051,153, 'Noord Holland', 'NH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2052,153, 'Overijsl', 'OV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2053,153, 'Utrecht', 'UT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2054,153, 'Zeeland', 'ZE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2055,153, 'Zuid Holland', 'ZH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2056,164, 'Ostfold', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2057,164, 'Akershus', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2058,164, 'Oslo', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2059,164, 'Hedmark', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2060,164, 'Oppland', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2061,164, 'Buskerud', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2062,164, 'Vestfold', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2063,164, 'Telemark', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2064,164, 'Aust-Agder', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2065,164, 'Vest-Agder', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2066,164, 'Rogaland', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2067,164, 'Hordaland', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2068,164, 'Sogn og Fjordane', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2069,164, 'Møre og Romsdal', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2070,164, 'Sør-Trøndelag', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2071,164, 'Nord-Trøndelag', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2072,164, 'Nordland', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2073,164, 'Troms', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2074,164, 'Finnmark', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2075,152, 'Bagmati', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2076,152, 'Bheri', 'BH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2077,152, 'Dhawalagiri', 'DH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2078,152, 'Gandaki', 'GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2079,152, 'Janakpur', 'JA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2080,152, 'Karnali', 'KA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2081,152, 'Kosi', 'KO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2082,152, 'Lumbini', 'LU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2083,152, 'Mahakali', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2084,152, 'Mechi', 'ME')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2085,152, 'Narayani', 'NA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2086,152, 'Rapti', 'RA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2087,152, 'Sagarmatha', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2088,152, 'Seti', 'SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2089,156, 'Auckland', 'AUK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2090,156, 'Bay of Plenty', 'BOP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2091,156, 'Canterbury', 'CAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2092,156, 'Gisborne', 'GIS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2093,156, 'Hawke''s Bay', 'HKB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2094,156, 'Marlborough', 'MBH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2095,156, 'Manawatu-Wanganui', 'MWT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2096,156, 'Nelson', 'NSN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2097,156, 'Northland', 'NTL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2098,156, 'Otago', 'OTA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2099,156, 'Southland', 'STL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2100,156, 'Tasman', 'TAS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2101,156, 'Taranaki', 'TKI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2102,156, 'Wellington', 'WGN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2103,156, 'Waikato', 'WKO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2104,156, 'West Coast', 'WTC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2105,172, 'El Callao', 'CAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2106,172, 'Amazonas', 'AMA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2107,172, 'Ancash', 'ANC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2108,172, 'Apurímac', 'APU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2109,172, 'Arequipa', 'ARE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2110,172, 'Ayacucho', 'AYA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2111,172, 'Cajamarca', 'CAJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2112,172, 'Cuzco', 'CUS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2113,172, 'Huancavelica', 'HUV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2114,172, 'Huánuco', 'HUC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2115,172, 'Ica', 'ICA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2116,172, 'Junín', 'JUN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2117,172, 'La Libertad', 'LAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2118,172, 'Lambayeque', 'LAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2119,172, 'Lima', 'LIM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2120,172, 'Loreto', 'LOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2121,172, 'Madre de Dios', 'MDD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2122,172, 'Moquegua', 'MOQ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2123,172, 'Pasco', 'PAS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2124,172, 'Piura', 'PIU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2125,172, 'Puno', 'PUN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2126,172, 'San Martín', 'SAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2127,172, 'Tacna', 'TAC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2128,172, 'Tumbes', 'TUM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2129,172, 'Ucayali', 'UCA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2130,169, 'Bocas del Toro', '1')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2131,169, 'Coclé', '2')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2132,169, 'Colón', '3')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2133,169, 'Chiriqui', '4')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2134,169, 'Darién', '5')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2135,169, 'Herrera', '6')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2136,169, 'Loa Santoa', '7')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2137,169, 'Panamá', '8')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2138,169, 'Veraguas', '9')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2139,169, 'Comarca de San Blas', 'Q')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2140,166, 'Islamabad', 'IS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2141,166, 'Baluchistan (en)', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2142,166, 'North-West Frontier', 'NW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2143,166, 'Punjab', 'PB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2144,166, 'Sind (en)', 'SD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2145,166, 'Federally Administered Tribal Aresa', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2146,166, 'Azad Rashmir', 'JK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2147,166, 'Northern Areas', 'NA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2148,175, 'Bia|a Podlaska', 'BP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2149,175, 'Bia|ystok', 'BK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2150,175, 'Bielsko', 'BB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2151,175, 'Bydgoszcz', 'BY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2152,175, 'Che|m', 'CH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2153,175, 'Ciechanów', 'CI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2154,175, 'Czestochowa', 'CZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2155,175, 'Elblag', 'EL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2156,175, 'Gdansk', 'GD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2157,175, 'Gorzaw', 'GO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2158,175, 'Jelenia Góra', 'JG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2159,175, 'Kalisz', 'KL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2160,175, 'Katowice', 'KA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2161,175, 'Kielce', 'KI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2162,175, 'Konin', 'KN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2163,175, 'Koszalin', 'KO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2164,175, 'Kraków', 'KR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2165,175, 'Krosno', 'KS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2166,175, 'Legnica', 'LG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2167,175, 'Leszno', 'LE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2168,175, 'Lublin', 'LU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2169,175, 'Lomza', 'LO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2170,175, 'Lódz', 'LD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2171,175, 'Nowy Sacz', 'NS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2172,175, 'Olsztyn', 'OL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2173,175, 'Opole', 'OP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2174,175, 'Ostro|eka', 'OS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2175,175, 'Pi|a', 'PI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2176,175, 'Piotrków', 'PT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2177,175, 'P|ock', 'PL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2178,175, 'Poznañ', 'PO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2179,175, 'Przemysl', 'PR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2180,175, 'Radom', 'RA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2181,175, 'Rzeszów', 'RZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2182,175, 'Siedlce', 'SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2183,175, 'Sieradz', 'SI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2184,175, 'Skierniewice', 'SR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2185,175, 'S|upsk', 'SL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2186,175, 'Suwa|ki', 'SU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2187,175, 'Szczecin', 'SZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2188,175, 'Tarnobrzeg', 'TG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2189,175, 'Tarnów', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2190,175, 'Toruñ', 'T0')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2191,175, 'Wa~b|zych', 'WB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2192,175, 'Warazawa', 'WA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2193,175, 'W|oc|awek', 'WL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2194,175, 'Wroc|aw', 'WR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2195,175, 'Zamosc', 'ZA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2196,175, 'Zielona Góra', 'ZG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2197,176, 'Aveiro', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2198,176, 'Beja', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2199,176, 'Braga', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2200,176, 'Bragança', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2201,176, 'Castelo Branco', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2202,176, 'Colmbra', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2203,176, 'Evora', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2204,176, 'Faro', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2205,176, 'Guarda', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2206,176, 'Leiria', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2207,176, 'Lisboa', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2208,176, 'Portalegre', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2209,176, 'Porto', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2210,176, 'Santarém', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2211,176, 'Setúbal', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2212,176, 'Viana do Castelo', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2213,176, 'Vila Real', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2214,176, 'Viseu', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2215,176, 'Regiao Autonoma dos Açores', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2216,176, 'Regiao AutOnoma da Madeira', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2217,171, 'Asunción', 'PY-ASU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2218,171, 'Alto Paraguay', 'PY-16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2219,171, 'Alto Paraná', 'PY-10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2220,171, 'Amambay', 'PY-13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2221,171, 'Boquerón', 'PY-19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2222,171, 'Caeguazú', 'PY-5')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2223,171, 'Caazapl', 'PY-6')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2224,171, 'Canindeyú', 'PY-14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2225,171, 'Central', 'PY-11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2226,171, 'Concepción', 'PY-1')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2227,171, 'Cordillera', 'PY-3')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2228,171, 'Guairá', 'PY-4')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2229,171, 'Itapua', 'PY-7')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2230,171, 'Miaiones', 'PY-8')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2231,171, 'Neembucu', 'PY-12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2232,171, 'Paraguarí', 'PY-9')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2233,171, 'Presidente Hayes', 'PY-15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2234,171, 'San Pedro', 'PY-2')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2235,179, 'Bucure''ti', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2236,179, 'Alba', 'AB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2237,179, 'Arad', 'AR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2238,179, 'Arge''', 'AG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2239,179, 'Bacau', 'BC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2240,179, 'Bihor', 'BH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2241,179, 'Bistrita-Nasaud', 'BN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2242,179, 'Boto''ani', 'BT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2243,179, 'Bra''ov', 'BV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2244,179, 'Braila', 'BR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2245,179, 'Buzau', 'BZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2246,179, 'Cara''-Severin', 'CS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2247,179, 'Calarasi', 'CL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2248,179, 'Cluj', 'CJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2249,179, 'Conatarta', 'CT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2250,179, 'Covasna', 'CV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2251,179, 'Dambovita', 'DB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2252,179, 'Dolj', 'DJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2253,179, 'Galati', 'GL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2254,179, 'Giurqiu', 'GR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2255,179, 'Gorj', 'GJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2256,179, 'Harghita', 'HR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2257,179, 'Hunedoara', 'HD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2258,179, 'Ialomita', 'IL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2259,179, 'Iasi', 'IS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2260,179, 'Maramures', 'MM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2261,179, 'Mehedinti', 'MH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2262,179, 'Mures', 'MS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2263,179, 'Neamt', 'NT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2264,179, 'Olt', 'OT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2265,179, 'Prahova', 'PH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2266,179, 'Satu Mare', 'SM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2267,179, 'Salaj', 'SJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2268,179, 'Sibiu', 'SB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2269,179, 'Suceava', 'SV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2270,179, 'Teleorman', 'TR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2271,179, 'Timis', 'TM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2272,179, 'Tulcea', 'TL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2273,179, 'Vaslui', 'VS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2274,179, 'Vâlcea', 'VL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2275,179, 'Vrancea', 'VN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2276,180, 'Adygeya', 'RAD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2277,180, 'Altay', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2278,180, 'Bashkortostan', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2279,180, 'Buryatiya', 'BU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2280,180, 'Chechenskaya', 'CE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2281,180, 'Chavashskaya', 'CU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2282,180, 'Dagestan', 'DA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2283,180, 'Ingushskaya', 'IN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2284,180, 'Kabardino-Balkarskaya', 'KB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2285,180, 'Kalmykiya', 'KL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2286,180, 'Karachayevo-Cherkesskaya Respublika Karacajevo-Cerkesskaja', 'KC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2287,180, 'Kareliya', 'KR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2288,180, 'Khakasiya', 'KK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2289,180, 'Komi', 'KO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2290,180, 'Mariy El', 'ME')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2291,180, 'Mordoviya', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2292,180, 'Sakha', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2293,180, 'Severnaya Osetiya', 'SE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2294,180, 'Tatarstan', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2295,180, 'Tyva', 'TY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2296,180, 'Udmurtakaya', 'UD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2297,180, 'Altayakiy kray', 'ALT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2298,180, 'Khabarovakiy kray', 'KHA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2299,180, 'Krasnodarakiy kray', 'KDA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2300,180, 'Krasnoyarskiy kray', 'KYA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2301,180, 'Primorakiy kray', 'PRI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2302,180, 'Stavropol''skiy kray', 'STA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2303,180, 'Amurskaya', 'AMU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2304,180, 'Arkhangelakaya', 'ARR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2305,180, 'Astrakhanskaya', 'AST')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2306,180, 'Belgorodakaya', 'BEL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2307,180, 'Bryanskaya', 'BRY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2308,180, 'Chelyabinskaya', 'CHE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2309,180, 'Chitinskaya', 'CHI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2310,180, 'Irkutakaya', 'IRR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2311,180, 'Ivanovókaya', 'IVA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2312,180, 'Kaliningradskaya', 'KGD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2313,180, 'Kaluzhakaya', 'KLU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2314,180, 'Kamchatskaya', 'KAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2315,180, 'Kemerovskaya', 'KEM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2316,180, 'Kirovskaya', 'KIR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2317,180, 'Kostromskaya', 'KOS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2318,180, 'Kurganskaya', 'KGN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2319,180, 'Kurakaya', 'KRS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2320,180, 'Leningradskaya', 'LEN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2321,180, 'Lipetskaya', 'LIP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2322,180, 'Magadanskaya', 'MAG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2323,180, 'Moskovskaya', 'MOS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2324,180, 'Murmanskaya', 'MUR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2325,180, 'Nizhegorodskaya', 'NIZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2326,180, 'Novgorodskaya', 'NGR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2327,180, 'Novosibirskaya', 'NVS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2328,180, 'Omskaya', 'OMS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2329,180, 'Orenburgskaya', 'ORE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2330,180, 'Orlovskaya', 'ORL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2331,180, 'Penzenskaya', 'PNZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2332,180, 'Permskaya', 'PER')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2333,180, 'Pskovskaya', 'PSK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2334,180, 'Rostov''kaya', 'ROS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2335,180, 'Ryazanskaya', 'RYA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2336,180, 'Sakhalinskaya', 'SAK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2337,180, 'Samarskaya', 'SAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2338,180, 'Saratovskaya', 'SAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2339,180, 'Smolenskaya', 'SMO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2340,180, 'Sverdlovskaya', 'SVE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2341,180, 'Tambovskaya', 'TAM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2342,180, 'Tomskaya', 'TOM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2343,180, 'Tul''skaya', 'TUL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2344,180, 'Tverskaya', 'TVE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2345,180, 'Tyumenskaya', 'TYU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2346,180, 'Ul''yanovskaya', 'ULY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2347,180, 'Vladimirskaya', 'VLA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2348,180, 'Volgogradskaya', 'VGG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2349,180, 'Vologodskaya', 'VLG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2350,180, 'Voronezhskaya', 'VOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2351,180, 'Yaroslavskaya', 'YAR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2352,180, 'Moskva', 'MOW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2353,180, 'Sankt-Peterburg', 'SPE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2354,180, 'Yevreyskaya avtonomnaya', 'YEV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2355,180, 'Aginskiy Buryatskiy avtonomnyy', 'AGB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2356,180, 'Chukotakiy avtonomnyy okrug', 'CHU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2357,180, 'Evenkiyakiy avtonomoyy okrug', 'EVE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2358,180, 'Khanty-Mansiyskiy avtonomnyy okrug Hanty-Mansijakij avtonomnyj okrug', 'KHM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2359,180, 'Komi-Permyatskiy avtonomnyy okrug Komi-Penmjatakij avtonomnyj okrug', 'KOP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2360,180, 'Koryakskiy avtonomnyy okrug', 'KOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2361,180, 'Nenetskiy avtonomoyy okrug', 'NEN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2362,180, 'Taymyrakiy (Dolgano-Nenetskiy)', 'TAY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2363,180, 'Ust''-Ordynskiy Buryatskiy', 'U0B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2364,180, 'Yamalo-Nenetskiy avtonomnyy okrug Jamalo-Nenetskij avtonomoyj okrug', 'YAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2365,192, 'Al Batah', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2366,192, 'Al HudÜd ash Shamallyah', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2367,192, 'Al Jawf', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2368,192, 'Al Madlnah', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2369,192, 'Al Qasim', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2370,192, 'Ar Riyad', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2371,192, 'Ash Sharqlyah', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2372,192, 'Asîr', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2373,192, 'Hã''il', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2374,192, 'Jlzãn', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2375,192, 'Makkah', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2376,192, 'Najran', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2377,192, 'Tabûk', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2378,211, 'Stockholm', 'AB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2379,211, 'Uppsala', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2380,211, 'Södermanland', 'D')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2381,211, 'Ostergötland', 'E')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2382,211, 'Jönköping', 'F')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2383,211, 'Kronoberg', 'G')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2384,211, 'Kalmar', 'H')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2385,211, 'Gotland', 'I')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2386,211, 'Blekinge', 'K')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2387,211, 'Skåne', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2388,211, 'Halland', 'N')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2389,211, 'Västra Götaland', 'O')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2390,211, 'Värmland', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2391,211, 'Orebro', 'T')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2392,211, 'Västmanland', 'U')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2393,211, 'Dalarna', 'W')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2394,211, 'Gävleborg', 'X')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2395,211, 'Västernorrland', 'Y')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2396,211, 'Jämtland', 'Z')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2397,211, 'Västerbotten', 'AC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2398,211, 'Norrbotten', 'BD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2399,66, 'Ahuachapán', 'AH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2400,66, 'Cabañas', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2401,66, 'Cuscatlán', 'CU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2402,66, 'Chalatenango', 'CH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2403,66, 'La Libertad', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2404,66, 'La Paz', 'PA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2405,66, 'La Uniôn', 'UN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2406,66, 'Morazán', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2407,66, 'San Miguel', 'SM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2408,66, 'San Salvador', 'SS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2409,66, 'Santa Ana', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2410,66, 'San Vicente', 'SV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2411,66, 'Sonsonate', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2412,66, 'Usulután', 'US')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2413,213, 'Al Hasakah', 'HA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2414,213, 'Al Ladhiqiyah', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2415,213, 'Al Qunaytirah', 'QU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2416,213, 'Ar Raqqah', 'RA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2417,213, 'As Suwaydä''', 'SU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2418,213, 'Dar''ã', 'DR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2419,213, 'Dayr az Zawr', 'DY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2420,213, 'Dimashq', 'DI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2421,213, 'Halab', 'HL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2422,213, 'Hamah', 'HM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2423,213, 'Jim''', 'HI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2424,213, 'Idlib', 'ID')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2425,213, 'Rif Dimashq', 'RD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2426,213, 'Tartüs', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2427,210, 'Hhohho', 'HH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2428,210, 'Lubombo', 'LU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2429,210, 'Manzini', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2430,210, 'Shiselweni', 'SH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2431,220, 'Centre', 'TC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2432,220, 'Kara', 'TK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2433,220, 'Maritime (Région)', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2434,220, 'Plateaux', 'P')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2435,220, 'Savannes', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2436,218, 'Bangkok', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2437,218, 'Samut Prakan', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2438,218, 'Nonthaburi', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2439,218, 'Pathum Thani', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2440,218, 'Phra Nakhon Si Ayutthaya', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2441,218, 'Ang Thong', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2442,218, 'Lop Buri', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2443,218, 'Sing Buri', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2444,218, 'Chai Nat', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2445,218, 'Saraburi', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2446,218, 'Chon Buri', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2447,218, 'Rayong', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2448,218, 'Chanthaburi', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2449,218, 'Trat', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2450,218, 'Chachoengsao', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2451,218, 'Prachin Buri', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2452,218, 'Nakhon Nayok', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2453,218, 'Sa Kaeo', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2454,218, 'Nakhon Ratchasima', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2455,218, 'Buri Ram', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2456,218, 'Surin', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2457,218, 'Si Sa Ket', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2458,218, 'Ubon Ratchathani', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2459,218, 'Yasothon', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2460,218, 'Chaiyaphum', '36')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2461,218, 'Amnat Charoen', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2462,218, 'Nong Bua Lam Phu', '39')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2463,218, 'Khon Kaen', '40')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2464,218, 'Udon Thani', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2465,218, 'Loei', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2466,218, 'Nong Khai', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2467,218, 'Maha Sarakham', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2468,218, 'Roi Et', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2469,218, 'Kalasin', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2470,218, 'Sakon Nakhon', '47')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2471,218, 'Nakhon Phanom', '48')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2472,218, 'Mukdahan', '49')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2473,218, 'Chiang Mai', '50')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2474,218, 'Lamphun', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2475,218, 'Lampang', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2476,218, 'Uttaradit', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2477,218, 'Phrae', '54')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2478,218, 'Nan', '55')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2479,218, 'Phayao', '56')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2480,218, 'Chiang Rai', '57')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2481,218, 'Mae Hong Son', '58')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2482,218, 'Nakhon Sawan', '60')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2483,218, 'Uthai Thani', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2484,218, 'Kamphaeng Phet', '62')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2485,218, 'Tak', '63')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2486,218, 'Sukhothai', '64')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2487,218, 'Phitsanulok', '65')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2488,218, 'Phichit', '66')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2489,218, 'Ratchaburi', '70')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2490,218, 'Kanchanaburi', '71')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2491,218, 'Suphanburi', '72')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2492,218, 'Nakhon Pathom', '73')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2493,218, 'Samut Sakhon', '74')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2494,218, 'Samut Songkhram', '75')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2495,218, 'Phetchabun', '76')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2496,218, 'Phetchaburi', '76')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2497,218, 'Prachuap Khiri Khan', '77')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2498,218, 'Nakhon Si Thammarat', '80')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2499,218, 'Krabi', '81')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2500,218, 'Phang Nga', '82')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2501,218, 'Phuket', '83')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2502,218, 'Surat Thani', '84')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2503,218, 'Ranong', '85')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2504,218, 'Chumpon', '86')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2505,218, 'Songkhla', '90')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2506,218, 'Satun', '91')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2507,218, 'Trang', '92')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2508,218, 'Phattalung', '93')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2509,218, 'Pattani', '94')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2510,218, 'Yala', '95')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2511,218, 'Narathiwat', '96')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2512,218, 'Pattaya', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2513,216, 'Khatlon', 'KT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2514,216, 'Sughd', 'SU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2515,219, 'Aileu', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2516,219, 'Ainaro', 'AN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2517,219, 'Baucau', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2518,219, 'Bobonaro', 'BO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2519,219, 'Cova Lima', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2520,219, 'Dili', 'DI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2521,219, 'Ermera', 'ER')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2522,219, 'Lautem', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2523,219, 'Liquica', 'LI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2524,219, 'Manatuto', 'MT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2525,219, 'Manufahi', 'MF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2526,219, 'Oecussi', 'OE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2527,219, 'Viqueque', 'VI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2528,226, 'Ahal Welayaty', 'A')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2529,226, 'Balkan Welayaty', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2530,226, 'Dashhowuz Welayaty', 'D')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2531,226, 'Lebap Welayaty', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2532,226, 'Mary Welayaty', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2533,224, 'Béja', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2534,224, 'Ben Arous', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2535,224, 'Bizerte', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2536,224, 'Gabès', '81')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2537,224, 'Gafsa', '71')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2538,224, 'Jendouba', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2539,224, 'Kairouan', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2540,224, 'Rasserine', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2541,224, 'Kebili', '73')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2542,224, 'L''Ariana', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2543,224, 'Le Ref', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2544,224, 'Mahdia', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2545,224, 'Medenine', '82')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2546,224, 'Moneatir', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2547,224, 'Naboul', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2548,224, 'Sfax', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2549,224, 'Sidi Bouxid', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2550,224, 'Siliana', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2551,224, 'Sousse', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2552,224, 'Tataouine', '83')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2553,224, 'Tozeur', '72')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2554,224, 'Tunis', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2555,224, 'Zaghouan', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2556,225, 'Adana', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2557,225, 'Adiyaman', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2558,225, 'Afyon', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2559,225, 'Agrri', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2560,225, 'Aksaray', '68')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2561,225, 'Amasya', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2562,225, 'Ankara', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2563,225, 'Antalya', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2564,225, 'Ardahan', '75')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2565,225, 'Artvin', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2566,225, 'Aydin', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2567,225, 'Ballkesir', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2568,225, 'Bartln', '74')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2569,225, 'Batman', '72')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2570,225, 'Bayburt', '69')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2571,225, 'Bilecik', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2572,225, 'Bingol', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2573,225, 'Bitlis', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2574,225, 'Bolu', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2575,225, 'Burdur', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2576,225, 'Bursa', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2577,225, 'Canakkale', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2578,225, 'Cankiri', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2579,225, 'Corum', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2580,225, 'Denizli', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2581,225, 'Diyarbakir', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2582,225, 'Edirne', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2583,225, 'Elazig', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2584,225, 'Erzincan', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2585,225, 'Erzurum', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2586,225, 'Eskisehir', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2587,225, 'Gaziantep', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2588,225, 'Gireaun', '28')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2589,225, 'GümÜ''hane', '29')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2590,225, 'Hakkari', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2591,225, 'Hatay', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2592,225, 'I dlr', '76')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2593,225, 'Isparta', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2594,225, 'Içel', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2595,225, 'Ietanbul', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2596,225, 'Izmir', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2597,225, 'Kahramanmara''', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2598,225, 'Karabük', '78')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2599,225, 'Karaman', '70')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2600,225, 'Kars', '36')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2601,225, 'Kastamonu', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2602,225, 'Kayseri', '38')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2603,225, 'Klrlkkale', '71')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2604,225, 'Klrklareli', '39')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2605,225, 'Klr''ehir', '40')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2606,225, 'Kilia', '79')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2607,225, 'Kocaeli', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2608,225, 'Konya', '42')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2609,225, 'Kütabya', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2610,225, 'Malatya', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2611,225, 'Manisa', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2612,225, 'Mardin', '47')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2613,225, 'Mugla', '48')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2614,225, 'Mus', '49')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2615,225, 'Nevsehir', '50')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2616,225, 'Nigde', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2617,225, 'Ordu', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2618,225, 'Rize', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2619,225, 'Sakarya', '54')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2620,225, 'Samsun', '55')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2621,225, 'Siirt', '56')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2622,225, 'Sinop', '57')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2623,225, 'Sivas', '58')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2624,225, 'Sanliurfa', '63')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2625,225, 'Sirnak', '73')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2626,225, 'Tekirdag', '59')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2627,225, 'Tokat', '60')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2628,225, 'Trabzon', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2629,225, 'Tunceli', '62')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2630,225, 'Usak', '64')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2631,225, 'Van', '65')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2632,225, 'Yalova', '77')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2633,225, 'Yozgat', '66')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2634,225, 'Zonguldak', '67')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2635,215, 'Kaohsiung', 'KHH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2636,215, 'Taipei', 'TPE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2637,215, 'Chisyi', 'CYI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2638,215, 'Hsinchu', 'HSZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2639,215, 'Keelung', 'KEE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2640,215, 'Taichung', 'TXG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2641,215, 'Tainan', 'TNN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2642,215, 'Changhua', 'CHA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2643,215, 'Chiayi', 'CYI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2644,215, 'Hsinchu', 'HSZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2645,215, 'Hualien', 'HUA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2646,215, 'Ilan', 'ILA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2647,215, 'Kaohsiung', 'KHH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2648,215, 'Miaoli', 'MIA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2649,215, 'Nantou', 'NAN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2650,215, 'Penghu', 'PEN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2651,215, 'Pingtung', 'PIF')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2652,215, 'Taichung', 'TXG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2653,215, 'Tainan', 'TNN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2654,215, 'Taipei', 'TPE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2655,215, 'Taitung', 'TTT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2656,215, 'Taoyuan', 'TAO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2657,215, 'Yunlin', 'YUN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2658,217, 'Arusha', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2659,217, 'Dar-es-Salaam', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2660,217, 'Dodoma', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2661,217, 'Iringa', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2662,217, 'Kagera', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2663,217, 'Kaskazini Pemba', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2664,217, 'Kaskazini Unguja', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2665,217, 'Xigoma', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2666,217, 'Kilimanjaro', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2667,217, 'Rusini Pemba', '10')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2668,217, 'Kusini Unguja', '11')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2669,217, 'Lindi', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2670,217, 'Mara', '13')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2671,217, 'Mbeya', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2672,217, 'Mjini Magharibi', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2673,217, 'Morogoro', '16')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2674,217, 'Mtwara', '17')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2675,217, 'Mwanza', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2676,217, 'Pwani', '19')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2677,217, 'Rukwa', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2678,217, 'Ruvuma', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2679,217, 'Shinyanga', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2680,217, 'Singida', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2681,217, 'Tabora', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2682,217, 'Tanga', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2683,231, 'Apac', 'APA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2684,231, 'Arua', 'ARU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2685,231, 'Bundibugyo', 'BUN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2686,231, 'Bushenyi', 'BUS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2687,231, 'Gulu', 'GUL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2688,231, 'Hoima', 'HOI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2689,231, 'Iganga', 'IGA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2690,231, 'Jinja', 'JIN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2691,231, 'Kabale', 'KBL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2692,231, 'Kabarole', 'KBR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2693,231, 'Kalangala', 'KLG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2694,231, 'Kampala', 'KLA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2695,231, 'Kamuli', 'KLI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2696,231, 'Kapchorwa', 'KAP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2697,231, 'Kasese', 'KAS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2698,231, 'Kibeale', 'KLE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2699,231, 'Kiboga', 'KIB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2700,231, 'Kisoro', 'KIS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2701,231, 'Kitgum', 'KIT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2702,231, 'Kotido', 'KOT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2703,231, 'Kumi', 'KUM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2704,231, 'Lira', 'LIR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2705,231, 'Luwero', 'LUW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2706,231, 'Masaka', 'MSK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2707,231, 'Masindi', 'MSI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2708,231, 'Mbale', 'MBL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2709,231, 'Mbarara', 'MBR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2710,231, 'Moroto', 'MOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2711,231, 'Moyo', 'MOY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2712,231, 'Mpigi', 'MPI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2713,231, 'Mubende', 'MUB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2714,231, 'Mukono', 'MUK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2715,231, 'Nebbi', 'NEB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2716,231, 'Ntungamo', 'NTU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2717,231, 'Pallisa', 'PAL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2718,231, 'Rakai', 'RAK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2719,231, 'Rukungiri', 'RUK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2720,231, 'Soroti', 'SOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2721,231, 'Tororo', 'TOR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2722,232, 'Cherkas''ka', '71')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2723,232, 'Chernihivs''ka', '74')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2724,232, 'Chernivets''ka', '77')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2725,232, 'Dnipropetrovs''ka', '12')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2726,232, 'Donets''ka', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2727,232, 'Ivano-Frankivs''ka', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2728,232, 'Kharkivs''ka', '63')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2729,232, 'Khersons''ka', '65')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2730,232, 'Khmel''nyts''ka', '68')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2731,232, 'Kirovohrads''ka', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2732,232, 'Kyivs''ka', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2733,232, 'Luhans''ka', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2734,232, 'L''vivs''ka', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2735,232, 'Mykolaivs''ka', '48')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2736,232, 'Odes ''ka', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2737,232, 'Poltavs''ka', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2738,232, 'Rivnens''ka', '56')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2739,232, 'Sums ''ka', '59')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2740,232, 'Ternopil''s''ka', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2741,232, 'Vinnyts''ka', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2742,232, 'Volyos''ka', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2743,232, 'Zakarpats''ka', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2744,232, 'Zaporiz''ka', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2745,232, 'Zhytomyrs''ka', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2746,232, 'Respublika Krym', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2747,232, 'Kyïv', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2748,232, 'Sevastopol''', '40')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2749,1, 'Alaska', 'AK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2750,1, 'Alabama', 'AL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2751,1, 'Arkansas', 'AR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2752,1, 'American Samoa', 'AS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2753,1, 'Arizona', 'AZ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2754,1, 'California', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2755,1, 'Colorado', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2756,1, 'Connecticut', 'CT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2757,1, 'District of Columbia', 'DC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2758,1, 'Delaware', 'DE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2759,1, 'Florida', 'FL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2760,1, 'Georgia', 'GA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2761,1, 'Hawaii', 'HI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2762,1, 'Iowa', 'IA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2763,1, 'Idaho', 'ID')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2764,1, 'Illinois', 'IL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2765,1, 'Indiana', 'IN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2766,1, 'Kansas', 'KS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2767,1, 'Kentucky', 'KY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2768,1, 'Louisiana', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2769,1, 'Massachusetts', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2770,1, 'Maryland', 'MD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2771,1, 'Maine', 'ME')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2772,1, 'Michigan', 'MI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2773,1, 'Minnesota', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2774,1, 'Missouri', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2775,1, 'Northern Mariana Islands', 'MP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2776,1, 'Mississippi', 'MS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2777,1, 'Montana', 'MT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2778,1, 'North Carolina', 'NC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2779,1, 'North Dakota', 'ND')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2780,1, 'Nebraska', 'NE')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2781,1, 'New Hampshire', 'NH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2782,1, 'New Jersey', 'NJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2783,1, 'New Mexico', 'NM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2784,1, 'Nevada', 'NV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2785,1, 'New York', 'NY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2786,1, 'Ohio', 'OH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2787,1, 'Oklahoma', 'OK')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2788,1, 'Oregon', 'OR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2789,1, 'Pennsylvania', 'PA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2790,1, 'Rhode Island', 'RI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2791,1, 'South Carolina', 'SC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2792,1, 'South Dakota', 'SD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2793,1, 'Tennessee', 'TN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2794,1, 'Texas', 'TX')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2795,1, 'Utah', 'UT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2796,1, 'Virginia', 'VA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2797,1, 'Vermont', 'VT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2798,1, 'Washington', 'WA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2799,1, 'Wisconsin', 'WI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2800,1, 'West Virginia', 'WV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2801,1, 'Wyoming', 'WY')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2802,235, 'Artigsa', 'AR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2803,235, 'Canelones', 'CA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2804,235, 'Cerro Largo', 'CL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2805,235, 'Colonia', 'CO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2806,235, 'Durazno', 'DU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2807,235, 'Flores', 'FS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2808,235, 'Florida', 'FD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2809,235, 'Lavalleja', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2810,235, 'Maldonado', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2811,235, 'Montevideo', 'MO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2812,235, 'Paysandu', 'PA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2813,235, 'Rio Negro', 'RN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2814,235, 'Rivera', 'RV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2815,235, 'Rocha', 'RO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2816,235, 'Salto', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2817,235, 'San José', 'SJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2818,235, 'Soriano', 'SO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2819,235, 'Tacuarembo', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2820,235, 'Treinta y Tres', 'TT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2821,236, 'Andijon', 'AN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2822,236, 'Buxoro', 'BU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2823,236, 'Farg''ona', 'FA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2824,236, 'Jizzax', 'JI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2825,236, 'Namangan', 'NG')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2826,236, 'Navoiy', 'NW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2827,236, 'Qashqadaryo', 'QA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2828,236, 'Samarqand', 'SA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2829,236, 'Sirdaryo', 'SI')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2830,236, 'Surxondaryo', 'SU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2831,236, 'Toshkent', 'TO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2832,236, 'Xorazm', 'XO')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2833,239, 'Amazonas', 'Z')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2834,239, 'Anzoategui', 'B')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2835,239, 'Apure', 'C')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2836,239, 'Aragua', 'D')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2837,239, 'Barinas', 'E')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2838,239, 'Bolivar', 'F')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2839,239, 'Carabobo', 'G')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2840,239, 'Cojedes', 'H')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2841,239, 'Delta Amacuro', 'Y')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2842,239, 'Falcon', 'I')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2843,239, 'Guarico', 'J')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2844,239, 'Lara', 'K')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2845,239, 'Merida', 'L')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2846,239, 'Miranda', 'M')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2847,239, 'Monagas', 'N')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2848,239, 'Nueva Esparta', 'O')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2849,239, 'Portuguesa', 'P')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2850,239, 'Sucre', 'R')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2851,239, 'Tachira', 'S')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2852,239, 'Trujillo', 'T')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2853,239, 'Vargas', 'X')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2854,239, 'Yaracuy', 'U')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2855,239, 'Zulia', 'V')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2856,239, 'Federal District', 'A')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2857,239, 'Federal Dependency', 'W')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2858,240, 'An Giang', '44')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2859,240, 'Bac Can', '53')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2860,240, 'Bac Giang', '54')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2861,240, 'Bac Lieu', '55')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2862,240, 'Bac Ninh', '56')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2863,240, 'Ba Ria - Vung Tau', '43')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2864,240, 'Ben Tre', '50')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2865,240, 'Binh Dinh', '31')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2866,240, 'Binh Duong', '57')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2867,240, 'Binh Phuoc', '58')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2868,240, 'Binh Thuan', '40')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2869,240, 'Ca Mau', '59')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2870,240, 'Can Tho', '48')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2871,240, 'Cao Bang', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2872,240, 'Dac Lac', '33')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2873,240, 'Dong Nai', '39')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2874,240, 'Dong Thap', '45')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2875,240, 'Gia Lai', '30')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2876,240, 'Ha Giang', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2877,240, 'Hai Duong', '61')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2878,240, 'Hai Phong thanh pho', '62')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2879,240, 'Ha Nam', '63')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2880,240, 'Ha Noi thu do', '64')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2881,240, 'Ha Tay', '15')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2882,240, 'Ha Tinh', '23')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2883,240, 'Hoa Binh', '14')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2884,240, 'Ho Chi Mlnh thanh po', '65')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2885,240, 'Hung Yen', '66')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2886,240, 'Khanh Hoa', '34')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2887,240, 'Kien Giang', '47')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2888,240, 'Kon Tum', '28')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2889,240, 'Lai Chau', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2890,240, 'Lam Dong', '35')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2891,240, 'Lang Son', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2892,240, 'Lao Cai', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2893,240, 'Long An', '41')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2894,240, 'Nam Dinh', '67')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2895,240, 'Nghe An', '22')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2896,240, 'Ninh Binh', '18')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2897,240, 'Ninh Thuan', '36')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2898,240, 'Phu Tho', '68')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2899,240, 'Phu Yen', '32')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2900,240, 'Quang Binh', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2901,240, 'Quang Nam', '27')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2902,240, 'Quang Ngai', '29')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2903,240, 'Quang Ninh', '24')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2904,240, 'Quang Tri', '25')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2905,240, 'Soc Trang', '52')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2906,240, 'Son La', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2907,240, 'Tay Ninh', '37')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2908,240, 'Thai Binh', '20')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2909,240, 'Thai Nguyen', '69')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2910,240, 'Thanh Hoa', '21')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2911,240, 'Thua Thien-Hue', '26')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2912,240, 'Tien Giang', '46')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2913,240, 'Tra Vinh', '51')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2914,240, 'Tuyen Quang', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2915,240, 'Vinh Long', '49')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2916,240, 'Vinh Yen', '70')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2917,240, 'Yen Bai', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2918,243, 'Abyan', 'AB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2919,243, 'Adan', 'AD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2920,243, 'Amran', 'AM')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2921,243, 'Al Bayda', 'BA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2922,243, 'Ad Dali', 'DA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2923,243, 'Dhamar', 'DH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2924,243, 'Hadramawt', 'HD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2925,243, 'Hajjah', 'HJ')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2926,243, 'Al Hudaydah', 'HU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2927,243, 'Ibb', 'IB')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2928,243, 'Al Jawf', 'JA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2929,243, 'Lahij', 'LA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2930,243, 'Ma''rib', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2931,243, 'Al Mahrah', 'MR')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2932,243, 'Al Mahwit', 'MW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2933,243, 'Sa''dah', 'SD')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2934,243, 'San''a', 'SN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2935,243, 'Shabwah', 'SH')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2936,243, 'Ta''izz', 'TA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2937,202, 'Eastern Cape', 'EC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2938,202, 'Free State', 'FS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2939,202, 'Gauteng', 'GT')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2940,202, 'Kwazulu-Natal', 'NL')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2941,202, 'Mpumalanga', 'MP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2942,202, 'Northern Cape', 'NC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2943,202, 'Northern Province', 'NP')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2944,202, 'North-West', 'NW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2945,202, 'Western Cape', 'WC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2946,244, 'Central', '02')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2947,244, 'Copperbelt', '08')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2948,244, 'Eastern', '03')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2949,244, 'Luapula', '04')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2950,244, 'Lusaka', '09')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2951,244, 'Northern', '05')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2952,244, 'North-Western', '06')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2953,244, 'Southern', '07')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2954,244, 'Western', '01')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2955,245, 'Bulawayo', 'BU')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2956,245, 'Harare', 'HA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2957,245, 'Manicaland', 'MA')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2958,245, 'Mashonaland Central', 'MC')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2959,245, 'Mashonaland East', 'ME')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2960,245, 'Mashonaland West', 'MW')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2961,245, 'Masvingo', 'MV')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2962,245, 'Matabeleland North', 'MN')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2963,245, 'Matabeleland South', 'MS')";
		$sql[] = "INSERT INTO `exp_br_state` VALUES (2964,245, 'Midlands', 'MI')";

## ----------------------------
##  Table structure for exp_br_store
## ----------------------------

	$sql[] = "DROP TABLE IF EXISTS exp_br_store;";
	$sql[] = "	CREATE TABLE exp_br_store (
					store_id int(11) NOT NULL AUTO_INCREMENT,
					site_id int(11) NOT NULL DEFAULT '1',
					logo varchar(100) NOT NULL DEFAULT 'logo.png',
					license varchar(255) NOT NULL,
					phone varchar(255) NOT NULL,
					address1 varchar(100) NOT NULL,
					address2 varchar(100) NOT NULL,
					city varchar(100) NOT NULL,
					state varchar(50) NOT NULL,
					country varchar(50) NOT NULL,
					zipcode varchar(50) NOT NULL,
					fax varchar(50) NOT NULL,
					currency_id int(11) NOT NULL DEFAULT '1',
					result_limit int(11) NOT NULL DEFAULT '96',
					result_per_page int(11) NOT NULL DEFAULT '12',
					result_paginate int(11) NOT NULL DEFAULT '5',
					register_group int(11) NOT NULL DEFAULT '5',
					guest_checkout int(11) NOT NULL DEFAULT '1',
					media_url varchar(255) NOT NULL,
					media_dir varchar(255) NOT NULL,
					meta_title varchar(100) NOT NULL,
					meta_keywords varchar(100) NOT NULL,
					meta_descr varchar(255) NOT NULL,
					donation_enabled int(11) NOT NULL DEFAULT '0', 
					subscription_enabled int(11) NOT NULL DEFAULT '0',
					first_notice int(11) NOT NULL DEFAULT '7',
					second_notice int(11) NOT NULL DEFAULT '14',
					third_notice int(11) NOT NULL DEFAULT '21',
					cancel_subscription int(11) NOT NULL DEFAULT '28',
					secure_url varchar(150) NOT NULL, 
					cart_url varchar(100) NOT NULL DEFAULT 'cart',
					checkout_url varchar(100) NOT NULL DEFAULT 'checkout', 
					thankyou_url varchar(100) NOT NULL DEFAULT 'checkout/thankyou', 
					customer_url varchar(100) NOT NULL DEFAULT 'customer', 
					product_url varchar(100) NOT NULL DEFAULT 'product',
					low_stock int(11) NOT NULL, 
					PRIMARY KEY (store_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				
## ----------------------------
##  Records of exp_br_store
## ----------------------------
	$path = rtrim(realpath(APPPATH.'/../../'),'/').DIRECTORY_SEPARATOR.'media';
	$sql[] = "INSERT INTO exp_br_store VALUES 
				('1', '1', 'logo.png', '', '(888) 555-5555', '12207 Wilshire Blvd', 'Suite 201', 'Los Angeles', 'CA', 'USA', '90025', '(888) 555-5555', '1', '96', '12', '5', '5', '1', 'http://".$_SERVER["HTTP_HOST"]."/media/','".$path."','','','',0,0,7,14,21,28,'http://".$_SERVER["HTTP_HOST"]."','cart','checkout','checkout/thankyou','customer','product','25')";
				
## ----------------------------
##  Table structure for exp_br_tax
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_tax;";
	$sql[] = "CREATE TABLE exp_br_tax (
  tax_id int(11) NOT NULL AUTO_INCREMENT,
  site_id int(11) NOT NULL DEFAULT '1',
  title varchar(50) NOT NULL,
  zone_id int(11) NOT NULL,
  state_id int(11) NOT NULL,
  zipcode text NULL, 
  rate float(10,4) NOT NULL,
  sort int(11) NOT NULL,
  PRIMARY KEY (tax_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

	## ----------------------------
	##  Records of exp_br_tax
	## ----------------------------
		$sql[] = "INSERT INTO exp_br_tax VALUES ('1','1','CA Sales Tax', '1', '2754','','8.75', '1')";
		
	## ----------------------------
	##  Table structure for exp_br_zone
	## ----------------------------
		$sql[] = "DROP TABLE IF EXISTS exp_br_zone;";
		$sql[] = "CREATE TABLE exp_br_zone (
	  zone_id int(11) NOT NULL AUTO_INCREMENT,
	  title varchar(100) NOT NULL,
	  code varchar(10) NOT NULL,
	  enabled int(11) DEFAULT 0, 
	  PRIMARY KEY (zone_id)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Records of exp_br_zone
## ----------------------------
	$sql[] = "INSERT INTO exp_br_zone VALUES 
				('1', 'United States', 'US',1), 
				('2', 'Afghanistan', 'AF',0), 
				('3', 'Albania', 'AL',0), 
				('4', 'Algeria', 'DZ',0), 
				('5', 'American Samoa', 'AS',0), 
				('6', 'Andorra', 'AD',0), 
				('7', 'Angola', 'AO',0), 
				('8', 'Anguilla', 'AI',0), 
				('9', 'Antarctica', 'AQ',0), 
				('10', 'Antigua and Barbuda', 'AG',0), 
				('11', 'Argentina', 'AR',0), 
				('12', 'Armenia', 'AM',0), 
				('13', 'Aruba', 'AW',0), 
				('14', 'Australia', 'AU',0), 
				('15', 'Austria', 'AT',0), 
				('16', 'Azerbaijan', 'AZ',0), 
				('17', 'Bahamas', 'BS',0), 
				('18', 'Bahrain', 'BH',0), 
				('19', 'Bangladesh', 'BD',0), 
				('20', 'Barbados', 'BB',0), 
				('21', 'Belarus', 'BY',0), 
				('22', 'Belgium', 'BE',0), 
				('23', 'Belize', 'BZ',0), 
				('24', 'Benin', 'BJ',0), 
				('25', 'Bermuda', 'BM',0), 
				('26', 'Bhutan', 'BT',0), 
				('27', 'Bolivia', 'BO',0), 
				('28', 'Bosnia and Herzegovina', 'BA',0), 
				('29', 'Botswana', 'BW',0), 
				('30', 'Bouvet Island', 'BV',0), 
				('31', 'Brazil', 'BR',0), 
				('32', 'British Indian Ocean Territory', 'IO',0), 
				('33', 'British Virgin Islands', 'VG',0), 
				('34', 'Brunei', 'BN',0), 
				('35', 'Bulgaria', 'BG',0), 
				('36', 'Burkina Faso', 'BF',0), 
				('37', 'Burundi', 'BI',0), 
				('38', 'Cambodia', 'KH',0), 
				('39', 'Cameroon', 'CM',0), 
				('40', 'Canada', 'CA',0), 
				('41', 'Cape Verde', 'CV',0), 
				('42', 'Cayman Islands', 'KY',0), 
				('43', 'Central African Republic', 'CF',0), 
				('44', 'Chad', 'TD',0), 
				('45', 'Chile', 'CL',0), 
				('46', 'China', 'CN',0), 
				('47', 'Christmas Island', 'CX',0), 
				('48', 'Cocos [Keeling] Islands', 'CC',0), 
				('49', 'Colombia', 'CO',0), 
				('50', 'Comoros', 'KM',0), 
				('51', 'Congo - Brazzaville', 'CG',0), 
				('52', 'Congo - Kinshasa', 'CD',0), 
				('53', 'Cook Islands', 'CK',0), 
				('54', 'Costa Rica', 'CR',0), 
				('55', 'Croatia', 'HR',0), 
				('56', 'Cuba', 'CU',0), 
				('57', 'Cyprus', 'CY',0), 
				('58', 'Czech Republic', 'CZ',0), 
				('59', 'Cote d’Ivoire', 'CI',0), 
				('60', 'Denmark', 'DK',0), 
				('61', 'Djibouti', 'DJ',0), 
				('62', 'Dominica', 'DM',0), 
				('63', 'Dominican Republic', 'DO',0), 
				('64', 'Ecuador', 'EC',0), 
				('65', 'Egypt', 'EG',0), 
				('66', 'El Salvador', 'SV',0), 
				('67', 'Equatorial Guinea', 'GQ',0), 
				('68', 'Eritrea', 'ER',0), 
				('69', 'Estonia', 'EE',0), 
				('70', 'Ethiopia', 'ET',0), 
				('71', 'Falkland Islands', 'FK',0), 
				('72', 'Faroe Islands', 'FO',0), 
				('73', 'Fiji', 'FJ',0), 
				('74', 'Finland', 'FI',0), 
				('75', 'France', 'FR',0), 
				('76', 'French Guiana', 'GF',0), 
				('77', 'French Polynesia', 'PF',0), 
				('78', 'French Southern Territories', 'TF',0), 
				('79', 'Gabon', 'GA',0), 
				('80', 'Gambia', 'GM',0), 
				('81', 'Georgia', 'GE',0), 
				('82', 'Germany', 'DE',0), 
				('83', 'Ghana', 'GH',0), 
				('84', 'Gibraltar', 'GI',0), 
				('85', 'Greece', 'GR',0), 
				('86', 'Greenland', 'GL',0), 
				('87', 'Grenada', 'GD',0), 
				('88', 'Guadeloupe', 'GP',0), 
				('89', 'Guam', 'GU',0), 
				('90', 'Guatemala', 'GT',0), 
				('92', 'Guinea', 'GN',0), 
				('93', 'Guinea-Bissau', 'GW',0), 
				('94', 'Guyana', 'GY',0), 
				('95', 'Haiti', 'HT',0), 
				('96', 'Heard Island and McDonald Islands', 'HM',0), 
				('97', 'Honduras', 'HN',0), 
				('98', 'Hong Kong SAR China', 'HK',0), 
				('99', 'Hungary', 'HU',0), 
				('100', 'Iceland', 'IS',0), 
				('101', 'India', 'IN',0), 
				('102', 'Indonesia', 'ID',0), 
				('103', 'Iran', 'IR',0), 
				('104', 'Iraq', 'IQ',0), 
				('105', 'Ireland', 'IE',0), 
				('106', 'Isle of Man', 'IM',0), 
				('107', 'Israel', 'IL',0), 
				('108', 'Italy', 'IT',0), 
				('109', 'Jamaica', 'JM',0), 
				('110', 'Japan', 'JP',0), 
				('112', 'Jordan', 'JO',0), 
				('113', 'Kazakhstan', 'KZ',0), 
				('114', 'Kenya', 'KE',0), 
				('115', 'Kiribati', 'KI',0), 
				('116', 'Kuwait', 'KW',0), 
				('117', 'Kyrgyzstan', 'KG',0), 
				('118', 'Laos', 'LA',0), 
				('119', 'Latvia', 'LV',0), 
				('120', 'Lebanon', 'LB',0), 
				('121', 'Lesotho', 'LS',0), 
				('122', 'Liberia', 'LR',0), 
				('123', 'Libya', 'LY',0), 
				('124', 'Liechtenstein', 'LI',0), 
				('125', 'Lithuania', 'LT',0), 
				('126', 'Luxembourg', 'LU',0), 
				('127', 'Macau SAR China', 'MO',0), 
				('128', 'Macedonia', 'MK',0), 
				('129', 'Madagascar', 'MG',0), 
				('130', 'Malawi', 'MW',0), 
				('131', 'Malaysia', 'MY',0), 
				('132', 'Maldives', 'MV',0), 
				('133', 'Mali', 'ML',0), 
				('134', 'Malta', 'MT',0), 
				('135', 'Marshall Islands', 'MH',0), 
				('136', 'Martinique', 'MQ',0), 
				('137', 'Mauritania', 'MR',0), 
				('138', 'Mauritius', 'MU',0), 
				('139', 'Mayotte', 'YT',0), 
				('140', 'Mexico', 'MX',0), 
				('141', 'Micronesia', 'FM',0), 
				('142', 'Moldova', 'MD',0), 
				('143', 'Monaco', 'MC',0), 
				('144', 'Mongolia', 'MN',0), 
				('146', 'Montserrat', 'MS',0), 
				('147', 'Morocco', 'MA',0), 
				('148', 'Mozambique', 'MZ',0), 
				('149', 'Myanmar [Burma]', 'MM',0), 
				('150', 'Namibia', 'NA',0), 
				('151', 'Nauru', 'NR',0), 
				('152', 'Nepal', 'NP',0), 
				('153', 'Netherlands', 'NL',0), 
				('154', 'Netherlands Antilles', 'AN',0), 
				('155', 'New Caledonia', 'NC',0), 
				('156', 'New Zealand', 'NZ',0), 
				('157', 'Nicaragua', 'NI',0), 
				('158', 'Niger', 'NE',0), 
				('159', 'Nigeria', 'NG',0), 
				('160', 'Niue', 'NU',0), 
				('161', 'Norfolk Island', 'NF',0), 
				('162', 'North Korea', 'KP',0), 
				('163', 'Northern Mariana Islands', 'MP',0), 
				('164', 'Norway', 'NO',0), 
				('165', 'Oman', 'OM',0), 
				('166', 'Pakistan', 'PK',0), 
				('167', 'Palau', 'PW',0), 
				('168', 'Palestinian Territories', 'PS',0), 
				('169', 'Panama', 'PA',0), 
				('170', 'Papua New Guinea', 'PG',0), 
				('171', 'Paraguay', 'PY',0), 
				('172', 'Peru', 'PE',0), 
				('173', 'Philippines', 'PH',0), 
				('174', 'Pitcairn Islands', 'PN',0), 
				('175', 'Poland', 'PL',0), 
				('176', 'Portugal', 'PT',0), 
				('177', 'Puerto Rico', 'PR',0), 
				('178', 'Qatar', 'QA',0), 
				('179', 'Romania', 'RO',0), 
				('180', 'Russia', 'RU',0), 
				('181', 'Rwanda', 'RW',0), 
				('182', 'Reunion', 'RE',0), 
				('184', 'Saint Helena', 'SH',0), 
				('185', 'Saint Kitts and Nevis', 'KN',0), 
				('186', 'Saint Lucia', 'LC',0), 
				('188', 'Saint Pierre and Miquelon', 'PM',0), 
				('189', 'Saint Vincent and the Grenadines', 'VC',0), 
				('190', 'Samoa', 'WS',0), 
				('191', 'San Marino', 'SM',0), 
				('192', 'Saudi Arabia', 'SA',0), 
				('193', 'Senegal', 'SN',0), 
				('195', 'Seychelles', 'SC',0), 
				('196', 'Sierra Leone', 'SL',0), 
				('197', 'Singapore', 'SG',0), 
				('198', 'Slovakia', 'SK',0), 
				('199', 'Slovenia', 'SI',0), 
				('200', 'Solomon Islands', 'SB',0), 
				('201', 'Somalia', 'SO',0), 
				('202', 'South Africa', 'ZA',0), 
				('203', 'South Georgia and the South Sandwich Islands', 'GS',0), 
				('204', 'South Korea', 'KR',0), 
				('205', 'Spain', 'ES',0), 
				('206', 'Sri Lanka', 'LK',0), 
				('207', 'Sudan', 'SD',0), 
				('208', 'Suriname', 'SR',0), 
				('209', 'Svalbard and Jan Mayen', 'SJ',0), 
				('210', 'Swaziland', 'SZ',0), 
				('211', 'Sweden', 'SE',0), 
				('212', 'Switzerland', 'CH',0), 
				('213', 'Syria', 'SY',0), 
				('214', 'Sao Tome and Principe', 'ST',0), 
				('215', 'Taiwan', 'TW',0), 
				('216', 'Tajikistan', 'TJ',0), 
				('217', 'Tanzania', 'TZ',0), 
				('218', 'Thailand', 'TH',0), 
				('219', 'Timor-Leste', 'TL',0), 
				('220', 'Togo', 'TG',0), 
				('221', 'Tokelau', 'TK',0), 
				('222', 'Tonga', 'TO',0), 
				('223', 'Trinidad and Tobago', 'TT',0), 
				('224', 'Tunisia', 'TN',0), 
				('225', 'Turkey', 'TR',0), 
				('226', 'Turkmenistan', 'TM',0), 
				('227', 'Turks and Caicos Islands', 'TC',0), 
				('228', 'Tuvalu', 'TV',0), 
				('229', 'U.S. Minor Outlying Islands', 'UM',0), 
				('230', 'U.S. Virgin Islands', 'VI',0), 
				('231', 'Uganda', 'UG',0), 
				('232', 'Ukraine', 'UA',0), 
				('233', 'United Arab Emirates', 'AE',0), 
				('234', 'United Kingdom', 'GB',0), 
				('235', 'Uruguay', 'UY',0), 
				('236', 'Uzbekistan', 'UZ',0), 
				('237', 'Vanuatu', 'VU',0), 
				('238', 'Vatican City', 'VA',0), 
				('239', 'Venezuela', 'VE',0), 
				('240', 'Vietnam', 'VN',0), 
				('241', 'Wallis and Futuna', 'WF',0), 
				('242', 'Western Sahara', 'EH',0), 
				('243', 'Yemen', 'YE',0), 
				('244', 'Zambia', 'ZM',0), 
				('245', 'Zimbabwe', 'ZW',0), 
				('246', 'Aland Islands', 'AX',0);";	

## ----------------------------
##  Table structure for exp_br_product_feeds
## ----------------------------
		
	$sql[] = "CREATE TABLE exp_br_product_feeds (
				  product_feed_id int(11) NOT NULL AUTO_INCREMENT,
				  product_id int(11) NOT NULL DEFAULT '0',
				  feed_id int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (product_feed_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	$sql[] = "INSERT INTO exp_br_product_feeds (product_id,feed_id) VALUES ('2519','1'),('2523','1'),('2516','1'),('2517','1');";
	
## ----------------------------
##  Table structure for exp_br_feeds
## ----------------------------

	$sql[] = "CREATE TABLE exp_br_feeds (
				  feed_id int(11) NOT NULL AUTO_INCREMENT,
				  feed_title varchar(128) NOT NULL DEFAULT '',
				  feed_code varchar(128) NOT NULL DEFAULT '',
				  PRIMARY KEY (feed_id),
				  UNIQUE (feed_code)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";	
	
	$sql[] = "INSERT INTO exp_br_feeds (feed_title,feed_code) VALUES ('Google Base','google_base');";
	
## ----------------------------
##  Table structure for exp_br_cron
## ----------------------------

	$sql[] = "CREATE TABLE exp_br_cron (
				  cron_id int(11) NOT NULL AUTO_INCREMENT,
				  status int(11) NOT NULL DEFAULT '0',
				  class varchar(128) NOT NULL DEFAULT '',
				  method varchar(128) NOT NULL DEFAULT '',
				  params text,
				  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (cron_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

## ----------------------------
##  Table structure for exp_br_wishlist
## ----------------------------

	$sql[] = "DROP TABLE IF EXISTS exp_br_wishlist;";
	$sql[] = "CREATE TABLE exp_br_wishlist (
					wishlist_id int(11) NOT NULL AUTO_INCREMENT,
					member_id int(11) NOT NULL DEFAULT '0',
					is_public int(11) NOT NULL DEFAULT '0',
					created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					product_id int(11) NOT NULL,
					notes text,
					PRIMARY KEY (wishlist_id),
					KEY index_wishlist (member_id)
				) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
				
## ----------------------------
##  Table structure for exp_br_wishlist_hash
## ----------------------------
	$sql[] = "DROP TABLE IF EXISTS exp_br_wishlist_hash;";
	$sql[] = "CREATE TABLE exp_br_wishlist_hash (
					wishlist_hash_id int(11) NOT NULL auto_increment,
					member_id int(11) NOT NULL,
					hash varchar(255) NOT NULL,
					PRIMARY KEY  (wishlist_hash_id),
					UNIQUE KEY member_id (member_id),
					UNIQUE KEY hash (hash)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

		foreach ($sql as $query)
		{
			$this->EE->db->query($query);
		}
		
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Module Uninstaller
	 *
	 * @access	public
	 * @return	bool
	 */	
	function uninstall() 
	{
		// Clean up the database
			$query = $this->EE->db->query("SELECT module_id FROM exp_modules WHERE module_name = 'Brilliant_retail'"); 
					
			$sql[] = "DELETE FROM exp_module_member_groups WHERE module_id = '".$query->row('module_id') ."'";		
			$sql[] = "DELETE FROM exp_modules WHERE module_name = 'Brilliant_retail'";
			$sql[] = "DELETE FROM exp_actions WHERE class = 'Brilliant_retail'";
			$sql[] = "DELETE FROM exp_actions WHERE class = 'Brilliant_retail_mcp'";
			$sql[] = "DROP TABLE IF EXISTS exp_br_admin_access;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_attribute;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_attribute_set;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_attribute_set_attribute;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_cart;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_category;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_config;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_config_data;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_cron";
			$sql[] = "DROP TABLE IF EXISTS exp_br_currencies;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_email;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_feeds;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_log;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order_address;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order_download;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order_item;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order_note;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order_options;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order_payment;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order_ship;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_order_subscription;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_addon;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_attributes;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_bundle;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_category;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_configurable;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_download;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_feeds;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_images;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_price;;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_options;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_related;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_product_subscription;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_promo;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_search;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_state;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_store;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_tax;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_wishlist;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_wishlist_hash;";
			$sql[] = "DROP TABLE IF EXISTS exp_br_zone;";
		
		foreach ($sql as $query)
		{
			$this->EE->db->query($query);
		}
		
		// As of 1.0.4.5 we are no longer going to update the base install above.
		// I'm not sure why the absolute genius David Dexter did it that way before
		// but lets just include the incrementals after base install to keep things 
		// consistent. - dpd
		
			$updates = array(1045);
			
			// better unset the previous queries
				foreach($updates as $u){
					$sql = array();
					// we are going to do each 'update' so that if one is dependent on another we don't have
					// any problems					
						$fl = PATH_THIRD.'brilliant_retail'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'update'.DIRECTORY_SEPARATOR.$i.'.php';	
						include($f);
					// Run DB updates
						foreach ($sql as $query){
							$this->EE->db->query($query);
						}
				}
			$this->reset_cache();

		return TRUE;
	}

	
	// --------------------------------------------------------------------

	/**
	 * Module Updater
	 *
	 * @access	public
	 * @return	bool
	 */	
	function update($current = '')
	{
		$this->EE->load->dbforge();
		
		// Required for updating member fields
			$sql = array();
		
		// Get the current version
			$version = str_replace(".","",$this->version)*1;
		
		// The first update file should be point above current
		
			$curr = (str_replace(".","",$current)*1);
		
		// Do we need to do any updates?
			if($curr < $version){
				$start = $curr + 1;
				
				// Loop through possible udate files
					for($i = $start; $i <= $version; $i++){
						$fl = PATH_THIRD.'brilliant_retail'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'update'.DIRECTORY_SEPARATOR.$i.'.php';
						if(file_exists($fl)){
							include($fl);
						}
					}
		
				// Run DB updates
					foreach ($sql as $query){
						$this->EE->db->query($query);
					}
		
				// Clear the cache settings file so that the system 
				// is forced to rebuild it
					$this->reset_cache();
			}
		return TRUE;
	}
	
	function reset_cache(){
		$files = $this->read_dir_files(APPPATH.'cache/brilliant_retail');
		foreach($files as $f){
			$nm = APPPATH.'cache/brilliant_retail/'.$f;
			if(file_exists($nm)){
				unlink($nm);
			}
		}
	}
	
	function read_dir_files($directory){
		$arr = array();
		if(file_exists($directory)){
			$dir = opendir($directory);
			while(false != ($file = readdir($dir))){
				if(($file != ".") && ($file != "..") && $file != 'search'){
					$arr[] = $file;
				}
			}
		}
		return $arr;
	}
}