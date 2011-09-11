<?php

	/*
		Classes to use Buckaroo within your website.

		Author:  Martijn Wieringa
		Company: PHP Solutions
		Email:   info@php-solutions.nl
		Date:    6-10-2009
	*/

	class BuckarooRequest
	{
		protected $fAmount = 0.00;
		protected $sCurrency = 'EUR'; // Ideal only support payments in EURO.
		protected $sHashKey = '';
		protected $sLanguageCode = 'nl'; // NL, EN, DE, FR
		protected $sMerchantId = '';
		protected $sOrderDescription = '';
		protected $sOrderId = '';
		protected $sPaymentType = 'ideal';
		protected $sReferenceCode = '';
		protected $bTestMode = false;
		protected $sUrlCancel = '';
		protected $sUrlError = '';
		protected $sUrlSuccess = '';

		protected $sButtonLabel = 'Afrekenen';
		protected $sButtonImage = false;
		protected $iButtonImageWidth = 0;
		protected $iButtonImageHeight = 0;

		protected $CRLF = "\r\n";

		public function __construct()
		{
			if(defined('BUCKAROO_HASH_KEY'))
			{
				$this->setHashKey(BUCKAROO_HASH_KEY);
			}

			if(defined('BUCKAROO_MERCHANT_ID'))
			{
				$this->setMerchantId(BUCKAROO_MERCHANT_ID);
			}

			if(defined('BUCKAROO_TEST_MODE'))
			{
				$this->setTestMode(BUCKAROO_TEST_MODE ? true : false);
			}
		}

		// Set amount in EURO, use a float or integer 
		public function setAmount($fAmount)
		{
			$this->fAmount = $fAmount;
		}

		// Set currency (EUR, USD, etc)
		public function setCurrency($sCurrency)
		{
			$this->sCurrency = $sCurrency;
		}

		// Your secret hash key to secure form data (should match your Ideal Dashboard)
		public function setHashKey($sHashKey)
		{
			$this->sHashKey = $sHashKey;
		}

		// Set language code (NL, EN, DE, FR) 
		public function setLanguage($sLanguageCode)
		{
			$sLanguageCode = strtolower($sLanguageCode);

			if(in_array($sLanguageCode, array('de', 'en', 'fr', 'nl')))
			{
				$this->sLanguageCode = $sLanguageCode;
			}
		}

		// Your merchant ID
		public function setMerchantId($sMerchantId)
		{
			$this->sMerchantId = $sMerchantId;
		}

		// Upto 32 characters
		public function setOrderDescription($sOrderDescription)
		{
			$this->sOrderDescription = substr($sOrderDescription, 0, 32);
		}

		// Upto 16 characters, should be a unique reference to your order
		public function setOrderId($sOrderId)
		{
			$this->sOrderId = substr($sOrderId, 0, 16);
		}

		// Set payment type (ideal, etc)
		public function setPaymentType($sPaymentType)
		{
			$this->sPaymentType = $sPaymentType;
		}

		// Set referenceCode
		public function setReferenceCode($sReferenceCode)
		{
			$this->sReferenceCode = $sReferenceCode;
		}

		// Enable or disable test mode
		public function setTestMode($bTestMode = true)
		{
			$this->bTestMode = $bTestMode;
		}

		public function setUrlCancel($sUrl)
		{
			$this->sUrlCancel = $sUrl;
		}

		public function setUrlError($sUrl)
		{
			$this->sUrlError = $sUrl;
		}

		public function setUrlSuccess($sUrl)
		{
			$this->sUrlSuccess = $sUrl;
		}

		public function setButton($sLabel, $sImage = false, $iWidth = 0, $iHeight = 0)
		{
			$this->sButtonLabel = $sLabel;
			$this->sButtonImage = $sImage;
			$this->iButtonImageWidth = $iWidth;
			$this->iButtonImageHeight = $iHeight;
		}

		public function createForm($sPaymentType = false)
		{
			if($sPaymentType === false)
			{
				$sPaymentType = $this->sPaymentType;
			}

			$html = '';

			if(strcasecmp($sPaymentType, 'creditcard') === 0)
			{
				$sPostUrl = 'https://payment.buckaroo.nl/sslplus/request_for_authorization.asp';
			}
			elseif(strcasecmp($sPaymentType, 'transfer') === 0)
			{
				$sPostUrl = 'https://payment.buckaroo.nl/gateway/transfer.asp';
			}
			elseif(strcasecmp($sPaymentType, 'withdraw') === 0)
			{
				$sPostUrl = 'https://payment.buckaroo.nl/gateway/machtiging.asp';
			}
			else // if(strcasecmp($sPaymentType, 'ideal') === 0)
			{
				$sPaymentType = 'ideal';
				$sPostUrl = 'https://payment.buckaroo.nl/gateway/ideal_payment.asp';
			}

			// Parse amount to CENT
			$iAmount = round($this->fAmount * 100);

			// Setup hash string
			$sHashString = $this->sMerchantId . $this->sOrderId . $iAmount . $this->sCurrency . ($this->bTestMode ? '1' : '0') . $this->sHashKey;
			$sHash = md5($sHashString);

			// Generate HTML form
			$html = '<form action="' . $this->escapeHtml($sPostUrl) . '" method="post" id="buckaroo">' . $this->CRLF
			. '<input type="hidden" name="BPE_Merchant" value="' . $this->sMerchantId . '">' . $this->CRLF
			. '<input type="hidden" name="BPE_Amount" value="' . $iAmount . '" >' . $this->CRLF
			. '<input type="hidden" name="BPE_Currency" value="' . $this->escapeHtml($this->sCurrency) . '">' . $this->CRLF
			. '<input type="hidden" name="BPE_Invoice" value="' . $this->escapeHtml($this->sOrderId) . '">' . $this->CRLF
			. '<input type="hidden" name="BPE_Description" value="' . $this->escapeHtml($this->sOrderDescription) . '">' . $this->CRLF
			. '<input type="hidden" name="BPE_Language" value="' . $this->escapeHtml($this->sLanguageCode) . '">' . $this->CRLF
			. '<input type="hidden" name="BPE_Locale" value="' . $this->escapeHtml($this->sLanguageCode) . '-NL">' . $this->CRLF
			. '<input type="hidden" name="BPE_Timestamp" value="' . date('d-m-Y H:m:s') . '">' . $this->CRLF
			. '<input type="hidden" name="BPE_Mode" value="' . ($this->bTestMode ? '1' : '0') . '">' . $this->CRLF
			. ($this->sReferenceCode ? '<input type="hidden" name="BPE_Reference" value="' . $this->escapeHtml($this->sReferenceCode) . '">' . $this->CRLF : '')
			. '<input type="hidden" name="BPE_Return_Method" value="POST">' . $this->CRLF
			. '<input type="hidden" name="BPE_Signature2" value="' . $sHash . '">' . $this->CRLF
			. ($this->sUrlCancel ? '<input type="hidden" name="BPE_Return_Reject" value="' . $this->escapeHtml($this->sUrlCancel) . '">' . $this->CRLF : '')
			. ($this->sUrlSuccess ? '<input type="hidden" name="BPE_Return_Success" value="' . $this->escapeHtml($this->sUrlSuccess) . '">' . $this->CRLF : '')
			. ($this->sUrlError ? '<input type="hidden" name="BPE_Return_Error" value="' . $this->escapeHtml($this->sUrlError) . '">' . $this->CRLF : '')

			. '<input type="hidden" name="frm_paymenttype" value="' . $this->escapeHtml($sPaymentType) . '">' . $this->CRLF

			. ($this->sButtonImage ? '<input type="image" value="' . $this->escapeHtml($this->sButtonLabel) . '" src="' . $this->escapeHtml($this->sButtonImage) . '"' . ($this->iButtonImageWidth ? ' width="' . $this->escapeHtml($this->iButtonImageWidth) . '"' : '') . ($this->iButtonImageHeight ? ' height="' . $this->escapeHtml($this->iButtonImageHeight) . '"' : '') . '>' : '<input type="submit" value="' . $this->escapeHtml($this->sButtonLabel) . '">') . $this->CRLF
			. '</form>' . $this->CRLF;

			return $html;
		}

		protected function escapeHtml($s)
		{
			$s = str_replace('&', '&amp;', $s);
			$s = str_replace('<', '&lt;', $s);
			$s = str_replace('>', '&gt;', $s);
			$s = str_replace('"', '&quot;', $s);
			return $s;
		}
	}



	class BuckarooResponse
	{
		protected $aStatusDescriptions = array(
			'000' => 'De credit card transactie is pending.', '001' => 'De credit card transactie is pending. De MPI-status van de klant wordt gecheckt.', '070' => 'De refund is nog niet verwerkt.', '071' => 'De refund is succesvol verwerkt.', '072' => 'Er is een fout opgetreden bij het refunden.', 
			'100' => 'De transactie is door de credit-maatschappij goedgekeurd.', '101' => 'De transactie is door de credit-maatschappij afgekeurd.', '102' => 'De transactie is mislukt. Er is een fout opgetreden in de verwerking bij de creditmaatschappij.', '103' => 'Deze creditcardtransactie is niet binnen de maximale, toegestane tijd uitgevoerd.', '171' => 'De refund voor deze creditcardbetaling is verwerkt.', '172' => 'De refund voor deze creditcardbetaling is verwerkt.', '173' => 'De refund voor deze creditcardbetaling is verwerkt.', '181' => 'De refund voor deze creditcardbetaling is mislukt.', '182' => 'De refund voor deze creditcardbetaling is mislukt.', '183' => 'De refund voor deze creditcardbetaling is mislukt.', 
			'201' => 'Er is timeout opgetreden bij het verwerken van de transactie. Gebruik de TransactionKey om de verwerkingsstatus nogmaals te controleren.', '203' => 'De transactie is geweigerd. Het creditcardnummer is geblokkeerd.', '204' => 'De transactie is geweigerd. Het ip-adres is geblokkeerd', '205' => 'De transactie is geweigerd. Het land van uitgifte van deze creditcard is geblokkeerd', '206' => 'De transactie is geweigerd. De faktuur [waarde] wordt momenteel of is reeds betaald.', '207' => 'De transactie is geweigerd. Het maximaal aantal betaalpogingen voor faktuur [waarde] is overschreden.', 
			'300' => 'Betaling voor deze overschrijving wordt nog verwacht.', '301' => 'De overschrijving is ontvangen.', '302' => 'De transactie is geweigerd of afgewezen.', '303' => 'De uiterste betaaldatum voor deze overschrijving is verstreken.', '304' => 'De datum voor ingebrekestelling is verstreken.', '305' => 'Het ontvangen bedrag voor de overschrijving is lager dan het bedrag van de transactie.', '306' => 'Het ontvangen bedrag voor de overschrijving is groter dan het bedrag van de transactie.', '309' => 'De overschrijving is geannuleerd.', '371' => 'De refund voor deze overschrijving is verwerkt.', '372' => 'De refund voor deze overschrijving is verwerkt.', '373' => 'De refund voor deze overschrijving is verwerkt.', '381' => 'De refund voor deze overschrijving is mislukt.', '382' => 'De refund voor deze overschrijving is mislukt.', '383' => 'De refund voor deze overschrijving is mislukt.', '390' => 'De transactie is buiten Buckaroo om met de klant afgehandeld.', 
			'400' => 'De kadokaart-transactie is nog in behandeling', '401' => 'De betaling middels kado-kaart is geslaagd.', '402' => 'Betaling middels de kadokaart is afgewezen.', '409' => 'Betaling middels de kadokaart is geannuleerd.', '410' => 'De Merchant Account Code is ongeldig', '411' => 'De betaling middels kad-kaart is voorlopig geaccepteerd.', '414' => 'Er is een systeem-fout opgetreden.', '421' => 'Er is een onbekende Issuer voor de kado-kaart opgegeven.', '422' => 'Er is een fout opgetreden bij de Issuer. De betaling is mislukt. [waarde].', '425' => 'Niet genoeg saldo om deze transactie uit te voeren.', '471' => 'De refund voor deze giftcardbetaling is verwerkt.', '472' => 'De refund voor deze giftcardbetaling is mislukt.', 
			'500' => 'Paypermail: transactie pending', '560' => 'Correctiebetaling uitgevoerd door Buckaroo.', '581' => 'Onvangst door overschrijving van ander Buck-account', 
			'600' => 'Eenmalige machtiging is nog niet verwerkt.', '601' => 'Eenmalige machtiging is met succes verwerkt.', '602' => 'Eenmalige machtiging is door de bank afgewezen.', '605' => 'Eenmalige machtiging is gestorneerd.', '609' => 'Eenmalige machtiging is geannuleerd voordat incasso plaatsvond.', '610' => 'Eenmalige machtiging is door de bank afgewezen. Rekening ongeldig.', '612' => 'Terugboeking wegens Melding Onterechte Incasso', '671' => 'De refund voor deze machtiging is verwerkt.', '672' => 'De refund voor deze machtiging is mislukt.', 
			'700' => 'De betaalopdracht is geaccepteerd en wordt in behandeling genomen.', '701' => 'De betaalopdracht is verwerkt.', '702' => 'De betaalopdracht is afgewezen.', '705' => 'De batch kon niet worden ingepland. Error: [waarde]', '710' => 'Betaalopdracht nog niet geverifieerd.', '711' => 'De batch kon niet gevonden worden: [waarde].', '712' => 'De batch is reeds verwerkt: [waarde].', '720' => 'Er is voor deze batch-transactie geen klant-id opgegeven.', '721' => 'Het opgegeven klant-id kon niet worden gevonden: [waarde].', 
			'800' => 'Deze iDeal-transactie is nog niet volledig verwerkt.', '801' => 'Deze iDeal-transactie is met succes verwerkt.', '802' => 'Deze iDeal-transactie is door de consument geannuleerd. Trx: [waarde]', '803' => 'Deze iDeal-transactie is niet binnen de maximale, toegestane tijd uitgevoerd. Trx: [waarde]', '804' => 'Deze iDeal-transactie is om onbekende reden bij de bank mislukt. Trx: [waarde]', '810' => 'Issuer (bank) is onbekend: [waarde]', '811' => 'Om technische reden kon de status van deze transactie nog niet bij de bank worden achterhaald. De transactie is nog niet afgerond.', '812' => 'De entrance-code [waarde] is ongeldig.', '813' => 'Acquirer-code is onbekend: [waarde].', '814' => 'Er is een systeemfout opgetreden. We zullen deze zo snel mogelijk verhelpen. De status zal daarna worden herzien.', '815' => 'Het iDeal transactie-id is ongeldig of niet beschikbaar.', '816' => 'Er kon geen transactie worden gevonden. Criteria: [waarde]', '820' => 'Deze Giropay-transactie is nog niet volledig verwerkt.', '821' => 'Deze Giropay-transactie is met succes verwerkt.', '822' => 'Deze Giropay-transactie is door de consument geannuleerd. Trx: [waarde]', '823' => 'Deze Giropay-transactie is niet binnen de maximale, toegestane tijd uitgevoerd. Trx: [waarde]', '824' => 'Deze Giropay-transactie is door de bank afgewezen.', '830' => 'Issuer (bankleitzahl) is onbekend: [waarde]', '831' => 'Om technische reden kon de status van deze transactie nog niet bij de bank worden achterhaald. De transactie is nog niet afgerond.', '833' => 'De entrance-code [waarde] is ongeldig.', '834' => 'Er is een systeemfout opgetreden. We zullen deze zo snel mogelijk verhelpen. De status zal daarna worden herzien.', '835' => 'Het Giropay transactie-id is ongeldig of niet beschikbaar.', '836' => 'Er kon geen transactie worden gevonden. Criteria: [waarde]', '871' => 'De refund voor deze iDeal-cardbetaling is verwerkt.', '872' => 'De refund voor deze iDeal-cardbetaling is mislukt.', '873' => 'De refund voor deze GiroPay-cardbetaling is verwerkt.', '874' => 'De refund voor deze GiroPay-betaling is mislukt.', 
			'900' => 'Geen XML-bericht ontvangen.', '901' => 'Ongeldig XML-bericht. [waarde]', '910' => '0 EUR transactie, Customergegevens opgeslagen.', '931' => '[nodetype] [element] ontbreekt.', '932' => 'Teveel elementen type [element] (max. 1).', '933' => 'Waarde [nodetype] [element] ontbreekt.', '934' => 'Waarde [nodetype] [element] (occurance [occurance]) ontbreekt.', '935' => 'Waarde attribuut [attribuut] ontbreekt in element [element].', '940' => 'Ongeldig request: [waarde].', '941' => 'Waarde veld [veld] ongeldig: [waarde].', '942' => 'Waarde attribuut [veld] ongeldig: [waarde].', '943' => 'Creditcard-type onbekend: [waarde]. (mastercard of visa)', '944' => 'Kaartnummer ongeldig (Luhn-check): [waarde].', '945' => 'Valuta onbekend ongeldig: [waarde].', '946' => 'Bedrag is geen numerieke waarde: [waarde].', '947' => 'Bedrag ongeldig: [waarde].', '948' => 'CVC-code ongeldig: [waarde].', '949' => 'Maand geldigheidsduur creditcard ongeldig: [waarde].', '950' => 'Jaar geldigheidsduur creditcard ongeldig: [waarde].', '951' => 'Taal onbekend of niet ondersteund: [waarde].', '952' => 'Het factuurnummer ontbreekt. Dit veld is verplicht.', '953' => 'Geblokkeerd door velocitycheck', '954' => 'Het transactie-ID [waarde] is al in gebruik.', '955' => 'Authenticatie voor deze creditcard betaling is afgewezen', '960' => 'Klantnummer ongeldig: [waarde].', '961' => 'Creditcard-type niet geactiveerd: [waarde].', '962' => 'Gekozen valuta ongeldig voor Merchant: [waarde].', '963' => 'Het transactie-id is ongeldig: [waarde]', '978' => 'De XML koppeling voor creditcards is nog niet geactiveerd.', '980' => 'De betaalmethode [waarde] is niet geactiveerd.', '990' => 'De digitale handtekening is incorrect: [waarde].', '991' => 'Er is een fout opgetreden bij het verwerken van de transactie. De Merchant Account Code kon niet worden gelocaliseerd.', '992' => 'Er is fout opgetreden bij het verwerken van de response. We zullen de storing zo snel mogelijk verhelpen.', '993' => 'Er is een fout opgetreden bij het verwerken van de transactie. We zullen de storing zo snel mogelijk verhelpen.', '999' => 'Er is een fout opgetreden waarvan de oorzaak vooralsnog onbekend is. We zullen de storing zo snel mogelijk verhelpen.'
		);

		protected $sHashKey = '';
		protected $sMerchantId = '';
		protected $sPaymentType = 'ideal';

		protected $sTransactionId = '';
		protected $sOrderId = '';
		protected $sReferenceCode = '';
		protected $sStatusCode = '';
		protected $sStatusDescription = '';
		protected $sPaymentId = '';

		public function __construct()
		{
			if(defined('BUCKAROO_HASH_KEY'))
			{
				$this->setHashKey(BUCKAROO_HASH_KEY);
			}

			if(defined('BUCKAROO_MERCHANT_ID'))
			{
				$this->setMerchantId(BUCKAROO_MERCHANT_ID);
			}
		}

		// Your secret hash key to secure form data (should match your Ideal Dashboard)
		public function setHashKey($sHashKey)
		{
			$this->sHashKey = $sHashKey;
		}

		// Your merchant ID
		public function setMerchantId($sMerchantId)
		{
			$this->sMerchantId = $sMerchantId;
		}

		// Set payment type (ideal, etc)
		public function setPaymentType($sPaymentType)
		{
			$this->sPaymentType = $sPaymentType;
		}

		public function getTransactionId()
		{
			return $this->sTransactionId;
		}

		public function getOrderId()
		{
			return $this->sOrderId;
		}

		public function getPaymentId()
		{
			return $this->sPaymentId;
		}

		public function getPaymentType()
		{
			return $this->sPaymentType;
		}

		public function getReferenceCode()
		{
			return $this->sReferenceCode;
		}

		public function getStatusCode()
		{
			return $this->sStatusCode;
		}

		public function getStatusDescription($sStatusCode = false)
		{
			if($sStatusCode === false)
			{
				$sStatusCode = $this->sStatusCode;
			}
			
			return (isset($this->aStatusDescriptions[$sStatusCode]) ? $this->aStatusDescriptions[$sStatusCode] : '');
		}

		// Get the status of the transaction, returns SUCCESS, PENDING or FAILURE
		public function getStatus($sStatusCode = false)
		{
			if($sStatusCode === false)
			{
				$sStatusCode = $this->sStatusCode;
			}

			if(in_array($sStatusCode, array('100', '301', '390', '401', '581', '601', '701', '801', '821')))
			{
				return 'SUCCESS'; // Payment completed
			}
			elseif(in_array($sStatusCode, array('000', '001', '070', '071', '100', '300', '400', '411', '500', '560', '600', '700', '710', '712', '800', '811', '820', '822', '823', '831')))
			{
				return 'PENDING'; // Payment in progress
			}
			else
			{
				return 'FAILURE'; // Payment FAILURE
			}
		}

		public function getResponse()
		{
			$bResponseValid = false;

			if(isset($_POST['bpe_signature2']) && isset($_POST['bpe_trx']) && isset($_POST['bpe_timestamp']) && isset($_POST['bpe_invoice']) && isset($_POST['bpe_reference']) && isset($_POST['bpe_currency']) && isset($_POST['bpe_amount']) && isset($_POST['bpe_result']) && isset($_POST['bpe_mode']))
			{
				$sHashString = $_POST['bpe_trx'] . $_POST['bpe_timestamp'] . $this->sMerchantId . $_POST['bpe_invoice'] . $_POST['bpe_reference'] . $_POST['bpe_currency'] . $_POST['bpe_amount'] . $_POST['bpe_result'] . $_POST['bpe_mode'] . $this->sHashKey;
				$sHash = md5($sHashString);

				if(strcmp($sHash, $_POST['bpe_signature2']) === 0)
				{
					$bResponseValid = true;

					$this->sTransactionId = $_POST['bpe_trx'];
					$this->sOrderId = $_POST['bpe_invoice'];
					$this->sReferenceCode = $_POST['bpe_reference'];
					$this->sStatusCode = $_POST['bpe_result'];

					if(isset($_POST['bpe_paymentid'])) // Beschikbaar bij 'overboeking'
					{
						$this->sPaymentId = $_POST['bpe_paymentid'];
					}

					if(isset($_POST['frm_paymenttype']))
					{
						$this->sPaymentType = $_POST['frm_paymenttype'];
					}
				}
			}

			return $bResponseValid;
		}
	}

?>