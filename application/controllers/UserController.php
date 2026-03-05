<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserController extends CI_Controller
{
	private $currentTime;

	public function __construct()
	{
		parent::__construct();
		$this->currentTime = date('Y-m-d H:i:s');
        $this->load->model('UserModel', 'user_model');
	}

    public function updateUser() {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $token = $body['token'] ?? null;

        if ($token != null) {
            $user = $this->user_model->getUserByToken($token);

            if ($user != null) {

                $updateData = [
                    'id' => $user['id'],
                    'image_url' => $body['image_url'] ?? null,
                    'username' => $body['username'] ?? null,
                    'city' => $body['city'] ?? null,
                    'latitude' => $body['latitude'] ?? null,
                    'longitude' => $body['longitude'] ?? null,
                    'fcm_token' => $body['fcm_token'] ?? null,
                ];

                // Убираем только null
                $updateData = array_filter($updateData, function ($value) {
                    return $value !== null;
                });

                // Обновляем если есть что обновлять
                if (!empty($updateData)) {
                    $this->user_model->setById($updateData);
                }

                // Получаем свежие данные
                $user = $this->user_model->getUserByToken($token);

                $this->output->set_status_header(200)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'success' => true,
                        'user' => $this->userSort($user)
                    ], JSON_UNESCAPED_UNICODE));
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

    private function userSort($user) {
        $user['is_verified'] = false;
        if ($user['passport_series'] != null) {
            $user['is_verified'] = true;
        }
        $user['id'] = (int)$user['id'];
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
        return $user;
    }

}