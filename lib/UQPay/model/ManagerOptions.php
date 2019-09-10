<?php


namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\config\ConfigOfAPI;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;


class ManagerOptions implements PaymentParameter {
	/**
	 * for merchant user this value is equal {@see ConfigOfAPI::$uqpay_id} and will be valued automatic
	 * @var int
	 * @ParamLink(value=Constants::AUTH_MERCHANT_ID_JSON, required=true)
	 */
	public $merchant_id;

	/**
	 * Unix timestamp
	 * @var integer
	 * @ParamLink(value=Constants::PAY_ORDER_DATE, required=true)
	 */
	public $date;

	/**
	 * @return array
	 * @throws UqpayException
	 */
	public function getRequestArr() {
		$result = array();
		$errors = array();
		if ( !empty( $this->merchant_id )) {
			$result[ Constants::AUTH_MERCHANT_ID_JSON ] = $this->merchant_id;
		}
		if ( empty( $this->date ) || 0 == $this->date ) {
			$errors[] = 'date';
		} else {
			$result[ Constants::PAY_ORDER_DATE ] = $this->date;
		}
		if ( sizeof( $errors ) > 0 ) {
			throw new UqpayException( 'Payment parameters invalid: [' . implode( ',', $errors ) . '] is required, but null' );
		}

		return $result;
	}
}