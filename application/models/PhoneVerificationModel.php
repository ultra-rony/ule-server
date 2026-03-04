<?php

class PhoneVerificationModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

	public function getPhoneVerificationData($phoneNumber)
	{
		return $this->db->from("phone_verifications pv")
            ->where("pv.phone =", $phoneNumber)
            ->limit(1)
			->get()
			->row_array();
	}

    public function add($phoneVerification)
	{
		$this->db->insert('phone_verifications', $phoneVerification);
		return $this->db->insert_id();
	}

    public function setById($phoneVerification)
	{
		return $this->db->where("phone_verifications.id =", $phoneVerification['id'])
            ->update('phone_verifications', $phoneVerification);
	}

}
