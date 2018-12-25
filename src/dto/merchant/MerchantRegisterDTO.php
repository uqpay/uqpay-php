<?php
namespace uqpay\payment\sdk\dto\merchant;
use uqpay\payment\sdk\dto\common\BaseJsonRequestDTO;
use Particle\Validator\Validator;
class MerchantRegisterDTO extends BaseJsonRequestDTO {
  private $name;
  private $abbr;
  private $regEmail;
  private $companyName;
  private $regNo; // company register number
  private $regAddress;
  private $country;
  private $mcc;

  private $website;
  private $contact;
  private $mobile;
  private $email; // business email


    public function __construct()
    {
        $this->validated();
    }

    protected function validated(){

        $validator = new Validator;
        $validator->required('name')->string();
        $validator->required('abbr')->string()->lengthBetween(1,8);
        $validator->required('regEmail')->email();
        $validator->required('companyName')->string();
        $validator->required('regNo')->string();
        $validator->required('regAddress')->string();
        $validator->required('country')->string();
        $validator->required('mcc')->string();
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
