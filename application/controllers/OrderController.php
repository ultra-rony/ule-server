<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderController extends CI_Controller
{
	private $currentTime;

	public function __construct()
	{
		parent::__construct();
		$this->currentTime = date('Y-m-d H:i:s');
        $this->load->model('OrderModel', 'order_model');
        $this->load->model('UserModel', 'user_model');
	}

    public function index() {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $token = $body['token'] ?? null;
        $categoryId = $body['category_id'] ?? null;
        $phone = $body['phone'] ?? null;

        if ($token != null) {
            $user = $this->user_model->getUserByToken($token);
            if ($user != null) {
                $workStartAt = $body['work_start_at'] ?? null;
                $hours = $body['hours'] ?? null;

                if ((int)$hours > 0 && $workStartAt) {
                    $date = new DateTime($workStartAt);
                    $date->modify("+{$hours} hours");
                    $workEndAt = $date->format('Y-m-d H:i:s');
                }
                $order = [
                    'id' => null,
                    'user_id' => $user['id'],
                    'category_id' => $categoryId,
                    'phone' => $phone,
                    'title' => $body['title'] ?? null,
                    'description' => $body['description'] ?? null,
                    'image_url' => $body['image_url'] ?? null,
                    'amount' => $body['amount'] ?? null,
                    'city' => $user['city'],
                    'address' => $body['address'] ?? null,
                    'latitude' => $body['latitude'] ?? null,
                    'longitude' => $body['longitude'] ?? null,
                    'required_workers' => $body['required_workers'] ?? null,
                    'status' => 1,
                    'work_start_at' => $workStartAt,
                    'work_end_at' => $workEndAt,
                    'time_zone' => $body['time_zone'] ?? null,
                    'created_at' => $this->currentTime,
                    'created_ip' => $this->getClientIp(),
                ];

                if ($phone != null && $categoryId != null) {
                    $this->order_model->add($order);
                }

                return $this->output->set_status_header(200)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'success' => true,
                        'message' => 'Success'
                    ], JSON_UNESCAPED_UNICODE));
            }
        }
        $this->output->set_status_header(403)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => false,
                'message' => 'Invalid token'
            ], JSON_UNESCAPED_UNICODE));
    }

    public function getClientIp()
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];
        foreach ($headers as $key) {
            if (empty($_SERVER[$key])) {
                continue;
            }
            $ips = explode(',', $_SERVER[$key]);
            foreach ($ips as $ip) {
                $ip = trim($ip);

                if (filter_var(
                    $ip,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                )) {
                    return $ip;
                }
            }
        }
        return '0.0.0.0';
    }

}