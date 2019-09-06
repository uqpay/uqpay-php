<?php


namespace uqpay\payment\model;


interface PaymentParameter {
	/**
	 * @return array
	 */
	public function getRequestArr();
}