<?php

class UserModel extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function getUserByToken($token) {
		return $this->db->from("users us")
			->where("us.token", $token)
			->get()
			->row_array();
	}

	public function getUserById($id) {
		return $this->db->from("users us")
			->where("us.id", $id)
			->get()
			->row_array();
	}

    public function getUserByPhoneNumber($phoneNumber) {
		return $this->db->from("users us")
			->where("us.phone", $phoneNumber)
			->get()
			->row_array();
	}

	public function add($user)
	{
		$this->db->insert('users', $user);
		return $this->db->insert_id();
	}

	public function setById($user)
	{
        $user['updated_at'] = date('Y-m-d H:i:s');
		return $this->db->where("users.id =", $user['id'])->update('users', $user);
	}

}
