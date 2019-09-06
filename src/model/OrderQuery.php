<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class OrderQuery implements PaymentParameter {

	/**
	 * for merchant user this value is equal {@see ConfigOfAPI::$uqpay_id} and will be valued automatic
	 * @var int
	 * @ParamLink(value=Constants::AUTH_MERCHANT_ID, required=true)
	 */
	public $merchant_id;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_TRADE_TYPE, required=true)
	 */
	public $trade_type = Constants::TRADE_TYPE_QUERY;

	/**
	 * Unix timestamp
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_DATE, required=true)
	 */
	public $date;

	/**
	 * required rule: $order_id not empty || $uqpay_order_id not empty
	 */

	/**
	 * your origin order id
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_ID, required=true)
	 */
	public $order_id;

	/**
	 * @var integer
	 * @ParamLink(value=Constants::RESULT_UQPAY_ORDER_ID, required=true)
	 */
	public $uqpay_order_id;

	/**
	 * @return array
	 * @throws UqpayException
	 */
	public function getRequestArr() {
		$result = array();
		$errors = array();
		if (empty($this->trade_type)) {
			$errors[] = 'trade_type';
		} else {
			$result[Constants::PAY_OPTIONS_TRADE_TYPE] = $this->trade_type;
		}
		if (empty($this->date) || 0 == $this->date) {
			$errors[] = 'date';
		} else {
			$result[Constants::PAY_ORDER_DATE] = $this->date;
		}
		if (empty($this->order_id) && empty($this->uqpay_order_id)) {
			$errors[] = 'order_id or uqpay_order_id';
		} else {
			if (!empty($this->order_id)) { $result[Constants::PAY_ORDER_ID] = $this->order_id; }
			if (!empty($this->uqpay_order_id)) { $result[Constants::RESULT_UQPAY_ORDER_ID] = $this->uqpay_order_id; }
		}
		if (sizeof($errors) > 0) {
			throw new UqpayException('Payment parameters invalid: ['.implode(',', $errors).'] is required, but null');
		}
		return $result;
	}
}