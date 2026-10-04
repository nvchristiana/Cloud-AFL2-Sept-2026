<?php
require_once __DIR__ . '/firebase_config.php';

$email = strtolower(trim($_GET['email'] ?? ''));
$message = '';

if ($email !== '') {
    try {
        $user = $auth->getUserByEmail($email);

        if ($user->emailVerified) {
            $database->getReference('users/' . md5($email))->set([
                'email' => $email,
                'is_verified' => true,
            ]);
            header('Location: login.php?verified=1');
            exit;
        } else {
            $message = "Your email is not verified yet. Please check your inbox.";
        }
    } catch (\Throwable $e) {
        $message = "Account not found.";
    }
} else {
    $message = "Invalid verification link.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Email - POPMART VIP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 text-center">
  <div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
  <a href="login.php">Back to login</a>
</body>
</html>