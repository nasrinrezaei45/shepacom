<?php

namespace NasrinRezaei45\Shepacom\Drivers\Merchant;

use NasrinRezaei45\Shepacom\Drivers\Driver;
use NasrinRezaei45\Shepacom\Exceptions\SendException;
use NasrinRezaei45\Shepacom\Exceptions\VerifyException;

class Merchant extends Driver
{

    private  $token_url = 'https://merchant.shepa.com/api/v1/token';
    private  $verify_url = 'https://merchant.shepa.com/api/v1/verify';

    public function send($amount, $email, $mobile, $description, $callback = null)
    {
        $api_key = $this->settings_array['api_key'];
        if (is_null($api_key)) {
            throw new SendException("Please define shepa api_key in your config file !");
        }
        $params = [
            'api'         => $api_key,
            'callback'    => $callback ? $callback : $this->settings_array['callback'],
            'amount'      => $amount,
            'email'       => $email,
            'mobile'      => $mobile,
            'description' => $description,
            'resellerId'  => '1000000012',
        ];
        $api_result = $this->curl_request($this->token_url, $params);
        if (isset($api_result['success']) && $api_result['success'] == true) {
            return $api_result['result'];
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
