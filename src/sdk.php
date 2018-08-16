<?php

namespace sdk;

use sdk\config\merchantConfig;
use sdk\config\paygateConfig;
use sdk\config\cashierConfig;
use Particle\Validator\Validator;
use sdk\util\payUtil;
use sdk\result\QRResult;
use sdk\result\CardResult;
use sdk\result\InAppResult;
use sdk\result\RefundResult;
use sdk\result\QueryResult;

include 'config/paygateConfig.php';
include 'config/merchantConfig.php';
include 'config/cashierConfig.php';
include 'utils/payUtil.php';
include 'result/QRResult.php';
include 'result/CardResult.php';
include 'result/InAppResult.php';
include 'result/RefundResult.php';
include 'result/QueryResult.php';


class sdk extends core
{
    private $paygateConfig;
    private $merchantConfig;
    private $cashierConfig;

    function __construct($paygateConfig = array(), $merchantConfig = array())
    {
        $this->paygateConfig = new paygateConfig();
        $this->merchantConfig = new merchantConfig();
        $this->cashierConfig = new cashierConfig();
//        $builder = new BuilderConfig();
//        $builder->paygateConfig($paygateConfig);
//        $builder->merchantConfig($merchantConfig);
//        $this->home($builder);
    }


//    function home($builder)
//    {
//        $this->paygateConfig = (object)array_merge((array)$this->paygateConfig, (array)$builder->paygateConfig);
//        $this->merchantConfig = (object)array_merge((array)$this->merchantConfig, (array)$builder->merchantConfig);
//    }

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

//throws IOException, UqpayRSAException, UqpayResultVerifyException
    function QRCodePayment($pay)
    {
        global $UqpayScanType;
        $this->validatePayData($pay);
        if ($pay["scantype"] == null) throw new \Exception("uqpay qr code payment need Scan Type");
        if (strcmp($pay["scantype"], $UqpayScanType["Merchant"]) == 0 && $pay["identity"] == null) throw new \Exception("uqpay qr code payment need the identity data when scan type is merchant");
        $payUtil = new payUtil();

        $paramsMap = $payUtil->generateDefPayParams($pay, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_SCAN_TYPE] = (string)$pay["scantype"];
        $result = $this->httpArrayPost($this->apiUrl(PAYGATE_API_PAY),$paramsMap);
        dump($result);
        return new QRResult($this->httpArrayPost($this->apiUrl(PAYGATE_API_PAY), $paramsMap));
    }

//throws IOException, UqpayRSAException
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
        return $paramsMap;
    }

//throws IOException, UqpayRSAException, UqpayResultVerifyException
    function CreditCardPayment($creditCard, $payData)
    {
        return new CardResult($this->httpArrayPost($this->apiUrl(PAYGATE_API_PAY), $this->generateCreditCardPayParams($creditCard, $payData)));
    }

//throws IOException, UqpayRSAException
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


//throws IOException, UqpayRSAException, UqpayResultVerifyException
    function InAppPayment($payData)
    {
        if ($payData["client"] == null) throw new \Exception("client type is required for uqpay in-app payment");
        global $paymentSupportClient;
        if (strcmp($payData["client"], $paymentSupportClient["PC_WEB"]) == 0) throw new \Exception("uqpay in-app payment not support pc client");
        $this->validatePayData($payData);
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($payData, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_CLIENT_TYPE] = (string)$payData["client"];
        return new InAppResult($this->doServerSidePostPay($paramsMap));
    }

//throws IOException, UqpayRSAException, UqpayResultVerifyException
    function Refund($refund) {
        $this->validatePayData($refund, "refund request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateRefundParams($refund, $this->merchantConfig);
        return new RefundResult($this->httpArrayPost($this->apiUrl(PAYGATE_API_REFUND),$paramsMap ));
    }


//throws UqpayRSAException, IOException, UqpayResultVerifyException
    function Cancel($cancel)  {
        $this->validatePayData($cancel, "cancel payment request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateCancelParams($cancel, $this->merchantConfig);
        return $this->httpArrayPost($this->apiUrl(PAYGATE_API_CANCEL),$paramsMap);
    }

//throws UqpayRSAException, IOException, UqpayResultVerifyException
    function Query($query) {
        $this->validatePayData($query, "query request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateQueryParams($query, $this->merchantConfig);
        return new QueryResult($this->httpArrayPost($this->apiUrl(PAYGATE_API_QUERY),$paramsMap));
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

