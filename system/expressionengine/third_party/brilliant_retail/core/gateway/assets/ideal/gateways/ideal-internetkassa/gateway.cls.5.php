<?php

	class Gateway extends GatewayCore
	{
		// Load iDEAL settings
		public function __construct()
		{
			$this->init();
		}

		
		// Setup payment
		public function doSetup()
		{
			$sHtml = '';

			// Look for proper GET's en POST's
			if(empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid setup request.</p>
<!-- Invalid orderid/ordercode -->';';
			}
			else
			{
				$sOrderId = $_GET['order_id'];
				$sOrderCode = $_GET['order_code'];

				// Lookup transaction
				if($this->getRecordByOrder($sOrderId, $sOrderCode))
				{
					if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
					{
						$sHtml .= '<p>Transaction already completed</p>';
					}
					else
					{
						$oIdeal = new IdealInternetKassa();

						// Account settings
						$oIdeal->setValue('ACQUIRER', $this->aSettings['GATEWAY_NAME']); // Aquirer Name
						$oIdeal->setValue('PSPID', $this->aSettings['PSP_ID']); // Merchant Id
						$oIdeal->setValue('SHA1_IN_KEY', $this->aSettings['SHA_1_IN']); // Secret Hash Key
						$oIdeal->setValue('SHA1_OUT_KEY', $this->aSettings['SHA_1_OUT']); // Secret Hash Key
						$oIdeal->setValue('TEST_MODE', $this->aSettings['TEST_MODE']); // True=TEST, False=LIVE

						// Webshop settings
						$oIdeal->setValue('accepturl', GatewayCore::getRootUrl() . 'return.php'); // Success/pending URL
						$oIdeal->setValue('declineurl', GatewayCore::getRootUrl() . 'return.php'); // Failure URL
						$oIdeal->setValue('exceptionurl', GatewayCore::getRootUrl() . 'return.php'); // Failure URL
						$oIdeal->setValue('cancelurl', GatewayCore::getRootUrl() . 'return.php'); // Cancelled URL
						$oIdeal->setValue('backurl', GatewayCore::getRootUrl() . 'return.php'); // Cart/Checkout URL
						// $oIdeal->setValue('homeurl', GatewayCore::getRootUrl()); // Homepage URL
						// $oIdeal->setValue('catalogurl', GatewayCore::getRootUrl()); // Catalog URL

						// Payment method(s)
						// $oIdeal->setValue('PM', 'iDEAL'); // Force 'iDEAL' or 'CreditCard'
						// $oIdeal->setValue('PMLIST', 'iDEAL;CreditCard'); // Available payment methods in acquirer GUI (when no PM was forced)
						$oIdeal->setValue('OPERATION', 'SAL');

						// Order settings
						$oIdeal->setValue('orderID', $this->oRecord['order_id']); // Order ID
						$oIdeal->setValue('COM', $this->oRecord['transaction_description']); // Order Description
						$oIdeal->setValue('amount', intval(round($this->oRecord['transaction_amount'] * 100))); // Order Amount
						
						// Customer settings (Optional)
						// $oIdeal->setValue('CN', 'Martijn Wieringa'); // Customer Name
						// $oIdeal->setValue('EMAIL', 'info@php-solutions.nl'); // Customer Email

						$sHtml = '<p><b>Direct online afrekenen via uw eigen bank.</b></p>' . $oIdeal->createForm('Verder >>') . '</div>';

						// Add auto-submit button
						if($this->aSettings['TEST_MODE'] == false)
						{
							$sHtml .= '<script type="text/javascript"> function doAutoSubmit() { document.forms[0].submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid setup request.</p>
<!-- Invalid orderid/ordercode -->';
				}
			}

			GatewayCore::output($sHtml);
		}


		// Catch return
		public function doReturn()
		{
			$sHtml = '';

			$oIdeal = new IdealInternetKassa();

			// Account settings
			$oIdeal->setValue('ACQUIRER', $this->aSettings['GATEWAY_NAME']); // Aquirer Name
			$oIdeal->setValue('PSPID', $this->aSettings['PSP_ID']); // Merchant Id
			$oIdeal->setValue('SHA1_IN_KEY', $this->aSettings['SHA_1_IN']); // Secret Hash Key
			$oIdeal->setValue('SHA1_OUT_KEY', $this->aSettings['SHA_1_OUT']); // Secret Hash Key
			$oIdeal->setValue('TEST_MODE', $this->aSettings['TEST_MODE']); // True=TEST, False=LIVE


			$sTransactionStatus = $oIdeal->validate();

			if($sTransactionStatus === false)
			{
				$sHtml .= '<p>Invalid return request.</p>
<!-- Invalid signature -->';
			}
			else
			{
				$sOrderId = $oIdeal->getValue('ORDERID');
				$sTransactionId = $oIdeal->getValue('PAYID');

				// Lookup record
				if($this->getRecordByOrder($sOrderId))
				{
					if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
					{
						if($this->oRecord['transaction_success_url'])
						{
							header('Location: ' . $this->oRecord['transaction_success_url']);
							exit;
						}
						else
						{
							$sHtml .= '<p>Uw betaling is met succes ontvangen.<br><input style="margin: 6px;" type="button" value="Terug naar de website" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl(1)) . '\'"></p>';
						}
					}
					else
					{
						$this->oRecord['transaction_id'] = $sTransactionId;
						$this->oRecord['transaction_status'] = $sTransactionStatus;

						if(empty($this->oRecord['transaction_log']) == false)
						{
							$this->oRecord['transaction_log'] .= "\n\n";
						}

						$this->oRecord['transaction_log'] .= 'Recieved status ' . $this->oRecord['transaction_status'] . ' for #' . $sTransactionId . ' on ' . date('Y-m-d, H:i:s') . '.';


						if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
						{
							$sHtml .= '<p>Uw betaling is met succes ontvangen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars($this->oRecord['transaction_success_url']) . '\'"></p>';
						}
						else
						{
							if(strcmp($this->oRecord['transaction_status'], 'CANCELLED') === 0)
							{
								$sHtml .= '<p>Uw betaling is geannuleerd. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl() . 'setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
							}
							elseif(strcmp($this->oRecord['transaction_status'], 'EXPIRED') === 0)
							{
								$sHtml .= '<p>Uw betaling is mislukt. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl() . 'setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
							}
							else // if(strcmp($this->oRecord['transaction_status'], 'FAILURE') === 0)
							{
								$sHtml .= '<p>Uw betaling is mislukt. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl() . 'setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
							}


							if($this->oRecord['transaction_payment_url'])
							{
								$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_payment_url']) . '">kies een andere betaalmethode</a></p>';
							}
							elseif($this->oRecord['transaction_failure_url'])
							{
								$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_failure_url']) . '">ik kan niet via iDEAL betalen</a></p>';
							}
						}


						// Update transaction
						$sql = "UPDATE `" . DATABASE_PREFIX . "transactions` SET `transaction_date` = '" . time() . "', `transaction_id` = '" . addslashes($this->oRecord['transaction_id']) . "', `transaction_status` = '" . addslashes($this->oRecord['transaction_status']) . "', `transaction_log` = '" . addslashes($this->oRecord['transaction_log']) . "' WHERE (`id` = '" . addslashes($this->oRecord['id']) . "') LIMIT 1;";
						mysql_query($sql) or die('QUERY: ' . $sql . ', ERROR: ' . mysql_error());



						// Handle status change
						if(function_exists('gateway_update_order_status'))
						{
							gateway_update_order_status($this->oRecord, 'doReturn');
						}



						if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
						{
							header('Location: ' . $this->oRecord['transaction_success_url']);
							exit;
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid return request.</p>
<!-- Invalid orderID -->';
				}
			}

			GatewayCore::output($sHtml);
		}
	}

?>