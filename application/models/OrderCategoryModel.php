<?php

class OrderCategoryModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

    public function getCategories()
	{
		return $this->db->from("categories cat")
			->get()
			->result_array();
	}

}
