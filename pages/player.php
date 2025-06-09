<?php
require_once '../core/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Spieler-ID prüfen
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Ungültige Spieler-ID.");
}
$playerId = intval($_GET['id']);

// Spieler laden
$stmt = $conn->prepare("
    SELECT name, total_kills, total_deaths, score,
           favorite_weapon, favorite_map, longest_killstreak,
           last_seen, first_seen
    FROM players
    WHERE id = ?
");
$stmt->bind_param("i", $playerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Spieler nicht gefunden.");
}
$player = $result->fetch_assoc();
$kd_ratio = $player['total_deaths'] > 0 ? round($player['total_kills'] / $player['total_deaths'], 2) : $player['total_kills'];
?>

<?php include '../templates/header.php'; ?>

<div class="container mt-5">
    <h1>Profil: <?= htmlspecialchars($player['name']) ?></h1>

    <div class="row mt-4">
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item"><strong>Kills:</strong> <?= $player['total_kills'] ?></li>
                <li class="list-group-item"><strong>Deaths:</strong> <?= $player['total_deaths'] ?></li>
                <li class="list-group-item"><strong>K/D-Ratio:</strong> <?= $kd_ratio ?></li>
                <li class="list-group-item"><strong>Score:</strong> <?= $player['score'] ?></li>
                <li class="list-group-item"><strong>Killstreak (max):</strong> <?= $player['longest_killstreak'] ?></li>
                <li class="list-group-item"><strong>Lieblingswaffe:</strong> <?= htmlspecialchars($player['favorite_weapon']) ?: '–' ?></li>
                <li class="list-group-item"><strong>Lieblingsmap:</strong> <?= htmlspecialchars($player['favorite_map']) ?: '–' ?></li>
                <li class="list-group-item"><strong>Zuletzt aktiv:</strong> <?= $player['last_seen'] ?></li>
                <li class="list-group-item"><strong>Erstes Spiel:</strong> <?= $player['first_seen'] ?></li>
            </ul>
        </div>
    </div>

    <!-- Optional: Platz für Charts (Kills über Zeit) -->
    <!--
    <div class="mt-5">
        <h4>Killverlauf</h4>
        <canvas id="killChart" width="400" height="150"></canvas>
    </div>
    -->

</div>

<?php include '../templates/footer.php'; ?>
