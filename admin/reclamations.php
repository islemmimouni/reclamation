<?php
require_once '../includes/init.php';
requireRole('admin');

// =====================
// TECHNICIENS
// =====================
$techniciens = $db->query("
    SELECT id, username 
    FROM users 
    WHERE role = 'technicien'
")->fetchAll(PDO::FETCH_ASSOC);

// =====================
// RECLAMATIONS
// =====================
$query = "SELECT r.*, 
                 u.username AS client_name, 
                 t.username AS technicien_name 
          FROM reclamations r 
          JOIN users u ON r.client_id = u.id 
          LEFT JOIN users t ON r.technicien_id = t.id 
          ORDER BY r.created_at DESC";

$stmt = $db->query($query);
$reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =====================
// ASSIGN TECHNICIEN
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_technician'])) {

    $reclamation_id = $_POST['reclamation_id'];
    $technicien_id = $_POST['technicien_id'];

    $query = "UPDATE reclamations 
              SET technicien_id = :tech_id, statut = 'assignee' 
              WHERE id = :rid";

    $stmt = $db->prepare($query);

    if ($stmt->execute([
        ':tech_id' => $technicien_id,
        ':rid' => $reclamation_id
    ])) {

        // =====================
        // NOTIF TECHNICIEN
        // =====================
        $db->prepare("
            INSERT INTO notifications (user_id, message)
            VALUES (:user_id, :message)
        ")->execute([
            ':user_id' => $technicien_id,
            ':message' => 'Nouvelle mission assignée'
        ]);

        // =====================
        // NOTIF CLIENT
        // =====================
        $db->prepare("
            INSERT INTO notifications (user_id, message)
            SELECT client_id, :message
            FROM reclamations
            WHERE id = :rid
        ")->execute([
            ':message' => 'Un technicien a été assigné à votre réclamation',
            ':rid' => $reclamation_id
        ]);

        $_SESSION['success'] = "Technicien assigné avec succès !";
        redirect('reclamations.php');
    }
}

// =====================
// DELETE RECLAMATION
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reclamation'])) {

    $reclamation_id = $_POST['reclamation_id'];

    $stmt = $db->prepare("DELETE FROM reclamations WHERE id = :rid");

    if ($stmt->execute([':rid' => $reclamation_id])) {
        $_SESSION['success'] = "Réclamation supprimée !";
        redirect('reclamations.php');
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <h1 class="mb-4">Gestion des réclamations</h1>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover" id="reclamationsTable">

                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Urgence</th>
                            <th>Statut</th>
                            <th>Technicien</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($reclamations as $rec): ?>
                        <tr>
                            <td><?= $rec['ticket_number'] ?></td>
                            <td><?= $rec['client_name'] ?></td>
                            <td><?= $rec['type_panne'] ?></td>
                            <td><?= $rec['urgence'] ?></td>
                            <td><?= $rec['statut'] ?></td>
                            <td><?= $rec['technicien_name'] ?? 'Non assigné' ?></td>

                            <td>

                                <?php if($rec['statut'] == 'en_attente'): ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="reclamation_id" value="<?= $rec['id'] ?>">

                                    <select name="technicien_id" required>
                                        <option value="">Choisir</option>
                                        <?php foreach($techniciens as $tech): ?>
                                            <option value="<?= $tech['id'] ?>">
                                                <?= $tech['username'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button type="submit" name="assign_technician">
                                        OK
                                    </button>
                                </form>
                                <?php endif; ?>

                                <form method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ?')">

                                    <input type="hidden" name="reclamation_id" value="<?= $rec['id'] ?>">

                                    <button type="submit" name="delete_reclamation">
                                        🗑
                                    </button>

                                </form>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>