<?php

namespace uqpay\payment\config\security;

use uqpay\payment\Constants;

class SecurityConfig {

	/**
	 * Encipher the request data with Merchant/Partner private key (MD5 Salt) before send to UQPAY Service
	 * @var SecretKey
	 */
	private $encipher;

	/**
	 * Decipher the response data from UQPAY Service with UQPAY Public RSA key
	 * @var SecretKey
	 */
	private $decipher;

	/**
	 * @param string $key_content
	 * @param string $key_type
	 *
	 * @param string $key_path
	 *
	 * @see SecretKey::builder()
	 */
	public function setEncipher( $key_content, $key_type = Constants::SIGN_TYPE_RSA, $key_path = '' ) {
		if ( empty( $key_content ) && empty( $key_path ) ) {
			return;
		}
		$this->encipher = SecretKey::builder( $key_content, $key_type, $key_path );
	}

	/**
	 * @return SecretKey
	 */
	public function getEncipher() {
		return $this->encipher;
	}

	/**
	 * @param string $key_content
	 * @param string $key_type
	 *
	 * @param string $key_path
	 *
	 * @see SecretKey::builder()
	 */
	public function setDecipher( $key_content, $key_type = Constants::SIGN_TYPE_RSA, $key_path = '' ) {
		if ( empty( $key_content ) && empty( $key_path ) ) {
			return;
		}
		$this->decipher = SecretKey::builder( $key_content, $key_type, $key_path );
	}

	/**
	 * Verifies that the key is configured
	 *
	 * @return bool
	 */
	public function configured() {
		if ( empty( $this->encipher ) || empty( $this->decipher ) || ! $this->encipher->verify() || ! $this->decipher->verify() ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $target
	 *
	 * @return array
	 * @throws SecurityUqpayException
	 */
	public function sign( $target ) {
		if ( $this->configured() ) {
			$sign_str = '';
			if ( $this->encipher->sign_type == Constants::SIGN_TYPE_RSA ) {
				$private_key = openssl_get_privatekey( $this->encipher->getContent() );
				if ( ! openssl_sign( $target, $sign_str, $private_key ) ) {
					openssl_free_key( $private_key );
					throw new SecurityUqpayException( 'Error from UQPAY API: Catch unKnow error when RSA encryption, please check the key or the target string.' );
				}
				openssl_free_key( $private_key );
				$sign_str = base64_encode( $sign_str );
			} else {
				$temp_t   = $target . $this->encipher->getContent();
				$sign_str = md5( $temp_t );
			}

			return array(
				'signature_type' => $this->encipher->sign_type,
				'signature'      => $sign_str
			);
		} else {
			throw new SecurityUqpayException( 'Error from UQPAY API: Please setting your encipher secret key.' );
		}
	}

	/**
	 * @param $target
	 * @param $signature
	 *
	 * @return bool
	 * @throws SecurityUqpayException
	 */
	public function verify( $target, $signature ) {
		if ( $this->configured() ) {
			if ( $this->decipher->sign_type == Constants::SIGN_TYPE_RSA ) {
				$public_key = openssl_get_publickey( $this->decipher->getContent() );
				$result     = (bool) openssl_verify( $target, base64_decode( $signature ), $public_key );

				return $result;
			} else {
				throw new SecurityUqpayException( 'Error from UQPAY API: For this moment the UQPAY Service Only use RSA to encipher the data' );
			}
		} else {
			throw new SecurityUqpayException( 'Error from UQPAY API: Please setting your encipher secret key.' );
		}
	}

}