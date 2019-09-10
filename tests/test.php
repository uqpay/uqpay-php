<?php
require_once dirname( __FILE__ ) . '/load.php';

use uqpay\payment\config\ConfigOfAPI;
use uqpay\payment\Constants;
use uqpay\payment\Gateway;
use uqpay\payment\model\BankCard;
use uqpay\payment\model\EmvcoCreator;
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
		$curl_headers = array();
		$curl_headers[] = 'Content-type: '.$headers['content-type'];
		$curl_headers[] = 'UQPAY-Version: '.$headers['UQPAY-Version'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
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
 * emvco create
 */
$partner_id = 1005393;
$merchant_of_this_partner = 1005412;
$partner_prv_key = '-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA7nrkEPqeWp/WhLl/sTQNdtQ0kMGzY5SS8wytR00FIDOj/9kv
C2+mCfW/pY5CsIwQKR/bw6Nbe5fCsZbx2j84ioCjizIxX9dCh9ysC8ADcU18gPh7
KNTncfRfte+HR4v1AA6nA2JikGqhw44dvJS92dUh3Eutt2lnk+7fHjnztvJVyC7O
Vo7VQSrb7CjgVfvWwh+XFKu7BDFXXifYj6U+wjoxFZgURXpMry8I9Hr5tVy33zho
BTyT6SjTK9VOaB5NT1hZmBjMJir/Qet9PtS4BqfFf0r6jDnps7Iem1MGCDfUhYC1
GczgXcD71K2zoMqF8Uhwfppj16jlqij2jNWTKwIDAQABAoIBAFc938cSV/HhPVHq
pnsGBtLsyJoYMm8AgE2n2pAV7gUcvycupZYybvR/0W9YPq9lXdgdjoDgduwc1Z2w
EaP8ssuASdP3NbbRAcbABLR7twaxCRYJUMzcLhszAfyFtuCGo8c0lQaY7GPWjn0C
tYAyjc1tuehkSxWo2rp0jWz6WF0ZT1fszULhP3EkT3IjC1DRpZoSf0X6ENvvbMt4
fUEQwUa16uu/M2WTYZOmfdAP5K09ejNAA/w9yEfvEi7gmV90Vbuz902TAQsskU8E
I3Ra14/ilSXWU/5YkShMckuyF1wiDQHRIPvMNflCjSJ20vQp8QjIZO4X5Wpr6JGB
8edrHjkCgYEA+zLGa3+x3aS3EOCsSxCmcdmPjkwbA54L5aqglycRsJKIkj2DOiv4
OSIVpls2tV78dsmGVCPdHWzjODrnqGxfznvRaSNWNBVfnzRcI8JnHX3+y2lhLzhb
NSHowEdhg6rKKw+1CRMLD5LAE/SnjU2XyJ/EpSWLvSTgyn5JwUiWbw0CgYEA8wnh
KRufaAXSyYNfI7xApIpXPEcCEBlx1f+2GhZC3tHrXrILvgT6IsRFRBNezdV42eXC
vkCfCyJMDanx15IJgT/uZXUhbXLaTC+gxUtfB4OmkDi1BAe4e/JUJutZrmBcQXL/
HqR/fNQYbLpdKJa8gJNOiV1XQ++kQA8GlVdpvRcCgYEAjMOeRx0umeq0n2OXiRUS
gJgPBwmE1dkaB6A/D5TYJ99lYrXPtKhxF+sOwMM6fBZ3WUWC3eGfBd8/0QHJUSsx
4O6nocgohVU42Wko/OzyhadWQbyStjhZfAO9fwpBDdyGH+1UYHpoZ1iwBD7EKb3C
ga1uL7FDhkGFKlPslsBLdH0CgYEA6cVw/JeDRw2C6S4iDz9+dkZTDrnGdDHlW1Ax
mvoarDUCzv03ajljWJmtfoObRyW0rvLf1RxXXuBIg0QaSZ5A4j/aUWDPHHXDIFEX
tW6AI7wwNL028H90plQ7OYxboO0zEAlK9/CGaE2iiMLh5K7I9mu6uUo9LC2PscZC
MNf571UCgYBzM1h3iLe6yE/LIMWU5vLSGuWysZqjpdPHVb44v+wo/Jm0MZg8ngDY
1CwUy0mpqheoABqE7QgVdUtDjOuusYcvWQtHImGxFGhiP8cmJ/g6rHokhaRoGtib
878/2K2dliXW9npnMIRaKePoyLz5QXHm/2uoljORJrCFhcv0g/4zfQ==
-----END RSA PRIVATE KEY-----';
$partner_uqpay_pub_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkAsYPNtb3YPv+sXNBN0s
HX7FMn0/q85hD2+VMPFeeCGW1aLYuvAj8IUT0SjcVMiJl0GkVphJuQMaPzFqK6ns
yy//duxfLCnhM7/I3loKlERGLN+mYhv4TS5KU3yMtyQra21YZ+/GKBqD1xQvvONA
3Y1vxw0TgFiYVgGaGh8ZLKslcoylACCk31IdiAwNW25ha+Dc1jbDcVfQFdHcMGI1
ovseA7Wskrn3DxH15ktWmf3xkZnxMDITeQERcMJ6+nVU4xmq2jscmdruuZNM0Xma
C46cRvBLrMbIlsL1rkQlEuKhWKj+kcJvKWfZpEjeQpblmn6QmF5bHP6Qx3pz2h9/
cQIDAQAB
-----END PUBLIC KEY-----';
$uqpay_partner_config = ConfigOfAPI::builder(
	$partner_prv_key,
	Constants::SIGN_TYPE_RSA,
	$partner_uqpay_pub_key,
	$partner_id,
	$test_mode,
	false
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