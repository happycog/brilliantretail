<?php

	/*
		Class to handle AssurePay communication.

		Version:     0.2
		Date:        31-5-2010
		PHP:         PHP 5


		See also:    http://www.ideal-simulator.nl


		Author:      Martijn Wieringa
		Company:     PHP Solutions
		Email:       info@php-solutions.nl
		Website:     http://www.php-solutions.nl
	*/

	class AssurePay
	{
		// Constants
		protected $CR = "\r";
		protected $LF = "\n";
		protected $CRLF = "\r\n";
		protected $sDirectoryRequestUrl = 'ssl://www.sisow.nl:443/Sisow/iDeal/RestHandler.ashx/DirectoryRequest';
		protected $sTransactionRequestUrl = 'ssl://www.sisow.nl:443/Sisow/iDeal/RestHandler.ashx/TransactionRequest';
		protected $sStatusRequestUrl = 'ssl://www.sisow.nl:443/Sisow/iDeal/RestHandler.ashx/StatusRequest';

		protected $sCachePath;

		// Errors
		protected $aErrors;

		// Merchant settings
		protected $sMerchantId;
		protected $sMerchantKey;
		protected $sShopId;

		// Cached values
		protected $sTransactionId;
		protected $sTransactionUrl;
		protected $sTransactionStatus;
		protected $aIssuers;


		// Constructor
		public function __construct()
		{
			$this->aErrors = array();

			$this->sMerchantId = false;
			$this->sMerchantKey = false;
			$this->sShopId = false;

			$this->sTransactionId = false;
			$this->sTransactionUrl = false;
			$this->sTransactionStatus = false;
			$this->aIssuers = array();

			if(defined('ASSUREPAY_MERCHANT_ID') && defined('ASSUREPAY_MERCHANT_KEY') && defined('ASSUREPAY_SHOP_ID'))
			{
				$this->setMerchant(ASSUREPAY_MERCHANT_ID, ASSUREPAY_MERCHANT_KEY, ASSUREPAY_SHOP_ID);
			}

			if(defined('ASSUREPAY_CACHE_PATH'))
			{
				$this->setCachePath(ASSUREPAY_CACHE_PATH);
			}
		}


		// Set account information
		public function setMerchant($sMerchantId, $sMerchantKey, $sShopId = '')
		{
			$this->sMerchantId = $sMerchantId;
			$this->sMerchantKey = $sMerchantKey;
			$this->sShopId = $sShopId;
		}

		// Should point to directory where cache is stored (Use FALSE for NO CACHE), make sure directory has read/write privileges
		function setCachePath($sPath = false)
		{
			$this->sCachePath = $sPath;
		}


		// Execute an 'issuer request'
		public function doIssuerRequest($bSelectList = false)
		{
			$sCacheFile = false;

			if($this->sCachePath)
			{
				$sCacheFile = $this->sCachePath . 'issuers.cache';

				if(file_exists($sCacheFile) == false)
				{
					// Attempt to create cache file
					if(@touch($sCacheFile))
					{
						@chmod($sCacheFile, 0777);
					}
				}

				if(file_exists($sCacheFile) && is_readable($sCacheFile) && is_writable($sCacheFile))
				{
					if(filemtime($sCacheFile) > strtotime('-24 Hours'))
					{
						// Read data from cache file
						if($sData = file_get_contents($sCacheFile))
						{
							return unserialize($sData);
						}
					}
				}
				else
				{
					$sCacheFile = false;
				}
			}

			$this->aIssuers = array();

			$aGetData = array();

			// Set XML Message
			$sXmlReply = $this->postToHost($this->sDirectoryRequestUrl . '?' . http_build_query($aGetData), false, true, 30);

			if($sXmlReply)
			{
				if($this->parseFromXml('directory', $sXmlReply))
				{
					$a = explode('<issuer>', str_replace('</issuer>', '', $sXmlReply));

					foreach($a as $k => $v)
					{
						$sIssuerId = $this->parseFromXml('issuerid', $v);
						$sIssuerName = $this->parseFromXml('issuername', $v);

						if($sIssuerId && $sIssuerName)
						{
							$this->aIssuers[$sIssuerId] = $sIssuerName;
						}
					}


					// Save data in cache?
					if($sCacheFile)
					{
						if($oHandle = fopen($sCacheFile, 'w'))
						{
							fwrite($oHandle, serialize($this->aIssuers));
							fclose($oHandle);
						}
					}
				}
				else
				{
					$this->setError('Invalid reply on Issuer Request.', false, __FILE__, __LINE__);
				}
			}
			else
			{
				$this->setError('No reply on Issuer Request.', false, __FILE__, __LINE__);
			}

			return $this->aIssuers;
		}


		// Execute a 'transaction request'.
		public function doTransactionRequest($sIssuerId, $sPurchaseId, $fPurchaseAmount, $sPurchaseDescription, $sEntranceCode, $sReturnUrl, $sCallbackUrl = '')
		{
			$this->sTransactionId = false;
			$this->sTransactionUrl = false;

			$iPurchaseAmount = round($fPurchaseAmount * 100, 2);

			$aGetData = array();
			$aGetData['shopid'] = $this->sShopId;
			$aGetData['merchantid'] = $this->sMerchantId;
			$aGetData['purchaseid'] = $sPurchaseId;
			$aGetData['entrancecode'] = $sEntranceCode;
			$aGetData['amount'] = $iPurchaseAmount;
			$aGetData['description'] = $sPurchaseDescription;
			$aGetData['issuerid'] = $sIssuerId;
			$aGetData['returnurl'] = $sReturnUrl;
			$aGetData['callbackurl'] = $sCallbackUrl;
			$aGetData['r'] = '86';
			$aGetData['sha1'] = sha1($aGetData['purchaseid'] . $aGetData['entrancecode'] . $aGetData['amount'] . $aGetData['shopid'] . $aGetData['merchantid'] . $this->sMerchantKey);

			$sXmlReply = $this->postToHost($this->sTransactionRequestUrl . '?' . http_build_query($aGetData), false, true, 30);

			if($sXmlReply)
			{
				if($this->parseFromXml('error', $sXmlReply))
				{
					$sErrorCode = $this->parseFromXml('errorcode', $sXmlReply);
					$sErrorMessage = $this->parseFromXml('errormessage', $sXmlReply);

					$this->setError('AssurePay Error: ' . $sErrorMessage, $sErrorCode, __FILE__, __LINE__);
				}
				elseif($this->parseFromXml('transaction', $sXmlReply))
				{
					$this->sTransactionId = $this->parseFromXml('trxid', $sXmlReply);
					$sTransactionUrl = $this->parseFromXml('issuerurl', $sXmlReply);
					$this->sTransactionUrl = urldecode($sTransactionUrl);
					$sSignature = $this->parseFromXml('sha1', $sXmlReply);

					// Validate signature
					$sHash = sha1($this->sTransactionId . $sTransactionUrl . $this->sMerchantId . $this->sMerchantKey);

					if(strcasecmp($sSignature, $sHash) !== 0)
					{
						$this->setError('Transaction Request Error: Invalid signature.', false, __FILE__, __LINE__);
					}
				}
				else
				{
					$this->setError('Transaction Request Error: Invalid response.', false, __FILE__, __LINE__);
				}
			}
			else
			{
				$this->setError('Transaction Request Error: No response recieved.', false, __FILE__, __LINE__);
			}

			return array($this->sTransactionId, $this->sTransactionUrl);
		}


		// Execute a 'transaction status request'.
		public function doStatusRequest($sTransactionId, $sTransactionCode = false, $sTransactionStatus = false, $sSignature = false)
		{
			$this->sTransactionStatus = false;

			if(($sTransactionCode !== false) && ($sTransactionStatus !== false) && ($sSignature !== false)) // Offline validation
			{
				$sHash = sha1($sTransactionId . $sTransactionCode . $sTransactionStatus . $this->sMerchantId . $this->sMerchantKey);

				if(strcasecmp($sSignature, $sHash) !== 0)
				{
					$this->setError('Status Request Error: Invalid signature.', false, __FILE__, __LINE__);
				}

				$this->sTransactionStatus = strtoupper($sTransactionStatus);
			}
			else // Realtime validation
			{
				$aGetData = array();
				$aGetData['trxid'] = $sTransactionId;
				$aGetData['shopid'] = $this->sShopId;
				$aGetData['merchantid'] = $this->sMerchantId;
				$aGetData['sha1'] = sha1($aGetData['trxid'] . $aGetData['shopid'] . $aGetData['merchantid'] . $this->sMerchantKey);

				$sXmlReply = $this->postToHost($this->sStatusRequestUrl, http_build_query($aGetData), 10);

				if($sXmlReply)
				{
					if($this->parseFromXml('error', $sXmlReply))
					{
						$sErrorCode = $this->parseFromXml('errorcode', $sXmlReply);
						$sErrorMessage = $this->parseFromXml('errormessage', $sXmlReply);

						$this->setError('AssurePay Error: ' . $sErrorMessage, $sErrorCode, __FILE__, __LINE__);
					}
					elseif($this->parseFromXml('transaction', $sXmlReply))
					{
						$sTransactionId = $this->parseFromXml('trxid', $sXmlReply);
						$sTransactionStatus = $this->parseFromXml('status', $sXmlReply);
						$sAmount = $this->parseFromXml('amount', $sXmlReply);
						$sPurchaseId = $this->parseFromXml('purchaseid', $sXmlReply);
						$sEntranceCode = $this->parseFromXml('entrancecode', $sXmlReply);
						$sConsumerAccount = $this->parseFromXml('consumeraccount', $sXmlReply);
						$sSignature = $this->parseFromXml('sha1', $sXmlReply);

						// Validate signature
						$sHash = sha1($sTransactionId . $sTransactionStatus . $sAmount . $sPurchaseId . $sEntranceCode . $sConsumerAccount . $this->sMerchantId . $this->sMerchantKey);

						if(strcasecmp($sSignature, $sHash) !== 0)
						{
							$this->setError('Status Request Error: Invalid signature.', false, __FILE__, __LINE__);
						}

						$this->sTransactionId = $sTransactionId;
						$this->sTransactionStatus = strtoupper($sTransactionStatus);
					}
					else
					{
						$this->setError('Status Request Error: Invalid response.', false, __FILE__, __LINE__);
					}
				}
				else
				{
					$this->setError('Status Request Error: No response recieved.', false, __FILE__, __LINE__);
				}
			}

			return $this->sTransactionStatus;
		}


		// Return TransactionId
		public function getTransactionId()
		{
			return $this->sTransactionId;
		}


		// Return TransactionUrl
		public function getTransactionUrl()
		{
			return $this->sTransactionUrl;
		}


		// Return TransactionStatus
		public function getTransactionStatus()
		{
			return $this->sTransactionStatus;
		}


		// Return Issuers
		function getIssuers($bHtml = false)
		{
			if($bHtml)
			{
				$sIssuers = '';

				foreach($this->aIssuers as $k => $v)
				{
					$sIssuers .= '<option value="' . htmlspecialchars($k) . '">' . htmlspecialchars($v) . '</option>';
				}

				return $sIssuers;
			}

			return $this->aIssuers;
		}


		// Start transaction
		public function doTransaction()
		{
			if($this->sTransactionId && $this->sTransactionUrl)
			{
				header('Location: ' . $this->sTransactionUrl);
				exit;
			}

			return false;
		}


		// Error functions
		protected function setError($sDesc, $sCode = false, $sFile = 0, $sLine = 0)
		{
			$this->aErrors[] = array('desc' => $sDesc, 'code' => $sCode, 'file' => $sFile, 'line' => $sLine);
		}

		public function getErrors()
		{
			return $this->aErrors;
		}

		public function hasErrors()
		{
			return (sizeof($this->aErrors) ? true : false);
		}


		// Get value within given XML tag
		protected function parseFromXml($key, $xml)
		{
			$begin = 0;
			$end = 0;
			$begin = strpos($xml, '<' . $key . '>');
			
			if($begin === false)
			{
				return false;
			}

			$begin += strlen($key) + 2;
			$end = strpos($xml, '</' . $key . '>');

			if($end === false)
			{
				return false;
			}

			$result = substr($xml, $begin, $end - $begin);
			return $this->unescapeXml($result);
		}


		// Escape special XML characters
		protected function escapeXml($string)
		{
			return utf8_encode(str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
		}


		// Unescape special XML characters
		protected function unescapeXml($string)
		{
			return str_replace(array('&lt;', '&gt;', '&quot;', '&amp;'), array('<', '>', '"', '&'), utf8_decode($string));
		}


		// Execute HTTP request
		protected function postToHost($sUrl, $sPostData = false, $bRemoveHeaders = false, $iTimeout = 30)
		{
			$aUrl = parse_url($sUrl);

			$oSocket = fsockopen(((strcmp($aUrl['scheme'], 'ssl') === 0) ? 'ssl://' : '') . $aUrl['host'], (empty($aUrl['port']) ? 80 : intval($aUrl['port'])), $sErrorNumber, $sErrorMessage, $iTimeout);
			$sReply = '';
			
			if($oSocket)
			{
				// echo $this->LF . $this->LF . '<h1>SEND DATA:</h1>' . $this->LF . '<code style="display: block; background: #E0E0E0; border: #000000 solid 1px; padding: 10px;">' . ($sPostData ? 'POST' : 'GET') . ' ' . (empty($aUrl['path']) ? '/' : $aUrl['path']) . (empty($aUrl['query']) ? '' : '?' . $aUrl['query']) . ' HTTP/1.0<br>' . $this->CRLF . 'Host: ' . $aUrl['host'] . '<br>' . $this->CRLF . 'Accept: text/html' . '<br>' . $this->CRLF . 'Accept-Charset: charset=ISO-8859-1,utf-8' . '<br>' . $this->CRLF . ($sPostData ? 'Content-Length: ' . strlen($sPostData) . '<br>' . $this->CRLF . 'Content-Type: application/x-www-form-urlencoded; charset=utf-8' . '<br>' . $this->CRLF . '<br>' . $this->CRLF . str_replace(array($this->LF, $this->CR), array('<br>' . $this->CRLF, ''), htmlspecialchars($sPostData)) : '') . '</code>' . $this->LF . $this->LF;

				// Send data
				fputs($oSocket, ($sPostData ? 'POST' : 'GET') . ' ' . (empty($aUrl['path']) ? '/' : $aUrl['path']) . (empty($aUrl['query']) ? '' : '?' . $aUrl['query']) . ' HTTP/1.0' . $this->CRLF);
				fputs($oSocket, 'Host: ' . $aUrl['host'] . $this->CRLF);
				fputs($oSocket, 'Accept: text/html' . $this->CRLF);
				fputs($oSocket, 'Accept-Charset: charset=ISO-8859-1,utf-8' . $this->CRLF);

				if($sPostData)
				{
					fputs($oSocket, 'Content-Length: ' . strlen($sPostData) . $this->CRLF);
					fputs($oSocket, 'Content-Type: application/x-www-form-urlencoded; charset=utf-8' . $this->CRLF . $this->CRLF);
					fputs($oSocket, $sPostData, strlen($sPostData));
				}
				else
				{
					fputs($oSocket, $this->CRLF);
				}

				// Recieve data
				while(!feof($oSocket))
				{
					$sReply .= @fgets($oSocket, 128);
				}

				fclose($oSocket);

				// echo $this->LF . $this->LF . '<h1>RECIEVED DATA:</h1>' . $this->LF . '<code style="display: block; background: #E0E0E0; border: #000000 solid 1px; padding: 10px;">' . str_replace(array($this->LF, $this->CR), array('<br>' . $this->CRLF, ''), htmlspecialchars($sReply)) . '</code>' . $this->LF . $this->LF;

				if($bRemoveHeaders)
				{
					// Remove headers from reply
					$sNeedle = $this->CRLF . $this->CRLF;
					$sReply = substr($sReply, strpos($sReply, $sNeedle) + strlen($sNeedle));
				}
			}
			else
			{
				die('Socket error: ' . $sErrorMessage);
			}

			return $sReply;
		}
	}

?>