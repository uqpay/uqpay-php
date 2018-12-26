<?php

namespace uqpay\payment\sdk\config;
class baseConfig
{
    var $testMode = false;
    var $testRSA;
    var $productRSA;

    public function getRSA()
    {
        if ($this->isTestMode()) {
            return $this->testRSA;
        }
        return $this->productRSA;
    }

    public function isTestMode()
    {
        return $this->testMode;
    }

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
