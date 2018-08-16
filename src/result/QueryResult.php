<?php

namespace result;

class QueryResult
{
    protected $sign;
    protected $merchantId;
    protected $tradeType;
    protected $code;
    protected $message;
    protected $date;
    protected $orderId;
    protected $uqOrderId;
    protected $relatedId; // 关联的订单ID，当发生退款、撤销时，对应订单
    protected $methodId;
    protected $state;
    protected $channelInfo;
    protected $extendInfo;


    function QueryResult($mapResult)
    {
        $this->code = (string)$mapResult[RESULT_CODE];
        $this->sign = (string)$mapResult[AUTH_SIGN];
        $this->merchantId = (int)(string)$mapResult[AUTH_MERCHANT_ID];
        $this->tradeType = $mapResult[PAY_OPTIONS_TRADE_TYPE];
        $this->message = (string)$mapResult[RESULT_MESSAGE];
        $this->date = date((int)$mapResult[ORDER_DATE]);
        $this->orderId = (string)$mapResult[ORDER_ID];
        $this->uqOrderId = (int)(string)$mapResult[RESULT_UQPAY_ORDER_ID];
        $this->methodId = (int)(string)$mapResult[PAY_OPTIONS_METHOD_ID];
        $this->state = (string)$mapResult[RESULT_STATE];
        $this->channelInfo = (string)$mapResult[ORDER_CHANNEL_INFO];
        $this->extendInfo = (string)$mapResult[ORDER_EXTEND_INFO];
        $this->relatedId = (string)$mapResult[RESULT_UQPAY_RELATED_ID];
    }
}