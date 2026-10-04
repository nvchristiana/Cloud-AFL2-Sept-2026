<?php
session_start();
require_once __DIR__ . '/firebase_config.php';

$error = '';
$success = '';

if (isset($_GET['verified'])) {
    $success = "Email verified successfully! Please login.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $user = null;

        try {
            $user = $auth->getUserByEmail($email);
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            $error = "Account not found.";
        } catch (\Throwable $e) {
            $error = "Something went wrong. Please try again.";
        }

        if ($user) {
            $signInResult = null;

            try {
                $signInResult = $auth->signInWithEmailAndPassword($email, $password);
            } catch (\Throwable $e) {
                $error = "Incorrect password.";
            }

            if ($signInResult) {
                $userRef = $database->getReference('users/' . md5($email));
                $isVerified = ($userRef->getChild('is_verified')->getValue() === true);

                if (!$isVerified && $user->emailVerified) {
                    $userRef->set(['email' => $email, 'is_verified' => true]);
                    $isVerified = true;
                }

                if ($isVerified) {
                    $_SESSION['user_id'] = $signInResult->firebaseUserId();
                    header('Location: index.php');
                    exit;
                } else {
                    $error = "Your email is not verified yet. Please check your inbox.";
                }
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - POPMART VIP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --sage-dark: #1b2e24;
      --sage-primary: #2d4a3e;
    }
    body {
      background: linear-gradient(135deg, #111e17 0%, #2d4a3e 50%, #1b2e24 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .auth-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
      width: 100%;
      max-width: 420px;
    }
    .btn-sage {
      background-color: var(--sage-primary);
      color: #fff;
      border: none;
    }
    .btn-sage:hover {
      background-color: var(--sage-dark);
      color: #fff;
    }
  </style>
</head>
<body class="p-3">

  <div class="auth-card p-4 p-md-5">
    <div class="text-center mb-4">
      <h3 class="fw-bold" style="color: var(--sage-dark);">Welcome Back</h3>
      <p class="text-muted small">POPMART VIP Inventory</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2 small" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success py-2 small" role="alert"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-sage w-100 py-2 fw-semibold">Login</button>
    </form>

    <div class="text-center mt-4">
      <small class="text-muted">Don't have an account? <a href="register.php" class="text-decoration-none fw-semibold" style="color: var(--sage-primary);">Register here</a></small>
    </div>
  </div>

</body>
</html>