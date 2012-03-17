<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2012						*/
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

// Begin the pricing tab
?>
<div class="publish_field" id="hold_br_price">

	<label class="hide_field">
		<span>
			<em class="required">*</em> <?=lang('br_price')?>
		</span>
	</label>

	<div id="sub_hold_br_price">
		<fieldset class="holder">
		
<table id="price_table" cellspacing="0" cellpadding="0" border="0" width="100%">
	<thead>
		<tr>
			<td colspan="6" align="right">
				<span class="button" style="float: right; margin: 0pt;">
					<a class="submit" href="#" id="price_add_option" style="color:#fff"><?=lang('br_add_option')?></a>
				</span></td>
		</tr>
		<tr>
			<th width="20%"><b><?=lang('br_member_group')?></b></th>
			<th width="20%"><b><?=lang('br_price')?></b></th>
			<th width="20%"><b><?=lang('br_start_dt')?></b></th>
			<th width="20%"><b><?=lang('br_end_dt')?></b></th>
			<th width="10%">&nbsp;</th>
			<th width="10%">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php
	$i=0;
	foreach($products[0]["price_matrix"] as $m){

		// Setup the member groups		
			$sel = ($m["group_id"] == 0) ? 'selected="selected"' : '' ;
			$group = '	<select name="price_group[]">
							<option value="0" '.$sel.'>'.lang('br_all_groups').'</option>';
				foreach($groups as $g){
					$sel = ($m["group_id"] == $g["group_id"]) ? 'selected="selected"' : '' ;
					$group .= '<option value="'.$g["group_id"].'" '.$sel.'>'.$g["group_title"].'</option>';
				}
			$group .= '	</select>';
		
		// Setup the quantity, date and remove options
		// by default lets not offer the options to the 
		// first row 
		
			$quantity 	= '<input type="hidden" name="price_qty[]" value="1" />';			
			if($i == 0){
				$start_dt 	= '<input type="hidden" name="price_start[]" value="" />&nbsp;';
				$end_dt 	= '<input type="hidden" name="price_end[]" value="" />&nbsp;';
				$move 	= '';
				$remove = '';
				$class = "nodrag nodrop";
			}else{
				$start = ($m["start_dt"] != '0000-00-00 00:00:00') ? date("m/d/Y",strtotime($m["start_dt"])) : '';
				$start_dt = form_input(
									array(	'name' => 'price_start[]', 
											'value' => $start, 
											'class' => 'datepicker')
									);
			
				$end = ($m["end_dt"] != '0000-00-00 00:00:00') ? date("m/d/Y",strtotime($m["end_dt"])) : '';
				$end_dt = form_input(
										array(	'name' => 'price_end[]', 
												'value' => $end, 
												'class' => 'datepicker')
										);
				$move 	= '<img src="'.$theme.'images/icon_move.png" />';
				$remove = '<a href="#delete" class="remove_price_row">'.lang('delete').'</a>';
				$class="";
			}
			
			echo "	<tr class='".$class."'>
									<td>
									".$quantity."
									".$group."</td>
									<td>
										".form_input(
											array(	'name' => 'price[]', 
													'class' => '{required:true}',
													'title' => lang('br_pricing').' - '.lang('br_price').' '.lang('br_is_required'),
													'value' => $m["price"])
											)."</td>
									<td>
										".$start_dt."</td>
									<td>
										".$end_dt."</td>
									<td class='move_price_row'>
										".$move."</td>
									<td style=\"text-align:center;\">
										".$remove."</td>
								</tr>";
		$i++;
	}
?>
				</tbody>
			</table>

		</fieldset>
	</div> <!-- /sub_hold_field -->
</div>
			
<?php

// We setup up these clone blocks for the javascript to 
// dynamically create rows. 

	$group = '	<select name="price_group[]">
					<option value="0" selected="selected">'.lang('br_all_groups').'</option>';
	foreach($groups as $g){
		$group .= '<option value="'.$g["group_id"].'">'.$g["group_title"].'</option>';
	}
	$group .= '	</select>';

	
	echo '	<div style="display:none" id="priceClone">
					<table>
						<tr>
							<td>
							<input type="hidden" name="price_qty[]" value="1" />
							'.$group.'</td>
							<td>
								'.form_input(
									array(	'name' 	=> 'price[]', 
											'class' => '{required:true}',
											'title' => lang('br_pricing').' - '.lang('br_price').' '.lang('br_is_required'),
											'value' => '')
									).'</td>
							<td>
								'.form_input(array(	'name' 	=> 'price_start[]', 
													'value' => '', 
													'class' => 'datepicker')
											).'</td> 
							<td>
								'.form_input(
											array(	'name' 	=> 'price_end[]', 
													'value' => '', 
													'class' => 'datepicker')
											).'</td>
							<td class="move_price_row">
								<img src="'.$theme.'images/icon_move.png" /></td>
							<td style=\"text-align:center;\">
								<a href="#delete" class="remove_price_row">'.lang('delete').'</a></td>
						</tr>			
					</table>
			</div>';

?>

<script type="text/javascript">
	$(function(){
		
		_restripe_products();
		
		$('#price_add_option').bind('click',function(){
			var opt = $('#priceClone table tr:first').clone().appendTo($('#price_table'));
			_restripe_products();
			return false;
		});
	});

	function _restripe_products(){
		$(".datepicker").removeClass('hasDatepicker').unbind().datepicker();
		$('#price_table tr').removeClass('even');
		$('#price_table tr:even').addClass('even');
		$('#price_table tbody').sortable({
							axis:'y', 
							items: "tr:gt(0)", 
							cursor:'move', opacity:0.6,
							helper:function(e, ui) {
								ui.children().each(function() {
									$(this).width($(this).width());
								});		
								return ui;
							},
							update:  _restripe_products
									});

		$('.remove_price_row').unbind().bind('click',function(){
			$(this).parent().parent().remove();
			_restripe_products();
			return false;
		});
	}		
</script>	
	
	