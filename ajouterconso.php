<?php
session_start();
include('db.php'); // Inclusion de votre fichier de connexion à la base de données

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Ajouter Consommation";
include('header.php');

// Variables pour le client
$nom = "";
$prenom = "";
$numeroRD = "";
$mois = "01"; // Mois par défaut
$annee = "2024"; // Année par défaut
$conso = "";
$etat = 0; // Impayé par défaut
$clientDetailsDisplayed = false; // Indique si les détails du client sont affichés

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['numeroRD']) && !isset($_POST['nextClient'])) {
        $numeroRD = $_POST['numeroRD'];
        
        // Rechercher le client par numéroRD
        $query = "SELECT nom, prenom FROM client WHERE numeroRD = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $numeroRD);
        $stmt->execute();
        $stmt->bind_result($nom, $prenom);
        $stmt->fetch();
        $stmt->close();

        if ($nom && $prenom) {
            $clientDetailsDisplayed = true; // Indique que les détails du client sont affichés
        } else {
            echo "<p>Client non trouvé. Veuillez vérifier le numéro RD.</p>";
        }
    }

    if (isset($_POST['conso']) && $clientDetailsDisplayed) {
        $mois = $_POST['mois'];
        $annee = $_POST['annee'];
        $conso = $_POST['conso'];
        
        // Insérer la consommation dans la base de données
        $query = "INSERT INTO consonsommation (numeroRD, mois, annee, consom3, etat) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isisi", $numeroRD, $mois, $annee, $conso, $etat);
        if ($stmt->execute()) {
            // Passer au client suivant
            $numeroRD++; // Incrémenter le numéro RD pour le client suivant

            // Rechercher le client suivant
            $query = "SELECT nom, prenom FROM client WHERE numeroRD = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $numeroRD);
            $stmt->execute();
            $stmt->bind_result($nom, $prenom);
            $stmt->fetch();
            $stmt->close();

            if ($nom && $prenom) {
                $clientDetailsDisplayed = true; // Indique que les détails du client sont affichés
            } else {
                // Aucun client suivant, réinitialiser les champs
                $clientDetailsDisplayed = false;
                $numeroRD = "";
                $nom = "";
                $prenom = "";
            }
        } else {
            echo "<p>Erreur lors de l'ajout de la consommation.</p>";
        }
    }
}
?>

<main>
    <h2>Ajouter une Consommation</h2>
    
    <!-- Formulaire pour rechercher le client -->
    <form action="ajouterconso.php" method="post">
        <label for="numeroRD">Numéro RD :</label>
        <input type="text" id="numeroRD" name="numeroRD" value="<?php echo htmlspecialchars($numeroRD); ?>" required>
        <button type="submit">Rechercher</button>
    </form>

    <?php if ($clientDetailsDisplayed): ?>
        <!-- Affichage des informations du client et du formulaire de consommation -->
        <h3>Client : <?php echo htmlspecialchars($nom); ?> <?php echo htmlspecialchars($prenom); ?></h3>
        <p>Numéro RD : <?php echo htmlspecialchars($numeroRD); ?></p>
        <form action="ajouterconso.php" method="post">
            <input type="hidden" name="numeroRD" value="<?php echo htmlspecialchars($numeroRD); ?>">
            
            <label for="mois">Mois :</label>
            <input type="text" id="mois" name="mois" value="<?php echo htmlspecialchars($mois); ?>" required>
            
            <label for="annee">Année :</label>
            <input type="text" id="annee" name="annee" value="<?php echo htmlspecialchars($annee); ?>" required>
            
            <label for="conso">Consommation :</label>
            <input type="text" id="conso" name="conso" value="<?php echo htmlspecialchars($conso); ?>" required autofocus>
            
            <!-- Pas d'élément pour l'état, car il est fixé côté serveur -->
            
            <button type="submit" name="conso">Ajouter Consommation</button>
        </form>
    <?php endif; ?>
</main>

<?php include('footer.php'); ?>
<?php echo "<link rel='stylesheet' href='ajouterclient.css'> <!-- Ajout du CSS spécifique -->"?>

<!-- JavaScript pour focus sur le champ de consommation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var consoField = document.getElementById('conso');
        if (consoField) {
            consoField.focus(); // Mettre le focus sur le champ de consommation
        }
    });
</script>
