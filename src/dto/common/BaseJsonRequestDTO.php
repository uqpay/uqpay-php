<?php
namespace uqpay\payment\sdk\dto\common;
use Particle\Validator\Validator;

class BaseJsonRequestDTO extends AuthDTO
{
    private $signType=1;
    private $date;
    private $signature;


    public function __construct()
    {
        $this->validated();
        $this->signature = "000000";  // Don't valued this by yourself, the SDK will automatic sign base on your config
    }

    protected function validated()
    {

        $validator = new Validator;
        $validator->required('date')->string();
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

