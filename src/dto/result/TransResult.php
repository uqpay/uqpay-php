<?php
namespace uqpay\payment\sdk\dto\result;

class TransResult extends BaseResult {
  private $orderId;
  private $uqOrderId; // this order id generate by uqpay
  private $amount;
  private $currency;
  private $state; // order state
  private $channelInfo;
  private $extendInfo;

  /**
   * this result valued when IN-APP payment
   */
  private $acceptCode;// this code will be used by UQPAY Mobile Client SDK to generate the wallet app url scheme

  /**
   * these results valued when the payment want return some channel info
   */
  private $channelCode;
  private $channelMsg;

  /**
   * these results valued when QRCode payment
   */
  private $scanType;
  private $qrCodeUrl;
  private $qrCode;

  /**
   * this result only valued when ThreeD CreditCard and Online Payment
   * if this result is valued, the others will be null
   * user can return this data to client, and post them with media type "application/x-www-form-urlencoded" to the apiURL (which u can get from this data)
   */
  private $redirectPostData;


  function __construct($postData, $apiURL) {
    $redirectPost = new RedirectPostData();
    $redirectPost->apiURL=$apiURL;
    $redirectPost->postData=$postData;
    $this->redirectPostData = redirectPost;
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
