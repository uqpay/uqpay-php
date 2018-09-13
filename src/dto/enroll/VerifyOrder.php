<?php
namespace uqpay\payment\sdk\dto\enroll;


use uqpay\payment\sdk\dto\common\PayOptionsDTO;

class VerifyOrder extends PayOptionsDTO {
  private $orderId; // your order id
  private $date; // this order generate date
  private $cardNum; // the card you will enroll or pay with
  private $phone;

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
