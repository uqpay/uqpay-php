<?php

namespace uqpay\payment\sdk\dto\pay;

use uqpay\payment\sdk\dto\common\OrderDTO;
use Particle\Validator\Validator;

class PayOrder extends OrderDTO
{
    private $transName;
    private $bankCard; // required when credit card payment
    private $serverHost; // required when server host payment
    private $merchantHost; // required when merchant host payment


    public function __construct()
    {
        $this->validated();
    }

    protected function validated(){

        $validator = new Validator;
        $validator->required('transName');
        $validator->required('bankCard');
        $validator->required('serverHost');
        $validator->required('merchantHost');
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