<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderCategoryController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
        $this->load->model('OrderCategoryModel', 'order_category_model');
	}

    public function index() {
        $categories = $this->order_category_model->getCategories();
        foreach($categories as &$item) {
            if ($item['price_per_hour'] != null) {
                $item['price_per_hour'] = json_decode($item['price_per_hour'], JSON_UNESCAPED_UNICODE);
            }
        }
        return $this->output->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($categories, JSON_UNESCAPED_UNICODE));
    }

}