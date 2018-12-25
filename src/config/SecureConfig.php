<?php

namespace uqpay\payment\sdk\config;

class SecureConfig
{
    private $privateKeyStr;
    /**
     * The pem file path of RSA Private Key
     * Tips: make sure you have the permission to read.
     */
    private $privateKeyPath;

    private $publicKeyStr;

    private $publicKeyPath;

    private $privateKey;

    private $publicKey;

    function __construct($config)
    {
        $this->publicKeyPath=$config["publicKeyPath"];
        $this->privateKeyPath=$config["privateKeyPath"];
    }

    public function getPrivateKey()
    {
        if ($this->privateKey != null) {
            return $this->privateKey;
        }

        if (strcmp($this->privateKeyPath, "") != 0) {
//            $this->privateKey = RSAUtil . loadPrivateKey($this->privateKeyPath, true);
        } else if (strcmp($this->privateKeyPath, "") == 0) {
//            $this->privateKey = RSAUtil . loadPrivateKey($this->privateKeyStr, false);
        }
        if ($this->privateKey == null) {
            throw new \Exception("Load Uqpay Payment Private Key Fail!");
        }
        return $this->privateKey;
    }

    public function getPublicKey()
    {
        if ($this->publicKey != null) {
            return $this->publicKey;
        }

        if (strcmp($this->publicKeyPath, "") != 0) {
//    publicKey = RSAUtil . loadPublicKey(this . publicKeyPath, true);
        } else if (strcmp($this->publicKeyPath, "") == 0) {
//    publicKey = RSAUtil . loadPublicKey(this . publicKeyStr, false);
        }
        if ($this->publicKey == null) {
            throw new \Exception("Load Uqpay Payment Public Key Fail!");
        }
        return $this->publicKey;
    }

    //set方法
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