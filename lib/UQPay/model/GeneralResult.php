<?php


namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;

class GeneralResult implements ResultParameter {
	/**
	 * @var int
	 * @ParamLink(value=Constants::RESULT_CODE)
	 */
	public $code;

	/**
	 * @var string
	 * @ParamLink(value=Constants::RESULT_MESSAGE)
	 */
	public $message;

	/**
	 * @var string
	 * @ParamLink(value=Constants::AUTH_SIGN)
	 */
	public $sign;

	/**
	 * @var string
	 * @ParamLink(value=Constants::AUTH_SIGN_TYPE)
	 */
	public $sign_type;

	/**
	 * @var int
	 * @ParamLink(value=Constants::AUTH_MERCHANT_ID)
	 */
	public $merchant_id;

	/**
	 * @var int
	 * @ParamLink(value=Constants::AUTH_AGENT_ID)
	 */
	public $agent_id;

	/**
	 * @var int
	 * @ParamLink(value=Constants::PAY_OPTIONS_METHOD_ID)
	 */
	public $method_id;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_OPTIONS_TRADE_TYPE)
	 */
	public $trade_type;

	/**
	 * Unix timestamp
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_DATE)
	 */
	public $date;

	/**
	 * @param array $result_array
	 */
	public function parseResultData( array &$result_array ) {
		if ( isset( $result_array[ Constants::RESULT_CODE ] ) ) {
			$this->code = $result_array[ Constants::RESULT_CODE ];
		}
		if ( isset( $result_array[ Constants::RESULT_MESSAGE ] ) ) {
			$this->message = $result_array[ Constants::RESULT_MESSAGE ];
		}
		if ( isset( $result_array[ Constants::AUTH_SIGN ] ) ) {
			$this->sign = $result_array[ Constants::AUTH_SIGN ];
		}
		if ( isset( $result_array[ Constants::AUTH_SIGN_TYPE ] ) ) {
			$this->sign_type = $result_array[ Constants::AUTH_SIGN_TYPE ];
		}
		if ( isset( $result_array[ Constants::AUTH_MERCHANT_ID ] ) ) {
			$this->merchant_id = $result_array[ Constants::AUTH_MERCHANT_ID ];
		}
		if ( isset( $result_array[ Constants::PAY_OPTIONS_METHOD_ID ] ) ) {
			$this->method_id = $result_array[ Constants::PAY_OPTIONS_METHOD_ID ];
		}
		if ( isset( $result_array[ Constants::PAY_OPTIONS_TRADE_TYPE ] ) ) {
			$this->trade_type = $result_array[ Constants::PAY_OPTIONS_TRADE_TYPE ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_DATE ] ) ) {
			$this->date = $result_array[ Constants::PAY_ORDER_DATE ];
		}
	}
}