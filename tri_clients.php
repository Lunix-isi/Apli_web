<?php
// Inclure le fichier de configuration de la base de données
include('db.php');

// Vérifier si un critère de tri a été soumis
if (isset($_GET['tri'])) {
    $tri = $_GET['tri'];

    // Définir la requête SQL avec le critère de tri
    $query = "SELECT * FROM client ORDER BY $tri";

    // Exécuter la requête
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>Numéro RD</th><th>Nom</th><th>Prénom</th><th>État de Paiement</th><th>Consommation</th></tr>';
        
        // Afficher les résultats
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['numeroRD']) . '</td>';
            echo '<td>' . htmlspecialchars($row['nom']) . '</td>';
            echo '<td>' . htmlspecialchars($row['prenom']) . '</td>';
            echo '<td>' . htmlspecialchars($row['etat'] == 1 ? 'Payé' : 'Impayé') . '</td>';
            echo '<td>' . htmlspecialchars($row['consom3']) . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
    } else {
        echo 'Aucun client trouvé.';
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
