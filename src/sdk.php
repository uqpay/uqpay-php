<?php

namespace uqpay\payment\sdk;

use Particle\Validator\Rule\CreditCard;
use uqpay\payment\sdk\config\merchantConfig;
use uqpay\payment\sdk\config\paygateConfig;
use uqpay\payment\sdk\config\cashierConfig;
use Particle\Validator\Validator;
use uqpay\payment\sdk\utils\payUtil;
use uqpay\payment\sdk\result\QRResult;
use uqpay\payment\sdk\result\CardResult;
use uqpay\payment\sdk\result\InAppResult;
use uqpay\payment\sdk\result\RefundResult;
use uqpay\payment\sdk\result\QueryResult;
use uqpay\payment\sdk\utils\request;
use uqpay\payment\sdk\utils\httpRequest;

include 'config/paygateConfig.php';
include 'config/merchantConfig.php';
include 'config/cashierConfig.php';
include 'utils/payUtil.php';
include 'result/QRResult.php';
include 'result/CardResult.php';
include 'result/InAppResult.php';
include 'result/RefundResult.php';
include 'result/QueryResult.php';
include 'utils/paymethod.php';
include 'utils/constants.php';


class sdk extends httpRequest
{
    private $paygateConfig;
    private $merchantConfig;
    private $cashierConfig;

    function __construct($paygateConfig = array(), $merchantConfig = array())
    {
        $this->paygateConfig = new paygateConfig();
        $this->merchantConfig = new merchantConfig();
        $this->cashierConfig = new cashierConfig();
    }

    function validatePayData($object, $msg = "pay data invalid for uqpay payment")
    {
        $validator = new Validator;
        $violations = $validator->validate($object);
        if (!$violations->isValid()) {
            throw new \Exception($msg);
        }
        return $violations->isValid();
    }

    function doServerSidePostPay($paramsMap)
    {
        $payUtil = new payUtil();
        $resultMap = $this->httpArrayPost($this->apiUrl(PAYGATE_API_PAY), $paramsMap);
        $payUtil->verifyUqpayNotice($resultMap, $this->paygateConfig);
        return $resultMap;
    }

    function apiUrl($url)
    {
        return $this->paygateConfig->apiRoot . $url;
    }

    function QRCodePayment($pay)
    {
        global $UqpayScanType;
        $this->validatePayData($pay);
        if ($pay["scantype"] == null) throw new \Exception("uqpay qr code payment need Scan Type");
        if (strcmp($pay["scantype"], $UqpayScanType["Merchant"]) == 0 && $pay["identity"] == null) throw new \Exception("uqpay qr code payment need the identity data when scan type is merchant");
        $payUtil = new payUtil();

        $paramsMap = $payUtil->generateDefPayParams($pay, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_SCAN_TYPE] = (string)$pay["scantype"];
        ksort($paramsMap);
        $paramsMap["sign"] = $payUtil->signParams(http_build_query($paramsMap), $this->paygateConfig);
        ksort($paramsMap);
        $result = $this->httpArrayPost($this->apiUrl(PAYGATE_API_PAY), $paramsMap);
        $QRResult = (object)array_merge(array(
            "scanType" => null,
            "QRCodeUrl" => null,
            "QRCode" => null
        ), (array)$result);
        return $QRResult;
    }

    function OnlinePayment($pay)
    {
        $this->validatePayData($pay);
        $payUtil = new payUtil();
        if ($pay["returnUrl"] == null || strcmp($pay["returnUrl"], "") == 0)
            throw new \Exception("uqpay online payment need sync notice url");
        $paramsMap = $payUtil->generateDefPayParams($pay, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_SYNC_NOTICE_URL] = $pay["returnUrl"];
        ksort($paramsMap);
        $payUtil->signParams(http_build_query($paramsMap), $this->paygateConfig);
        return $this->apiUrl(PAYGATE_API_PAY) . "?" . http_build_query($paramsMap);
    }

    function generateCreditCardPayParams($creditCard, $payData)
    {
        $this->validatePayData($creditCard, "credit card info invalid for uqpay credit card payment");
        $this->validatePayData($payData);
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($payData, $this->merchantConfig);
        $paramsMap["creditCard"] = ($payUtil->generateCreditCardPayParams($creditCard));
        ksort($paramsMap);
        $paramsMap["sign"] = $payUtil->signParams(http_build_query($paramsMap), $this->paygateConfig);
        return $paramsMap;
    }

    function CreditCardPayment($creditCard, $payData)
    {
        $result = $this->httpArrayPost($this->apiUrl(PAYGATE_API_PAY), $this->generateCreditCardPayParams($creditCard, $payData));
//        $cardResult = new CardResult($result);
        return json_decode($result);
    }

    function ThreeDSecurePayment($creditCard, $payData)
    {
        if ($payData["returnUrl"] == null || strcmp($payData["returnUrl"], "") == 0)
            throw new \Exception("uqpay 3D secure payment need sync notice url");
        $payUtil = new payUtil();
        $paramsData = $this->generateCreditCardPayParams($creditCard, $payData);
        ksort($paramsData);
        $paramsData["sign"] = $payUtil->signParams(http_build_query($paramsData), $this->paygateConfig);
        return $this->apiUrl(PAYGATE_API_PAY) . "?" . http_build_query($paramsData);
    }


    function InAppPayment($payData)
    {
        if ($payData["client"] == null) throw new \Exception("client type is required for uqpay in-app payment");
        global $paymentSupportClient;
        if (strcmp($payData["client"], $paymentSupportClient["PC_WEB"]) == 0) throw new \Exception("uqpay in-app payment not support pc client");
        $this->validatePayData($payData);
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($payData, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_CLIENT_TYPE] = (string)$payData["client"];
        ksort($paramsData);
        $paramsData["sign"] = $payUtil->signParams(http_build_query($paramsData), $this->paygateConfig);
        $result = $this->doServerSidePostPay($paramsMap);
        return json_decode($result);
    }

    function Refund($refund)
    {
        $this->validatePayData($refund, "refund request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateRefundParams($refund, $this->merchantConfig);
        ksort($paramsData);
        $paramsData["sign"] = $payUtil->signParams(http_build_query($paramsData), $this->paygateConfig);
        $result = $this->httpArrayPost($this->apiUrl(PAYGATE_API_REFUND), $paramsMap);
        return json_decode($result);
    }

    function Cancel($cancel)
    {
        $this->validatePayData($cancel, "cancel payment request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateCancelParams($cancel, $this->merchantConfig);
        $result = $this->httpArrayPost($this->apiUrl(PAYGATE_API_CANCEL), $paramsMap);
        return json_decode($result);
    }

    function Query($query)
    {
        $this->validatePayData($query, "query request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateQueryParams($query, $this->merchantConfig);
        $result = $this->httpArrayPost($this->apiUrl(PAYGATE_API_QUERY), $paramsMap);
        return json_decode($result);
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

class BuilderConfig
{
    private $test;
    public $paygateConfig;
    public $merchantConfig;

    public function paygateConfig($config)
    {
        $this->test = new paygateConfig();
        if ($config == null) throw new \Exception("uqpay paygate config == null");
        if ($config["RSA"] == null) throw new \Exception("uqpay paygate config miss rsa config");
        $this->paygateConfig = (object)array_merge($config, (array)$this->test);
        return $this->paygateConfig;
    }

    public function merchantConfig($config)
    {
        $this->test = new merchantConfig();
        if ($config == null) throw new \Exception("uqpay paygate config == null");
        if ($config["id"] == null) throw new \Exception("uqpay merchant config miss merchant account id");
        $this->merchantConfig = (object)array_merge($config, (array)$this->test);
        return $this->merchantConfig;
    }
}

