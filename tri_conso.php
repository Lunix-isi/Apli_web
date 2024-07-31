<?php include('header.php'); ?>
<?php include('db.php'); ?>

<h2>Consommations - Tri par Critères</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
    <label for="tri">Trier par :</label>
    <select id="tri" name="tri">
        <option value="numeroRD" <?php if(isset($_GET['tri']) && $_GET['tri'] == 'numeroRD') echo 'selected'; ?>>Numéro RD</option>
        <option value="mois" <?php if(isset($_GET['tri']) && $_GET['tri'] == 'mois') echo 'selected'; ?>>Mois</option>
        <option value="annee" <?php if(isset($_GET['tri']) && $_GET['tri'] == 'annee') echo 'selected'; ?>>Année</option>
        <option value="etat" <?php if(isset($_GET['tri']) && $_GET['tri'] == 'etat') echo 'selected'; ?>>État (payé/non payé)</option>
    </select>
    <button type="submit">Trier</button>
</form>

<?php
// Pagination configuration
$results_per_page = 10; // Number of results per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Sorting criteria
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'numeroRD';
$valid_columns = ['numeroRD', 'mois', 'annee', 'etat'];
if (!in_array($tri, $valid_columns)) {
    $tri = 'numeroRD';
}

// Fetch total number of results
$total_query = "SELECT COUNT(*) AS total FROM consonsommation";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_results = $total_row['total'];
$total_pages = ceil($total_results / $results_per_page);

// Fetch sorted and paginated results
$query = "SELECT numeroRD, mois, annee, consom3, etat FROM consonsommation ORDER BY $tri LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $start_from, $results_per_page);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Display the sorted and paginated results
    echo "<table border='1' width='100%'>";
    echo "<tr><th>Numéro RD</th><th>Mois</th><th>Année</th><th>Consommation (m³)</th><th>État</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $etat = $row['etat'] == 1 ? 'Payé' : 'Non payé';
        echo "<tr><td>{$row['numeroRD']}</td><td>{$row['mois']}</td><td>{$row['annee']}</td><td>{$row['consom3']}</td><td>$etat</td></tr>";
    }
    echo "</table>";

    // Display pagination
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='tri_conso.php?page=$i&tri=$tri'";
        if ($i == $page) echo " class='active'";
        echo ">$i</a> ";
    }
    echo "</div>";
} else {
    echo "<p>Aucune consommation trouvée.</p>";
}
?>

<style>
.pagination {
    display: flex;
    justify-content: center;
    margin: 20px 0;
    flex-wrap: wrap;
}

.pagination a {
    margin: 0 5px;
    padding: 10px 15px;
    text-decoration: none;
    border: 1px solid #ddd;
    transition: background-color 0.3s, color 0.3s;
    flex-grow: 1;
    text-align: center;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {
    background-color: #ddd;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 8px 12px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}
</style>

<?php include('footer.php'); ?>
