<?php
// fichier: profile.php
require_once 'includes/init.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        $query = "UPDATE users SET email = :email, phone = :phone WHERE id = :id";
        $stmt = $db->prepare($query);
        if ($stmt->execute([':email' => $email, ':phone' => $phone, ':id' => $_SESSION['user_id']])) {
            $_SESSION['success'] = "Profil mis à jour !";
            redirect('profile.php');
        }
    }
    
    if (isset($_POST['change_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        $query = "SELECT password FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (password_verify($old_password, $user['password'])) {
            if ($new_password === $confirm_password && strlen($new_password) >= 6) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $db->prepare("UPDATE users SET password = :pwd WHERE id = :id");
                if ($update->execute([':pwd' => $new_hash, ':id' => $_SESSION['user_id']])) {
                    $_SESSION['success'] = "Mot de passe changé !";
                }
            } else {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas ou sont trop courts.";
            }
        } else {
            $_SESSION['error'] = "Mot de passe actuel incorrect.";
        }
        redirect('profile.php');
    }
}

$query = "SELECT * FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h1 class="mb-4">Mon profil</h1>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Informations personnelles</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Nom d'utilisateur</label>
                        <input type="text" class="form-control" value="<?php echo $user['username']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Téléphone</label>
                        <input type="tel" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Changer le mot de passe</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Mot de passe actuel</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Confirmer le mot de passe</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-warning">Changer le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>