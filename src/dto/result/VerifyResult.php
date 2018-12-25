<?php

namespace uqpay\payment\sdk\dto\result;

class VerifyResult extends BaseResult
{
    private $uqOrderId; // this order id generate by uqpay

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
