<?php include('header.php'); ?>
<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptabilité Générale</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <section class="accounting">
            <h2>Comptabilité Générale</h2>
            <?php
            // Requête pour obtenir le nombre total de paiements effectués
            $query = "SELECT COUNT(*) AS total_paiements FROM paiement";
            $result = $conn->query($query);
            $total_paiements = $result->fetch_assoc()['total_paiements'];

            // Requête pour obtenir le nombre total de paiements impayés
            $query = "SELECT COUNT(*) AS total_impayes FROM consonsommation WHERE etat = 0";
            $result = $conn->query($query);
            $total_impayes = $result->fetch_assoc()['total_impayes'];

            // Requête pour obtenir le montant total encaissé
            $query = "SELECT SUM(montant) AS total_encaisse FROM paiement";
            $result = $conn->query($query);
            $total_encaisse = $result->fetch_assoc()['total_encaisse'];

            // Requête pour obtenir le montant total encaissé aujourd'hui
            $query = "SELECT SUM(montant) AS total_aujourdhui FROM paiement WHERE DATE(date_paiement) = CURDATE()";
            $result = $conn->query($query);
            $total_aujourdhui = $result->fetch_assoc()['total_aujourdhui'];

            // Requête pour obtenir le montant total encaissé cette semaine
            $query = "SELECT SUM(montant) AS total_semaine FROM paiement WHERE WEEK(date_paiement) = WEEK(CURDATE())";
            $result = $conn->query($query);
            $total_semaine = $result->fetch_assoc()['total_semaine'];

            // Requête pour obtenir le montant total encaissé ce mois
            $query = "SELECT SUM(montant) AS total_mois FROM paiement WHERE MONTH(date_paiement) = MONTH(CURDATE()) AND YEAR(date_paiement) = YEAR(CURDATE())";
            $result = $conn->query($query);
            $total_mois = $result->fetch_assoc()['total_mois'];
            ?>

            <div class="stat">
                <h3>Total des Paiements Effectués</h3>
                <p><?php echo $total_paiements; ?></p>
            </div>
            <div class="stat">
                <h3>Total des Impayés</h3>
                <p><?php echo $total_impayes; ?></p>
            </div>
            <div class="stat">
                <h3>Total Encaissé</h3>
                <p><?php echo number_format($total_encaisse, 2, ',', ' '); ?> FCFA</p>
            </div>
            <div class="stat">
                <h3>Paiements d'Aujourd'hui</h3>
                <p><?php echo number_format($total_aujourdhui, 2, ',', ' '); ?> FCFA</p>
            </div>
            <div class="stat">
                <h3>Paiements de la Semaine</h3>
                <p><?php echo number_format($total_semaine, 2, ',', ' '); ?> FCFA</p>
            </div>
            <div class="stat">
                <h3>Paiements du Mois</h3>
                <p><?php echo number_format($total_mois, 2, ',', ' '); ?> FCFA</p>
            </div>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
