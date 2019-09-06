<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class OrderRefund implements PaymentParameter {

	/**
	 * for merchant user this value is equal {@see ConfigOfAPI::$uqpay_id}
	 * @var int
	 * @ParamLink(value=Constants::AUTH_MERCHANT_ID, required=true)
	 */
	public $merchant_id;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_TRADE_TYPE, required=true)
	 */
	public $trade_type = Constants::TRADE_TYPE_REFUND;

	/**
	 * this order id not the origin pay order id
	 * here suggest enter the refund order id of your system
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
	 * @var double
	 * @ParamLink(value=Constants::PAY_ORDER_AMOUNT, required=true)
	 */
	public $amount;

	/**
	 * async notice url
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_ASYNC_NOTICE_URL, required=true)
	 */
	public $callback_url;

	/**
	 * Unix timestamp
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_DATE, required=true)
	 */
	public $date;

	/**
	 * @var int
	 * @ParamLink(value=Constants::PAY_OPTIONS_CLIENT_TYPE, required=true)
	 */
	public $client_type = Constants::PAYMENT_SUPPORTED_CLIENT_PC;

	/**
	 * @var array
	 * @ParamLink(value=Constants::PAY_ORDER_EXTEND_INFO, target_type = "JSON")
	 */
	public $extend_info;

	/**
	 * @return array
	 * @throws UqpayException
	 */
	public function getRequestArr() {
		$result = array();
		$errors = array();
		if (empty($this->merchant_id) || 0 == $this->merchant_id) {
			$errors[] = 'merchant_id';
		} else {
			$result[Constants::AUTH_MERCHANT_ID] = $this->merchant_id;
		}
		if (empty($this->trade_type)) {
			$errors[] = 'trade_type';
		} else {
			$result[Constants::PAY_OPTIONS_TRADE_TYPE] = $this->trade_type;
		}
		if (empty($this->order_id)) {
			$errors[] = 'order_id';
		} else {
			$result[Constants::PAY_ORDER_ID] = $this->order_id;
		}
		if (empty($this->uqpay_order_id) || 0 == $this->uqpay_order_id) {
			$errors[] = 'uqpay_order_id';
		} else {
			$result[Constants::RESULT_UQPAY_ORDER_ID] = $this->uqpay_order_id;
		}
		if (empty($this->amount) || 0 == $this->amount) {
			$errors[] = 'amount';
		} else {
			$result[Constants::PAY_ORDER_AMOUNT] = $this->amount;
		}
		if (empty($this->callback_url)) {
			$errors[] = 'callback_url';
		} else {
			$result[Constants::PAY_OPTIONS_ASYNC_NOTICE_URL] = $this->callback_url;
		}
		if (empty($this->date) || 0 == $this->date) {
			$errors[] = 'date';
		} else {
			$result[Constants::PAY_ORDER_DATE] = $this->date;
		}
		if (empty($this->client_type) || 0 == $this->client_type) {
			$errors[] = 'client_type';
		} else {
			$result[Constants::PAY_OPTIONS_CLIENT_TYPE] = $this->client_type;
		}
		if (!empty($this->extend_info)) { $result[Constants::PAY_ORDER_EXTEND_INFO] = json_encode($this->extend_info); }
		if (sizeof($errors) > 0) {
			throw new UqpayException('Payment parameters invalid: ['.implode(',', $errors).'] is required, but null');
		}
		return $result;
	}
}