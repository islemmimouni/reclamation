<?php
// fichier: includes/footer.php
?>

    </div><!-- fin .container mt-4 -->

    <footer class="site-footer">
        <div class="footer-top">
            <div class="container">
                <div class="row g-4">
                    <!-- Colonne 1 : Marque -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand">
                            <h5><i class="fas fa-ticket-alt me-2"></i>Gestion Réclamations</h5>
                            <p>Plateforme de gestion des réclamations Telecom. Suivez vos tickets en temps réel et obtenez une assistance rapide.</p>
                            <div class="footer-socials">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne 2 : Liens rapides -->
                    <div class="col-lg-2 col-md-6">
                        <h6 class="footer-title">Liens rapides</h6>
                        <ul class="footer-links">
                            <li><a href="../index.php"><i class="fas fa-chevron-right"></i> Accueil</a></li>
                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'client'): ?>
                                <li><a href="../dashboard/client.php"><i class="fas fa-chevron-right"></i> Dashboard</a></li>
                                <li><a href="../reclamation/creer.php"><i class="fas fa-chevron-right"></i> Nouvelle réclamation</a></li>
                                <li><a href="../reclamation/mes_reclamations.php"><i class="fas fa-chevron-right"></i> Mes réclamations</a></li>
                            <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'technicien'): ?>
                                <li><a href="../dashboard/technicien.php"><i class="fas fa-chevron-right"></i> Dashboard</a></li>
                                <li><a href="../technicien/missions.php"><i class="fas fa-chevron-right"></i> Mes missions</a></li>
                            <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                <li><a href="../dashboard/admin.php"><i class="fas fa-chevron-right"></i> Dashboard</a></li>
                                <li><a href="../admin/reclamations.php"><i class="fas fa-chevron-right"></i> Réclamations</a></li>
                                <li><a href="../admin/utilisateurs.php"><i class="fas fa-chevron-right"></i> Utilisateurs</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Colonne 3 : Support -->
                    <div class="col-lg-3 col-md-6">
                        <h6 class="footer-title">Support</h6>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-chevron-right"></i> FAQ</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Documentation</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Politique de confidentialité</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Conditions d'utilisation</a></li>
                        </ul>
                    </div>

                    <!-- Colonne 4 : Contact -->
                    <div class="col-lg-3 col-md-6">
                        <h6 class="footer-title">Contact</h6>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Rue Telecom, Tunis, Tunisie</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span>+216 71 000 000</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>support@telecom.tn</span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Lun – Ven : 8h00 – 17h00</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <p class="mb-0">&copy; <?= date('Y') ?> Gestion des Réclamations Telecom. Tous droits réservés.</p>
                    <p class="mb-0 footer-made">
                        Fait avec <i class="fas fa-heart text-danger mx-1"></i> pour un meilleur service client
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>

    <?php if(isset($_SESSION['user_id'])): ?>
    <script>
        // Rafraîchir le badge toutes les 30s (le JS principal est dans header.php)
        if (typeof fetchNotifications === 'function') {
            setInterval(fetchNotifications, 30000);
        }
    </script>
    <?php endif; ?>

</body>
</html>