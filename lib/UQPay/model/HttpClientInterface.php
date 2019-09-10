<?php


namespace uqpay\payment\model;


interface HttpClientInterface {
	/**
	 * The Format of headers like this:
	 * array('content-type'=>'text/plain', 'content-length' => '100')
	 * @param array $headers
	 * @param string $body
	 * @param string $url
	 *
	 * @return string of the response body
	 */
	public function post(array $headers, $body, $url);
}