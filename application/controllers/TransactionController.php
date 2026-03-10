<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransactionController extends CI_Controller
{
	private $currentTime;

	public function __construct()
	{
		parent::__construct();
		$this->currentTime = date('Y-m-d H:i:s');
        $this->load->model('UserModel', 'user_model');
        $this->load->model('UserTransactionModel', 'user_transaction_model');
	}

    public function index() {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $token = $body['token'] ?? null;
        if ($token != null) {
            $user = $this->user_model->getUserByToken($token);
            if ($user != null) {
                $transactions = $this->user_transaction_model->getTransactions($user['id']);
                foreach($transactions as &$item) {
                    $item['id'] = (int)$item['id'];
                    $item['amount'] = (float)$item['amount'];
                }
                $this->output->set_status_header(200)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($transactions, JSON_UNESCAPED_UNICODE));
                return;
            }
        }
        $this->output->set_status_header(403)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => false,
                'message' => 'Invalid token'
            ], JSON_UNESCAPED_UNICODE));
    }

}