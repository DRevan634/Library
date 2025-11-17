<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../middleware.php';
requireAuth();
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Books — Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<?php include __DIR__ . '/_header.php'; ?>

<main class="flex-fill">
  <div class="container py-4">
    <h2 class="mb-4">Book Catalog</h2>

    <div id="alert-box"></div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered text-center align-middle">
        <thead class="table-dark">
          <tr>
            <?php if ($user && $user['role'] === 'admin'): ?>
            <th>ID</th>
            <?php endif; ?>
            <th>Title</th><th>Author</th><th>Year</th><th>Pages</th><th>Price</th><th>Rate</th><th>Opis_68153</th>
            <?php if ($user && $user['role'] === 'admin'): ?>
            <th>Actions</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody id="books-table"></tbody>
      </table>
    </div>

    <?php if ($user && $user['role'] === 'admin'): ?>
      <div class="card mt-4 p-3">
        <h5>Add New Book</h5>
        <form id="addForm" class="row g-2">
          <div class="col-md-4"><input id="b_title" class="form-control" placeholder="Title" required minlength="2"></div>
          <div class="col-md-3"><input id="b_author" class="form-control" placeholder="Author" required></div>
          <div class="col-md-1"><input id="b_year" type="number" class="form-control" placeholder="Year" required  min="1500" max="<?= date('Y'); ?>"></div>
          <div class="col-md-2"><input id="b_pages" type="number" class="form-control" placeholder="Pages" required  min="1" max="10000"></div>
          <div class="col-md-2"><input id="b_price" type="number" min="0" step="0.01" class="form-control" placeholder="Price" required></div>
          <div class="col-md-1"><input id="b_rate" type="number" step="0.1" min="0" max="5" class="form-control" placeholder="Rate" required></div>
          <div class="col-md-1"><input id="b_opis_68153"class="form-control" placeholder="Opis_68153" required></div>
          <div class="col-md-1"><button class="btn btn-primary w-100" type="submit">Add</button></div>
        </form>
      </div>
    <?php endif; ?>

  </div>
</main>

<footer class="bg-light text-center py-3">&copy; <?=date('Y')?> Library</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/common.js"></script>
<script src="js/books.js"></script>
</body>
</html>
