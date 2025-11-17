<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../middleware.php';
requireAdmin();
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
if (!$user || $user['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Book — Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<?php include __DIR__ . '/_header.php'; ?>

<main class="flex-fill">
  <div class="container py-5" style="max-width:700px;">
    <h3>Edit Book</h3>
    <div id="alert"></div>
    <form id="editForm">
      <input type="hidden" id="e_id">
      <div class="mb-3"><input id="e_title" class="form-control" placeholder="Title" required minlength="2"></div>
      <div class="mb-3"><input id="e_author" class="form-control" placeholder="Author" required></div>
      <div class="mb-3"><input id="e_year" type="number" class="form-control" placeholder="Year" required min="1500" max="<?= date('Y'); ?>"></div>
      <div class="mb-3"><input id="e_pages" type="number" class="form-control" placeholder="Pages" required min="1" max="10000"></div>
      <div class="mb-3"><input id="e_price" type="number" min="0" step="0.01" class="form-control" placeholder="Price" required></div>
      <div class="mb-3"><input id="e_rate" type="number" step="0.1" min="0" max="5" class="form-control" placeholder="Rate" required></div>
      <div class="mb-3"><input id="e_opis_68153"class="form-control" placeholder="Opis_68153" required></div>
      <button class="btn btn-primary">Save</button>
      <a href="books.php" class="btn btn-secondary ms-2">Back</a>
    </form>
  </div>
</main>

<footer class="bg-light text-center py-3">&copy; <?=date('Y')?> Library</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/common.js"></script>
<script src="js/books.js"></script>
</body>
</html>
