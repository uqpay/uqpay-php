<?php


namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;

class VerifyResult extends GeneralResult {
	/**
	 * @var integer
	 * @ParamLink(value=Constants::RESULT_UQPAY_ORDER_ID)
	 */
	public $uqpay_order_id;

	public function parseResultData( array &$result_array ) {
		parent::parseResultData( $result_array );
		if ( isset( $result_array[ Constants::RESULT_UQPAY_ORDER_ID ] ) ) {
			$this->uqpay_order_id = $result_array[ Constants::RESULT_UQPAY_ORDER_ID ];
		}
	}


}