<?php
// fichier: login.php
require_once 'includes/init.php';

if (isset($_SESSION['user_id'])) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE (username = :username OR email = :email)";
    $stmt = $db->prepare($query);
    $stmt->execute([':username' => $username, ':email' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['success'] = "Bienvenue " . $user['username'] . "!";
        
        // Redirection selon le rôle
        switch($user['role']) {
            case 'admin':
                redirect('dashboard/admin.php');
                break;
            case 'technicien':
                redirect('dashboard/technicien.php');
                break;
            default:
                redirect('dashboard/client.php');
        }
    } else {
        $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
        redirect('login.php');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Réclamations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .login-card {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-ticket-alt fa-3x text-primary"></i>
                            <h3 class="mt-2">Connexion</h3>
                            <p class="text-muted">Connectez-vous à votre compte</p>
                        </div>
                        
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Nom d'utilisateur ou Email</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
                            <a href="index.php">← Retour à l'accueil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>