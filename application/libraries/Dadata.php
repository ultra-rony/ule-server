<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dadata {

    private $ci;
    private $token = '0cbef0173132ccb22cae92eabcf3318bc979bdd4';
    private $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function suggestAddress($query, $count = 5)
    {
        $data = [
            "query" => $query,
            "count" => $count
        ];

        $ch = curl_init($this->url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . $this->token,
            'Content-Type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if(curl_errno($ch)){
            return [
                'error' => curl_error($ch)
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }

}