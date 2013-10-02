<style type="text/css">
	* {
		font-family: 'Helvetica Neue',Helvetica,sans-serif;
		font-size: 12px;
	}
</style>
			<table width="100%" cellpadding="0" cellspacing="10">
        		<tr>
        			<td>
        				<img src="<?=rtrim($company["media_url"],'/').'/'.$company["logo"]?>" width="400"></td>
        			<td>
						<?=lang('br_order_date')?> : <?=date("n/d/y",$order["created"])?><br />
			        	<?=lang('br_order_number')?> : <?=$order["order_id"]?>
			        </td>	
        		</tr>
        		<tr>
        			<td width="50%">
        				<table width="100%" border="1" cellpadding="5" cellspacing="0">
        					<tr>
        						<th style="padding-left:2px;background-color:#DDDDDD;#color:#666666">
        							<?=lang('br_ship_to')?></th>
        					</tr>
        					<tr>
        						<td>
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
									<?=$order["address"][0]["shipping_phone"]?></td>
        					</tr>
        				</table></td>
        			<td width="50%">
        				<table width="100%" border="1" cellpadding="5" cellspacing="0">
        					<tr>
        						<th style="padding-left:2px;background-color:#DDDDDD;#color:#666666">
        							<?=lang('br_bill_to')?></th>
        					</tr>
        					<tr>
        						<td>
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
									</td>
        					</tr>
        				</table></td>
        		</tr>
        		<tr>
        			<td colspan="2"  style="border-bottom-width:0">
        				<table width="100%" border="0" style="border:1px #000000 solid" cellpadding="5" cellspacing="0">
        					<tr>
                    			<th style="padding-left:2px;background-color:#DDDDDD;#color:#666666">
        							<?=lang('br_sku')?></th>
                    			<th style="padding-left:2px;background-color:#DDDDDD;#color:#666666">
        							<?=lang('br_item')?></th>
                    			<th style="padding-left:2px;background-color:#DDDDDD;#color:#666666">
        							<?=lang('br_qty')?></th>
                    		</tr>
        					<?php
                    			$i = 1;
                    			if(isset($order["items"])){
                        			foreach($order["items"] as $item){
                        				$class = ($i % 2 != 0) ? 'odd' : 'even';
                        				echo '	<tr class="'.$class.'">
                        							<td>
                        								'.$item["sku"].'</td>
                        							<td>
                        								<strong>'.$item["title"].'</strong>
                        								'.$item["options"];
                        					if(trim($item["opts"]) != ''){
                        						echo '<br />'.$item["opts"].'<br />';
                        					}			
                        				echo 			'</td>
                        							<td>
                        								'.$item["quantity"].'</td>
                        						</tr>';
                        				$i++;
                        			}
								}
							?>
        				</table></td>
        		</tr>
        		<tr>
        			<td width="50%" valign="top">
        				<table width="100%" cellpadding="0" cellspacing="0">
        					<tr>
        						<th>
        							Payment Method</th>
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
        							Shipping Method</th>
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
