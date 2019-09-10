<?php


namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class MerchantRegister extends ManagerOptions {
	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_NAME, required=true)
	 */
	public $name;

	/**
	 * merchant abbreviation， length between 1 and 8
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_ABBR, required=true)
	 */
	public $abbr;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_LOGIN_EMAIL, required=true)
	 */
	public $register_email;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_COMPANY_NAME, required=true)
	 */
	public $company_name;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_COMPANY_ID, required=true)
	 */
	public $company_register_num;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_COMPANY_ADDRESS, required=true)
	 */
	public $company_register_address;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_COMPANY_COUNTRY, required=true)
	 */
	public $company_register_country;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_MCC, required=true)
	 */
	public $mcc;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_WEBSITE)
	 */
	public $website;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_CONTACT)
	 */
	public $contact;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_MOBILE)
	 */
	public $mobile;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_REGISTER_BUSINESS_EMAIL)
	 */
	public $business_email;

	public function getRequestArr() {
		$result = parent::getRequestArr();
		$errors = array();
		if ( empty( $this->name ) ) {
			$errors[] = 'name';
		} else {
			$result[ Constants::MERCHANT_REGISTER_NAME ] = $this->name;
		}
		if ( empty( $this->abbr ) ) {
			$errors[] = 'abbr';
		} else {
			$result[ Constants::MERCHANT_REGISTER_ABBR ] = $this->abbr;
		}
		if ( empty( $this->register_email ) ) {
			$errors[] = 'register_email';
		} else {
			$result[ Constants::MERCHANT_REGISTER_LOGIN_EMAIL ] = $this->register_email;
		}
		if ( empty( $this->company_name ) ) {
			$errors[] = 'company_name';
		} else {
			$result[ Constants::MERCHANT_REGISTER_COMPANY_NAME ] = $this->company_name;
		}
		if ( empty( $this->company_register_num ) ) {
			$errors[] = 'company_register_num';
		} else {
			$result[ Constants::MERCHANT_REGISTER_COMPANY_ID ] = $this->company_register_num;
		}
		if ( empty( $this->company_register_address ) ) {
			$errors[] = 'company_register_address';
		} else {
			$result[ Constants::MERCHANT_REGISTER_COMPANY_ADDRESS ] = $this->company_register_address;
		}
		if ( empty( $this->company_register_country ) ) {
			$errors[] = 'company_register_country';
		} else {
			$result[ Constants::MERCHANT_REGISTER_COMPANY_COUNTRY ] = $this->company_register_country;
		}
		if ( empty( $this->mcc ) ) {
			$errors[] = 'mcc';
		} else {
			$result[ Constants::MERCHANT_REGISTER_MCC ] = $this->mcc;
		}
		if ( ! empty( $this->website ) ) {
			$result[ Constants::MERCHANT_REGISTER_WEBSITE ] = $this->website;
		}
		if ( ! empty( $this->contact ) ) {
			$result[ Constants::MERCHANT_REGISTER_CONTACT ] = $this->contact;
		}
		if ( ! empty( $this->mobile ) ) {
			$result[ Constants::MERCHANT_REGISTER_MOBILE ] = $this->mobile;
		}
		if ( ! empty( $this->business_email ) ) {
			$result[ Constants::MERCHANT_REGISTER_BUSINESS_EMAIL ] = $this->business_email;
		}

		if ( sizeof( $errors ) > 0 ) {
			throw new UqpayException( 'Payment parameters invalid: [' . implode( ',', $errors ) . '] is required, but null' );
		}

		return $result;
	}


}