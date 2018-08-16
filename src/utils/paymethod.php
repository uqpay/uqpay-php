<?php

$scenesEnum = array(
    "QRCode"=>"QRCode",
    "OnlinePay"=>"OnlinePay",
    "CreditCard"=>"CreditCard",
    "ThreeDCreditCard"=>"ThreeDCreditCard",
    "InApp"=>"InApp"
);

$payMethod = array(
    1001=>$scenesEnum["QRCode"],
    1002=>$scenesEnum["QRCode"],
    1003=>$scenesEnum["QRCode"],
    1102=>$scenesEnum["OnlinePay"],
    1100=>$scenesEnum["OnlinePay"],
    1200=>$scenesEnum["CreditCard"],
    1201=>$scenesEnum["CreditCard"],
    1202=>$scenesEnum["CreditCard"],
    1203=>$scenesEnum["CreditCard"],
    1204=>$scenesEnum["CreditCard"],
    1250=>$scenesEnum["ThreeDCreditCard"],
    1251=>$scenesEnum["ThreeDCreditCard"],
    1300=>$scenesEnum["CreditCard"],
    1301=>$scenesEnum["OnlinePay"],
    1501=>$scenesEnum["OnlinePay"],
    2000=>$scenesEnum["InApp"],
    2001=>$scenesEnum["InApp"],
    3000=>$scenesEnum["OnlinePay"]
);

$UqpayScanType = array(
    "Merchant"=>0,//merchant
    "Consumer"=>1,//Consumer
);