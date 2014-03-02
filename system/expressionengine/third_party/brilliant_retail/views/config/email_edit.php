<?php
/************************************************************/
/*  BrilliantRetail                                         */
/*                                                          */
/*  @package    BrilliantRetail                             */
/*  @Author     David Dexter                                */
/*  @copyright  Copyright (c) 2010-2013                     */
/*  @license    http://brilliantretail.com/license.html     */
/*  @link       http://brilliantretail.com                  */
/*                                                          */
/************************************************************/
/* NOTICE                                                   */
/*                                                          */
/* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF    */
/* ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED  */
/* TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A      */
/* PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT      */
/* SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY */
/* CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION  */
/* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR  */
/* IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER      */
/* DEALINGS IN THE SOFTWARE.                                */  
/************************************************************/

echo form_open( $action,
                array(  'method'    => 'POST', 
                        'id'        => 'email_edit',
                        'class'     => 'b2r_category', 
                        'encrypt'   => 'multipart/form-data'),
                array(  'title'		=> $email["title"], 
                		'email_id' 	=> $email["email_id"]));
?>
<div id="b2r_page" class="b2r_category">
    <table id="attribute_tbl" class="product_edit" width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="35%">
                    <?=lang("br_".$email["title"])?></th>
				<th>
					&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr class="odd">
                <td class="cell_1">
                    <?=lang('br_subject')?> *</td>
                <td class="cell_2">
                    <input type="text" id="subject" name="subject" value="<?=$email["subject"]?>" class="{required:true}" /></td>
            </tr>
            <tr>
                <td class="cell_1">
                    <?=lang('br_from_name')?> *</td>
                <td class="cell_2">
                    <input type="text" id="from_name" name="from_name" value="<?=$email["from_name"]?>" class="{required:true}"  />
            </tr>
            <tr>
                <td class="cell_1">
                    <?=lang('br_from_email')?> *</td>
                <td class="cell_2">
                    <input type="text" id="from_email" name="from_email" value="<?=$email["from_email"]?>" class="{required:true,email:true}"  />
            </tr>
            <tr class="odd">
                <td class="cell_1">
                    <?=lang('br_bcc_list')?></td>
                <td class="cell_2">
                    <input type="text" id="bcc_list" name="bcc_list" value="<?=$email["bcc_list"]?>" class=""  />
            </tr>
            <tr>
                <td colspan="2">
                    <?php /* <p style="padding: 4px 0;"><a href="#" id="var_show"><?=lang('br_show_email_vars')?></a></p> */ ?>
                    <div id="var_list">
                        <?=lang('br_email_var_instructions')?>
                        <br />
                        <ul>
                        <?php
                            $vars = array('domain','current_time','company_title');
                            sort($vars);
                            foreach($vars as $v){
                                echo '<li><strong>&#123;'.$v.'&#125;</strong> - '.lang('br_email_var_'.$v).'</li>';
                            }
                        ?>
                        </ul>
                    </div>
                    <textarea id="content" name="content" class="ckeditor" style="height: 450px"><?=form_prep($email["content"])?></textarea></td>
            </tr>
        </tbody>
    </table>
    <div id="bottom_buttons">
        <?=form_submit(array('name' => 'submit', 'value' => lang('br_save_continue'), 'class'=>'submit'))?>
        <?=form_submit(array('name' => 'submit', 'value' => lang('save'), 'class'=>'submit'))?>
        <div class="b2r_clearboth"><!-- --></div>
    </div>
</form>                     
<style type="text/css">
    #var_list {
        display:none;
        border: 1px #ccc solid;
        padding: 10px;
        background: #F7F7F7;
    }
    #var_list li {
        padding: 3px 0;
    }
</style>
<script type="text/javascript">
    $(function(){
        $('#email_edit').validate();
        $('#var_show').bind('click',function(){
            var a = $(this);
            var b = $('#var_list');
            if(b.css('display') == 'block'){
                a.html('<?=lang('br_show_email_vars')?>')
                b.slideUp();
            }else{
                a.html('<?=lang('br_hide_email_vars')?>')
                b.slideDown();
            }
            return false;
        });
    });
</script>