<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
========================================================
Plugin TweetLine Version 2.3
--------------------------------------------------------
Copyright: David Dexter (Brilliant2.com) 
License: Absolutely Freeware - Use It and Abuse It.... 
http://www.brilliant2.com 
--------------------------------------------------------
This addon may be used free of charge. Should you have 
the opportunity to use it for commercial projects then 
I applaud you! 
========================================================
File: pi.tweetline.php
--------------------------------------------------------
Purpose: Grab a feed from twitter for a give user
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


$plugin_info = array(  	'pi_name' => 'TweetLine',
					    'pi_version' => '2.3',
					    'pi_author' => 'David Dexter',
					    'pi_author_url' => 'http://www.brilliant2.com',
					    'pi_description' => 'TweetLine returns a set of status tweets for a given user. Developed for EE 2.0+ (Requires Curl on the server)',
					    'pi_usage' => tweetline::usage());

class Tweetline
{
    var $return_data;
	var $cache = '';
    function tweetline()
    {
        $this->EE =& get_instance();
		
		// Cache path
			$this->cache = rtrim(APPPATH,"/").'/cache/tweetline/';
			if(!file_exists($this->cache)){
				mkdir($this->cache);
			}
		
		// Get the username
			$username = ( ! $this->EE->TMPL->fetch_param('username')) ? 'brilliant2' :  strtolower($this->EE->TMPL->fetch_param('username'));
        	if($username == ''){
	        	$username = 'brilliantretail';
        	}

		// Show retweets and mentions?
			$retweets = ( ! $this->EE->TMPL->fetch_param('retweets')) ? 'yes' :  strtolower($this->EE->TMPL->fetch_param('retweets'));
			$mentions = ( ! $this->EE->TMPL->fetch_param('mentions')) ? 'yes' :  strtolower($this->EE->TMPL->fetch_param('mentions'));
        
        // Automatically format linsk?
        	$auto_format = ( ! $this->EE->TMPL->fetch_param('format')) ? 'no' :  strtolower($this->EE->TMPL->fetch_param('format'));
        
        // What's the limit?
			$limit = ( ! $this->EE->TMPL->fetch_param('limit')) ? 5 : $this->EE->TMPL->fetch_param('limit');

		// What is the cache time in minutes
		// The value can't be less than 3 due to 
		// Twitters restriction on API calls per hour
			$cache_time = ( ! $this->EE->TMPL->fetch_param('cache')) ? 10 :  $this->EE->TMPL->fetch_param('cache');
			if($cache_time < 3){
				$cache_time = 3;
			}
		
        // Lets check the cache 
        	
        	$content = $this->_check_cache($this->cache.'tweetline.'.$username.'.cache');
			$content = @unserialize($content);
			if($content !== false){
				$tm = (time() - $content["cachestamp"]) / 60;
				if($tm <= $cache_time){
					unset($content["cachestamp"]);
					$xml = $content;
				}
			}
			
			if(!isset($xml)){
			
		        // Get the rss feed from twitter rest api
		        // I used the rss format so that RT's are 
		        // not stripped out. 
	        	
	        	$url = 'http://api.twitter.com/1/statuses/user_timeline/'.$username.'.rss';
		        $curl = curl_init();
			    curl_setopt ($curl, CURLOPT_URL, $url);
			    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			    $result = curl_exec($curl);
			    curl_close($curl);
		
				$xml = $this->simpleXMLToArray(simplexml_load_string($result));
				
				// Save the results to cache
				$this->_save_cache($this->cache.'tweetline.'.$username.'.cache',$xml);
			}
	
				// Set an empty $variables array just in case there
				// is an issue with Twitter
					
				if(isset($xml["error"])){
					$variables[0] = array(
												'post' 		=> '',
												'link' 		=> '',
												'date' 		=> '',
												'rel_date' 	=> '' 
											);
	
				}else{
				// Build the parse variables
		
					$i = 1;
					foreach($xml["channel"]["item"] as $items){
						$show = true;
						$post = substr($items["title"],strlen($username)+2);
						
						if(substr($post,0,1) == '@'){
							if($mentions != 'yes'){
								$show = false;
							}
						}else if(substr($post,0,2) == 'RT'){
							if($retweets != 'yes'){
								$show = false;
							}
						}
						if($show == true){
							if($i <= $limit){
								$link = (array)$items["link"];
								$post = substr($items["title"],strlen($username)+2);
								if($auto_format == 'yes'){
									$post = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank">$1$2$4</a>', $post);
									$post = preg_replace("/@(\w+)/", '<a href="http://www.twitter.com/\1" target="_blank">@\\1</a>', $post);
									$post = preg_replace("/#(\w+)/", '<a href="http://search.twitter.com/search?q=\1" target="_blank">#\\1</a>', $post);
								}
								$variables[] = array(
									'post' => $post,
									'link' 	=> $link[0], 
									'date' 	=> date("U",strtotime($items["pubDate"])),    
									'rel_date' => $this->_build_relative_time(date("U",strtotime($items["pubDate"])))
									);					
							}
							$i++;
						}
					}
				}
						
		// Parse the goodness 
			$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $variables); 
		   	$this->return_data = $output;
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
	
	function _save_cache($fl,$vars){
		$vars['cachestamp'] = time();
		$fh = fopen($fl, 'w') or die("can't open tweetline cache file");
		$str = serialize($vars);
		fwrite($fh, $str);
		fclose($fh);
		return true;
	}

    function _build_relative_time($time){
   		$diff = time() - $time;
		if ($diff < 60) {
			return 'less than a minute ago';
		}else if($diff < 120) {
			return 'about a minute ago';
		}else if($diff < (60*60)) {
			return round($diff / 60,0) . ' minutes ago';
		}else if($diff < (120*60)) {
			return 'about an hour ago';
		} else if($diff < (24*60*60)) {
			return 'about ' + round($diff / 3600,0) . ' hours ago';
		} else if($diff < (48*60*60)) {
			return '1 day ago';
		} else {
			return round($diff / 86400,0) . ' days ago';
		}
    }
    
     function simpleXMLToArray($xml,
                    $flattenValues=true,
                    $flattenAttributes = true,
                    $flattenChildren=true,
                    $valueKey='@value',
                    $attributesKey='@attributes',
                    $childrenKey='@children'){

        $return = array();
        if(!($xml instanceof SimpleXMLElement)){return $return;}
        $name = $xml->getName();
        $_value = trim((string)$xml);
        if(strlen($_value)==0){$_value = null;};

        if($_value!==null){
            if(!$flattenValues){$return[$valueKey] = $_value;}
            else{$return = $_value;}
        }

        $children = array();
        $first = true;
        foreach($xml->children() as $elementName => $child){
            $value = $this->simpleXMLToArray($child, $flattenValues, $flattenAttributes, $flattenChildren, $valueKey, $attributesKey, $childrenKey);
            if(isset($children[$elementName])){
                if($first){
                    $temp = $children[$elementName];
                    unset($children[$elementName]);
                    $children[$elementName][] = $temp;
                    $first=false;
                }
                $children[$elementName][] = $value;
            }
            else{
                $children[$elementName] = $value;
            }
        }
        if(count($children)>0){
            if(!$flattenChildren){$return[$childrenKey] = $children;}
            else{$return = array_merge($return,$children);}
        }

        $attributes = array();
        foreach($xml->attributes() as $name=>$value){
            $attributes[$name] = trim($value);
        }
        if(count($attributes)>0){
            if(!$flattenAttributes){$return[$attributesKey] = $attributes;}
            else{$return = array_merge($return, $attributes);}
        }
       
        return $return;
    }

	function usage(){
		ob_start();
?>
Example:
----------------
{exp:tweetline username="brilliant2" limit="5" mentions="false" retweets="false"}
	<p>
		<a href="{link}" target="_blank">{post}</a><br />
		{date format="%M %d, %Y %g:%i%a"} {rel_date}
	</p>
{/exp:tweetline}

Parameters:
----------------
username: Twitter username.
limit: The maximum number of tweets to pull 
format (Default "no") - If yes then auto link pounds(#), ats (@), and http 
retweets: (Default "yes") - If false then retweets (RT) will be removed
mentions: (Default "yes") - If false then mentions (@'s) will be removed 
Cache: (Default 10) How long to store the cache API request in minutes. The minimum value is 3 due to API restrictions at Twitter.

Tags:
----------------
link: Link to post on twitter
post: Title of the post
date: Date the post was added (accepts format parameter)
rel_date: Returns the data in a relative format (i.e. '1 day ago', 'about an hour ago')
count / total_results / switch are available by system default
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
  	/* END */
}
/* END Class */
/* End of file pi.tweetline.php */
/* Location: ./system/expressionengine/third_party/tweetline/pi.tweetline.php */