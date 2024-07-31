<?php include('logging.php'); ?>

<?php
session_start();
session_destroy();
logMessage("Connexion: $username s'est deconnecté.");
header("Location: login.php");
exit();
?>
