<?php


namespace uqpay\payment\model;


use uqpay\payment\Constants;

class EmvcoCreatorResult extends ManagerBaseResult {

	/**
	 * @var integer
	 */
	public $code_id;

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $payload;

	/**
	 * @var string
	 */
	public $terminal_id;

	/**
	 * @var string
	 */
	public $content;

	public function parseResultData( array &$result_array ) {
		parent::parseResultData( $result_array );
		if ( isset( $result_array[ Constants::EMVCO_CREATOR_RESULT_CODE_ID ] ) ) {
			$this->code_id = $result_array[ Constants::EMVCO_CREATOR_RESULT_CODE_ID ];
		}
		if ( isset( $result_array[ Constants::EMVCO_CREATOR_TYPE ] ) ) {
			$this->type = $result_array[ Constants::EMVCO_CREATOR_TYPE ];
		}
		if ( isset( $result_array[ Constants::EMVCO_CREATOR_NAME ] ) ) {
			$this->name = $result_array[ Constants::EMVCO_CREATOR_NAME ];
		}
		if ( isset( $result_array[ Constants::EMVCO_CREATOR_RESULT_PAYLOAD ] ) ) {
			$this->payload = $result_array[ Constants::EMVCO_CREATOR_RESULT_PAYLOAD ];
		}
		if ( isset( $result_array[ Constants::EMVCO_CREATOR_TERMINAL_ID ] ) ) {
			$this->terminal_id = $result_array[ Constants::EMVCO_CREATOR_TERMINAL_ID ];
		}
		if ( isset( $result_array[ Constants::EMVCO_CREATOR_RESULT_CONTENT ] ) ) {
			$this->content = $result_array[ Constants::EMVCO_CREATOR_RESULT_CONTENT ];
		}
	}


}