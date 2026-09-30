<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory);

if (getenv('FIREBASE_CREDENTIALS')) {
    $credentials = json_decode(getenv('FIREBASE_CREDENTIALS'), true);
    $factory = $factory->withServiceAccount($credentials);
} else {
    $factory = $factory->withServiceAccount(__DIR__ . '/src/firebase_credentials.json');
}

$factory = $factory->withDatabaseUri('https://cloud-youra-default-rtdb.asia-southeast1.firebasedatabase.app');

$database = $factory->createDatabase();
$auth = $factory->createAuth();