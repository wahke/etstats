
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($player['name']) ?></h5>
        <p class="card-text">
            <strong>Kills:</strong> <?= $player['total_kills'] ?> |
            <strong>Deaths:</strong> <?= $player['total_deaths'] ?> |
            <strong>K/D:</strong> <?= $player['kd_ratio'] ?>
        </p>
        <a href="player.php?id=<?= $player['id'] ?>" class="btn btn-sm btn-primary">Profil ansehen</a>
    </div>
</div>
