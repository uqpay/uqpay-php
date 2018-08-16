<?php

namespace uqpay\payment\sdk\result;

class RefundResult
{
    private $sign;            //签名（参见3.签名与传参）
    private $merchantId;
    private $tradeType;        //交易类型，详见附录1
    private $code;            //结果码
    private $message;        //结果描述
    private $date;            //请求的时间，使用Unix时间戳

    private $orderId;        //商户订单ID，在商户端唯一
    private $uqOrderId;        //UQPAY端的订单ID
    private $amount;        //订单金额
    private $state;            //订单状态
    private $extendInfo;    //商户订单传入的Json编码的扩展信息


    function RefundResult($mapResult)
    {
        $this->code = (string)$mapResult[RESULT_CODE];
        $this->sign = (string)$mapResult[AUTH_SIGN];
        $this->merchantId = (int)(string)$mapResult[AUTH_MERCHANT_ID];
        $this->tradeType = (string)$mapResult[PAY_OPTIONS_TRADE_TYPE];
        $this->message = (string)$mapResult[RESULT_MESSAGE];
        $this->date = date((int)$mapResult[ORDER_DATE]);
        $this->orderId = (string)$mapResult[ORDER_ID];
        $this->uqOrderId = (int)(string)$mapResult[RESULT_UQPAY_ORDER_ID];
        $this->amount = $mapResult[ORDER_AMOUNT];
        $this->state = (string)$mapResult[RESULT_STATE];
        $this->extendInfo = (string)$mapResult[ORDER_EXTEND_INFO];
    }
}