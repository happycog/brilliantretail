# BUG REPORT CHANGE LOG # 

* Added cart discounts to promos. [#0000122]
* Added New BR Poe Fieldtype [#0000118]
* Fixed Discount error when logged in as super admin [#0000138]
* Fixed WYSIWYG Editor Image Error [#0000114]
* Fixed checkout "email you entered doesn't appear to be valid" [#0000136]
* Fixed Site ID missing from get_product_collection [#0000133]
* Fixed Disabled Promo Still Working. [#0000130]
* Fixed Search error if my search contains less than 3 characters. [#0000127]
* Fixed Zero subtotal with tax // shipping cost still shows no_payment gateway. [#0000123]
* Fixed Email validation errors (dpdexter) - resolved. [#0000117]
* Fixed the product file fieldtype to not validate the file input as 'required' when a selection exists [#0000162]
* Updated Rename fieldtype to BrilliantRetail Products. [#0000116]
* Updated CKEditor to the latest release. [#0000115]


Updated the USPS module to use the new USPS V4 API
Updated the eWay Gateway to add the CVN input field
Updated product edit form to allow users to change product type. 
Updated model > order_model > get_order_collection method with proper left join to remove existing member requirement. 
Updated model > order_model > get_order with proper right join to remove existing member requirement. 
Updated model > order_model > create_shipment/create_order_address/create_order_download/create_order_payment/create_order_item to return insert_id
Updated UPS shipping model to return rates in order desc. 
Updated Control Panel success and failure messages to use EE system messages
Updated the related product tag to properly request the _get_product core method instead of the product model
Updated USPS to Rates v3 API
Updated control panel report method to use the read_system_files helper to build file list
Updated product bundle search field
Updated jQuery to XXXX
Updated jQuery UI to XXXX
Updated system JavaScript to display before closing body tag
Updated the mkdir function throughout BrilliantRetail to better set the permissions with ExpressionEngine Constants
Fixed issue with USPS First Class International Package rate
Fixed issue with checkout create downloads (check for bug report)
Fixed issue when download products are contained within bundles
Fixed issue with shipping key "county" in get_totals ajax post 
Added expand/collapse function to category tree in product edit form
Added method to automagically create channel per site and add channel_id to the store configuration array
Added method to automagically create field group per site
Added method to automagically add the group id to new channels
Added Australian Post shipping method
Added browse button for images that exist in the media/product directory
Added browse button to downloadable products for files the exist in the media/import directory 
Added drag & drop sorting to category edit form 
Added js method to add template javascript to session cache for including above the closing body tag
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
Added system messages to order detail notes
Added a method for batch updating statuses from the order detail page 
Removed _clean_search_term in favor of ExpressionEngine native sanitize_search_terms() method
