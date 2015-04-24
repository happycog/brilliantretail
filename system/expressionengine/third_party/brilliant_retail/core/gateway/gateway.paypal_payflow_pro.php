<?php

/* * ********************************************************* */
/* 	BrilliantRetail 										 */
/* 															 */
/* 	@package	BrilliantRetail								 */
/* 	@Author		Ezequiel Maraschio  								 */
/* 	@copyright	Copyright (c) 2010-2015						 */
/* 	@license	http://brilliantretail.com/license.html		 */
/* 	@link		http://brilliantretail.com 					 */
/* 															 */
/* * ********************************************************* */
/* NOTICE													 */
/* 															 */
/* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF 	 */
/* ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED	 */
/* TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 		 */
/* PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT 		 */
/* SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY */
/* CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION	 */
/* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR 	 */
/* IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 		 */
/* DEALINGS IN THE SOFTWARE. 								 */
/* * ********************************************************* */
include_once('assets/paypal/paypal_payflow.php');

class Gateway_paypal_payflow_pro extends Brilliant_retail_gateway
{

    public $title = 'Paypal Payflow Pro';
    public $label = 'Credit Card Payment (PayPal)';
    public $descr = 'Accept creditcards directly from your site with an PayPal Payflow Pro payment gateway account.';
    public $enabled = true;
    public $version = 1;

    public function process($data, $config)
    {
        $user = ($config['user'] != '' ? $config['user'] : $config['merchant_id']);
        $payflow = new PayFlow($config['merchant_id'], $config['partner_id'], $user, $config['password'], 'single');

        $environment = ($config['sandbox'] == "TRUE" ? 'test' : 'live');
        $payflow->setEnvironment($environment);
        $payflow->setTransactionType('S');
        $payflow->setPaymentMethod('C');
        $payflow->setPaymentCurrency($this->_config["currency"]);

        $payflow->setAmount($data["order_total"], FALSE);
        $payflow->setCCNumber($data["payflow_cc_num"]);
        $payflow->setCVV($data["payflow_cc_code"]);
        $payflow->setExpiration($data["payflow_cc_month"] . $data["payflow_cc_year"]);
        $payflow->setCreditCardName($data["payflow_cc_name"]);

        $payflow->setCustomerFirstName($data["br_billing_fname"]);
        $payflow->setCustomerLastName($data["br_billing_lname"]);
        $payflow->setCustomerAddress($data["br_billing_address1"]);
        $payflow->setCustomerCity($data["br_billing_city"]);
        $payflow->setCustomerState($data["br_billing_state"]);
        $payflow->setCustomerZip($data["br_billing_zip"]);
        $payflow->setCustomerCountry($data["br_billing_country"]);
        $payflow->setCustomerPhone($data["br_billing_phone"]);

        $transaction = $payflow->processTransaction();
        $response = $payflow->getResponse();
        
        if ($transaction) {
            $trans = $this->create_order($data, $response);
        } elseif (($response['RESULT'] == '126') && ($config['fraud_filters_actions'] == 'neworder')) {
            $trans = $this->create_order($data, $response, 1);
        } else {
            $trans = array(
                'error' => $response['RESPMSG']
            );
        }
        return $trans;
    }

    private function create_order($data, $response, $status = 3)
    {
        $card_type = cc_type_number($data["payflow_cc_num"]);
        $details = array(
            "Method" => "Paypal Payflow Pro",
            "Card Type" => $card_type,
            "Card" => 'XXXX' . substr($data["payflow_cc_num"], -4, 4),
            "Approval Code" => $response["AUTHCODE"],
            "Transaction ID" => $response["PNREF"]
        );

        $trans = array(
            'status' => $status,
            'transaction_id' => $response["PNREF"],
            'payment_card' => 'XXXX' . substr($data["payflow_cc_num"], -4, 4),
            'payment_type' => 'Authorize',
            'amount' => $data["order_total"],
            'details' => serialize($details),
            'approval' => $response["RESPMSG"],
        );

        return $trans;
    }

    public function form()
    {
        $form = '   <div class="general">
                        <label>Credit Card Name *<br />
                        <input class="txtinp required" name="payflow_cc_name" type="text" /></label>
                    </div>
                    <div class="general">
                        <label>Credit Card Number *<br />
                        <input class="txtinp required creditcard" name="payflow_cc_num" type="text" /></label>
                    </div>

                    <div class="expdate_month">
                        <label>Expiration Date *<br />
                        <select name="payflow_cc_month" class="required">
                          <option value="01">January</option>
                          <option value="02">February</option>
                          <option value="03">March</option>
                          <option value="04">April</option>
                          <option value="05">May</option>
                          <option value="06">June</option>
                          <option value="07">July</option>
                          <option value="08">August</option>
                          <option value="09">September</option>
                          <option value="10">October</option>
                          <option value="11">November</option>
                          <option value="12">December</option>
                        </select></label>
                    </div>
                    <div class="expdate_year">
                        <label>&nbsp;<br />
                        <select name="payflow_cc_year" class="required">';
        $year = date("Y");
        for ($i = $year; $i <= ($year + 10); $i++) {
            $form .= '			<option value="' . ($i-2000) . '">' . $i . '</option>';
        }
        $form .= '   	</select></label>
		                </div>
		                <div class="clearboth"><!-- --></div>
		                <div class="card_code">
		                    <label>Security Code *<br />
		                    <input class="txtinp required" name="payflow_cc_code" type="text" /></label>
		                </div>
		                <div class="clearboth"><!-- --></div>';
        return $form;
    }

    public function install($config_id)
    {
        $data = array();
        $data[] = array(
            'config_id' => $config_id,
            'label' => 'Partner ID',
            'code' => 'partner_id',
            'type' => 'text',
            'required' => true,
            'sort' => 1
        );
        $data[] = array(
            'config_id' => $config_id,
            'label' => 'Merchant Login ID',
            'code' => 'merchant_id',
            'type' => 'text',
            'required' => true,
            'sort' => 2
        );
        $data[] = array(
            'config_id' => $config_id,
            'label' => 'User',
            'code' => 'user',
            'type' => 'text',
            'required' => false,
            'sort' => 3
        );
        $data[] = array(
            'config_id' => $config_id,
            'label' => 'Password',
            'code' => 'password',
            'type' => 'text',
            'required' => true,
            'sort' => 4
        );
        $data[] = array(
            'config_id' => $config_id,
            'label' => 'Fraud Filter Actions',
            'code' => 'fraud_filters_actions',
            'type' => 'dropdown',
            'options' => 'neworder:Create a new order|decline:Decline the order',
            'value' => 'neworder',
            'sort' => 5
        );
        $data[] = array(
            'config_id' => $config_id,
            'label' => 'Sandbox Mode',
            'code' => 'sandbox',
            'type' => 'dropdown',
            'options' => 'TRUE:True|FALSE:False (Transactions are Live)',
            'value' => 'TRUE',
            'sort' => 6
        );

        foreach ($data as $d) {
            $this->EE->db->insert('br_config_data', $d);
        }
        return true;
    }

    public function remove($config_id)
    {
        return true;
    }

}