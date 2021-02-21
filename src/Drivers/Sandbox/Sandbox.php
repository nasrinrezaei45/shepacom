<?php

namespace NasrinRezaei45\Shepacom\Drivers\Sandbox;

use NasrinRezaei45\Shepacom\Drivers\Driver;
use NasrinRezaei45\Shepacom\Exceptions\SendException;
use NasrinRezaei45\Shepacom\Exceptions\VerifyException;

class Sandbox extends Driver
{

    private $amount;
    private $callback;
    private $email;
    private $mobile;
    private $description;

    private $token_url = 'https://sandbox.shepa.com/api/v1/token';
    private $verify_url = 'https://sandbox.shepa.com/api/v1/verify';


    public function __construct(array $settings_array)
    {
        parent::__construct($settings_array);
    }

    public function send($amount, $email, $mobile, $description, $callback = null)
    {
        $params = [
            'api'         => $this->settings_array['api_key'],
            'callback'    => $callback ? $callback : $this->settings_array['callback'],
            'amount'      => $amount,
            'email'       => $email,
            'mobile'      => $mobile,
            'description' => $description,
            'resellerId'  => '1000000012',
        ];
        $api_result = $this->curl_request($this->token_url, $params);

        if (isset($api_result['success']) && $api_result['success'] == true) {
            return $api_result['result']['url'];
        }
        $errors = '';
        foreach ($api_result['error'] as $err) {
            $errors .= $err . PHP_EOL;
        }
        throw new SendException($errors);
    }

    public function verify($token, $amount)
    {
        $api_result = $this->curl_request($this->verify_url, [
            'api'    => $this->settings_array['api_key'],
            'token'  => $token,
            'amount' => $amount,
        ]);
        if (isset($api_result['success']) && $api_result['success']) {
            return $api_result['result'];
        }
        throw new VerifyException('خطا در ارسال اطلاعات به Shepa.com. لطفا از برقرار بودن اینترنت و در دسترس بودن Shepa.com اطمینان حاصل کنید');

    }
}