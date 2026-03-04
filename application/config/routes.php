<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$v1 = 'api/v1/';
$route[$v1.'callgate/create'] = 'PhoneVerificationController/callgateCreate';
$route[$v1.'callgate/check'] = 'PhoneVerificationController/callgateCheck';

$route['migrate'] = 'migrate';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
