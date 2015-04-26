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
<div id="b2r_dboard">
	<div id="b2r_graph">
        <?php
        	$i = 0;
			foreach($reports as $rep){
				$class = ($i != 0) ? 'nodisplay' : '';
				echo '	<div id="db_graph_'.$i.'" class="db_graph '.$class.'">
							<div>';
					
				if($rep["graph"] == '')
				{
					echo '<div class="no_graph"><p>'.lang('br_no_graph_availble').'</p></div>';
				}else{
					echo '<img src="'.$rep["graph"].'" width="100%" alt="'.$rep["title"].'" title="'.$rep["title"].'" />';
				}
		        
		        echo '		</div>
		                	<h1>	
		                		'.$currency_marker.$rep["total"].'</h1>
			                <h2><a href="'.$rep["link"].'">'.$rep["title"].'</a></h2>
			           	</div>';
				$i++;
			}
		?>
    </div> <!-- graph -->
	            <div id="b2r_orders">
	            	<h3>Orders</h3>
	                <ul>
	                <?php
	                	foreach($order_collection as $order){
	                		echo '	<li>
				                    	<p class="b2r_acct">
				                    		<a href="'.$base_url.'&method=order_detail&order_id='.$order["order_id"].'">'.$order["order_id"].'</a>
				                    	</p>
				                        <p class="b2r_name">
				                        	'.$order["customer"].'
				                        </p>
				                        <p class="b2r_amt">
				                        	'.$currency_marker.$order["total"].'
				                        </p>
				                        <div class="b2r_clearboth"><!-- --></div>
				                    </li>';
	                	}
	                ?>
	                	<li>
	                		<p class="b2r_all"><a href="<?=BASE?>&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=order"><?=lang('br_all_orders')?> &raquo;</a></p>
	                        <div class="b2r_clearboth"><!-- --></div>
						</li>
	                </ul>
	            </div> <!-- orders -->
	            
	            
	            <div id="b2r_sales">
	            	<ul id="db_graph_links">
	            		<?php
							$i=0;
	            			foreach($reports as $rep){
	            				$class = ($i == 0) ? 'b2r_active' : '';
      							echo '	<li class="'.$class.' db_graph_link" data-gid="'.$i.'" >
					                    	<h1>'.$currency_marker.$rep["total"].'</h1>
					                        <h2>'.$rep["title"].'</h2>
					                    </li>';
								$i++;
							}
						?>
	                </ul>
	                <div class="b2r_clearboth"><!-- --></div>
	            </div> <!-- b2r_sales -->
			</div>
		 	<div class="b2r_clearboth"><!-- --></div>

			<?=$br_footer?>

        </div> <!-- b2r_dashboard --> 


<script type="text/javascript">
	$(function(){
		$('#db_graph_links .db_graph_link').bind('click',function(){
			var a = $(this);
			var b = a.attr('data-gid');
			
			$('#db_graph_links .db_graph_link').removeClass('b2r_active');
			$('#b2r_graph .db_graph').hide();
			
			$('#db_graph_'+b).show();
			a.addClass('b2r_active');
		});
	});
</script>