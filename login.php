<?php include('logging.php'); ?>
<?php
session_start();
include('db.php'); // Inclusion de votre fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validation des informations d'identification
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
			logMessage("Connexion: $username s'est connecté.");
            exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <h1>Connexion</h1>
    </header>
    <div class="container">
        <form action="login.php" method="post">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Se connecter</button>
        </form>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    </div>
    <footer class="site-footer">
        <p>&copy; 2024 Votre Nom ou Entreprise. Tous droits réservés.</p>
    </footer>
</body>
</html>
