<?php
return [
    'call_api'=>[
        'api_match_url'=>env('API_MATCH_URL','https://api.football-data.org/v4/matches'),
        'api_competition_url'=>env('API_COMPETITION_URL','http://api.football-data.org/v4/competitions'),
        'api_key'=>env('API_KEY','a6c8b22c5f6942f48f5a4ca296bd42e8')
    ],
    'vnpay' => [
        'tmn_code' => env('VNP_TMNCODE', 'QOTUG92O'),
        'hash_secret' => env('VNP_HASHSECRET', 'NPMGKCWINKENWSTLVIQEHXGXNUCTIZOY'),
        'url' => env('VNP_URL',"https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"),
        'return_url' => env('VNP_RETURN_URL', "http://localhost/vnpay_php/vnpay_return.php"),
        'vnpay_api_url' => env('VNP_API_URL', "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html"),
        'api_url' => env('API_URL', "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction"),
    ],
    
];