<?php


namespace uqpay\payment;

use ReflectionException;
use uqpay\payment\config\ConfigOfAPI;
use uqpay\payment\config\security\SecurityUqpayException;
use uqpay\payment\model\BankCard;
use uqpay\payment\model\CancelResult;
use uqpay\payment\model\CashierRequest;
use uqpay\payment\model\EmvcoCreator;
use uqpay\payment\model\EmvcoCreatorResult;
use uqpay\payment\model\EnrollOrder;
use uqpay\payment\model\EnrollResult;
use uqpay\payment\model\HostPayOrder;
use uqpay\payment\model\HostPreInit;
use uqpay\payment\model\HostPreInitResult;
use uqpay\payment\model\HttpClientInterface;
use uqpay\payment\model\ManagerBaseResult;
use uqpay\payment\model\MerchantRegister;
use uqpay\payment\model\OrderCancel;
use uqpay\payment\model\OrderQuery;
use uqpay\payment\model\OrderRefund;
use uqpay\payment\model\PaymentOrder;
use uqpay\payment\model\PaymentResult;
use uqpay\payment\model\QueryResult;
use uqpay\payment\model\RefundResult;
use uqpay\payment\model\VerifyOrder;
use uqpay\payment\model\VerifyResult;

class Gateway {

	/**
	 * @var ConfigOfAPI
	 */
	private $config;

	/**
	 * @var HttpClientInterface
	 */
	private $http_client;

	/**
	 * UqpayAPI constructor.
	 *
	 * @param ConfigOfAPI $config
	 */
	public function __construct( ConfigOfAPI $config ) {
		$this->config = $config;
	}

	public function setHttpClient( HttpClientInterface $http_client ) {
		$this->http_client = $http_client;
	}

	public function isAvailable() {
		return ! empty( $this->config ) && $this->config->isAvailable();
	}

	/**
	 * @return ConfigOfAPI
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @param array $body
	 * @param string $url
	 *
	 * @param bool $is_json
	 *
	 * @return array|mixed|object
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	private function doHttpPost( array $body, $url, $is_json = false ) {
		$headers['UQPAY-Version'] = '1.0.0';
		if ( $is_json ) {
			$headers['content-type'] = 'application/json;charset=UTF-8';
			$headers['accept'] = 'application/json;charset=UTF-8';
			$request_body = json_encode($body, JSON_UNESCAPED_SLASHES);
		} else {
			$headers['content-type'] = 'application/x-www-form-urlencoded;charset=UTF-8';
			$request_body = http_build_query( $body );
		}
		$res_body     = $this->http_client->post( $headers, $request_body, $url );
		$result_array = json_decode( $res_body, true );
		$verify       = $is_json ? ModelHelper::verifyManagerResult( $res_body, $result_array[ Constants::AUTH_SIGN_JSON ], $this->config->getSecurity() )
			: ModelHelper::verifyPaymentResult( $result_array, $this->config->getSecurity() );
		if ( $verify ) {
			return $result_array;
		}
		throw new UqpayException( 'Verify payment result failed: ' . $res_body );
	}

	/**
	 * @param array $params_array
	 * @param string $url
	 * @param $result_class_name
	 *
	 * @return object
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	private function directFormPost( array $params_array, $url, $result_class_name ) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_AGENT ) {
			$params_array[ Constants::AUTH_AGENT_ID ] = $this->config->uqpay_id;
		}
		ModelHelper::signRequestParams( $params_array, $this->config->getSecurity() );
		$result_array = $this->doHttpPost( $params_array, $url );

		return ModelHelper::parseResultData( $result_array, $result_class_name );
	}

	/**
	 * @param array $params_array
	 * @param $url
	 * @param $result_class_name
	 *
	 * @return object
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	private function directJsonPost( array $params_array, $url, $result_class_name ) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_AGENT ) {
			$params_array[ Constants::AUTH_AGENT_ID_JSON ] = $this->config->uqpay_id;
		}
		ModelHelper::signRequestParams( $params_array, $this->config->getSecurity(), true );
		$result_array = $this->doHttpPost( $params_array, $url, true );

		return ModelHelper::parseResultData( $result_array, $result_class_name );
	}

	/**
	 * @param PaymentOrder $payment_order
	 *
	 * @return PaymentResult
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	private function redirectPayment( PaymentOrder &$payment_order ) {
		$params_array = ModelHelper::assemblyOrderData( $payment_order );
		if ( empty( $payment_order->return_url ) ) {
			throw new UqpayException( 'Payment parameters invalid: [return_url] is required for redirect payment, but is null' );
		}
		ModelHelper::signRequestParams( $params_array, $this->config->getSecurity() );

		return PaymentResult::builderRedirectResult( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_PAY ) );
	}

	/**
	 * @param PaymentOrder $payment_order
	 *
	 * @return object|PaymentResult
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	private function onlineQRCodePayment( PaymentOrder &$payment_order ) {
		$params_array = ModelHelper::assemblyOrderData( $payment_order );
		if ( empty( $payment_order->scan_type ) ) {
			throw new UqpayException( 'Payment parameters invalid: [scan_type] is required for online QR Code payment, but is null' );
		}
		if ( empty( $payment_order->identity ) && $payment_order->scan_type == Constants::QR_CODE_SCAN_BY_MERCHANT ) {
			throw new UqpayException( 'Payment parameters invalid: [identity] is required for online QR Code payment when merchant scan consumer, but is null' );
		}

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_PAY ), PaymentResult::class );
	}

	/**
	 * @param PaymentOrder $payment_order
	 *
	 * @return object|PaymentResult
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	private function offlineQRCodePayment( PaymentOrder &$payment_order ) {
		$params_array = ModelHelper::assemblyOrderData( $payment_order );
		$errors       = array();
		if ( empty( $payment_order->identity ) ) {
			$errors[] = 'identity';
		}
		if ( empty( $payment_order->merchant_city ) ) {
			$errors[] = 'merchant_city';
		}
		if ( empty( $payment_order->terminal_id ) ) {
			$errors[] = 'terminal_id';
		}
		if ( sizeof( $errors ) > 0 ) {
			throw new UqpayException( 'Payment parameters invalid: [' . implode( ',', $errors ) . '] is required for offline QR Code payment, but null' );
		}

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_PAY ), PaymentResult::class );
	}

	/**
	 * @param PaymentOrder $payment_order
	 * @param BankCard $bank_card
	 *
	 * @return object|PaymentResult
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	private function bankCardPayment( PaymentOrder &$payment_order, BankCard $bank_card ) {
		$params_array = ModelHelper::assemblyOrderData( $payment_order, $bank_card );

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_PAY ), PaymentResult::class );
	}

	/**
	 * @param PaymentOrder $payment_order
	 *
	 * @return object|PaymentResult
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	private function inAppPayment( PaymentOrder &$payment_order ) {
		$params_array = ModelHelper::assemblyOrderData( $payment_order );
		if ( $payment_order->client_type == Constants::PAYMENT_SUPPORTED_CLIENT_PC ) {
			throw new UqpayException( 'Payment parameters invalid: uqpay in-app payment not support pc client' );
		}

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_PAY ), PaymentResult::class );
	}

	/****
	 * Payment
	 ****/

	/**
	 * @param PaymentOrder $payment_order
	 *
	 * @param BankCard|null $bank_card
	 *
	 * @return object|PaymentResult
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function pay( PaymentOrder $payment_order, BankCard $bank_card = null ) {
		$payment_order->trade_type = Constants::TRADE_TYPE_PAY;
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$payment_order->merchant_id = $this->config->uqpay_id;
		}
		$scene = PayMethodHelper::SCENE_OF_METHOD[ $payment_order->method_id ];
		if ( empty( $scene ) ) {
			throw new UqpayException( 'UnSupport Payment method: ' . $payment_order->method_id );
		}
		switch ( $scene ) {
			case PayMethodHelper::SCENES_REDIRECT_PAY:
				return $this->redirectPayment( $payment_order );
			case PayMethodHelper::SCENES_QR:
				return $this->onlineQRCodePayment( $payment_order );
			case PayMethodHelper::SCENES_OFFLINE_QR:
				return $this->offlineQRCodePayment( $payment_order );
			case PayMethodHelper::SCENES_CREDIT_CARD:
				if ( $bank_card == null ) {
					throw new UqpayException( 'Payment parameters invalid: bank card info is required, but null' );
				}

				return $this->bankCardPayment( $payment_order, $bank_card );
			case PayMethodHelper::SCENES_IN_APP:
				return $this->inAppPayment( $payment_order );
		}

		return null;
	}

	/**
	 * Generate the cashier Link
	 *
	 * @param CashierRequest $cashier_request
	 *
	 * @return string
	 * @throws SecurityUqpayException
	 */
	public function cashier( CashierRequest $cashier_request ) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$cashier_request->merchant_id = $this->config->uqpay_id;
		}
		$params_array = ModelHelper::assemblyOrderData( $cashier_request );
		ModelHelper::signRequestParams( $params_array, $this->config->getSecurity() );

		return $this->config->getCashierApiUrl( '/?' . http_build_query( $params_array ) );
	}

	/****
	 * Order Operation
	 ****/

	/**
	 * @param OrderRefund $order_refund
	 *
	 * @return object|RefundResult
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function refund( OrderRefund $order_refund ) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$order_refund->merchant_id = $this->config->uqpay_id;
		}
		$params_array = ModelHelper::assemblyOrderData( $order_refund );

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_REFUND ), RefundResult::class );
	}

	/**
	 * @param OrderCancel $order_cancel
	 *
	 * @return object|CancelResult
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function cancel( OrderCancel $order_cancel ) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$order_cancel->merchant_id = $this->config->uqpay_id;
		}
		$params_array = ModelHelper::assemblyOrderData( $order_cancel );

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_CANCEL ), CancelResult::class );
	}

	/**
	 * @param OrderQuery $order_query
	 *
	 * @return object
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function query( OrderQuery $order_query ) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$order_query->merchant_id = $this->config->uqpay_id;
		}
		$params_array = ModelHelper::assemblyOrderData( $order_query );

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_QUERY ), QueryResult::class );
	}

	/****
	 * Bank Card Enroll
	 ****/

	/**
	 * @param EnrollOrder $enroll_order
	 *
	 * @return EnrollResult|object|null
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function enroll( EnrollOrder $enroll_order ) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$enroll_order->merchant_id = $this->config->uqpay_id;
		}
		$scene = PayMethodHelper::SCENE_OF_METHOD[ $enroll_order->method_id ];
		switch ( $scene ) {
			case PayMethodHelper::SCENES_MERCHANT_HOST:
			case PayMethodHelper::SCENES_SERVER_HOST:
				$params_array = ModelHelper::assemblyOrderData( $enroll_order );

				return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_ENROLL ), EnrollResult::class );
		}

		return null;
	}

	/**
	 * @param VerifyOrder $verify_order
	 *
	 * @return VerifyResult|object
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function verify( VerifyOrder $verify_order ) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$verify_order->merchant_id = $this->config->uqpay_id;
		}
		$params_array = ModelHelper::assemblyOrderData( $verify_order );

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_VERIFY ), VerifyResult::class );
	}

	/****
	 * Merchant Manager API
	 ****/

	/**
	 * @param MerchantRegister $merchant_register
	 *
	 * @return ManagerBaseResult|object
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function register(MerchantRegister $merchant_register) {
		$params_array = ModelHelper::assemblyOrderData( $merchant_register );
		return $this->directJsonPost($params_array, $this->config->getOperationApiUrl(Constants::APPGATE_API_MERCHANT_REGISTER), ManagerBaseResult::class);
	}


	/****
	 * EMVCO Manager API
	 ****/

	/**
	 * @param EmvcoCreator $emvco_creator
	 *
	 * @return EmvcoCreatorResult|object
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function createQRCode(EmvcoCreator $emvco_creator) {
		$params_array = ModelHelper::assemblyOrderData( $emvco_creator );
		return $this->directJsonPost($params_array, $this->config->getOperationApiUrl(Constants::APPGATE_API_EMVCO_CREATE), EmvcoCreatorResult::class);
	}

	/****
	 * Host-UI Server side API
	 ****/

	/**
	 * generate the token of Host-UI
	 *
	 * @param HostPreInit $pre_init
	 *
	 * @return HostPreInitResult|object
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function hostPreInit(HostPreInit $pre_init) {
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$pre_init->merchant_id = $this->config->uqpay_id;
		}
		$params_array = ModelHelper::assemblyOrderData( $pre_init );
		return $this->directJsonPost($params_array, $this->config->getOperationApiUrl(Constants::APPGATE_API_HOST_INIT), HostPreInitResult::class);
	}

	/**
	 * The payment api of Host-UI
	 * @param HostPayOrder $pay_order
	 *
	 * @return PaymentResult|object
	 * @throws ReflectionException
	 * @throws SecurityUqpayException
	 * @throws UqpayException
	 */
	public function hostPay(HostPayOrder $pay_order) {
		$pay_order->trade_type = Constants::TRADE_TYPE_PAY;
		$pay_order->method_id = 0;
		if ( $this->config->member_type == Constants::MEMBER_TYPE_MER ) {
			$pay_order->merchant_id = $this->config->uqpay_id;
		}
		$params_array = ModelHelper::assemblyOrderData( $pay_order );

		return $this->directFormPost( $params_array, $this->config->getPaymentApiUrl( Constants::PAYGATE_API_HOST_PAY ), PaymentResult::class );
	}

}