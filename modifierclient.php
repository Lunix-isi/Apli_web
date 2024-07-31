<?php
session_start();
include('db.php'); // Inclusion de votre fichier de connexion à la base de données

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Modifier Client";
include('header.php');

// Variables pour le client
$numeroRD = "";
$client = null;
$message = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['rechercher'])) {
        $numeroRD = $_POST['numeroRD'];
        
        // Rechercher le client par son numéro RD
        $query = "SELECT * FROM client WHERE numeroRD = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $numeroRD);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();
        $stmt->close();
        
        if (!$client) {
            $message = "<p>Client non trouvé.</p>";
        }
    } elseif (isset($_POST['modifier'])) {
        // Modifier les informations du client
        $numeroRD = $_POST['numeroRD'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $tel = $_POST['tel'];
        $cnib = $_POST['cnib'];
        
        $query = "UPDATE client SET nom = ?, prenom = ?, tel = ?, cnib = ? WHERE numeroRD = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $nom, $prenom, $tel, $cnib, $numeroRD);
        if ($stmt->execute()) {
            $message = "<p>Informations du client mises à jour avec succès.</p>";
           
        } else {
            $message = "<p>Erreur lors de la mise à jour des informations.</p>";
        }
        $stmt->close();
    }
}
?>

<main>
    <h2>Modifier un Client</h2>
    
    <!-- Formulaire de recherche du client -->
    <form action="modifierclient.php" method="post">
        <label for="numeroRD">Numéro RD :</label>
        <input type="text" id="numeroRD" name="numeroRD" value="<?php echo htmlspecialchars($numeroRD); ?>" required>
        <button type="submit" name="rechercher">Rechercher</button>
    </form>

    <?php if ($client): ?>
        <!-- Formulaire de modification du client -->
        <h3>Informations du Client</h3>
        <form action="modifierclient.php" method="post">
            <input type="hidden" name="numeroRD" value="<?php echo htmlspecialchars($client['numeroRD']); ?>">
            
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($client['nom']); ?>" required>
            
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($client['prenom']); ?>" required>
            
            <label for="tel">Téléphone :</label>
            <input type="text" id="tel" name="tel" value="<?php echo htmlspecialchars($client['tel']); ?>" required>
            
            <label for="cnib">CNIB :</label>
            <input type="text" id="cnib" name="cnib" value="<?php echo htmlspecialchars($client['cnib']); ?>" required>
            
            <button type="submit" name="modifier">Modifier</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
</main>

<?php echo "<link rel='stylesheet' href='modifierclient.css'> <!-- Ajout du CSS spécifique -->"?>
<?php include('footer.php'); ?>
