<?php
namespace uqpay\payment\sdk\dto\operation;
use Particle\Validator\Validator;
class OrderQuery {
  /**
   * is required
   */

  private $orderId; // your order id

  private $date; // this request generate date


    public function __construct()
    {
        $this->validated();
    }

    protected function validated(){

        $validator = new Validator;
        $validator->required('orderId')->string();
        $validator->required('date');
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
