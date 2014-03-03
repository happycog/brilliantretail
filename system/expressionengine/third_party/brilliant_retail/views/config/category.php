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

echo form_open( $action,
				array(	'method' 	=> 'POST', 
						'id' 		=> 'add_cat',
						'class' 	=> '', 
						'encrypt' 	=> 'multipart/form-data'));
?>
    <input type="hidden" id="action" name="action" value="create" />
    <input type="hidden" id="order" name="order" />
    <input type="hidden" id="parent_id" name="parent_id" value="" />
    <input type="hidden" id="new_title" name="title" value="" />
    
<div id="b2r_page" class="b2r_category">
   
    <table id="admin_header" cellpadding="0" cellspacing="0">
    	<tr>
			<td>
    			<p class="b2r_addprod" style="display:none"><a href="#"><img src="<?=$theme?>images/move.png" /></a></p></td>
		</tr>
    </table>
    <table class="product_edit" width="100%" id="category_tbl" cellpadding="0" cellspacing="0">
    	<tbody>
	    	<tr>
	    		<th><?=lang('br_level_1')?></th>
	    		<th><?=lang('br_level_2')?></th>
	    		<th><?=lang('br_level_3')?></th>
	    		<th><?=lang('br_level_4')?></th>
	    	</tr>
	    	<tr>
	        	<?php
	        		for($i=0;$i<=3;$i++){
						$class = ($i % 2 == 0) ? 'odd' : '';
				?>
						<td width="25%" style="width:25%" class="tree_container <?=$class?>">
				        	 	<ul id="level_<?=$i?>" level="<?=$i?>" class="cat_list">
			        			<li class="add_item">
			        				<span><?=lang('br_add_category')?></span>
			        			</li>
				        		<?php
				        			if(isset($categories[$i])){
					        			foreach($categories[$i] as $key){	
											foreach($key as $val){
												$state = ($val["enabled"] == 0 ) ? 'disabled' : '';
												echo '	<li id="cat_'.$val["category_id"].'" class="cat_'.$val["parent_id"].' '.$state.'">
															<div class="cat_action"><a href="'.str_replace("&amp;","&",BASE).'&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_category_edit&cat_id='.$val["category_id"].'"><img src="'.$theme.'images/config.png" alt="Configure" title="Configure" /></a></div>
															<img src="'.$theme.'images/move.png" class="cat_handle">
															<span>'.$val["title"].'</span>
														</li>';
											}
										}
									}
				        		?>
				        		</ul>
			        	</td>
	        	<?php
	        		}
	        	?>
	        </tr>
		</tbody>
</table>
</div>
</form>
<script type="text/javascript">
<!--
	var options = {
						beforeSubmit:  _block_ui,
						success: function(data){
				        	var a = data.split('|');
							if(a[1] != ''){
								alert(a[1]); 
								$('#category_img').attr('src','/media/images/'+a[1]).show();
								$('#image').val('');
							}
				        	$.unblockUI();
			        	}
			        }
			        
	$(function() {
		_bind_cat_sort();
		_bind_cat_click();
		_bind_cat_add();
		$("#level_1,#level_2,#level_3").hide();
		$('#cat_form').bind('submit',function(){ 
				return false; 
			});
		$('#add_cat').bind('submit',_save_new_cat);

	});

	function _bind_cat_sort(){
		$("#level_0,#level_1,#level_2,#level_3").sortable( "destroy" ).sortable({ 
			start:function(){
			},
			items: 'li:not(.add_item)',
			axis: 'y',
			placeholder: 'ui-state-active',
			handle: '.cat_handle', 
			update: function(event, ui){
				if (this === ui.item.parent()[0]) {
					var ul_id = ui.item.parent().attr('id');
					var cat_id = ui.item.parent().attr('level');
					
					$('#order').val($('#'+ul_id).sortable('serialize'));
					$('#action').val('order');
					$('#add_cat').ajaxSubmit();
					ui.item.attr('class','cat_'+cat_id);
				}
			}
		});
	}

	function _bind_cat_click(){
		$('#level_0 li,#level_1 li,#level_2 li,#level_3 li')
				.not('.add_item')
				.not('img')
				.unbind('click')
				.bind('click',function(){
				
					var a = $(this);
					var b = a.parent().attr('id').split('_');
					var c = a.attr('id').split('_');
					var level = b[1] * 1;
					var next = level + 1;
					var cat_id = c[1];

					// deselect current and above
						for(i=level;i<=3;i++){
							$('li','#level_'+i).removeClass('tree_selected');
						}
					
					// Hide categories above
						if(level <= 3){
							for(i = 4;i>level;i--){
								$('li','#level_'+i).hide();
							}	
							$('#level_'+next).show().attr('level',cat_id);
						}
					
					// Add selection class
						a.addClass('tree_selected');
					
					// Show cats and create button
						$('.cat_'+cat_id+',.add_item','#level_'+next).show();
					
				});
	}
	function _bind_cat_add(){
		$(".add_item").bind('click',function(){
			$('#action').val('create');
			$(".add_item").unbind();
			$(this).html('<input type="text" name="new_cat" id="new_cat" style="width:93%;margin-bottom:4px" /> <div> <a href="#" class="add_cat"><?=lang('save')?></a> | <a href="#" class="cancel_cat"><?=lang('br_cancel')?></a></div>');
			$('#parent_id').val($(this).parent().attr('level'));
			
			$('.add_cat').bind('click',
								function(){
									$('#add_cat').submit();
								});
			
			$(".cancel_cat").bind('click',function(){
				$(this).parent().parent().html('<span><?=lang('br_add_category')?></span>');
				_bind_cat_add();
				return false;
			});
		});
	}
	
	function _save_new_cat(){
		var a 		= $('#new_cat');
		var where 	= a.parent().parent().find('li:eq(0)');
		var cat_nm 	= a.val();
		$('#new_title').val(cat_nm);
		a.parent().html('<span><?=lang('br_add_category')?></span>');
		$('#add_cat').ajaxSubmit({
										beforeSubmit:  _block_ui,
										success: function(data){
								        	$('<li id="cat_'+data+'" class="cat_'+$('#parent_id').val()+'"><div class="cat_action"><a href="<?=str_replace("&amp;","&",BASE)?>&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_category_edit&cat_id='+data+'"><img src="<?=$theme?>images/config.png" /></a></div><img src="<?=$theme?>images/move.png" class="cat_handle"><span>'+cat_nm+'</span></li>').insertAfter(where);
								        	$.unblockUI();
								        	_bind_cat_sort();
											_bind_cat_click();
											_bind_cat_add();
							        	}
							       });
		return false;
	}
	
	function _block_ui(){
		$.blockUI({ css: { 
			            border: 'none', 
			            padding: '8px', 
			            backgroundColor: '#000', 
			            '-webkit-border-radius': '8px', 
			            '-moz-border-radius': '8px', 
			            opacity: .5, 
			            color: '#fff' 
	        		} 
	        	});
	}
-->
</script>
