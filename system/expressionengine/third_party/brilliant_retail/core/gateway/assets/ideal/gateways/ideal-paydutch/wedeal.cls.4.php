<?php

	/*
		Class to manage your iDEAL Requests

		Version:     0.1
		Date:        08-12-2010
		PHP:         PHP 5

		Suitable for:
		PayDutch     WeDeal    (www.paydutch.nl)

		See also:
		www.ideal-simulator.nl


		Author:      Martijn Wieringa
		Company:     PHP Solutions
		Email:       info@php-solutions.nl
		Website:     http://www.php-solutions.nl
	*/


	class PayDutchRequest
	{
		// Error stack
		var $aErrors = array();

		// API info
		var $sApiVersion = '1.0';
		var $sApiUrl = 'https://www.paydutch.nl/api/processreq.aspx';
		var $sApiMethod = '0101'; // WeDeal Method

		// Account info
		var $sUserName = '';
		var $sUserPass = '';

		// Callback info
		var $sCallbackUserName = '';
		var $sCallbackUserPass = '';

		// Order info
		var $sOrderId = '';
		var $sOrderDescription = '';
		var $fOrderAmount = 0.00;

		// Redirect info
		var $sTransactionId = false;
		var $sTransactionUrl = false;

		function PayDutchRequest()
		{
		}

		// Set user/account information
		function setUser($sUserName, $sUserPass)
		{
			if(strlen($sUserName) > 17)
			{
				$this->setError('Username should have 17 chars or less.', false, __FILE__, __LINE__);
			}

			if(strlen($sUserName) > 20)
			{
				$this->setError('Password should have 20 chars or less.', false, __FILE__, __LINE__);
			}

			$this->sUserName = substr($sUserName, 0, 17);
			$this->sUserPass = substr($sUserPass, 0, 20);
		}

		// Set user/account information
		function setCallback($sUserName, $sUserPass)
		{
			if(strlen($sUserName) > 17)
			{
				$this->setError('Username should have 17 chars or less.', false, __FILE__, __LINE__);
			}

			if(strlen($sUserName) > 20)
			{
				$this->setError('Password should have 20 chars or less.', false, __FILE__, __LINE__);
			}

			$this->sCallbackUserName = substr($sUserName, 0, 17);
			$this->sCallbackUserPass = substr($sUserPass, 0, 20);
		}

		// Set order information
		function setOrder($sOrderId, $sOrderDescription, $fOrderAmount)
		{
			if(strlen($sOrderId) > 50)
			{
				$this->setError('Order ID should have 50 chars or less.', false, __FILE__, __LINE__);
			}

			if(strlen($sOrderDescription) > 255)
			{
				$this->setError('Order description should have 255 chars or less.', false, __FILE__, __LINE__);
			}

			if(($fOrderAmount < 1.00) || ($fOrderAmount > 10000.00))
			{
				$this->setError('Order amount should be 1,00 to 10.000,00 EURO.', false, __FILE__, __LINE__);
			}

			$this->sOrderId = substr($sOrderId, 0, 50);
			$this->sOrderDescription = substr($sOrderDescription, 0, 255);
			$this->fOrderAmount = $fOrderAmount;
		}

		// Lookup available gateways
		function doGatewayRequest()
		{
			$sXML = '<?xml version="1.0" encoding="UTF-8"?>
<request>
	<type>listmethod</type>
	<merchant>
		<username>' . $this->escapeXml($this->sUserName) . '</username>
		<password>' . $this->escapeXml($this->sUserPass) . '</password>
	</merchant>
</request>';
	
			return $this->doHttpRequest($this->sApiUrl, $sXML, true);
		}

		// Submit transaction information (recievces TransactionID and TransactionURL)
		function doTransactionRequest()
		{
			$sXML = '<?xml version="1.0" encoding="UTF-8"?>
<request>
	<type>transaction</type>
	<transactionreq>
		<username>' . $this->escapeXml($this->sUserName) . '</username>
		<password>' . $this->escapeXml($this->sUserPass) . '</password>
		<reference>' . $this->escapeXml($this->sOrderId) . '</reference>
		<description>' . $this->escapeXml($this->sOrderDescription) . '</description>
		<amount>' . $this->escapeXml(number_format($this->fOrderAmount, 2, ',', '')) . '</amount>
		<methodcode>' . $this->escapeXml($this->sApiMethod) . '</methodcode>
	</transactionreq>
</request>';
	
			if($sTransactionUrl = $this->doHttpRequest($this->sApiUrl, $sXML, true))
			{
				$this->sTransactionUrl = $sTransactionUrl;

				if(strpos($sTransactionUrl, '?ID='))
				{
					$a = explode('?ID=', $sTransactionUrl);
					$a = explode('&', $a[1]);
					$this->sTransactionId = $a[0];
				}
				else
				{
					$this->setError('Cannot detect Transaction ID', false, __FILE__, __LINE__);
				}

				return array($this->sTransactionId, $this->sTransactionUrl);
			}

			return false;
		}

		// Fetch & validate return information
		function doReturnRequest()
		{
			if(isset($_GET['Reference']) && isset($_GET['PaymentState']) && isset($_GET['Username']) && isset($_GET['Password']) && isset($_GET['ID']) && isset($_GET['PaymentMethod']) && isset($_GET['Description']))
			{
				if((strcasecmp($_GET['Username'], $this->sCallbackUserName) === 0) && (strcasecmp($_GET['Password'], $this->sCallbackUserPass) === 0))
				{
					return array('order_id' => $_GET['Reference'], 'transaction_description' => $_GET['Description'], 'transaction_id' => $_GET['ID'], 'transaction_method' => $_GET['PaymentMethod'], 'transaction_status' => strtoupper($_GET['PaymentState']));
				}
				else
				{
					$this->setError('Invalid ClientName/ClientPassword. Please validate the technical settings of your PayDutch account.', false, __FILE__, __LINE__);
				}
			}
			else
			{
				$this->setError('GET data is missing or invalid.', false, __FILE__, __LINE__);
			}

			return false;
		}

		// Redirect to iDEAL URL
		function doRedirect()
		{
			if($this->sTransactionUrl)
			{
				header('Location: ' . $this->sTransactionUrl);
				exit;
			}
			else
			{
				$this->setError('No valid Redirect URL available.', false, __FILE__, __LINE__);
			}

			return false;
		}

		// Validate order status
		function doStatusRequest($sOrderId = false)
		{
			$sXML = '<?xml version="1.0" encoding="UTF-8"?>
<request>
	<type>query</type>
	<merchant>
		<username>' . $this->escapeXml($this->sUserName) . '</username>
		<password>' . $this->escapeXml($this->sUserPass) . '</password>
		<reference>' . $this->escapeXml($sOrderId ? $sOrderId : $this->sOrderId) . '</reference>
	</merchant>
</request>';
	
			if($sData = $this->doHttpRequest($this->sApiUrl, $sXML, true))
			{
				if($iStrPos = strpos($sData, '<state>'))
				{
					$iStrPos += 7;

					if($iStrPos2 = strpos($sData, '</state>', $iStrPos))
					{
						$sState = strtoupper(substr($sData, $iStrPos, $iStrPos2 - $iStrPos));

						// Translate to default iDEAL states
						$sState = str_replace(array('REGISTER', 'PROCESSING', 'INCOME', 'ASSEMBLE', 'PAYOUT', 'SUCCESS', 'CANCELLED', 'FAILED'), array('OPEN', 'PENDING', 'SUCCESS', 'PENDING', 'SUCCESS', 'SUCCESS', 'CANCELLED', 'FAILED'), $sState);

						return $sState;
					}
					else
					{
						$this->setError('Invalid server response recieved.', false, __FILE__, __LINE__);
					}
				}
				else
				{
					$this->setError('Invalid server response recieved.', false, __FILE__, __LINE__);
				}
			}
			else
			{
				$this->setError('No server response recieved.', false, __FILE__, __LINE__);
			}

			return false;
		}

		// Error functions
		function setError($sDesc, $sCode = false, $sFile = 0, $sLine = 0)
		{
			$this->aErrors[] = array('desc' => $sDesc, 'code' => $sCode, 'file' => $sFile, 'line' => $sLine);
		}

		function getErrors()
		{
			return $this->aErrors;
		}

		function hasErrors()
		{
			return (sizeof($this->aErrors) ? true : false);
		}



		// Escape special XML characters
		function escapeXml($string)
		{
			$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
			$string = utf8_encode($string);
			// $string = '<![CDATA[' . $string . ']]>';

			return $string;
		}

		// Unescape special XML characters
		function unescapeXml($string)
		{
			if(($iPos = strpos($string, '<![CDATA[')) !== false)
			{
				$string = substr($string, $iPos + 9);

				if(($iPos = strrpos($string, ']]>')) !== false)
				{
					$string = substr($string, 0, $iPos);
				}
			}

			$string = utf8_decode($string);
			$string = str_replace(array('&lt;', '&gt;', '&quot;', '&amp;'), array('<', '>', '"', '&'), $string);

			return $string;
		}
	
		function doHttpRequest($sUrl, $sPostData = false, $bRemoveHeaders = false, $iTimeout = 30, $bDebug = false)
		{
			$aUrl = parse_url($sUrl);

			$sRequestUrl = '';

			if(in_array($aUrl['scheme'], array('ssl', 'https')))
			{
				$sRequestUrl .= 'ssl://';

				if(empty($aUrl['port']))
				{
					$aUrl['port'] = 443;
				}
			}
			elseif(empty($aUrl['port']))
			{
				$aUrl['port'] = 80;
			}

			$sRequestUrl .= $aUrl['host'] . ':' . $aUrl['port'];

			$sErrorNumber = 0;
			$sErrorMessage = '';

			$oSocket = fsockopen($sRequestUrl, $sErrorNumber, $sErrorMessage, $iTimeout);
			$sResponse = '';

			if($oSocket)
			{
				$sRequest = ($sPostData ? 'POST' : 'GET') . ' ' . (empty($aUrl['path']) ? '/' : $aUrl['path']) . (empty($aUrl['query']) ? '' : '?' . $aUrl['query']) . ' HTTP/1.0' . "\r\n";
				$sRequest .= 'Host: ' . $aUrl['host'] . "\r\n";
				$sRequest .= 'Accept: text/html' . "\r\n";
				$sRequest .= 'Accept-Charset: charset=ISO-8859-1,utf-8' . "\r\n";

				if($sPostData)
				{
					$sRequest .= 'Content-Length: ' . strlen($sPostData) . "\r\n";
					// $sRequest .= 'Content-Type: application/x-www-form-urlencoded; charset=utf-8' . "\r\n" . "\r\n";
					$sRequest .= 'Content-Type: text/xml; charset=utf-8' . "\r\n" . "\r\n";
					$sRequest .= $sPostData;
				}
				else
				{
					$sRequest .= "\r\n";
				}

				if($bDebug === true)
				{
					echo "\r\n" . "\r\n" . '<h1>SEND DATA:</h1>' . "\r\n" . '<code style="display: block; background: #E0E0E0; border: #000000 solid 1px; padding: 10px;">' . str_replace(array("\n", "\r"), array('<br>' . "\r\n", ''), htmlspecialchars($sRequest)) . '</code>' . "\r\n" . "\r\n";
				}

				// Send data
				fputs($oSocket, $sRequest);

				// Recieve data
				while(!feof($oSocket))
				{
					$sResponse .= @fgets($oSocket, 128);
				}

				fclose($oSocket);

				if($bDebug === true)
				{
					echo "\r\n" . "\r\n" . '<h1>RECIEVED DATA:</h1>' . "\r\n" . '<code style="display: block; background: #E0E0E0; border: #000000 solid 1px; padding: 10px;">' . str_replace(array("\n", "\r"), array('<br>' . "\r\n", ''), htmlspecialchars($sResponse)) . '</code>' . "\r\n" . "\r\n";
				}

				if($bRemoveHeaders) // Remove headers from reply
				{
					list($sHeader, $sBody) = preg_split('/(\\r?\\n){2,2}/', $sResponse, 2);
					return $sBody;
				}
				else
				{
					return $sResponse;
				}
			}
			elseif($bDebug)
			{
				die('Socket error: ' . $sErrorMessage);
			}
			else
			{
				$this->setError('Cannot setup a HTTP request using FSOCK: ' . $sErrorMessage, $sErrorNumber, __FILE__, __LINE__);
			}
		}
	}

?>