<?php
// fichier: config/database.php

class Database {
    private $host = "localhost";
    private $db_name = "telecom_reclamations";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );

            // Mode erreur SQL
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Encodage UTF-8
            $this->conn->exec("SET NAMES utf8");

        } catch(PDOException $exception) {
            die("Erreur de connexion : " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>