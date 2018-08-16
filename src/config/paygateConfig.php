<?php

namespace config;
require_once 'baseConfig.php';

class paygateConfig extends baseConfig
{
    private $apiRoot = "https://paygate.uqpay.com";

    private $uqpayPublicKey;

    public function main()
    {
        $this->uqpayPublicKey = new RSAconfig();
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