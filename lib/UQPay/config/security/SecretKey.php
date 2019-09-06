<?php

namespace uqpay\payment\config\security;

use uqpay\payment\Constants;

class SecretKey {


	public $sign_type = Constants::SIGN_TYPE_RSA;

	/**
	 * RSA Key Or MD5 Salt Content
	 * Tips: for RSA, A PEM formatted key content
	 * Tips: if the content is not empty, we will ignore the path
	 * @var string
	 */
	public $content = '';

	/**
	 * Absolute path
	 * The pem file path of RSA Private Key
	 * Or The txt file path of MD5 Salt
	 * Tips: make sure you have the permission to read.
	 * @var string
	 */
	public $path = '';

	public function verify() {
		return (! empty( $this->content ) || ! empty($this->path) ) && ! empty( $this->sign_type );
	}

	public function getContent() {
		if (!empty($this->content)) {
			return $this->content;
		}
		if (!empty($this->path)) {
			return file_get_contents($this->path);
		}
		return '';
	}

	public static function builder( $key_content, $key_type = Constants::SIGN_TYPE_RSA, $key_path = '' ) {
		$key            = new self();
		$key->sign_type = $key_type;
		$key->content   = $key_content;
		$key->path = $key_path;
		return $key;
	}
}