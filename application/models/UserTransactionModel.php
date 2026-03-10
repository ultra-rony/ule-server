<?php

class UserTransactionModel extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function add($transaction)
	{
		$this->db->insert('user_transactions', $transaction);
		return $this->db->insert_id();
	}

}
