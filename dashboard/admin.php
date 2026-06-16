<?php
// fichier: dashboard/admin.php
require_once '../includes/init.php';
requireRole('admin');

$stats = getStats($db, 'admin');

// Données pour les graphiques
$query = "SELECT type_panne, COUNT(*) as count FROM reclamations GROUP BY type_panne";
$pannes_stats = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT urgence, COUNT(*) as count FROM reclamations GROUP BY urgence";
$urgence_stats = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as mois, COUNT(*) as total 
          FROM reclamations 
          WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
          GROUP BY DATE_FORMAT(created_at, '%Y-%m')
          ORDER BY mois";
$evolution = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <h1 class="mb-4">Dashboard Administrateur</h1>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Réclamations</h5>
                    <h2><?php echo $stats['total_reclamations'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>En Attente</h5>
                    <h2><?php echo $stats['en_attente'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Résolues</h5>
                    <h2><?php echo $stats['resolues'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Satisfaction</h5>
                    <h2><?php echo $stats['satisfaction_moyenne'] ?? 0; ?>/5</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Réclamations par type de panne</h5>
                </div>
                <div class="card-body">
                    <canvas id="pannesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Réclamations par urgence</h5>
                </div>
                <div class="card-body">
                    <canvas id="urgenceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Évolution des réclamations (6 mois)</h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('pannesChart'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode(array_column($pannes_stats, 'type_panne')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($pannes_stats, 'count')); ?>,
            backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b']
        }]
    }
});

new Chart(document.getElementById('urgenceChart'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_column($urgence_stats, 'urgence')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($urgence_stats, 'count')); ?>,
            backgroundColor: ['#28a745', '#ffc107', '#fd7e14', '#dc3545']
        }]
    }
});

new Chart(document.getElementById('evolutionChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($evolution, 'mois')); ?>,
        datasets: [{
            label: 'Nombre de réclamations',
            data: <?php echo json_encode(array_column($evolution, 'total')); ?>,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4
        }]
    }
});
</script>

<?php include '../includes/footer.php'; ?>