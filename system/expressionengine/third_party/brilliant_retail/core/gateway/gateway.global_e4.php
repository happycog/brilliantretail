<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Ezequiel Maraschio 								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
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

class Gateway_global_e4 extends Brilliant_retail_gateway {
	// Required variables
	public $title 	= 'First Data Global Gateway e4';
	public $label 	= 'First Data Global Gateway e4';
	public $descr 	= 'Accept credit cards directly from your site with a First Data Global Gateway e4 account.';
	public $enabled = true;
	public $version = 1.0;

	function process($data, $config)
	{

            $url = "https://api.globalgatewaye4.firstdata.com/transaction/v11";
            $gateway_id = $config["gateway_id"];
            $password = $config["password"];
            if ($config['test_mode'] == "TRUE") {
                $url = "https://api.demo.globalgatewaye4.firstdata.com/transaction/v11";
                $gateway_id = $config["test_gateway_id"];
                $password = $config["test_password"];
            }

            $packet = array(
                            "gateway_id" => $gateway_id,
                            "password" => $password,
                            "transaction_type" => "00", //Purchase
                            "amount" => $data['order_total'],
                            "cardholder_name" => $data['e4_cc_name'],
                            "cc_number" => $data['e4_cc_num'],
                            "cc_expiry" => $data['e4_cc_month_exp'].$data['e4_cc_year_exp']
                        );
            $response = $this->e4_send($packet, $url);

            if ($response["transaction_approved"]) {
                 $details = array(
                            "Method" => "First Data Global Gateway e4",
                            "Approval Code" => $response['exact_resp_code']
                        );

                $trans = array(
                            'status' => 3,
                            'transaction_id' => $response['sequence_no'],
                            'payment_type' => 'First Data Global Gateway e4',
                            'amount' => $data["order_total"],
                            'details' => serialize($details),
                            'approval' => $response['exact_message']
                        );
            } else {
                $trans = array('error' => $response["transaction_error"]. ' - ' . $response['exact_message']);
            }
            return $trans;
	}

	function e4_send($packet, $url) {
                $data_string = json_encode($packet);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=UTF-8',
                    'Accept: application/json',
                    'Content-Length: ' . strlen($data_string))
                );

                $result = curl_exec($ch);
                curl_close($ch);

                return json_decode($result, true);;
	}

	// Create a inputs for the checkout form
	function form()
	{
		$form = '<div class="general">
	                    <label>Credit Card Name *<br />
	                    <input class="txtinp required" name="e4_cc_name" type="text" /></label>
	                </div>

	                <div class="general">
	                    <label>Credit Card Number *<br />
	                    <input class="txtinp required creditcard" name="e4_cc_num" type="text" /></label>
	                </div>

	                <div class="expdate_month">
	                    <label>Expiration Date *<br />
	                    <select name="e4_cc_month_exp" class="required">
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
	                    <select name="e4_cc_year_exp" class="required">';
		$year = date("y");
		for($i=$year;$i<=($year+10);$i++){
			$i = str_pad($i, 2, 0, STR_PAD_LEFT);
			$form .= '			<option value="'.$i.'">'.$i.'</option>';
		}
		$form .=  '   	</select></label>
	                </div>
	                <div class="clearboth"><!-- --></div>';
		return $form;
	}

	// Install the gateway
	function install($config_id)
	{
		$data = array();
		$data[] = array(
						'config_id' => $config_id,
						'label'	 	=> 'Gateway ID',
						'code'		=> 'gateway_id',
						'type' 		=> 'text',
						'value'		=> '',
						'descr'		=> 'Also known as the Gateway ID, this value identifies the Merchant and Terminal under which the transaction is to be processed',
						'sort' 		=> 1
						);
		$data[] = array(
						'config_id' => $config_id,
						'label'	 	=> 'Password',
						'code'		=> 'password',
						'type' 		=> 'text',
						'value'		=> '',
						'descr'		=> 'Authenticates the Global Gateway e4 web service API request, this value should not be exposed to the public',
						'sort' 		=> 2
						);
                $data[] = array(
						'config_id' => $config_id,
						'label'	 	=> 'Test Gateway ID',
						'code'		=> 'test_gateway_id',
						'type' 		=> 'text',
						'value'		=> '',
						'descr'		=> 'Also known as the Gateway ID, this value identifies the Merchant and Terminal under which the transaction is to be processed',
						'sort' 		=> 4
						);
		$data[] = array(
						'config_id' => $config_id,
						'label'	 	=> 'Test Password',
						'code'		=> 'test_password',
						'type' 		=> 'text',
						'value'		=> '',
						'descr'		=> 'Test account to authenticates the Global Gateway e4 web service API request, this value should not be exposed to the public',
						'sort' 		=> 5
						);
		$data[] = array(
						'config_id' => $config_id,
						'label'	 	=> 'Test Mode',
						'code' 		=> 'test_mode',
						'type' 		=> 'dropdown',
						'options' 	=> 'TRUE:True|FALSE:False',
						'descr'		=> 'Select Test Mode. If you select FALSE, transactions will be live',
						'value' 	=> 'TRUE',
						'sort' 		=> 3
						);
		foreach($data as $d){
			$this->EE->db->insert('br_config_data',$d);
		}
		return true;
	}

	function remove($config_id)
	{
		return true;
	}
}