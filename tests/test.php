<?php
require_once dirname( __FILE__ ) . '/load.php';

use uqpay\payment\config\ConfigOfAPI;
use uqpay\payment\Constants;
use uqpay\payment\Gateway;
use uqpay\payment\model\BankCard;
use uqpay\payment\model\HttpClientInterface;
use uqpay\payment\model\PaymentOrder;
use uqpay\payment\PayMethodHelper;

$merchant_id = 1005004;
$prv_key = '-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEArQyr++lCstZ4I4px5IDbjf9z/WKaOa/UajNgrsXOUpcIsha5
BgImMJARTthFyva2BXclMMtLWoz0jKxbWXu+m73KyiTsEpZbw4evZ1aAs8y1hVjB
RsYw405xHbSfdZfQQBTUlY6Va6oqEhpNmZCnxzwtwmm+v71CF1Z7LnxNYmM6IrQ9
79VuAUyMDRAcJxBL4vjSfsLmB3QPKGeWC2z7j2JNtqhr0lXlGGRgeXj2XXFZGAly
keowUvEL27sWEEMAb/afe0oNdTEdxALtd3IEJYNus9SdzY3QHVj78p/Y4kXBYsX4
3WIVB3akHYXG37oYG92xWEFs3nzaZZI98iG+twIDAQABAoIBAGQNo5KvN4U3Q5cp
ANjhOBBN1r52OD2KUAJnWksyyywtbzWotamnrHT/l0JDAXdsVamrTbF8mUDtpqd/
MAH47igWAB4IYwYMMVpIJT5WYWuTvJAw1O8awEFspTJLsLbI4/tpD9C48+OgK1r0
IlHbtWYYgUya31L1FjVwJyCldgif6okGS0vgRU+/UzgcsaYOlq0a5+HRLjWnNmTv
NhVJiiTd9fUbfGtVa669bsrWyrPDd4luUiUVESQkybAbS2ffpKOypjfrT+I/8Y4E
C9RcVIinf0Tw7DeD7tMzmM6Ppq2Q8dYheuWzk+0C6BPV1EUo9XMQSGwTP8HJW+VS
OyO82yECgYEA4erLLxMWTM2LzFG2u1Xf+GMjcZAS0IaQhC1vwjuKhf0CmvkB2lhh
83muNnc2k70OBKM9TrQSltfwfl2rCJ64Hi20+EZINzRNU//B9woia5xrjQiqg0gs
O1sMlNt2+K4/Pk5R6eKdeGV+zunYw1U1YNmUOsRkDwBugN8NsiKDz7UCgYEAxBex
JJM3fMKLWy2+OHOTT45OyqVkHm3og9hP7s5K8EW0nbjpY4Qn1j5PDngns7Vzw+zF
ryc32DjxKeASn4PKTlwmgggOPgrzYeZEMWsDRgYpNFwQO8/U7nVDYGaieeiTlQ6l
1IE5PLhMRqhoSmfBzMUgVfvo9g+AtGPAE8iJYDsCgYEAipODXrTOkP3kKshU1kSu
xaXKL/a4E8D3FJzqWLI9HkM8PeNQB6b/LmINQsuNZsIovx+Ck6xRWsXKdzjtmLQD
LD/NKh2yXmpupH/Vcrt8sZWZQ0F1lmHHAAGxjf2w1InNsWJJTLX88cUQK8u1ctvp
iibsjb+5wJn7LoGj3Qje4aECgYBE2pzU3uyI3jbYmUNFxy9eq/V2qoRxOt5+DSJk
FAO0QoWdLCSnUOw8CjzwM7idHYW8shLn4bl2Luhfb9KaOEh9I1ZSKkn19xpmsdgY
Eh9gIyGsxPbeSafW4035N5CthcDsgewwpf9XFs+Rr+iO18fxAvbLulyeqerjbHMx
fyTdqQKBgEhzb8UHNBzLFvMetobOi0dD4L9j1/b+fhCciaDbUgzWRRJBjw2a43C0
Kv3P5otNiow7lEl2JA9ITgWee8RvWypTnsw3YZIv/WLXwt/FPVkaLtYEF7sEBx+Q
8bhgiQJjcJ4f0gPPj3DKpF8hcr3cseaB+pfef6CDAianDeLng6qp
-----END RSA PRIVATE KEY-----';
$pub_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhB/ls6tXiaYxejXRttBX
OD0VujO96AdFAAWKf3XvBZpLNuYDFwesRzoYAfwntiZlkbULdczT8jKuGSjL65yV
ZobRtpwhpBdKipTrWKAS98ainOepIbCRtZr/Nm4aF55cChXcHqZCcQU5dSNNg5Jp
qbXwir+CbOsTZreEVLjPeM82ycvHYGoIHKhMR/9HXLxcG76EypliTUTQkE1BoPyV
QOZeDEpmdpXsBoSgH5SL/gIjUGvRV6A3yRU6bRNtIL+XiFF+TIz65sQSyKX9tXUw
FckpgXyAmCaqZ52mfkdVUzvWL96Rxw1H/I39F5seqM6ACBXd33Sp1NZEykdgx4nd
cQIDAQAB
-----END PUBLIC KEY-----';

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
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
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