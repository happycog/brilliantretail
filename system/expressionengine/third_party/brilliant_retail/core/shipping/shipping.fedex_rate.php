<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright © 2010-2012						*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/*															*/
/************************************************************/
/* NOTICE													*/
/*															*/
/* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF 	*/
/* ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED	*/
/* TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 		*/
/* PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT 		*/
/* SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY */
/* CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION	*/
/* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR 	*/
/* IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 		*/
/* DEALINGS IN THE SOFTWARE. 								*/	
/************************************************************/
require_once('assets/fedex/library/fedex-common.php');

ini_set("soap.wsdl_cache_enabled", "0");

class Shipping_fedex_rate extends Brilliant_retail_shipping {
	public $title 	= 'FedEx Rate Service';
	public $label  	= 'FedEx Rates';
	public $descr 	= 'FedEx Shipping';
	public $enabled = true;
	public $version = '2.0';
	
	
	public function quote()
	{
		$wsdl = PATH_THIRD."brilliant_retail/core/shipping/assets/fedex/wsdl/RateService_v13.wsdl";
		$client = new SoapClient($wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

		$request['WebAuthenticationDetail'] = array(
			'UserCredential' => array(
										'Key' 		=> '2Upc9i5T4JIswKQU', 
										'Password' 	=> 'mbGbyf91U49bkj8dKG4vwyPSR'
									 )
								); 
		$request['ClientDetail'] = array(
											'AccountNumber' => '510087364', 
											'MeterNumber' 	=> '118517847'
		);
		
		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v13 using PHP ***');
		$request['Version'] = array(
			'ServiceId' => 'crs', 
			'Major' => '13', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		$request['RequestedShipment']['ServiceType'] = 'INTERNATIONAL_PRIORITY'; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
		$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
		$request['RequestedShipment']['TotalInsuredValue']=array('Ammount'=>100,'Currency'=>'USD');
		$request['RequestedShipment']['Shipper'] = $this->addShipper();
		$request['RequestedShipment']['Recipient'] = $this->addRecipient();
		$request['RequestedShipment']['ShippingChargesPayment'] = $this->addShippingChargesPayment();
		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
		$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
		$request['RequestedShipment']['PackageCount'] = '1';
		$request['RequestedShipment']['RequestedPackageLineItems'] = $this->addPackageLineItem1();
		try 
		{
			if(setEndpoint('changeEndpoint'))
			{
				$newLocation = $client->__setLocation(setEndpoint('endpoint'));
			}
			
			$response = $client ->getRates($request);
		        
		    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
		    {  	
		    	$rateReply = $response -> RateReplyDetails;
		    	echo '<table border="1">';
		        echo '<tr><td>Service Type</td><td>Amount</td><td>Delivery Date</td></tr><tr>';
		    	$serviceType = '<td>'.$rateReply -> ServiceType . '</td>';
		        $amount = '<td>$' . number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",") . '</td>';
		        if(array_key_exists('DeliveryTimestamp',$rateReply)){
		        	$deliveryDate= '<td>' . $rateReply->DeliveryTimestamp . '</td>';
		        }else if(array_key_exists('TransitTime',$rateReply)){
		        	$deliveryDate= '<td>' . $rateReply->TransitTime . '</td>';
		        }else {
		        	$deliveryDate='<td>&nbsp;</td>';
		        }
		        echo $serviceType . $amount. $deliveryDate;
		        echo '</tr>';
		        echo '</table>';
		        
		        printSuccess($client, $response);
		    }
		    else
		    {
		        printError($client, $response);
		    } 
		    
		    writeToLog($client);    // Write to log file   
		
		} catch (SoapFault $exception) {
		   printFault($exception, $client);        
		}

	}	
	
	public function install($config_id)
	{
		return true;
	}
	


	public function addShipper(){
		$shipper = array(
			'Contact' => array(
				'PersonName' => 'Sender Name',
				'CompanyName' => 'Sender Company Name',
				'PhoneNumber' => '9012638716'),
			'Address' => array(
				'StreetLines' => array('Address Line 1'),
				'City' => 'Collierville',
				'StateOrProvinceCode' => 'TN',
				'PostalCode' => '38017',
				'CountryCode' => 'US')
		);
		return $shipper;
	}

	public function addRecipient(){
		$recipient = array(
			'Contact' => array(
				'PersonName' => 'Recipient Name',
				'CompanyName' => 'Company Name',
				'PhoneNumber' => '9012637906'
			),
			'Address' => array(
				'StreetLines' => array('Address Line 1'),
				'City' => 'Richmond',
				'StateOrProvinceCode' => 'BC',
				'PostalCode' => 'V7C4V4',
				'CountryCode' => 'CA',
				'Residential' => false)
		);
		return $recipient;	                                    
	}

	public function addShippingChargesPayment(){
		$shippingChargesPayment = array(
			'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
			'Payor' => array(
				'ResponsibleParty' => array(
				'AccountNumber' => '510087364',
				'CountryCode' => 'US')
			)
		);
		return $shippingChargesPayment;
	}

	public function addLabelSpecification(){
		$labelSpecification = array(
			'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
			'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
			'LabelStockType' => 'PAPER_7X4.75');
		return $labelSpecification;
	}

	public function addSpecialServices(){
		$specialServices = array(
			'SpecialServiceTypes' => array('COD'),
			'CodDetail' => array(
				'CodCollectionAmount' => array('Currency' => 'USD', 'Amount' => 150),
				'CollectionType' => 'ANY')// ANY, GUARANTEED_FUNDS
		);
		return $specialServices; 
	}

	public function addPackageLineItem1(){
		$packageLineItem = array(
			'SequenceNumber'=>1,
			'GroupPackageCount'=>1,
			'Weight' => array(
				'Value' => 50.0,
				'Units' => 'LB'
			),
			'Dimensions' => array(
				'Length' => 108,
				'Width' => 5,
				'Height' => 5,
				'Units' => 'IN'
			)
		);
		return $packageLineItem;
	}
}