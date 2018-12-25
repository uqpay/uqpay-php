<?php
namespace uqpay\payment\sdk\dto\common;
use Particle\Validator\Validator;
class ServerHostDTO {
  private $token;
  private $phone;

    public function __construct()
    {
        $this->validated();
    }

    protected function validated(){

        $validator = new Validator;
        $validator->required('token')->string();
        $validator->required('phone')->string();
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
