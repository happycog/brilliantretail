<?php

	/*
		Class to manage your iDEAL Requests

		Version:     0.4
		Date:        03-12-2009
		PHP:         PHP 5

		Suitable for:
		Rabobank     iDEAL Professional
		ING BANK     iDEAL Advanced
		ABN AMRO     iDEAL Zelfbouw

		See also:
		www.ideal-simulator.nl


		Author:      Martijn Wieringa
		Company:     PHP Solutions
		Email:       info@php-solutions.nl
		Website:     http://www.php-solutions.nl
	*/

	class IdealRequest
	{
		protected $aErrors = array();

		// Security settings
		protected $sSecurePath;
		protected $sCachePath;
		protected $sPrivateKeyPass;
		protected $sPrivateKeyFile;
		protected $sPrivateCertificateFile;
		protected $sPublicCertificateFile;

		// Account settings
		protected $bABNAMRO = false; // ABN has some issues
		protected $sAquirerName;
		protected $sAquirerUrl;
		protected $bTestMode = false;
		protected $sMerchantId;
		protected $sSubId;

		// Constants
		protected $LF = "\n";
		protected $CRLF = "\r\n";

		public function __construct()
		{
			$this->sPrivateKeyFile = 'private.key';
			$this->sPrivateCertificateFile = 'private.cer';

			if(defined('IDEAL_SECURE_PATH'))
			{
				$this->setSecurePath(IDEAL_SECURE_PATH);
			}

			if(defined('IDEAL_CACHE_PATH'))
			{
				$this->setCachePath(IDEAL_CACHE_PATH);
			}

			if(defined('IDEAL_PRIVATE_KEY'))
			{
				$this->setPrivateKey(IDEAL_PRIVATE_KEY);
			}

			if(defined('IDEAL_PRIVATE_KEY_FILE'))
			{
				$this->sPrivateKeyFile = IDEAL_PRIVATE_KEY_FILE;
			}

			if(defined('IDEAL_PRIVATE_CERTIFICATE_FILE'))
			{
				$this->sPrivateCertificateFile = IDEAL_PRIVATE_CERTIFICATE_FILE;
			}

			if(defined('IDEAL_AQUIRER'))
			{
				if(defined('IDEAL_TEST_MODE'))
				{
					$this->setAquirer(IDEAL_AQUIRER, IDEAL_TEST_MODE);
				}
				else
				{
					$this->setAquirer(IDEAL_AQUIRER);
				}
			}

			if(defined('IDEAL_MERCHANT_ID'))
			{
				if(defined('IDEAL_SUB_ID'))
				{
					$this->setMerchant(IDEAL_MERCHANT_ID, IDEAL_SUB_ID);
				}
				else
				{
					$this->setMerchant(IDEAL_MERCHANT_ID);
				}
			}
		}


		// Should point to directory with .cer and .key files
		public function setSecurePath($sPath)
		{
			$this->sSecurePath = $sPath;
		}

		// Should point to directory where cache is strored
		public function setCachePath($sPath = false)
		{
			$this->sCachePath = $sPath;
		}

		// Set password to generate signatures
		public function setPrivateKey($sPrivateKeyPass, $sPrivateKeyFile = false, $sPrivateCertificateFile = false)
		{
			$this->sPrivateKeyPass = $sPrivateKeyPass;

			if($sPrivateKeyFile)
			{
				$this->sPrivateKeyFile = $sPrivateKeyFile;
			}

			if($sPrivateCertificateFile)
			{
				$this->sPrivateCertificateFile = $sPrivateCertificateFile;
			}
		}

		// Set MerchantID id and SubID
		public function setMerchant($sMerchantId, $sSubId = 0)
		{
			$this->sMerchantId = $sMerchantId;
			$this->sSubId = $sSubId;
		}

		// Set aquirer (Use: Rabobank, ING Bank, ABN Amro, Frieslandbank, Simulator or Mollie)
		public function setAquirer($sAquirerName, $bTestMode = false)
		{
			$this->sAquirerName = $sAquirerName;
			$this->bTestMode = $bTestMode;

			$sAquirerName = strtolower($sAquirerName);

			if(strpos($sAquirerName, 'rabo') !== false) // Rabobank
			{
				$this->sPublicCertificateFile = 'rabobank.cer';
				$this->sAquirerUrl = 'ssl://ideal' . ($bTestMode ? 'test' : '') . '.rabobank.nl:443/ideal/iDeal';
			}
			elseif(strpos($sAquirerName, 'fortis') !== false) // Fortis Bank
			{
				$this->sPublicCertificateFile = 'fortisbank.cer';
				$this->sAquirerUrl = 'ssl://acquirer-ideal.' . ($bTestMode ? 'test' : 'bank') . '.nl.fortis.com:443/ideal/iDeal';
			}
			elseif(strpos($sAquirerName, 'fries') !== false) // ING Bank
			{
				$this->sPublicCertificateFile = 'frieslandbank.cer';
				$this->sAquirerUrl = 'ssl://' . ($bTestMode ? 'test' : '') . 'idealkassa.frieslandbank.nl:443/ideal/iDeal';
			}
			elseif(strpos($sAquirerName, 'ing') !== false) // ING Bank
			{
				$this->sPublicCertificateFile = 'ingbank.cer';
				$this->sAquirerUrl = 'ssl://ideal' . ($bTestMode ? 'test' : '') . '.secure-ing.com:443/ideal/iDeal';
			}
			elseif(strpos($sAquirerName, 'abn') !== false) // ABN AMRO
			{
				$this->bABNAMRO = true;
				$this->sPublicCertificateFile = 'abnamro' . ($bTestMode ? '.test' : '') . '.cer';
				$this->sAquirerUrl = '';
				
				// With ABN AMRO, the AcquirerUrl depends on the request type
				$sClass = get_class($this);

				if(strcasecmp($sClass, 'issuerrequest') === 0)
				{
					if($bTestMode)
					{
						$this->sAquirerUrl = 'ssl://itt.idealdesk.com:443/ITTEmulatorAcquirer/Directory.aspx';
					}
					else
					{
						$this->sAquirerUrl = 'ssl://idealm.abnamro.nl:443/nl/issuerInformation/getIssuerInformation.xml';
					}
				}
				elseif(strcasecmp($sClass, 'transactionrequest') === 0)
				{
					if($bTestMode)
					{
						$this->sAquirerUrl = 'ssl://itt.idealdesk.com:443/ITTEmulatorAcquirer/Transaction.aspx';
					}
					else
					{
						$this->sAquirerUrl = 'ssl://idealm.abnamro.nl:443/nl/acquirerTrxRegistration/getAcquirerTrxRegistration.xml';
					}
				}
				elseif(strcasecmp($sClass, 'statusrequest') === 0)
				{
					if($bTestMode)
					{
						$this->sAquirerUrl = 'ssl://itt.idealdesk.com:443/ITTEmulatorAcquirer/Status.aspx';
					}
					else
					{
						$this->sAquirerUrl = 'ssl://idealm.abnamro.nl:443/nl/acquirerStatusInquiry/getAcquirerStatusInquiry.xml';
					}
				}
			}
			elseif(strpos($sAquirerName, 'sim') !== false) // IDEAL SIMULATOR
			{
				$this->sPublicCertificateFile = 'simulator.cer';
				$this->sAquirerUrl = 'ssl://www.ideal-simulator.nl:443/professional/';
				$this->bTestMode = true; // Always in TEST MODE
			}
			elseif(strpos($sAquirerName, 'mollie') !== false) // Mollie
			{
				$this->sPublicCertificateFile = 'mollie.cer';

				if($this->bTestMode)
				{
					$this->sAquirerUrl = 'ssl://secure.mollie.nl:443/xml/idealAcquirer/testmode/';
				}
				else
				{
					$this->sAquirerUrl = 'ssl://secure.mollie.nl:443/xml/idealAcquirer/';
				}
			}
			else // Unknown issuer
			{
				$this->setError('Unknown aquirer. Please use "Rabobank", "ING Bank", "ABN Amro", "Mollie" or "Simulator".', false, __FILE__, __LINE__);
				return false;
			}
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



		// Validate configuration
		protected function checkConfiguration($aSettings = array('sSecurePath', 'sPrivateKeyPass', 'sPrivateKeyFile', 'sPrivateCertificateFile', 'sPublicCertificateFile', 'sAquirerUrl', 'sMerchantId'))
		{
			$bOk = true;

			for($i = 0; $i < sizeof($aSettings); $i++)
			{
				// echo $aSettings[$i] . ' = ' . $this->$aSettings[$i] . '<br>';

				if(isset($this->$aSettings[$i]) == false)
				{
					$bOk = false;
					$this->setError('Setting ' . $aSettings[$i] . ' was not configurated.', false, __FILE__, __LINE__);
				}
			}

			return $bOk;
		}



		// Send GET/POST data through sockets
		protected function postToHost($url, $data, $timeout = 30)
		{
			$__url = $url;
			$idx = strrpos($url, ':');
			$host = substr($url, 0, $idx);
			$url = substr($url, $idx + 1);
			$idx = strpos($url, '/');
			$port = substr($url, 0, $idx);
			$path = substr($url, $idx);

			$fsp = fsockopen($host, $port, $errno, $errstr, $timeout);
			$res = '';
			
			if($fsp)
			{
				// echo "\n\nSEND DATA: \n\n" . $data . "\n\n";

				fputs($fsp, 'POST ' . $path . ' HTTP/1.0' . $this->CRLF);
				fputs($fsp, 'Host: ' . substr($host, 6) . $this->CRLF);
				fputs($fsp, 'Accept: text/html' . $this->CRLF);
				fputs($fsp, 'Accept: charset=ISO-8859-1' . $this->CRLF);
				fputs($fsp, 'Content-Length:' . strlen($data) . $this->CRLF);
				fputs($fsp, 'Content-Type: text/html; charset=ISO-8859-1' . $this->CRLF . $this->CRLF);
				fputs($fsp, $data, strlen($data));

				while(!feof($fsp))
				{
					$res .= @fgets($fsp, 128);
				}

				fclose($fsp);

				// echo "\n\nRECIEVED DATA: \n\n" . $res . "\n\n";
			}
			else
			{
				$this->setError('Error while connecting to ' . $__url, false, __FILE__, __LINE__);
			}

			return $res;
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

		// Remove space characters from string
		protected function removeSpaceCharacters($string)
		{
			if($this->bABNAMRO)
			{
				// return preg_replace('/(\f|\n|\r|\t|\v)/', '', $string); // \v Doesn't work properly on linux.
				return preg_replace('/(\f|\n|\r|\t)/', '', $string);
			}
			else
			{
				return preg_replace('/\s/', '', $string);
			}
		}

		// Escape (replace/remove) special characters in string
		protected function escapeSpecialChars($string)
		{
			$string = str_replace(array('à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ð', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', '§', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', '€', 'Ð', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', '§', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Ÿ'), array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'ed', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 's', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'EUR', 'ED', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'S', 'U', 'U', 'U', 'U', 'Y', 'Y'), $string);
			$string = preg_replace('/[^a-zA-Z0-9\-\.\,\(\)_]+/', ' ', $string);
			$string = preg_replace('/[\s]+/', ' ', $string);

			return $string;
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



		// Security functions
		protected function getCertificateFingerprint($bPublicCertificate = false)
		{
			if($fp = fopen($this->sSecurePath . ($bPublicCertificate ? $this->sPublicCertificateFile : $this->sPrivateCertificateFile), 'r'))
			{
				$sRawData = fread($fp, 8192);
				fclose($fp);

				$sData = openssl_x509_read($sRawData);

				if(!openssl_x509_export($sData, $sData))
				{
					$this->setError('Error in certificate ' . $this->sSecurePath . ($bPublicCertificate ? $this->sPublicCertificateFile : $this->sPrivateCertificateFile), false, __FILE__, __LINE__);
					return false;
				}
			
				$sData = str_replace('-----BEGIN CERTIFICATE-----', '', $sData);
				$sData = str_replace('-----END CERTIFICATE-----', '', $sData);

				return strtoupper(sha1(base64_decode($sData)));
			}
			else
			{
				$this->setError('Cannot open certificate file: ' . ($bPublicCertificate ? $this->sPublicCertificateFile : $this->sPrivateCertificateFile), false, __FILE__, __LINE__);
			}
		}

		// Calculate signature of the given message
		protected function getSignature($sMessage)
		{
			$sMessage = $this->removeSpaceCharacters($sMessage);

			if($fp = fopen($this->sSecurePath . $this->sPrivateKeyFile, 'r'))
			{
				$sRawData = fread($fp, 8192);
				fclose($fp);

				$sSignature = '';

				if($sPrivateKey = openssl_get_privatekey($sRawData, $this->sPrivateKeyPass))
				{
					if(openssl_sign($sMessage, $sSignature, $sPrivateKey))
					{
						openssl_free_key($sPrivateKey);
						$sSignature = base64_encode($sSignature);
					}
					else
					{
						$this->setError('Error while signing message.', false, __FILE__, __LINE__);
					}
				}
				else
				{
					$this->setError('Invalid password for ' . $this->sPrivateKeyFile . ' file.', false, __FILE__, __LINE__);
				}

				return $sSignature;
			}
			else
			{
				$this->setError('Cannot open private key file: ' . $this->sPrivateKeyFile, false, __FILE__, __LINE__);
			}
		}

		// Validate signature for the given data
		protected function verifySignature($sData, $sSignature)
		{
			$bOk = false;

			if($fp = fopen($this->sSecurePath . $this->sPublicCertificateFile, 'r'))
			{
				$sRawData = fread($fp, 8192);
				fclose($fp);

				if($sPublicKey = openssl_get_publickey($sRawData))
				{
					$bOk = (openssl_verify($sData, $sSignature, $sPublicKey) ? true : false);
					openssl_free_key($sPublicKey);
				}
				else
				{
					$this->setError('Cannot retrieve key from public certificate file: ' . $this->sPublicCertificateFile, false, __FILE__, __LINE__);
				}
			}
			else
			{
				$this->setError('Cannot open public certificate file: ' . $this->sPublicCertificateFile, false, __FILE__, __LINE__);
			}

			return $bOk;
		}
	}




	class IssuerRequest extends IdealRequest
	{
		public function __construct()
		{
			parent::__construct();
		}

		// Execute request (Lookup issuer list)
		public function doRequest()
		{
			if($this->checkConfiguration())
			{
				$sCacheFile = false;

				if(($this->bTestMode == false) && $this->sCachePath)
				{
					$sCacheFile = $this->sCachePath . 'issuers.cache';
					$bFileCreated = false;

					if(file_exists($sCacheFile) == false)
					{
						$bFileCreated = true;

						// Attempt to create cache file
						if(@touch($sCacheFile))
						{
							@chmod($sCacheFile, 0777);
						}
					}

					if(file_exists($sCacheFile) && is_readable($sCacheFile) && is_writable($sCacheFile))
					{
						if($bFileCreated || (filemtime($sCacheFile) > strtotime('-24 Hours')))
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

				$sTimestamp = gmdate('Y-m-d') . 'T' . gmdate('H:i:s') . '.000Z';
				$sMessage = $this->removeSpaceCharacters($sTimestamp . $this->sMerchantId . $this->sSubId);

				$sToken = $this->getCertificateFingerprint();
				$sTokenCode = $this->getSignature($sMessage);

				$sXmlMessage = '<?xml version="1.0" encoding="UTF-8" ?>' . $this->LF
				. '<DirectoryReq xmlns="http://www.idealdesk.com/Message" version="1.1.0">' . $this->LF
				. '<createDateTimeStamp>' . $this->escapeXml($sTimestamp) . '</createDateTimeStamp>' . $this->LF
				. '<Merchant>' . $this->LF
				. '<merchantID>' . $this->escapeXml($this->sMerchantId) . '</merchantID>' . $this->LF
				. '<subID>' . $this->escapeXml($this->sSubId) . '</subID>' . $this->LF
				. '<authentication>SHA1_RSA</authentication>' . $this->LF
				. '<token>' . $this->escapeXml($sToken) . '</token>' . $this->LF
				. '<tokenCode>' . $this->escapeXml($sTokenCode) . '</tokenCode>' . $this->LF
				. '</Merchant>' . $this->LF
				. '</DirectoryReq>';

				$sXmlReply = $this->postToHost($this->sAquirerUrl, $sXmlMessage, 10);

				if($sXmlReply)
				{
					if($this->parseFromXml('errorCode', $sXmlReply)) // Error found
					{
						// Add error to error-list
						$this->setError($this->parseFromXml('errorMessage', $sXmlReply) . ' - ' . $this->parseFromXml('errorDetail', $sXmlReply), $this->parseFromXml('errorCode', $sXmlReply), __FILE__, __LINE__);
					}
					else
					{
						$aIssuerShortList = array();
						$aIssuerLongList = array();

						while(strpos($sXmlReply, '<issuerID>'))
						{
							$sIssuerId = $this->parseFromXml('issuerID', $sXmlReply);
							$sIssuerName = $this->parseFromXml('issuerName', $sXmlReply);
							$sIssuerList = $this->parseFromXml('issuerList', $sXmlReply);

							if(strcmp($sIssuerList, 'Short') === 0) // Short list
							{
								// Only support ABN Amro Bank when in HTTPS mode.
								// if((strcasecmp(substr($_SERVER['SERVER_PROTOCOL'], 0, 5), 'HTTPS') === 0) || (stripos($sIssuerName, 'ABN') === false))
								{
									$aIssuerShortList[$sIssuerId] = $sIssuerName;
								}
							}
							else // Long list
							{
								$aIssuerLongList[$sIssuerId] = $sIssuerName;
							}

							$sXmlReply = substr($sXmlReply, strpos($sXmlReply, '</issuerList>') + 13);
						}


						// $aIssuerList = array_merge($aIssuerShortList, $aIssuerLongList);
						$aIssuerList = $aIssuerShortList;

						if(sizeof($aIssuerLongList))
						{
							foreach($aIssuerLongList as $k => $v)
							{
								$aIssuerList[$k] = $v;
							}
						}


						// Save data in cache?
						if($sCacheFile)
						{
							if($oHandle = fopen($sCacheFile, 'w'))
							{
								fwrite($oHandle, serialize($aIssuerList));
								fclose($oHandle);
							}
						}

						return $aIssuerList;
					}
				}
			}

			return false;
		}
	}




	class TransactionRequest extends IdealRequest
	{
		protected $sOrderId;
		protected $sOrderDescription;
		protected $iOrderAmount;
		protected $sReturnUrl;
		protected $sIssuerId;
		protected $sEntranceCode;

		// Transaction info
		protected $sTransactionId;
		protected $sTransactionUrl;

		public function __construct()
		{
			parent::__construct();

			if(defined('IDEAL_RETURN_URL'))
			{
				$this->setReturnUrl(IDEAL_RETURN_URL);
			}

			// Random EntranceCode
			$this->sEntranceCode = sha1(rand(1000000, 9999999));
		}

		public function setOrderId($sOrderId)
		{
			$this->sOrderId = substr($sOrderId, 0, 16);
		}

		public function setOrderDescription($sOrderDescription)
		{
			$this->sOrderDescription = trim(substr($this->escapeSpecialChars($sOrderDescription), 0, 32));
		}

		public function setOrderAmount($fOrderAmount)
		{
			$this->iOrderAmount = round($fOrderAmount * 100);
		}

		public function setReturnUrl($sReturnUrl)
		{
			// Fix for ING Bank, urlescape [ and ]
			$sReturnUrl = str_replace('[', '%5B', $sReturnUrl);
			$sReturnUrl = str_replace(']', '%5D', $sReturnUrl);

			$this->sReturnUrl = substr($sReturnUrl, 0, 512);
		}

		// ID of the selected bank
		public function setIssuerId($sIssuerId)
		{
			$this->sIssuerId = $sIssuerId;
		}

		// A random generated entrance code
		public function setEntranceCode($sEntranceCode)
		{
			$this->sEntranceCode = substr($sEntranceCode, 0, 40);
		}

		// Retrieve the transaction URL recieved in the XML response of de IDEAL SERVER
		public function getTransactionUrl()
		{
			return $this->sTransactionUrl;
		}

		// Execute request (Setup transaction)
		public function doRequest()
		{
			if($this->checkConfiguration() && $this->checkConfiguration(array('sOrderId', 'sOrderDescription', 'iOrderAmount', 'sReturnUrl', 'sReturnUrl', 'sIssuerId', 'sEntranceCode')))
			{
				$sTimestamp = gmdate('Y-m-d') . 'T' . gmdate('H:i:s') . '.000Z';
				$sMessage = $this->removeSpaceCharacters($sTimestamp . $this->sIssuerId . $this->sMerchantId . $this->sSubId . $this->sReturnUrl . $this->sOrderId . $this->iOrderAmount . 'EUR' . 'nl' . $this->sOrderDescription . $this->sEntranceCode);
				$sToken = $this->getCertificateFingerprint();
				$sTokenCode = $this->getSignature($sMessage);

				$sXmlMessage = '<?xml version="1.0" encoding="UTF-8" ?>' . $this->LF
				. '<AcquirerTrxReq xmlns="http://www.idealdesk.com/Message" version="1.1.0">' . $this->LF
				. '<createDateTimeStamp>' . $this->escapeXml($sTimestamp) .  '</createDateTimeStamp>' . $this->LF
				. '<Issuer>' . $this->LF
				. '<issuerID>' . $this->escapeXml($this->sIssuerId) . '</issuerID>' . $this->LF
				. '</Issuer>' . $this->LF
				. '<Merchant>' . $this->LF 
				. '<merchantID>' . $this->escapeXml($this->sMerchantId) . '</merchantID>' . $this->LF
				. '<subID>' . $this->escapeXml($this->sSubId) . '</subID>' . $this->LF
				. '<authentication>SHA1_RSA</authentication>' . $this->LF
				. '<token>' . $this->escapeXml($sToken) . '</token>' . $this->LF
				. '<tokenCode>' . $this->escapeXml($sTokenCode) . '</tokenCode>' . $this->LF
				. '<merchantReturnURL>' . $this->escapeXml($this->sReturnUrl) . '</merchantReturnURL>' . $this->LF
				. '</Merchant>' . $this->LF
				. '<Transaction>' . $this->LF
				. '<purchaseID>' . $this->escapeXml($this->sOrderId) . '</purchaseID>' . $this->LF
				. '<amount>' . $this->escapeXml($this->iOrderAmount) . '</amount>' . $this->LF
				. '<currency>EUR</currency>' . $this->LF
				. '<expirationPeriod>PT30M</expirationPeriod>' . $this->LF
				. '<language>nl</language>' . $this->LF
				. '<description>' . $this->escapeXml($this->sOrderDescription) . '</description>' . $this->LF
				. '<entranceCode>' . $this->escapeXml($this->sEntranceCode) . '</entranceCode>' . $this->LF
				. '</Transaction>' . $this->LF 
				. '</AcquirerTrxReq>';

				$sXmlReply = $this->postToHost($this->sAquirerUrl, $sXmlMessage, 10);

				if($sXmlReply)
				{
					if($this->parseFromXml('errorCode', $sXmlReply)) // Error found
					{
						// Add error to error-list
						$this->setError($this->parseFromXml('errorMessage', $sXmlReply) . ' - ' . $this->parseFromXml('errorDetail', $sXmlReply), $this->parseFromXml('errorCode', $sXmlReply), __FILE__, __LINE__);
					}
					else
					{
						$this->sTransactionId = $this->parseFromXml('transactionID', $sXmlReply);
						$this->sTransactionUrl = html_entity_decode($this->parseFromXml('issuerAuthenticationURL', $sXmlReply));

						return $this->sTransactionId;
					}
				}
			}

			return false;
		}

		// Start transaction
		public function doTransaction()
		{
			if((sizeof($this->aErrors) == 0) && $this->sTransactionId && $this->sTransactionUrl)
			{
				header('Location: ' . $this->sTransactionUrl);
				exit;
			}

			$this->setError('Please setup a valid transaction request first.', false, __FILE__, __LINE__);
			return false;
		}
	}




	class StatusRequest extends IdealRequest
	{
		// Account info
		protected $sAccountCity;
		protected $sAccountName;
		protected $sAccountNumber;

		// Transaction info
		protected $sTransactionId;
		protected $sTransactionStatus;

		public function __construct()
		{
			parent::__construct();
		}

		// Set transaction id
		public function setTransactionId($sTransactionId)
		{
			$this->sTransactionId = $sTransactionId;
		}

		// Get account city
		public function getAccountCity()
		{
			if(!empty($this->sAccountCity))
			{
				return $this->sAccountCity;
			}

			return '';
		}

		// Get account name
		public function getAccountName()
		{
			if(!empty($this->sAccountName))
			{
				return $this->sAccountName;
			}

			return '';
		}

		// Get account number
		public function getAccountNumber()
		{
			if(!empty($this->sAccountNumber))
			{
				return $this->sAccountNumber;
			}

			return '';
		}

		// Execute request
		public function doRequest()
		{
			if($this->checkConfiguration() && $this->checkConfiguration(array('sTransactionId')))
			{
				$sTimestamp = gmdate('Y-m-d') . 'T' . gmdate('H:i:s') . '.000Z';
				$sMessage = $this->removeSpaceCharacters($sTimestamp . $this->sMerchantId . $this->sSubId . $this->sTransactionId);
				$sToken = $this->getCertificateFingerprint();
				$sTokenCode = $this->getSignature($sMessage);

				$sXmlMessage = '<?xml version="1.0" encoding="UTF-8" ?>' . $this->LF
				. '<AcquirerStatusReq xmlns="http://www.idealdesk.com/Message" version="1.1.0">' . $this->LF
				. '<createDateTimeStamp>' . $this->escapeXml($sTimestamp) . '</createDateTimeStamp>' . $this->LF
				. '<Merchant>' 
				. '<merchantID>' . $this->escapeXml($this->sMerchantId) . '</merchantID>' . $this->LF
				. '<subID>' . $this->escapeXml($this->sSubId) . '</subID>' . $this->LF
				. '<authentication>SHA1_RSA</authentication>' . $this->LF
				. '<token>' . $this->escapeXml($sToken) . '</token>' . $this->LF
				. '<tokenCode>' . $this->escapeXml($sTokenCode) . '</tokenCode>' . $this->LF
				. '</Merchant>' . $this->LF
				. '<Transaction>' 
				. '<transactionID>' . $this->escapeXml($this->sTransactionId) . '</transactionID>' . $this->LF
				. '</Transaction>' 
				. '</AcquirerStatusReq>';

				$sXmlReply = $this->postToHost($this->sAquirerUrl, $sXmlMessage, 10);

				if($sXmlReply)
				{
					if($this->parseFromXml('errorCode', $sXmlReply)) // Error found
					{
						// Add error to error-list
						$this->setError($this->parseFromXml('errorMessage', $sXmlReply) . ' - ' . $this->parseFromXml('errorDetail', $sXmlReply), $this->parseFromXml('errorCode', $sXmlReply), __FILE__, __LINE__);
					}
					else
					{
						$sTimestamp = $this->parseFromXml('createDateTimeStamp', $sXmlReply);
						$sTransactionId = $this->parseFromXml('transactionID', $sXmlReply);
						$sTransactionStatus = $this->parseFromXml('status', $sXmlReply);

						$sAccountNumber = $this->parseFromXml('consumerAccountNumber', $sXmlReply);
						$sAccountName = $this->parseFromXml('consumerName', $sXmlReply);
						$sAccountCity = $this->parseFromXml('consumerCity', $sXmlReply);

						$sMessage = $this->removeSpaceCharacters($sTimestamp . $sTransactionId . $sTransactionStatus . $sAccountNumber);

						$sSignature = base64_decode($this->parseFromXml('signatureValue', $sXmlReply));
						$sFingerprint = $this->parseFromXml('fingerprint', $sXmlReply);

						if(strcasecmp($sFingerprint, $this->getCertificateFingerprint(true)) !== 0)
						{
							// Invalid Fingerprint
							$this->setError('Unknown fingerprint.', false, __FILE__, __LINE__);
						}
						elseif($this->verifySignature($sMessage, $sSignature) == false)
						{
							// Invalid Fingerprint
							$this->setError('Bad signature.', false, __FILE__, __LINE__);
						}
						else
						{
							// $this->sTransactionId = $sTransactionId;
							$this->sTransactionStatus = strtoupper($sTransactionStatus);

							$this->sAccountCity = $sAccountCity;
							$this->sAccountName = $sAccountName;
							$this->sAccountNumber = $sAccountNumber;

							return $this->sTransactionStatus;
						}
					}
				}
			}

			return false;
		}
	}
?>