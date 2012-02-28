# Davids working list. 

Just some little notes for me. Since I'm the only one looking these days. 

### Need to add a browse function for download files that are present in media/uploads
### Need to install notifications
### Need to update copyright throughout from 2011 to 2010-2012 
### Category expand/collapse: http://checkboxtree.googlecode.com/svn/tags/checkboxtree-0.5.2/index.html

### Price Display 

* Handle non-existing pricing for member groups to products. 

### Channel Fields

* Create field group per site and add the group id to new channels
* Create channel per site and add channel_id to the store configuration array
* Need to add product_entry table to install upd file. 
* Need to add product_entry rows to install upd file. 

### Product Edit Page

* Delete product needs to remove entries from:
	
	* br_product_feeds 
	* br_product_configurable 
	* br_product_price 
	* br_product_entry
	* channel_titles
	* channel_data
	
* Add an edit page to hide or order product detail page entry fields
 
### Order Overview

* Update status
* Batch Print

### State / Country

* Need to update changes sent in ticket system

### Hidden Variables

* br_hide_blank_option - Remove the blank option from the configurable / option dropdowns
* br_filter_power - Adjust the power on the price range buckets 

# Changelog

Removed _clean_search_term in favor of ExpressionEngine native sanitize_search_terms() method
Updated model > order_model > get_order_collection method with proper left join to remove existing member requirement. 
Updated model > order_model > get_order with proper right join to remove existing member requirement. 
Updated model > order_model > create_shipment/create_order_address/create_order_download/create_order_payment/create_order_item to return insert_id
Updated UPS shipping model to return rates in order desc. 
Updated Control Panel success and failure messages to use EE system messages
Updated the related product tag to properly request the _get_product core method instead of the product model
Updated USPS to Rates v3 API
Updated control panel report method to use the read_system_files helper to build file list
Updated product bundle search field
Fixed issue with USPS First Class International Package rate
Fixed issue with checkout create downloads (check for bug report)
Fixed issue when download products are contained within bundles
Added option_count to product > option tag pair
Added has_options tag (TRUE/FALSE)
Added on_sale flag to the product tag
Added mode, sort, and dir parameters to catalog tag
Added private note option to admin order detail
Added nl2br spacing on order notes
Added _local language override and removed code from package language file 
Added support for custom language files in _local directory 
Added support for custom report files in the _local directory 
Added multiple modes to image tag (matte,scale,fit)
Added watermark parameter to image tag (optional)
Added reflection parameter to image tag (optional)