# Davids working list. 

Just some little notes for me. Since I'm the only one looking these days. 

### General 

* Handle non-existing pricing for member groups to products. 
* Add the on_sale flag to product 

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

# Changelog

Removed _clean_search_term in favor of ExpressionEngine native sanitize_search_terms() method
Updated model > order_model > get_order_collection method with proper left join to remove existing member requirement. 
Updated model > order_model > get_order with proper right join to remove existing member requirement. 
Updated model > order_model > create_shipment/create_order_address/create_order_download/create_order_payment/create_order_item to return insert_id
Updated UPS shipping model to return rates in order desc. 
Updated Control Panel success and failure messages to use EE system messages
Added option_count to product > option tag pair
Added has_options tag (TRUE/FALSE)