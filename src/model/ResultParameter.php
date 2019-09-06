<?php


namespace uqpay\payment\model;


interface ResultParameter {
	/**
	 * @param array $result_array
	 *
	 */
	public function parseResultData(array &$result_array);
}