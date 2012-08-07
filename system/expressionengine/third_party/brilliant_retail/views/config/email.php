<?php
/************************************************************/
/*  BrilliantRetail                                         */
/*                                                          */
/*  @package    BrilliantRetail                             */
/*  @Author     David Dexter                                */
/*  @copyright  Copyright (c) 2010-2012                     */
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
?>
<div id="b2r_page" class="b2r_category">
    <table id="emailTable" class="product_edit" width="100%" cellpadding="0" cellspacing="0" style="clear:both">
        <thead>
            <tr>
                <th>
                    <?=lang("br_title")?></th>
                <th style="width:100px">
                    <?=lang('version')?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($emails as $e){
                    echo '  <tr>
                                <td>
                                    <a href="'.$base_url.AMP.'method=config_email_edit&email_id='.$e["email_id"].'">'.lang('br_'.$e["title"]).'</a></td>
                                <td>
                                    '.$e["version"].'</td>
                            </tr>';
                }
            ?>
        </tbody>
    </table>
</form>

<script type="text/javascript">
    $(function(){
        var oTable = $('#emailTable').dataTable({
                                                    "bStateSave": true
                                                });
        $('<p class="b2r_search_btn"><a href="#" id="clear"><b><?=lang('br_clear')?></b></a></p>').insertBefore('#emailTable_filter input');
        $('#clear').click(function(){
                                        oTable.fnFilterClear();
                                        return false
                                    });
    });
</script>