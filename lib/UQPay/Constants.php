<?php

namespace uqpay\payment;

class Constants {
	const SIGN_TYPE_RSA = 'RSA';
	const SIGN_TYPE_MD5 = 'MD5';

	const ENV_MODE_TEST = 'test';
	const ENV_MODE_PROD = 'prod';

	const MEMBER_TYPE_MER = 'merchant';
	const MEMBER_TYPE_AGENT = 'agent';

	const PAYMENT_API_ROOT = array(
		self::ENV_MODE_TEST => 'https://paygate.uqpay.net',
		self::ENV_MODE_PROD => 'https://paygate.uqpay.com'
	);
	const OPERATION_API_ROOT = array(
		self::ENV_MODE_TEST => 'https://appgate.uqpay.net',
		self::ENV_MODE_PROD => 'https://appgate.uqpay.com'
	);
	const CASHIER_API_ROOT = array(
		self::ENV_MODE_TEST => 'https://cashier.uqpay.net',
		self::ENV_MODE_PROD => 'https://cashier.uqpay.com'
	);

	const AUTH_SIGN = 'sign';
	const AUTH_SIGN_TYPE = 'signtype';
	const AUTH_MERCHANT_ID = 'merchantid';
	const AUTH_AGENT_ID = 'agentid';
	const AUTH_SIGN_JSON = 'signature';
	const AUTH_SIGN_TYPE_JSON = 'signType';
	const AUTH_MERCHANT_ID_JSON = 'merchantId';
	const AUTH_AGENT_ID_JSON = 'agentId';

	const PAY_OPTIONS_TRADE_TYPE = 'transtype';
	const PAY_OPTIONS_METHOD_ID = 'methodid';
	const PAY_OPTIONS_SCAN_TYPE = 'scantype';
	const PAY_OPTIONS_ASYNC_NOTICE_URL = 'callbackurl';
	const PAY_OPTIONS_SYNC_NOTICE_URL = 'returnurl';
	const PAY_OPTIONS_CLIENT_TYPE = 'clienttype';
	const PAY_OPTIONS_IDENTITY = 'identity';
	const PAY_OPTIONS_MERCHANT_CITY = 'merchantcity';
	const PAY_OPTIONS_TERMINAL_ID = 'terminalid';

	const PAYMENT_SUPPORTED_CLIENT_PC = 1;
	const PAYMENT_SUPPORTED_CLIENT_IOS = 2;
	const PAYMENT_SUPPORTED_CLIENT_ANDROID = 3;

	const TRADE_TYPE_PAY = 'pay';
	const TRADE_TYPE_CANCEL = 'cancel';
	const TRADE_TYPE_REFUND = 'refund';
	const TRADE_TYPE_PRE_AUTH = 'preauth';
	const TRADE_TYPE_PRE_AUTH_COMPLETE = 'preauthcomplete';
	const TRADE_TYPE_PRE_AUTH_CANCEL = 'preauthcancel';
	const TRADE_TYPE_PRE_AUTH_CANCEL_COMPLETE = 'preauthcancel';
	const TRADE_TYPE_VERIFY_CODE = 'verifycode';
	const TRADE_TYPE_ENROLL = 'enroll';
	const TRADE_TYPE_WITHDRAW = 'withdraw';
	const TRADE_TYPE_QUERY = 'query';

	const QR_CODE_SCAN_BY_MERCHANT = 0;
	const QR_CODE_SCAN_BY_CONSUMER = 1;

	const PAY_ORDER_ID = 'orderid';
	const PAY_ORDER_AMOUNT = 'amount';
	const PAY_ORDER_CURRENCY = 'currency';
	const PAY_ORDER_TRANS_NAME = 'transname';
	const PAY_ORDER_DATE = 'date';
	const PAY_ORDER_FINISH_TIME = 'finishTime';
	const PAY_ORDER_QUANTITY = 'quantity';
	const PAY_ORDER_STORE_ID = 'storeid';
	const PAY_ORDER_SELLER = 'seller';
	const PAY_ORDER_CHANNEL_INFO = 'channelinfo';
	const PAY_ORDER_EXTEND_INFO = 'extendinfo';
	const PAY_ORDER_CLIENT_IP = 'clientip';

	const PAYGATE_API_PAY = '/pay';
	const PAYGATE_API_HOST_PAY = '/hosted/pay';
	const PAYGATE_API_REFUND = '/refund';
	const PAYGATE_API_CANCEL = '/cancel';
	const PAYGATE_API_QUERY = '/query';
	const PAYGATE_API_PRE_AUTH = '/preauth';
	const PAYGATE_API_ENROLL = '/enroll';
	const PAYGATE_API_VERIFY = '/verify';

	const APPGATE_API_MERCHANT_REGISTER = '/merchant/register';
	const APPGATE_API_MERCHANT_VIEW = '/merchant/view';
	const APPGATE_API_MERCHANT_LIST = '/merchant/list';
	const APPGATE_API_MERCHANT_CHECKING = '/file/checking';
	const APPGATE_API_PRODUCT_CONFIG = '/product/config';
	const APPGATE_API_PRODUCT_LIST = '/product/list';
	const APPGATE_API_EMVCO_CREATE = '/emvco/create';
	const APPGATE_API_EMVCO_QUERY = '/emvco/query';
	const APPGATE_API_EMVCO_PAYLOAD = '/emvco/payload';
	const APPGATE_API_RES_EXCHANGE_RATE = '/res/exchange';
	const APPGATE_API_FINANCE_APPLY_WITHDRAW = '/finance/withdraw';
	const APPGATE_API_FINANCE_BALANCE = '/finance/balance';
	const APPGATE_API_HOST_INIT = '/hosted/init';

	const ORDER_STATE_READY = 'Ready';
	const ORDER_STATE_PAYING = 'Paying';
	const ORDER_STATE_SUCCESS = 'Success';
	const ORDER_STATE_MULTI_PAY = 'MultiPay';
	const ORDER_STATE_SYNC_SUCCESS = 'SyncSuccess';
	const ORDER_STATE_REFUNDING = 'Refunding';
	const ORDER_STATE_EXCEPTION = 'Exception';
	const ORDER_STATE_FAILED = 'Failed';
	const ORDER_STATE_SYNC_FAILED = 'SyncFailed';

	const RESULT_MESSAGE = 'message';
	const RESULT_CODE = 'code';
	const RESULT_UQPAY_ORDER_ID = 'uqorderid';
	const RESULT_UQPAY_RELATED_ID = 'relatedid';
	const RESULT_STATE = 'state';
	const RESULT_ACCEPT_CODE = 'acceptcode';

	const RESULT_QR_CODE_URL = 'qrcodeurl';
	const RESULT_QR_CODE_DATA = 'qrcode';

	const RESULT_CHANNEL_CODE = 'channelcode';
	const RESULT_CHANNEL_MESSAGE = 'channelmsg';

	const RESULT_3D_PA_REQUEST = 'parequst';
	const RESULT_3D_ASC_URL = 'ascurl';

	const BANK_CARD_FIRST_NAME = 'firstname';
	const BANK_CARD_LAST_NAME = 'lastname';
	const BANK_CARD_CARD_NUM = 'cardnum';
	const BANK_CARD_CVV = 'cvv';
	const BANK_CARD_EXPIRE_MONTH = 'expiremonth';
	const BANK_CARD_EXPIRE_YEAR = 'expireyear';
	const BANK_CARD_ADDRESS_COUNTRY = 'addresscountry';
	const BANK_CARD_PHONE = 'phone';
	const BANK_CARD_EMAIL = 'email';
	const BANK_CARD_THREE_D_UQORDERID = 'uqorderid';
	const BANK_CARD_THREE_D_PARES = 'paresponse';

	const MERCHANT_HOST_ENROLL_CODE_UQPAY_ID = 'codeorderid';
	const MERCHANT_HOST_VERIFY_CODE = 'verifycode';
	const SERVER_HOST_CARD_TOKEN = 'token';

	const MERCHANT_REGISTER_NAME = 'name';
	const MERCHANT_REGISTER_ABBR = 'abbr';
	const MERCHANT_REGISTER_LOGIN_EMAIL = 'regEmail';
	const MERCHANT_REGISTER_COMPANY_NAME = 'companyName';
	const MERCHANT_REGISTER_COMPANY_ID = 'regNo';
	const MERCHANT_REGISTER_COMPANY_ADDRESS = 'regAddress';
	const MERCHANT_REGISTER_COMPANY_COUNTRY = 'country';
	const MERCHANT_REGISTER_MCC = 'mcc';
	const MERCHANT_REGISTER_WEBSITE = 'website';
	const MERCHANT_REGISTER_CONTACT = 'contact';
	const MERCHANT_REGISTER_MOBILE = 'mobile';
	const MERCHANT_REGISTER_BUSINESS_EMAIL = 'email';

	const MANAGER_BASE_RESULT_CODE = 'respCode';
	const MANAGER_BASE_RESULT_MESSAGE = 'respMessage';

	const QR_CHANNEL_TYPE_UNION = 'UnionPay';

	const QR_TYPE_STATIC = 'Static';
	const QR_TYPE_DYNAMIC = 'Dynamic';

	const EMVCO_CREATOR_TYPE = 'type';
	const EMVCO_CREATOR_NAME = 'name';
	const EMVCO_CREATOR_CODE_TYPE = 'codeType';
	const EMVCO_CREATOR_TERMINAL_ID = 'terminalId';
	const EMVCO_CREATOR_SHOP_NAME = 'shopName';
	const EMVCO_CREATOR_AMOUNT = 'amount';
	const EMVCO_CREATOR_CITY = 'city';
	const EMVCO_CREATOR_ORDER_ID = 'orderId';
	const EMVCO_CREATOR_RESULT_CODE_ID = 'codeId';
	const EMVCO_CREATOR_RESULT_PAYLOAD = 'payload';
	const EMVCO_CREATOR_RESULT_CONTENT = 'content';

	const HOST_INIT_CUSTOMER_KEY = 'customer';
	const HOST_INIT_RESULT_TOKEN = 'token';
	const HOST_PAY_CARD_TOKEN = "cardtoken";
}