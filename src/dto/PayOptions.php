<?php

namespace uqpay\payment\sdk\dto;

class PayOptions implements PaygateParams
{
    public $tradeType;
    public $methodId;
    public $callbackUrl;
    public $client;
    public $returnUrl;
    public $scanType;
    public $identity;
    public $channelInfo;
    public $extendInfo;

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