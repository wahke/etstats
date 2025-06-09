
<?php
require_once '../core/Database.php';
require_once '../core/ServerQuery.php';
require_once '../core/Helpers.php';

$server_ip = getSetting('server_ip');
$server_port = intval(getSetting('server_port'));

$status = ServerQuery::getETStatus($server_ip, $server_port);
?>

<?php include '../templates/header.php'; ?>

<div class="container mt-5">
    <h1>Live Serverstatus</h1>

    <?php if (!$status): ?>
        <div class="alert alert-danger">Der Server ist offline oder nicht erreichbar.</div>
    <?php else: ?>
        <div class="card p-4 mb-4">
            <h4><?= htmlspecialchars($status['hostname']) ?></h4>
            <p><strong>IP:</strong> <?= $server_ip ?> : <?= $server_port ?></p>
            <p><strong>Map:</strong> <?= $status['mapname'] ?></p>
            <p><strong>Mod:</strong> <?= $status['mod'] ?></p>
            <p><strong>Spieler:</strong> <?= count($status['players']) ?> / <?= $status['maxplayers'] ?></p>
        </div>

        <?php if (count($status['players']) > 0): ?>
            <h5>Aktive Spieler</h5>
            <table class="table table-sm table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Score</th>
                        <th>Ping</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($status['players'] as $i => $player): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($player['name']) ?></td>
                            <td><?= $player['score'] ?></td>
                            <td><?= $player['ping'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include '../templates/footer.php'; ?>
