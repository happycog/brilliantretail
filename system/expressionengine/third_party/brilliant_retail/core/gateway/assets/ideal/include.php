<?php

function database_getSettings()
	{
		// Array to contain all settings
		$aSettings = array();

		// MySQL Server/Host
		$aSettings['host'] = 'localhost';

		// MySQL Username
		$aSettings['user'] = '';

		// MySQL Password
		$aSettings['pass'] = '';

		// MySQL Database name
		$aSettings['name'] = '';

		// MySQL Table Prefix
		$aSettings['prefix'] = '';

		return $aSettings;
	}


function gateway_getSettings()
	{
		// Array to contain all settings
		$aSettings = array();

		// Your iDEAL Merchant ID
		$aSettings['MERCHANT_ID'] = '123456789';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$aSettings['TEST_MODE'] = true;

		// Password used to generate private key file
		$aSettings['PRIVATE_KEY_PASS'] = 'Password';

		// Name of your PRIVATE-KEY-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_KEY_FILE'] = 'private.key';

		// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'private.cer';

		// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
		$aSettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

		// Path to TEMP folder (This folder should not be accessable for webusers)
		$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'iDEAL Simulator';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.ideal-simulator.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-professional';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-professional/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;


		// Email settings for iDEAL status updates
		$aSettings['EMAIL_TO'] = 'info@domain.tld';
		$aSettings['EMAIL_FROM'] = 'info@domain.tld';

		return $aSettings;
	}



	$aGatewaySettings = gateway_getSettings();



	// Load gateway class
	if(file_exists($aGatewaySettings['GATEWAY_FILE']) == false)
	{
		die('ERROR: Cannot load gateway file "' . $aGatewaySettings['GATEWAY_FILE'] . '".<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);
	}
	else
	{
		require_once($aGatewaySettings['GATEWAY_FILE']);
	}



	// Define database settings
	define('DATABASE_HOST', $aDatabaseSettings['host'], true);
	define('DATABASE_USER', $aDatabaseSettings['user'], true);
	define('DATABASE_PASS', $aDatabaseSettings['pass'], true);
	define('DATABASE_NAME', $aDatabaseSettings['name'], true);
	define('DATABASE_PREFIX', $aDatabaseSettings['prefix'], true);

	// Connect to database
	mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS) or die('ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);
	mysql_select_db(DATABASE_NAME) or die('ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);


	// Order update
	// $oRecord containts current record from table #transactions
	// $sView contains the current called view (doReport, doReturn, doValidate)
	function gateway_update_order_status($oRecord, $sView)
	{
		$aGatewaySettings = gateway_getSettings();

		if(in_array($oRecord['transaction_status'], array('SUCCESS', 'PENDING')))
		{
			$sMailTo = '';
			$sMailSubject = 'iDEAL Update: ' . $oRecord['transaction_description'];
			$sMailMessage = '';
			$sMailHeaders = '';

			if(!empty($aGatewaySettings['EMAIL_TO']))
			{
				$sMailTo = $aGatewaySettings['EMAIL_TO'];
				$sMailHeaders = 'From: "iDEAL Betaal Formulier" <' . $aGatewaySettings['EMAIL_TO'] . '>';
			}

			if(!empty($aGatewaySettings['EMAIL_FROM']))
			{
				$sMailHeaders = 'From: ' . $aGatewaySettings['EMAIL_FROM'];
			}

			if($sMailTo && $sMailHeaders)
			{
				// Send email to webmaster
				$sMailMessage .= 'iDEAL Update

Order:         ' . $oRecord['order_id'] . '
Bedrag:        ' . $oRecord['transaction_amount'] . '
Omschrijving:  ' . $oRecord['transaction_description'] . '

Params:        ' . str_replace(array("\r", "\n"), array("", "\n               "), $oRecord['transaction_params']) . '

Transactie:    ' . $oRecord['transaction_id'] . '
Status:        ' . $oRecord['transaction_status'] . '


Controleer de status van iDEAL transacties ALTIJD via uw iDEAL Dashboard.
';

				mail($sMailTo, $sMailSubject, $sMailMessage, $sMailHeaders);
			}
		}
	}

?>