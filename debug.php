<?php
$b64 = getenv('FIREBASE_CREDENTIALS_B64') ?: ($_ENV['FIREBASE_CREDENTIALS_B64'] ?? $_SERVER['FIREBASE_CREDENTIALS_B64'] ?? null);

if ($b64) {
    $data = json_decode(base64_decode($b64), true);
    echo "Private Key ID Baru: " . ($data['private_key_id'] ?? '-') . "<br>";
    echo "Project ID: " . ($data['project_id'] ?? '-') . "<br>";
} else {
    echo "Variable B64 tidak ditemukan.";
}