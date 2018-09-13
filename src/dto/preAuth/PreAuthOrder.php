<?php
namespace uqpay\payment\sdk\dto\preAuth;
use uqpay\payment\sdk\dto\common\OrderDTO;

class PreAuthOrder extends OrderDTO {
  private $transName; // product info
  private $uqOrderId;
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
