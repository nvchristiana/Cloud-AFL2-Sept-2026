<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory);

$envCreds = $_ENV['FIREBASE_CREDENTIALS'] ?? $_SERVER['FIREBASE_CREDENTIALS'] ?? getenv('FIREBASE_CREDENTIALS');

if ($envCreds) {
    $credentials = json_decode($envCreds, true);
    if (!$credentials) {
        $credentials = json_decode(base64_decode($envCreds), true);
    }
    if (is_array($credentials) && isset($credentials['private_key'])) {
        $credentials['private_key'] = str_replace('\n', "\n", $credentials['private_key']);
    }
    $factory = $factory->withServiceAccount($credentials);
} else {
    $factory = $factory->withServiceAccount(__DIR__ . '/src/firebase_credentials.json');
}

$factory = $factory->withDatabaseUri('https://cloud-youra-default-rtdb.asia-southeast1.firebasedatabase.app');

$database = $factory->createDatabase();
$auth = $factory->createAuth();