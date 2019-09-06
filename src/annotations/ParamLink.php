<?php

namespace uqpay\payment\annotations;


/**
 * TODO use for next version
 * @Annotation
 * @Target("PROPERTY")
 */
final class ParamLink {
	/**
	 * @var string
	 * @Required
	 */
	public $value;

	/** @var string */
	public $target_type;

	/** @var bool */
	public $required;
}