<?php

require_once('ideal.class.php');

$partner_id  = 000000; // Uw mollie partner ID

if (isset($_GET['transaction_id'])) 
{  
	$iDEAL = new iDEAL_Payment ($partner_id);
	// $iDEAL->setTestMode();
	
	$iDEAL->checkPayment($_GET['transaction_id']);

	if ($iDEAL->getPaidStatus() == true) 
	{
		/* De betaling is betaald, deze informatie kan opgeslagen worden (bijv. in de database).
	   	   Met behulp van $iDEAL->getConsumerInfo(); kunt u de consument gegevens ophalen (de 
		   functie returned een array). Met behulp van $iDEAL->getAmount(); kunt u het betaalde
		   bedrag vergelijken met het bedrag dat afgerekend zou moeten worden. */
	} 
}
