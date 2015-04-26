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

echo $br_header;
?>
<div class="rightNav" style="padding:0">
	<div style="float: left; width: 100%;">
			
			<?php
				foreach($order["right"] as $row)
				{
					foreach($row as $key => $val)
					{
						echo '	<span class="button">
									<a 	id="'.strtolower(str_replace(" ","_",$key)).'"
										title="'.$key.'" 
										class="submit" 
										target="'.$val["target"].'" 
										href="'.$val["link"].'">'.$key.'</a>
								</span>';
					}
				}
			?>
		</div>
	<div class="clear_left"></div>
</div>

<div id="popin-overlay" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;display:none;"></div>
	<table id="order_table" width="100%" cellpadding="3" cellspacing="0">
		<tr>
			<td width="50%" valign="top">
				<table width="100%" class="product_edit" cellpadding="0" cellspacing="0">
					<tr>
						<th>
							<?=lang('br_order_details')?></th>
					</tr>
					<tr>
						<td>
							<table id="order_details" cellspacing="0" width="100%">
								<tr>
									<td>
										<strong><?=lang('br_order_number')?></strong></td>
									<td>
										<?=$order["order_id"]?></td>
								</tr>
								<tr>
									<td>
										<strong><?=lang('br_order_date')?></strong></td>
									<td>
										<?=$order["created"]?></td>
								</tr>
								<tr>
									<td>
										<strong><?=lang('br_order_status')?></strong></td>
									<td>
										<div id="order_status_form">
											<img src="<?=$theme?>images/popin-arrow.png" class="popin-arrow" />
											<div class="popin-top">
												<img src="<?=$theme?>images/close.png" />
												<div><?=lang('br_order_update_status')?></div>
											</div>
											<div class="popin-body">
												<?php
													echo form_open($status_action,array('method' => 'POST', 'id' => 'statusForm'),$hidden);
												?>
													<select name="status_id" id="status_id">
													<?php 
														ksort($status);
														foreach($status as $key => $val){
															$sel = ($key == $order["status_id"]) ? 'selected="selected"' : '';
															echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';  
														}
													?>
													</select>
													<br />
													<br />
													<p>
													<input type="checkbox" name="notify" />&nbsp;<?=lang('br_status_notify')?>
													</p>
													<input type="submit" class="submit" value="<?=lang('update')?>" />
												</form>
											</div>
											<div class="popin-bottom"></div>
										</div>
										<span id="order_status">
											<?=$status[$order["status_id"]]?>
										</span>
									</td>
								</tr>
							</table></td>
					</tr>
				</table></td>
			<td width="50%" valign="top">
				<table width="100%" class="product_edit" cellpadding="0" cellspacing="0">
					<tr>
						<th>
							<?=lang('br_customer')?></th>
					</tr>
					<tr>
						<td>
							<table id="order_customer" cellspacing="0" width="100%">
								<tr>
									<td>
										<strong><?=lang('br_customer_username')?></strong></td>
									<td>
										<?php
											echo $order["member"]["br_fname"].' '.$order["member"]["br_lname"];
										?>		
									</td>
								</tr>
								<tr>
									<td>
										<strong><?=lang('br_customer_email')?></strong></td>
									<td>
										<?php
											echo '<a href="mailto:'.$order["member"]["email"].'">'.$order["member"]["email"].'</a>';
										?></td>
								</tr>
								<tr>
									<td>
										<strong><?=lang('br_member_group')?></strong></td>
									<td>
										<?=$groups[$order["member"]["group_id"]]?></td>
								</tr>
							</table></td>
					</tr>
				</table></td>
		</tr>
		<tr>
			<td width="50%">
				<table width="100%" class="product_edit" cellpadding="0" cellspacing="0">
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
				<table width="100%" class="product_edit" cellpadding="0" cellspacing="0">
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
			<td colspan="2">
				<table width="100%" class="product_edit" cellpadding="0" cellspacing="0">
					<tr>
            			<th>
            				<?=lang('br_item')?></th>
            			<th>
            				<?=lang('br_sku')?></th>
            			<th>
            				<?=lang('br_qty')?></th>
            			<th style="width:75px">
            				<?=lang('br_price')?></th>
            			<th style="width:75px">
            				&nbsp;</th>
            		</tr>
					<?php
            			$i = 1;
            			if(isset($order["items"])){
                			foreach($order["items"] as $item){
                				$class = ($i % 2 != 0) ? 'odd' : 'even';
                				echo '	<tr class="'.$class.'">
                							<td>
                								<strong>'.$item["title"].'</strong>';
                				echo '<br />'.$item["options"];
            					if(trim($item["opts"]) != ''){
            						echo $item["opts"].'<br />';
            					}			
                				echo 			'</td>
                							<td>
                								'.$item["sku"].'</td>
                							<td>
                								'.$item["quantity"].'</td>
                							<td colspan="2">
                								'.$currency_marker.$item["price"].'</td>
                						</tr>';
                				$i++;
                			}
						}
					?>

					<tr>
						<td colspan="3" class="totals">
							<?=lang('br_subtotal')?> :</td>
						<td colspan="2">
							<?=$currency_marker.$order["base"]?></td>
					</tr>		
					<tr>
						<td colspan="3" class="totals">
							<?=lang('br_discount')?> :
							<?php
								if($order["coupon_code"] != '')
								{
									echo '<br /><span style="font-weight:normal">('.lang('br_promo_code').': '.$order["coupon_code"].')</span>';
								}
							?>&nbsp;</td>
						<td colspan="2">
							<?=$currency_marker.$order["discount"]?></td>
					</tr>
					<tr>
						<td colspan="3" class="totals">
							<?=lang('br_shipping')?> :</td>
						<td colspan="2">
							<?=$currency_marker.$order["shipping"]?></td>
					</tr>
					<tr>
						<td colspan="3" class="totals">
							<?=lang('br_tax')?> :</td>
						<td colspan="2">
							<?=$currency_marker.$order["tax"]?></td>
					</tr>
					<tr>
						<td colspan="3" class="totals">
							<?=lang('br_total')?> :</td>
						<td>
							&nbsp;</td>
						<td class="totals">
							<?=$currency_marker.$order["order_total"]?></td>
					</tr>
					
			<?php
				foreach($order["payment"] as $p)
				{
					$date = (date("n/d/Y",$p["created"]) != '12/31/1969') ? ' ('.date("n/d/Y",$p["created"]).')' : '';
			?>
					<tr>
						<td class="payment" colspan="3">
							<strong>Payment: <?=$p["payment_type"]?></strong> <em><?=$date?></em></td>
						<td class="payment" colspan="2">
							(<?=$currency_marker.$p["amount"]?>)</td>
					</tr>
			<?php
				}
			?>		
					
					
					<tr>
						<td colspan="3" class="totals">
							<?=lang('br_total_paid')?> :</td>
						<td>
							&nbsp;</td>
						<td class="totals">
							<?=$currency_marker.$order["order_total_paid"]?></td>
					</tr>
					<tr>
						<td colspan="3" class="totals">
							<?=lang('br_order_balance')?> :</td>
						<td>
							&nbsp;</td>
						<td class="totals">
							<?=$currency_marker.$order["order_total_due"]?></td>
					</tr>
				</table></td>
		</tr>
		<tr>
			<td width="50%" valign="top">
				<table width="100%" class="product_edit" cellpadding="0" cellspacing="0">
					<tr>
						<th>
							<?=lang('br_payment_info')?></th>
					</tr>
					<tr>
						<td class="small_content_box">
							<table id="order_customer" cellspacing="0" width="100%">
								<?php 
									if(isset($order["payment"][0]))
									{
										$d = unserialize($order["payment"][0]["details"]);
										foreach($d as $key => $val){
											
											echo '	<tr>
														<td>
															<strong>'.lang(strtolower(str_replace(" ","_",trim($key)))).'</strong></td>
														<td>
															'.$val.'		
														</td>
													</tr>';
										}								
									}
								?>
							</table></td>
					</tr>
				</table></td>
			<td width="50%" valign="top">
				<table width="100%" class="product_edit" cellpadding="0" cellspacing="0"> 
					<tr>
						<th>
							<?=lang('br_shipping')?></th>
					</tr>
					<tr>
						<td class="small_content_box"> 
							<table id="order_customer" cellspacing="0" width="100%">
								<?php 
									if(isset($order["shipment"][0]))
									{
										$list = array(
													'br_ship_method' 	=> strtoupper($order["shipment"][0]["method"]),
													'br_ship_label' 	=> $order["shipment"][0]["label"], 
													'br_ship_tracknum'	=> $order["shipment"][0]["tracknum"] 
												);
		
										foreach($list as $key => $val){
											echo '	<tr>
														<td>
															<strong>'.lang($key).'</strong></td>
														<td>
															'.$val.'		
														</td>
													</tr>';
										}								
									}
								?>
							</table></td>
					</tr>
				</table></td>
		</tr>  
  		<tr>
			<td colspan="2">
					<?php
						echo form_open_multipart($note_action,array('method' => 'POST', 'id' => 'noteForm'),$hidden);
					?>
							<table id="order_notes_form" width="100%" class="order_notes" cellpadding="0" cellspacing="0"> 
								<tr>
									<th colspan="2">
										<?=lang('br_order_notes')?></th>
								</tr>
								<tr>
									<td>
										<b><?=lang('br_order_note')?></b></td>
									<td>
											<textarea name="order_note" id="order_note"></textarea>
									</td>
								</tr>
								<tr>
									<td>
										<b><?=lang('br_note_private')?></b></td>
									<td>
										<input type="checkbox" value="1" name="isprivate" />
									</td>
								</tr>
								<tr>
									<td>
										<b><?=lang('br_order_note_file')?></b></td>
									<td>
											<input type="file" name="order_note_file" />
									</td>
								</tr>
								<tr>
									<td>
										<b><?=lang('br_note_notify')?></b></td>
									<td>
										<input type="checkbox" name="order_note_notify" />
									</td>
								</tr>

								<tr>
									<td>&nbsp;</td>
									<td>
										<input type="submit" class="submit" value="<?=lang('br_add_note')?>" />
									</td>
								</tr>
							</table>
							<?php
								form_close();
							?></td>
					</tr>
					<?php
						if(count($order["notes"]) > 0){
							echo '	<tr>
										<td colspan="2">
											<div id="order_notes">';
						}
						
						$i = 0;
						foreach($order["notes"] as $n){ 
							if(isset($n["order_note"])){
    							if($n["isprivate"] == 1){
    								$class = 'private_note';
    							}elseif($n["isprivate"] == 2){
    								$class = 'system_note';
    							}else{
    								$class = 'order_note';
    							}
    							
    							$note = $n["order_note"];
    							if($n["filenm"] != ''){
    								$note .= '<br /><a href="/media/attachments/'.$n["filenm"].'" target="_blank">'.lang('br_attachment').'</a>';
    							}
    							
    							if($n["member_id"] == 0){
    								$by 	= $n["created"];
    								$remove = '';
    							}else{
    								$by 	= lang('br_posted_by').' <b><a href="'.BASE.'&C=myaccount&id='.$n["member_id"].'">'.$n["screen_name"].'</a></b> on '.$n["created"];
    								$remove = '<a href="'.$base_url.'&method=order_remove_note&order_id='.$n["order_id"].'&note_id='.$n["order_note_id"].'"><img src="'.$theme.'images/delete-grey.png" /></a>';
    							}
    							
								// prep note in case there are strings that are too long which will 
								// blow up the design. We do that by simply testing string length 
								// and injecting spaces when needed.
									// Break the note up into parts per word 
   	 									$arr = explode(" ",$note);
    								// Reset the note varialbe
  	  									$note = '';
									// Loop 
		    							foreach($arr as $a){
		    								if(strlen($a) > 80){
		    									if(strpos($a,"\n") !== false){
		    										$b = explode("\n",$a);
		    										foreach($b as $c){
		    											$note .= "\n".wordwrap($c,80," ",true);
		    										}
		    									}else{
		    										$note .= " ".wordwrap($a,80," ",true);
		    									}
		    								}else{
		    									$note .= " ".$a;
		    								}
		    							}
    								
    							echo '	<table width="100%" class="order_notes" cellpadding="0" cellspacing="0">
        									<thead>
        										<tr>
        											<th>
        												'.$by.'</th>
        											<th width="10%" style="text-align:right;padding:5px 5px 0">
        												'.$remove.'</th>
        										</tr>
        									</thead>
        									<tbody>
            									<tr class="'.$class.'">
            										<td colspan="2">
            											'.nl2br($note).'</td>
            									</tr>
        									</tbody>
        								</table>';
    							$i++;
							}
						}

						if(count($order["notes"]) > 0){
							echo '	</div>
											</td>
									</tr>';
						}
					?>
		</table>

<?php
	echo $br_footer;
?>
<script type="text/javascript">
	$(function(){
		$('#order_status').bind('click',
								function(){
									var a = $('#order_status_form');
									var b = $('#popin-overlay');
									if(a.is(':visible')){
										a.hide();
										b.hide();
									}else{
										a.css('marginLeft',$(this).width()+40).show();
										b.show();
									}
								});

		$('div.popin-top img,#popin-overlay').bind('click',function(){
										$('#popin-overlay').hide();
										$('#order_status_form').fadeOut();
									});
	});
</script>