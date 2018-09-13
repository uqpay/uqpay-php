<?php

namespace uqpay\payment\sdk\exception;

class UqpayPayFailException extends \Exception
{
    private $code;
    private $message;

    function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
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