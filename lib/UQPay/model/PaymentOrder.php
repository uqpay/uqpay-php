<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\config\ConfigOfAPI;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class PaymentOrder extends PaymentOptions {

	/**
	 * for merchant user this value is equal {@see ConfigOfAPI::$uqpay_id} and will be valued automatic
	 * @var int
	 * @ParamLink(value=Constants::AUTH_MERCHANT_ID, required=true)
	 */
	public $merchant_id;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_ID, required=true)
	 */
	public $order_id;

	/**
	 * product info
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_TRANS_NAME, required=true)
	 */
	public $trans_name;

	/**
	 * @var double
	 * @ParamLink(value=Constants::PAY_ORDER_AMOUNT, required=true)
	 */
	public $amount = 0;

	/**
	 * use ISO 4217 currency code
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_CURRENCY, required=true)
	 */
	public $currency;

	/**
	 * Unix timestamp
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_DATE, required=true)
	 */
	public $date;

	/**
	 * consumer client ip
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_CLIENT_IP, required=true)
	 */
	public $client_ip;

	/**
	 * @var int
	 * @ParamLink(value=Constants::PAY_ORDER_QUANTITY)
	 */
	public $quantity;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_STORE_ID)
	 */
	public $store_id;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_SELLER)
	 */
	public $seller;

	/**
	 * @return array
	 * @throws UqpayException
	 */
	public function getRequestArr() {
		$result = parent::getRequestArr();
		$errors = array();
		if (empty($this->merchant_id) || 0 == $this->merchant_id) {
			$errors[] = 'merchant_id';
		} else {
			$result[Constants::AUTH_MERCHANT_ID] = $this->merchant_id;
		}
		if (empty($this->order_id)) {
			$errors[] = 'order_id';
		} else {
			$result[Constants::PAY_ORDER_ID] = $this->order_id;
		}
		if (empty($this->trans_name)) {
			$errors[] = 'trans_name';
		} else {
			$result[Constants::PAY_ORDER_TRANS_NAME] = $this->trans_name;
		}
		if (empty($this->amount) || 0 == $this->amount) {
			$errors[] = 'amount';
		} else {
			$result[Constants::PAY_ORDER_AMOUNT] = $this->amount;
		}
		if (empty($this->currency)) {
			$errors[] = 'currency';
		} else {
			$result[Constants::PAY_ORDER_CURRENCY] = $this->currency;
		}
		if (empty($this->date) || 0 == $this->date) {
			$errors[] = 'date';
		} else {
			$result[Constants::PAY_ORDER_DATE] = $this->date;
		}
		if (empty($this->client_ip)) {
			$errors[] = 'client_ip';
		} else {
			$result[Constants::PAY_ORDER_CLIENT_IP] = $this->client_ip;
		}
		if (!empty($this->quantity)) { $result[Constants::PAY_ORDER_QUANTITY] = $this->quantity; }
		if (!empty($this->store_id)) { $result[Constants::PAY_ORDER_STORE_ID] = $this->store_id; }
		if (!empty($this->seller)) { $result[Constants::PAY_ORDER_SELLER] = $this->seller; }
		if (sizeof($errors) > 0) {
			throw new UqpayException('Payment parameters invalid: ['.implode(',', $errors).'] is required, but null');
		}
		return $result;
	}


}