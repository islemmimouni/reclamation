<?php
require_once '../includes/init.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { echo json_encode(['notifications' => []]); exit(); }

try {
    $stmt = $db->prepare("SELECT id, message, is_read, created_at FROM notifications 
                          WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 20");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    echo json_encode(['notifications' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['notifications' => []]);



    }