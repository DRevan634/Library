<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');
header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../middleware.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ($_POST['action'] ?? null);

function validateBookInput($data, $isUpdate = false) {
    $errors = [];

    if (!$isUpdate || isset($data['title'])) {
        if (empty(trim($data['title'] ?? ''))) {
            $errors[] = "Title is required.";
        } elseif (strlen($data['title']) < 2) {
            $errors[] = "Title must be at least 2 characters.";
        }
    }

    if (!$isUpdate || isset($data['author'])) {
        if (empty(trim($data['author'] ?? ''))) {
            $errors[] = "Author is required.";
        }
    }

    if (!$isUpdate || isset($data['year'])) {
        if (!isset($data['year']) || !is_numeric($data['year'])) {
            $errors[] = "Year must be a number.";
        } 
        else {
            $year = (int)$data['year'];
            $currentYear = (int)date('Y');
            if ($year < 1500 || $year > $currentYear) {
                $errors[] = "Year must be between 1500 and " . $currentYear . ".";
            }
        }
    }

    if (!$isUpdate || isset($data['pages'])) {
    if (!isset($data['pages']) || !is_numeric($data['pages'])) {
        $errors[] = "Pages must be a number.";
    } else {
        $pages = (int)$data['pages'];
        if ($pages < 1 || $pages > 10000) {
            $errors[] = "Pages must be between 1 and 10000.";
        }
    }
}

    if (!$isUpdate || isset($data['price'])) {
        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
            $errors[] = "Price must be a non-negative number.";
        }
    }

    if (!$isUpdate || isset($data['rate'])) {
        if (!isset($data['rate']) || !is_numeric($data['rate']) || $data['rate'] < 0 || $data['rate'] > 5) {
            $errors[] = "Rate must be between 0.0 and 5.0.";
        }
    }

    return $errors;
}


if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $book = $stmt->fetch();
        if (!$book) {
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
            exit;
        }
        echo json_encode($book);
    } else {
        $stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
        echo json_encode($stmt->fetchAll());
    }
    exit;
}

if ($method === 'POST' && $action === 'update') {
    requireAdmin();
    $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['errors' => ['Invalid JSON']]);
        exit;
    }

    
    $errors = validateBookInput($input, true);
    if ($errors) {
        http_response_code(400);
        echo json_encode(['errors'=>$errors]);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE books 
        SET title=?, author=?, year=?, price=?, rate=?, pages=?, opis_68153=?
        WHERE id=?
    ");

    $stmt->execute([
        $input['title'],
        $input['author'],
        $input['year'],
        $input['price'],
        $input['rate'],
        $input['pages'],
        $input['opis_68153'],
        $input['id']
    ]);

    echo json_encode(['success'=>true]);
    exit;
}

if ($method === 'POST' && $action === 'delete') {
    requireAdmin();
    $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;
    $id = $input['id'] ?? 0;
    if (!$id) {
        http_response_code(404);
        echo json_encode(['error' => 'ID missing']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success'=>true]);
    exit;
}

if ($method === 'POST') {
    requireAdmin();
    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['errors' => ['Invalid JSON']]);
        exit;
    }
    
    $errors = validateBookInput($data);
    if ($errors) {
        http_response_code(400);
        echo json_encode(['errors' => $errors]);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO books (title, author, year, pages, price, rate, opis_68153) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([ $data['title'], $data['author'], (int)$data['year'], (int)$data['pages'], (float)$data['price'], (float)$data['rate'], $data['opis_68153'] ]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);





