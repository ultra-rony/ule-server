<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callgate {

    private $CI;
    private $token = '5wxbamqgi30aq6rsv7a5i7f052e2ov2o00hrmtydix1krud3o7g2wzn3nh1df8qp';

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function createCall($recipient)
    {
        $data = [
            'recipient' => $recipient
        ];

        $ch = curl_init('https://lcab.smsint.ru/json/v1.0/callgate/create');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Token: ' . $this->token
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return [
                'success' => false,
                'error' => $error
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    public function checkCall($id)
    {
        $data = [
            'id' => $id
        ];

        $ch = curl_init('https://lcab.smsint.ru/json/v1.0/callgate/check');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Token: ' . $this->token
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return [
                'success' => false,
                'error' => $error
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}