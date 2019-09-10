<?php


namespace uqpay\payment\model;


use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class EmvcoCreator extends ManagerOptions {

	/**
	 * @var string
	 */
	public $type = Constants::QR_CHANNEL_TYPE_UNION;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * support values: {@see Constants::QR_TYPE_DYNAMIC, Constants::QR_TYPE_STATIC}
	 * @var string
	 */
	public $code_type;

	/**
	 * @var string
	 */
	public $terminal_id;

	/**
	 * @var string
	 */
	public $shop_name;

	/**
	 * @var integer
	 */
	public $amount;

	/**
	 * @var string
	 */
	public $city;

	/**
	 * @var string
	 */
	public $order_id;

	public function getRequestArr() {
		$result = parent::getRequestArr();
		$errors = array();

		if ( empty( $this->type )) {
			$errors[] = 'type';
		} else {
			$result[ Constants::EMVCO_CREATOR_TYPE ] = $this->type;
		}
		if ( empty( $this->code_type )) {
			$errors[] = 'code_type';
		} else {
			$result[ Constants::EMVCO_CREATOR_CODE_TYPE ] = $this->code_type;
		}
		if ( empty( $this->terminal_id )) {
			$errors[] = 'terminal_id';
		} else {
			$result[ Constants::EMVCO_CREATOR_TERMINAL_ID ] = $this->terminal_id;
		}
		if ( empty( $this->city )) {
			$errors[] = 'city';
		} else {
			$result[ Constants::EMVCO_CREATOR_CITY ] = $this->city;
		}
		if (!empty($this->name)) { $result[ Constants::EMVCO_CREATOR_NAME ] = $this->name; }
		if (!empty($this->shop_name)) { $result[ Constants::EMVCO_CREATOR_SHOP_NAME ] = $this->shop_name; }
		if (!empty($this->amount)) { $result[ Constants::EMVCO_CREATOR_AMOUNT ] = $this->amount; }
		if (!empty($this->order_id)) { $result[ Constants::EMVCO_CREATOR_ORDER_ID ] = $this->order_id; }
		if ( sizeof( $errors ) > 0 ) {
			throw new UqpayException( 'Payment parameters invalid: [' . implode( ',', $errors ) . '] is required, but null' );
		}

		return $result;
	}


}