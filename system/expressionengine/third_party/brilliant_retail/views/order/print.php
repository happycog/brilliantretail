<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter 								*/
/* 	@copyright	Copyright (c) 2011, Brilliant2.com 			*/
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
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?=$site_name.' - '.lang('br_order_number')?> : <?=$order["order_id"]?></title>
		<link rel="stylesheet" type="text/css" href="<?=$print_css?>" media="all"/>
	</head>
	<body>
		<div id="wrapper">
			<div id="logo">
				<img src="<?=rtrim($company["media_url"],'/').'/'.$company["logo"]?>">
			</div>
			<h4 id="status_text">
	        	<?=lang('br_order_date')?> : <?=date("n/d/y",$order["created"])?><br />
	        	<?=lang('br_order_number')?> : <?=$order["order_id"]?><br />
	        </h4>
	       	<table id="order_table" width="100%" cellpadding="0" cellspacing="15">
        		<tr>
        			<td width="50%">
        				<table width="100%" cellpadding="0" cellspacing="0">
        					<tr>
        						<th>
        							<?=lang('br_ship_to')?></th>
        					</tr>
        					<tr>
        						<td>
									<p>
										<b><?=$order["address"][0]["shipping_fname"]?> <?=$order["address"][0]["shipping_lname"]?></b><br />
										<?=$order["address"][0]["shipping_company"]?><br />
										<?=$order["address"][0]["shipping_address1"]?><br />
										<?php 
											if(trim($order["address"][0]["shipping_address2"]) != '')
											{
												echo $order["address"][0]["shipping_address2"].'<br />';
											}
										?>
										<?=$order["address"][0]["shipping_city"]?>, <?=$order["address"][0]["shipping_state"]?> <?=$order["address"][0]["shipping_zip"]?><br />
										<?=$order["address"][0]["shipping_country"]?><br />
										<?=$order["address"][0]["shipping_phone"]?>
									</p></td>
        					</tr>
        				</table></td>
        			<td width="50%">
        				<table width="100%" cellpadding="0" cellspacing="0">
        					<tr>
        						<th>
        							<?=lang('br_bill_to')?></th>
        					</tr>
        					<tr>
        						<td>
									<p>
										<b><?=$order["address"][0]["billing_fname"]?> <?=$order["address"][0]["billing_lname"]?></b><br />
										<?=$order["address"][0]["billing_company"]?><br />
										<?=$order["address"][0]["billing_address1"]?><br />
										<?php 
											if(trim($order["address"][0]["billing_address2"]) != '')
											{
												echo $order["address"][0]["billing_address2"].'<br />';
											}
										?>
										<?=$order["address"][0]["billing_city"]?>, <?=$order["address"][0]["billing_state"]?> <?=$order["address"][0]["billing_zip"]?><br />
										<?=$order["address"][0]["billing_country"]?><br />
										<?=$order["address"][0]["billing_phone"]?>
									</p></td>
        					</tr>
        				</table></td>
        		</tr>
        		<tr>
        			<td colspan="2"  style="border-bottom-width:0">
        				<table width="100%" cellpadding="0" cellspacing="0">
        					<tr>
                    			<th>
                    				<?=lang('br_item')?></th>
                    			<th>
                    				<?=lang('br_sku')?></th>
                    			<th>
                    				<?=lang('br_qty')?></th>
                    			<th>
                    				<?=lang('br_price')?></th>
                    		</tr>
        					<?php
                    			$i = 1;
                    			if(isset($order["items"])){
                        			foreach($order["items"] as $item){
                        				$class = ($i % 2 != 0) ? 'odd' : 'even';
                        				echo '	<tr class="'.$class.'">
                        							<td>
                        								<strong>'.$item["title"].'</strong><br />
                        								'.$item["options"];
                        					if(trim($item["opts"]) != ''){
                        						echo $item["opts"].'<br />';
                        					}			
                        				echo 			'</td>
                        							<td>
                        								'.$item["sku"].'</td>
                        							<td>
                        								'.$item["quantity"].'</td>
                        							<td>
                        								'.$currency_marker.$item["price"].'</td>
                        						</tr>';
                        				$i++;
                        			}
								}
							?>

							<tr>
								<th colspan="3" style="text-align:right">
									<?=lang('br_subtotal')?> :<br />
									<?=lang('br_discount')?> :<br />
									<?=lang('br_shipping')?> :<br />
									<?=lang('br_tax')?> :<br />
									<?=lang('br_total')?> :</th>
								<th>
									<?=$currency_marker?><?=$order["base"]?><br />
									<?=$currency_marker?><?=$order["discount"]?><br />
									<?=$currency_marker?><?=$order["shipping"]?><br />
                    				<?=$currency_marker?><?=$order["tax"]?><br />
                    				<?=$currency_marker?><?=number_format(($order["total"]+$order["tax"]+$order["shipping"]),2)?></th>
							</tr>
        				</table></td>
        		</tr>
        		<tr>
        			<td width="50%" valign="top">
        				<table width="100%" cellpadding="0" cellspacing="0">
        					<tr>
        						<th>
        							<?=lang('br_payment_info')?></th>
        					</tr>
        					<tr>
        						<td>
        							<?php
        								$d = unserialize($order["payment"][0]["details"]);
        								foreach($d as $key => $val){
        									echo '<b>'.lang(strtolower(str_replace(" ","_",trim($key)))).'</b> : '.$val.'<br />';
        								}
        							?></td>
        					</tr>
        				</table></td>
        			<td width="50%" valign="top">
        				<table width="100%" id="order_totals" cellpadding="0" cellspacing="0"> 
        					<tr>
        						<th>
        							<?=lang('br_shipping')?></th>
        					</tr>
        					<tr>
        						<td>
        							<?php 
        								$list = array(
        												'Method' 	=> strtoupper($order["shipment"][0]["method"]),
        												'label' 	=> $order["shipment"][0]["label"] 
        											);

        								foreach($list as $key => $val){
        									echo '<b>'.lang($key).'</b> : '.$val.'<br />';
        								}
        							?></td>
        					</tr>
        				</table></td>
        		</tr>  
          	</table>

    	<div class="clearboth"><!-- --></div>
	
		</div> <!-- b2r_dashboard --> 
	</body>
</html>