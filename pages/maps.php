<?php
require_once '../core/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Alle Maps abrufen
$sql = "
    SELECT name, times_played, total_kills, total_deaths
    FROM maps
    ORDER BY times_played DESC
";
$result = $conn->query($sql);
?>

<?php include '../templates/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Map-Statistiken</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Map</th>
                <th>Spiele</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th>K/D-Verhältnis</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()):
                $kd = $row['total_deaths'] > 0 ? round($row['total_kills'] / $row['total_deaths'], 2) : $row['total_kills'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['times_played'] ?></td>
                    <td><?= $row['total_kills'] ?></td>
                    <td><?= $row['total_deaths'] ?></td>
                    <td><?= $kd ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../templates/footer.php'; ?>
