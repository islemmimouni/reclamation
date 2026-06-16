<?php
// fichier: api/mark_all_notifications_read.php
require_once '../includes/init.php';
header('Content-Type: application/json');

// Méthode autorisée : POST uniquement
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit();
}

// Authentification requise
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit();
}

try {
    // AND is_read = 0 évite une UPDATE inutile sur les déjà lues
    $stmt = $db->prepare(
        "UPDATE notifications
         SET is_read = 1
         WHERE user_id = :user_id AND is_read = 0"
    );
    $stmt->execute([':user_id' => (int) $_SESSION['user_id']]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    error_log('[mark_all_notifications_read] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur serveur']);
}
?>