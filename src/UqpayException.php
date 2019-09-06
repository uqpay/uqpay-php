<?php


namespace uqpay\payment;
use Exception;

class UqpayException extends Exception {

	/** @var string sanitized/localized error message */
	protected $localized_message;

	/**
	 * UqpayException constructor.
	 *
	 * @param string $message
	 * @param string $localized_message
	 */
	public function __construct($message = '', $localized_message = '') {
		$this->localized_message = $localized_message;
		parent::__construct($message);
	}

	public function getLocalizedMessage() {
		return $this->localized_message;
	}
}