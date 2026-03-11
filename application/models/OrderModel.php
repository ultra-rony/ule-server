<?php

class OrderModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

    public function add($order)
	{
		$this->db->insert('orders', $order);
		return $this->db->insert_id();
	}

    public function setById($order)
	{
		return $this->db->where("orders.id =", $order['id'])
            ->update('orders', $order);
	}

}
