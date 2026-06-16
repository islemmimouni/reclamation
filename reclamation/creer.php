<?php
require_once '../includes/init.php';
requireRole('client');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_reclamation'])) {

    $type_panne = $_POST['type_panne'];
    $urgence = $_POST['urgence'];
    $localisation = trim($_POST['localisation']);
    $description = trim($_POST['description']);

    $errors = [];

    if (empty($localisation)) $errors[] = "La localisation est requise.";
    if (empty($description)) $errors[] = "La description est requise.";

    if (empty($errors)) {

        // =========================
        // INSERT RECLAMATION
        // =========================
        $query = "INSERT INTO reclamations 
                  (client_id, type_panne, urgence, localisation, description) 
                  VALUES 
                  (:client_id, :type_panne, :urgence, :localisation, :description)";

        $stmt = $db->prepare($query);

        $result = $stmt->execute([
            ':client_id' => $_SESSION['user_id'],
            ':type_panne' => $type_panne,
            ':urgence' => $urgence,
            ':localisation' => $localisation,
            ':description' => $description
        ]);

        if ($result) {

            // =========================
            // NOTIFICATION ADMIN (CORRIGÉ)
            // =========================
            $message = "Nouvelle réclamation de " . $_SESSION['user_nom'];

            $notif_query = "INSERT INTO notifications (user_id, message)
                            SELECT id, :message
                            FROM users
                            WHERE role = 'admin'";

            $db->prepare($notif_query)->execute([
                ':message' => $message
            ]);

            $_SESSION['success'] = "Votre réclamation a été soumise avec succès.";
            redirect('mes_reclamations.php');

        } else {
            $_SESSION['error'] = "Erreur lors de la soumission.";
        }

    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Nouvelle Réclamation</h4>
            </div>

            <div class="card-body">

                <form method="POST">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Type de panne</label>
                            <select name="type_panne" class="form-select" required>
                                <option value="Internet">Internet</option>
                                <option value="Téléphonie">Téléphonie</option>
                                <option value="Télévision">Télévision</option>
                                <option value="Fibre optique">Fibre optique</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Urgence</label>
                            <select name="urgence" class="form-select" required>
                                <option value="basse">Basse</option>
                                <option value="moyenne">Moyenne</option>
                                <option value="haute">Haute</option>
                                <option value="critique">Critique</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Localisation</label>
                        <input type="text" name="localisation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5" required></textarea>
                    </div>

                    <button type="submit" name="create_reclamation" class="btn btn-primary">
                        Soumettre
                    </button>

                    <a href="mes_reclamations.php" class="btn btn-secondary">
                        Annuler
                    </a>

                </form>

            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>