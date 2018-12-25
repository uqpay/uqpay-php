<?php
namespace uqpay\payment\sdk\dto\result\appgate;

class ExchangeRateResult extends BaseAppgateResult {
  private $originalCurrency; // 原币种
  private $targetCurrency; // 目标币种
  private $buyPrice; // 买入价
  private $sellPrice; // 卖出价
  private $addTime; // 时间

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
