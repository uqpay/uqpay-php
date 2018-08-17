<?php

namespace uqpay\payment\sdk\result;

class QRResult
{
    private $scanType;
    private $QRCodeUrl;
    private $QRCode;

    function fromValue($value)
    {
        global $UqpayScanType;
        foreach ($UqpayScanType as $k => $v) {
            if($value == $v){
                return $v;
            }
        }
        return null;
    }

    function __construct($mapResult)
    {
        global $UqpayScanType;
        $this->scanType = $this->fromValue((string)($mapResult[PAY_OPTIONS_SCAN_TYPE]));
        if ($this->scanType != null && strcmp($this->scanType, $UqpayScanType["Consumer"])==0) {
            $this->QRCodeUrl = (string)$mapResult[RESULT_QR_CODE_URL];
            $this->QRCode = (string)$mapResult[RESULT_QR_CODE_DATA];
        }
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