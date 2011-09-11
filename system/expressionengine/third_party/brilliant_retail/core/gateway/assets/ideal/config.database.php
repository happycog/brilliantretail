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

?>