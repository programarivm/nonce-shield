<?php
use NonceShield\Nonce;

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(__DIR__ . '/../../../');
$dotenv->load();

session_start();

(new Nonce)->validateToken();
echo 'This request was successfully protected against CSRF attacks.';
