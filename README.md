# UQPAY Payment PHP library

This PHP library provides integration access to the UQPAY Payment Gateway.
## Dependencies

PHP version >= 5.6 is required.

The following PHP extensions are required:

* hash
* openssl
* json

## Quick Start Example

### Installation
```shell script
composer require uqpay/uqpay_php
```

### Sample Usage

```php
<?php

use uqpay\payment\config\ConfigOfAPI;
use uqpay\payment\Constants;
use uqpay\payment\Gateway;
use uqpay\payment\model\BankCard;
use uqpay\payment\model\HttpClientInterface;
use uqpay\payment\model\PaymentOrder;
use uqpay\payment\PayMethodHelper;

$merchant_id = 1005004;
$prv_key = 'Your_Private_Key_Content';
$pub_key = 'The_UQPAY_Public_Key_Your_Downloaded_Content';

// under test mode all request will call the test sandbox of UQPAY, and for default the test mode is on
$test_mode = true;

$uqpay_config = ConfigOfAPI::builder(
	$prv_key,
	Constants::SIGN_TYPE_RSA,
	$pub_key,
	$merchant_id,
	$test_mode
);

$uqpay_gateway = new Gateway($uqpay_config);

/**
 * Implementing HttpClientInterface
 * here we just use curl
 **/
class HttpClient implements HttpClientInterface {
	public function post( array $headers, $body, $url ) {
        $curl_headers = array();
		$curl_headers[] = 'Content-type: '.$headers['content-type'];
		$curl_headers[] = 'UQPAY-Version: '.$headers['UQPAY-Version'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $curl_headers);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}
$uqpay_gateway->setHttpClient(new HttpClient());

/**
 * credit card payment
 **/

$payment_order = new PaymentOrder();
$payment_order->method_id = PayMethodHelper::UNION_PAY_EXPRESS_PAY;
$payment_order->order_id = time();
$payment_order->trans_name = 'product name';
$payment_order->amount = 1;
$payment_order->currency = 'HKD';
$payment_order->date = time();
$payment_order->client_ip = '127.0.0.1';
$payment_order->callback_url = 'https://localhost:8080/async';

$bank_card = new BankCard();
$bank_card->first_name = 'test';
$bank_card->last_name = 'test';
$bank_card->card_num='6250947000000014';
$bank_card->cvv='123';
$bank_card->expire_year=33;
$bank_card->expire_month=12;

$result = $uqpay_gateway->pay($payment_order, $bank_card);
var_dump($result);

/**
 * host-ui server side
 */

/**
 * host pre-init
 */

$host_pre = new HostPreInit();
$host_pre->customer = "1005004";
$host_pre->date = time();

$result = $uqpay_gateway->hostPreInit($host_pre);
var_dump($result);

/**
 * host pay
 */

$host_pay = new HostPayOrder();

$host_pay->card_token = '92796b16ced54e448e3c04e031a72cf4';

$host_pay->order_id = time();
$host_pay->trans_name = 'product name';
$host_pay->amount = 1;
$host_pay->currency = 'HKD';
$host_pay->date = time();
$host_pay->client_ip = '127.0.0.1';
$host_pay->callback_url = 'https://localhost:8080/async';

$result = $uqpay_gateway->hostPay($host_pay);
var_dump($result);

/**
 * emvco create
 */
$partner_id = 1005393;
$merchant_of_this_partner = 1005412;
$partner_prv_key = 'Your_partner_prv_key_Content';
$partner_uqpay_pub_key = 'The_UQPAY_Public_Key_Your_Downloaded_Content';
$uqpay_partner_config = ConfigOfAPI::builder(
	$partner_prv_key,
	Constants::SIGN_TYPE_RSA,
	$partner_uqpay_pub_key,
	$partner_id,
	$test_mode,
	false // set false means your are a partner
);

$uqpay_gateway = new Gateway($uqpay_partner_config);
$uqpay_gateway->setHttpClient(new HttpClient());
$emvco = new EmvcoCreator();
$emvco->type = Constants::QR_CHANNEL_TYPE_UNION;
$emvco->name = 'PHP TEST';
$emvco->code_type = Constants::QR_TYPE_STATIC;
$emvco->terminal_id = '10001A';
$emvco->city = 'Singapore';
$emvco->date = time();
$emvco->merchant_id = $merchant_of_this_partner;

$qr_result = $uqpay_gateway->createQRCode($emvco);
var_dump($qr_result);

```
## Documentation

 * [Official documentation](https://developer.uqpay.com/api/#/)
 
 
## License

See the LICENSE file.