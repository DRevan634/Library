<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['user'])) header('Location: books.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register - Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<?php include __DIR__ . '/_header.php'; ?>

<main class="flex-fill">
  <div class="container py-5" style="max-width:520px;">
    <h3 class="mb-4">Register</h3>
    <div id="alert"></div>
    <form id="registerForm" novalidate>
      <div class="mb-3">
        <input id="r_username" class="form-control" placeholder="Username (letters, numbers, _)" required pattern="[a-zA-Z0-9_]{3,}">
      </div>
      <div class="mb-3">
        <input id="r_email" type="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input id="r_password" type="password" class="form-control" placeholder="Password (min 6)" required minlength="6">
      </div>
      <div class="mb-3">
        <input id="r_password2" type="password" class="form-control" placeholder="Confirm password" required>
      </div>
      <button class="btn btn-success w-100">Register</button>
    </form>
  </div>
</main>

<footer class="bg-light text-center py-3">&copy; <?=date('Y')?> Library</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
