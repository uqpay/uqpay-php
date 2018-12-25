<?php
namespace uqpay\payment\sdk\dto\preAuth;
use uqpay\payment\sdk\dto\common\OrderDTO;

class PreAuthOrder extends OrderDTO {
  private $transName; // product info
  private $uqOrderId;
    private $bankCard; // required when credit card payment
    private $serverHost; // required when server host payment
    private $merchantHost; // required when merchant host payment
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
