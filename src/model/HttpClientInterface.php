<?php


namespace uqpay\payment\model;


interface HttpClientInterface {
	/**
	 * @param array $headers
	 * @param string $body
	 * @param string $url
	 *
	 * @return string the response body
	 */
	public function post(array $headers, $body, $url);
}