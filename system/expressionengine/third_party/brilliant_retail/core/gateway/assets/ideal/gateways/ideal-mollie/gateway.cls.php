<?php

	// Load gateway classes & libraries
	if(version_compare(PHP_VERSION, '5', '<'))
	{
		die('No PHP 4 support available!');
	}
	else
	{
		require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
		require_once(dirname(__FILE__) . '/gateway.cls.5.php');
		require_once(dirname(__FILE__) . '/idealmollie.cls.5.php');
	}
	
?>