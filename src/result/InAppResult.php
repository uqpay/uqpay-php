<?php

namespace sdk\result;

class InAppResult
{
    private $acceptCode;

    public function InAppResult($mapResult)
    {
        $mapResult=(array) $mapResult;
        $this->acceptCode = (string)$mapResult[RESULT_ACCEPT_CODE];
    }
}