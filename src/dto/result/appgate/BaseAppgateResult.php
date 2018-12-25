<?php
namespace uqpay\payment\sdk\dto\result\appgate;

class BaseAppgateResult {
  private $merchantId;
  private $agentId;
  private $date;  // Unix Timestamp
  private $signType;
  private $signature;
  private $respCode;
  private $respMessage;

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
