<?php
namespace uqpay\payment\model;

use uqpay\payment\annotations\ParamLink;
use uqpay\payment\Constants;
use uqpay\payment\UqpayException;

class ConfigMerPayment extends ManagerOptions {
	/**
	 * @var int
	 * @ParamLink(value=Constants::MERCHANT_CONFIG_METHOD_ID, required=true)
	 */
    public $method_id;

	/**
	 * fee template id, u can set a fee template on UQPAY Partner Dashboard
	 * @var int
	 * @ParamLink(value=Constants::MERCHANT_CONFIG_FEE_TEMP_ID, required=true)
	 */
    public $fee_temp_id;

	/**
	 * if true, it will be active automatic
	 * @var boolean
	 * @ParamLink(value=Constants::MERCHANT_CONFIG_DEF_OPEN, required=true)
	 */
    public $open = false;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_CONFIG_MER_NAME, required=true)
	 */
    public $merchant_name;

	/**
	 * @var string
	 * @ParamLink(value=Constants::MERCHANT_CONFIG_MER_ABBR, required=true)
	 */
    public $merchant_abbr;

    public function getRequestArr() {
	    $result = parent::getRequestArr();
	    $errors = array();
	    if ( empty( $this->method_id ) ) {
		    $errors[] = 'method_id';
	    } else {
		    $result[ Constants::MERCHANT_CONFIG_METHOD_ID ] = $this->method_id;
	    }
	    if ( empty( $this->fee_temp_id ) ) {
		    $errors[] = 'fee_temp_id';
	    } else {
		    $result[ Constants::MERCHANT_CONFIG_FEE_TEMP_ID ] = $this->fee_temp_id;
	    }
	    if ( empty( $this->merchant_name ) ) {
		    $errors[] = 'merchant_name';
	    } else {
		    $result[ Constants::MERCHANT_CONFIG_MER_NAME ] = $this->merchant_name;
	    }
	    if ( empty( $this->merchant_abbr ) ) {
		    $errors[] = 'merchant_abbr';
	    } else {
		    $result[ Constants::MERCHANT_CONFIG_MER_ABBR ] = $this->merchant_abbr;
	    }
	    $result[ Constants::MERCHANT_CONFIG_DEF_OPEN ] = $this->open;

	    if ( sizeof( $errors ) > 0 ) {
		    throw new UqpayException( 'Payment parameters invalid: [' . implode( ',', $errors ) . '] is required, but null' );
	    }

	    return $result;
    }
}