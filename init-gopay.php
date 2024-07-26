<?php

require_once __DIR__ . '/autoload.php';

use GoPay\Definition\Language;

$gopay = GoPay\payments([
    'goid' => '8138705157',
    'clientId' => '1564918465',
    'clientSecret' => 'wJPdBzYB',
    'gatewayUrl' => 'https://gw.sandbox.gopay.com/api',
    'language' => Language::CZECH
]);