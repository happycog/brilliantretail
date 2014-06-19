<?php
/**
 * Payment Gateway
 *
 * This library provides generic payment gateway handling functionlity
 * to the other payment gateway classes in an uniform way. Please have
 * a look on them for the implementation details.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

abstract class PaymentGateway
{
    /**
     * Holds the last error encountered
     *
     * @var string
     */
    public $lastError;

    /**
     * Do we need to log IPN results ?
     *
     * @var boolean
     */
    public $logIpn;

    /**
     * File to log IPN results
     *
     * @var string
     */
    public $ipnLogFile;

    /**
     * Payment gateway IPN response
     *
     * @var string
     */
    public $ipnResponse;

    /**
     * Are we in test mode ?
     *
     * @var boolean
     */
    public $testMode;

    /**
     * Field array to submit to gateway
     *
     * @var array
     */
    public $fields = array();

    /**
     * IPN post values as array
     *
     * @var array
     */
    public $ipnData = array();

    /**
     * Payment gateway URL
     *
     * @var string
     */
    public $gatewayUrl;

    /**
     * Initialization constructor
     *
     * @param none
     * @return void
     */
    public function __construct()
    {
        // Some default values of the class
        $this->lastError = '';
        $this->logIpn = FALSE;
        $this->ipnResponse = '';
        $this->testMode = FALSE;
    }

    /**
     * Adds a key=>value pair to the fields array
     *
     * @param string key of field
     * @param string value of field
     * @return
     */
    public function addField($field, $value)
    {
        $this->fields["$field"] = $value;
    }

    /**
     * Submit Payment Request
     *
     * Generates a form with hidden elements from the fields array
     * and submits it to the payment gateway URL. The user is presented
     * a redirecting message along with a button to click.
     *
     * @param none
     * @return void
     */
    public function submitPayment()
    {

        $this->prepareSubmit();

        echo "<html>\n";
        echo "<head><title>Processing PayPal Website Payments Standar</title></head>\n";
        echo "<body onLoad=\"document.forms['gateway_form'].submit();\">\n";
        echo "<form method=\"POST\" name=\"gateway_form\" ";
        echo "action=\"" . $this->gatewayUrl . "\">\n";

        foreach ($this->fields as $name => $value)
        {
             echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }


        echo "	<p style=\"text-align:center;\">Your order is being processed... will be redirected to the payment website.
        		<br/><br/>If you are not automatically redirected to payment website within 5 seconds...<br/><br/>
        		<input type=\"submit\" value=\"Click Here\"></p>
        		</form>
        		</body>
        	</html>";
    }

    /**
     * Perform any pre-posting actions
     *
     * @param none
     * @return none
     */
    protected function prepareSubmit()
    {
        // Fill if needed
    }

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    abstract protected function enableTestMode();

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
    abstract protected function validateIpn();

    /**
     * Logs the IPN results
     *
     * @param boolean IPN result
     * @return void
     */
    public function logResults($success)
    {

        if (!$this->logIpn) return;

        // Timestamp
        $text = '[' . date('m/d/Y g:i A').'] - ';

        // Success or failure being logged?
        $text .= ($success) ? "SUCCESS!\n" : 'FAIL: ' . $this->lastError . "\n";

        // Log the POST variables
        $text .= "IPN POST Vars from gateway:\n";
        foreach ($this->ipnData as $key=>$value)
        {
            $text .= "$key=$value, ";
        }

        // Log the response from the paypal server
        $text .= "\nIPN Response from gateway Server:\n " . $this->ipnResponse;

        // Write to log
        $fp = fopen($this->ipnLogFile,'a');
        fwrite($fp, $text . "\n\n");
        fclose($fp);
    }
}

/**
 * Paypal Class
 *
 * Integrate the Paypal payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package		Payment Gateway
 * @category	Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

class Paypal extends PaymentGateway
{

    /**
	 * Initialize the Paypal gateway
	 *
	 * @param none
	 * @return void
	 */
	public function __construct()
	{
        parent::__construct();

        // Some default values of the class
		$this->gatewayUrl = 'https://www.paypal.com/cgi-bin/webscr';
		$this->ipnLogFile = 'paypal.ipn_results.log';

		// Populate $fields array with a few default
		$this->addField('rm', '2');           // Return method = POST
	}

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    public function enableTestMode()
    {
        $this->testMode = TRUE;
        $this->gatewayUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }

    /*
	* 	Validate the IPN notification
	*/
	public function validateIpn()
	{
		// parse the paypal URL
		$urlParsed = parse_url($this->gatewayUrl);

		$string = "cmd=_notify-validate";
		
		foreach ($_POST as $key => $value)
		{
			// Put the key / value in our ipnData array. 
				$this->ipnData[$key] = $value;
			$clean = (get_magic_quotes_gpc()) ? stripslashes(str_replace("\n", "\r\n", $value)) : str_replace("\n", "\r\n", $value);
			$string .= "&".$key."=".urlencode($clean);
		}
		
		$ch = curl_init('https://'.$urlParsed['host'].'/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		$this->ipnResponse = curl_exec($ch);

        
		if (strpos(strtoupper($this->ipnResponse), "VERIFIED"))
		{
		 	return true;
		}
		else
		{
			return false;
		}
	}

	function fsockopen_process($url){ 
		$parts	= parse_url($url);
		$host	= $parts['host'];
		$path	= ( ! isset($parts['path'])) ? '/' : $parts['path'];
		$port	= ($parts['scheme'] == "https") ? '443' : '80';
		$ssl	= ($parts['scheme'] == "https") ? 'ssl://' : '';
		
		
		if (isset($parts['query']) && $parts['query'] != '')
		{
			$path .= '?'.$parts['query'];
		}
		
		$postdata = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value)
		{
			// str_replace("\n", "\r\n", $value)
			// put line feeds back to CR+LF as that's how PayPal sends them out
			// otherwise multi-line data will be rejected as INVALID
			$this->ipnData[$key] = $value;
			$postdata .= "&$key=".urlencode(stripslashes(str_replace("\n", "\r\n", $value)));
		}
		
		$info = '';

		$fp = @fsockopen($ssl.$host, $port, $error_num, $error_str, 8); 

		if (is_resource($fp))
		{
			fputs($fp, "POST {$path} HTTP/1.0\r\n"); 
			fputs($fp, "Host: {$host}\r\n"); 
			fputs($fp, "Content-Type: application/x-www-form-urlencoded\r\n"); 
			fputs($fp, "Content-Length: ".strlen($postdata)."\r\n"); 
			fputs($fp, "Connection: close\r\n\r\n"); 
			fputs($fp, $postdata . "\r\n\r\n");
			
			while($datum = fread($fp, 4096))
			{
				$info .= $datum;
			}

			@fclose($fp); 
		}
		
		return $info; 
	}

}
