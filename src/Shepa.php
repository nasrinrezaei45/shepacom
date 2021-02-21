<?php


namespace NasrinRezaei45\Shepacom;

use Exception;
use NasrinRezaei45\Shepacom\Drivers\DriverInterface;
use NasrinRezaei45\Shepacom\Drivers\Driver;
use ReflectionClass;

class Shepa implements DriverInterface
{

    protected $selected_api_driver_name;

    protected $config_array;

    protected $selected_driver_setting;


    public function __construct(array $config_array)
    {
        $this->config_array = $config_array;
        $this->selected_api_driver_name = $this->config_array['default'];
        $this->selected_driver_setting = $this->config_array['drivers'][$this->selected_api_driver_name];
    }

    private function getDriverInstance(): Driver
    {
        $class = $this->config_array['map'][$this->selected_api_driver_name];
        $this->validateApi();
        return new $class($this->selected_driver_setting);
    }

    public function via(string $selected_api_driver_name)
    {
        $this->selected_api_driver_name = $selected_api_driver_name;
        $this->validateApi();
        $this->selected_driver_setting = $this->config_array['drivers'][$selected_api_driver_name];
        return $this;
    }

    private function validateApi()
    {
        if (empty($this->selected_api_driver_name)) {
            throw new Exception('Driver not selected or default driver does not exist.');
        }
        if (empty($this->config_array['drivers'][$this->selected_api_driver_name]) || empty($this->config_array['map'][$this->selected_api_driver_name])) {
            throw new Exception('Driver not found in config file. Try updating the package.');
        }
        if (!class_exists($this->config_array['map'][$this->selected_api_driver_name])) {
            throw new Exception($this->config_array['map'][$this->selected_api_driver_name] . ' Driver source not found. Please update the package.');
        }
        $reflect = new ReflectionClass($this->config_array['map'][$this->selected_api_driver_name]);
        if (!$reflect->implementsInterface(DriverInterface::class)) {
            throw new Exception("Driver must be an instance of DriverInterface.");
        }
    }


    public function send($amount, $email, $mobile, $description, $callback = null)
    {
        $driverInstance = $this->getDriverInstance();
        return $driverInstance->send($amount, $email, $mobile, $description, $callback);
    }

    public function verify($token, $amount)
    {
        $driverInstance = $this->getDriverInstance();
        return $driverInstance->verify($token, $amount);
    }

}