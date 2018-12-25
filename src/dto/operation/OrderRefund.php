<?php
namespace uqpay\payment\sdk\dto\operation;
use Particle\Validator\Validator;
class OrderRefund {
  /**
   * is required
   */
  private $tradeType = 103;
  private $orderId; // your order id
  private $amount;
  private $callbackUrl; // async notice url
  private $date; // this request generate date
  private $clientType = 1;

  /**
   * not required
   */
  private $extendInfo;

    public function __construct()
    {
        $this->validated();
    }

    protected function validated(){

        $validator = new Validator;
        $validator->required('tradeType');
        $validator->required('orderId')->string();
        $validator->required('amount')->numeric();
        $validator->required('callbackUrl')->string();
        $validator->required('date');
        $validator->required('clientType');
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
