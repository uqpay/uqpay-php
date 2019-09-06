<?php


namespace uqpay\payment\model;


use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;
use uqpay\payment\PayMethodHelper;
use uqpay\payment\UqpayException;

class BankCard implements PaymentParameter {
	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_FIRST_NAME, required=true)
	 */
	public $first_name;

	/**
	 * @var string
	 * @ParamLink(value=Constants::BANK_CARD_LAST_NAME, required=true)
	 */
	public $last_name;

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
	 * @return array
	 * @throws UqpayException
	 */
	public function getRequestArr() {
		$result = array();
		$errors = array();
		if (empty($this->first_name)) {
			$errors[] = 'first_name';
		} else {
			$result[Constants::BANK_CARD_FIRST_NAME] = $this->first_name;
		}
		if (empty($this->last_name)) {
			$errors[] = 'last_name';
		} else {
			$result[Constants::BANK_CARD_LAST_NAME] = $this->last_name;
		}
		if (empty($this->card_num)) {
			$errors[] = 'card_num';
		} else {
			$result[Constants::BANK_CARD_CARD_NUM] = $this->card_num;
		}
		if (!empty($this->cvv)) { $result[Constants::BANK_CARD_CVV] = $this->cvv; }
		if (!empty($this->expire_month)) { $result[Constants::BANK_CARD_EXPIRE_MONTH] = $this->expire_month; }
		if (!empty($this->expire_year)) { $result[Constants::BANK_CARD_EXPIRE_YEAR] = $this->expire_year; }

		if (sizeof($errors) > 0) {
			throw new UqpayException('Payment parameters invalid: ['.implode(',', $errors).'] is required for bank card, but null');
		}
		return $result;
	}
}