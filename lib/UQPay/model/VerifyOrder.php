<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\config\ConfigOfAPI;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class VerifyOrder extends PaymentOptions {
	/**
	 * for merchant user this value is equal {@see ConfigOfAPI::$uqpay_id} and will be valued automatic
	 * @var int
	 * @ParamLink(value=Constants::AUTH_MERCHANT_ID, required=true)
	 */
	public $merchant_id;

	/**
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_ID, required=true)
	 */
	public $order_id;

	/**
	 * Unix timestamp
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_DATE, required=true)
	 */
	public $date;

	/**
	 * consumer client ip
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_CLIENT_IP, required=true)
	 */
	public $client_ip;

	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_CARD_NUM, required=true)
	 */
	public $card_num;

	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_PHONE)
	 */
	public $phone;

	/**
	 * VerifyOrder constructor.
	 */
	public function __construct() {
		$this->trade_type = Constants::TRADE_TYPE_VERIFY_CODE;
	}


	public function getRequestArr() {
		$result = parent::getRequestArr();
		$errors = array();
		if ( empty( $this->merchant_id ) || 0 == $this->merchant_id ) {
			$errors[] = 'merchant_id';
		} else {
			$result[ Constants::AUTH_MERCHANT_ID ] = $this->merchant_id;
		}
		if ( empty( $this->order_id ) ) {
			$errors[] = 'order_id';
		} else {
			$result[ Constants::PAY_ORDER_ID ] = $this->order_id;
		}
		if ( empty( $this->date ) || 0 == $this->date ) {
			$errors[] = 'date';
		} else {
			$result[ Constants::PAY_ORDER_DATE ] = $this->date;
		}
		if ( empty( $this->client_ip ) ) {
			$errors[] = 'client_ip';
		} else {
			$result[ Constants::PAY_ORDER_CLIENT_IP ] = $this->client_ip;
		}
		if ( empty( $this->phone ) ) {
			$errors[] = 'phone';
		} else {
			$result[ Constants::BANK_CARD_PHONE ] = $this->phone;
		}
		if ( sizeof( $errors ) > 0 ) {
			throw new UqpayException( 'Payment parameters invalid: [' . implode( ',', $errors ) . '] is required, but null' );
		}

		return $result;
	}


}