<?php
namespace uqpay\payment\sdk\dto\common;
use Particle\Validator\Validator;

class PayOptionsDTO implements PaygateParams, PayOptions {
  /**
   * is required
   */

  private $methodId;

  private $callbackUrl; // async notice url

  private $client; // only required for in app payment

  private $tradeType;

  /**
   * not required for each payment API
   */
  private $returnUrl; // sync notice url
  private $scanType; // only required for qr code payment
  private $identity; // only required for qr code payment when scan type is Merchant

  private $channelInfo;
  private $extendInfo;



    public function __construct()
    {
        $this->validated();
    }

    protected function validated(){

        $validator = new Validator;
        $validator->required('methodId')->integer();
        $validator->required('callbackUrl')->string();
        $validator->required('client')->string();
        $validator->required('tradeType')->string();
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
