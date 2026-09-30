<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory);

$rawEnv = getenv('FIREBASE_CREDENTIALS') ?: ($_ENV['FIREBASE_CREDENTIALS'] ?? $_SERVER['FIREBASE_CREDENTIALS'] ?? null);

if ($rawEnv) {
    $credentials = json_decode($rawEnv, true);
    if (!is_array($credentials)) {
        $credentials = json_decode(base64_decode($rawEnv), true);
    }
    if (is_array($credentials) && isset($credentials['private_key'])) {
        $credentials['private_key'] = str_replace(["\\n", '\n'], "\n", $credentials['private_key']);
    }
    if (is_array($credentials)) {
        $factory = $factory->withServiceAccount($credentials);
    }
} else {
    $factory = $factory->withServiceAccount(__DIR__ . '/src/firebase_credentials.json');
}

$factory = $factory->withDatabaseUri('https://cloud-youra-default-rtdb.asia-southeast1.firebasedatabase.app');

$database = $factory->createDatabase();
$auth = $factory->createAuth();