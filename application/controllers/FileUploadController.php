<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileUploadController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url']);
        $this->load->library('upload');
    }

    public function index()
    {
        // Поле должно называться "file"
        if (empty($_FILES['file']) || empty($_FILES['file']['name'])) {
            return $this->output
                ->set_status_header(400)
                ->set_output('No file provided (field name must be "file").');
        }

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;

        // Создаём папку, если нет
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return $this->output
                    ->set_status_header(500)
                    ->set_output('Failed to create uploads directory.');
            }
        }

        // Конфиг загрузки
        $config = [];
        $config['upload_path']   = $uploadDir;

        // ВАЖНО: allowed_types в CI3 — это расширения (без точек)
        $config['allowed_types'] = implode('|', [
            'gif','jpg','jpeg','png','bmp','webp','svg','tiff','ico',
            'mp4','mov','m4v','webm','mkv','avi','mpeg','mpg'
        ]);

        // 200 MB (CI3 считает в KB)
        $config['max_size'] = 204800;

        // Сразу делаем уникальное имя (лучше чем rename)
        $config['encrypt_name'] = TRUE;

        // MIME detection (часто лечит)
        $config['detect_mime']  = TRUE;
        $config['mod_mime_fix'] = TRUE;

        // Можно ограничить размеры изображений (опционально)
        // $config['max_width']  = 6000;
        // $config['max_height'] = 6000;

        $this->upload->initialize($config, true);

        if (!$this->upload->do_upload('file')) {
            // Отладочная инфа в лог
            log_message('error', 'UPLOAD ERROR: ' . $this->upload->display_errors('', ''));
            log_message('error', 'CLIENT FILE NAME: ' . ($_FILES['file']['name'] ?? ''));
            log_message('error', 'CLIENT MIME TYPE: ' . ($_FILES['file']['type'] ?? ''));

            return $this->output
                ->set_status_header(400)
                ->set_output($this->upload->display_errors('', ''));
        }

        $data = $this->upload->data();

        // Публичная ссылка (base_url должен быть настроен)
        $baseUrl = rtrim($this->config->item('base_url'), '/');
        $publicUrl = $baseUrl . '/uploads/' . $data['file_name'];

        // Определяем тип по расширению
        $fileExt = strtolower(pathinfo($data['file_name'], PATHINFO_EXTENSION));

        $videoExt = ['mp4','mov','m4v','webm','mkv','avi','mpeg','mpg'];
        $imageExt = ['gif','jpg','jpeg','png','bmp','webp','svg','tiff','ico'];

        $payload = [
            'file_url'   => $publicUrl,
            'file_name'  => $data['file_name'],
            'file_type'  => 'file',
            'mime'       => $data['file_type'],
            'size_kb'    => (int) $data['file_size']
        ];

        if (in_array($fileExt, $imageExt, true)) {
            $payload['file_type'] = 'image';
            $payload['image_url'] = $publicUrl;
        } elseif (in_array($fileExt, $videoExt, true)) {
            $payload['file_type'] = 'video';
            $payload['video_url'] = $publicUrl;
        }

        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}