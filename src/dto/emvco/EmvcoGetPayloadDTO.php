<?php
namespace uqpay\payment\sdk\dto\emvco;
use uqpay\payment\sdk\dto\common\BaseJsonRequestDTO;
use Particle\Validator\Validator;

class EmvcoGetPayloadDTO extends BaseJsonRequestDTO {
  private $type;

    public function __construct()
{
    $this->validated();
}

    protected function validated(){

    $validator = new Validator;
    $validator->required('type');
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
