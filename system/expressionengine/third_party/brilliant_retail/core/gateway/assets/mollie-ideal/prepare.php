<?php

require_once('ideal.class.php');

$partner_id  = '00000'; // Uw mollie partner ID
$amount      = 118;    // Het af te rekenen bedrag in centen (!!!)
$description = 'Testbetaling'; // Beschrijving die consument op zijn/haar afschrift ziet.

$return_url  = 'http://www.domein.nl/return.php'; // URL waarnaar de consument teruggestuurd wordt na de betaling
$report_url  = 'http://www.domein.nl/report.php'; // URL die Mollie aanvraagt (op de achtergrond) na de betaling om de status naar op te sturen

if (!in_array('ssl', stream_get_transports()))
{
	echo "<h1>Foutmelding</h1>";
	echo "<p>Uw PHP installatie heeft geen SSL ondersteuning. SSL is nodig voor de communicatie met de Mollie iDEAL API.</p>";
	exit;	
}

$iDEAL = new iDEAL_Payment ($partner_id);
//$iDEAL->setTestMode();

if (isset($_POST['bank_id']) and !empty($_POST['bank_id'])) 
{
	if ($iDEAL->createPayment($_POST['bank_id'], $amount, $description, $return_url, $report_url)) 
	{
		/* Hier kunt u de aangemaakte betaling opslaan in uw database, bijv. met het unieke transactie_id
		   Het transactie_id kunt u aanvragen door $iDEAL->getTransactionId() te gebruiken. Hierna wordt 
		   de consument automatisch doorgestuurd naar de gekozen bank. */
		
		header("Location: " . $iDEAL->getBankURL());
		exit;	
	}
	else 
	{
		/* Er is iets mis gegaan bij het aanmaken bij de betaling. U kunt meer informatie 
		   vinden over waarom het mis is gegaan door $iDEAL->getErrorMessage() en/of 
		   $iDEAL->getErrorCode() te gebruiken. */
		
		echo '<p>De betaling kon niet aangemaakt worden.</p>';
		
		echo '<p><strong>Foutmelding:</strong> ', $iDEAL->getErrorMessage(), '</p>';
		exit;
	}
}


/*
  Hier worden de mogelijke banken opgehaald en getoont aan de consument.
*/

$bank_array = $iDEAL->getBanks();

if ($bank_array == false)
{
	echo '<p>Er is een fout opgetreden bij het ophalen van de banklijst: ', $iDEAL->getErrorMessage(), '</p>';
	exit;
}

?>
<form method="post">
	<select name="bank_id">
		<option value=''>Kies uw bank</option>
		
<?php foreach ($bank_array as $bank_id => $bank_name) { ?>
		<option value="<?php echo $bank_id ?>"><?php echo $bank_name ?></option>
<?php } ?>

	</select>
	<input type="submit" name="submit" value="Betaal via iDEAL" />
</form>