<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
========================================================
Plugin Snippet Version 2.1
--------------------------------------------------------
Copyright: David Dexter (Brilliant2.com) 
License: Absolutely Freeware - Use It and Abuse It.... 
http://www.GetEE.com 
--------------------------------------------------------
This addon may be used free of charge. Should you have 
the opportunity to use it for commercial projects then 
I applaud you! 
========================================================
File: pi.tweetline.php
--------------------------------------------------------
Purpose: Create text snippet 
========================================================
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF
ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO
EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN
AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE
OR OTHER DEALINGS IN THE SOFTWARE.
========================================================
*/

$plugin_info = array(
						'pi_name'			=> 'Snippet',
						'pi_version'		=> '2.2',
						'pi_author'			=> 'David Dexter',
						'pi_author_url'		=> 'http://www.getee.com/',
						'pi_description'	=> 'Creates a simple snippet from a block of text. Community support available at <a href="http://getsatisfaction.com/getee" target="_blank">GetSatisfaction.com/GetEE</a>',
						'pi_usage'			=> snippet::usage()
					);


class Snippet {

	var $return_data = "";

    function snippet()
    {
		$this->EE =& get_instance();
		
		$total = ( ! $this->EE->TMPL->fetch_param('total')) ? '500' :  $this->EE->TMPL->fetch_param('total');
		$word = ( ! $this->EE->TMPL->fetch_param('word')) ? true :  $this->EE->TMPL->fetch_param('word');
		$ellipsis = ( ! $this->EE->TMPL->fetch_param('ellipsis')) ? '...' :  $this->EE->TMPL->fetch_param('ellipsis');

		if(!is_numeric($total)){
			$total = 500;
        }
        
        $str = strip_tags($this->EE->TMPL->tagdata);
        
        if(strlen($str) <= $total){
    		$this->return_data = $str;
        }else{
        	if($word === true){
        		$this->return_data = $this->truncate_str($str,$total).$ellipsis;
    		}else{
    			$this->return_data = substr($str,0,$total).$ellipsis;
    		}
        }
    }

   function truncate_str($str, $maxlen) 
	{ 
	  $newstr = substr($str, 0, $maxlen);
	  $newstr = substr($newstr, 0, strrpos($newstr," "));
	  return $newstr; 
	}  

// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.
//  Make sure and use output buffering

function usage()
{
ob_start(); 
?>
Wrap a block of test in the tag pair. The plugin will strip the tags and return a preview of the block with the 
total number of characters as set by the 'total' parameter. If the original text was longer than the total ellipsis will
be added. 

{exp:snippet total="100" word="true" ellipsis="..."}

The block of text that you want to snippet. 

{/exp:snippet}

Parameters: 

* {total} = default 500

* {word} = Tells the plugin if you want it to end the snippet on the last full word. Set to "true" by default.

* {ellipsis} = Set to '...' by default. You can pass any text that you want appended to the end of the snippet. 

<?php
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}
/* END */


}
// END CLASS
?>