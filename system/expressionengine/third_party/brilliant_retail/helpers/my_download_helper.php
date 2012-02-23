<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter 								*/
/* 	@copyright	Copyright (c) 2010-2012 Brilliant2.com		*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/*	@since 		Version 1.1.0.0 							*/
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

/*
 * Force Download
 *
 * Generates headers that force a download to happen
 *
 * @access    public
 * @param    string    filename
 * @param    mixed    the data to be downloaded
 * @return    void
 */
if ( ! function_exists('force_download'))
{
    function force_download($filename = '', $file = '')
    {
        if ($filename == '' OR $file == '')
        {
            return FALSE;
        }

        // Try to determine if the filename includes a file extension.
        // We need it in order to set the MIME type
        if (FALSE === strpos($filename, '.'))
        {
            return FALSE;
        }

        // Grab the file extension
        $x = explode('.', $filename);
        $extension = end($x);

        // Load the mime types
        @include(APPPATH.'config/mimes'.EXT);

        // Set a default mime if we can't find it
        if ( ! isset($mimes[$extension]))
        {
            $mime = 'application/octet-stream';
        }
        else
        {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }

        // Generate the server headers
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE)
        {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: ".filesize($file));
        }
        else
        {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: ".filesize($file));
        }

        readfile_chunked($file);
        die;
    }
}

/**
 * readfile_chunked
 *
 * Reads file in chunks so big downloads are possible without changing PHP.INI
 *
 * @access    public
 * @param    string    file
 * @param    boolean    return bytes of file
 * @return    void
 */
if ( ! function_exists('readfile_chunked'))
{
    function readfile_chunked($file, $retbytes=TRUE)
    {
       $chunksize = 1 * (1024 * 1024);
       $buffer = '';
       $cnt =0;

       $handle = fopen($file, 'r');
       if ($handle === FALSE)
       {
           return FALSE;
       }

       while (!feof($handle))
       {
           $buffer = fread($handle, $chunksize);
           echo $buffer;
           ob_flush();
           flush();

           if ($retbytes)
           {
               $cnt += strlen($buffer);
           }
       }

       $status = fclose($handle);

       if ($retbytes AND $status)
       {
           return $cnt;
       }

       return $status;
    }
}

/* End of file MY_download_helper.php */
/* Location: ./application/helpers/MY_download_helper.php */ 