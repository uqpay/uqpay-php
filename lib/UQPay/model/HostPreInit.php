<?php


namespace uqpay\payment\model;


use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class HostPreInit extends ManagerOptions {

	/**
	 * the unique key of your customer
	 * @var string
	 */
	public $customer;

	public function getRequestArr() {
		$result = parent::getRequestArr();
		$errors = array();
		if ( empty( $this->customer )) {
			$errors[] = 'customer';
		} else {
			$result[ Constants::HOST_INIT_CUSTOMER_KEY ] = $this->customer;
		}
		if ( sizeof( $errors ) > 0 ) {
			throw new UqpayException( 'Host init parameters invalid: [' . implode( ',', $errors ) . '] is required, but null' );
		}

		return $result;
	}

}