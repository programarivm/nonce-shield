<?php
use NonceShield\Nonce;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

http_response_code(200);
header('Content-Type: application/json');
echo json_encode([
    '_nonce_shield_token' => (new Nonce)->getToken('/get-token.php')]
);
exit;
