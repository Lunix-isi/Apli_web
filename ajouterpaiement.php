<?php
include('logging.php');
session_start();
include('db.php'); // Inclusion de votre fichier de connexion à la base de données

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Paiement</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <section class="payment">
            <h2>Ajouter un Paiement</h2>
            <?php
            $numeroRD = isset($_POST['numeroRD']) ? $_POST['numeroRD'] : '';
            $msg = '';

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['paiement'])) {
                $numeroRD = $_POST['numeroRD'];
                $moisAnnee = explode("-", $_POST['consommation']);
                $mois = $moisAnnee[0];
                $annee = $moisAnnee[1];
                $typedepaiement = $_POST['typedepaiement'];

                // Requête pour obtenir la consommation
                $query = "SELECT consom3 FROM consonsommation WHERE numeroRD = ? AND mois = ? AND annee = ? AND etat = 0";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iss", $numeroRD, $mois, $annee);
                $stmt->execute();
                $stmt->bind_result($consom3);
                $stmt->fetch();
                $stmt->close();

                if ($consom3) {
                    $montant = $consom3 * 500 + 500;

                    // Requête pour insérer le paiement
                    $query = "INSERT INTO paiement (numeroRD, mois, annee, consom3, montant, typedepaiement) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("isiiis", $numeroRD, $mois, $annee, $consom3, $montant, $typedepaiement);
                    if ($stmt->execute()) {
                        // Mettre à jour l'état de la consommation
                        $query = "UPDATE consonsommation SET etat = 1 WHERE numeroRD = ? AND mois = ? AND annee = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("iss", $numeroRD, $mois, $annee);
                        $stmt->execute();
                        $stmt->close();
                        $msg = "Paiement enregistré avec succès.";
                    } else {
                        $msg = "Erreur lors de l'enregistrement du paiement.";
                    }
                } else {
                    $msg = "Aucune consommation impayée trouvée pour le mois et l'année spécifiés.";
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer'])) {
                $paiementId = $_POST['paiement_id'];

                // Requête pour obtenir les détails du paiement
                $query = "SELECT numeroRD, mois, annee FROM paiement WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $paiementId);
                $stmt->execute();
                $stmt->bind_result($numeroRD, $mois, $annee);
                $stmt->fetch();
                $stmt->close();

                // Supprimer le paiement
                $query = "DELETE FROM paiement WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $paiementId);
                if ($stmt->execute()) {
                    // Mettre à jour l'état de la consommation
                    $query = "UPDATE consonsommation SET etat = 0 WHERE numeroRD = ? AND mois = ? AND annee = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("iss", $numeroRD, $mois, $annee);
                    $stmt->execute();
                    $stmt->close();
                    $msg = "Paiement supprimé avec succès.";
                } else {
                    $msg = "Erreur lors de la suppression du paiement.";
                }
            }

            ?>

            <form action="ajouterpaiement.php" method="post">
                <label for="numeroRD">Numéro RD:</label>
                <input type="number" id="numeroRD" name="numeroRD" value="<?php echo htmlspecialchars($numeroRD); ?>" required>
                <button type="submit">Rechercher</button>
            </form>

            <?php if ($numeroRD): ?>
                <?php
                // Requête pour obtenir les consommations impayées
                $query = "SELECT mois, annee, consom3 FROM consonsommation WHERE numeroRD = ? AND etat = 0";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $numeroRD);
                $stmt->execute();
                $result = $stmt->get_result();
                $consommations = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();

                // Requête pour obtenir les paiements effectués
                $query = "SELECT id, mois, annee, consom3, montant, typedepaiement FROM paiement WHERE numeroRD = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $numeroRD);
                $stmt->execute();
                $result = $stmt->get_result();
                $paiements = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                ?>

                <?php if (!empty($consommations)): ?>
                    <form action="ajouterpaiement.php" method="post">
                        <input type="hidden" name="numeroRD" value="<?php echo htmlspecialchars($numeroRD); ?>">

                        <label for="consommation">Consommation :</label>
                        <select id="consommation" name="consommation" required>
                            <?php foreach ($consommations as $conso): ?>
                                <option value="<?php echo htmlspecialchars($conso['mois'] . "-" . $conso['annee']); ?>">
                                    Mois: <?php echo htmlspecialchars($conso['mois']); ?>, Année: <?php echo htmlspecialchars($conso['annee']); ?> - Consommation: <?php echo htmlspecialchars($conso['consom3']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($consommations)): ?>
                            <h4>Montant à Payer : <?php echo htmlspecialchars($consommations[0]['consom3'] * 500 + 500); ?> FCFA</h4>
                        <?php endif; ?>
                        <label for="typedepaiement">Type de Paiement :</label>
                        <select id="typedepaiement" name="typedepaiement" required>
                            <option value="espèces">Espèces</option>
                            <option value="transfert">Transfert</option>
                        </select>

                        <button type="submit" name="paiement">Enregistrer Paiement</button>
                    </form>
                <?php else: ?>
                    <p>Aucune consommation impayée trouvée pour ce numéro RD.</p>
                <?php endif; ?>

                <?php if (!empty($paiements)): ?>
                    <h3>Paiements Effectués</h3>
                    <table>
                        <tr>
                            <th>Mois</th>
                            <th>Année</th>
                            <th>Consommation (m³)</th>
                            <th>Montant</th>
                            <th>Type de Paiement</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($paiements as $paiement): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($paiement['mois']); ?></td>
                                <td><?php echo htmlspecialchars($paiement['annee']); ?></td>
                                <td><?php echo htmlspecialchars($paiement['consom3']); ?></td>
                                <td><?php echo htmlspecialchars($paiement['montant']); ?> FCFA</td>
                                <td><?php echo htmlspecialchars($paiement['typedepaiement']); ?></td>
                                <td>
                                    <form action="ajouterpaiement.php" method="post" style="display:inline;">
                                        <input type="hidden" name="paiement_id" value="<?php echo $paiement['id']; ?>">
                                        <button type="submit" name="supprimer">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>Aucun paiement effectué pour ce numéro RD.</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($msg): ?>
                <p><?php echo $msg; ?></p>
            <?php endif; ?>
        </section>
    </main>
	<?php echo "<link rel='stylesheet' href='ajouterpaiement.css'>" ?>
    <?php include('footer.php'); ?>
</body>
</html>
