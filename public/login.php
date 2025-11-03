<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['user'])) header('Location: books.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login - Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<?php include __DIR__ . '/_header.php'; ?>

<main class="flex-fill">
  <div class="container py-5" style="max-width:480px;">
    <h3 class="mb-4">Login</h3>
    <div id="alert"></div>
    <form id="loginForm">
      <div class="mb-3"><input class="form-control" id="username" placeholder="Username" required></div>
      <div class="mb-3"><input class="form-control" id="password" type="password" placeholder="Password" required></div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</main>

<footer class="bg-light text-center py-3">&copy; <?=date('Y')?> Library</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
