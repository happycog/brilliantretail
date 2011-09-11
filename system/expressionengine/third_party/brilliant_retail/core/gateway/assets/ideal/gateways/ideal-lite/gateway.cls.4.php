<?php

	class Gateway extends GatewayCore
	{
		// Load iDEAL settings
		function Gateway()
		{
			$this->init();
		}

		
		// Setup payment
		function doSetup()
		{
			$sHtml = '';

			// Look for proper GET's en POST's
			if(empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid setup request.</p>';
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
						$oIdealLite = new IdealLite();
						$oIdealLite->setHashKey($this->aSettings['HASH_KEY']);
						$oIdealLite->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['SUB_ID']);
						$oIdealLite->setAquirer($this->aSettings['GATEWAY_NAME'], $this->aSettings['TEST_MODE']);
						
						// Set return URLs
						$oIdealLite->setUrlCancel(GatewayCore::getRootUrl() . 'return.php?trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code'] . '&status=CANCELLED');
						$oIdealLite->setUrlError(GatewayCore::getRootUrl() . 'return.php?trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code'] . '&status=FAILURE');
						$oIdealLite->setUrlSuccess(GatewayCore::getRootUrl() . 'return.php?trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code'] . '&status=SUCCESS');

						// Set order details
						$oIdealLite->setOrderId($this->oRecord['order_id']); // Unieke order referentie (tot 16 karakters)
						$oIdealLite->setOrderDescription($this->oRecord['transaction_description']); // Order omschrijving (tot 32 karakters)
						$oIdealLite->setAmount($this->oRecord['transaction_amount']); // Bedrag (in EURO's)

						// Customize submit button
						$oIdealLite->setButton('Verder >>');

						$sHtml = '<p><b>Direct online afrekenen via uw eigen bank.</b></p>' . $oIdealLite->createForm() . '</div>';

						if($this->aSettings['TEST_MODE'] == false)
						{
							$sHtml .= '<script type="text/javascript"> function doAutoSubmit() { document.forms[0].submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid setup request.</p>';
				}
			}

			GatewayCore::output($sHtml);
		}


		// Catch return
		function doReturn()
		{
			$sHtml = '';

			if(empty($_GET['trxid']) || empty($_GET['ec']) || empty($_GET['status']))
			{
				$sHtml .= '<p>Invalid return request.</p>';
			}
			else
			{
				$sTransactionId = $_GET['trxid'];
				$sTransactionCode = $_GET['ec'];

				// Lookup record
				if($this->getRecordByTransaction($sTransactionId, $sTransactionCode))
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
						$this->oRecord['transaction_status'] = ((empty($_GET['status']) || (in_array($_GET['status'], array('SUCCESS', 'CANCELLED', 'FAILURE')) == false)) ? 'FAILURE' : $_GET['status']);

						if(empty($this->oRecord['transaction_log']) == false)
						{
							$this->oRecord['transaction_log'] .= "\n\n";
						}

						$this->oRecord['transaction_log'] .= 'Recieved status ' . $this->oRecord['transaction_status'] . ' for #' . $sTransactionId . ' on ' . date('Y-m-d, H:i:s') . '.';


						if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
						{
							$sHtml .= '<p>Uw betaling is met succes ontvangen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars($this->oRecord['transaction_success_url']) . '\'"></p>';
						}
						elseif((strcmp($this->oRecord['transaction_status'], 'OPEN') === 0) && !empty($this->oRecord['transaction_url']))
						{
							$sHtml .= '<p>Uw betaling is nog niet afgerond.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars($this->oRecord['transaction_url']) . '\'"></p>';
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
						$sql = "UPDATE `" . DATABASE_PREFIX . "transactions` SET `transaction_date` = '" . time() . "', `transaction_status` = '" . addslashes($this->oRecord['transaction_status']) . "', `transaction_log` = '" . addslashes($this->oRecord['transaction_log']) . "' WHERE (`id` = '" . addslashes($this->oRecord['id']) . "') LIMIT 1;";
						mysql_query($sql) or die('QUERY: ' . $sql . ', ERROR: ' . mysql_error());



						if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
						{
							header('Location: ' . $this->oRecord['transaction_success_url']);
							exit;
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid return request.</p>';
				}
			}

			GatewayCore::output($sHtml);
		}
	}

?>