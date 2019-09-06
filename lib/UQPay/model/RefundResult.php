<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;

class RefundResult extends GeneralResult {

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_ID)
	 */
	public $order_id;

	/**
	 * this is a new uqpay order id, not the payment one
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
	 * @ParamLink(value=Constants::RESULT_STATE)
	 */
	public $state;

	/**
	 * @var array
	 * @ParamLink(value=Constants::PAY_ORDER_EXTEND_INFO, target_type="JSON")
	 */
	public $extend_info;

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
		if ( isset( $result_array[ Constants::RESULT_STATE ] ) ) {
			$this->state = $result_array[ Constants::RESULT_STATE ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_EXTEND_INFO ] ) && !empty($result_array[ Constants::PAY_ORDER_EXTEND_INFO ])) {
			$this->extend_info = json_decode($result_array[ Constants::PAY_ORDER_EXTEND_INFO ], true);
		}
	}


}