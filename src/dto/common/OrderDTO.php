<?php
namespace uqpay\payment\sdk\dto\common;

 class OrderDTO extends PayOptionsDTO {
  /**
   * is required
   */
  private $orderId; // your order id
  private $amount;
  private $currency; // use ISO 4217 currency code same as the Java Currency Class
  private $date; // this order generate date

  /**
   * not required
   */
  private $quantity; // quantity of products
  private $storeId; // your store id
  private $seller; // your seller id

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
