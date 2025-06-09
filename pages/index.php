<?php
require_once '../core/Database.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Spieler abfragen: Top 10 nach Kills
$sql = "
    SELECT id, name, total_kills, total_deaths,
           ROUND(total_kills / GREATEST(total_deaths, 1), 2) AS kd_ratio
    FROM players
    ORDER BY total_kills DESC
    LIMIT 10
";
$result = $conn->query($sql);
?>

<?php include '../templates/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Top 10 Spieler</h1>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Spieler</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th>K/D Ratio</th>
                <th>Profil</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$rank}</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>{$row['total_kills']}</td>";
                echo "<td>{$row['total_deaths']}</td>";
                echo "<td>{$row['kd_ratio']}</td>";
                echo "<td><a href='player.php?id={$row['id']}' class='btn btn-sm btn-primary'>Ansehen</a></td>";
                echo "</tr>";
                $rank++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../templates/footer.php'; ?>
