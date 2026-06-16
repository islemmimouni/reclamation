<?php
// fichier: dashboard/technicien.php
require_once '../includes/init.php';
requireRole('technicien');

$stats = getStats($db, 'technicien', $_SESSION['user_id']);

$query = "SELECT r.*, u.username as client_name 
          FROM reclamations r 
          JOIN users u ON r.client_id = u.id 
          WHERE r.technicien_id = :user_id 
          AND r.statut IN ('assignee', 'en_cours')
          ORDER BY r.created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$missions = $stmt->fetchAll(PDO::FETCH_ASSOC);?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="mb-4">Dashboard Technicien</h1>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Missions en cours</h5>
                    <h2><?php echo $stats['missions_en_cours'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Missions terminées</h5>
                    <h2><?php echo $stats['missions_terminees'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Total prises en charge</h5>
                    <h2><?php echo $stats['total_prises_en_charge'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Mes missions en cours</h5>
        </div>
        <div class="card-body">
            <?php if(empty($missions)): ?>
                <p class="text-muted">Aucune mission en cours.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Ticket</th><th>Client</th><th>Type</th><th>Urgence</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($missions as $mission): ?>
                            <tr>
                                <td><?php echo $mission['ticket_number']; ?></td>
                                <td><?php echo $mission['client_name']; ?></td>
                                <td><?php echo $mission['type_panne']; ?></td>
                                <td><?php echo $mission['urgence']; ?></td>
                                <td><a href="../technicien/missions.php" class="btn btn-sm btn-primary">Voir</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>