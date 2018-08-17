<?php
namespace uqpay\payment\sdk\config;
use uqpay\payment\sdk\config\baseConfig;
require_once 'baseConfig.php';
class cashierConfig extends baseConfig{
    private $cashierApiRoot = "https://cashier.uqpay.cn";

    function __construct($config)
    {
        $this->apiRoot=$config["apiRoot"];
    }

    //set方法
    public function __set($name, $value){
        $this->$name = $value;
    }

    //get方法
    public function __get($name){
        if(!isset($this->$name)){
            //未设置
            $this->$name = "";
        }
        return $this->$name;
    }
}