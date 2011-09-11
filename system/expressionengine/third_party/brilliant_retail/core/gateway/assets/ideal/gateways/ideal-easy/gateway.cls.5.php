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
						$oIdealEasy = new IdealEasy();

						// Set order details
						$oIdealEasy->setMerchant($this->aSettings['MERCHANT_ID']);
						$oIdealEasy->setAmount($this->oRecord['transaction_amount']); // Bedrag (in EURO's)
						$oIdealEasy->setOrderId($this->oRecord['order_id']); // Unieke order referentie (tot 16 karakters)
						$oIdealEasy->setOrderDescription($this->oRecord['transaction_description']); // Order omschrijving (tot 32 karakters)

						// Customize submit button
						$oIdealEasy->setButton('Verder >>');
						
						$sHtml .= $oIdealEasy->createForm();


						if($this->aSettings['TEST_MODE'] === false)
						{
							$sHtml .= '<script type="text/javascript"> function doAutoSubmit() { document.forms[checkout].submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
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
	}

?>