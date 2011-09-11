<?php

	class IdealInternetKassa
	{
		// Available keys for HASH'es
		protected $aShaIn = array('ACCEPTURL', 'ADDMATCH', 'ADDRMATCH', 'ALIAS', 'ALIASOPERATION', 'ALIASUSAGE', 'ALLOWCORRECTION', 'AMOUNT', 'AMOUNTHTVA', 'AMOUNTTVA', 'BACKURL', 'BGCOLOR', 'BRAND', 'BRANDVISUAL', 'BUTTONBGCOLOR', 'BUTTONTXTCOLOR', 'CANCELURL', 'CARDNO', 'CATALOGURL', 'CERTID', 'CHECK_AAV', 'CIVILITY', 'CN', 'COM', 'COMPLUS', 'COSTCENTER', 'CREDITCODE', 'CUID', 'CURRENCY', 'CVC', 'DATA', 'DATATYPE', 'DATEIN', 'DATEOUT', 'DECLINEURL', 'DISCOUNTRATE', 'ECI', 'ECOM_BILLTO_POSTAL_CITY', 'ECOM_BILLTO_POSTAL_COUNTRYCODE', 'ECOM_BILLTO_POSTAL_NAME_FIRST', 'ECOM_BILLTO_POSTAL_NAME_LAST', 'ECOM_BILLTO_POSTAL_POSTALCODE', 'ECOM_BILLTO_POSTAL_STREET_LINE1', 'ECOM_BILLTO_POSTAL_STREET_LINE2', 'ECOM_BILLTO_POSTAL_STREET_NUMBER', 'ECOM_CONSUMERID', 'ECOM_CONSUMERORDERID', 'ECOM_CONSUMERUSERALIAS', 'ECOM_PAYMENT_CARD_EXPDATE_MONTH', 'ECOM_PAYMENT_CARD_EXPDATE_YEAR', 'ECOM_PAYMENT_CARD_NAME', 'ECOM_PAYMENT_CARD_VERIFICATION', 'ECOM_SHIPTO_COMPANY', 'ECOM_SHIPTO_DOB', 'ECOM_SHIPTO_ONLINE_EMAIL', 'ECOM_SHIPTO_POSTAL_CITY', 'ECOM_SHIPTO_POSTAL_COUNTRYCODE', 'ECOM_SHIPTO_POSTAL_NAME_FIRST', 'ECOM_SHIPTO_POSTAL_NAME_LAST', 'ECOM_SHIPTO_POSTAL_POSTALCODE', 'ECOM_SHIPTO_POSTAL_STREET_LINE1', 'ECOM_SHIPTO_POSTAL_STREET_LINE2', 'ECOM_SHIPTO_POSTAL_STREET_NUMBER', 'ECOM_SHIPTO_TELECOM_FAX_NUMBER', 'ECOM_SHIPTO_TELECOM_PHONE_NUMBER', 'ECOM_SHIPTO_TVA', 'ED', 'EMAIL', 'EXCEPTIONURL', 'EXCLPMLIST', 'FIRSTCALL', 'FLAG3D', 'FONTTYPE', 'FORCECODE1', 'FORCECODE2', 'FORCECODEHASH', 'FORCETP', 'GENERIC_BL', 'GIROPAY_ACCOUNT_NUMBER', 'GIROPAY_BLZ', 'GIROPAY_OWNER_NAME', 'GLOBORDERID', 'GUID', 'HDFONTTYPE', 'HDTBLBGCOLOR', 'HDTBLTXTCOLOR', 'HEIGHTFRAME', 'HOMEURL', 'HTTP_ACCEPT', 'HTTP_USER_AGENT', 'INCLUDE_BIN', 'INCLUDE_COUNTRIES', 'INVDATE', 'INVDISCOUNT', 'INVLEVEL', 'INVORDERID', 'ISSUERID', 'LANGUAGE', 'LEVEL1AUTHCPC', 'LIMITCLIENTSCRIPTUSAGE', 'LINE_REF', 'LIST_BIN', 'LIST_COUNTRIES', 'LOGO', 'MERCHANTID', 'MODE', 'MTIME', 'MVER', 'OPERATION', 'OR_INVORDERID', 'OR_ORDERID', 'ORDERID', 'ORIG', 'OWNERADDRESS', 'OWNERADDRESS2', 'OWNERCTY', 'OWNERTELNO', 'OWNERTOWN', 'OWNERZIP', 'PAIDAMOUNT', 'PARAMPLUS', 'PARAMVAR', 'PAYID', 'PAYMETHOD', 'PM', 'PMLIST', 'PMLISTPMLISTTYPE', 'PMLISTTYPE', 'PMLISTTYPEPMLIST', 'PMTYPE', 'POPUP', 'POST', 'PSPID', 'PSWD', 'REF', 'REF_CUSTOMERID', 'REF_CUSTOMERREF', 'REFER', 'REFID', 'REFKIND', 'REMOTE_ADDR', 'REQGENFIELDS', 'RTIMEOUT', 'RTIMEOUTREQUESTEDTIMEOUT', 'SCORINGCLIENT', 'SETT_BATCH', 'SID', 'TAAL', 'TBLBGCOLOR', 'TBLTXTCOLOR', 'TID', 'TITLE', 'TOTALAMOUNT', 'TP', 'TRACK2', 'TXTBADDR2', 'TXTCOLOR', 'TXTOKEN', 'TXTOKENTXTOKENPAYPAL', 'TYPE_COUNTRY', 'UCAF_AUTHENTICATION_DATA', 'UCAF_PAYMENT_CARD_CVC2', 'UCAF_PAYMENT_CARD_EXPDATE_MONTH', 'UCAF_PAYMENT_CARD_EXPDATE_YEAR', 'UCAF_PAYMENT_CARD_NUMBER', 'USERID', 'USERTYPE', 'VERSION', 'WBTU_MSISDN', 'WBTU_ORDERID', 'WEIGHTUNIT', 'WIN3DS', 'WITHROOT');
		protected $aShaOut = array('AAVADDRESS', 'AAVCHECK', 'AAVZIP', 'ACCEPTANCE', 'ALIAS', 'AMOUNT', 'BRAND', 'CARDNO', 'CCCTY', 'CN', 'COMPLUS', 'CURRENCY', 'CVCCHECK', 'DCC_COMMPERCENTAGE', 'DCC_CONVAMOUNT', 'DCC_CONVCCY', 'DCC_EXCHRATE', 'DCC_EXCHRATESOURCE', 'DCC_EXCHRATETS', 'DCC_INDICATOR', 'DCC_MARGINPERCENTAGE', 'DCC_VALIDHOUS', 'DIGESTCARDNO', 'ECI', 'ED', 'ENCCARDNO', 'IP', 'IPCTY', 'NBREMAILUSAGE', 'NBRIPUSAGE', 'NBRIPUSAGE_ALLTX', 'NBRUSAGE', 'NCERROR', 'ORDERID', 'PAYID', 'PM', 'SCO_CATEGORY', 'SCORING', 'STATUS', 'TRXDATE', 'VC');

		// Default values
		protected $aValues = array('ACQUIRER' => 'Rabobank', 'PSPID' => 'TESTSTDRIK', 'SHA1_IN_KEY' => '', 'SHA1_OUT_KEY' => '', 'TEST_MODE' => false, 'CURRENCY' => 'EUR', 'LANGUAGE' => 'nl_NL', 'ORDERID' => '', 'AMOUNT' => 0, 'CN' => '', 'EMAIL' => '', 'OWNERZIP' => '', 'OWNERADDRESS' => '', 'OWNERCTY' => '', 'OWNERTOWN' => '', 'OWNERTELNO' => '', 'COM' => 'Webshop Bestelling');

		public function __construct()
		{
		}

		function setValue($sKey, $sValue = '')
		{
			$sKey = strtoupper($sKey);

			$this->aValues[$sKey] = $sValue;
		}

		function getValue($sKey)
		{
			$sKey = strtoupper($sKey);

			if(isset($this->aValues[$sKey]))
			{
				return $this->aValues[$sKey];
			}

			return '';
		}

		protected function getActionUrl()
		{
			if(isset($this->aValues['ACQUIRER']))
			{
				$sAcquirer = strtolower($this->aValues['ACQUIRER']);

				if(strpos($sAcquirer, 'abn') !== false) // ABN AMRO
				{
					// Unknown..
				}
				elseif(strpos($sAcquirer, 'ing') !== false) // ING Bank
				{
					// Unknown..
				}
				elseif(strpos($sAcquirer, 'rab') !== false) // Rabobank
				{
					return 'https://i-kassa.rabobank.nl/rik/' . ($this->aValues['TEST_MODE'] ? 'test' : 'prod') . '/orderstandard.asp';
				}
				elseif(strpos($sAcquirer, 'sim') !== false) // iDEAL Simulator
				{
					// Not available
				}
			}

			return '';
		}

		public function createForm($sSubmitLabel = 'Afrekenen')
		{
			// Calculate HASH
			$sHashString = '';

			foreach($this->aShaIn as $k)
			{
				if(isset($this->aValues[$k]) && strlen($this->aValues[$k]))
				{
					$sHashString .= $k . '=' . $this->aValues[$k] . $this->aValues['SHA1_IN_KEY'];
				}
			}

			$sHash = strtoupper(sha1($sHashString));


			$html = '
<form method="post" action="' . $this->getActionUrl() . '" id="ogone" name="ogone">
	<input type="hidden" name="SHASIGN" value="' . htmlspecialchars($sHash) . '">';

			foreach($this->aShaIn as $k)
			{
				if(isset($this->aValues[$k]))
				{
					$html .= '
	<input type="hidden" name="' . htmlspecialchars($k) . '" value="' . htmlspecialchars($this->aValues[$k]) . '">';
				}
			}

			$html .= '
	<input type="submit" value="' . htmlspecialchars($sSubmitLabel) . '">
</form>';

			return $html;
		}

		public function validate()
		{
			$sTransactionStatus = false;

			if(isset($_GET))
			{
				$aValues = array_change_key_case($_GET, CASE_UPPER);

				if(isset($aValues['SHASIGN']))
				{
					// Calculate HASH
					$sHashString = '';

					foreach($this->aShaOut as $k)
					{
						if(isset($aValues[$k]) && strlen($aValues[$k]))
						{
							$sHashString .= $k . '=' . $aValues[$k] . $this->aValues['SHA1_OUT_KEY'];
						}
					}

					$sHash = strtoupper(sha1($sHashString));

					if(strcmp($aValues['SHASIGN'], $sHash) === 0) // Reply is valid!
					{
						// Add raw POST data to $this->aValues
						foreach($aValues as $k => $v)
						{
							$this->aValues[$k] = $v;
						}

						if(strcasecmp($aValues['PM'], 'IDEAL') === 0)
						{
							// Return transaction status
							switch($aValues['STATUS'])
							{
								case '0': $sTransactionStatus = 'FAILURE'; break;
								case '1': $sTransactionStatus = 'FAILURE'; break; // Alternative
								case '2': $sTransactionStatus = 'FAILURE'; break;
								case '4': $sTransactionStatus = 'PENDING'; break;
								case '41': $sTransactionStatus = 'PENDING'; break;
								case '5': $sTransactionStatus = 'SUCCESS'; break;
								case '51': $sTransactionStatus = 'PENDING'; break;
								case '52': $sTransactionStatus = 'PENDING'; break;
								case '55': $sTransactionStatus = 'PENDING'; break;
								case '59': $sTransactionStatus = 'PENDING'; break;
								case '6': $sTransactionStatus = 'CANCELLED'; break;
								case '61': $sTransactionStatus = 'PENDING'; break;
								case '62': $sTransactionStatus = 'PENDING'; break;
								case '63': $sTransactionStatus = 'FAILURE'; break;
								case '64': $sTransactionStatus = 'CANCELLED'; break;
								case '7': $sTransactionStatus = 'SUCCESS'; break;
								case '71': $sTransactionStatus = 'PENDING'; break;
								case '72': $sTransactionStatus = 'PENDING'; break;
								case '73': $sTransactionStatus = 'FAILURE'; break;
								case '74': $sTransactionStatus = 'SUCCESS'; break;
								case '75': $sTransactionStatus = 'SUCCESS'; break;
								case '8': $sTransactionStatus = 'SUCCESS'; break;
								case '81': $sTransactionStatus = 'PENDING'; break;
								case '82': $sTransactionStatus = 'PENDING'; break;
								case '83': $sTransactionStatus = 'FAILURE'; break;
								case '84': $sTransactionStatus = 'FAILURE'; break;
								case '85': $sTransactionStatus = 'SUCCESS'; break;
								case '9': $sTransactionStatus = 'SUCCESS'; break; // Alternative
								case '91': $sTransactionStatus = 'PENDING'; break;
								case '92': $sTransactionStatus = 'EXPIRE'; break; // Alternative
								case '93': $sTransactionStatus = 'FAILURE'; break;
								case '94': $sTransactionStatus = 'FAILURE'; break;
								case '95': $sTransactionStatus = 'SUCCESS'; break;
								case '99': $sTransactionStatus = 'PENDING'; break;
							}
						}
						else
						{
							// Return transaction status
							switch($aValues['STATUS'])
							{
								case '0': $sTransactionStatus = 'FAILURE'; break;
								case '1': $sTransactionStatus = 'CANCELLED'; break;
								case '2': $sTransactionStatus = 'FAILURE'; break;
								case '4': $sTransactionStatus = 'PENDING'; break;
								case '41': $sTransactionStatus = 'PENDING'; break;
								case '5': $sTransactionStatus = 'SUCCESS'; break;
								case '51': $sTransactionStatus = 'PENDING'; break;
								case '52': $sTransactionStatus = 'PENDING'; break;
								case '55': $sTransactionStatus = 'PENDING'; break;
								case '59': $sTransactionStatus = 'PENDING'; break;
								case '6': $sTransactionStatus = 'CANCELLED'; break;
								case '61': $sTransactionStatus = 'PENDING'; break;
								case '62': $sTransactionStatus = 'PENDING'; break;
								case '63': $sTransactionStatus = 'FAILURE'; break;
								case '64': $sTransactionStatus = 'CANCELLED'; break;
								case '7': $sTransactionStatus = 'SUCCESS'; break;
								case '71': $sTransactionStatus = 'PENDING'; break;
								case '72': $sTransactionStatus = 'PENDING'; break;
								case '73': $sTransactionStatus = 'FAILURE'; break;
								case '74': $sTransactionStatus = 'SUCCESS'; break;
								case '75': $sTransactionStatus = 'SUCCESS'; break;
								case '8': $sTransactionStatus = 'SUCCESS'; break;
								case '81': $sTransactionStatus = 'PENDING'; break;
								case '82': $sTransactionStatus = 'PENDING'; break;
								case '83': $sTransactionStatus = 'FAILURE'; break;
								case '84': $sTransactionStatus = 'FAILURE'; break;
								case '85': $sTransactionStatus = 'SUCCESS'; break;
								case '9': $sTransactionStatus = 'PENDING'; break;
								case '91': $sTransactionStatus = 'PENDING'; break;
								case '92': $sTransactionStatus = 'PENDING'; break;
								case '93': $sTransactionStatus = 'FAILURE'; break;
								case '94': $sTransactionStatus = 'FAILURE'; break;
								case '95': $sTransactionStatus = 'SUCCESS'; break;
								case '99': $sTransactionStatus = 'PENDING'; break;
							}
						}
					}
				}
			}

			return $sTransactionStatus;
		}
	}

?>