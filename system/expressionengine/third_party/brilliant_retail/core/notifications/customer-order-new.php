<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
/* 	@license	http://opensource.org/licenses/OSL-3.0	*/
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

$html = <<< EOF
<table width="100%" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" >
		<tr>
			<td valign="top" align="center">
				<table width="550" cellpadding="0" cellspacing="0">
					<tr>
						<td style="background-color:#FFFFFF;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;" align="center">
							<span style="font-size:10px;color:#333333;line-height:200%;font-family:verdana;text-decoration:none;">
								{site_name}
							</span></td>
					</tr>
					<tr>
						<td style="background-color:#FFFFFF;border-top:0px solid #FFFFFF;border-bottom:0px solid #333333;"><center><a href=""><IMG id=emailhead1 SRC="{media}/images/email-logo.jpg" border="0" title="Your Company"  alt="Your Company" align="center"></a></center></td>
					</tr>
				</table>
				<table width="550" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">
					<tr>
						<td bgcolor="#FFFFFF" valign="top" style="font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">
							<p>
								<span style="font-size:12px;font-weight:regular;color:#000000;font-family:arial;line-height:140%">Thank you for your order {fname} {lname}.</span><br /><br />
								To view order details and  files  visit your account  at <a href="{site_url}/customer">{site_url}/customer</a>. If <span style="font-size:12px;font-weight:regular;color:#000000;font-family:arial;line-height:140%">you have any questions  <a href="{site_url}/contact">contact us </a>. </span><br />
								<br />
								<span style="font-size:16px;font-weight:bold;color:#333333; font-family:arial;line-height:140%">Order Confirmation</span>
							</p>
							<p style="border:1px solid #f0f0f0; padding:14px 18px; background:#f3f3f3;">
								<span style="font-size:14px;font-weight:bold;color:#333333; font-family:arial;line-height:140%">Purchasing Information</span> 
								<br />
								
								{address}

									<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">Billed To:</span><br />
									<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%">
										{billing_fname} {billing_lname}<br />
										{if '{billing_company}' != ''}{billing_company} <br />{/if}
										{billing_address1}<br /> 
										{if '{billing_address2}' != ''}{billing_address2} <br />{/if}
										{billing_city}, {billing_state} {billing_zip}<br /> 
										{billing_country}<br />{billing_phone}</span><br /><br />
									<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">Shipped To:</span>
									<br />
									<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%">
										{shipping_fname} {shipping_lname}<br />
										{if '{shipping_company}' != ''}{shipping_company} <br />{/if}
										{shipping_address1}	<br />
										{if '{shipping_address2}' != ''}{shipping_address2} <br />{/if}
										{shipping_city}, {shipping_state} {shipping_zip}<br /> {shipping_country}<br /> {shipping_phone}</span><br /><br />

								{/address}

								<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">
								Payment Method:</span>
								
								<br />
								<br />
								{payment}
									Payment Type: {payment_type}<br />
									Approval: {approval}<br />
									Transaction ID: {transaction_id}<br />
								{/payment}
								<br />											
								<br />
								<span style="font-size:14px;font-weight:bold;color:#333333; font-family:arial;line-height:140%">Order Summary</span> <br />
								<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">Order #: </span>{order_id}<br />
								<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">Delivery Method:</span><br />
								<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%"> {delivery_method} - {delivery_label}</span><br />
								<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">Order Items:</span><br />
								<table style="background-color:#FFF" width="100%">
									<tr>
										<td colspan="2" align="left" style="background-color:#EBEBEB;padding-left:4px">
											<span style="font-size:12px;font-weight:bold;color:#000000;font-family:arial;line-height:140%">ITEMS</span></td>
										<td valign="top" style="width:40px;background-color:#EBEBEB;padding-left:4px">
											<span style="font-size:12px;font-weight:bold;color:#000000;font-family:arial;line-height:140%">Qty</span></td>
										<td valign="top" style="background-color:#EBEBEB;padding-left:4px">
											<span style="font-size:12px;font-weight:bold;color:#000000;font-family:arial;line-height:140%">Price</span></td>
									{items}
										<tr>
											<td valign="top" style="width:110px">
												<a href="{site_url}/product/{url_title}">
													<img src="{media}/{image_thumb}" style="border:1px #CCCCCC solid"  /></a></td>
											<td valign="top">
												<span style="font-size:12px;font-weight:bold;color:#000000;font-family:arial;line-height:140%">
													<a href="{site_url}/product/{url_title}">{title}</a></span><br />
												<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%">
													<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%">{options}</span>
												</span>
											</td>
											<td valign="top" style="width:40px">
												<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%">{quantity}</span></td>
											<td valign="top">
												<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%">{currency_marker}{price}</span></td>
										</tr>
									{/items}
								</table>
								<span style="font-size:12px;font-weight:normal;color:#000000;font-family:arial;line-height:140%">-----------</span> <br />
								<span style="font-size:12px;font-weight:normal;color:#000000;font-family:arial;line-height:140%">Subtotal: {currency_marker}{order_subtotal}</span> <br />
								<span style="font-size:12px;font-weight:normal;color:#000000;font-family:arial;line-height:140%">Discount: {currency_marker}{discount_total}</span> <br />
								<span style="font-size:12px;font-weight:normal;color:#000000;font-family:arial;line-height:140%">Tax: {currency_marker}{tax_total}</span> <br />
								<span style="font-size:12px;font-weight:normal;color:#000000;font-family:arial;line-height:140%">Shipping: {currency_marker}{shipping}</span> <br />
								<span style="font-size:12px;font-weight:bold;color:#000000;font-family:arial;line-height:140%">Total: {currency_marker}{order_total}</span> <br /><br />
								<span style="font-size:12px;font-weight:normal;color:#333333;font-family:arial;line-height:140%">Special Instructions:</span><br />
								<br />
								<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:100%">If you have questions about the status of your order please <a href="{site_url}/contact/">contact us</a><a href="{site_url}/contact"></a>.</span><br />
								</p>
								<p>
									<span style="font-size:12px;font-weight:regular;color:#000000;font-family:arial;line-height:140%"> Thank you,<br />
								  	<span style="font-size:12px;color:#000000;line-height:200%;font-family:verdana;text-decoration:none;">{site_name}</span></span> 
								</p>
						</td>
					</tr>
					<tr>
						<td style="background-color:#f2f2f2;border-top:1px solid #FFFFFF;" valign="top">
							<span style="font-size:10px;color:#333333;line-height:100%;font-family:verdana;">
								If you do not wish to receive these emails simply <a href="{site_url}/unsubscribe">unsubscribe</a>.<br />
							</span></td>
					</tr>
				</table></td>
		</tr>
</table>
EOF;

$msg = array(
	"version" => 1.0, 
	"subject" => "Thank you for your order - Order #{order_id}", 
	"content" => $html
);