<?php

namespace uqpay\payment\sdk\utils;
$scenesEnum = array(
    "Global"=>"Global", //全局
  "Unknown"=>"Unknown", //未知
  "RedirectPay"=>"RedirectPay",//在线支付（跳转）
  "DirectPay"=>"DirectPay",//在线支付（直接返回结果）
  "MerchantHost"=>"MerchantHost",//存在验证环节的支付，如银联鉴权支付
  "EmbedPay"=>"EmbedPay",//嵌入方式支付
    "QRCode" => "QRCode",
    "CreditCard" => "CreditCard",
    "ThreeDCreditCard" => "ThreeDCreditCard",
    "InApp" => "InApp"
);



class payMethodObject
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
$payMethodList = new payMethodObject();
$payMethod = array(
    $payMethodList->UnionPay => $scenesEnum["QRCode"],
    $payMethodList->AlipayQR => $scenesEnum["QRCode"],
    $payMethodList->WeChatQR => $scenesEnum["QRCode"],
    $payMethodList->WeChatH5 => $scenesEnum["RedirectPay"],
    $payMethodList->UnionPayOnline => $scenesEnum["RedirectPay"],
    $payMethodList->VISA => $scenesEnum["CreditCard"],
    $payMethodList->VISA3D => $scenesEnum["ThreeDCreditCard"],
    $payMethodList->Master => $scenesEnum["CreditCard"],
    $payMethodList->Master3D => $scenesEnum["ThreeDCreditCard"],
    $payMethodList->UnionPay => $scenesEnum["CreditCard"],
    $payMethodList->AMEX => $scenesEnum["CreditCard"],
    $payMethodList->JCB => $scenesEnum["CreditCard"],
    $payMethodList->PayPal => $scenesEnum["CreditCard"],
    $payMethodList->Alipay => $scenesEnum["RedirectPay"],
    $payMethodList->AlipayWap => $scenesEnum["RedirectPay"],
    $payMethodList->Wechat_InAPP => $scenesEnum["InApp"],
    $payMethodList->UnionPay_InAPP => $scenesEnum["InApp"],
    $payMethodList->ApplePay => $scenesEnum["RedirectPay"]
);

$UqpayScanType = array(
    "Merchant" => 0,//merchant
    "Consumer" => 1,//Consumer
);

$BankCardType = array(
    "Debit" => 1,//借记卡
    "Credit" => 2,//信用卡
);