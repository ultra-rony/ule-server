<?php

class OrderModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

	public function getOrders($page = 0, $confirmed = false) {
		$limit = 100;
		$offset = $page * $limit;
		return $this->db->select("or.*")
			->select("(SELECT cat.name FROM categories cat WHERE cat.id = or.category_id) as category_name")
            ->select("(SELECT us.username FROM users us WHERE us.id = or.user_id) as username")
            ->select("(SELECT us.image_url FROM users us WHERE us.id = or.user_id) as user_image_url")
			->from("orders or")
			->limit($limit, $offset)
			->order_by("id", "DESC")
			->get()
			->result_array();
	}

    public function getOrder($orderId) {
		return $this->db->select("or.*")
			->select("(SELECT cat.name FROM categories cat WHERE cat.id = or.category_id) as category_name")
            ->select("(SELECT us.username FROM users us WHERE us.id = or.user_id) as username")
            ->select("(SELECT us.image_url FROM users us WHERE us.id = or.user_id) as user_image_url")
			->from("orders or")
            ->where("or.id =", (int)$orderId)
			->limit(1)
			->get()
			->row_array();
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
