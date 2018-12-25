<?php
namespace uqpay\payment\sdk\dto\result\appgate;

class QRCodeResult extends BaseAppgateResult {
  private $codeId;
  private $type;
  private $name;
  private $payload;
  private $terminalId;
  private $content; // QR Code Content

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
