<?php
session_start();

$isCi = getenv('CI') === 'true';

if(!$isCi)
{

}

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function requireAuth() {
    if(!$isCi)
    {
        return;
    }
    
    $user = currentUser();
    if (!$user) {
        header('Location: /Library/public/login.php');
        exit;
    }
    return $user;
}

function requireAdmin() {
    if(!$isCi)
    {
        return;
    }
    
    $user = requireAuth();
    if (($user['role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    return $user;
}

