<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2013						*/
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

// Make address a little more usable;
	$address = $order["address"][0];
?>

<?=$br_header?>

<?php
	
	echo form_open_multipart(	'&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=order_detail_add_payment_process',
								array(	'method' 	=> 'POST', 
										'id' 		=> 'paymentForm'),
								$hidden);

?>
	<table cellspacing="0" id="add_payment_table" cellpadding="0" border="0" class="mainTable">
		<thead>
			<tr>
				<th width="30%"><?=lang('br_add_payment_form')?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?=lang('br_order_id')?></td>
				<td><?=$hidden["order_id"]?></td>
			</tr>
			<tr>
				<td><?=lang('br_total')?></td>
				<td><?=$currency_marker?><?=$order["order_total"]?></td>
			</tr>
			<tr>
				<td><?=lang('br_total_paid')?></td>
				<td><?=$currency_marker?><?=$order["order_total_paid"]?></td>
			</tr>
			<tr>
				<td><?=lang('br_total_due')?></td>
				<td><b><?=$currency_marker?><?=$order["order_total_due"]?></b></td>
			</tr>
			<tr>
				<td style="vertical-align:top">
					<?=lang('br_billing_address1')?></td>
				<td>
					<label>First Name *</label>
					<input 	class="txtinp required" name="br_billing_fname" value="<?=$address["billing_fname"]?>" type="text" />
					<label>Last Name *</label>
					<input 	class="txtinp required" name="br_billing_lname" value="<?=$address["billing_lname"]?>" type="text" />
					<label>Company</label>
					<input 	class="txtinp" name="br_billing_company" value="<?=$address["billing_company"]?>" type="text" />
					<label>Telephone *</label>
					<input 	class="txtinp required" name="br_billing_phone" value="<?=$address["billing_phone"]?>" type="text" />
					<label>Address 1*</label>
					<input 	class="txtinp required" name="br_billing_address1" value="<?=$address["billing_address1"]?>" type="text" />
					<label>Address 2</label>
					<input 	class="txtinp" name="br_billing_address2" value="<?=$address["billing_address2"]?>" type="text" />
					<label>Country *</label>
					<select name="br_billing_country" id="br_billing_country" class="required">
						<?php
							foreach($countries as $key => $val){
								$sel = ($key == $address["billing_country"]) ? 'selected="selected"' : '' ;
								echo '<option value="'.$key.'" class="{zone_id:'.$val["zone_id"].'}" '.$sel.'>'.$val["title"].'</option>';
							}
						?>
					</select>
					<div class="clearboth"></div>
					<label>City *</label>
					<input class="txtinp required" name="br_billing_city" value="<?=$address["billing_city"]?>" type="text" />
					<label>State *</label> 
					<select name="br_billing_state" id="br_billing_state" class="required" data-br_selected="<?=$address["billing_state"]?>" data-br_country="br_billing_country"></select>
					<div class="clearboth"></div>
					<label>Zip Code *</label>
					<input class="txtinp required" name="br_billing_zip" id="br_billing_zip" value="<?=$address["billing_zip"]?>" type="text" /></td>
			</tr>
			<tr>
				<td><?=lang('br_order_amount')?></td>
				<td>
					<input type="hidden" name="order_total_due" value="<?=$order["order_total_due"]?>" />
					<?=$currency_marker?><input type="text" name="order_amount" style="width: 100px" value="<?=$order["order_total_due"]?>" />
					<em><?=lang('br_add_payment_instructions')?></em></td>
			</tr>
			<tr id="checkout">
				<td style="vertical-align:top">
					<?=lang('br_add_payment_form')?></td>
				<td class="col2">
					<?=$order["payment_options"]?></td>
			</tr>	
			<?php 
				/*
					<tr>
						<td style="vertical-align:top">
							<?=lang('br_add_payment_form')?></td>
						<td class="col2">
							notes form</td>
					</tr>							
				*/
			?>
		</tbody>
	</table>

	<ul id="publish_submit_buttons">
		<li><input type="submit" class="submit" name="submit" id="submit_button" value="<?=lang('submit')?>" /></li>
	</ul>

</form>

<?=$br_footer?>

<style type="text/css">
	.gateways { margin-bottom: 10px;}
	.clearboth { clear: both; }

#checkout label {
	margin: 0 0 0 2px;
	font-size: 14px;
	color: #505757;
}
#checkout .col2 .general {
	padding: 5px 0 0 5px;
	width: 240px; }

#checkout .col2 .payment_form {
	display:none;
	padding: 0 0 10px 0;
}

#checkout .col2 .general .txtinp {
	width: 228px; }

#checkout .col2 .general select {
	margin: 3px 0 0;
	width: 238px; }

#checkout .col2 .expdate_month {
	float: left;
	display: inline;
	padding: 5px 0 0 5px;
	width: 160px; }

#checkout .col2 .expdate_month select {
	width: 148px; }

#checkout .col2 .expdate_year {
	float: left;
	display: inline;
	padding: 5px 0 0;
	width: 80px; }

#checkout .col2 .expdate_year select {
	width: 78px; }
	
#checkout .col2 .card_code {
	padding: 5px 0 0 5px;
	width: 240px; 
}

#checkout .col2 .card_code .txtinp{
	width: 50px;
}
</style>
<script type="text/javascript">

	$(function(){
		// stripe things 
			$('#paymentForm').validate();
		
		// bind the payment options
			_bind_payment_options();

		// Create our address selectors
			var selects = $('select[data-br_country]'),
			country_state_map = <?=$map?>;
			
			selects.each(function() {
				var select = $(this),
					country = $( '#'+select.data('br_country') );
				
				// when the country changes, populate the states
				// trigger the first change right away to update
				country.change(function() {
					var str = '<option value=""><?=lang('br_select_a_state')?></option>',
						country = this.options[this.selectedIndex].text;
					
					$.each(country_state_map[country], function(k, v) {
						str += '<option value="'+k+'">'+v+'</option>';
					});
					
					select.empty().append(str);
					select.val(select.data('br_selected'));
					
				}).triggerHandler('change');
			});
	});

// get all tied selects
	function _bind_payment_options(){
		var first = $('.payment_form:eq(0)');
		if(first.html() != ''){
			first.show();
		}
		$('.gateway').unbind().bind('click',function(){
			$('.payment_form:visible').hide();
			var a = $(this).parent().parent();
			var b = $('.payment_form',a);
			if(b.html() != ''){
				b.show();
			} 
		});
	}
</script>