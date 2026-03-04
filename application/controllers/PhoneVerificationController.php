<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PhoneVerificationController extends CI_Controller
{
	private $currentTime;

	public function __construct()
	{
		parent::__construct();
		$this->currentTime = date('Y-m-d H:i:s');
		$this->load->model('PhoneVerificationModel', 'phone_verification_model');
        $this->load->model('UserModel', 'user_model');
        $this->load->library('callgate');
	}

	public function callgateCreate()
	{
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $phoneNumber = $body['phone_number'] ?? null;
        if ($phoneNumber == null) {
            $this->output->set_status_header(403)
				->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => 'Invalid phone number'], JSON_UNESCAPED_UNICODE));
			return;
        }

        $verificationData = $this->phone_verification_model->getPhoneVerificationData($phoneNumber);
        $callgateResult = $this->callgate->createCall($phoneNumber);
        if ($callgateResult['success'] == false) {
            $this->output->set_status_header(403)
				->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => 'Invalid phone number'], JSON_UNESCAPED_UNICODE));
			return;
        }
        $data = [
            'id' => null,
            'phone' => $phoneNumber,
            'is_verification' => false,
            'callgate_create_response' => json_encode($callgateResult, JSON_UNESCAPED_UNICODE),
            'callgate_check_response' => null,
            'updated_at' => $this->currentTime,
            'created_at' => $this->currentTime,
            'created_ip' => $this->getClientIp(),
        ];
        if ($verificationData != null) {
            $data['id'] = $verificationData['id'];
            $this->phone_verification_model->setById($data);
        }else {
            $this->phone_verification_model->add($data);
        }
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true, 'mobile' => $callgateResult['result']['mobile']], JSON_UNESCAPED_UNICODE));
    }

    public function callgateCheck()
	{
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $phoneNumber = $body['phone_number'] ?? null;
        if ($phoneNumber == null) {
            $this->output->set_status_header(403)
				->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => 'Invalid phone number'], JSON_UNESCAPED_UNICODE));
			return;
        }
        $verificationData = $this->phone_verification_model->getPhoneVerificationData($phoneNumber);
        if ($verificationData != null && (int)$verificationData['is_verification'] == 0) {

            $callgateJson = $verificationData['callgate_create_response'] ?? null;
            $callgateData = json_decode($callgateJson, true);
            $callgateId = $callgateData['result']['id'] ?? null;

            // Проверка callgate
            $callgateCheckResult = $this->callgate->checkCall($callgateId);

            if ($callgateCheckResult != null && $callgateCheckResult['success'] == true) {
                $data = [
                    'id' => $verificationData['id'],
                    'is_verification' => 1,
                    'callgate_check_response' => json_encode($callgateCheckResult, JSON_UNESCAPED_UNICODE),
                    'updated_at' => $this->currentTime,
                ];
                $this->phone_verification_model->setById($data);

                $user = $this->user_model->getUserByPhoneNumber($phoneNumber);
                // Создание нового пользователя
                if ($user == null) {
                    $user = [
                        'id' => null,
                        'phone' => $phoneNumber,
                        'token' => bin2hex(random_bytes(36)),
                        'created_ip' => $this->getClientIp(),
                        'created_at' => $this->currentTime,
                        'updated_at' => $this->currentTime,
                    ];
                    $user['id'] = $this->user_model->add($user);
                }
                $user = $this->user_model->getUserById($user['id']);
                $user['is_verified'] = false;
                if ($user['passport_series'] != null) {
                    $user['is_verified'] = true;
                }
                $user['latitude'] = $user['latitude']  !== null ? (float)$user['latitude']  : null;
                $user['longitude'] = $user['longitude'] !== null ? (float)$user['longitude'] : null;

                unset($user['created_ip']);
                unset($user['passport_series']);
                unset($user['passport_number']);
                unset($user['passport_issue_date']);
                unset($user['passport_expiry_date']);
                unset($user['passport_issued_by']);
                unset($user['passport_department_code']);
                unset($user['passport_image_url']);

                $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['success' => true, 'user' => $user], JSON_UNESCAPED_UNICODE)); 
                return;
            }
        }
        $this->output->set_status_header(403)
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => false, 'message' => 'Такой номер не зарегистрирован!'], JSON_UNESCAPED_UNICODE));
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