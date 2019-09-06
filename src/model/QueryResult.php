<?php


namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;


class QueryResult extends GeneralResult {
	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_ID)
	 */
	public $order_id;

	/**
	 *
	 * @var integer
	 * @ParamLink(value=Constants::RESULT_UQPAY_ORDER_ID)
	 */
	public $uqpay_order_id;

	/**
	 * related order id (uqpay order), if this is a refund/cancel/withdraw order
	 * @var string
	 * @ParamLink(value=Constants::RESULT_UQPAY_RELATED_ID)
	 */
	public $uqpay_related_id;

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
	 * Unix timestamp
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_FINISH_TIME)
	 */
	public $finish_time;

	/**
	 * @var int
	 * @ParamLink(value=Constants::PAY_OPTIONS_SCAN_TYPE)
	 */
	public $scan_type;

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

	public function parseResultData( array &$result_array ) {
		parent::parseResultData( $result_array );
		if ( isset( $result_array[ Constants::PAY_ORDER_ID ] ) ) {
			$this->order_id = $result_array[ Constants::PAY_ORDER_ID ];
		}
		if ( isset( $result_array[ Constants::RESULT_UQPAY_ORDER_ID ] ) ) {
			$this->uqpay_order_id = $result_array[ Constants::RESULT_UQPAY_ORDER_ID ];
		}
		if ( isset( $result_array[ Constants::RESULT_UQPAY_RELATED_ID ] ) ) {
			$this->uqpay_related_id = $result_array[ Constants::RESULT_UQPAY_RELATED_ID ];
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
		if ( isset( $result_array[ Constants::PAY_ORDER_FINISH_TIME ] ) ) {
			$this->finish_time = $result_array[ Constants::PAY_ORDER_FINISH_TIME ];
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_CHANNEL_INFO ] ) && !empty($result_array[ Constants::PAY_ORDER_CHANNEL_INFO ])) {
			$this->channel_info = json_decode($result_array[ Constants::PAY_ORDER_CHANNEL_INFO ], true);
		}
		if ( isset( $result_array[ Constants::PAY_ORDER_EXTEND_INFO ] ) && !empty($result_array[ Constants::PAY_ORDER_EXTEND_INFO ])) {
			$this->extend_info = json_decode($result_array[ Constants::PAY_ORDER_EXTEND_INFO ], true);
		}

		if ( isset( $result_array[ Constants::PAY_OPTIONS_SCAN_TYPE ] ) ) {
			$this->scan_type = $result_array[ Constants::PAY_OPTIONS_SCAN_TYPE ];
		}
	}
}