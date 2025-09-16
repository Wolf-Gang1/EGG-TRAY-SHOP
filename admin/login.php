<?php
session_start();
require_once __DIR__ . '/../src/db.php';

// Hardcoded admin credentials for demo
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'password123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../public/assets/css/style.css">
</head>
<body>
  <h1>Admin Login</h1>
  <?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  
  <form method="post">
    <label>Username:
      <input type="text" name="username" required>
    </label><br><br>
    <label>Password:
      <input type="password" name="password" required>
    </label><br><br>
    <button type="submit">Login</button>
  </form>
</body>
</html>
