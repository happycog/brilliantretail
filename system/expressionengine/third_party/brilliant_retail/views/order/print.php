<br />
<br />
<table width="100%" border="0" cellpadding="0" cellspacing="10">
	<tr>
		<td width="50%">
			<img src="<?=rtrim($company["media_url"],'/').'/'.$company["logo"]?>" />
		</td>
		<td width="50%">
			<h2>Invoice #<?=$order["order_id"]?></h2>
			<?=lang('br_order_date')?> : <?=date("n/d/y",$order["created"])?><br />
	    	<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<strong><?=lang('br_order_date')?>: </strong></td>
					<td>
						 <?=date("n/d/y",$order["created"])?></td>
				</tr>
				<tr>
					<td>
						<strong><?=lang('br_customer_username')?>: </strong></td>
					<td>
						<?php
							echo $order["member"]["br_fname"].' '.$order["member"]["br_lname"];
						?></td>
				</tr>
				<tr>
					<td>
						<strong><?=lang('br_customer_email')?>: </strong></td>
					<td>
						<?php
							echo '<a href="mailto:'.$order["member"]["email"].'">'.$order["member"]["email"].'</a>';
						?></td>
				</tr>
			</table>
	    </td>	
	</tr>
</table>


	
<table id="order_table" width="100%" cellpadding="15" border="0" cellspacing="0">
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
							<?php
								if($order["address"][0]["shipping_company"] != '')
								{
									echo $order["address"][0]["shipping_company"].'<br />';
								}
							?>
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
			<table width="100%" cellpadding="0"  cellspacing="0">
				<tr>
					<th>
						<?=lang('br_bill_to')?></th>
				</tr>
				<tr>
					<td>
						<p>
							<b><?=$order["address"][0]["billing_fname"]?> <?=$order["address"][0]["billing_lname"]?></b><br />
							<?php
								if($order["address"][0]["billing_company"] != '')
								{
									echo $order["address"][0]["billing_company"].'<br />';
								}
							?>
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
			<table width="100%" border="1" cellpadding="0" cellspacing="0">
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
	
				<tr class="last">
					<td colspan="3" class="totals" style="text-align:right">
						<p><?=lang('br_subtotal')?> :</p>
						<p><?=lang('br_discount')?> :</p>
						<p><?=lang('br_shipping')?> :</p>
						<p><?=lang('br_tax')?> :</p>
						<p><b><?=lang('br_total')?> :</b></p>
						<p><b><?=lang('br_total_paid')?> :</b></p>
						<p><b><?=lang('br_total_due')?> :</b></p>
					</td>
					<td class="totals">
						<p><?=$currency_marker?><?=$order["base"]?></p>
						<p><?=$currency_marker?><?=$order["discount"]?></p>
						<p><?=$currency_marker?><?=$order["shipping"]?></p>
	    				<p><?=$currency_marker?><?=$order["tax"]?></p>
	    				<p><b><?=$currency_marker?><?=$order["order_total"]?></b></p>
	    				<p><b><?=$currency_marker?><?=$order["order_total_paid"]?></b></p>
	    				<p><b><?=$currency_marker?><?=$order["order_total_due"]?></b></p>
	    			</td>
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