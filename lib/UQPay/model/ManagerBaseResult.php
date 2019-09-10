<?php


namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;

class ManagerBaseResult implements ResultParameter {

	/**
	 * @var int
	 * @ParamLink(value=Constants::AUTH_MERCHANT_ID_JSON)
	 */
	public $merchant_id;

	/**
	 * @var int
	 * @ParamLink(value=Constants::AUTH_AGENT_ID_JSON)
	 */
	public $agent_id;

	/**
	 * Unix timestamp
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_DATE)
	 */
	public $date;

	/**
	 * @var string
	 * @ParamLink(value=Constants::AUTH_SIGN_JSON)
	 */
	public $sign;

	/**
	 * @var string
	 * @ParamLink(value=Constants::AUTH_SIGN_TYPE_JSON)
	 */
	public $sign_type;

	/**
	 * @var integer
	 * @ParamLink(value=Constants::MANAGER_BASE_RESULT_CODE)
	 */
	public $res_code;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MANAGER_BASE_RESULT_MESSAGE)
	 */
	public $res_message;

	public function parseResultData( array &$result_array ) {
		if ( isset( $result_array[ Constants::AUTH_MERCHANT_ID_JSON ] ) ) {
			$this->merchant_id = $result_array[ Constants::AUTH_MERCHANT_ID_JSON ];
		}
		if ( isset( $result_array[ Constants::AUTH_AGENT_ID_JSON ] ) ) {
			$this->agent_id = $result_array[ Constants::AUTH_AGENT_ID_JSON ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_DATE ] ) ) {
			$this->date = $result_array[ Constants::PAY_ORDER_DATE ];
		}
		if ( isset( $result_array[ Constants::AUTH_SIGN_JSON ] ) ) {
			$this->sign = $result_array[ Constants::AUTH_SIGN_JSON ];
		}
		if ( isset( $result_array[ Constants::AUTH_SIGN_TYPE_JSON ] ) ) {
			$this->sign_type = $result_array[ Constants::AUTH_SIGN_TYPE_JSON ];
		}
		if ( isset( $result_array[ Constants::MANAGER_BASE_RESULT_CODE ] ) ) {
			$this->res_code = $result_array[ Constants::MANAGER_BASE_RESULT_CODE ];
		}
		if ( isset( $result_array[ Constants::MANAGER_BASE_RESULT_MESSAGE ] ) ) {
			$this->res_message = $result_array[ Constants::MANAGER_BASE_RESULT_MESSAGE ];
		}
	}
}