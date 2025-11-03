<?php
require_once __DIR__ . '/../db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Library - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<?php include __DIR__ . '/_header.php'; ?>

<main class="flex-fill">
  <div class="container py-5">
    <h1 class="text-center mb-4">Welcome to Library</h1>
    <p class="lead text-center">Simple library management app built with PHP (REST API) and Bootstrap. Admins can manage books; users can browse the catalog.</p>
    <div class="text-center mt-4">
      <a href="books.php" class="btn btn-primary btn-lg me-2">View Books</a>
      <a href="register.php" class="btn btn-success btn-lg">Get Started</a>
    </div>
  </div>
</main>

<footer class="bg-light text-center py-3">
  &copy; <?=date('Y')?> Library
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
