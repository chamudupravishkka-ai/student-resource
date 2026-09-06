<?php

$secret = "my-secret-key-123";

$token = $_GET['token'] ?? '';

$data = base64_decode($token);

if (!$data) {
    exit("Invalid link");
}

$parts = explode("::", $data, 2);

$iv = $parts[0];
$encrypted = $parts[1];

$realpath = openssl_decrypt(
    $encrypted,
    "AES-256-CBC",
    $secret,
    0,
    $iv
);

if (!$realpath || !is_file($realpath)) {
    exit("File not found");
}

header("Content-Type: application/pdf");
header('Content-Disposition: attachment; filename="' . basename($realpath) . '"');

readfile($realpath);
exit;