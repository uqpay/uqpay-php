<?php


namespace uqpay\payment\model;


use uqpay\payment\Constants;

class ConfigMethodResult extends ManagerBaseResult {
	/**
	 * @var boolean
	 */
	public $is_success;

	/**
	 * @var int
	 */
	public $method_id;

	public function parseResultData( array &$result_array ) {
		parent::parseResultData( $result_array );
		if ( isset( $result_array[ Constants::MERCHANT_CONFIG_RESULT_SUCCESS ] ) ) {
			$this->is_success = $result_array[ Constants::MERCHANT_CONFIG_RESULT_SUCCESS ];
		}
		if ( isset( $result_array[ Constants::MERCHANT_CONFIG_RESULT_METHOD_ID ] ) ) {
			$this->method_id = $result_array[ Constants::MERCHANT_CONFIG_RESULT_METHOD_ID ];
		}
	}


}