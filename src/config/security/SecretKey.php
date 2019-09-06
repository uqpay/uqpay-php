<?php

namespace uqpay\payment\config\security;

use uqpay\payment\Constants;

class SecretKey {


	public $sign_type = Constants::SIGN_TYPE_RSA;

	/**
	 * RSA Key Or MD5 Salt Content
	 * Tips: for RSA, A PEM formatted key content
	 * @var string
	 */
	public $content = '';

	public function verify() {
		return ! empty( $this->content ) && ! empty( $this->sign_type );
	}

	public static function builder( $key_content, $key_type = Constants::SIGN_TYPE_RSA ) {
		$key            = new self();
		$key->sign_type = $key_type;
		$key->content   = $key_content;

		return $key;
	}
}