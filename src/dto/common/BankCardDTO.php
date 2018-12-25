<?php
namespace uqpay\payment\sdk\dto\common;
use Particle\Validator\Validator;

class BankCardDTO {
    private $firstName;
    private $lastName;
    private $cvv;
    private $cardNum;
    private $expireMonth;
    private $expireYear;
  public function __construct()
  {
      $this->validated();
  }

  protected function validated(){

      $validator = new Validator;
      $validator->required('firstName')->string();
      $validator->required('lastName')->string();
      $validator->required('cvv')->string();
      $validator->required('expireMonth')->string();
      $validator->required('expireYear')->string();
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
