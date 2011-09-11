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
				if($this->getRecordByOrder($sOrderId, $sOrderCode))
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
						$oMollie = new iDEAL_Payment($this->aSettings['PARTNER_ID']);

						if(empty($this->aSettings['PROFILE_KEY']) == false)
						{
							$oMollie->setProfileKey($this->aSettings['PROFILE_KEY']);
						}

						$aIssuerList = $oMollie->getBanks();
						$sIssuerList = '';

						if($aIssuerList === false)
						{
							GatewayCore::output('<code>Er is een fout opgetreden bij het ophalen van de banklijst: ', $oMollie->getErrorMessage(), '</code>');
						}

						if(empty($this->oRecord['transaction_log']) == false)
						{
							$this->oRecord['transaction_log'] .= "\n\n";
						}

						$this->oRecord['transaction_log'] .= 'Executing IssuerRequest on ' . date('Y-m-d, H:i:s') . '.';

						$this->save();


						foreach($aIssuerList as $k => $v)
						{
							$sIssuerList .= '<option value="' . $k . '">' . htmlspecialchars($v) . '</option>';
						}

						$sHtml .= '
<form action="' . htmlspecialchars(GatewayCore::getRootUrl() . 'transaction.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '" method="post" id="checkout">
	<p><b>Kies uw bank</b><br><select name="issuer_id" style="margin: 6px; width: 200px;">' . $sIssuerList . '</select><br><input type="submit" value="Verder"></p>.
</form>';
					}
				}
				else
				{
					$sHtml .= '<p>Invalid issuer request.</p>';
				}
			}

			GatewayCore::output($sHtml);
		}


		// Execute payment
		public function doTransaction()
		{
			$sHtml = '';

			// Look for proper GET's en POST's
			if(empty($_POST['issuer_id']) || empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid transaction request.</p>';
			}
			else
			{
				$sIssuerId = $_POST['issuer_id'];
				$sOrderId = $_GET['order_id'];
				$sOrderCode = $_GET['order_code'];

				// Lookup transaction
				if($this->getRecordByOrder($sOrderId, $sOrderCode))
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
						$oMollie = new iDEAL_Payment($this->aSettings['PARTNER_ID']);

						if(empty($this->aSettings['PROFILE_KEY']) == false)
						{
							$oMollie->setProfileKey($this->aSettings['PROFILE_KEY']);
						}

						$oPayment = $oMollie->createPayment($sIssuerId, round($this->oRecord['transaction_amount'] * 100), $this->oRecord['transaction_description'], GatewayCore::getRootUrl() . 'return.php?order_id=' . htmlspecialchars($this->oRecord['order_id']) . '&order_code=' . htmlspecialchars($this->oRecord['order_code']), GatewayCore::getRootUrl() . 'report.php?order_id=' . htmlspecialchars($this->oRecord['order_id']) . '&order_code=' . htmlspecialchars($this->oRecord['order_code']));

						if($oPayment == false)
						{
							GatewayCore::output('<code>De betaling kon niet aangemaakt worden.<br><br><b>Foutmelding:</b> ', $oMollie->getErrorMessage() . '</code>');
						}

						$sTransactionId = $oMollie->getTransactionId();
						$sTransactionUrl = $oMollie->getBankURL();

						if(empty($this->oRecord['transaction_log']) == false)
						{
							$this->oRecord['transaction_log'] .= "\n\n";
						}

						$this->oRecord['transaction_log'] .= 'Executing TransactionRequest on ' . date('Y-m-d, H:i:s') . '. Recieved: ' . $sTransactionId;
						$this->oRecord['transaction_id'] = $sTransactionId;
						$this->oRecord['transaction_url'] = $sTransactionUrl;
						$this->oRecord['transaction_status'] = 'OPEN';
						$this->oRecord['transaction_date'] = time();
						
						$this->save();
						
						// die('<a href="' . htmlentities($sTransactionUrl) . '">' . htmlentities($sTransactionUrl) . '</a>');
						header('Location: ' . $sTransactionUrl);
						exit;
					}
				}
				else
				{
					$sHtml .= '<p>Invalid transaction request.</p>';
				}
			}

			GatewayCore::output($sHtml);
		}


		// Catch return
		public function doReturn()
		{
			$sHtml = '';

			if(empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid return request.</p>';
			}
			else
			{
				$sOrderId = $_GET['order_id'];
				$sOrderCode = $_GET['order_code'];

				// Lookup transaction
				if($this->getRecordByOrder($sOrderId, $sOrderCode))
				{
					// Transaction already finished
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

			GatewayCore::output($sHtml);
		}


		// Catch report
		public function doReport()
		{
			$sHtml = '';

			if(empty($_GET['transaction_id']) || empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid report request.</p>';
			}
			else
			{
				$sTransactionId = $_GET['transaction_id'];
				$sOrderId = $_GET['order_id'];
				$sOrderCode = $_GET['order_code'];

				// Lookup record
				if($this->getRecordByOrder($sOrderId, $sOrderCode))
				{
					// Check status
					$oMollie = new iDEAL_Payment($this->aSettings['PARTNER_ID']);

					if(empty($this->aSettings['PROFILE_KEY']) == false)
					{
						$oMollie->setProfileKey($this->aSettings['PROFILE_KEY']);
					}

					if($oMollie->checkPayment($sTransactionId))
					{
						if($oMollie->getPaidStatus() == true)
						{
							$this->oRecord['transaction_status'] = 'SUCCESS';
						}
						else
						{
							$this->oRecord['transaction_status'] = 'CANCELLED';
						}
					}
					else
					{
						$this->oRecord['transaction_status'] = 'FAILURE';
						// GatewayCore::output('<code>' . var_export($oMollie->getErrors(), true) . '</code>');
					}

					if(empty($this->oRecord['transaction_log']) == false)
					{
						$this->oRecord['transaction_log'] .= "\n\n";
					}

					$this->oRecord['transaction_log'] .= 'Executing StatusRequest on ' . date('Y-m-d, H:i:s') . ' for #' . $sTransactionId . '. Recieved: ' . $this->oRecord['transaction_status'];

					$this->save();


					// Handle status change
					if(function_exists('gateway_update_order_status'))
					{
						gateway_update_order_status($this->oRecord, 'doReport');
					}

					$sHtml .= '<p>De transactie status is bijgewerkt.</p>';
				}
				else
				{
					$sHtml .= '<p>Invalid report request.</p>';
				}
			}

			GatewayCore::output($sHtml);
		}


		// Validate all open transactions
		public function doValidate()
		{
			$sql = "SELECT * FROM `" . DATABASE_PREFIX . "transactions` WHERE (`transaction_status` = 'OPEN') AND (`transaction_method` = '" . addslashes($this->aSettings['GATEWAY_METHOD']) . "') ORDER BY `id` ASC;";
			$oRecordset = mysql_query($sql) or die('QUERY: ' . $sql . '<br><br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

			$sHtml = '<b>Controle van openstaande transacties.</b><br>';

			if(mysql_num_rows($oRecordset))
			{
				while($oRecord = mysql_fetch_assoc($oRecordset))
				{
					// Execute status request
					$oMollie = new iDEAL_Payment($this->aSettings['PARTNER_ID']);

					if(empty($this->aSettings['PROFILE_KEY']) == false)
					{
						$oMollie->setProfileKey($this->aSettings['PROFILE_KEY']);
					}

					if($oMollie->checkPayment($oRecord['transaction_id']))
					{
						if($oMollie->getPaidStatus() == true)
						{
							$oRecord['transaction_status'] = 'SUCCESS';
						}
						else
						{
							$oRecord['transaction_status'] = 'CANCELLED';
						}
					}
					else
					{
						$oRecord['transaction_status'] = 'FAILURE';
					}

					if(empty($oRecord['transaction_log']) == false)
					{
						$oRecord['transaction_log'] .= "\n\n";
					}

					$oRecord['transaction_log'] .= 'Executing StatusRequest on ' . date('Y-m-d, H:i:s') . ' for #' . $oRecord['transaction_id'] . '. Recieved: ' . $oRecord['transaction_status'];

					$this->save($oRecord);


					// Add to body
					$sHtml .= '<br>#' . $oRecord['transaction_id'] . ' : ' . $oRecord['transaction_status'];


					// Handle status change
					if(function_exists('gateway_update_order_status'))
					{
						gateway_update_order_status($oRecord, 'doValidate');
					}
				}

				$sHtml .= '<br><br><br>Alle openstaande transacties zijn bijgewerkt.';
			}
			else
			{
				$sHtml .= '<br>Er zijn geen openstaande transacties gevonden.';
			}

			GatewayCore::output('<p>' . $sHtml . '</p><p>&nbsp;</p><p><input type="button" value="Venster sluiten" onclick="javascript: window.close();"></p>');
		}
	}

?>