<?php

namespace NasrinRezaei45\Shepacom\Drivers;


interface DriverInterface
{

    public function send($amount, $email, $mobile, $description, $callback = null);

    public function verify($token, $amount);
}