<?php
namespace uqpay\payment\sdk\dto\operation;
/**
 * for moment is the same as order refund
 */
class OrderCancel extends OrderRefund {
  function __construct() {
    $this->tradeType=102;
  }
}
