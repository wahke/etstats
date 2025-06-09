<?php
require_once '../core/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Alle Waffen abrufen
$sql = "
    SELECT name, total_kills, total_hits, total_shots,
           ROUND(total_hits / GREATEST(total_shots, 1) * 100, 2) AS accuracy
    FROM weapons
    ORDER BY total_kills DESC
";
$result = $conn->query($sql);

// Bildfunktion ohne file_exists, stattdessen mit JS-Fallback
function weaponImage($modName) {
    $filename = $modName; // z. B. MOD_MP40
    $path = "/assets/img/weapons/{$filename}.gif";
    return "<img src=\"{$path}\" alt=\"{$modName}\" width=\"auto\" height=\"32\" onerror=\"this.onerror=null;this.replaceWith(document.createTextNode('{$modName}'))\">";
}
?>

<?php include '../templates/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Waffenstatistiken</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Waffe</th>
                <th>Kills</th>
                <th>Treffer</th>
                <th>Schüsse</th>
                <th>Trefferquote (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= weaponImage($row['name']) ?></td>
                    <td><?= $row['total_kills'] ?></td>
                    <td><?= $row['total_hits'] ?></td>
                    <td><?= $row['total_shots'] ?></td>
                    <td><?= $row['accuracy'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../templates/footer.php'; ?>