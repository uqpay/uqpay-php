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
     * @ParamLink(value=Constants::BANK_CARD_ADDRESS_COUNTRY)
     */
    public $address_country;

    /**
     * @var string
     * @ParamLink(value=Constants::BANK_CARD_EMAIL)
     */
    public $email;

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
     * @ParamLink(apiKey = Constant::BANK_CARD_ADDRESS_STATE)
     */
    public $address_state;
    /**
     * @var string
     * @ParamLink(apiKey = Constant::BANK_CARD_ADDRESS_CITY)
     */
    public $address_city;
    /**
     * @var string
     * @ParamLink(apiKey = Constant::BANK_CARD_ADDRESS)
     */
    public $address;
    /**
     * @var string
     * @ParamLink(apiKey = Constant::BANK_CARD_PHONE)
     */
    public $phone;
    /**
     * @var string
     * @ParamLink(apiKey = Constant::BANK_CARD_ZIP)
     */
    public $zip;

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
        if (!empty($this->address_country)) { $result[Constants::BANK_CARD_ADDRESS_COUNTRY] = $this->address_country; }
        if (!empty($this->email)) { $result[Constants::BANK_CARD_EMAIL] = $this->email; }
        if (!empty($this->zip)) { $result[Constants::BANK_CARD_ZIP] = $this->zip; }
        if (!empty($this->address)) { $result[Constants::BANK_CARD_ADDRESS] = $this->address; }
        if (!empty($this->phone)) { $result[Constants::BANK_CARD_PHONE] = $this->phone; }
        if (!empty($this->address_city)) { $result[Constants::BANK_CARD_ADDRESS_CITY] = $this->address_city; }
        if (!empty($this->address_state)) { $result[Constants::BANK_CARD_ADDRESS_STATE] = $this->address_state; }

		if (sizeof($errors) > 0) {
			throw new UqpayException('Payment parameters invalid: ['.implode(',', $errors).'] is required for bank card, but null');
		}
		return $result;
	}
}