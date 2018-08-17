<?php
namespace uqpay\payment\sdk\utils;

use GuzzleHttp\Client;
class httpRequest
{
    function httpArrayPost($url, $data)
    {
        $client = new Client();
        $promise = $client->post($url, ["form_params" => $data]);
        return (string)$promise->getBody();
    }
}