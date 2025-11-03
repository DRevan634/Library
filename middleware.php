<?php
session_start();

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function requireAuth() {
    $user = currentUser();
    if (!$user) {
        header('Location: /Library/public/login.php');
        exit;
    }
    return $user;
}

function requireAdmin() {
    $user = requireAuth();
    if (($user['role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    return $user;
}
