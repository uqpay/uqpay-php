<?php
namespace uqpay\payment\sdk\dto\result\appgate;

class PayloadResult extends BaseAppgateResult {
  private $payload;
  private $currency;
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
