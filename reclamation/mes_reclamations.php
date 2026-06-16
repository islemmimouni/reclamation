<?php
require_once '../includes/init.php';
requireRole('client');

$query = "SELECT r.*,
        CASE 
            WHEN r.technicien_id IS NOT NULL 
            THEN u.username 
            ELSE 'Non assigné' 
        END as technicien_name
        FROM reclamations r
        LEFT JOIN users u ON r.technicien_id = u.id
        WHERE r.client_id = :id
        ORDER BY r.created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute([':id' => $_SESSION['user_id']]);
$reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="mb-4">Mes réclamations</h1>

    <?php if(empty($reclamations)): ?>
        <div class="alert alert-info">Vous n'avez pas encore de réclamation.</div>
        <a href="creer.php" class="btn btn-primary">Créer une réclamation</a>

    <?php else: ?>

        <?php foreach($reclamations as $rec): ?>
            <div class="card mb-3">

                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <strong>Ticket: <?= $rec['ticket_number'] ?></strong>

                        <span class="badge bg-<?=
                            $rec['statut'] == 'resolue' ? 'success' :
                            ($rec['statut'] == 'en_attente' ? 'warning' : 'info')
                        ?>">
                            <?= $rec['statut'] ?>
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <p><strong>Type:</strong> <?= $rec['type_panne'] ?></p>
                    <p><strong>Urgence:</strong> <?= $rec['urgence'] ?></p>
                    <p><strong>Localisation:</strong> <?= $rec['localisation'] ?></p>
                    <p><strong>Description:</strong> <?= nl2br($rec['description']) ?></p>
                    <p><strong>Technicien:</strong> <?= $rec['technicien_name'] ?></p>
                    <p><strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($rec['created_at'])) ?></p>

                    <?php if(!empty($rec['rapport_technicien'])): ?>
                        <div class="alert alert-info mt-3">
                            <?= nl2br($rec['rapport_technicien']) ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>