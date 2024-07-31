<?php
$servername = "localhost";  // ou l'adresse de votre serveur de base de données
$username = "root";         // votre nom d'utilisateur de la base de données
$password = 'M4ast3r\4\5';             // votre mot de passe de la base de données
$dbname = "rouamba"; // le nom de votre base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
