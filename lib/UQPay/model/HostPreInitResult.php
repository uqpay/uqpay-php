<?php


namespace uqpay\payment\model;


use uqpay\payment\Constants;

class HostPreInitResult extends ManagerBaseResult {
	/**
	 * use this token to init UQPAY-HostUI
	 * @var string
	 */
	public $token;

	public function parseResultData(array &$result_array) {
		parent::parseResultData( $result_array );
		if ( isset( $result_array[ Constants::HOST_INIT_RESULT_TOKEN ] ) ) {
			$this->token = $result_array[ Constants::HOST_INIT_RESULT_TOKEN ];
		}
	}
}