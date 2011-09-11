<?php

	/*
		Class to generate an Ideal Easy form. 

		Author:  Martijn Wieringa
		Company: PHP Solutions
		Email:   info@php-solutions.nl
		Version: 0.3
		Date:    1-8-2009
	*/

	class IdealEasy
	{
		// Default settings
		protected $sCurrency = 'EUR'; // Ideal only support payments in EURO.
		protected $sLanguageCode = 'NL_NL'; // NL_NL
		protected $sPaymentType = 'iDEAL';


		// Account settings
		protected $sMerchantId = '';
		protected $sUrlAquirer = 'https://internetkassa.abnamro.nl/ncol/prod/orderstandard.asp';


		// Order settings
		protected $fOrderAmount = 0.00;
		protected $sOrderId = '';
		protected $sOrderDescription = '';


		// Customer settings - probably used to autofill creditcard data (available for iDEAL Kassa), but ignored for iDEAL Easy
		protected $sCustomerName = '';
		protected $sCustomerEmail = '';
		protected $sCustomerAddress = '';
		protected $sCustomerCity = '';
		protected $sCustomerZip = '';


		// Form settings
		protected $sButtonLabel = 'Betalen met iDEAL';
		protected $sButtonImage = false;
		protected $iButtonImageWidth = 0;
		protected $iButtonImageHeight = 0;


		function __construct()
		{
			if(defined('IDEAL_MERCHANT_ID'))
			{
				$this->setMerchant(IDEAL_MERCHANT_ID);
			}
		}

		// Set amount in EURO, use a float or integer 
		public function setAmount($fOrderAmount)
		{
			$this->fOrderAmount = $fOrderAmount;
		}

		// Your merchantID and subID
		public function setMerchant($sMerchantId)
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

		// Set submit button label, or define an image as submit-button
		public function setButton($sLabel, $sImage = false, $iWidth = 0, $iHeight = 0)
		{
			$this->sButtonLabel = $sLabel;
			$this->sButtonImage = $sImage;
			$this->iButtonImageWidth = $iWidth;
			$this->iButtonImageHeight = $iHeight;
		}

		// Generate iDEAL Easy form
		public function createForm()
		{
			$iAmount = round($this->fOrderAmount * 100);

			// Generate HTML form
			$HTTP_REFERER = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))) . '://' . $_SERVER['HTTP_HOST'] . '/';

			$html = '<form action="' . $this->escapeHtml($this->sUrlAquirer) . '" method="post">'
			. '<input name="HTTP_REFERER" type="hidden" value="' . $this->escapeHtml($HTTP_REFERER) . '">'
			. '<input type="hidden" name="PSPID" value="' . $this->sMerchantId . '">'
			. '<input type="hidden" name="orderID" value="' . $this->escapeHtml($this->sOrderId) . '">'
			. '<input type="hidden" name="amount" value="' . $iAmount . '">'
			. '<input type="hidden" name="currency" value="' . $this->escapeHtml($this->sCurrency) . '">'
			. '<input type="hidden" name="language" value="' . $this->escapeHtml($this->sLanguageCode) . '">'
			. '<input type="hidden" name="COM" value="' . $this->escapeHtml($this->sOrderDescription) . '">'
			. '<input type="hidden" name="PM" value="' . $this->escapeHtml($this->sPaymentType) . '">'
			. '<input type="hidden" name="CN" value="' . $this->escapeHtml($this->sCustomerName) . '">' // Customer Name, optional
			. '<input type="hidden" name="EMAIL" value="' . $this->escapeHtml($this->sCustomerEmail) . '">' // Customer Email, optional
			. '<input type="hidden" name="owneraddress" value="' . $this->escapeHtml($this->sCustomerAddress) . '">' // Customer Address, optional
			. '<input type="hidden" name="ownertown" value="' . $this->escapeHtml($this->sCustomerCity) . '">' // Customer City, optional
			. '<input type="hidden" name="ownerzip" value="' . $this->escapeHtml($this->sCustomerZip) . '">' // Customer Postalcode, optional
			. ($this->sButtonImage ? '<input type="image" value="' . $this->escapeHtml($this->sButtonLabel) . '" src="' . $this->escapeHtml($this->sButtonImage) . '"' . ($this->iButtonImageWidth ? ' width="' . $this->escapeHtml($this->iButtonImageWidth) . '"' : '') . ($this->iButtonImageHeight ? ' height="' . $this->escapeHtml($this->iButtonImageHeight) . '"' : '') . '>' : '<input type="submit" value="' . $this->escapeHtml($this->sButtonLabel) . '">')
			. '</form>';

			return $html;
		}

		protected function escapeHtml($string)
		{
			return str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
		}
	}

?>