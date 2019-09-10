<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class EnrollOrder extends PaymentOptions {
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
	 * the verify code you get after request the verify api
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_HOST_VERIFY_CODE, required=true)
	 */
	public $verify_code;

	/**
	 * the uqpay order id, when you request for the verify code you will get it
	 * @var integer
	 * @ParamLink(value=Constants::MERCHANT_HOST_ENROLL_CODE_UQPAY_ID, required=true)
	 */
	public $verify_code_id;

	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_CVV)
	 */
	public $cvv;

	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_CARD_NUM, required=true)
	 */
	public $card_num;

	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_EXPIRE_MONTH)
	 */
	public $expire_month;

	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_EXPIRE_YEAR)
	 */
	public $expire_year;

	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_PHONE)
	 */
	public $phone;

	/**
	 * consumer client ip
	 * @var string
	 * @ParamLink(value=Constants::PAY_ORDER_CLIENT_IP, required=true)
	 */
	public $client_ip;

	/**
	 * EnrollOrder constructor.
	 */
	public function __construct() {
		$this->trade_type=Constants::TRADE_TYPE_ENROLL;
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
		if ( empty( $this->verify_code ) ) {
			$errors[] = 'verify_code';
		} else {
			$result[ Constants::MERCHANT_HOST_VERIFY_CODE ] = $this->verify_code;
		}
		if ( empty( $this->verify_code_id ) || 0 == $this->verify_code_id ) {
			$errors[] = 'verify_code_id';
		} else {
			$result[ Constants::MERCHANT_HOST_ENROLL_CODE_UQPAY_ID ] = $this->verify_code_id;
		}
		if ( empty( $this->card_num ) ) {
			$errors[] = 'card_num';
		} else {
			$result[ Constants::BANK_CARD_CARD_NUM ] = $this->card_num;
		}
		if ( ! empty( $this->cvv ) ) {
			$result[ Constants::BANK_CARD_CVV ] = $this->cvv;
		}
		if ( ! empty( $this->expire_month ) ) {
			$result[ Constants::BANK_CARD_EXPIRE_MONTH ] = $this->expire_month;
		}
		if ( ! empty( $this->expire_year ) ) {
			$result[ Constants::BANK_CARD_EXPIRE_YEAR ] = $this->expire_year;
		}
		if ( ! empty( $this->phone ) ) {
			$result[ Constants::BANK_CARD_PHONE ] = $this->phone;
		}
		if ( empty( $this->client_ip ) ) {
			$errors[] = 'client_ip';
		} else {
			$result[ Constants::PAY_ORDER_CLIENT_IP ] = $this->client_ip;
		}
		if ( sizeof( $errors ) > 0 ) {
			throw new UqpayException( 'Payment parameters invalid: [' . implode( ',', $errors ) . '] is required, but null' );
		}

		return $result;
	}


}