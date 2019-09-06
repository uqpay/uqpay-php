<?php


namespace uqpay\payment\model;


use uqpay\payment\Constants;

class OrderCancel extends OrderRefund {

	/**
	 * OrderCancel constructor.
	 */
	public function __construct() {
		$this->trade_type = Constants::TRADE_TYPE_CANCEL;
	}

	public function getRequestArr() {
		return parent::getRequestArr();
	}


}