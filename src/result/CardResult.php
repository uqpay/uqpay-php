<?php

namespace uqpay\payment\sdk\result;

class CardResult
{
    private $channelCode;
    private $channelMsg;

    public function CardResult($mapResult)
    {
        $mapResult=(array) $mapResult;
            $this->channelCode = (string)$mapResult[RESULT_CHANNEL_CODE];
            $this->channelMsg = (string)$mapResult[RESULT_CHANNEL_MESSAGE];
    }
}