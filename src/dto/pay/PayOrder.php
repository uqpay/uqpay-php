<?php

namespace uqpay\payment\sdk\dto\pay;

use uqpay\payment\sdk\dto\common\OrderDTO;

class PayOrder extends OrderDTO
{
    private $transName;

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