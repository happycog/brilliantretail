# Davids working list. 

Just some little notes for me. Since I'm the only one looking these days. 

### Channel Fields

* Create field group per site and add the group id to new channels
* Create channel per site and add channel_id to the store configuration array

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