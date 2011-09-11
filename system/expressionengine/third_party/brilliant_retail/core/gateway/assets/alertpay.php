<?php
abstract class ap_PaymentGateway
{

	 /**
     * Adds a key=>value pair to the fields array
     *
     * @param string key of field
     * @param string value of field
     * @return
     */
    public function addField($field, $value)
    {
        $this->fields["$field"] = $value;
    }
    
    public $fields = array();
    
    public $gatewayUrl;

}


class AlertPay extends ap_PaymentGateway
{

	public function __construct()
	{
        // Some default values of the class
		$this->gatewayUrl = 'https://www.alertpay.com/PayProcess.aspx';
		$this->ipnV2Url = "https://www.alertpay.com/ipn2.ashx";
	}
	
	public function RunPayment(){
	
	 echo "<html>\n";
     echo "<head><title>Processing AlertPay...</title></head>\n";
     //echo "<body>";
     echo "<body onLoad=\"document.forms['gateway_form'].submit();\">\n";
     echo "<form method=\"POST\" name=\"gateway_form\" ";
     echo "action=\"" . $this->gatewayUrl . "\">\n";

     foreach ($this->fields as $name => $value)
     {
          echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
     }


     echo "	<p style=\"text-align:center;\">Your order is being processed... will be redirected to the payment website.
     		<br/><br/>If you are not automatically redirected to payment website within 5 seconds...<br/><br/>
     		<input type=\"submit\" value=\"Click Here\"></p>
     		</form>
     		</body>
     	</html>";
	
	}
	
	public function callToken($token,$securitycode)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->ipnV2Url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "token=".$token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		$response = curl_exec($ch);
		
		curl_close($ch);
		
		if(strlen($response) > 0)
		{
			if(urldecode($response) == "INVALID TOKEN")
			{
				//the token is not valid
			}
			else
			{
				//urldecode the received response from Alertpay's IPN V2
				$response = urldecode($response);
				
				//split the response string by the delimeter "&"
				$aps = explode("&", $response);
					
				//define an array to put the IPN information
				$info = array();
				$str='';
				
				foreach ($aps as $ap)
				{
					//put the IPN information into an associative array $info
					$ele = explode("=", $ap);
					$info[$ele[0]] = $ele[1];
					
					$str.= "$ele[0] \t";
					$str.= "=\t";
					$str.= "$ele[1]\r\n";
				}
				
				//#file_put_contents(APPPATH.'cache/brilliant_retail/alertpay_ipn_'.time().'.txt', $str);
				
				//setting information about the transaction from the IPN information array
				//$receivedMerchantEmailAddress = $info['ap_merchant'];
				//$transactionStatus = $info['ap_status'];
				//$testModeStatus = $info['ap_test'];
				//$purchaseType = $info['ap_purchasetype'];
				//$totalAmountReceived = $info['ap_totalamount'];
				//$feeAmount = $info['ap_feeamount'];
				//$netAmount = $info['ap_netamount'];
				//$transactionReferenceNumber = $info['ap_referencenumber'];
				//$currency = $info['ap_currency'];
				//$transactionDate = $info['ap_transactiondate'];
				//$transactionType = $info['ap_transactiontype'];
				//
				////setting the customer's information from the IPN information array
				//$customerFirstName = $info['ap_custfirstname'];
				//$customerLastName = $info['ap_custlastname'];
				//$customerAddress = $info['ap_custaddress'];
				//$customerCity = $info['ap_custcity'];
				//$customerState = $info['ap_custstate'];
				//$customerCountry = $info['ap_custcountry'];
				//$customerZipCode = $info['ap_custzip'];
				//$customerEmailAddress = $info['ap_custemailaddress'];
				//
				////setting information about the purchased item from the IPN information array
				//$myItemName = $info['ap_itemname'];
				//$myItemCode = $info['ap_itemcode'];
				//$myItemDescription = $info['ap_description'];
				//$myItemQuantity = $info['ap_quantity'];
				//$myItemAmount = $info['ap_amount'];
				//
				////setting extra information about the purchased item from the IPN information array
				//$additionalCharges = $info['ap_additionalcharges'];
				//$shippingCharges = $info['ap_shippingcharges'];
				//$taxAmount = $info['ap_taxamount'];
				//$discountAmount = $info['ap_discountamount'];
				//
				////setting your customs fields received from the IPN information array
				
				$myCustomField_1 = $info['apc_1'];
				
				//$myCustomField_2 = $info['apc_2'];
				//$myCustomField_3 = $info['apc_3'];
				//$myCustomField_4 = $info['apc_4'];
				//$myCustomField_5 = $info['apc_5'];
				//$myCustomField_6 = $info['apc_6'];
				
				$return_data=Array(
					"transactionid" => "0"
				);
				
				if ($info["ap_status"]=="Success")
				{
					$return_data["transactionid"] = $myCustomField_1;
				}
				
				return $return_data;
			}
		}
		else
		{
			//something is wrong, no response is received from Alertpay
		}
	
	}

}