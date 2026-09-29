<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/src/firebase_credentials.json')
    ->withDatabaseUri('https://AFL2-Cloud-Christiana-default-rtdb.asia-southeast1.firebasedatabase.app');

$database = $factory->createDatabase();
$auth = $factory->createAuth();