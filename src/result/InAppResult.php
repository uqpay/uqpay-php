<?php

namespace uqpay\payment\sdk\result;

class InAppResult
{
    private $acceptCode;

    function __construct($mapResult)
    {
        $this->acceptCode = (string)$mapResult[RESULT_ACCEPT_CODE];
    }
}