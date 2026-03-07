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

    public function getUser() {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $token = $body['token'] ?? null;
        if ($token != null) {
            $user = $this->user_model->getUserByToken($token);
            if ($user != null) {
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
                    'device' => $body['device'] ?? null,
                    'email' => $body['email'] ?? null,
                    // Passport
                    'last_name' => $body['last_name'] ?? null,
                    'first_name' => $body['first_name'] ?? null,
                    'middle_name' => $body['middle_name'] ?? null,
                    'series' => $body['series'] ?? null,
                    'number' => $body['number'] ?? null,
                    'issued_by' => $body['issued_by'] ?? null,
                    'issue_date' => $body['issue_date'] ?? null,
                    'birth_date' => $body['birth_date'] ?? null,
                    'birth_place' => $body['birth_place'] ?? null,
                    'passport_image' => $body['passport_image'] ?? null,
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
        if ($user['passport_image'] != null) {
            $user['is_verified'] = true;
        }
        $user['id'] = (int)$user['id'];
        $user['latitude'] = $user['latitude']  !== null ? (float)$user['latitude']  : null;
        $user['longitude'] = $user['longitude'] !== null ? (float)$user['longitude'] : null;
        unset($user['created_ip']);
        unset($user['last_name']);
        unset($user['first_name']);
        unset($user['middle_name']);
        unset($user['series']);
        unset($user['number']);
        unset($user['issued_by']);
        unset($user['issue_date']);
        unset($user['birth_date']);
        unset($user['birth_place']);
        unset($user['passport_image']);
        return $user;
    }

}