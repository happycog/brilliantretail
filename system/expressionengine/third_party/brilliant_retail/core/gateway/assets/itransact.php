<?php
abstract class it_PaymentGateway
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


class iTransact extends it_PaymentGateway
{

	public function __construct()
	{
        // Some default values of the class
		$this->gatewayUrl = 'https://secure.itransact.com/cgi-bin/mas/split.cgi';
	}
	
	public function SubmitPayment(){
	
	 echo "<html>\n";
     echo "<head><title>Processing iTransact...</title></head>\n";
     echo "<body>";
     //echo "<body onLoad=\"document.forms['gateway_form'].submit();\">\n";
     echo "<form method=\"POST\" name=\"gateway_form\" ";
     echo "action=\"" . $this->gatewayUrl . "\">\n";

     foreach ($this->fields as $name => $value)
     {
          $a = explode("|",$value);
          foreach ($a as $item)
          {
          echo "<input type=\"hidden\" name=\"$name\" value=\"$item\"/>\n";
          }
     }


     echo "	<p style=\"text-align:center;\">Your order is being processed... will be redirected to the payment website.
     		<br/><br/>If you are not automatically redirected to payment website within 5 seconds...<br/><br/>
     		<input type=\"submit\" value=\"Click Here\"></p>
     		</form>
     		</body>
     	</html>";
	
	}
}