<?php
// fichier: admin/utilisateurs.php
require_once '../includes/init.php';
requireRole('admin');

// Ajouter un technicien
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_technicien'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 🔍 CHECK USERNAME EXISTE
    $check = $db->prepare("SELECT id FROM users WHERE username = :username");
    $check->execute([':username' => $username]);

    if ($check->fetch()) {
        $_SESSION['error'] = "❌ Ce nom d'utilisateur existe déjà.";
        redirect('utilisateurs.php');
    }

    // 🔍 CHECK EMAIL EXISTE (important aussi)
    $check = $db->prepare("SELECT id FROM users WHERE email = :email");
    $check->execute([':email' => $email]);

    if ($check->fetch()) {
        $_SESSION['error'] = "❌ Cet email existe déjà.";
        redirect('utilisateurs.php');
    }

    // ➕ INSERT
    $query = "INSERT INTO users (username, email, phone, password, role)
              VALUES (:username, :email, :phone, :password, 'technicien')";

    $stmt = $db->prepare($query);

    if ($stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':phone' => $phone,
        ':password' => $password
    ])) {
        $_SESSION['success'] = "Technicien ajouté avec succès !";
        redirect('utilisateurs.php');
    }
}
// Désactiver un utilisateur
if (isset($_GET['toggle_status'])) {
    $user_id = $_GET['toggle_status'];
    $query = "UPDATE users SET is_active = NOT is_active WHERE id = :id AND role != 'admin'";
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $user_id]);
    redirect('utilisateurs.php');
}

// Récupérer tous les utilisateurs
$users = $db->query("SELECT * FROM users ORDER BY role, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <h1 class="mb-4">Gestion des utilisateurs</h1>
    
    <!-- Ajouter technicien -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Ajouter un technicien</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-2">
                    <input type="tel" name="phone" class="form-control" placeholder="Téléphone" required>
                </div>
                <div class="col-md-2">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_technicien" class="btn btn-primary w-100">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Liste des utilisateurs -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Date inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $user['role'] == 'admin' ? 'danger' : 
                                        ($user['role'] == 'technicien' ? 'info' : 'success'); 
                                ?>"><?php echo $user['role']; ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $user['is_active'] ? 'Actif' : 'Inactif'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if($user['role'] != 'admin'): ?>
                                <a href="?toggle_status=<?php echo $user['id']; ?>" class="btn btn-sm btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?>">
                                    <?php echo $user['is_active'] ? 'Désactiver' : 'Activer'; ?>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>