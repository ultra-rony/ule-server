<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'vendor/autoload.php';
use YooKassa\Model\Notification\NotificationFactory;
use YooKassa\Model\NotificationEventType;

class WebhookYookassaController extends CI_Controller
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
        if ($body['object']['metadata']['webhook_secret_key'] == $this->config->item('yookassa_webhook_key') && $body['object']['paid'] == true) {
            // Достаем пользователя
            $user = $this->user_model->getUserById($body['object']['metadata']['user_id']);
            if (!$user) {
                return $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'success' => false,
                        'message' => 'User not found'
                    ], JSON_UNESCAPED_UNICODE));
            }
            // Сохраняем первый депозит
            if ($user['first_deposit_at'] == null) {
                $user['first_deposit_at'] = $this->currentTime;
            }

            $amount = (float)$body['object']['amount']['value'];
            // Баланс до пополнения
            $balanceBefore = (float)$user['balance'];

            // Пополняем баланс
            $balanceAfter = $balanceBefore + $amount;
            $user['balance'] = $balanceAfter;
            $this->user_model->setById($user);

            // Сохраняем транзакцию
            $this->user_transaction_model->add([
                'id' => null,
                'user_id' => $user['id'],
                'type' => 'deposit',
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'amount' => $amount,
                'meta' => json_encode($body, JSON_UNESCAPED_UNICODE),
                'created_at' => $this->currentTime,
            ]);

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true, 'message' => "Success"], JSON_UNESCAPED_UNICODE));
        }else {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => "Bad request"], JSON_UNESCAPED_UNICODE));
        }
    }

}