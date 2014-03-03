<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2014						*/
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

$html = <<< EOF
<table width="100%" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" >
	<tr>
		<td valign="top" align="center">
			<table width="550" cellpadding="0" cellspacing="0">
				<tr>
					<td style="background-color:#FFFFFF;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;" align="center"><span style="font-size:10px;color:#333333;line-height:200%;font-family:verdana;text-decoration:none;">{site_name}</span></td>
				</tr>
				<tr>
					<td style="background-color:#FFFFFF;border-top:0px solid #FFFFFF;border-bottom:0px solid #333333;"><center><a href=""><IMG id=emailhead1 SRC="{media}/images/email-logo.jpg" BORDER="0" title="Your Company"  alt="Your Company" align="center"></a></center></td>
				</tr>
			</table>
			<table width="550" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">
				<tr>
					<td bgcolor="#FFFFFF" valign="top" style="font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">
						<p>
							<span style="font-size:12px;font-weight:regular;color:#000000;font-family:arial;line-height:140%">Hi {fname}.</span><br>
							A note has been added to your order.<br>
						</p>
						<p style="border:1px solid #f0f0f0; padding:14px 18px; background:#f3f3f3;">
							<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">Order #: </span>{order_id}<br>
							<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">Note:</span>
							<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%"> {order_note} </span><br>
						</p>
						<p>
							<span style="font-size:12px;font-weight:regular;color:#000000;font-family:arial;line-height:140%"> Thank you,<br>
							<span style="font-size:12px;color:#000000;line-height:200%;font-family:verdana;text-decoration:none;">{site_name}</span></span> <br><br>
							If <span style="font-size:12px;font-weight:regular;color:#000000;font-family:arial;line-height:140%">you have any questions <a href="{site_url}/contact">contact us </a>. </span>
						</p></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOF;

$msg = array(
	"version" => 1.0, 
	"subject" => "Your order has been updated - Order #{order_id}", 
	"content" => $html
);