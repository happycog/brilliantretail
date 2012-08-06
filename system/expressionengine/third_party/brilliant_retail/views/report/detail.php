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

	echo $br_header;

	echo form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=report_detail&report='.$report,
					array(	'method' 	=> 'POST', 
							'id' 		=> 'report_'.$parent, 
							'encrypt' 	=> 'multipart/form-data'));
?>
		<input type="hidden" id="export" name="export" value="0" />

		<table id="admin_header" cellpadding="0" cellspacing="0">
	    	<tr>
				<td>
            		<?php if($input!="") {?>
            		<div id="report_edit"><a class="submit" href="<?=$base_url?>&method=report"><b><?=lang('edit')?></b></a></div>
            		<?php } ?>
					<div id="report_export"><a href="<?=$_SERVER["REQUEST_URI"]?>&export=csv" target="_blank"><b><?=lang('br_export_csv')?></b></a></div>
					<div class="b2r_clearboth"><!-- --></div>
        			<div id="report_inputs">
						<?=$input?>
						<input type="submit" class="submit" id="report_submit" name="submit" value="<?=lang('submit')?>" />
						<div class="b2r_clearboth"><!-- --></div>
					</div></td>
			</tr>
	    </table>

        <div class="b2r_clearboth"><!-- --></div>

		<?php
			if($detail["graph"] != ''){
				echo '<div align="center" id="report_graph" style="border:1px #ccc solid;padding:10px;width:90%"><img src="'.$detail["graph"].'" width="100%"  /></div>';
			}
		?>
		
		<table width="100%" class="mainTable" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<?php
						foreach($detail["header"] as $row){
							echo '<th>'.$row.'</th>';
						}
					?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<?php
						foreach($detail["footer"] as $row){
							echo '<th>'.$row.'</th>';
						}
					?>
				</tr>
			</tfoot>
			<tbody>
				<?php
					if(count($detail["results"]) == 0){
						echo '	<tr>
									<td colspan="'.count($detail["header"]).'">'.lang('br_no_results').'</td>
								</tr>';
					}
					foreach($detail["results"] as $row){
						echo '<tr>';
						foreach($row as $td){
							echo '<td>'.$td.'</td>';
						}
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
		            	
	</form>                     
        
<?=$br_footer?>

<script type="text/javascript">
	$(function(){
		$(".datepicker").datepicker();
		
		$('table.b2r_product_tbl tr:even').addClass('even');
		
		$('#report_edit a').bind('click',function(){
			$('#export').val(0);
			var a = $(this);
			var b = $('#report_inputs');
			if(b.css('display') == 'block'){
				a.html('<b><?=lang('edit')?></b>');
				b.slideUp();
			}else{
				a.html('<b><?=lang('br_cancel')?></b>');
				b.slideDown();
			}
			return false;
		});
		$('#report_export a').bind('click',function(){
			$('#export').val(1);
			$('#report_submit').trigger('click');
			return false;
		});
	});
</script>