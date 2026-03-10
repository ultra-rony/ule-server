<?php

class UserTransactionModel extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

    public function getTransactions($userId) {
		return $this->db->select("ut.id, ut.type, ut.amount, ut.created_at")
            ->from("user_transactions ut")
			->where("ut.user_id", $userId)
            ->limit(100)
			->get()
			->result_array();
	}

	public function add($transaction)
	{
		$this->db->insert('user_transactions', $transaction);
		return $this->db->insert_id();
	}

}
