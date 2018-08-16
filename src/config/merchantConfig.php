<?php
  namespace sdk\config;

  class merchantConfig{
      private $id;
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