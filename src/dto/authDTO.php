<?php

namespace uqpay\payment\sdk\dto;


class AuthDTO
{
//@ParamLink(Constants.AUTH_MERCHANT_ID)
//@Positive
    private $merchantId;

//@ParamLink(Constants.AUTH_AGENT_ID)
    private $agentId;

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