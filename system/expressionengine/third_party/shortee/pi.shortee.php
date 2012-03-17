<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
========================================================
Plugin Shortee Version 1.0
--------------------------------------------------------
Copyright: David Dexter (BrilliantRetail.com) 
License: Absolutely Freeware - Use It and Abuse It.... 
http://www.BrilliantRetail.com
--------------------------------------------------------
This addon may be used free of charge. Should you have 
the opportunity to use it for commercial projects then 
I applaud you! 
========================================================
File: pi.shortee.php
--------------------------------------------------------
Purpose: Grab and cache an is.gd url.
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


$plugin_info = array(  	'pi_name' => 'Shortee',
					    'pi_version' => '1.0',
					    'pi_author' => 'David Dexter',
					    'pi_author_url' => 'http://www.BrilliantRetail.com',
					    'pi_description' => 'Shortee caches returns an http://is.gd url. Developed for EE 2.0+ (Requires Curl)',
					    'pi_usage' => Shortee::usage());

class Shortee
{
    var $return_data;
	var $cache;
	var $link;
	var $title;
	var $text;
	var $link_id;
	var $link_class;
	var $link_target;
	
    function Shortee()
    {
        $this->EE =& get_instance();
		
		$link = $this->EE->TMPL->fetch_param('link');
		$text = ( ! $this->EE->TMPL->fetch_param('text')) ? '' :  $this->EE->TMPL->fetch_param('text');
		$attr["title"] 	= ( ! $this->EE->TMPL->fetch_param('title')) ? '' :  $this->EE->TMPL->fetch_param('title');
		$attr["id"] 	= ( ! $this->EE->TMPL->fetch_param('link_id')) ? '' :  $this->EE->TMPL->fetch_param('link_id');
		$attr["class"] 	= ( ! $this->EE->TMPL->fetch_param('link_class')) ? '' :  $this->EE->TMPL->fetch_param('link_class');
		$attr["target"]	= ( ! $this->EE->TMPL->fetch_param('link_target')) ? '' :  $this->EE->TMPL->fetch_param('link_target');
			
		// Cache path
			$this->cache = rtrim(APPPATH,"/").'/cache/shortee/';
			if(!file_exists($this->cache)){
				mkdir($this->cache);
			}
		
		// Lets check the cache 
        	$url = $this->full_url();
        	$cache_file = $this->cache.'shortee.'.md5($url).'.cache';
        	$short_url = $this->_check_cache($cache_file);
			
			if($short_url !== false){
				// Don't do anything. 
				// we already have it
			}else{
				
			    // Get the url
			    	$url = 'http://is.gd/api.php?longurl='.urlencode(rtrim($url));
			       	$curl = curl_init();
				    curl_setopt ($curl, CURLOPT_URL, $url);
				    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				    $short_url = curl_exec($curl);
				    curl_close($curl);
	
				$this->_save_cache($cache_file,$short_url);	
			
			}		
			if(strtolower($link) == 'true'){
				$tmp = '<a href="'.$short_url.'"  ';
				foreach($attr as $key => $val){
					if($val != ''){
						$tmp .= $key . '="'.trim($val).'" '; 	
					}
				}
				$text = (trim($text) != '') ? trim($text) : $short_url ;
				$tmp .= '>'.$text.'</a>';
				$short_url = $tmp;
			}			
			$this->return_data = $short_url;
    }
    
    function _check_cache($fl){
		if(file_exists($fl)){
			$fh = fopen($fl,'r');
			$content = fread($fh, filesize($fl));
			return $content;
		}else{
			return false;
		}        
	}
	
	function _save_cache($cache_file,$short_url){
		$fh = fopen($cache_file, 'w') or die("can't open Shortee cache file");
		fwrite($fh, $short_url);
		fclose($fh);
		return true;
	}

	function full_url(){
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	}

	function usage(){
		ob_start();
?>
Example:
----------------
{exp:shortee}

Parameters:
----------------
url: The url you want to shorten. (default = current url | optional)
link: If true the link will be wrapped into a link (default = false | optional)

If link you can set the anchor tag attributes:

title: (default = empty | optional)
text: (default = empty | optional)
link_id: (default = empty | optional)
link_class: (default = empty | optional)
link_target: (default = empty | optional)
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
  	/* END */
}
/* END Class */
/* End of file pi.shortee.php */
/* Location: ./system/expressionengine/third_party/Shortee/pi.Shortee.php */