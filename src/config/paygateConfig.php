<?php

namespace uqpay\payment\sdk\config;
require_once 'baseConfig.php';

class paygateConfig extends baseConfig
{
    private $apiRoot = "https://paygate.uqpay.com";

    private $rsaConfig;

    public function __construct($config)
    {
        $this->apiRoot=$config["apiRoot"];
        $this->rsaConfig = $config["rsaConfig"];
    }

//set方法
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    //get方法
    public function __get($name)
    {
        if (!isset($this->$name)) {
            //未设置
            $this->$name = "";
        }
        return $this->$name;
    }
}