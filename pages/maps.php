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

function getMapImagePath($mapName) {
    $base = '/assets/img/maps/';
    $extensions = ['gif', 'png', 'jpg', 'jpeg'];
    foreach ($extensions as $ext) {
        $path = $_SERVER['DOCUMENT_ROOT'] . "{$base}{$mapName}.{$ext}";
        if (file_exists($path)) {
            return "{$base}{$mapName}.{$ext}";
        }
    }
    return "{$base}UNKNOWNMAP.gif";
}
?>

<?php include '../templates/header.php'; ?>

<style>
.map-hover {
    position: relative;
    display: inline-block;
}
.map-hover .map-preview {
    display: none;
    position: absolute;
    top: 25px;
    left: 0;
    z-index: 10;
    border: 1px solid #ccc;
    background: #fff;
    padding: 5px;
}
.map-hover:hover .map-preview {
    display: block;
}
</style>

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
                $mapName = htmlspecialchars($row['name']);
                $imagePath = getMapImagePath($row['name']);
            ?>
                <tr>
                    <td>
                        <div class="map-hover">
                            <?= $mapName ?>
                            <div class="map-preview">
                                <img src="<?= $imagePath ?>" alt="<?= $mapName ?>" style="max-width:200px;max-height:150px;">
                            </div>
                        </div>
                    </td>
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