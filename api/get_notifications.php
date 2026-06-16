<?php
// fichier: api/get_notifications.php
require_once '../includes/init.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non authentifié']);
    exit();
}

$query = "SELECT COUNT(*) as unread FROM notifications 
          WHERE user_id = :user_id AND is_read = 0";
$stmt = $db->prepare($query);
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['unread_count' => $result['unread']]);
?>