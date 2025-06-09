
<?php
// Sprachdatei laden (Standard: de)
$lang = include __DIR__ . '/../lang/de.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(\$lang['site_title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/pages/index.php"><?= htmlspecialchars(\$lang['site_title']) ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/pages/index.php"><?= \$lang['nav_home'] ?></a></li>
                <li class="nav-item"><a class="nav-link" href="/pages/weapons.php"><?= \$lang['nav_weapons'] ?></a></li>
                <li class="nav-item"><a class="nav-link" href="/pages/maps.php"><?= \$lang['nav_maps'] ?></a></li>
                <li class="nav-item"><a class="nav-link" href="/pages/live.php"><?= \$lang['nav_live'] ?></a></li>
                <li class="nav-item"><a class="nav-link" href="/pages/admin.php"><?= \$lang['nav_admin'] ?></a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
