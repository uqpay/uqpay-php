<?php


namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class HostPayOrder extends PaymentOrder {
	/**
	 * get this token by Host-UI
	 * @var string
	 * @ParamLink(value=Constants::HOST_PAY_CARD_TOKEN, required=true)
	 */
	public $card_token;

	/**
	 * @return array
	 * @throws UqpayException
	 */
	public function getRequestArr() {
		$result = parent::getRequestArr();
		$errors = array();
		if ( empty( $this->card_token ) ) {
			$errors[] = 'card_token';
		} else {
			$result[ Constants::HOST_PAY_CARD_TOKEN ] = $this->card_token;
		}

		if ( sizeof( $errors ) > 0 ) {
			throw new UqpayException( 'Payment parameters invalid: [' . implode( ',', $errors ) . '] is required, but null' );
		}

		return $result;
	}
}