<?php
namespace uqpay\payment\sdk\dto\exchangeRate;
use uqpay\payment\sdk\dto\common\BaseJsonRequestDTO;
use Particle\Validator\Validator;
class ExchangeRateQueryDTO extends BaseJsonRequestDTO {
  private $originalCurrency;
  private $targetCurrency;

    public function __construct()
    {
        $this->validated();
    }

    protected function validated(){

        $validator = new Validator;
        $validator->required('originalCurrency')->string();
        $validator->required('targetCurrency')->string();
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
