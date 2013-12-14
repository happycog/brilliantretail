<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright © 2010-2013						*/
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

/************************************************************/
/*	Special thanks to Frans Cooijmans at dwise.nl
/************************************************************/

include_once(PATH_THIRD.'brilliant_retail/core/gateway/assets/mollie-ideal/ideal.class.php');

class Gateway_mollie_ideal extends Brilliant_retail_gateway
{
    public $title 		= 'Mollie iDEAL';
    public $label 		= 'Via iDEAL betalen';
    public $descr 		= 'Mollie Payment Provider';
    public $enabled 	= true;
    public $ipn_enabled = true;
    public $version 	= '1.0';
    public $cart_button = false;

    function __construct()
    {
        parent::__construct();
    }

    public function process($data, $config)
    {
        $this->mollie = new iDEAL_Payment($config['partner_id']);

        // check if the bank_id is set
        if(!isset($data['bank_id']) || empty($data['bank_id']))
        {
            return array('error' => 'Selecteer een bank');
        }

        // Return the transaction details
        $trans = array(
            'status' => -1,
            'transaction_id' => $data['transaction_id'],
            'payment_card' => "",
            'payment_type' => 'Mollie iDEAL',
            'amount' => $data["order_total"],
            'details' => serialize(array("Method" => "Mollie iDEAL")),
            'approval' => "",
            'bank_id' => $data['bank_id']
        );

        return $trans;
    }

    function start_ipn($data, $config)
    {
        $this->mollie = new iDEAL_Payment($config['partner_id']);

        // set return url, bases on the order_id we know how to process the IPN call
        $return_url = $data['notify_url'] . '&order_id=' . $data['order_id']; 
        $report_url = $data['notify_url'];
        
        // IMPORTANT: fix numbers to be without separators.
        $amount = number_format($data["order_total"], 2, '', '');

        // TODO: create config variable for default "order" text, displayed on Mollie redirect page.
        if($this->mollie->createPayment($data['bank_id'], $amount, 'Order: ' . $data['order_id'], $return_url, $report_url))
        {
            $url = $this->mollie->getBankURL();
            $transid = $this->mollie->getTransactionId();

            $this->EE->db->set('transaction_id', $transid);
            $this->EE->db->where('transaction_id', $data['transaction_id']);
            $this->EE->db->update('br_order_payment');

            // IMPORTANT: set merchant_id to the transaction_id we get from Mollie, we need this later on.
            $this->EE->db->set('merchant_id', $transid);
            $this->EE->db->where('merchant_id', $data['transaction_id']);
            $this->EE->db->update('br_order');

            return $this->EE->functions->redirect($url);
        }
        else
        {
            $this->EE->product_model->cart_update_status(session_id(), 0);
            $_SESSION["br_alert"] = $this->mollie->getErrorMessage();
            $this->EE->functions->redirect($this->_secure_url($this->_config["store"][$this->site_id]["checkout_url"]));
        }
    }

    public function gateway_ipn($config)
    {
        $this->mollie = new iDEAL_Payment($config['partner_id']);

        $order_id = $this->EE->input->get('order_id');
        $transacion_id = $this->EE->input->get('transaction_id');

        if(!$transacion_id)
            return log_message('error', 'Mollie gateway called without transaction_id');

        log_message('debug', 'gateway called ' . $transacion_id);

        // if called with an order_id, we know this is a redirect request in the scope of the user
        // also see: start_ipn()
        if($order_id)
        {
            $order_status = $this->_ipn_validate_order($transacion_id);

            if(!$order_status)
            {
                $this->EE->product_model->cart_update_status(session_id(), 0);
                $_SESSION["br_alert"] = "Er is iets misgegaan tijdens uw betaling.";
                return $this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
            }

            switch($order_status['status_id'])
            {
                case -1:
                    $this->EE->product_model->cart_update_status(session_id(), 0);
                    $_SESSION["br_alert"] = "Er is iets misgegaan tijdens uw betaling.";
                    return $this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
                    break;
                case 0:
                    $this->EE->product_model->cart_update_status(session_id(), 0);
                    $_SESSION["br_alert"] = "U heeft uw betaling geannuleerd";
                    return $this->EE->functions->redirect($this->EE->functions->create_url($this->_config["store"][$this->site_id]["cart_url"]));
                    break;
                default:
                    $_SESSION["order_id"] = $order_status['order_id'];
                    return $this->EE->functions->redirect($this->EE->functions->create_url($config['thank_you_page']));
                    break;
            }
        }
        else // this is a ipn call from molly (outside the user scope)
        {
            $this->mollie->checkPayment($transacion_id);

            if($this->mollie->getPaidStatus() == TRUE)
            {
                $this->ipn_create_order($transacion_id, 3);
            }
            else
            {
                if($this->mollie->getBankStatus() == 'Cancelled')
                {
                    $order_status = $this->_ipn_validate_order($transacion_id);
                    $order_status['status_id'] = 0;

                    $this->EE->order_model->update_order_status($order_status);
                }
            }
        }
    }

    
    // TODO: Translations / language class.
    function form()
    {
        // Custom helper function to get hte config in the form function, would be handy to have this default by BrilliantRetail
        // We need the config because we need the partner_id to get the correct list of banks (managable trough mollie admin).
        $config = $this->_get_config();
        $this->mollie = new iDEAL_Payment($config['partner_id']);
        $bank_array = $this->mollie->getBanks();

        if($bank_array == false)
        {
            return '<p>Er is een fout opgetreden bij het ophalen van de banklijst: ' . $this->mollie->getErrorMessage() . '</p>';
        }

        $form = '<select name="bank_id" class="required">';
        $form .= '<option value="">Kies uw bank</option>';

        foreach($bank_array as $bank_id => $bank_name)
        {
            $form.= '<option value="' . $bank_id . '">' . $bank_name . '</option>';
        }

        $form .= '</select>';

        return $form;
    }

    // TODO: Translations / language class.
    function install($config_id)
    {
        $data = array();
        $data[] = array(
            'config_id' => $config_id,
            'label' => 'Partner ID',
            'code' => 'partner_id',
            'type' => 'text',
            'value' => '',
            'descr' => 'Geef uw partner id van Mollie op',
            'required' => true,
            'sort' => 1
        );

        $data[] = array(
            'config_id' => $config_id,
            'label' => 'Thank You pagina',
            'code' => 'thank_you_page',
            'type' => 'text',
            'descr' => 'Pagina waarna verwezen wordt bij succesvolle betaling',
            'value' => '',
            'sort' => 2
        );

        foreach($data as $d)
        {
            $this->EE->db->insert('br_config_data', $d);
        }
        return true;
    }

    function remove($config_id)
    {
        $this->EE->db->where('config_id', $config_id);
        $this->EE->db->delete('br_config_data');

        return true;
    }
    
    // Helper function, see Form() function.
    function _get_config()
    {
        $rem = array('gateway.', '.php');
        $code = strtolower(str_replace($rem, '', basename(__FILE__)));

        // Config data for the given code
        $config = array();
        if(isset($this->_config["gateway"][$this->site_id][$code]["config_data"]))
        {
            $config_data = $this->_config["gateway"][$this->site_id][$code]["config_data"];
            foreach($config_data as $c)
            {
                $config[$c["code"]] = $c["value"];
            }
        }

        return $config;
    }

}