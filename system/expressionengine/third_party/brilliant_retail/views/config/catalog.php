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
	echo form_open('&D=cp&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_category_update',
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
				<?php
					echo '	<select id="select_config">';
        			foreach($submenu as $key => $val){
            			$sel = ($key == $sub_selected) ? 'selected="selected"' : '' ; 
            			echo '	<option value="'.$key.'" '.$sel.'>'.lang($key).'</option>'; 
            		}
            		echo '	</select>
                			<script type="text/javascript">
                				$(function(){
                					$(\'#select_config\').change(function(){
										window.location = \''.$base_url.'&&method=\'+$(this).val();
                					});
                				});	
                			</script>';
				?>
				<h3><?=lang('br_categories')?></h3>
				<p id="b2r_numprod" style="display:none"><span><b id="cat_sel"></b></span></p>
				<div class="b2r_clearboth"><!-- --></div>
    			<p class="b2r_addprod" style="display:none"><a href="#"><?=lang('br_edit')?></a></p>
				<div class="b2r_clearboth"><!-- --></div></td>
		</tr>
    </table>

	<div class="b2r_clearboth"><!-- --></div>

    <table class="mainTable" id="category_tbl">
    	<tbody>
	    	<tr>
	        	<td width="33%" style="width:33%">
	        	 	<div class="tree_container odd">
		        	 	<ul id="level_0" level="0" class="cat_list">
	        			<li class="add_item">
	        				<span><?=lang('br_add_category')?></span>
	        			</li>
		        		<?php
		        			if(isset($categories[0])){
			        			foreach($categories[0] as $key){	
									foreach($key as $val){
										$state = ($val["enabled"] == 0 ) ? 'disabled' : '';
										echo '	<li id="cat_'.$val["category_id"].'" class="cat_'.$val["parent_id"].' '.$state.'">
													<img src="'.$theme.'/images/icon_move.png" class="cat_handle"><span>'.$val["title"].'</span>
												</li>';
									}
								}
							}
		        		?>
		        		</ul>
					</div>
	        	</td>
	        	<td width="34%" style="width:34%">
		        	<div class="tree_container">
		        		<ul id="level_1" class="cat_list">
	        			<li class="add_item">
		        			<span><?=lang('br_add_category')?></span>
	        			</li>
		        		<?php
							if(isset($categories[1])){
			        			foreach($categories[1] as $key){	
									foreach($key as $val){
										$state = ($val["enabled"] == 0 ) ? 'disabled' : '';
										echo '	<li id="cat_'.$val["category_id"].'" class="cat_'.$val["parent_id"].' '.$state.'">
													<img src="'.$theme.'/images/icon_move.png" class="cat_handle"><span>'.$val["title"].'</span>
												</li>';
									}
								}
							}
		        		?>
						</ul>
					</div>
	        	</td>
	        	<td width="33%" style="width:33%">
	        		<div class="tree_container odd">
		        		<ul id="level_2" class="cat_list">
	        			<li class="add_item">
	        				<span><?=lang('br_add_category')?></span>
	        			</li>
		        		<?php
		        			if(isset($categories[2])){
			        			foreach($categories[2] as $key){	
									foreach($key as $val){
										$state = ($val["enabled"] == 0 ) ? 'disabled' : '';
										echo '	<li id="cat_'.$val["category_id"].'" class="cat_'.$val["parent_id"].' '.$state.'">
													<img src="'.$theme.'/images/icon_move.png" class="cat_handle"><span>'.$val["title"].'</span>
												</li>';
									}
								}
							}
		        		?>
		        		</ul>
					</div>
	        	</td>
	        </tr>
		</tbody>
</table>
</div>
</form>
<script type="text/javascript">
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
	$("#level_1,#level_2").hide();
	$('#cat_form').bind('submit',function(){ 
			return false; 
		});
	$('#add_cat').bind('submit',_save_new_cat);
});

function _bind_cat_sort(){
	$("#level_0,#level_1,#level_2").sortable( "destroy" ).sortable({ 
		start:function(){
		},
		
		items: 'li:not(.add_item)',
		axis: 'y',
		placeholder: 'ui-state-active',
		handle: '.cat_handle', 
		distance: 10, 
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
	$('	#level_0 li,#level_1 li,#level_2 li').not('.add_item').not('img')
			.unbind('click').bind('click',function(){
				
				var a = $(this);
				var b = a.parent().attr('id').split('_');
				var c = a.attr('id').split('_');
				var level = b[1] * 1;
				var next = level + 1;
				var cat_id = c[1];
				
				// deselect current and above
					for(i=level;i<=2;i++){
						$('li','#level_'+i).removeClass('tree_selected');
					}
				
				// Hide categories above
					if(level <= 2){
						for(i = 3;i>level;i--){
							$('li','#level_'+i).hide();
						}	
						$('#level_'+next).show().attr('level',cat_id);
					}
				
				// Add selection class
					a.addClass('tree_selected');
				
				// Show cats and create button
					$('.cat_'+cat_id+',.add_item','#level_'+next).show();
				
				// Show title and edit button in header 
					$('#cat_sel').html($(this).find('span').html());
					$('#b2r_numprod').show();
					$('.b2r_addprod').show().find('a').attr('href','<?=str_replace("&amp;","&",BASE)?>&C=addons_modules&M=show_module_cp&module=brilliant_retail&method=config_category_edit&cat_id='+cat_id);
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
							        	$('<li id="cat_'+data+'" class="cat_'+$('#parent_id').val()+'"><img src="<?=$theme?>images/icon_move.png" class="cat_handle"><span>'+cat_nm+'</span></li>').insertAfter(where);
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
</script>
