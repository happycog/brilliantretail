<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010, Brilliant2.com 			*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.0 Beta							*/
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
<div id="b2retail">

	<?=$br_header?>
   
    <div id="b2r_content">

    	<?=$br_menu?>
        
        <div id="b2r_main">
        
            <?=$br_logo?>
            
            <div id="b2r_panel">
                
                <div id="b2r_panel_int">
                
                	<div id="b2r_products" class="br_contain">
							
							<div id="order_member">
                            <?php
                            	echo 	$member_photo. 
                            			'<h5>'.$order["member"]["br_fname"].' '.$order["member"]["br_lname"].' (<a href="'.$base_url.'&method=customer_detail&member_id='.$order["member_id"].'">'.$order["username"].'</a>)
                            			<br />
										<span><a href="mailto:'.$order["email"].'">'.$order["email"].'</a></span></h5>';
							?>
                            </div>
                            <div class="b2r_icon" id="order_id">
                            	<img src="<?=$theme?>images/icon_order_sm.png" />
                        	</div>
                        	
                        	<div id="order_status_id">
							    <?php
									echo form_open('D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=order_update_status&order_id='.$hidden["order_id"],array('method' => 'POST', 'id' => 'statusForm'),$hidden);
								?>
								<select name="status_id" id="status_id">
								<?php 
									ksort($status);
									foreach($status as $key => $val){
										$sel = ($key == $order["status_id"]) ? 'selected="selected"' : '';
										echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';  
									}
								?>
								</select><input type="submit" class="update" value="<?=lang('br_update')?>" />
								<br />
								<div style="margin:5px 0">
									<input type="checkbox" name="notify" style="float:left;" />&nbsp;<?=lang('br_status_notify')?>
								</div>
								</form>
							</div>
                        	
                        	<div class="b2r_clearleft"><!-- --></div>

							<h4 id="status_text">
                            	<?=lang('br_order_date')?> : <?=date("n/d/y",$order["created"])?><br />
                            	<?=lang('br_order_number')?> : <?=$order["order_id"]?> (<a href="<?=BASE?>&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=order_detail&order_id=<?=$order["order_id"]?>&print=true" target="_blank"><?=lang('br_print')?></a>)<br />
                            </h4>
                            <div class="b2r_clearboth"><!-- --></div>
                            
                            <div class="b2r_line"><!-- --></div>
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
															<?=$order["address"][0]["shipping_address2"]?><br />
															<?=$order["address"][0]["shipping_city"]?>, <?=$order["address"][0]["shipping_state"]?> <?=$order["address"][0]["shipping_zip"]?><br />
															<?=$order["address"][0]["shipping_country"]?>
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
															<?=$order["address"][0]["billing_address2"]?><br />
															<?=$order["address"][0]["billing_city"]?>, <?=$order["address"][0]["billing_state"]?> <?=$order["address"][0]["billing_zip"]?><br />
															<?=$order["address"][0]["billing_country"]?>
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
                            			<td width="50%">
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
                            			<td width="50%">
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
                              		<tr>
                            			<td colspan="2">
                            				<table width="100%" cellpadding="0" cellspacing="0">
                            					<tr>
                            						<th>
                            							<?=lang('br_order_notes')?></th>
                            					</tr>
                            					<tr>
                            						<td>
                            							<div id="order_note_form">
                            							<?php
                            								echo form_open_multipart('D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=order_add_note',array('method' => 'POST', 'id' => 'noteForm'),$hidden);
														?>
                            							<table width="100%"> 
                            								<tr>
                            									<td>
                            										<?=lang('br_order_note')?></td>
                            									<td>
																		<textarea name="order_note"></textarea>
                            									</td>
                            								</tr>
                            								<tr>
                            									<td>
                            										<?=lang('br_order_note_file')?></td>
                            									<td>
																		<input type="file" name="order_note_file" />
                            									</td>
                            								</tr>
                            								<tr>
                            									<td>
                            										<?=lang('br_note_notify')?></td>
                            									<td>
																	<input type="checkbox" name="order_note_notify" />
																</td>
                            								</tr>

                            								<tr>
                            									<td>&nbsp;</td>
                            									<td>
																	<input type="submit" value="<?=lang('br_add_note')?>" />
                            									</td>
                            								</tr>
                            							</table>
                            							<?php
                            								form_close();
                            							?>
                            							</div></td>
                            					</tr>
                            					<tr>
                            						<td>
                            							<table width="100%" id="order_notes" cellspacing="0">
                            					<?php
                            						foreach($order["notes"] as $n){ 
                            							if(isset($n["order_note"])){
	                            							$note = $n["order_note"];
	                            							if($n["filenm"] != ''){
	                            								$note .= '<br /><a href="/media/attachments/'.$n["filenm"].'" target="_blank">'.lang('br_attachment').'</a>';
	                            							}
	                            							echo '	<tr>
	                            										<td class="order_note">
	                            											'.date('n/d/y g:i:s a',$n["created"]).'</td>
	                            										<td class="order_note">
	                            											'.$note.'</td>
	                            										<td class="order_note">
	                            											<a href="'.$base_url.'&method=order_remove_note&order_id='.$n["order_id"].'&note_id='.$n["order_note_id"].'">'.lang('delete').'</a></td>
	                            									</tr>';
														}
                            						}
                            					?>
                            							</table>
                            							<p>&nbsp;</p>
                            						</td>
                            					</tr>
                            				</table></td> 
                            		</tr>                          		
                            		
                            	</table>

                        	<div class="b2r_clearboth"><!-- --></div>
        				
                    </div> <!-- b2r_dashboard --> 
                    
                </div> <!-- b2r_panel_int -->
            </div> <!-- b2r_panel -->

    	</div> <!-- b2r_main -->

        <div class="b2r_clearboth"><!-- --></div>
        
        <?=$br_footer?>
        
    </div> <!-- b2r_content -->
    
</div> <!-- #b2retail -->