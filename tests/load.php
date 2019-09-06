<?php
spl_autoload_register( function ( $class_name ) {
	$class_name = str_replace('uqpay\payment', 'lib/UQPay', $class_name);
	require_once dirname( __FILE__ ) . '/../'.str_replace('\\', '/', $class_name) . '.php';
} );
