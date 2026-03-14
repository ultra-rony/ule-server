<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryController extends CI_Controller
{
	private $currentTime;

	public function __construct()
	{
		parent::__construct();
		$this->currentTime = date('Y-m-d H:i:s');
        $this->load->model('OrderModel', 'order_model');
        $this->load->model('UserModel', 'user_model');
	}

    public function history() {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $token = $body['token'] ?? null;

        if ($token != null) {
            $user = $this->user_model->getUserByToken($token);
            $orders = $this->order_model->getHistory($user['id']);

            $result = [];

            foreach($orders as $order) {
                $result[] = $this->sortOrder($order);
            }
            return $this->output->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $this->output->set_status_header(403)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => false,
                'message' => 'Invalid token'
            ], JSON_UNESCAPED_UNICODE));
    }

    private function sortOrder($order, $details = false) {
        $sort = [
            'id' => (int)$order['id'],
            'customer' => [
                'id' => (int)$order['user_id'],
                'username' => $order['username'],
                'image_url' => $order['user_image_url'],
                'rating' => 0.0,
                'total_number' => 0
            ],
            'category' => [
                'id' => (int)$order['category_id'],
                'category_name' => $order['category_name'],
            ],
            'title' => $order['title'],
            'description' => $order['description'],
            'amount' => (float)$order['amount'],
            'image_url' => $order['image_url'],
            'city' => $order['city'],
            'address' => $order['address'],
            'required_workers' => (int)$order['required_workers'],
            'work_start_at' => $order['work_start_at'],
        ];
        if ($details) {
            $sort['latitude'] = (float)$order['latitude'];
            $sort['longitude'] = (float)$order['longitude'];
            $sort['work_end_at'] = $order['work_end_at'];
            $sort['customer']['phone'] = $order['phone'];
            $sort['status'] = (int)$order['status'];
            $sort['hour'] = null;

            if ($sort['work_start_at'] != null && $sort['work_end_at'] != null) {
                $start = strtotime($sort['work_start_at']);
                $end = strtotime($sort['work_end_at']);
                $sort['hour'] = (int)(($end - $start) / 3600);
            }
        }
        return $sort;
    }

}