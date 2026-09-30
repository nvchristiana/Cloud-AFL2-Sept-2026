<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory);

$b64 = getenv('FIREBASE_CREDENTIALS_B64') ?: ($_ENV['FIREBASE_CREDENTIALS_B64'] ?? null);

if ($b64) {
    $credentials = json_decode(base64_decode($b64), true);
    if (!is_array($credentials)) {
        die("Invalid configuration.");
    }
    $factory = $factory->withServiceAccount($credentials);
} else {
    $factory = $factory->withServiceAccount(__DIR__ . '/src/firebase_credentials.json');
}

$factory = $factory->withDatabaseUri('https://cloud-youra-default-rtdb.asia-southeast1.firebasedatabase.app');

$database = $factory->createDatabase();
$auth = $factory->createAuth();