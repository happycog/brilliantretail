<?php

	// Load gateway classes & libraries
	if(version_compare(PHP_VERSION, '5', '<'))
	{
		require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.4.php');
		require_once(dirname(__FILE__) . '/gateway.cls.4.php');
		require_once(dirname(__FILE__) . '/idealinternetkassa.cls.4.php');
	}
	else
	{
		require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
		require_once(dirname(__FILE__) . '/gateway.cls.5.php');
		require_once(dirname(__FILE__) . '/idealinternetkassa.cls.5.php');
	}

?>