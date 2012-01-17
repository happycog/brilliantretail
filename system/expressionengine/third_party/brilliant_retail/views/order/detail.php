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
                            			'<h5>'.$order["member"]["br_fname"].' '.$order["member"]["br_lname"].'
                            			<br />
										<span><a href="mailto:'.$order["member"]["email"].'">'.$order["member"]["email"].'</a></span></h5>';
							?>
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
								</select><input type="submit" class="update" value="<?=lang('update')?>" />
								<br />
								<div style="margin:5px 0">
									<input type="checkbox" name="notify" style="float:left;" />&nbsp;<?=lang('br_status_notify')?>
								</div>
								</form>
							</div>
                        	
                        	<div class="b2r_clearleft"><!-- --></div>

							<h4 id="status_text">
                            	<?=lang('br_order_date')?> : <?=date("n/d/y",$order["created"])?><br />
                            	<?=lang('br_order_number')?> : <?=$order["order_id"]?> (<a href="<?=BASE?>&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=order_detail&order_id=<?=$order["order_id"]?>&print=true" target="_blank"><?=lang('print')?></a>)<br />
                            </h4>
                            <div class="b2r_clearboth"><!-- --></div>
                            
                            <div class="b2r_line"><!-- --></div>
                            	<table id="order_table" width="100%" cellpadding="0" cellspacing="15">
                            		<tr>
                            			<td width="50%">
                            				<table width="100%" class="subOrder" cellpadding="0" cellspacing="0">
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
                            				<table width="100%" class="subOrder" cellpadding="0" cellspacing="0">
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
													<td colspan="3" style="text-align:right">
														<b><?=lang('br_subtotal')?> :</b><br />
														<b><?=lang('br_discount')?> :</b><br />
														<b><?=lang('br_shipping')?> :</b><br />
														<b><?=lang('br_tax')?> :</b><br />
														<b><?=lang('br_total')?> :</b></td>
													<td>
														<?=$currency_marker?><?=$order["base"]?><br />
														<?=$currency_marker?><?=$order["discount"]?><br />
														<?=$currency_marker?><?=$order["shipping"]?><br />
			                            				<?=$currency_marker?><?=$order["tax"]?><br />
			                            				<?=$currency_marker?><?=number_format(($order["total"]+$order["tax"]+$order["shipping"]),2)?></td>
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
                            							<table width="100%" cellpadding="0" cellspacing="0"> 
                            								<tr>
                            									<td>
                            										<b><?=lang('br_order_note')?></b></td>
                            									<td>
																		<textarea name="order_note"></textarea>
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
                            						$i = 0;
                            						foreach($order["notes"] as $n){ 
                            							if(isset($n["order_note"])){
	                            							$class = ($i % 2 == 0) ? 'even' : 'odd';
	                            							$note = $n["order_note"];
	                            							if($n["filenm"] != ''){
	                            								$note .= '<br /><a href="/media/attachments/'.$n["filenm"].'" target="_blank">'.lang('br_attachment').'</a>';
	                            							}
	                            							echo '	<tr class="'.$class.'">
	                            										<td width="20%">
	                            											'.date('n/d/y g:i:s a',$n["created"]).'</td>
	                            										<td width="*">
	                            											'.$note.'</td>
	                            										<td width="10%">
	                            											<a href="'.$base_url.'&method=order_remove_note&order_id='.$n["order_id"].'&note_id='.$n["order_note_id"].'">'.lang('delete').'</a></td>
	                            									</tr>';
	                            							$i++;
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