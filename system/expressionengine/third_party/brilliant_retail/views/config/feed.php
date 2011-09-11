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

if( count($feeds) > 0 )
{

  $cp_pad_table_template["table_open"] = '<table id="feeds_tbl" class="mainTable">';
	
  $this->table->set_template($cp_pad_table_template);
  $this->table->set_heading(
  	array(
  		'data' => lang('br_feed_title'),
  		'style' => 'width:160px'
  	),
  	array(	
  		'data' => lang('br_feed_code'),
  		'style' => ''
  	),
  	array(	
  		'data' => lang('br_products'),
  		'style' => 'width:60px'
  	)
  );
  
  foreach($feeds as $f){
  	$this->table->add_row(
  	  array(
  		  '<a href="'.$base_url.'&method=config_feed_edit&feed_id='.$f['feed_id'].'">'.$f['feed_title'].'</a>',
  			$f['feed_code'],
  			$f['feed_product_count'],							
  		)
  	);
  } 

  $feed_table = $this->table->generate();
}
?>

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
        <h3><?=lang('br_config_feeds')?></h3>
        <p id="b2r_numprod"><span><b><?= count($feeds);?></b></span></p>
        <div class="b2r_clearboth"><!-- --></div>
        <p class="b2r_addprod"><a href="<?=$base_url.AMP?>method=config_feed_edit"><?=lang('br_add')?></a></p>
      </td>
    </tr>
  </table>

<?php
if ( isset($feed_table) && $feed_table != '' )
{
  echo $feed_table;
}
else
{
?>
<p>No product feeds were found. To create your first product feed, <a href="<?= $base_url . AMP . 'method=config_feed_edit' ?>">click here</a>.
<?php
}
?>
                        	                     
<script type="text/javascript">
$(function(){
	var oTable = $('#feeds_tbl').dataTable({
    "bStateSave": true
  });
  $('<p class="b2r_search_btn"><a href="#" id="clear"><b><?=lang('br_clear')?></b></a></p>').insertBefore('#attributeTable_filter input');
  $('<div style="clear:both"></div>').insertAfter('#attributeTable_filter');
  $('#clear').click(function(){
    oTable.fnFilterClear();
    return false
  });
});
</script>
<div class="b2r_clearboth"><!-- --></div>
                        