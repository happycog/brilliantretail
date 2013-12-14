<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/************************************************************/
/*	BR Shorthand	 										*/
/*															*/
/*	@package	BR Shorthand								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2013						*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0									*/
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
include_once(PATH_THIRD.'brilliant_retail/mod.brilliant_retail.php');

$plugin_info = array(  	'pi_name' => 'BR Shorthand',
                        'pi_version' => '1.0.0',
                        'pi_author' => 'David Dexter',
                        'pi_author_url' => 'http://www.brilliantretail.com',
                        'pi_description' => 'BR Short hand creates a simplified class declaration for BrilliantRetail tags by replacing the brilliant_retail portion of the tag with "br"',
                        'pi_usage' => Br_usage::instructions() );

class br extends Brilliant_retail
{
    public function __construct()
    {
        parent::__construct();
    }
}

class Br_usage
{
    public function instructions()
    {
            ob_start();
?>
Example:
----------------
Replace any calls to a brilliant_retail tag with br.

The current method for calling the logo:

<a href="{exp:brilliant_retail:path src=''}"><img src="{exp:brilliant_retail:logo}" alt="{site_name}" /></a>

Can be replaced with:

<a href="{exp:br:path src=''}"><img src="{exp:br:logo}" alt="{site_name}" /></a>
<?php
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
}
