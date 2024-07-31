<?php
session_start();
include('db.php'); // Inclusion de votre fichier de connexion à la base de données

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}?>
<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Clients</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <section class="welcome">
            <h1>Bienvenue sur le système de gestion des clients</h1>
            <p>Utilisez les options ci-dessous pour gérer les clients, les consommations et les paiements.</p>
        </section>
        <section class="actions">
            <div class="action-item">
                <a href="ajouterclient.php" class="action-link">Ajouter un Client</a>
            </div>
            <div class="action-item">
                <a href="ajouterconso.php" class="action-link">Ajouter une Consommation</a>
            </div>
            <div class="action-item">
                <a href="ajouterpaiement.php" class="action-link">Ajouter un Paiement</a>
            </div>
            <div class="action-item">
                <a href="modifierclient.php" class="action-link">Modifier un Client</a>
            </div>
            <div class="action-item">
                <a href="tri_conso.php" class="action-link">Trier les Consommations</a>
            </div>
            <div class="action-item">
                <a href="logs/" class="action-link">Voir les Logs</a>
            </div>
			<div class="action-item">
                <a href="stat.php" class="action-link"> Statistique</a>
            </div>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
