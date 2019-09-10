<?php


namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;

class EnrollResult extends GeneralResult {
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
	 * this field has value when the ServerHost methods
	 * @var string
	 * @ParamLink(value=Constants::SERVER_HOST_CARD_TOKEN)
	 */
	public $token;

	public function parseResultData( array &$result_array ) {
		parent::parseResultData( $result_array );
		if ( isset( $result_array[ Constants::PAY_ORDER_ID ] ) ) {
			$this->order_id = $result_array[ Constants::PAY_ORDER_ID ];
		}
		if ( isset( $result_array[ Constants::RESULT_UQPAY_ORDER_ID ] ) ) {
			$this->uqpay_order_id = $result_array[ Constants::RESULT_UQPAY_ORDER_ID ];
		}
		if ( isset( $result_array[ Constants::RESULT_CHANNEL_CODE ] ) ) {
			$this->channel_code = $result_array[ Constants::RESULT_CHANNEL_CODE ];
		}
		if ( isset( $result_array[ Constants::RESULT_CHANNEL_MESSAGE ] ) ) {
			$this->channel_msg = $result_array[ Constants::RESULT_CHANNEL_MESSAGE ];
		}
		if ( isset( $result_array[ Constants::SERVER_HOST_CARD_TOKEN ] ) ) {
			$this->token = $result_array[ Constants::SERVER_HOST_CARD_TOKEN ];
		}
	}


}