<?php
// fichier: technicien/historique.php
require_once '../includes/init.php';
requireRole('technicien');

$query = "SELECT r.*, u.username as client_name, e.note, e.avis 
          FROM reclamations r 
          JOIN users u ON r.client_id = u.id
          LEFT JOIN evaluations e ON r.id = e.reclamation_id
          WHERE r.technicien_id = :user_id 
          AND r.statut IN ('resolue', 'fermee')
          ORDER BY r.updated_at DESC";

$stmt = $db->prepare($query);
$stmt->execute([
    ':user_id' => $_SESSION['user_id']
]);

$historique = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="mb-4">Historique des interventions</h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Note</th>
                            <th>Avis</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($historique as $inter): ?>
                        <tr>
                            <td><?= $inter['ticket_number']; ?></td>
                            <td><?= $inter['client_name']; ?></td>
                            <td><?= $inter['type_panne']; ?></td>
                            <td><?= date('d/m/Y', strtotime($inter['updated_at'])); ?></td>
                            <td>
                                <?php if(!empty($inter['note'])): ?>
                                    <?= str_repeat('⭐', $inter['note']); ?>
                                    (<?= $inter['note']; ?>/5)
                                <?php else: ?>
                                    Non évalué
                                <?php endif; ?>
                            </td>
                            <td><?= $inter['avis'] ?? '-'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>