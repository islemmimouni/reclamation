<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

function redirect($url) {
    header("Location: $url");
    exit();
}

function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Veuillez vous connecter.";
        redirect('../login.php');
    }
}

function requireRole($role) {
    requireAuth();

    if ($_SESSION['role'] !== $role) {
        $_SESSION['error'] = "Accès non autorisé.";
        redirect('../index.php');
    }
}

function getStats($db, $role, $user_id = null) {

    $stats = [];

    // ADMIN
    if ($role === 'admin') {

        $query = "SELECT 
            (SELECT COUNT(*) FROM reclamations) AS total_reclamations,
            (SELECT COUNT(*) FROM reclamations WHERE statut = 'en_attente') AS en_attente,
            (SELECT COUNT(*) FROM reclamations WHERE statut = 'resolue') AS resolues,
            (SELECT COUNT(*) FROM users WHERE role = 'technicien') AS techniciens,
            (SELECT COUNT(*) FROM users WHERE role = 'client') AS clients";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // TECHNICIEN
    elseif ($role === 'technicien') {

        $query = "SELECT 
            (SELECT COUNT(*) FROM reclamations 
                WHERE technicien_id = :id 
                AND statut IN ('assignee','en_cours')) AS en_cours,

            (SELECT COUNT(*) FROM reclamations 
                WHERE technicien_id = :id 
                AND statut = 'resolue') AS resolues,

            (SELECT COUNT(*) FROM reclamations 
                WHERE technicien_id = :id) AS total";

        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $user_id]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CLIENT
    elseif ($role === 'client') {

        $query = "SELECT 
            (SELECT COUNT(*) FROM reclamations 
                WHERE client_id = :id) AS total_reclamations,

            (SELECT COUNT(*) FROM reclamations 
                WHERE client_id = :id 
                AND statut IN ('en_attente','assignee','en_cours')) AS en_cours,

            (SELECT COUNT(*) FROM reclamations 
                WHERE client_id = :id 
                AND statut = 'resolue') AS resolues";

        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $user_id]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return $stats;
}

function getNotifications($db, $user_id, $limit = 5) {

    $limit = (int) $limit;

    $query = "SELECT * FROM notifications 
              WHERE user_id = :id 
              ORDER BY created_at DESC 
              LIMIT $limit";

    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>