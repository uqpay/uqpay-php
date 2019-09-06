<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;

class PaymentResult extends GeneralResult {

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_ID)
	 */
	public $order_id;

	/**
	 * @var integer
	 * @ParamLink(value=Constants::RESULT_UQPAY_ORDER_ID)
	 */
	public $uqpay_order_id;

	/**
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_AMOUNT)
	 */
	public $amount;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_CURRENCY)
	 */
	public $currency;

	/**
	 * @var string
	 * @ParamLink(value=Constants::RESULT_STATE)
	 */
	public $state;

	/**
	 * @var array
	 * @ParamLink(value=Constants::PAY_ORDER_CHANNEL_INFO, target_type="JSON")
	 */
	public $channel_info;
	/**
	 * @var array
	 * @ParamLink(value=Constants::PAY_ORDER_EXTEND_INFO, target_type="JSON")
	 */
	public $extend_info;

	/**
	 * this result valued when IN-APP payment
	 * @var string
	 * @ParamLink(value=Constants::RESULT_ACCEPT_CODE)
	 */
	public $accept_code;

	/**
	 * @var string
	 * @ParamLink(value=Constants::RESULT_CHANNEL_CODE)
	 */
	public $channel_code;
	/**
	 * @var string
	 * @ParamLink(value=Constants::RESULT_CHANNEL_MESSAGE)
	 */
	public $channel_msg;

	/**
	 * @var int
	 * @ParamLink(value=Constants::PAY_OPTIONS_SCAN_TYPE)
	 */
	public $scan_type;

	/**
	 * @var string
	 * @ParamLink(value=Constants::RESULT_QR_CODE_URL)
	 */
	public $qr_url;
	/**
	 * @var string
	 * @ParamLink(value=Constants::RESULT_QR_CODE_DATA)
	 */
	public $qr_payload;

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
	 * this result only valued when ThreeD CreditCard and Redirect Payment
	 * if this result is valued, the others will be null except the state and code
	 * user can return this data to client
	 * and post them with media type "application/x-www-form-urlencoded" to the apiURL (which u can get from this data)
	 * @var PostRedirectData;
	 */
	public $redirect;

	public static function builderRedirectResult(array $post_data, $url) {
		$result = new self();
		$result -> state = Constants::ORDER_STATE_READY;
		$result -> code = 10002;
		$redirect = new PostRedirectData();
		$redirect -> body = $post_data;
		$redirect -> url = $url;
		$result -> redirect = $redirect;
		return $result;
	}

	public function parseResultData( array &$result_array ) {
		parent::parseResultData( $result_array );
		if ( isset( $result_array[ Constants::PAY_ORDER_ID ] ) ) {
			$this->order_id = $result_array[ Constants::PAY_ORDER_ID ];
		}
		if ( isset( $result_array[ Constants::RESULT_UQPAY_ORDER_ID ] ) ) {
			$this->uqpay_order_id = $result_array[ Constants::RESULT_UQPAY_ORDER_ID ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_AMOUNT ] ) ) {
			$this->amount = $result_array[ Constants::PAY_ORDER_AMOUNT ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_CURRENCY ] ) ) {
			$this->currency = $result_array[ Constants::PAY_ORDER_CURRENCY ];
		}
		if ( isset( $result_array[ Constants::RESULT_STATE ] ) ) {
			$this->state = $result_array[ Constants::RESULT_STATE ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_CHANNEL_INFO ] ) && !empty($result_array[ Constants::PAY_ORDER_CHANNEL_INFO ])) {
			$this->channel_info = json_decode($result_array[ Constants::PAY_ORDER_CHANNEL_INFO ], true);
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_EXTEND_INFO ] ) && !empty($result_array[ Constants::PAY_ORDER_EXTEND_INFO ])) {
			$this->extend_info = json_decode($result_array[ Constants::PAY_ORDER_EXTEND_INFO ], true);
		}
		if ( isset( $result_array[ Constants::RESULT_ACCEPT_CODE ] ) ) {
			$this->accept_code = $result_array[ Constants::RESULT_ACCEPT_CODE ];
		}
		if ( isset( $result_array[ Constants::RESULT_CHANNEL_CODE ] ) ) {
			$this->channel_code = $result_array[ Constants::RESULT_CHANNEL_CODE ];
		}
		if ( isset( $result_array[ Constants::RESULT_CHANNEL_MESSAGE ] ) ) {
			$this->channel_msg = $result_array[ Constants::RESULT_CHANNEL_MESSAGE ];
		}
		if ( isset( $result_array[ Constants::PAY_OPTIONS_SCAN_TYPE ] ) ) {
			$this->scan_type = $result_array[ Constants::PAY_OPTIONS_SCAN_TYPE ];
		}
		if ( isset( $result_array[ Constants::RESULT_QR_CODE_URL ] ) ) {
			$this->qr_url = $result_array[ Constants::RESULT_QR_CODE_URL ];
		}
		if ( isset( $result_array[ Constants::RESULT_QR_CODE_DATA ] ) ) {
			$this->qr_payload = $result_array[ Constants::RESULT_QR_CODE_DATA ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_STORE_ID ] ) ) {
			$this->store_id = $result_array[ Constants::PAY_ORDER_STORE_ID ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_SELLER ] ) ) {
			$this->seller = $result_array[ Constants::PAY_ORDER_SELLER ];
		}
	}


}