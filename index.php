<?php
// fichier: index.php
require_once 'includes/init.php';

$query = "SELECT 
            (SELECT COUNT(*) FROM reclamations WHERE statut = 'resolue') as reclamations_resolues,
            (SELECT ROUND(AVG(note), 1) FROM evaluations WHERE note IS NOT NULL) as satisfaction,
            (SELECT COUNT(*) FROM users WHERE role = 'client') as clients_actifs";
$stmt = $db->prepare($query);
$stmt->execute();
$public_stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réclamations Telecom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Gestion des Réclamations Telecom</h1>
            <p class="lead mb-4">Solution complète pour la gestion et le suivi de vos réclamations 24h/24</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <div class="d-flex justify-content-center gap-3">
                    <a href="login.php" class="btn btn-light btn-lg">Se connecter</a>
                    <a href="register.php" class="btn btn-outline-light btn-lg">S'inscrire</a>
                </div>
            <?php else: ?>
                <a href="dashboard/<?php echo $_SESSION['role']; ?>.php" class="btn btn-light btn-lg">
                    Accéder à mon espace
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <div class="stat-number"><?php echo number_format($public_stats['reclamations_resolues'] ?? 0); ?></div>
                    <p>Réclamations résolues</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-star fa-3x text-warning mb-3"></i>
                    <div class="stat-number"><?php echo $public_stats['satisfaction'] ?? 0; ?>/5</div>
                    <p>Satisfaction client</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-users fa-3x text-info mb-3"></i>
                    <div class="stat-number"><?php echo number_format($public_stats['clients_actifs'] ?? 0); ?></div>
                    <p>Clients satisfaits</p>
                </div>
            </div>
        </div>

        <h2 class="text-center mb-5">Nos fonctionnalités</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-headset fa-4x text-primary mb-3"></i>
                        <h5>Support 24/7</h5>
                        <p>Notre équipe est disponible 24h/24 pour répondre à vos besoins.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-4x text-success mb-3"></i>
                        <h5>Suivi en temps réel</h5>
                        <p>Suivez l'évolution de vos réclamations en temps réel.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt fa-4x text-info mb-3"></i>
                        <h5>Sécurisé</h5>
                        <p>Vos données sont protégées avec les meilleurs standards.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>