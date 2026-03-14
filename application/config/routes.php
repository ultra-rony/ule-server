<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$v1 = 'api/v1/';
$route[$v1.'callgate/create'] = 'PhoneVerificationController/callgateCreate';
$route[$v1.'callgate/check'] = 'PhoneVerificationController/callgateCheck';

$route[$v1.'users/update'] = 'UserController/updateUser';
$route[$v1.'users/get'] = 'UserController/getUser';
$route[$v1.'users/transactions'] = 'TransactionController/index';

$route[$v1.'orders/add'] = 'OrderController/index';
$route[$v1.'orders'] = 'OrderController/orders';
$route[$v1.'orders/(:num)'] = 'OrderController/orderDetails/$1';
$route[$v1.'orders/categories'] = 'OrderCategoryController/index';

$route[$v1.'fileupload'] = 'FileUploadController';

$route['webhook/yookassa'] = 'WebhookYookassaController/index';
$route[$v1.'payment/yookassa'] = 'YookassaPaymentController';

$route['migrate'] = 'migrate';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
