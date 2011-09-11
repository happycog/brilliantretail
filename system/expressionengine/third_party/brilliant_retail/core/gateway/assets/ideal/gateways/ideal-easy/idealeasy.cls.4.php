<?php

	/*
		Class to generate an Ideal Lite form. 
		Also calculates your security hashcode.

		Author:  Martijn Wieringa
		Company: PHP Solutions
		Email:   info@php-solutions.nl
		Version: 0.3
		Date:    10-07-2009
	*/

	class IdealEasy
	{
		// Default settings
		var $sCurrency = 'EUR'; // Ideal only support payments in EURO.
		var $sLanguageCode = 'NL_NL'; // NL
		var $sPaymentType = 'iDEAL';


		// Account settings
		var $sMerchantId = '';
		var $sUrlAquirer = 'https://internetkassa.abnamro.nl/ncol/prod/orderstandard.asp';


		// Order settings
		var $fOrderAmount = 0.00;
		var $sOrderId = '';
		var $sOrderDescription = '';


		// Customer settings - probably used to autofill creditcard data (available for iDEAL Kassa), but ignored for iDEAL Easy
		var $sCustomerName = '';
		var $sCustomerEmail = '';
		var $sCustomerAddress = '';
		var $sCustomerCity = '';
		var $sCustomerZip = '';


		// Form settings
		var $sButtonLabel = 'Betalen met iDEAL';
		var $sButtonImage = false;
		var $iButtonImageWidth = 0;
		var $iButtonImageHeight = 0;


		function IdealEasy()
		{
			if(defined('IDEAL_MERCHANT_ID'))
			{
				$this->setMerchant(IDEAL_MERCHANT_ID);
			}
		}

		// Set amount in EURO, use a float or integer 
		function setAmount($fOrderAmount)
		{
			$this->fOrderAmount = $fOrderAmount;
		}

		// Your merchantID and subID
		function setMerchant($sMerchantId)
		{
			$this->sMerchantId = $sMerchantId;
		}

		// Upto 32 characters
		function setOrderDescription($sOrderDescription)
		{
			$this->sOrderDescription = substr($sOrderDescription, 0, 32);
		}

		// Upto 16 characters, should be a unique reference to your order
		function setOrderId($sOrderId)
		{
			$this->sOrderId = substr($sOrderId, 0, 16);
		}

		// Set submit button label, or define an image as submit-button
		function setButton($sLabel, $sImage = false, $iWidth = 0, $iHeight = 0)
		{
			$this->sButtonLabel = $sLabel;
			$this->sButtonImage = $sImage;
			$this->iButtonImageWidth = $iWidth;
			$this->iButtonImageHeight = $iHeight;
		}

		// Generate iDEAL Easy form
		function createForm()
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

		function escapeHtml($string)
		{
			return str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
		}
	}

?>