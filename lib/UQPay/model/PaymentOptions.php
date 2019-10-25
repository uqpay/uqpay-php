<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;
use uqpay\payment\PayMethodHelper;
use uqpay\payment\UqpayException;

class PaymentOptions implements PaymentParameter {
	/**
	 * @see PayMethodHelper
	 * @var int
	 * @ParamLink(value=Constants::PAY_OPTIONS_METHOD_ID, required=true)
	 */
	public $method_id;

	/**
	 * @var int
	 * @required true
	 * @ParamLink(value=Constants::PAY_OPTIONS_CLIENT_TYPE, required=true)
	 */
	public $client_type = Constants::PAYMENT_SUPPORTED_CLIENT_PC;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_TRADE_TYPE, required=true)
	 */
	public $trade_type;

	/*******
	 * not required for each payment API
	 *******/

	/**
	 * async notice url
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_ASYNC_NOTICE_URL)
	 */
	public $callback_url;
	/**
	 * sync notice url
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_SYNC_NOTICE_URL)
	 */
	public $return_url;

	/**
	 * only required for qr code payment
	 * @var int
	 * @ParamLink(value=Constants::PAY_OPTIONS_SCAN_TYPE)
	 */
	public $scan_type = Constants::QR_CODE_SCAN_BY_MERCHANT;

	/*******
	 * only required for qr code payment when scan type is Merchant or the pay method is offline qr code
	 *******/

	/**
	 * the payload of the consumer qr code
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_IDENTITY)
	 */
	public $identity;

	/**
	 * city of the merchant
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_MERCHANT_CITY)
	 */
	public $merchant_city;

	/**
	 * the terminal id of scan code device
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_TERMINAL_ID)
	 */
	public $terminal_id;

	/**
	 * @var array
	 * @ParamLink(value=Constants::PAY_ORDER_CHANNEL_INFO, target_type="JSON")
	 */
	public $channel_info;

	/**
	 * @var array
	 * @ParamLink(value=Constants::PAY_ORDER_EXTEND_INFO, target_type = "JSON")
	 */
	public $extend_info;

	/**
	 * only use for 3D CreditCard Payment,
	 * This URL is used to get the paResponse sent by the bank
	 * the paResponse will be used to finished the 3D payment, {@see ThreeDFinishDTO}
	 * @var string
	 */
	public $threeD_paRes_cb_url;

	/**
	 * @return array
	 * @throws UqpayException
	 */
	public function getRequestArr() {
		$result = array();
		$errors = array();
		if (empty($this->method_id) && $this->method_id != 0) {
			$errors[] = 'method_id';
		} else {
			$result[Constants::PAY_OPTIONS_METHOD_ID] = $this->method_id;
		}
		if (empty($this->client_type) || 0 == $this->client_type) {
			$errors[] = 'client_type';
		} else {
			$result[Constants::PAY_OPTIONS_CLIENT_TYPE] = $this->client_type;
		}
		if (empty($this->trade_type)) {
			$errors[] = 'trade_type';
		} else {
			$result[Constants::PAY_OPTIONS_TRADE_TYPE] = $this->trade_type;
		}
		if (!empty($this->callback_url)) { $result[Constants::PAY_OPTIONS_ASYNC_NOTICE_URL] = $this->callback_url; }
		if (!empty($this->return_url)) { $result[Constants::PAY_OPTIONS_SYNC_NOTICE_URL] = $this->return_url; }
		if (!empty($this->scan_type)) { $result[Constants::PAY_OPTIONS_SCAN_TYPE] = $this->scan_type; }
		if (!empty($this->identity)) { $result[Constants::PAY_OPTIONS_IDENTITY] = $this->identity; }
		if (!empty($this->merchant_city)) { $result[Constants::PAY_OPTIONS_MERCHANT_CITY] = $this->merchant_city; }
		if (!empty($this->terminal_id)) { $result[Constants::PAY_OPTIONS_TERMINAL_ID] = $this->terminal_id; }
		if (!empty($this->channel_info)) { $result[Constants::PAY_ORDER_CHANNEL_INFO] = json_encode($this->channel_info); }
		if (!empty($this->extend_info)) { $result[Constants::PAY_ORDER_EXTEND_INFO] = json_encode($this->extend_info); }
		if (sizeof($errors) > 0) {
			throw new UqpayException('Payment parameters invalid: ['.implode(',', $errors).'] is required, but null');
		}
		return $result;
	}
}