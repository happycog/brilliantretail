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
                
                	<div id="b2r_settings">

						<?php
            	       		if($sub_selected == ''){
                				
                				echo '<div id="b2r_page" class="b2r_category overview">
											<table class="mainTable" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<th colspan="2">
														'.lang('br_config').'</th>
												</tr>';
								
								$i=1;
								foreach($submenu as $key => $val){
                            		$class = ($i % 2 == 0) ? 'even' : 'odd';
                            		echo '		<tr class="'.$class.'"> 
													<td class="overviewItemName"><a href="'.$val.'">'.lang('br_'.$key).'</a></td> 
													<td class="overviewItemDesc">'.lang('br_'.$key.'_instructions').'</td> 
												</tr>';
                            		$i++;
                            	}
								echo '		</table> 
											<div class="tableFooter"></div>		
											<div class="b2r_clearboth"><!-- --></div>
			                        	</div> <!-- b2r_optionals -->';
                        	}
							echo $content;
						?>
                    
                    </div> <!-- b2r_settings --> 
                    
                </div> <!-- b2r_panel_int -->
            </div> <!-- b2r_panel -->
            
    	</div> <!-- b2r_main -->
        <div class="b2r_clearboth"><!-- --></div>
        
        <?=$br_footer?>
        
    </div> <!-- b2r_content -->
    
</div> <!-- #b2retail -->