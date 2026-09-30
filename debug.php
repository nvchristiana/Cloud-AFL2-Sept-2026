<?php
$b64 = getenv('FIREBASE_CREDENTIALS_B64') ?: ($_ENV['FIREBASE_CREDENTIALS_B64'] ?? $_SERVER['FIREBASE_CREDENTIALS_B64'] ?? null);
$old = getenv('FIREBASE_CREDENTIALS') ?: ($_ENV['FIREBASE_CREDENTIALS'] ?? $_SERVER['FIREBASE_CREDENTIALS'] ?? null);
$localFile = __DIR__ . '/src/firebase_credentials.json';

echo "B64: " . ($b64 ? "OK" : "NO") . "<br>";
echo "OLD: " . ($old ? "OK" : "NO") . "<br>";
echo "FILE: " . (file_exists($localFile) ? "OK" : "NO") . "<br>";

$data = null;
$source = "NONE";

if ($b64) {
    $data = json_decode(base64_decode($b64), true);
    $source = "B64";
} elseif ($old) {
    $data = json_decode($old, true);
    $source = "OLD";
} elseif (file_exists($localFile)) {
    $data = json_decode(file_get_contents($localFile), true);
    $source = "FILE";
}

echo "Source: " . $source . "<br>";
echo "Project ID: " . ($data['project_id'] ?? '-') . "<br>";
echo "Client Email: " . ($data['client_email'] ?? '-') . "<br>";

$key = $data['private_key'] ?? '';
echo "Has Real Newline: " . (strpos($key, "\n") !== false ? "YES" : "NO") . "<br>";
echo "Has Escaped Newline: " . (strpos($key, '\n') !== false ? "YES" : "NO") . "<br>";