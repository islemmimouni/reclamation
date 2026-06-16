<?php
require_once '../includes/init.php';
requireRole('client');

$stats = getStats($db, 'client', $_SESSION['user_id']);

$query = "SELECT * FROM reclamations 
          WHERE client_id = :id 
          ORDER BY created_at DESC 
          LIMIT 5";

$stmt = $db->prepare($query);
$stmt->execute([':id' => $_SESSION['user_id']]);

$recent_reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="mb-4">Dashboard Client</h1>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Réclamations</h5>
                    <h2><?php echo $stats['total_reclamations'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>En Cours</h5>
                    <h2><?php echo $stats['en_cours'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Résolues</h5>
                    <h2><?php echo $stats['resolues'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Dernières réclamations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr><th>Ticket</th><th>Type</th><th>Urgence</th><th>Statut</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_reclamations as $rec): ?>
                                <tr>
                                    <td><?php echo $rec['ticket_number']; ?></td>
                                    <td><?php echo $rec['type_panne']; ?></td>
                                    <td><?php echo $rec['urgence']; ?></td>
                                    <td><?php echo $rec['statut']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($rec['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>