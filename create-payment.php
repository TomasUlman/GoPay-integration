<?php

require_once __DIR__ . '/init-gopay.php';

use GoPay\Definition\Payment\Currency;

$response = $gopay->createPayment([
    'payer' => [
        'contact' => [
            'email' => 'test@gopay.cz',
        ],
    ],
    'amount' => 100,
    'currency' => Currency::CZECH_CROWNS,
    'order_number' => '001',
    'callback' => [
        'return_url' => 'http://localhost:8000/index.php'
    ],
]);

if ($response->hasSucceed())
    echo json_encode($response->json);
