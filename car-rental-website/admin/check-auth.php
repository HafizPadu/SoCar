<?php
session_start();
header('Content-Type: application/json');

$response = [
    'logged_in' => isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true,
    'username' => $_SESSION['admin_username'] ?? null,
    'session_id' => session_id()
];

echo json_encode($response);
?>