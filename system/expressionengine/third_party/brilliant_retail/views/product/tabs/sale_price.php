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

// Begin the pricing tab
?>

<div class="publish_field" id="hold_br_sale_price">

	<label class="hide_field">
		<span>
			<?=lang('br_sale_price')?>
		</span>
	</label>

	<div id="sub_hold_br_sale_price">
		<fieldset class="holder">
<?php
	$hide_header = (count($products[0]["sale_matrix"]) == 0) ? 'style="display:none"' : '';
	
	$sale_price_matrix = '	<table id="sale_price_table" class="product_edit" cellspacing="0" cellpadding="0" border="0" width="100%">
								<thead>
									<tr class="nodrag no drop">
										<td colspan="6">
											<span class="button" style="float: right; margin: 0pt;">
												<a class="submit" href="#" id="sale_price_add_option" style="color:#fff">'.lang('br_add_option').'</a>
											</span></td>
									</tr>
									<tr>
										<th width="25%">'.lang('br_member_group').'</th>
										<th width="20%">'.lang('br_sale_price').'</th>
										<th width="20%">'.lang('br_start_dt').'</th>
										<th width="20%">'.lang('br_end_dt').'</th>
										<th width="5%" colspan="2">'.lang('actions').'</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan="6">
											'.lang('br_sale_price_empty').'</td>
									</tr>
								</tfoot>
								<tbody>';
	
	$i=0;
	foreach($products[0]["sale_matrix"] as $m){

		// Setup the member groups		
			$sel = ($m["group_id"] == 0) ? 'selected="selected"' : '' ;
			$group = '	<select name="sale_price_group[]">
							<option value="0" '.$sel.'>'.lang('br_all_groups').'</option>';
				foreach($groups as $g){
					$sel = ($m["group_id"] == $g["group_id"]) ? 'selected="selected"' : '' ;
					$group .= '<option value="'.$g["group_id"].'" '.$sel.'>'.$g["group_title"].'</option>';
				}
			$group .= '	</select>';
		
		// Setup the quantity, date and remove options
		// by default lets not offer the options to the 
		// first row 
	
			$quantity = '<input type="hidden" name="sale_price_qty[]" value="1" />';

			$start = ($m["start_dt"] != '0000-00-00 00:00:00') ? date("m/d/Y",strtotime($m["start_dt"])) : '';
			$start_dt = form_input(
								array(	'name' => 'sale_price_start[]', 
										'value' => $start, 
										'class' => 'datepicker')
								);
		
			$end = ($m["end_dt"] != '0000-00-00 00:00:00') ? date("m/d/Y",strtotime($m["end_dt"])) : '';
			$end_dt = form_input(
									array(	'name' => 'sale_price_end[]', 
											'value' => $end, 
											'class' => 'datepicker')
									);
			$move 	= '<img src="'.$theme.'images/move.png" />';
			$remove = '<a href="#delete" class="remove_sale_price_row"><img src="'.$theme.'images/delete.png" alt="'.lang('delete').'" title="'.lang('delete').'" /></a>';
			$class="";
		
			
		$sale_price_matrix .=	"	<tr class='".$class."'>
										<td>
											".$quantity." 
											".$group."</td>
										<td>
											".form_input(
												array(	'name' => 'sale_price[]', 
														'class' => '{required:true}',
														'title' => lang('br_pricing').' - '.lang('br_sale_price').' '.lang('br_is_required'),
														'value' => $m["price"])
												)."</td>
										<td>
											".$start_dt."</td>
										<td>
											".$end_dt."</td>
										<td class='move_sale_price_row'>
											".$move."</td>
										<td style=\"text-align:center;\">
											".$remove."</td>
									</tr>";
		$i++;
	}
	$sale_price_matrix .= '		</tbody>
							</table>';

	echo $sale_price_matrix;
?>

		</fieldset>
	</div> <!-- /sub_hold_field -->
</div>

<?php		
	// We setup up these clone blocks for the javascript to 
	// dynamically create rows. 

	$group = '	<select name="sale_price_group[]">
							<option value="0" selected="selected">'.lang('br_all_groups').'</option>';
	foreach($groups as $g){
		$group .= '<option value="'.$g["group_id"].'">'.$g["group_title"].'</option>';
	}
	$group .= '	</select>';

	echo '	<div style="display:none" id="sale_priceClone">
					<table>
						<tr>
							<td>
								<input type="hidden" name="sale_price_qty[]" value="1" />  
								'.$group.'</td>
							<td>
								'.form_input(
									array(	'name' => 'sale_price[]', 
											'title' => lang('br_pricing').' - '.lang('br_sale_price').' '.lang('br_is_required'),
											'value' => '')
									).'</td>
							<td>
								'.form_input(array(	'name' => 'sale_price_start[]', 
													'value' => '', 
													'class' => 'datepicker')
											).'</td> 
							<td>
								'.form_input(
											array(	'name' => 'sale_price_end[]', 
													'value' => '', 
													'class' => 'datepicker')
											).'</td>
							<td class="move_sale_price_row">
								<img src="'.$theme.'images/move.png" /></td>
							<td style=\"text-align:center;\">
								<a href="#delete" class="remove_sale_price_row"><img src="'.$theme.'images/delete.png" alt="'.lang('delete').'" title="'.lang('delete').'" /></a></td>
						</tr>			
					</table>
			</div>';

?>
<script type="text/javascript">
	$(function(){
		
		_sale_price_restripe();
		
		$('#sale_price_add_option').bind('click',function(){
			var opt = $('#sale_priceClone table tr:first').clone().appendTo($('#sale_price_table'));
			$('#sale_header').show();
			_sale_price_restripe();
			return false;
		});
	});

	function _sale_price_restripe(){
		
		var rows = $('#sale_price_table tbody tr').size();
		if(rows == 0){
			$('#sale_price_table tfoot').show();
		}else{
			$('#sale_price_table tfoot').hide();
		}
		
		$(".datepicker").removeClass('hasDatepicker').unbind().datepicker();
		$('#sale_price_table tr').removeClass('odd');
		$('#sale_price_table tr:odd').addClass('odd');
		
		$('#sale_price_table tbody').sortable({axis:'y', cursor:'move', opacity:0.6,
							helper:function(e, ui) {
								ui.children().each(function() {
									$(this).width($(this).width());
								});		
								return ui;
							},
							update:  _sale_price_restripe
									});

		$('.remove_sale_price_row').unbind().bind('click',function(){
			$(this).parent().parent().remove();
			var size = $('#sale_price_table tbody tr').size();
			if(size == 1){
				$('#sale_header').hide();
			}
			_sale_price_restripe();
			return false;
		});
	}		
</script>	