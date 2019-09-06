<?php


namespace uqpay\payment\config;

use uqpay\payment\config\security\SecurityConfig;
use uqpay\payment\Constants;

class ConfigOfAPI {
	/**
	 * default the api will run as a test environment
	 * @var string
	 */
	public $env = Constants::ENV_MODE_TEST;

	/**
	 * @var string
	 */
	public $member_type = Constants::MEMBER_TYPE_MER;

	/**
	 * for Merchant this is the merchant ID
	 * for Agent this is the agent ID
	 * @var int
	 */
	public $uqpay_id = 0;

	/**
	 * security config for production
	 * @var SecurityConfig
	 */
	private $security_prod;

	/**
	 * security config for test
	 * @var SecurityConfig
	 */
	private $security_test;

	public function getSecurity() {
		if ( $this->env == Constants::ENV_MODE_TEST ) {
			return $this->security_test;
		}
		if ( $this->env == Constants::ENV_MODE_PROD ) {
			return $this->security_prod;
		}

		return null;
	}

	public function getPaymentApiUrl( $api ) {
		return Constants::PAYMENT_API_ROOT[ $this->env ] . $api;
	}

	public function getOperationApiUrl( $api ) {
		return Constants::OPERATION_API_ROOT[ $this->env ] . $api;
	}

	public function getCashierApiUrl( $api ) {
		return Constants::CASHIER_API_ROOT[ $this->env ] . $api;
	}

	/**
	 * build UQPAY API Config
	 *
	 * @param string $prv_key_content the private key of user or the md5Key, check the files download from the UQPAY Dashboard [yourID_prv.pem or md5Key.txt]
	 * @param string $prv_key_type 'RSA'/'MD5'
	 * @param string $uqpay_pub_key_content check the file UQPAY_pub.pem
	 * @param int $uqpay_id
	 * @param bool $is_mer for merchant set true otherwise false
	 * @param bool $is_test
	 *
	 * @return ConfigOfAPI
	 */
	public static function builder( $prv_key_content, $prv_key_type, $uqpay_pub_key_content, $uqpay_id = 0, $is_test = true, $is_mer = true ) {
		$security = new SecurityConfig();
		$security->setEncipher( $prv_key_content, $prv_key_type );
		$security->setDecipher( $uqpay_pub_key_content );
		$result              = new self();
		$result->uqpay_id    = $uqpay_id;
		$result->member_type = $is_mer ? Constants::MEMBER_TYPE_MER : Constants::MEMBER_TYPE_AGENT;

		if ( $is_test ) {
			$result->env           = Constants::ENV_MODE_TEST;
			$result->security_test = $security;
		} else {
			$result->env           = Constants::ENV_MODE_PROD;
			$result->security_prod = $security;
		}

		return $result;
	}

	public function isAvailable() {
		return $this->uqpay_id != 0
		       && (
			       ( $this->env == Constants::ENV_MODE_TEST && ! empty( $this->security_test ) && $this->security_test->configured() )
			       || ( $this->env == Constants::ENV_MODE_PROD && ! empty( $this->security_prod ) && $this->security_prod->configured() )

		       );
	}
}