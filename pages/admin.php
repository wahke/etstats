<?php
session_start();
require_once '../core/Database.php';
require_once '../core/Helpers.php';

// Zugriffsschutz: Wenn kein Login vorhanden, weiterleiten
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$db = Database::getInstance()->getConnection();
$success = false;

// Beim Absenden speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip   = trim($_POST['server_ip']);
    $port = intval($_POST['server_port']);

    $stmt = $db->prepare("
        REPLACE INTO settings (name, value) VALUES 
        ('server_ip', ?),
        ('server_port', ?)
    ");
    $stmt->bind_param("ss", $ip, $port);
    $success = $stmt->execute();
}

// Einstellungen laden
function setting($key) {
    return htmlspecialchars(getSetting($key) ?? '');
}
?>

<?php include '../templates/header.php'; ?>

<div class="container mt-5">
    <h1>Adminbereich – Servereinstellungen</h1>

    <p><a href="logout.php" class="btn btn-sm btn-outline-danger float-end">Logout</a></p>

    <?php if ($success): ?>
        <div class="alert alert-success">Einstellungen gespeichert.</div>
    <?php endif; ?>

    <form method="post" class="mt-4">
        <div class="mb-3">
            <label for="server_ip" class="form-label">Server-IP</label>
            <input type="text" class="form-control" name="server_ip" id="server_ip" value="<?= setting('server_ip') ?>" required>
        </div>
        <div class="mb-3">
            <label for="server_port" class="form-label">Server-Port</label>
            <input type="number" class="form-control" name="server_port" id="server_port" value="<?= setting('server_port') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
