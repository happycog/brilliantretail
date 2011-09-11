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
				$sHtml .= '<p>Invalid issuer request.</p>';
			}
			else
			{
				$sOrderId = $_GET['order_id'];
				$sOrderCode = $_GET['order_code'];


				// Lookup transaction
				if($this->__getRecordByOrder($sOrderId, $sOrderCode))
				{
					if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
					{
						$sHtml .= '<p>Transaction already completed</p>';
					}
					elseif((strcmp($this->oRecord['transaction_status'], 'OPEN') === 0) && !empty($this->oRecord['transaction_url']))
					{
						header('Location: ' . $this->oRecord['transaction_url']);
						exit;
					}
					else
					{
						// Load gateway settings
						$aSettings = gateway_getSettings();
					
						// Setup request
						$oBuckaroo = new BuckarooRequest();
						$oBuckaroo->setMerchantId($aSettings['MERCHANT_ID']);
						$oBuckaroo->setHashKey($aSettings['HASH_KEY']);
						$oBuckaroo->setTestMode($aSettings['TEST_MODE']);

						// Set URL's
						$oBuckaroo->setUrlCancel(GatewayCore::getRootUrl() . 'return.php?trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code']);
						$oBuckaroo->setUrlError(GatewayCore::getRootUrl() . 'return.php?trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code']);
						$oBuckaroo->setUrlSuccess(GatewayCore::getRootUrl() . 'return.php?trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code']);
						// $oBuckaroo->setUrlReport(GatewayCore::getRootUrl() . 'report.php?trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code']);

						// Set order details
						$oBuckaroo->setOrderId($this->oRecord['order_id']); // Order id
						$oBuckaroo->setOrderDescription($this->oRecord['transaction_description']); // Order description
						$oBuckaroo->setReferenceCode($this->oRecord['transaction_code']); // Random/unique reference code
						$oBuckaroo->setAmount($this->oRecord['transaction_amount']); // Order price

						// Customize submit button
						$oBuckaroo->setButton('Verder >>');

						$sHtml .= $oBuckaroo->createForm(substr($oRecord['transaction_method'], 0, -9)); // Param: Creditcard, Ideal, Transfer, Withdraw
						
						// Add auto-submit button
						if($this->aSettings['TEST_MODE'] == false)
						{
							$sHtml .= '<script type="text/javascript"> function doAutoSubmit() { document.forms[0].submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid issuer request.</p>';
				}
			}

			GatewayCore::output($sHtml, '<p><img alt="iDEAL" border="0" src="' . GatewayCore::getRootUrl() . 'gateways/buckaroo-ideal/logo.gif"></p>');
		}


		// Catch return
		public function doReturn()
		{
			$sHtml = '';

			if(empty($_GET['trxid']) || empty($_GET['ec']))
			{
				$sHtml .= '<p>Invalid return request.</p>';
			}
			else
			{
				$sTransactionId = $_GET['trxid'];
				$sTransactionCode = $_GET['ec'];

				// Lookup record
				if($this->__getRecordByTransaction($sTransactionId, $sTransactionCode))
				{
					if(strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
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
						// Setup BuckarooResponse
						$oBuckaroo = new BuckarooResponse();
						$oBuckaroo->setMerchantId($aSettings['MERCHANT_ID']);
						$oBuckaroo->setHashKey($aSettings['HASH_KEY']);
						$oBuckaroo->setTestMode($aSettings['TEST_MODE']);

						if($oBuckaroo->getResponse())
						{
							$this->oRecord['transaction_status'] = $oBuckaroo->getStatus();

							if(empty($this->oRecord['transaction_log']) == false)
							{
								$this->oRecord['transaction_log'] .= "\n\n";
							}

							$this->oRecord['transaction_log'] .= 'Executing StatusRequest on ' . date('Y-m-d, H:i:s') . ' for #' . $this->oRecord['transaction_id'] . '. Recieved: ' . $this->oRecord['transaction_status'] . "\n\n" . 'Buckaroo Payment ID: ' . $oBuckaroo->getPaymentId() . "\n" . 'Buckaroo Payment Type: ' . $oBuckaroo->getPaymentType() . "\n" . 'Buckaroo Transaction ID: ' . $oBuckaroo->getTransactionId() . "\n" . 'Buckaroo Status Code: ' . $oBuckaroo->getStatusCode();

							$this->save();



							// Handle status change
							if(function_exists('gateway_update_order_status'))
							{
								gateway_update_order_status($this->oRecord, 'doReturn');
							}


							
							// Set status message
							if(strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
							{
								$sHtml .= '<p>Uw betaling is met succes ontvangen.<br><input style="margin: 6px;" type="button" value="Terug naar de website" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl(1)) . '\'"></p>';
							}
							elseif((strcasecmp($this->oRecord['transaction_status'], 'OPEN') === 0) && !empty($this->oRecord['transaction_url']))
							{
								$sHtml .= '<p>Uw betaling is nog niet afgerond.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars($this->oRecord['transaction_url']) . '\'"></p>';
							}
							else
							{
								if(strcasecmp($this->oRecord['transaction_status'], 'CANCELLED') === 0)
								{
									$sHtml .= '<p>Uw betaling is geannuleerd. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl() . 'setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
								}
								elseif(strcasecmp($this->oRecord['transaction_status'], 'EXPIRED') === 0)
								{
									$sHtml .= '<p>Uw betaling is mislukt. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl() . 'setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
								}
								else // if(strcasecmp($this->oRecord['transaction_status'], 'FAILURE') === 0)
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


							if($this->oRecord['transaction_success_url'] && (strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0))
							{
								header('Location: ' . $this->oRecord['transaction_success_url']);
								exit;
							}
						}
						else
						{
							$sHtml .= '<p>Invalid return request.</p>';
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


		// Load record from table #transactions using order_id and order_code
		public function __getRecordByOrder($sOrderId, $sOrderCode)
		{
			$sql = "SELECT * FROM `" . DATABASE_PREFIX . "transactions` WHERE (`order_id` = '" . addslashes($sOrderId) . "') AND (`order_code` = '" . addslashes($sOrderCode) . "') AND (`transaction_method` LIKE '%-buckaroo') LIMIT 1;";
			$oRecordset = mysql_query($sql) or die('QUERY: ' . $sql . '<br><br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

			if(mysql_num_rows($oRecordset))
			{
				$this->oRecord = mysql_fetch_assoc($oRecordset);
			}
			else
			{
				$this->oRecord = false;
			}

			return $this->oRecord;
		}


		// Load record from table #transactions using transaction_id and transaction_code
		public function __getRecordByTransaction($sTransactionId, $sTransactionCode)
		{
			$sql = "SELECT * FROM `" . DATABASE_PREFIX . "transactions` WHERE (`transaction_id` = '" . addslashes($sTransactionId) . "') AND (`transaction_code` = '" . addslashes($sTransactionCode) . "') AND (`transaction_method` LIKE '%-buckaroo') LIMIT 1;";
			$oRecordset = mysql_query($sql) or die('QUERY: ' . $sql . '<br><br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

			if(mysql_num_rows($oRecordset))
			{
				$this->oRecord = mysql_fetch_assoc($oRecordset);
			}
			else
			{
				$this->oRecord = false;
			}

			return $this->oRecord;
		}
	}

?>