# INSTALLATION  
http://www.brilliantretail.com/developers/detail/Installation

# UPGRADING 
http://www.brilliantretail.com/developers/detail/updating_to_the_latest_release

# Documentation 
https://www.brilliantretail.com/user-guide

# Moderated Support
http://getsatisfaction.com/brilliantretail  

# CHANGE LOG # 

1.0.3.5
* IN PROGRESS - Added category_menu style parameter (default nested)
* IN PROGRESS - Added category_menu level parameter to adjust the parent category level (default 1)
* IN PROGRESS - Added category_menu depth parameter to adjust the depth of the nested ul list (default 3)
* IN PROGRESS - Added donation product type
* IN PROGRESS - Added Subscription product type
* IN PROGRESS - Added custom member fields to register form 
* IN PROGRESS - Added custom member fields to checkout process

* IMPORTANT: Moved cp_theme/brilliant_retail directory to third_party/brilliant_retail 
* IMPORTANT: Moved css and js files from _assets (parsed) to new js and css folders in media directory
* Added member based pricing
* Added member based sale pricing
* Added "Feeds" section to admin and product new/edit form
* Added a forth level of category navigation [#0000092]
* Added ajax filter to admin product overview [#0000096]
* Added ajax filter to admin customer overview [#0000090]
* Added ajax filter to admin order overview [#0000091]
* Added captcha to member register form [#0000089]
* Added confirm_email name to checkout template for server side validation
* Added additional member_id database index to order table. 
* Added product_count variable to catalog results tag pair 
* Added feed tag to pull product data associated to each feed
* Added server side error handling to checkout form (Thanks Tristan Blease)
* Added method to save and repopulate checkout form inputs after a server side validation error (Thanks Tristan Blease)
* Added method to save and repopulate registration form inputs after a server side validation error
* Added product_count variable to BrilliantRetail FieldType
* Added limit parameter to BrilliantRetail FiedldType
* Added random parameter to BrillantRetail FieldType
* Added image_total to product tag
* Added image_count to product internal image tag
* Fixed customer_order tag to include tax, shipping and discounts in total tag
* Fixed customer orders in admin include tax, shipping and discounts in total tag
* Fixed dashboard recent order totals to include include tax, shipping and discounts
* Fixed dashboard sales report when order totals are equal to zero
* Fixed issue with attr:code tags when value was not present in EE 2.2 [#0000071]
* Fixed base path issue with BrilliantRetail FieldType. Now uses config->item() value 
* Fixed bug in show_js parameter for checkout_form and customer_profile tags
* Fixed a bug in read_from_cache method. Now reads exact filesize and closes file handle 
* Fixed a bug with the XSS filter on product descriptions
* Fixed drag and drop issue with categories reorder in settings 
* Fixed password update function to utilize new Authorization library [#0000073]
* Fixed zip code error in Rates Matrix shipping method [#0000076]
* Fixed an issue with the return parameter from PayPal
* Fixed a typo in the delete category language entry [#0000082]
* Updated admin theme. 
* Updated local code directory outside of the brilliant_retail package. [#0000093]
* Updated all language keys with a "br_" prefix to avoid namespace collisions [#0000083]
* Updated customer_model get_customers method to include order history total [#0000090]  
* Updated datatables jQuery script to 1.8.1
* Updated customer_register to use the BR member group setting 
* Updated Tax rate to accept up to 4 decimal values
* Updated Report > General Sales Report to display discount prior to total
* Updated checkout template to remove unnecessary break tags 
* Updated checkout template with new input values to remember on error
* Updated Authorize.net gateway to remove unnecessary break tags 
* Updated query session cache to improve database calls and memory usage
* Updated image tag to use MIME type instead of extension for building cache version
* Updated Tweetline to the latest version 

1.0.3.0
* Added new state / country selector method (requires update to profile edit / checkout templates) 
* Added show_js (default=TRUE) variable to checkout_form tag and removed js from theme file. 
* Added show_js (default=TRUE) variable to customer_profile tag and removed js from theme file. 
* Added unique ID's to each of the gateway div wrappers in checkout. 
* Added additional MySQL indexes to multiple tables for improved performance.
* Added remove button to custom file attributes in the product edit form.
* Added category description
* Added optional category template path
* Added "sales order total" and "sales order history" to customer tab
* Added "store" tag to access address and phone information
* Added automatic canonical link tag when meta not built from last segment
* Added alt parameter to image tag
* Added SKU to search index
* Fixed search index to better handle numerical search terms
* Fixed attribute type file to remove value from file input
* Fixed convert_weight method call in FedEx Shipping module
* Fixed product type configurable to automatically count quantity
* Fixed extension name on product image upload. It was incorrectly naming the files .jpg instead of .png
* Fixed javascript issue with RatesMatrix Shipping Method.
* Fixed incorrect closing div in Blank Theme cart template
* Fixed double inclusion of id="main_menu" in Blank Theme
* Fixed javascript HTML validation error in Blank Theme footer template
* Fixed html error (closing li) in upd file sample data.
* Fixed "if" conditional wrapper around br_shipping_state
* Fixed default content install errors relating to ExpressionEngine v2.2
* Fixed encoding issue with included jQuery Validate plugin 
* Updated core BrilliantRetail class __construct with call to all BrilliantRetail helpers and models so they are available to extensions.
* Updated Tweetline (getee.com) to the latest version
* Updated product overview category filter to display indented sub-categories
* Updated XSS filter from notification save function which was striping inline styles.
* Removed show_404 (CI) method from catalog and added redirect to selected EE 404 page.
* Removed unused exp_br_cart_item, exp_br_product_type tables
* Removed gateway class meta data settings and depreciated jquery metadata plugin 

1.0.2.5 
* Added category filter to admin product list page
* Added common gateway functions into gateway_helper.php to observe DRY methodology
* Added example .htaccess file.
* Added required tags to customer profile template.
* Added check so only "No Payment Required" gateways will display on zero sum totals.
* Added customer downloads report
* Added Thank You url setting to admin for checkout return. 
* Fixed redirect issue with when saving categories.
* Fixed session_start issue when setting session_id from POST.
* Fixed promotion issue where fixed discounts could result in negative totals.
* Fixed a date bug in the report.sales.php (added conditional wrapper)
* Removed notification insert from upd file. These should be built automatically from flat files on install.

1.0.2.4
* Added SagePay (Beta) Gateway to the package for testing
* Added featured option to product detail 
* Added new shipping method "Rates Matrix"
* Added sort input to shipping methods
* Fixed issue with missing symbol (+/-) on price adjusted items in cart options
* Updated shipping title / enabled to function the same as gateways
* Updated catalog tag to parse products with the same function as the product detail page 

1.0.2.3 

* Added CDG Commerce gateway
* Added AlertPay Gateway
* Added return url parameter to the PayPal WPS gateway
* Added a check for a minimum weight of 1 pound in the UPS Shipping Method.
* Fixed issue with theme includes in admin which caused MSM issues with file and image uploads.
* Fixed "exclude" parameter bug in category_menu tag
* Fixed issue with new product if no categories are defined
* Fixed product image upload bug introduced in Flash v10
* Fixed issue with product {attr:code} tag pair
* Fixed product category sorting didn't display disabled or inventory 0 items.
* Fixed Bug in SecureHosting Gateway where date format is not accepted when live, but is in test gateway.
* Updated product image upload limit size to 20 MB via flash uploader
* Updated all admin view files to call language reference for "cancel" links.
* Updated mod file to pass email to gateway interface

1.0.2.2

* Added product sorting to category edit page
* Added disable="price|category" param to layered navigation
* Added {exp:brilliant_retail:geturl type=''} which returns the path from the store settings. You can pass the following in the type:
		- cart_url
		- checkout_url
		- customer_url
		- product_url
		- secure_url
		- media_url
* Added additional param for {exp:brilliant_retail:category_menu} named exclude='' allowing you to pass url_title's to exclude from the menu. Multiple params passed with pipe character "|".
* Added no_results tag to the product tag
* Fixed product tag if an invalid url_title was passed in the url_title
* Fixed rtrim bug in product_model that checked for existing file directory on product upload.
* Fixed issue with urlencoded condigurable attributes
* Fixed issue with new multiselect attribute type on save
* Fixed product file custom attribute that would disappear when being edited
* Fixed issue with deleted related products
* Fixed bug in 'Notify Customer' email on order detail page

1.0.2.1 
* Fixed gateway issue with MSM in mod file 

1.0.2.0
* Added RealEx Gateway.
* Added PayPoint Gateway
* Added SecureHosting Gateway.
* Added attr:(code) selector to product tag. Allows for individually selecting attributes
* Added path tag for dynamically setting paths for javascript / css / and customer / checkout links
* Added drag state style on Fieldtype
* Added member_id parameter to customer_profile tag 
* Added product_switch tag to catalog > result tag
* Added product_switch tag to search > results tag
* Added product_switch tag to BrilliantRetail FieldType
* Added multiselect product attribute type
* Added form param to promo form (Updated checkout template) 
* Added batch delete / enable / disable to product view 
* Added hidden free shipping variable so that shipping could be removed on non-shippable / digital products
* Added site_id to br_attribute, br_attribute_set, br_config, br_product_category, br_promo, br_search, br_tax tables (MSM) 
* Added Order note notification email template and logic to code.
* Added low_stock column to site settings to accommodate basic stock reporting
* Added Low Stock Report
* Fixed weight calculation on checkout_shipping method to account for quantity per item 
* Fixed issue multiple product options with type dropdown (Thanks Chris Brady)
* Fixed missing forgot_password action in 1.0.1.1 update
* Fixed fieldtype error when items are deleted 
* Fixed checkout error when using guest checkout with an existing email
* Fixed group_by issue with get_downloads_by_member method
* Fixed issue on catalog page when there were no results without no_result tag
* Fixed an issue with uses_per field in promotions
* Fixed misc. english language file entries 
* Fixed notification template variables to remove invalid variables
* Fixed an issue with get_products and disabled products. 
* Fixed an issue with use counts on 'uses per' coupon codes
* Fixed UPS configuration bug 
* Fixed bug in Reports where status_id of order wasn't being reflected
* Fixed config data entries to store at the store level (MSM)
* Fixed issue with new store config / set default status codes (MSM)
* Fixed Best Seller Report (MSM)
* Updated version numbers of Product Best Sellers and Search Report to 1.0
* Updated report detail to facilitate passing of empty $inputs


1.0.1.1
* Added new forgot_password tag / function 
* Added password page to Blank template to reset password
* Added inventory control to check quantity in cart against quantity available
* Added image_switch tag to the product > images loop
* Fixed issue with order collection for non-admin users 
* Fixed encoding on configurable product labels (quotes and double quotes)
* Fixed price adjust error in configurable products
* Fixed ipn_order_create to reduce inventory on basic items and configurable item options
* Fixed error with PayPal Pro when response was SuccessWithWarning
* Fixed issue with product > image image_title tag having 'products/' in the title
* Updated quantity restriction to basic products only
* Updated BrilliantRetail Fieldtype Layout

1.0.1.0 
* Added New Payment Gateway: PayPal Website Payment Standard
* Added New Payment Gateway: eWay Australia
* Added New Payment Gateway: PsiGate 
* Added New Shipping Method: 'Pickup In store' Shipping Option (i.e. No Shipping)
* Added New Report: Customer Search History Report
* Added New Report: List of Orders by Customer over time
* Added New Report: Product Best Sellers Report
* Added purchase_version number tag to customer_download tag
* Added cancel status to order
* Added additional developer hooks
* Updated category_menu tag to include sort parameter (accepts sort|title)
* Updated image tag to include alt parameter
* Updated all currency instances to pass through currency_round function
* Updated exp_br_state table so state_id start at 1 and are now standard across all installations
* Updated USPS with additional configuration options
* Fixed shipping method pre calculations for product weight
* Fixed path_separator and directory_separator on Zend library include in core file
* Fixed Brilliant_retail fieldtype to allow multiple instances per page
* Fixed quantity issue on cart_update and cart_add. Forced integers. 
* Fixed download_version tag displaying improper number in customer_download tag
* Fixed issue with general sales report total
* Fixed issue with javascript file names in the WYSIWYG editor
* Fixed the cart_items tag to account for quantity of items 
* Removed countries that are not recognized by PayPal
* Misc updates to Blank Theme

1.0.0
* IMPORTANT: Fixed issue with USPS shipping gateway requires that the shipping method be removed and reinstalled from the Settings > Shipping section.  
* Fixed issue with missing "cost" index on new product entry
* Fixed broken path on post in order update
* Fixed update password in profile 
* Fixed state / country selector on checkout 
* Fixed tax calculation based on country / state combination
* Fix the spelling of 3 countries 
* Add password update action
* Add developers hooks (for more information visit http://www.brilliantretail.com/developers/detail/developer_hooks)
* Add countries multiselect to admin panel

0.9.9 - Add states / regions for zones. Updated checkout script file. 

0.9.8 - Add redirect variables to settings > site for global redirects and secure url. Please check your site settings in BrilliantRetail after upgrading. 