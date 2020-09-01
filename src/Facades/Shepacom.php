<?php

declare(strict_types=1);

namespace NasrinRezaei45\shepacom\Facades;

use Illuminate\Support\Facades\Facade;
use NasrinRezaei45\Shepacom\Exceptions\SendException;
use NasrinRezaei45\Shepacom\Exceptions\VerifyException;
use NasrinRezaei45\Shepacom\Http\Request;

/**
 * This is the shepacom facade class.
 */
class Shepacom extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shepacom';
    }

    /**
     * Send data to pay.ir and init transaction
     *
     * @param $amount
     * @param $redirect
     * @param null $factorNumber
     * @param null $mobile
     * @param null $description
     * @return mixed
     * @throws SendException
     */
    public static function send($amount, $redirect = null, $email = null, $mobile = null, $description = null, $api = null)
    {
        $url = 'https://merchant.shepa.com/api/v1/token';
        $api = !empty($api) ? $api : config('shepacom.api_key');
        if($api == 'sandbox') $url = 'https://sandbox.shepa.com/api/v1/token';
        $params = [
            'api' => $api,
            'callback' => $redirect ? $redirect : url(config('shepacom.callback')),
            'amount' => $amount,
            'email' => $email,
            'mobile' => $mobile,
            'description' => $description,
            'resellerId' => '1000000012',
        ];
        $send = Request::make($url, $params);
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] == 200 && !empty($send['response']['success'])) {
                $send['response']['payment_url'] = $send['response']['result']['url'];
                return $send['response'];
            }

            dd($verify['response']['error']);
        }

        throw new SendException('خطا در ارسال اطلاعات به Shepa.com. لطفا از برقرار بودن اینترنت و در دسترس بودن shepa.com اطمینان حاصل کنید');
    }

    /**
     * Verify transaction
     *
     * @param $token
     * @return mixed
     * @throws VerifyException
     */
    public static function verify($token, $amount ,$api = null)
    {
        $url = 'https://merchant.shepa.com/api/v1/verify';
        $api = $api ? $api : config('shepacom.api_key');
        if($api == 'sandbox') $url = 'https://sandbox.shepa.com/api/v1/verify';
        $verify = Request::make($url, [
            'api' => $api,
            'token' => $token,
            'amount' => $amount,
        ]);
        if (isset($verify['status']) && isset($verify['response'])) {
            if ($verify['status'] == 200 && !empty($verify['response']['success'])) {
                return $verify['response']['result'];
            }
            dd($verify['response']['error']);
        }

        throw new VerifyException('خطا در ارسال اطلاعات به Shepa.com. لطفا از برقرار بودن اینترنت و در دسترس بودن Shepa.com اطمینان حاصل کنید');
    }
}
