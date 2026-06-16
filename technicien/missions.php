<?php
require_once '../includes/init.php';
requireRole('technicien');

$user_id = $_SESSION['user_id'];

/* =========================
   MISSIONS DISPONIBLES
========================= */
function getAvailableMissions($db)
{
    $query = "SELECT r.*, u.username AS client_name, u.phone, u.email
              FROM reclamations r
              JOIN users u ON r.client_id = u.id
              WHERE r.statut = 'en_attente'
              ORDER BY r.created_at DESC";

    return $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

/* =========================
   MES MISSIONS
========================= */
function getMyMissions($db, $user_id)
{
    $query = "SELECT r.*, u.username AS client_name, u.phone, u.email
              FROM reclamations r
              JOIN users u ON r.client_id = u.id
              WHERE r.technicien_id = :id
              AND r.statut IN ('assignee','en_cours','resolue','fermee')
              ORDER BY r.created_at DESC";

    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* =========================
   ACCEPTER MISSION
========================= */
function acceptMission($db, $user_id, $mission_id)
{
    $query = "UPDATE reclamations
              SET technicien_id = :tech,
                  statut = 'assignee'
              WHERE id = :id AND statut = 'en_attente'";

    $stmt = $db->prepare($query);

    $ok = $stmt->execute([
        ':tech' => $user_id,
        ':id' => $mission_id
    ]);

    if ($ok) {
        $notif = "INSERT INTO notifications (user_id, message)
                  SELECT client_id, 'Votre réclamation a été prise en charge'
                  FROM reclamations
                  WHERE id = :id";

        $db->prepare($notif)->execute([':id' => $mission_id]);
    }

    return $ok;
}

/* =========================
   UPDATE MISSION (SANS date_resolution)
========================= */
function updateMission($db, $user_id, $mission_id, $statut, $rapport)
{
    $query = "UPDATE reclamations
              SET statut = :statut,
                  rapport_technicien = :rapport
              WHERE id = :id
              AND technicien_id = :tech";

    $stmt = $db->prepare($query);

    return $stmt->execute([
        ':statut' => $statut,
        ':rapport' => $rapport,
        ':id' => $mission_id,
        ':tech' => $user_id
    ]);
}

/* =========================
   ACTIONS POST
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // accepter mission
    if (isset($_POST['accept_mission'])) {
        acceptMission($db, $user_id, $_POST['reclamation_id']);
        header("Location: missions.php");
        exit;
    }

    // update mission
    if (isset($_POST['update_status'])) {
        updateMission(
            $db,
            $user_id,
            $_POST['reclamation_id'],
            $_POST['statut'],
            trim($_POST['rapport'])
        );

        header("Location: missions.php");
        exit;
    }
}

/* =========================
   DATA
========================= */
$available_missions = getAvailableMissions($db);
$my_missions = getMyMissions($db, $user_id);
?>

<?php include '../includes/header.php'; ?>

<div class="container py-4">

    <h2 class="mb-4">📋 Gestion des missions</h2>

    <!-- ================= DISPONIBLES ================= -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Missions disponibles
        </div>

        <div class="card-body">
            <div class="row">

                <?php foreach ($available_missions as $m): ?>
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">

                            <h6>#<?= $m['ticket_number']; ?></h6>
                            <p><b>Client:</b> <?= $m['client_name']; ?></p>
                            <p><b>Type:</b> <?= $m['type_panne']; ?></p>
                            <p><b>Urgence:</b> <?= $m['urgence']; ?></p>

                            <form method="POST">
                                <input type="hidden" name="reclamation_id" value="<?= $m['id']; ?>">
                                <button name="accept_mission" class="btn btn-success btn-sm">
                                    Accepter
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <!-- ================= MES MISSIONS ================= -->
    <div class="card">
        <div class="card-header bg-info text-white">
            Mes missions
        </div>

        <div class="card-body">

            <?php if (empty($my_missions)): ?>
                <p>Aucune mission.</p>
            <?php endif; ?>

            <?php foreach ($my_missions as $m): ?>
            <div class="card mb-3">
                <div class="card-body">

                    <h6>#<?= $m['ticket_number']; ?></h6>
                    <p><b>Client:</b> <?= $m['client_name']; ?></p>
                    <p><b>Statut:</b> <?= $m['statut']; ?></p>

                    <?php if (!in_array($m['statut'], ['resolue','fermee'])): ?>

                    <form method="POST">

                        <input type="hidden" name="reclamation_id" value="<?= $m['id']; ?>">

                        <textarea name="rapport" class="form-control mb-2" required></textarea>

                        <select name="statut" class="form-select mb-2">
                            <option value="en_cours">En cours</option>
                            <option value="resolue">Résolue</option>
                            <option value="fermee">Fermée</option>
                        </select>

                        <button name="update_status" class="btn btn-primary btn-sm">
                            Mettre à jour
                        </button>

                    </form>

                    <?php endif; ?>

                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>