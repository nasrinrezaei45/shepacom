<?php


namespace NasrinRezaei45\Shepacom;

use NasrinRezaei45\Shepacom\Facades\Shepacom;

class ShepacomPG
{
    public $token;
    public $amount;
    public $callback;
    public $email;
    public $mobile;
    public $description;
    public $paymentUrl;
	public $api;

    /**
     * send
     *
     * @return mixed
     * @throws Exceptions\SendException
     */
    public function send()
    {
        try {
            $send = Shepacom::send($this->amount, $this->callback, $this->email, $this->mobile, $this->description , $this->api);
            $this->paymentUrl = $send['payment_url'];
        } catch (Exceptions\SendException $e) {
            throw $e;
        }
    }

    /**
     * verify
     *
     * @return mixed
     * @throws Exceptions\VerifyException
     */
    public function verify()
    {
        try {
            return Shepacom::verify($this->token , $this->amount);
        } catch (Exceptions\VerifyException $e) {
            throw $e;
        }
    }
}
