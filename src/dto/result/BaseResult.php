<?php

namespace uqpay\payment\sdk\dto\result;

use uqpay\payment\sdk\dto\PaygateParams;

class BaseResult implements PaygateParams
{
    private $code;
    private $message;
    private $sign;
    private $merchantId;
    private $agentId;
    private $methodId;
    private $tradeType;
    private $date;

    function __construct($mapResult)
    {
        $mapResult = (array)$mapResult;
        $this->code = (string)$mapResult[RESULT_CHANNEL_CODE];
        $this->message = (string)$mapResult[RESULT_CHANNEL_MESSAGE];
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
