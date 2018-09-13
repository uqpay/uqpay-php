<?php

namespace uqpay\payment\sdk;

use uqpay\payment\sdk\dto\enroll\EnrollOrder;
use uqpay\payment\sdk\dto\enroll\VerifyOrder;
use uqpay\payment\sdk\dto\PaygateParams;
use uqpay\payment\sdk\dto\PayOptions;
use Particle\Validator\Rule\CreditCard;
use uqpay\payment\sdk\config\merchantConfig;
use uqpay\payment\sdk\config\paygateConfig;
use uqpay\payment\sdk\config\cashierConfig;
use Particle\Validator\Validator;
use uqpay\payment\sdk\dto\common\BankCardCompatibleDTO;
use uqpay\payment\sdk\dto\common\CreditCardDTO;
use uqpay\payment\sdk\dto\pay\PayOrder;
use uqpay\payment\sdk\dto\preAuth\PreAuthOrder;
use uqpay\payment\sdk\utils\payMethod;
use uqpay\payment\sdk\utils\payUtil;
use uqpay\payment\sdk\result\QRResult;
use uqpay\payment\sdk\result\CardResult;
use uqpay\payment\sdk\result\InAppResult;
use uqpay\payment\sdk\result\RefundResult;
use uqpay\payment\sdk\result\QueryResult;
use uqpay\payment\sdk\utils\request;
use uqpay\payment\sdk\utils\httpRequest;
use uqpay\payment\sdk\dto\authDTO;
use uqpay\payment\sdk\dto\common\BankCardDTO;

include 'utils/payUtil.php';
include 'result/QRResult.php';
include 'result/CardResult.php';
include 'result/InAppResult.php';
include 'result/RefundResult.php';
include 'result/QueryResult.php';
include 'utils/payMethod.php';
include 'utils/constants.php';


class sdk extends httpRequest
{
    private $paygateConfig;
    private $merchantConfig;
    private $cashierConfig;
    private $auth;

    function __construct(paygateConfig $paygateConfig, merchantConfig $merchantConfig, cashierConfig $cashierConfig)
    {
        $this->paygateConfig = $paygateConfig;
        $this->merchantConfig = $merchantConfig;
        $this->cashierConfig = $cashierConfig;
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

    private function doServerSidePost($url, $paramsMap)
    {
        $payUtil = new payUtil();
        ksort($paramsMap);
        $paramsMap["sign"] = $payUtil->signParams(http_build_query($paramsMap), $this->paygateConfig);
        ksort($paramsMap);
        $resultMap = $this->httpArrayPost($url, $paramsMap);
        $payUtil->verifyUqpayNotice($resultMap, $this->paygateConfig);
        return $resultMap;
    }

    private function redirectPost($url, $paramsMap)
    {
        $payUtil = new payUtil();
        ksort($paramsMap);
        $paramsMap["sign"] = $payUtil->signParams(http_build_query($paramsMap), $this->paygateConfig);
        ksort($paramsMap);
        $resultMap = $this->httpArrayPost($url, $paramsMap);
        return $resultMap;
    }

    private function doServerSidePostPay($paramsMap)
    {
        return $this->doServerSidePost($this->apiUrl(PAYGATE_API_PAY), $paramsMap);
    }

    private function apiUrl($url)
    {
        return $this->paygateConfig->apiRoot . $url;
    }

    private function QRCodePayment($pay,$url)
    {
        global $UqpayScanType;
        $this->validatePayData($pay);
        if ($pay["scantype"] == null) throw new \Exception("uqpay qr code payment need Scan Type");
        if (strcmp($pay["scantype"], $UqpayScanType["Merchant"]) == 0 && $pay["identity"] == null) throw new \Exception("uqpay qr code payment need the identity data when scan type is merchant");
        $payUtil = new payUtil();

        $paramsMap = $payUtil->generateDefPayParams($pay, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_SCAN_TYPE] = (string)$pay["scantype"];
        $result = $this->doServerSidePost($url,$paramsMap);
//        $QRResult = new QRResult($result);
        return $result;
    }


    private function RedirectPayment($payOptions, $url)
    {
        if ($payOptions->returnUrl == null || $payOptions->returnUrl == "") {
            throw new \Exception("uqpay online payment need sync notice url");
        }
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($payOptions, $this->merchantConfig);
        return $this->redirectPost($url, $paramsMap);
    }

    private
    function OnlinePayment($pay)
    {
        $this->validatePayData($pay);
        $payUtil = new payUtil();
        if ($pay["returnUrl"] == null || strcmp($pay["returnUrl"], "") == 0)
            throw new \Exception("uqpay online payment need sync notice url");
        $paramsMap = $payUtil->generateDefPayParams($pay, $this->merchantConfig);
        $paramsMap[PAY_OPTIONS_SYNC_NOTICE_URL] = $pay["returnUrl"];
        ksort($paramsMap);
        $paramsMap["sign"] = $payUtil->signParams(http_build_query($paramsMap), $this->paygateConfig);
        return $this->apiUrl(PAYGATE_API_PAY) . "?" . http_build_query($paramsMap);
    }

    private
    function generateCreditCardPayParams($creditCard, $payData)
    {
        $this->validatePayData($creditCard, "credit card info invalid for uqpay credit card payment");
        $this->validatePayData($payData);
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateDefPayParams($payData, $this->merchantConfig);
        $paramsMap["creditCard"] = ($payUtil->generateCreditCardPayParams($creditCard));
        return $paramsMap;
    }

    private function MerchantHostPayment($pay,$bankCard, $url)
    {
        global $BankCardType;
        if ($bankCard->cardType == $BankCardType["Credit"]) {
            if ($bankCard->expireMonth == null || strcmp($bankCard->expireMonth, "") == 0 || $bankCard->expireYear == null || strcmp($bankCard->expireYear, "") == 0) {
                throw new \Exception("uqpay merchant host payment if the card type is credit, the expire date info is required");
            }
        }
        $paramsMap = $this->generateCreditCardPayParams($bankCard, $pay);
        return $this->doServerSidePost($url, $paramsMap);
    }

    private function CreditCardPayment($creditCard, $payData, $url)
    {
        $result = $this->doServerSidePost($url, $this->generateCreditCardPayParams($creditCard, $payData));
//        $cardResult = new CardResult($result);
        return $result;
    }

    private function ThreeDSecurePayment($creditCard, $payData, $url)
    {
        if ($payData["returnUrl"] == null || strcmp($payData["returnUrl"], "") == 0)
            throw new \Exception("uqpay 3D secure payment need sync notice url");
        $paramsData = $this->generateCreditCardPayParams($creditCard, $payData);
        return $this->redirectPost($url, $paramsData);

//        return $this->apiUrl(PAYGATE_API_PAY) . "?" . http_build_query($paramsData);
    }


    private
    function InAppPayment($payData, $url)
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
        $result = $this->doServerSidePost($url, $paramsMap);
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
        return $this->doServerSidePost($this->apiUrl(PAYGATE_API_PRE_AUTH), $paramsMap);
    }

    private function EnrollCard(EnrollOrder $order)
    {
        $paramsMap = array();
        $paramsMap["orderId"] = $order->orderId;
        $paramsMap["date"] = $order->date;
        $paramsMap["verifyCode"] = $order->verifyCode;
        $paramsMap["codeOrderId"] = $order->codeOrderId;
        return $this->doServerSidePost($this->apiUrl(PAYGATE_API_ENROLL), $paramsMap);
    }

    private function VerifyPhone(VerifyOrderr $order)
    {
        $paramsMap = array();
        $paramsMap["orderId"] = $order->orderId;
        $paramsMap["date"] = $order->date;
        $paramsMap["verifyCode"] = $order->verifyCode;
        $paramsMap["codeOrderId"] = $order->codeOrderId;
        return $this->doServerSidePost($this->apiUrl(PAYGATE_API_VERIFY), $paramsMap);
    }

    private
    function Refund($refund)
    {
        $this->validatePayData($refund, "refund request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateRefundParams($refund, $this->merchantConfig);
        ksort($paramsData);
        $paramsData["sign"] = $payUtil->signParams(http_build_query($paramsData), $this->paygateConfig);
        $result = $this->doServerSidePost($this->apiUrl(PAYGATE_API_REFUND), $paramsMap);
        return $result;
    }

    private
    function Cancel($cancel)
    {
        $this->validatePayData($cancel, "cancel payment request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateCancelParams($cancel, $this->merchantConfig);
        $result = $this->doServerSidePost($this->apiUrl(PAYGATE_API_CANCEL), $paramsMap);
        return $result;
    }

    private
    function Query($query)
    {
        $this->validatePayData($query, "query request data invalid for uqpay order operation");
        $payUtil = new payUtil();
        $paramsMap = $payUtil->generateQueryParams($query, $this->merchantConfig);
        $result = $this->doServerSidePost($this->apiUrl(PAYGATE_API_QUERY), $paramsMap);
        return $result;
    }


//===========================================
// Pay API
//===========================================

    /**
     * this pay api support those pay methods which no need input other params(get from the consumers, eg. credit card info)
     *
     * @param order
     * @return
     * @throws UqpayRSAException
     * @throws UqpayResultVerifyException
     * @throws UqpayPayFailException
     * @throws IOException
     * throws UqpayRSAException, UqpayResultVerifyException, UqpayPayFailException, IOException
     */

    function __call($name, $args)
    {
        if ($name == 'pay') {
            $i = count($args);
            if (method_exists($this, $f = 'pay' . $i)) {
                call_user_func_array(array($this, $f), $args);
            }
        }
    }

    function pay1(PayOrder $order)
    {
        global $UqpayTradeType;
        $order->tradeType = $UqpayTradeType->pay;
        $this->validatePayData($order);
        global $payMethod;
        $scenes = $payMethod[$order->methodId];
        switch ($scenes) {
            case "QRCode":
                return $this->QRCodePayment($order, $this->apiUrl(PAYGATE_API_PAY));
            case "RedirectPay":
                return $this->RedirectPayment($order, $this->apiUrl(PAYGATE_API_PAY));
            case "InApp":
                return $this->InAppPayment($order, $this->apiUrl(PAYGATE_API_PAY));
            default:
                return null;
        }
    }

    /**
     * this pay api for moment just support the bank card payment
     * for the merchant host , the bank card required params see the {@link BankCardCompatibleDTO}
     *
     * @param order required
     * @param bankCard required
     * @return
     * @throws UqpayRSAException
     * @throws UqpayResultVerifyException
     * @throws UqpayPayFailException
     * @throws IOException
     * throws UqpayRSAException, UqpayResultVerifyException, UqpayPayFailException, IOException
     */
    function pay2(PayOrder $order, BankCardDTO $bankCard)
    {
        global $UqpayTradeType;
        $order->tradeType = $UqpayTradeType->pay;
        $this->validatePayData($order);
        $PayMethod = new payMethod();
        global $payMethod;
        $scenes = $payMethod[$order->methodId];
        $creditCardDTO = new CreditCardDTO();
        $bankCardCompatibleDTO = new BankCardCompatibleDTO();
        switch ($scenes) {
            case "CreditCard":
                switch ($order->methodId) {
                    case $PayMethod->AMEX:
                    case $PayMethod->JCB:
                    case $PayMethod->Master:
                    case $PayMethod->VISA:
                        $this->validatePayData($bankCard);
                        break;
                    default:
                        $this->validatePayData($creditCardDTO->valueOf($bankCard));
                }
                return $this->CreditCardPayment($order, $bankCard, $this->apiUrl(PAYGATE_API_PAY));
            case "ThreeDCreditCard":
                $this->validatePayData($bankCard);
                return $this->ThreeDSecurePayment($order, $bankCard, $this->apiUrl(PAYGATE_API_PAY));
            case "MerchantHost":
                return $this->MerchantHostPayment($order, $bankCardCompatibleDTO->valueOf($bankCard), $this->apiUrl(PAYGATE_API_PAY));
            default:
                return null;
        }
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
    function preAuth(PreAuthOrder $order, BankCardDTO $bankCard)
    {
        $this->validatePayData($order);
        $bankCardCompatibleDTO = new BankCardCompatibleDTO();
        switch ($order->tradeType) {
            case "preauth":
                global $payMethod;
                $scenes = $payMethod[$order->methodId];
                switch ($scenes) {
                    case "InApp":
                        return $this->InAppPayment($order, $this->apiUrl(PAYGATE_API_PRE_AUTH));
                    case "CreditCard":
                        $this->validatePayData($bankCard);
                        return $this->CreditCardPayment($order, $bankCard, $this->apiUrl(PAYGATE_API_PRE_AUTH));
                    case "RedirectPay":
                        $this->validatePayData($bankCard);
                        return $this->ThreeDSecurePayment($order, $bankCard, $this->apiUrl(PAYGATE_API_PRE_AUTH));
                    case "MerchantHost":
                        return $this->MerchantHostPayment($order, $bankCardCompatibleDTO->valueOf($bankCard), $this->apiUrl(PAYGATE_API_PRE_AUTH));
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
