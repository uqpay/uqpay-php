<?php
namespace uqpay\payment\sdk\config;
class AppgateConfig extends BaseConfig {

  private $apiRoot = "https://appgate.uqpay.com";

    public function __construct($config)
    {
        if(array_key_exists('apiRoot',$config)){
            $this->apiRoot=$config["apiRoot"];
        }
    }
    public function __set($name, $value){
        $this->$name = $value;
    }

    //get方法
    public function __get($name){
        if(!isset($this->$name)){
            //未设置
            $this->$name = "";
        }
        return $this->$name;
    }
}
?>
