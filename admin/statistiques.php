<?php
require_once '../includes/init.php';
requireRole('admin');

// TOTAL RECLAMATIONS
$total = $db->query("SELECT COUNT(*) AS total FROM reclamations")
            ->fetch(PDO::FETCH_ASSOC);

// EN ATTENTE
$en_attente = $db->query("
    SELECT COUNT(*) AS total 
    FROM reclamations 
    WHERE statut = 'en_attente'
")->fetch(PDO::FETCH_ASSOC);

// RESOLUES
$resolues = $db->query("
    SELECT COUNT(*) AS total 
    FROM reclamations 
    WHERE statut = 'resolue'
")->fetch(PDO::FETCH_ASSOC);

// TECHNICIENS
$techniciens = $db->query("
    SELECT COUNT(*) AS total 
    FROM users 
    WHERE role = 'technicien'
")->fetch(PDO::FETCH_ASSOC);

// CLIENTS
$clients = $db->query("
    SELECT COUNT(*) AS total 
    FROM users 
    WHERE role = 'client'
")->fetch(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Statistiques</h1>

    <div class="row">

        <div class="col-md-3">
            <div class="card p-3">
                <h4>Total réclamations</h4>
                <h2><?= $total['total'] ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h4>En attente</h4>
                <h2><?= $en_attente['total'] ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h4>Résolues</h4>
                <h2><?= $resolues['total'] ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h4>Techniciens</h4>
                <h2><?= $techniciens['total'] ?></h2>
            </div>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>