<?php
namespace uqpay\payment\sdk\dto\enroll;


use uqpay\payment\sdk\dto\common\PayOptionsDTO;

class EnrollOrder extends PayOptionsDTO {
  private $orderId; // your order id
  private $date; // this order generate date
  private $verifyCode; // the verify code you get after request the verify api
  private $codeOrderId;// the uqpay order id, when you request for the verify code you will get it

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
