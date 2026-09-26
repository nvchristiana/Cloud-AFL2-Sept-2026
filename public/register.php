<?php
session_start();
require_once __DIR__ . '/firebase_config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            $user = $auth->createUserWithEmailAndPassword($email, $password);
            $success = "Account created successfully! You can now login.";
        } catch (\Throwable $e) {
            $error = $e->getMessage();
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
  <title>Register - POPMART VIP</title>
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
      <h3 class="fw-bold" style="color: var(--sage-dark);">Register Account</h3>
      <p class="text-muted small">POPMART VIP Inventory</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2 small" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success py-2 small" role="alert"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
      </div>
      <button type="submit" class="btn btn-sage w-100 py-2 fw-semibold">Register</button>
    </form>

    <div class="text-center mt-4">
      <small class="text-muted">Already have an account? <a href="login.php" class="text-decoration-none fw-semibold" style="color: var(--sage-primary);">Login here</a></small>
    </div>
  </div>

</body>
</html>