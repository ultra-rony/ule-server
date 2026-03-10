<?php
require_once 'vendor/autoload.php';
use YooKassa\Client;
class YookassaPaymentController extends CI_Controller {

    private $yookassa_config;

    public function __construct() {
        parent::__construct();
        $this->yookassa_config = [
            'secret' => $this->config->item('yookassa_secret_key'),
            'id' => $this->config->item('yookassa_shop_id'),
        ];
        $this->load->model('UserModel', 'user_model');
    }

    public function index() {
        // Разрешаем только POST
        $response = ['success' => false, 'message' => "Invalid request type"];
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->output
                ->set_status_header(405)
                ->set_content_type('application/json')
                ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
            return;
        }
        // Проверка токена (пользователь не найден)
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $token = $body['token'] ?? "empty";
        $user = $this->user_model->getUserByToken($token);
        if ($user === null) {
            $response = ['success' => false, 'message' => "Invalid key"];
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
            return;
        }
        // Проверка данных
        if ($body['refill'] == null) {
            $response = ['success' => false, 'message' => "Bad request"];
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
            return;
        }

        $idempotenceKey = uniqid('', true);
        $client = new Client();
        $client->setAuth(
            $this->yookassa_config['id'], 
            $this->yookassa_config['secret']
        );
        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => (float)$body['refill'],
                    'currency' => "RUB",
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://ule1.ru',
                ),
                'metadata' => array(
                    'user_id' => $user['id'],
                    'webhook_secret_key' => $this->config->item('yookassa_webhook_key'),
                ),
                 'receipt' => [
                    'customer' => [
                        'email' => "sk-suntar@mail.ru",
                    ],
                    'items' => [
                        [
                            'description' => "Пополнение баланса ULE1",
                            'quantity' => '1.00',
                            'amount' => [
                                'value' => (float)$body['refill'],
                                'currency' => "RUB",
                            ],
                            'vat_code' => 1, // НДС 0%
                            'payment_subject' => 'service', 
                            'payment_mode' => 'full_payment',
                        ],
                    ],
                ],
                'capture' => true,
                'description' => "Пополнение баланса ULE1",
            ),
            $idempotenceKey
        );
        $confirmationUrl = $payment->getConfirmation()->getConfirmationUrl();
        $response = [
            'success' => true,
            'redirect' => $confirmationUrl
        ];
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

}