<?php

define("AUTH_SIGN","sign");
define("AUTH_MERCHANT_ID","merchantid");
define("PAY_OPTIONS_TRADE_TYPE","tradetype");
define("PAY_OPTIONS_METHOD_ID","methodid");

define("PAY_OPTIONS_SCAN_TYPE","scantype");
define("PAY_OPTIONS_ASYNC_NOTICE_URL","callbackurl");
define("PAY_OPTIONS_SYNC_NOTICE_URL","returnurl");
define("PAY_OPTIONS_CLIENT_TYPE","clienttype");

define("ORDER_ID","orderid");
define("ORDER_AMOUNT","amount");
define("ORDER_CURRENCY", "currency");
define("ORDER_TRANS_NAME","transname");
define("ORDER_DATE", "date");
define("ORDER_QUANTITY", "quantity");
define("ORDER_STORE_ID", "storeid");
define("ORDER_SELLER", "seller");
define("ORDER_CHANNEL_INFO", "channelinfo");
define("ORDER_EXTEND_INFO", "extendinfo");

define("RESULT_MESSAGE", "message");
define("RESULT_CODE", "code");
define("RESULT_UQPAY_ORDER_ID", "uqorderid");
define("RESULT_UQPAY_RELATED_ID", "relatedid");
define("RESULT_UQPAY_SCENES_ID", "scenesId");
define("RESULT_STATE", "state");
define("RESULT_ACCEPT_CODE", "acceptcode");

define("RESULT_QR_CODE_URL", "qrcodeurl");
define("RESULT_QR_CODE_DATA", "qrcode");

define("RESULT_CHANNEL_CODE", "channelcode");
define("RESULT_CHANNEL_MESSAGE", "channelmsg");

define("CREDIT_CARD_FIRST_NAME", "firstname");
define("CREDIT_CARD_LAST_NAME", "lastname");
define("CREDIT_CARD_CARD_NUM", "cardnum");
define("CREDIT_CARD_CVV", "cvv");
define("CREDIT_CARD_EXPIRE_MONTH", "expiremonth");
define("CREDIT_CARD_EXPIRE_YEAR", "expireyear");
define("CREDIT_CARD_ADDRESS_COUNTRY", "addresscountry");
define("CREDIT_CARD_ADDRESS_STATE", "addressstate");
define("CREDIT_CARD_ADDRESS_CITY", "addresscity");
define("CREDIT_CARD_ADDRESS", "address");
define("CREDIT_CARD_PHONE", "phone");
define("CREDIT_CARD_EMAIL", "email");
define("CREDIT_CARD_ZIP", "zip");

define("PAYGATE_API_PAY", "/pay");
define("PAYGATE_API_REFUND", "/refund");
define("PAYGATE_API_CANCEL", "/cancel");
define("PAYGATE_API_QUERY", "/query");


$paymentSupportClient = array(
    "PC_WEB" => 1,
    "IOS"=>2,
    "Android"=>3,
);
