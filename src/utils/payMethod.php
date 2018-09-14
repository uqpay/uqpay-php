<?php

namespace uqpay\payment\sdk\utils;


class payMethod
{
    public $UnionPayQR = 1001;
    public $AlipayQR = 1002;
    public $WeChatQR = 1003;
    public $Wechat_WebBased_InApp = 1102;
    public $UnionPayOnline = 1100;
    public $Union_Merchant_Host = 1101;
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
    public $UnionPay_InAPP = 2001;
    public $UnionPay_Online_InAPP = 2002;
    public $ApplePay = 3000;

    public $scenesEnum = array(
        "RedirectPay" => "RedirectPay",//在线支付（跳转）
        "DirectPay" => "DirectPay",//在线支付（直接返回结果）
        "MerchantHost" => "MerchantHost",//存在验证环节的支付，如银联鉴权支付
        "EmbedPay" => "EmbedPay",//嵌入方式支付
        "QRCode" => "QRCode",
        "CreditCard" => "CreditCard",
        "ThreeDCreditCard" => "ThreeDCreditCard",
        "InApp" => "InApp"
    );

    public $UqpayScanType = array(
        "Merchant" => 0,//merchant
        "Consumer" => 1,//Consumer
    );

    public $BankCardType = array(
        "Debit" => 1,//借记卡
        "Credit" => 2,//信用卡
    );
    public $paymentSupportClient = array(
        "PC_WEB" => 1,
        "IOS" => 2,
        "Android" => 3,
    );

    public $UqpayTradeType = array(
        "pay" => 101,
        "cancel" => 102,
        "refund" => 103,
        "preauth" => 104,
        "preauthcomplete" => 105,
        "preauthcancel" => 106,
        "preauthcc" => 107,
        "verifycode" => 130,
        "enroll" => 131,
        "withdraw" => 140,
        "query" => 180
    );

    function payMethod()
    {
        $payMethod = array(
            $this->UnionPay => $this->scenesEnum["QRCode"],
            $this->AlipayQR => $this->scenesEnum["QRCode"],
            $this->WeChatQR => $this->scenesEnum["QRCode"],
            $this->Wechat_WebBased_InApp => $this->scenesEnum["RedirectPay"],
            $this->UnionPayOnline => $this->scenesEnum["RedirectPay"],
            $this->Union_Merchant_Host=>$this->scenesEnum["MerchantHost"],
            $this->VISA => $this->scenesEnum["CreditCard"],
            $this->VISA3D => $this->scenesEnum["ThreeDCreditCard"],
            $this->Master => $this->scenesEnum["CreditCard"],
            $this->Master3D => $this->scenesEnum["ThreeDCreditCard"],
            $this->UnionPay => $this->scenesEnum["CreditCard"],
            $this->AMEX => $this->scenesEnum["CreditCard"],
            $this->JCB => $this->scenesEnum["CreditCard"],
            $this->PayPal => $this->scenesEnum["CreditCard"],
            $this->Alipay => $this->scenesEnum["RedirectPay"],
            $this->AlipayWap => $this->scenesEnum["RedirectPay"],
            $this->Wechat_InAPP => $this->scenesEnum["InApp"],
            $this->UnionPay_InAPP => $this->scenesEnum["InApp"],
            $this->UnionPay_Online_InAPP=>$this->scenesEnum["InApp"],
            $this->ApplePay => $this->scenesEnum["RedirectPay"]
        );
        return $payMethod;
    }
}
