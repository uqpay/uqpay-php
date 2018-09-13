<?php

namespace uqpay\payment\sdk\dto\common;
class CreditCardDTO extends BankCardCompatibleDTO
{
    private $firstName;
    private $lastName;
    private $cvv;

    function valueOf(BankCardDTO $bankCard)
    {
        $result = new CreditCardDTO();
        $safe = new \Threaded();
        $safe->merge($result);
        $safe->merge($bankCard);
        return $safe;
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
