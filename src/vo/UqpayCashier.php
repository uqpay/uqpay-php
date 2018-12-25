<?php
namespace uqpay\payment\sdk\vo;
class UqpayCashier {
  private $merchantId;//	商户ID， 商户在UQPAY系统中的唯一标识
  private $tradeType; //	交易类型，详见附录1
  private $date;	//请求的时间，使用Unix时间戳
  private $orderId; //商户订单号
  private $amount;
  private $currency; //币种
  private $transName; //商品信息
  private $callbackUrl; //回调地址
  private $returnUrl;//同步跳转URL
  private $quantity = 0; //商品数量
  private $storeId = 0; //店铺ID
  private $seller = 0; //销售员ID
  private $channelInfo;
  private $extendInfo;


  public function getParamsMap() {
    $paramsMap = array();
    $paramsMap['merchantId'] =(string)$this->merchantId;
    $paramsMap['tradeType'] =$this->tradeType;
    $paramsMap['date'] =$this->date;
    $paramsMap['orderId'] =$this->orderId;
    if ($this->amount > 0) {
        $paramsMap['amount'] =(string)$this->amount;
    }
      $paramsMap['currency'] =$this->currency;
      $paramsMap['transName'] =$this->transName;
      $paramsMap['callbackUrl'] =$this->callbackUrl;
      $paramsMap['returnUrl'] =$this->returnUrl;
    if ($this->quantity > 0) {
        $paramsMap['quantity'] =(string)$this->quantity;
    }
    if ($this->storeId > 0) {
        $paramsMap['storeId'] =(string)$this->storeId;
    }
    if ($this->seller > 0) {
        $paramsMap['seller'] =(string)$this->seller;
    }
    if ($this->channelInfo != null && strcmp($this->channelInfo, "") != 0) {
        $paramsMap['channelInfo'] =$this->channelInfo;
    }
    if ($this->extendInfo != null && strcmp($this->extendInfo, "") != 0) {
        $paramsMap['extendInfo'] =$this->extendInfo;
    }
    return $paramsMap;
  }
}
