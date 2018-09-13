<?php

namespace uqpay\payment\sdk\utils;
$scenesEnum = array(
    "QRCode" => "QRCode",
    "OnlinePay" => "OnlinePay",
    "CreditCard" => "CreditCard",
    "ThreeDCreditCard" => "ThreeDCreditCard",
    "InApp" => "InApp"
);

class payMethod
{
    public $UnionPayQR = 1001;
    public $AlipayQR = 1002;
    public $WeChatQR = 1003;
    public $WeChatH5 = 1102;
    public $UnionPayOnline = 1100;
    public $UnionMerchantHost = 1101;
    public $VISA = 1200;
    public $VISA3D = 1250;
    public $Master = 1201;
    public $Master3D = 1251;
    public $UnionPay = 1202;
    public $AMEX = 1203;
    public $JCB = 1204;
    public $PayPal = 1300;
    public $Alipay = 1301;
    public $AlipayWap = 1501;
    public $Wechat_InAPP = 2000;
    public $UnionPay_InAPP = 2002;
    public $ApplePay = 3000;
}

$payMethod = array(
    1001 => $scenesEnum["QRCode"],
    1002 => $scenesEnum["QRCode"],
    1003 => $scenesEnum["QRCode"],
    1102 => $scenesEnum["OnlinePay"],
    1100 => $scenesEnum["OnlinePay"],
    1200 => $scenesEnum["CreditCard"],
    1201 => $scenesEnum["CreditCard"],
    1202 => $scenesEnum["CreditCard"],
    1203 => $scenesEnum["CreditCard"],
    1204 => $scenesEnum["CreditCard"],
    1250 => $scenesEnum["ThreeDCreditCard"],
    1251 => $scenesEnum["ThreeDCreditCard"],
    1300 => $scenesEnum["CreditCard"],
    1301 => $scenesEnum["OnlinePay"],
    1501 => $scenesEnum["OnlinePay"],
    2000 => $scenesEnum["InApp"],
    2001 => $scenesEnum["InApp"],
    3000 => $scenesEnum["OnlinePay"]
);

$UqpayScanType = array(
    "Merchant" => 0,//merchant
    "Consumer" => 1,//Consumer
);

$BankCardType = array(
    "Debit" => 1,//借记卡
    "Credit" => 2,//信用卡
);