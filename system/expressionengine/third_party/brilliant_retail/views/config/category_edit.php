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
?>
<style type="text/css">
	#close_search 
	{
		position:absolute;
		right:55px;
		margin-top:5px;
		cursor: pointer;
		display: none;
	}
</style>


<?php
	echo form_open_multipart($action,
        					 array(	
        					        'method' 	 => 'POST', 
        				            'id' 	 	 => 'category_update_order',
        							'class' 	 => ''
        							),
                             array(  
    		                         'action'        => 'update',
    		                         'category_id'   => $category["category_id"]
    		                     ));
?>
	

<div id="b2r_page" class="b2r_category">

<table id="category_update_tbl" cellpadding="0" cellspacing="0" class="product_edit" width="100%" style="margin-top:0;">
	<thead>
		<tr class="odd">
			<th width="30%">
				<?=lang('br_category_settings')?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
	    	<td class="cell_1">
	    		<?=lang('br_title')?> *</td>
	    	<td>
	        	<input name="title" id="title" value="<?=$category["title"]?>" type="text" class="{required:true}" /></td>
		</tr>	
    	<tr class="odd">
        	<td>
        		<?=lang('br_url_title')?> *</td>
        	<td>
	        	<input name="url_title" id="url_title" value="<?=$category["url_title"]?>" type="text" class="{required:true}" /></td>
	    </tr>	
	    <?php
	    /*
	    <tr>
	    	<td>
        		<?=lang('br_parent_category')?> *</td>
	    	<td>
	    		<?php
	    			foreach($categories[0] as $key => $val)
	    			{
	    				echo $val["title"];
	    				if(isset($categories[$key]))
	    				{
	    					foreach($categories[$key] as $k => $v)
	    					{
	    						echo $val["title"];
	    				}
	    				echo $key.' - '.$val.'<br />';
	    			}
	    			echo '<pre>';
	    			var_dump($categories);
	    			echo '</pre>';
	    		?></td>
	    </tr>
		*/
		?>
		<tr>
	    	<td>
	    		<?=lang('br_enabled')?></td>
	    	<td>
				<select name="enabled" id="enabled" title="input three" class="">
					<?php
						$sel = ($category["enabled"] == 1) ? 'selected="selected"' : '';
							echo '<option value="1" '.$sel.'>'.lang('br_yes').'</option>';
						$sel = ($category["enabled"] == 0) ? 'selected="selected"' : '';
							echo '<option value="0" '.$sel.'>'.lang('br_no').'</option>';
					?>
				</select></td>
		</tr>
		<tr class="odd">
        	<td>
        		<?=lang('br_details')?> *</td>
        	<td>
	        	<textarea name="detail" id="detail" class="ckeditor"><?=$category["detail"]?></textarea></td>
	    </tr>
		<tr>
	    	<td>
	    		<?=lang('br_images')?></td>
	    	<td>
				<?=$category["image"]?>
				<input type="file" name="image" id="image" /></td>
		</tr>	
	    <tr class="odd">
        	<td>
        		<?=lang('br_template_path')?></td>
        	<td>
            	<input name="template_path" id="template_path" value="<?=$category["template_path"]?>" type="text" /></td>
    	</tr>	
    </table>
    <table cellpadding="0" cellspacing="0"  class="product_edit" width="100%">
		<thead>
			<tr class="odd">
				<th colspan="2">
					<?=lang('br_meta_data')?></th>
			</tr>
		</thead>
		<tr class="odd">
        	<td>
        		<?=lang('br_meta_title')?></td>
        	<td>
            	<input name="meta_title" id="meta_title" value="<?=$category["meta_title"]?>" type="text" /></td>
    	</tr>	
    	<tr>
        	<td>
        		<?=lang('br_meta_descr')?></td>
        	<td>
            	<textarea name="meta_descr" id="meta_descr"><?=$category["meta_descr"]?></textarea></td>
    	</tr>	
    	<tr class="odd">
        	<td>
        		<?=lang('br_meta_keyword')?></td>
        	<td>
            	<textarea name="meta_keyword" id="meta_keyword" title="input two" class=""><?=$category["meta_keyword"]?></textarea></td>
        </tr>	     	
	</tbody>
</table>

<input type="hidden" name="items" id="items" />

<table id="product_sort_tbl"  class="product_edit" width="100%" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<td colspan="3">
				<img src="<?=$theme?>images/close.png" id="close_search" />
				<input type="text" id="br_search" placeholder="<?=lang('br_category_edit_product_search')?>" />
				<br />
				<div id="results_div" style="border:1px #CCC solid;margin-bottom:10px;height:200px;overflow:auto;display:none;">
					<table id="br_result" width="100%" cellpadding="0" cellspacing="0">
						<tbody></tbody>
					</table>				
				</div>
			</td>
		</tr>
		<tr class="odd">
			<th>
				<?=lang('br_category_products')?></th>
			<th width="5%"> 
				<?=lang('br_sort')?></th>
			<th width="5%" colspan="2"> 
				<?=lang('br_actions')?></th>
		</tr>
	</thead>
	<tbody id="selection">
	<?php 
		foreach($products as $items) 
		{ 
	?>
		<tr class="odd">
        	<td style="vertical-align:middle;">
        		<?= $items['title'] ?>
        	</td>
        	<td>
        		<input id="items_<?= $items['id']?>" class="input_numeric" value="<?= $items['sort_order']?>" type="text" /></td>
	    	<td>
	    		<a href="#" class="delete_product"><img src="<?=$theme?>images/delete.png"></a></td>
	    </tr>
	<?php 
		} 
	?>
	</tbody>
</table>
<div id="bottom_buttons">
	<input type="submit" class="submit" value="<?=lang('save_changes')?>" />
	<?=form_submit(array('name' => 'delete', 'id' => 'delete', 'value' => lang('delete'), 'class'=>'submit'))?>
</div>
</div>
</form>
<script type="text/javascript">
<!--
$(function() {
	
	_init_cat();
	
	$('#delete').bind('click',function(){
		if(confirm('<?=lang('br_confirm_delete_category')?>')){
			$('#action').val('delete');
			$('#category_update_order').submit();
		}
	});
	
	
	$('#br_search').keyup(function(e){
		var term = $(this).val();
		if(term.length < 2){
			//return;
		}

		$.getJSON("<?=$product_search?>",{'type':'related','term':term},function(data){
            	$('#br_result').find('tbody tr').remove();
				var i = 0;
				$.each(data, function(i,item){
            		i++;
            		$('<tr id="product_'+item.product_id+'">'+
            		  	'<td>'+item.title+'</td>'+
            		  	'<td width="10%">'+
            		  		'<a href="#" class="add_related" data-title="'+item.title+'" data-product_id="'+item.product_id+'" ><img src="<?=$theme?>images/add.png" /></a>'+
            		  	'</td>'+
            		  '</tr>').appendTo($('#br_result tbody'));
            	});
            	if(1 > 0){
            		$('#results_div').slideDown();
					$('#close_search').show();
				}
            	$('.add_related').unbind('click').bind('click',function(e){
					e.preventDefault();
					_add_row($(this).data('title'),$(this).data('product_id'));
				});
				
        	_init_cat();
        
        });
	}).keypress( function(e) {
		/* Prevent default */
		if ( e.keyCode == 13 )
		{
			return false;
		}
	});

});

function _init_cat()
{
	$('#close_search').bind('click',function(){
		$('#br_search').val('');
		$('#results_div').slideUp();
		$('#close_search').hide();
	});
	
	$('.delete_product').bind('click',
										function(e){
											e.preventDefault();
											$(this).parent().parent().remove();	
										});
	$('.input_numeric').change(_set_cat_order);
	_set_cat_order();
}

function _set_cat_order()
{
	$('#items').val('');
	var str = '';
	$( "input.input_numeric" ).each(function( index ) {
		var set_id = $( this ).attr('id').split('_');
		str +=  set_id[1] + ":" + $(this).val()+'|';
	});
	$('#items').val(str);
}

function _add_row(title,product_id)
{
	$('	<tr class="odd">'+
			'<td style="vertical-align:middle;">'
				+title+
			'</td>'+
			
			'<td>'+
        		'<input id="items_'+product_id+'" class="input_numeric" value="0" type="text" /></td>'+
			'<td>'+
			'<a href="#" class="delete_product"><img src="<?=$theme?>images/delete.png"></a></td>'+
		'</tr>').prependTo($('#product_sort_tbl tbody#selection'));
		$('#product_'+product_id).remove();
		_set_cat_order();
}

-->
</script>
