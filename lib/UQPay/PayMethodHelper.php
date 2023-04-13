<?php


namespace uqpay\payment;

class PayMethodHelper {

	/**
	 * payment method id
	 * @see https://developer.uqpay.com/api/#/appendix?id=_2-method-id
	 */

	const UNION_PAY_ONLINE_QR = 1001;
	const ALIPAY_OFFLINE_QR = 1002;
	const WECHAT_OFFLINE_QR = 1003;
	const UNION_PAY_OFFLINE_QR = 1004;
    const PAYNOW_OFFLINE_QR= 1012;
	const ALIPAY_ONLINE_QR = 1010;
	const WECHAT_ONLINE_QR = 1011;
    Const PAYNOW_ONLINE_QR = 1013;
    const GRAB_OFFLINE_QR = 1005;
    const GRAB_ONLINE_QR = 1006;
	const WECHAT_WEB_BASED_IN_APP = 1102;
	const UNION_SECURE_PAY = 1100;
	const UNION_PAY_MERCHANT_HOST = 1101;
	const UNION_PAY_SERVER_HOST = 1103;
	const UNION_PAY_EXPRESS_PAY = 1202;
    const WECHAT_H5 = 1502;
	const VISA = 1200;
	const VISA_3D = 2500;
	const MASTER = 1201;
	const MASTER_3D = 2501;
	const AMEX = 1203;
	const JCB = 1204;
	const PAYPAL = 1300;
	const ALIPAY_ONLINE = 1301;
	const ALIPAY_WAP = 1501;
	const WECHAT_IN_APP = 2000;
	const UNION_PAY_IN_APP = 2001;
	const ALIPAY_IN_APP = 2002;
	const APPLE_PAY = 3000;
	const GCash_ONLINE = 1107;
    const TrueMoney_ONLINE = 1110;
    const KAKAOPAY_ONLINE = 1108;
    const TNG_ONLINE = 1109;
    const USDT_ONLINE = 5006;
    const USDT_OFFLINE = 5007;
    const USDC_ONLINE = 5008;
    const USDC_OFFLINE = 5009;
    const TrueMoney_OFFLINE_QR = 1028;
    const UNION_B2B = 10001;


	/**
	 * Scene used by payment method
	 * Different scenarios use different payment interactions
	 */

	const SCENES_QR = 'QR_code';
	const SCENES_OFFLINE_QR = 'offline_QR';
	const SCENES_CREDIT_CARD = 'credit_card';
	const SCENES_3D_CREDIT_CARD = '3D_credit_card';
	const SCENES_REDIRECT_PAY = 'redirect_pay';
	const SCENES_MERCHANT_HOST = 'merchant_host';
	const SCENES_SERVER_HOST = 'server_host';
	const SCENES_IN_APP = 'in_app';

	const SCENE_OF_METHOD = array(
		self::UNION_PAY_ONLINE_QR => self::SCENES_QR,
		self::ALIPAY_ONLINE_QR => self::SCENES_QR,
		self::WECHAT_ONLINE_QR => self::SCENES_QR,
		self::UNION_PAY_OFFLINE_QR => self::SCENES_OFFLINE_QR,
		self::WECHAT_OFFLINE_QR => self::SCENES_OFFLINE_QR,
		self::ALIPAY_OFFLINE_QR => self::SCENES_OFFLINE_QR,
		self::WECHAT_WEB_BASED_IN_APP => self::SCENES_REDIRECT_PAY,
		self::UNION_SECURE_PAY => self::SCENES_REDIRECT_PAY,
		self::UNION_PAY_MERCHANT_HOST => self::SCENES_MERCHANT_HOST,
		self::UNION_PAY_SERVER_HOST => self::SCENES_SERVER_HOST,
		self::UNION_PAY_EXPRESS_PAY => self::SCENES_CREDIT_CARD,
		self::VISA => self::SCENES_CREDIT_CARD,
		self::VISA_3D => self::SCENES_3D_CREDIT_CARD,
		self::MASTER => self::SCENES_CREDIT_CARD,
		self::MASTER_3D => self::SCENES_3D_CREDIT_CARD,
		self::AMEX => self::SCENES_3D_CREDIT_CARD,
		self::JCB => self::SCENES_CREDIT_CARD,
		self::PAYPAL => self::SCENES_REDIRECT_PAY,
		self::ALIPAY_ONLINE => self::SCENES_REDIRECT_PAY,
		self::ALIPAY_WAP => self::SCENES_REDIRECT_PAY,
		self::WECHAT_IN_APP => self::SCENES_IN_APP,
		self::UNION_PAY_IN_APP => self::SCENES_IN_APP,
		self::ALIPAY_IN_APP => self::SCENES_IN_APP,
		self::APPLE_PAY => self::SCENES_REDIRECT_PAY,
        self::PAYNOW_OFFLINE_QR => self::SCENES_OFFLINE_QR,
        self::GRAB_OFFLINE_QR => self::SCENES_OFFLINE_QR,
        self::GRAB_ONLINE_QR => self::SCENES_QR,
        self::PAYNOW_ONLINE_QR => self::SCENES_QR,
        self::WECHAT_H5  => self::SCENES_REDIRECT_PAY,
        self::GCash_ONLINE => self::SCENES_QR,
        self::TrueMoney_ONLINE => self::SCENES_QR,
        self::KAKAOPAY_ONLINE => self::SCENES_QR,
        self::TNG_ONLINE => self::SCENES_QR,
        self::USDT_ONLINE => self::SCENES_QR,
        self::USDC_OFFLINE => self::SCENES_OFFLINE_QR,
        self::USDT_OFFLINE => self::SCENES_OFFLINE_QR,
        self::USDC_ONLINE => self::SCENES_QR,
        self::TrueMoney_OFFLINE_QR => self::SCENES_OFFLINE_QR,
        self::UNION_B2B => self::SCENES_REDIRECT_PAY,
	);
}