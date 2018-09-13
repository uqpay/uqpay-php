<?php
namespace uqpay\payment\sdk\dto\common;


class BankCardDTO extends CreditCardDTO {
  private $addressCountry;
  private $addressState;
  private $addressCity;
  private $address;
  private $zip;
  private $email;


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
