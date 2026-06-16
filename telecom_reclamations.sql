-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 14 mai 2026 à 20:49
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `telecom_reclamations`
--

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `reclamation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` >= 1 and `note` <= 5),
  `avis` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `reclamation_id`, `user_id`, `note`, `avis`, `created_at`) VALUES
(2, 8, 7, 3, 'tres bien', '2026-05-13 20:48:30'),
(3, 6, 3, 5, 'j\'adore', '2026-05-14 11:54:25');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reclamation_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `reclamation_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, NULL, 'Nouvelle réclamation de mimouniislem', 0, '2026-05-13 08:41:16'),
(2, 1, NULL, 'Nouvelle réclamation de mimouniislem', 0, '2026-05-13 08:41:29'),
(3, 1, NULL, 'Nouvelle réclamation de mimouniislem', 0, '2026-05-13 08:42:05'),
(4, 1, NULL, 'Nouvelle réclamation de mimouniislem', 0, '2026-05-13 08:43:34'),
(5, 2, NULL, 'Nouvelle mission assignée', 0, '2026-05-13 09:01:00'),
(6, 3, NULL, 'Un technicien a été assigné à votre réclamation', 0, '2026-05-13 09:01:00'),
(7, 3, NULL, 'Un technicien a accepté votre réclamation', 0, '2026-05-13 09:23:15'),
(8, 3, NULL, 'Un technicien a accepté votre demande', 0, '2026-05-13 09:31:28'),
(9, 3, NULL, 'Votre réclamation a été prise en charge', 0, '2026-05-13 09:34:32'),
(10, 3, NULL, 'Votre réclamation a été prise en charge', 0, '2026-05-13 09:35:46'),
(11, 1, NULL, 'Nouvelle réclamation de sinda', 0, '2026-05-13 19:50:45'),
(12, 4, NULL, 'Nouvelle réclamation de sinda', 0, '2026-05-13 19:50:45'),
(14, 7, NULL, 'Votre réclamation a été prise en charge', 0, '2026-05-13 19:53:20'),
(15, 1, NULL, 'Nouvelle réclamation de sinda', 0, '2026-05-13 20:51:06'),
(16, 4, NULL, 'Nouvelle réclamation de sinda', 0, '2026-05-13 20:51:06'),
(18, 7, NULL, 'Votre réclamation a été prise en charge', 0, '2026-05-13 20:52:34'),
(19, 2, NULL, 'Nouvelle mission assignée', 0, '2026-05-14 11:57:07'),
(20, 3, NULL, 'Un technicien a été assigné à votre réclamation', 0, '2026-05-14 11:57:08');

-- --------------------------------------------------------

--
-- Structure de la table `reclamations`
--

CREATE TABLE `reclamations` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(20) NOT NULL,
  `client_id` int(11) NOT NULL,
  `technicien_id` int(11) DEFAULT NULL,
  `type_panne` varchar(50) NOT NULL,
  `localisation` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `urgence` enum('basse','moyenne','haute','critique') DEFAULT 'moyenne',
  `statut` enum('en_attente','assignee','en_cours','resolue','fermee','rejetee') DEFAULT 'en_attente',
  `rapport_technicien` text DEFAULT NULL,
  `note_client` int(11) DEFAULT NULL,
  `avis_client` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reclamations`
--

INSERT INTO `reclamations` (`id`, `ticket_number`, `client_id`, `technicien_id`, `type_panne`, `localisation`, `description`, `urgence`, `statut`, `rapport_technicien`, `note_client`, `avis_client`, `created_at`, `updated_at`, `resolved_at`) VALUES
(1, 'TIC000001', 3, 6, 'Téléphonie', 'tunis', 'ja iun probem dan sle reseuaux', 'moyenne', 'resolue', 'fin', NULL, NULL, '2026-05-13 08:37:21', '2026-05-13 09:35:23', NULL),
(2, 'TIC000002', 3, NULL, 'Téléphonie', 'tunis', 'ja iun probem dan sle reseuaux', 'moyenne', 'en_attente', NULL, NULL, NULL, '2026-05-13 08:37:28', '2026-05-13 08:37:28', NULL),
(3, 'TIC000003', 3, 6, 'Internet', 'tunis', 'zdhuzejdbzbbzded', 'basse', 'fermee', 'oui', NULL, NULL, '2026-05-13 08:38:55', '2026-05-13 09:34:24', NULL),
(4, 'TIC000004', 3, 2, 'Internet', 'tunis', 'zdhuzejdbzbbzded', 'basse', 'resolue', 'tout est bien', NULL, NULL, '2026-05-13 08:41:16', '2026-05-14 11:59:01', NULL),
(5, 'TIC000005', 3, 6, 'Internet', 'tunis', 'zdhuzejdbzbbzded', 'basse', 'assignee', NULL, NULL, NULL, '2026-05-13 08:41:29', '2026-05-13 09:35:46', NULL),
(6, 'TIC000006', 3, 6, 'Téléphonie', 'tunis', 'ddddd', 'critique', 'resolue', 'jai faire cette mession avec sucett', NULL, NULL, '2026-05-13 08:42:05', '2026-05-13 09:25:36', NULL),
(7, 'TIC000007', 3, 2, 'Téléphonie', 'tunis', 'ddddddsd', 'critique', 'fermee', 'pl', NULL, NULL, '2026-05-13 08:43:34', '2026-05-13 09:37:24', NULL),
(8, 'TIC000008', 7, 6, 'Autre', 'mateur', 'jai une problem sur niveau internet', 'moyenne', 'resolue', 'jai faire solver la problem', NULL, NULL, '2026-05-13 19:50:45', '2026-05-13 19:56:17', NULL),
(9, 'TIC000009', 7, 6, 'Fibre optique', 'ariana', 'svp', 'critique', 'assignee', NULL, NULL, NULL, '2026-05-13 20:51:06', '2026-05-13 20:52:34', NULL);

--
-- Déclencheurs `reclamations`
--
DELIMITER $$
CREATE TRIGGER `generate_ticket_number` BEFORE INSERT ON `reclamations` FOR EACH ROW BEGIN
    DECLARE ticket_num INT;
    SET ticket_num = (SELECT IFNULL(MAX(CAST(SUBSTRING(ticket_number, 4) AS UNSIGNED)), 0) + 1 FROM reclamations);
    SET NEW.ticket_number = CONCAT('TIC', LPAD(ticket_num, 6, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('client','technicien','admin') DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@telecom.com', '0102030405', 'admin', '2026-05-12 23:11:52'),
(2, 'technicien1', '$2y$10$61v7ugFz8V1NomJWxOPwz.Wa9wi6zAY9430VJiqdPTDzma/xMPf5.', 'tech@telecom.com', '0601020304', 'technicien', '2026-05-12 23:11:52'),
(3, 'mimouniislem', '$2y$10$61v7ugFz8V1NomJWxOPwz.Wa9wi6zAY9430VJiqdPTDzma/xMPf5.', 'mimouniislem06@gmail.com', '55809660', 'client', '2026-05-13 08:14:23'),
(4, 'admin2', '$2y$10$61v7ugFz8V1NomJWxOPwz.Wa9wi6zAY9430VJiqdPTDzma/xMPf5.', 'admin2@test.com', NULL, 'admin', '2026-05-13 08:53:58'),
(6, 'technicien2', '$2y$10$cSP2d0t7CW.ztVxLHEWnDuv96Irfhu.bvENW16YmSYv4JGs9XKoni', 'mimouni06@gmail.com', '55809660', 'technicien', '2026-05-13 09:09:35'),
(7, 'sinda', '$2y$10$d5lgoubhgLa18RHp61qpO./mZ10HYuSmtNYCGStNbhSmzE/DmO5uG', 'sinda06@gmail.com', '88809560', 'client', '2026-05-13 19:48:26'),
(8, 'teh3', '$2y$10$eOqtbpr4SRA7zySPZ7WU2utTqx5wvtR8irk7C9V.xlSkLbH/5z.U2', 'teh3@gmail.com', '88809546', 'technicien', '2026-05-13 20:59:42');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_evaluation` (`reclamation_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reclamation_id` (`reclamation_id`);

--
-- Index pour la table `reclamations`
--
ALTER TABLE `reclamations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `technicien_id` (`technicien_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `reclamations`
--
ALTER TABLE `reclamations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`reclamation_id`) REFERENCES `reclamations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`reclamation_id`) REFERENCES `reclamations` (`id`);

--
-- Contraintes pour la table `reclamations`
--
ALTER TABLE `reclamations`
  ADD CONSTRAINT `reclamations_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reclamations_ibfk_2` FOREIGN KEY (`technicien_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
