<?php if (!defined('BASEPATH')) die('No direct script access allowed');

/**
 * Credits Gateway
 * For BrilliantRetail
 *
 * @package			DevDemon_Credits
 * @version			2.0
 * @author			DevDemon <http://www.devdemon.com> - Lead Developer @ Parscale Media
 * @copyright 		Copyright (c) 2007-2011 Parscale Media <http://www.parscale.com>
 * @license 		http://www.devdemon.com/license/
 * @link			http://www.devdemon.com
 * @see				http://www.brilliantretail.com
 */
class Gateway_credits extends Brilliant_retail_gateway
{

	// Required variables
	public $title 	= 'Credits (DevDemon)';
	public $label 	= 'Credits';
	public $descr 	= 'Use earned Credits to pay for goods.';
	public $enabled = TRUE;
	public $version = '2.0';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Creat EE Instance
		$this->EE =& get_instance();
		$this->site_id = $this->EE->config->item('site_id');

		return;
	}

	// ********************************************************************************* //

	/**
	 * Process "Payment"
	 * @param array $data
	 * @param array $config
	 * @access public
	 * @return array
	 */
	public function process($data, $config)
	{
		$member_id = $this->EE->session->userdata["member_id"];
		$status = 3;
		$details = array();
		$total_money = $data["order_total"];
		$total_credits = 0;
		$trans_id = uniqid();
		$credits_per_dollar = $config['credits_dollar'];

		// How many credits is this?
		$total_credits = round(($total_money * $credits_per_dollar), 2);

		// How much credits do we have?
		$this->EE->db->select('SUM(c.credits) as credits_total', FALSE);
		$this->EE->db->from('exp_credits c');
		$this->EE->db->where('member_id', $member_id);
		$query = $this->EE->db->get();
		$credits = $query->row('credits_total');

		if ($total_credits < $credits)
		{
			$status = 3;
		}
		else
		{
			$trans = array(
				'error' => "You don't have enough credits to complete this transaction"
			);

			return $trans;
		}

		// Action ID of BR Payment
		$this->EE->load->add_package_path(PATH_THIRD . 'credits/');
		$this->EE->config->load('credits');
		$actions = $this->EE->config->item('credits_actions');
		$action_id = $actions['brilliantretail_payment'];

		// Does the action stat already exist?
		$query = $this->EE->db->select('credit_id, credits')->from('exp_credits')->where('action_id', $action_id)->where('member_id',  $member_id)->where('site_id', $this->site_id)->limit(1)->get();

		// Do we need to update?
		$update = ( $query->num_rows() > 0 ) ? TRUE : FALSE;
		$credit_id = $query->row('credit_id');

		if ($update)
		{
			$this->EE->db->set('credits', "( credits - {$total_credits} )", FALSE);
			$this->EE->db->where('credit_id', $credit_id);
			$this->EE->db->update('exp_credits');
		}
		else
		{
			$this->EE->db->set('action_id',	$action_id);
			$this->EE->db->set('site_id',	$this->site_id);
			$this->EE->db->set('member_id', $member_id);
			$this->EE->db->set('credits',	"-{$total_credits}");
			$this->EE->db->insert('exp_credits');
		}


		// Lets log it!
		$this->EE->db->set('site_id',		$this->site_id);
		$this->EE->db->set('sender',		$member_id);
		$this->EE->db->set('receiver',		0);
		$this->EE->db->set('action_id',		$action_id);
		$this->EE->db->set('rule_id',		0);
		$this->EE->db->set('date',			$this->EE->localize->now);
		$this->EE->db->set('credits',		"-{$total_credits}");
		$this->EE->db->set('item_type',		0);
		$this->EE->db->set('item_id',		0);
		$this->EE->db->set('item_parent_id',0);
		$this->EE->db->set('comments',		"Transaction Value: {$total_money}");
		$this->EE->db->insert('exp_credits_log');
		$log_id = $this->EE->db->insert_id();

		// Details
		$details['Method'] = 'Credits';
		$details['Transaction ID'] = "{$trans_id}-{$log_id}";
		$details['Credits Used'] = $total_credits;

		// Return the trans details
		$trans = array(	'status' => $status,
						'transaction_id' => "{$trans_id}-{$log_id}",
						'payment_type' => 'Credits',
						'amount' => $total_money,
						'details' => serialize($details),
						'approval' => '',
		);

		return $trans;
	}

	// ********************************************************************************* //

	/**
	 * Install Gateway
	 *
	 * @param int $config_id
	 * @access public
	 * @return bool
	 */
	public function install($config_id)
	{
		$this->EE->db->set('config_id', $config_id);
		$this->EE->db->set('label', 'Credits per dollar');
		$this->EE->db->set('code', 'credits_dollar');
		$this->EE->db->set('type', 'text');
		$this->EE->db->set('value', '50');
		$this->EE->db->set('descr', 'How many Credits for 1 Dollar/Euro/Yen etc');
		$this->EE->db->set('required', TRUE);
		$this->EE->db->set('sort', 1);
		$this->EE->db->insert('br_config_data');

		return TRUE;
	}

	// ********************************************************************************* //

	public function remove($config_id)
	{

		return TRUE;
	}

	// ********************************************************************************* //



} // END CLASS

/* End of file gateway.credits.php */
/* Location: ./system/expressionengine/third_party/brilliant_retail/core/gateway/gateway.credits.php */