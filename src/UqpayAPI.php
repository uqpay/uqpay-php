<?php

namespace uqpay\payment\sdk;

use uqpay\payment\sdk\config\AppgateConfig;
use uqpay\payment\sdk\dto\common\BaseJsonRequestDTO;
use uqpay\payment\sdk\dto\emvco\EmvcoCreateDTO;
use uqpay\payment\sdk\dto\emvco\EmvcoGetPayloadDTO;
use uqpay\payment\sdk\dto\enroll\EnrollOrder;
use uqpay\payment\sdk\config\merchantConfig;
use uqpay\payment\sdk\config\paygateConfig;
use uqpay\payment\sdk\config\cashierConfig;
use Particle\Validator\Validator;
use uqpay\payment\sdk\dto\exchangeRate\ExchangeRateQueryDTO;
use uqpay\payment\sdk\dto\merchant\MerchantRegisterDTO;
use uqpay\payment\sdk\dto\preAuth\PreAuthOrder;
use uqpay\payment\sdk\dto\result\TransResult;
use uqpay\payment\sdk\utils\payMethod;
use uqpay\payment\sdk\utils\payUtil;
use uqpay\payment\sdk\utils\httpRequest;
use uqpay\payment\sdk\dto\common\AuthDTO;
use uqpay\payment\sdk\dto\common\BankCardDTO;
use uqpay\payment\sdk\dto\enroll\VerifyOrder;
use uqpay\payment\sdk\vo\UqpayCashier;

include 'utils/payUtil.php';
include 'utils/constants.php';


class UqpayAPI extends httpRequest
{
    private $paygateConfig;
    private $merchantConfig;
    private $cashierConfig;
    private $appgateConfig;
    private $auth;

    function __construct(paygateConfig $paygateConfig, merchantConfig $merchantConfig, cashierConfig $cashierConfig, AppgateConfig $appgateConfig)
    {
        $this->paygateConfig = $paygateConfig;
        $this->merchantConfig = $merchantConfig;
        $this->cashierConfig = $cashierConfig;
        $this->appgateConfig = $appgateConfig;
        $this->auth = new authDTO();
        $this->auth->agentId = $merchantConfig->agentId;
        $this->auth->merchantId = $merchantConfig->id;
    }

    private function validatePayData($object, $msg = "pay data invalid for uqpay payment")
    {
        $validator = new Validator;
        $violations = $validator->validate($object);
        if (!$violations->isValid()) {
            throw new \Exception($msg);
        }
        return $violations->isValid();
    }

    public function updateMerchantId($merchantId)
    {
        $this->auth->merchantId = $merchantId;
    }

    public function paygateApiUrl($url)
    {
        return $this->paygateConfig->apiRoot + $url;
    }

    public function appgateApiUrl($url)
    {
        return $this->appgateConfig->apiRoot + $url;
    }

    private function setAuthForJsonParams(BaseJsonRequestDTO $jsonParams)
    {
        $jsonParams->merchantId($this->auth->merchantId);
        $jsonParams->agentId($this->auth->agentId);
    }

    private function generatePayParamsMap($args)
    {
        $paygateParams = (array)$args;
        $paramsMap = array_combine($paygateParams, (array)$this->auth);
        $payUtil = new payUtil();
        ksort($paramsMap);
        $paramsMap = $payUtil->signParams($paramsMap, $this->paygateConfig);
        ksort($paramsMap);
        return $paramsMap;
    }

    private function directFormPost($url, $paramsMap)
    {
        $payUtil = new payUtil();
        ksort($paramsMap);
        $paramsMap = $payUtil->signParams($paramsMap, $this->paygateConfig);
        ksort($paramsMap);
        $resultMap = $this->httpArrayPost($url, $paramsMap);
        $payUtil->verifyUqpayNotice($resultMap, $this->paygateConfig);
        return $resultMap;
    }

    private function directJsonPost($url, $paramsMap)
    {
        $payUtil = new payUtil();
        ksort($paramsMap);
        $paramsMap = $payUtil->signParams($paramsMap, $this->paygateConfig);
        ksort($paramsMap);
        $resultMap = $this->httpJsonPost($url, $paramsMap);
//        $payUtil->verifyUqpayNotice($resultMap, $this->paygateConfig);
        return $resultMap;
    }

    private function redirectPost($url, $paramsMap)
    {
        $payUtil = new payUtil();
        ksort($paramsMap);
        $paramsMap = $payUtil->signParams($paramsMap, $this->paygateConfig);
        ksort($paramsMap);
        $resultMap = $this->httpRedirectArrayPost($url, $paramsMap);
        return $resultMap;
    }

//    private function doServerSidePostPay($paramsMap)
//    {
//        return $this->doServerSidePost($this->apiUrl(PAYGATE_API_PAY), $paramsMap);
//    }

    private function apiUrl($url)
    {
        return $this->paygateConfig->apiRoot . $url;
    }

    private function QRCodePayment($pay, $url)
    {
        $payMethod = new payMethod();
        $UqpayScanType = $payMethod->UqpayScanType;
        $this->validatePayData($pay);
        if ($pay["scantype"] == null) throw new \Exception("uqpay qr code payment need Scan Type");
        if (strcmp($pay["scantype"], $UqpayScanType["Merchant"]) == 0 && $pay["identity"] == null) throw new \Exception("uqpay qr code payment need the identity data when scan type is merchant");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($pay, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_SCAN_TYPE] = (string)$pay["scantype"];
        $result = $this->directFormPost($url, $paramsMap);
        return $result;
    }

    private function OfflineQRCodePayment($pay, $url)
    {
        if ($pay["identity"] == null)
            throw new \Exception("uqpay offline qr code payment need the identity data");
        if ($pay["merchantCity"] == null) {
            throw new \Exception("uqpay offline qr code payment need the merchant city data");
        }
        if ($pay["terminalID"] == null) {
            throw new \Exception("uqpay offline qr code payment need the terminal id data");
        }
        $paramsMap = $this->generatePayParamsMap($pay);
        return $this->directFormPost($url, $paramsMap);
    }

    private function RedirectPayment($payOptions, $url)
    {
        if ($payOptions["returnUrl"] == null || $payOptions["returnUrl"] == "") {
            throw new \Exception("uqpay online payment need sync notice url");
        }
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($payOptions, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_SYNC_NOTICE_URL] = $payOptions["returnUrl"];
        $transResult = new TransResult($paramsMap, $url);
        return $transResult;
    }

    private function generateCreditCardPayParams($creditCard, $payData)
    {
        $this->validatePayData($creditCard, "credit card info invalid for uqpay credit card payment");
        $this->validatePayData($payData);
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($payData, $this->merchantConfig);
        $paramsMap["creditCard"] = ($payUtil->generateCreditCardPayParams($creditCard));
        return $paramsMap;
    }

    private function MerchantHostPayment($pay, $bankCard, $url)
    {
        $paramsMap = $this->generateCreditCardPayParams($bankCard, $pay);
        return $this->directFormPost($url, $paramsMap);
    }

    private function ServerHostPayment($pay, $bankCard, $url)
    {
        $paramsMap = $this->generateCreditCardPayParams($bankCard, $pay);
        return $this->directFormPost($url, $paramsMap);
    }

    private function CreditCardPayment($creditCard, $payData, $url)
    {
        $result = $this->directFormPost($url, $this->generateCreditCardPayParams($creditCard, $payData));
        return $result;
    }

    private function ThreeDSecurePayment($creditCard, $payData, $url)
    {
        if ($payData["returnUrl"] == null || strcmp($payData["returnUrl"], "") == 0)
            throw new \Exception("uqpay 3D secure payment need sync notice url");
        $paramsData = $this->generateCreditCardPayParams($creditCard, $payData);
        $transResult = new TransResult($paramsData, $url);
        return $transResult;
    }


    private
    function InAppPayment($payData, $url)
    {
        if ($payData["client"] == null) throw new \Exception("client type is required for uqpay in-app payment");
        $payMethod = new payMethod();
        $paymentSupportClient = $payMethod->paymentSupportClient;
        if (strcmp($payData["client"], $paymentSupportClient["PC_WEB"]) == 0) throw new \Exception("uqpay in-app payment not support pc client");
        $this->validatePayData($payData);
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($payData, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_CLIENT_TYPE] = (string)$payData["client"];
        $paramsMap[PAY_OPTIONS_SYNC_NOTICE_URL] = $payData["returnUrl"];
        ksort($paramsMap);
        $paramsMap = $payUtil->signParams($paramsMap, $this->paygateConfig);
        $result = $this->directFormPost($url, $paramsMap);
        return $result;
    }

    private function PreAuthFinish(PreAuthOrder $order)
    {
        if ($order->uqOrderId <= 0) {
            throw new \Exception("uqpay order id is required");
        }
        $paramsMap = array();
        $paramsMap["transName"] = $order->transName;
        $paramsMap["uqOrderId"] = $order->uqOrderId;
        return $this->directFormPost($this->apiUrl(PAYGATE_API_PRE_AUTH), $paramsMap);
    }

    private function EnrollCard(EnrollOrder $order, BankCardDTO $bankCardDTO)
    {
        $paramsMap = array();
        $paramsMap["orderId"] = $order->orderId;
        $paramsMap["date"] = $order->date;
        $paramsMap["verifyCode"] = $order->verifyCode;
        $paramsMap["codeOrderId"] = $order->codeOrderId;
        return $this->directFormPost($this->apiUrl(PAYGATE_API_ENROLL), $paramsMap);
    }

    private function VerifyPhone(VerifyOrder $order)
    {
        $payMethodObject = new payMethod();
        $UqpayTradeType = $payMethodObject->UqpayTradeType;
        $order->tradeType = $UqpayTradeType["verifycode"];
        return $this->directFormPost($this->apiUrl(PAYGATE_API_VERIFY), $order);
    }

    private
    function Refund($refund)
    {
        $this->validatePayData($refund, "refund request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateRefundParams($refund, $this->merchantConfig);
        ksort($paramsMap);
        $paramsMap = $payUtil->signParams($paramsMap, $this->paygateConfig);
        $result = $this->directFormPost($this->apiUrl(PAYGATE_API_REFUND), $paramsMap);
        return $result;
    }

    private
    function Cancel($cancel)
    {
        $this->validatePayData($cancel, "cancel payment request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateCancelParams($cancel, $this->merchantConfig);
        $result = $this->directFormPost($this->apiUrl(PAYGATE_API_CANCEL), $paramsMap);
        return $result;
    }

    private
    function Query($query)
    {
        $this->validatePayData($query, "query request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateQueryParams($query, $this->merchantConfig);
        $result = $this->directFormPost($this->apiUrl(PAYGATE_API_QUERY), $paramsMap);
        return $result;
    }


//===========================================
// Pay API
//===========================================

    /**
     * @param $order
     * @return mixed|null|TransResult
     */


    function pay($order)
    {

        $payMethodObject = new payMethod();
        $UqpayTradeType = $payMethodObject->UqpayTradeType;
        $order["tradeType"] = $UqpayTradeType["pay"];
        $this->validatePayData($order);
        $payMethod = $payMethodObject->payMethod();
        $scenes = $payMethod[$order["methodId"]];
        switch ($scenes) {
            case "QRCode":
                return $result = $this->QRCodePayment($order, $this->apiUrl(PAYGATE_API_PAY));
                break;
            case "OfflineQRCode":
                return $this->OfflineQRCodePayment($order, $this->paygateApiUrl(PAYGATE_API_PAY));
            case "OnlinePay":
                return $this->RedirectPayment($order, $this->paygateApiUrl(PAYGATE_API_PAY));
            case "InApp":
                return $result = $this->InAppPayment($order, $this->apiUrl(PAYGATE_API_PAY));
                break;
            case "CreditCard":
                switch ($order->methodId) {
                    case $payMethodObject->AMEX:
                    case $payMethodObject->JCB:
                    case $payMethodObject->Master:
                    case $payMethodObject->VISA:
                        $this->validatePayData($order["bankCard"]);
                        break;
                    default:
                        $this->validatePayData($order["bankCard"]);
                }
                return $this->CreditCardPayment($order, $order["bankCard"], $this->apiUrl(PAYGATE_API_PAY));
            case "ThreeDCreditCard":
                $this->validatePayData($order["bankCard"]);
                return $this->ThreeDSecurePayment($order, $order["bankCard"], $this->apiUrl(PAYGATE_API_PAY));
            case "MerchantHost":
                return $this->MerchantHostPayment($order, $order["merchantHost"], $this->apiUrl(PAYGATE_API_PAY));
            case "ServerHost":
                return $this->ServerHostPayment($order, $order["serverHost"], $this->apiUrl(PAYGATE_API_PAY));
            default:
                $result = null;
        }
        return $result;
    }



//===========================================
// PreAuth API
//===========================================

    /**
     * check the code ,it's easy to understand.
     * be sure set the right trade type
     * @param order required
     * @param bankCard optional
     * @return
     * @throws UqpayRSAException
     * @throws UqpayResultVerifyException
     * @throws UqpayPayFailException
     * @throws IOException
     * throws UqpayRSAException, UqpayResultVerifyException, UqpayPayFailException, IOException
     */
    function preAuth(PreAuthOrder $order)
    {
        $this->validatePayData($order);
        switch ($order->tradeType) {
            case "preauth":
                $payMethodObject = new payMethod();
                $payMethod = $payMethodObject->payMethod();
                $scenes = $payMethod[$order["methodId"]];
                switch ($scenes) {
                    case "InApp":
                        return $this->InAppPayment($order, $this->paygateApiUrl(PAYGATE_API_PRE_AUTH));
                    case "CreditCard":
                        $this->validatePayData($order["bankCard"]);
                        return $this->CreditCardPayment($order, $order["bankCard"], $this->paygateApiUrl(PAYGATE_API_PRE_AUTH));
                    case "OnlinePay":
                        return $this->ThreeDSecurePayment($order, $order["bankCard"], $this->paygateApiUrl(PAYGATE_API_PRE_AUTH));
                    case "MerchantHost":
                        return $this->MerchantHostPayment($order, $order["merchantHost"], $this->apiUrl(PAYGATE_API_PRE_AUTH));
                    case "ServerHost":
                        return $this->MerchantHostPayment($order, $order["serverHost"], $this->apiUrl(PAYGATE_API_PRE_AUTH));
                    default:
                        return null;
                }
            case "preauthcc":
            case "preauthcomplete":
            case "preauthcancel":
                return $this->PreAuthFinish($order);
            default:
                return null;
        }
    }



    //===========================================
    // Enroll API
    //===========================================

    function enroll(EnrollOrder $order, BankCardDTO $bankCardDTO)
    {
        $payMethodObject = new payMethod();
        $UqpayTradeType = $payMethodObject->UqpayTradeType;
        $order->tradeType = $UqpayTradeType["enroll"];

        $payMethodObject = new payMethod();
        $payMethod = $payMethodObject->payMethod();
        $scenes = $payMethod[$order["methodId"]];
        switch ($scenes) {
            case "MerchantHost":
                return $this->EnrollCard($order, $bankCardDTO);
            case "ServerHost":
                return $this->EnrollCard($order, $bankCardDTO);
            default:
                return null;
        }
    }

    function verify(VerifyOrder $order)
    {
        $payMethodObject = new payMethod();
        $UqpayTradeType = $payMethodObject->UqpayTradeType;
        $order->tradeType = $UqpayTradeType["verifycode"];
        return $this->VerifyPhone($order);
    }

    //===========================================
    // Merchant Register API
    //===========================================

public function register(MerchantRegisterDTO $registerDTO){
$this->setAuthForJsonParams($registerDTO);
return $this->directJsonPost($registerDTO,$this->appgateApiUrl(APPGATE_API_REGISTER));
}

//===========================================
// EMVCO QRCode API
//===========================================

public function createQRCode(EmvcoCreateDTO $createDTO){
    $this->setAuthForJsonParams($createDTO);
    return $this->directJsonPost($createDTO,$this->appgateApiUrl(APPGATE_API_EMVCO_CREATE));
  }

  public function getQRCodePayload(EmvcoGetPayloadDTO $payloadDTO){
    $this->setAuthForJsonParams($payloadDTO);
    return $this->directJsonPost($payloadDTO,$this->appgateApiUrl(APPGATE_API_EMVCO_PAYLOAD));
  }

  //===========================================
  // UQPAY Public Resource API
  //===========================================

  public function queryExchangeRate(ExchangeRateQueryDTO $queryDTO){
    $this->setAuthForJsonParams($queryDTO);
    return $this->directJsonPost($queryDTO, $this->appgateApiUrl(APPGATE_API_RES_EXCHANGE_RATE));
  }

  //===========================================
  // Cashier API
  //===========================================
  public function generateCashierLink(UqpayCashier $cashier)
     {
    $paramsMap = $cashier->getParamsMap();
         $payUtil = new payUtil();
         ksort($paramsMap);
         $paramsMap = $payUtil->signParams($paramsMap, $this->cashierConfig);
    return $this->cashierConfig['apiRoot'] ."?" . http_build_query($paramsMap);
  }


//set方法
    public
    function __set($name, $value)
    {
        $this->$name = $value;
    }

//get方法
    public
    function __get($name)
    {
        if (!isset($this->$name)) {
            //未设置
            $this->$name = "";
        }
        return $this->$name;
    }
}
