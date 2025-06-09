
<?php

$step = 1;
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = $_POST['db_host'];
    $dbName = $_POST['db_name'];
    $dbUser = $_POST['db_user'];
    $dbPass = $_POST['db_pass'];
    $adminUser = $_POST['admin_user'];
    $adminPass = $_POST['admin_pass'];

    // Verbindungsversuch
    $mysqli = new mysqli($dbHost, $dbUser, $dbPass);
    if ($mysqli->connect_error) {
        $error = "Fehler bei der Verbindung zur Datenbank: " . $mysqli->connect_error;
    } else {
        // Datenbank erstellen (falls nicht vorhanden)
        $mysqli->query("CREATE DATABASE IF NOT EXISTS `$dbName`");
        $mysqli->select_db($dbName);

        // SQL-Datei ausführen
        $sqlFile = __DIR__ . '/sql/install.sql';
        $sql = file_get_contents($sqlFile);
        if (!$mysqli->multi_query($sql)) {
            $error = "Fehler beim Ausführen von install.sql: " . $mysqli->error;
        } else {
            // Warten bis alles durch ist
            while ($mysqli->more_results() && $mysqli->next_result()) {}

            // config.php schreiben
            $configContent = "<?php
define('DB_HOST', '" . addslashes($dbHost) . "');
define('DB_NAME', '" . addslashes($dbName) . "');
define('DB_USER', '" . addslashes($dbUser) . "');
define('DB_PASS', '" . addslashes($dbPass) . "');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>";
            file_put_contents(__DIR__ . '/../config/config.php', $configContent);

            // Admin einfügen
            $hash = password_hash($adminPass, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO admins (username, password_hash, role) VALUES (?, ?, 'admin')");
            $stmt->bind_param("ss", $adminUser, $hash);
            $stmt->execute();

            $success = true;
            $step = 2;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>ETStats Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

<?php if ($step === 1): ?>
    <h2>ETStats Web – Setup</h2>
    <p>Bitte gib die Datenbank-Zugangsdaten ein:</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <h4>MySQL-Datenbank</h4>
        <div class="mb-3"><label>Host</label><input type="text" name="db_host" class="form-control" required></div>
        <div class="mb-3"><label>Datenbankname</label><input type="text" name="db_name" class="form-control" required></div>
        <div class="mb-3"><label>Benutzer</label><input type="text" name="db_user" class="form-control" required></div>
        <div class="mb-3"><label>Passwort</label><input type="password" name="db_pass" class="form-control"></div>

        <h4>Admin-Zugang</h4>
        <div class="mb-3"><label>Benutzername</label><input type="text" name="admin_user" class="form-control" required></div>
        <div class="mb-3"><label>Passwort</label><input type="password" name="admin_pass" class="form-control" required></div>

        <button type="submit" class="btn btn-primary">Setup starten</button>
    </form>
<?php else: ?>
    <div class="alert alert-success">
        Installation erfolgreich abgeschlossen!<br>
        <a href="../pages/index.php" class="btn btn-success mt-3">Zur Startseite</a>
    </div>
<?php endif; ?>

</div>
</body>
</html>
