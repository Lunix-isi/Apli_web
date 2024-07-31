<?php
include('logging.php');
session_start();
include('db.php'); // Inclusion de votre fichier de connexion à la base de données
require 'vendor/autoload.php'; // Inclusion de l'autoload de Composer

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Ajouter Client";
include('header.php');

// Traitement de l'importation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    if ($file) {
        try {
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Skip the first row if it contains headers
            array_shift($data);

            $query = "INSERT INTO client (nom, prenom, tel, cnib) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            foreach ($data as $row) {
                $nom = $row[0];
                $prenom = $row[1];
                $tel = $row[2];
                $cnib = $row[3];
                $stmt->bind_param("ssss", $nom, $prenom, $tel, $cnib);
                $stmt->execute();
            }

            echo "<p>Clients importés avec succès.</p>";
        } catch (Exception $e) {
            echo "<p>Erreur lors de l'importation : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Veuillez sélectionner un fichier.</p>";
    }
}

// Traitement de l'ajout manuel
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_FILES['file'])) {
    if (isset($_POST['nom'], $_POST['prenom'], $_POST['tel'], $_POST['cnib'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $tel = $_POST['tel'];
        $cnib = $_POST['cnib'];

        $query = "INSERT INTO client (nom, prenom, tel, cnib) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nom, $prenom, $tel, $cnib);
        if ($stmt->execute()) {
			logMessage("L'utilisateur a enregistré un numéro RD: $numeroRD, Nom: $nom, Prénom: $prenom.");
            echo "<p>Client ajouté avec succès.</p>";
        } else {
            echo "<p>Erreur lors de l'ajout du client.</p>";
        }
    }
}
?>

<main>
    <h2>Ajouter un Nouveau Client</h2>
    <form action="ajouterclient.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>
        
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>
        
        <label for="tel">Téléphone :</label>
        <input type="tel" id="tel" name="tel" required>
        
        <label for="cnib">CNIB :</label>
        <input type="text" id="cnib" name="cnib" required>
        
        <button type="submit">Ajouter</button>
    </form>

    <h2>Importer des Clients depuis Excel</h2>
    <form action="ajouterclient.php" method="post" enctype="multipart/form-data">
        <label for="file">Choisir un fichier Excel :</label>
        <input type="file" id="file" name="file" accept=".xlsx, .xls" required>
        <button type="submit">Importer</button>
    </form>
</main>
<?php echo "<link rel='stylesheet' href='ajouterclient.css'> <!-- Ajout du CSS spécifique -->"?>
<?php include('footer.php'); ?>
