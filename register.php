<?php
// fichier: register.php
require_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    if (strlen($username) < 3) $errors[] = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (strlen($password) < 6) $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    if ($password !== $confirm_password) $errors[] = "Les mots de passe ne correspondent pas.";
    
    // Vérifier si l'utilisateur existe déjà
    $check = $db->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $check->execute([':username' => $username, ':email' => $email]);
    if ($check->fetch()) {
        $errors[] = "Ce nom d'utilisateur ou email est déjà utilisé.";
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, phone, password, role) VALUES (:username, :email, :phone, :password, 'client')";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':phone' => $phone,
            ':password' => $hashed_password
        ])) {
            $_SESSION['success'] = "Inscription réussie! Vous pouvez maintenant vous connecter.";
            redirect('login.php');
        } else {
            $_SESSION['error'] = "Erreur lors de l'inscription.";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestion des Réclamations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .register-card {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card register-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus fa-3x text-primary"></i>
                            <h3 class="mt-2">Inscription</h3>
                            <p class="text-muted">Créez votre compte client</p>
                        </div>
                        
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Nom d'utilisateur</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
                            <a href="index.php">← Retour à l'accueil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>