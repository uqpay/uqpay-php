<?php

namespace uqpay\payment\sdk\utils;

class payUtil
{
    function generateDefPayParams($payData, $config)
    {
        $paramsMap = array();
        $paramsMap[AUTH_MERCHANT_ID] = (string)$config->id;
        $paramsMap[ORDER_ID] = $payData["orderId"];
        $paramsMap[ORDER_AMOUNT] = (double)$payData["amount"];
        $paramsMap[ORDER_CURRENCY] = $payData["currency"];
        $paramsMap[ORDER_TRANS_NAME] = $payData["transName"];
        $paramsMap[ORDER_DATE] = (string)$payData["date"];
        $paramsMap[PAY_OPTIONS_CLIENT_TYPE] = (string)$payData["client"];
        if ($payData["quantity"] != 0) {
            $paramsMap[ORDER_QUANTITY] = (string)$payData["quantity"];
        }
        if ($payData["storeId"] != 0) {
            $paramsMap[ORDER_STORE_ID] = (string)$payData["storeId"];
        }
        if ($payData["seller"] != 0) {
            $paramsMap[ORDER_SELLER] = (string)$payData["seller"];
        }
        if ($payData["channelInfo"] != null && strcmp($payData["channelInfo"], "") != 0) {
            $paramsMap[ORDER_CHANNEL_INFO] = $payData["channelInfo"];
        }
        if ($payData["extendInfo"] != null && strcmp($payData["extendInfo"], "") != 0) {
            $paramsMap[ORDER_EXTEND_INFO] = $payData["extendInfo"];
        }
        $paramsMap[PAY_OPTIONS_METHOD_ID] = (string)$payData["methodId"];

        $paramsMap[PAY_OPTIONS_TRADE_TYPE] = $payData["tradeType"];
        $paramsMap[PAY_OPTIONS_ASYNC_NOTICE_URL] = $payData["callbackUrl"];
        return $paramsMap;
    }

    function generateCreditCardPayParams($creditCard)
    {
        $paramsMap = array();
        $paramsMap[CREDIT_CARD_FIRST_NAME] = $creditCard["firstName"];
        $paramsMap[CREDIT_CARD_LAST_NAME] = $creditCard["lastName"];
        $paramsMap["cardType"] = $creditCard["cardType"];
        $paramsMap[CREDIT_CARD_CARD_NUM] = $creditCard["cardNum"];
        $paramsMap[CREDIT_CARD_CVV] = $creditCard["cvv"];
        $paramsMap[CREDIT_CARD_EXPIRE_MONTH] = $creditCard["expireMonth"];
        $paramsMap[CREDIT_CARD_EXPIRE_YEAR] = $creditCard["expireYear"];
        $paramsMap[CREDIT_CARD_ADDRESS_COUNTRY] = $creditCard["addressCountry"];
        $paramsMap[CREDIT_CARD_ADDRESS_STATE] = $creditCard["addressState"];
        $paramsMap[CREDIT_CARD_ADDRESS_CITY] = $creditCard["addressCity"];
        $paramsMap[CREDIT_CARD_ADDRESS] = $creditCard["address"];
        $paramsMap[CREDIT_CARD_PHONE] = $creditCard["phone"];
        $paramsMap[CREDIT_CARD_EMAIL] = $creditCard["email"];
        $paramsMap[CREDIT_CARD_ZIP] = $creditCard["zip"];
        return $paramsMap;
    }

    function signParams($data, $config)
    {
        $priKey = file_get_contents($config->rsaConfig->privateKeyPath);
        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);
        //调用openssl内置签名方法，生成签名$sign
        openssl_sign(urldecode($data), $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    function generateRefundParams($refund, $config)
    {
        $paramsMap = array();
        $paramsMap[AUTH_MERCHANT_ID] = (string)$config["Id"];
        $paramsMap[ORDER_ID] = $refund["orderId"];
        $paramsMap[ORDER_AMOUNT] = (string)$refund["amount"];
        $paramsMap[ORDER_DATE] = (string)$refund["date"];
        if ($refund["extendInfo"] != null && strcmp($refund["extendInfo"], "") != 0) {
            $paramsMap[ORDER_EXTEND_INFO] = $refund["extendInfo"];
        }
        $paramsMap[PAY_OPTIONS_TRADE_TYPE] = $refund["tradeType"];
        $paramsMap[PAY_OPTIONS_ASYNC_NOTICE_URL] = $refund["callbackUrl"];
        return $paramsMap;
    }

    function generateCancelParams($cancel, $config)
    {
        $paramsMap = array();
        $paramsMap[AUTH_MERCHANT_ID] = (string)$config["id"];
        $paramsMap[ORDER_ID] = $cancel["orderId"];
        $paramsMap[ORDER_DATE] = (string)$cancel["date"];
        if ($cancel["extendInfo"] != null && strcmp($cancel["extendInfo"], "") != 0) {
            $paramsMap[ORDER_EXTEND_INFO] = $cancel["extendInfo"];
        }
        $paramsMap[PAY_OPTIONS_TRADE_TYPE] = $cancel["tradeType"];
        return $paramsMap;

    }

    function generateQueryParams($query, $config)
    {
        $paramsMap = array();
        $paramsMap[AUTH_MERCHANT_ID] = (string)$config["id"];
        $paramsMap[ORDER_ID] = $query["orderId"];
        $paramsMap[ORDER_DATE] = (string)$query["date"];
        $paramsMap[PAY_OPTIONS_TRADE_TYPE] = $query["tradeType"];
        return $paramsMap;
    }

//throws UnsupportedEncodingException, UqpayRSAException
    function generateCashierLink($cashier, $config)
    {
        $getArray = (array)$cashier;
        $requestArr["merchantId"] = $getArray["merchantId"];
        $requestArr["tradeType"] = $getArray["tradeType"];
        $requestArr["date"] = $getArray["date"];
        $requestArr["orderId"] = $getArray["orderId"];
        if ($getArray["amount"] > 0) {
            $requestArr["amount"] = $getArray["amount"];
        }
        $requestArr["currency"] = $getArray["currency"];
        $requestArr["transName"] = $getArray["transName"];
        $requestArr["callbackUrl"] = $getArray["callbackUrl"];
        $requestArr["returnUrl"] = $getArray["returnUrl"];
        if ($getArray["quantity"] > 0) {
            $requestArr["quantity"] = $getArray["quantity"];
        }
        if ($getArray["storeId"] > 0) {
            $requestArr["storeId"] = $getArray["storeId"];
        }
        if ($getArray["seller"] > 0) {
            $requestArr["seller"] = (string)$getArray["seller"];
        }
        if ($getArray["channelInfo"] != null && strcmp($getArray["channelInfo"], "") !== 0) {
            $requestArr["channelInfo"] = $getArray["channelInfo"];
        }
        if ($getArray["extendInfo"] != null && strcmp($getArray["extendInfo"], "") !== 0) {
            $requestArr["extendInfo"] = $getArray["extendInfo"];
        }
        ksort($requestArr);
        $requestArr["sign"] = $this->signParams(http_build_query($requestArr), $config);
        ksort($requestArr);
        $urlQuery = http_build_query($requestArr);
        return $config->apiRoot . "?" . $urlQuery;
    }

//throws UnsupportedEncodingException, UqpayRSAException, UqpayResultVerifyException
    function verifyUqpayNotice($paramsMap, $config)
    {
        if ($paramsMap[AUTH_SIGN] == null)
            throw new \Exception("The payment result is not a valid uqpay result, sign data is missing", $paramsMap);
        $needVerifyParams = array();
        foreach ($paramsMap as $k => $v) {
            if ($k != AUTH_SIGN) {
                $needVerifyParams[$k] = (string)$v;
            }
        }
        ksort($needVerifyParams);
        $paramsQuery = urldecode(http_build_query($needVerifyParams));
        $RSAUtil = new RSAUtil;
        $verify = $RSAUtil->verify($paramsQuery, (string)$paramsMap[AUTH_SIGN], $config->rsaConfig->publicKeyPath);
        if (!(boolean)$verify) throw new \Exception("The payment result is invalid, be sure is from the UQPAY server", $paramsMap);
    }

}