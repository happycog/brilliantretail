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
						<td style="background-color:#FFFFFF;border-top:0px solid #FFFFFF;border-bottom:0px solid #333333;">
						    <center>
						        <a href="{site_url}">
						            <IMG id=emailhead1 SRC="{media}/images/email-logo.jpg" border="0" title="Your Company"  alt="Your Company" align="center"></a>
                            </center></td>
					</tr>
				</table>
<table width="550" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td bgcolor="#FFFFFF" valign="top" style="font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">
<p>
<span style="font-size:12px;font-weight:regular;color:#000000;font-family:arial;line-height:140%">Welcome {fname},</span>
<br />
<br />
{if activation_url != ''}
    
    <a href="{activation_url}">Click here to activate</a> your new account. 
    <br />
    <br />
    You can also cut and paste this link into your browser:
    <br />
    <br />
    {activation_url}
    <br />
    <br />

{if:else}
    Your account has been created. 
    <br />
    <br />
    To login go to <a href="{site_url}/customer">{site_url}/customer</a>. <br />
    <br />
{/if}


<span style="font-size:16px;font-weight:bold;color:#333333; font-family:arial;line-height:140%">New Account</span></p>
<p style="border:1px solid #f0f0f0; padding:14px 18px; background:#f3f3f3;">

<span style="font-size:12px;font-weight:bold;color:#333333;font-family:arial;line-height:140%">Login Email:</span><br />
<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:140%"> {email} </span><br />
<br />
<span style="font-size:12px;font-weight:normal;color:#333333; font-family:arial;line-height:100%">If you have questions about the status of your order please <a href="{site_url}/contact/">contact us</a>.</span><br />
</p>
<p><span style="font-size:12px;font-weight:regular;color:#000000;font-family:arial;line-height:140%"> Thank you,<br />
  <span style="font-size:12px;color:#000000;line-height:200%;font-family:verdana;text-decoration:none;">{site_name}</span></span> </p></td>
</tr>
				</table></td>
		</tr>
</table>
EOF;


$msg = array(
	"version" 	=> 1.0, 
	"subject" 	=> "Thank you for registering with {site_name}",
	"content" 	=> $html
);