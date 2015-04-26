<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
/* 	@license	http://opensource.org/licenses/OSL-3.0	*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.3.0								*/
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

	SagePay Class
	
	Handles SagePay Direct payments
		
*/

class SagePay {


	var $eoln;
	var $protocol_version = "2.23";
	var $VendorTxCode;

	var $CI;

	function SagePay()
	{
		// Load CI instance so we can get config values etc
		$this->CI =& get_instance();
		
		$eoln = chr(13) . chr(10);
	
	}


	
	/*************************************************************
	Send a post request with cURL
		$data = POST data to send
	*************************************************************/
	function requestPost($type='payment',$data,$config)
	{
	
		if ($type == 'payment')
		{
			$url = $config[$config['system']]['purchase_url'];
		}
		else
		{
			$url = $config[$config['system']]['callback_url'];
		}
		
		// Generate unique code
		$this->VendorTxCode = $config['vendor'] . (rand(0,320000) * rand(0,320000));
	
		$data['VPSProtocol'] = '2.23';
		$data['TxType'] = 'PAYMENT';
		$data['Vendor'] = $config['vendor'];
		$data['VendorTxCode'] = $this->VendorTxCode;
		$data['Currency'] = $config['currency'];
		$data['ClientIPAddress'] = $this->CI->input->server("REMOTE_ADDR");
		
		
		// Format data for post
		$data = $this->_formatData($data);
		
		// Set a one-minute timeout for this script
		set_time_limit(60);
		
		// Initialise output variable
		$output = array();
		
		// Open the cURL session
		$curlSession = curl_init();
		
		// Set the URL
		curl_setopt ($curlSession, CURLOPT_URL, $url);
		// No headers, please
		curl_setopt ($curlSession, CURLOPT_HEADER, 0);
		// It's a POST request
		curl_setopt ($curlSession, CURLOPT_POST, 1);
		// Set the fields for the POST
		curl_setopt ($curlSession, CURLOPT_POSTFIELDS, $data);
		// Return it direct, don't print it out
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1); 
		// This connection will timeout in 30 seconds
		curl_setopt($curlSession, CURLOPT_TIMEOUT,30); 
		//The next two lines must be present for the kit to work with newer version of cURL
		//You should remove them if you have any problems in earluer version of cURL
		curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 1);
		
		
		
		//Send the request and store the result in an array
		$response = @split(chr(10),curl_exec($curlSession));
		
		// Check that a connection was made
		if (curl_error($curlSession))
		{
			// If it wasn't...
			$output['Status'] = "FAIL";
			$output['StatusDetail'] = curl_error($curlSession);
		}
		
		// Close the cURL session
		curl_close ($curlSession);
		
		// Tokenise the response
		for ($i=0; $i<count($response); $i++)
		{
			// Find position of first "=" character
			$splitAt = strpos($response[$i], "=");
			// Create an associative (hash) array with key/value pairs ('trim' strips excess whitespace)
			$output[trim(substr($response[$i], 0, $splitAt))] = trim(substr($response[$i], ($splitAt+1)));
		} 
		
		// Return the output
		return $output;
		
	} 
	





	/***** Private functions *****/


	function _displayAssociativeArray( $data )
	{
		$result = "";
		foreach ( $data as $key => $value )
		{
			$result .= $key . " => " . $value . "<br/>";
		}
		return $result;
	}
	
	

	
	/*************************************************************
	Format data for sending in POST request
		$data = data as an associative array
	*************************************************************/
	
	function _formatData($data)
	{
	
		// Initialise output variable
		$output = "";
		
		// Step through the fields
		foreach($data as $key => $value)
		{
			// Stick them together as key=value pairs (url encoded)
			$output .= "&" . $key . "=". urlencode($value);
		}
		
		// Kludge to take out the initial &
		$output = substr($output,1);
		
		// Return the output
		return $output;
	
	
	}
	
	
	/*************************************************************
	Given a list of possible fields, add them to $target if they've
	been set in $source.
	*************************************************************/
	function _addOptionalFields( $source, $target, $fields )
	{
		$result = $target;
		foreach ( $fields as $field )
		{
			if ( trim( $source[ $field ] ) != '' )
			{
				$result[ $field ] = $source[ $field ];
			}
		}
		return $result;
	}

}


