<?php
  namespace uqpay\payment\sdk\config;

  class merchantConfig{
      private $id;
      private $agentId = 0;
      function __construct($config)
      {
          $this->id = $config["id"];
          if(array_key_exists('agentId,',$config)){
          $this->agentId = $config["agentId"];
          }
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