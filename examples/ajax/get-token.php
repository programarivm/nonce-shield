<?php
use CsrfShield\Nonce;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

(new Nonce)->startToken();

http_response_code(200);
header('Content-Type: application/json');
echo json_encode([
    '_csrf_shield_token' => (new Nonce)->getToken()]
);
exit;
