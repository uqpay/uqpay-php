<?php
namespace sdk\config;
use sdk\config\baseConfig;
require_once 'baseConfig.php';
class cashierConfig extends baseConfig{
    private $apiRoot = "https://cashier.uqpay.cn";

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