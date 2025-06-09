
<?php
require_once '../core/Database.php';
require_once '../core/LogParser.php';
require_once '../templates/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logfile'])) {
    $uploadDir = __DIR__ . '/';
    $fileName = basename($_FILES['logfile']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['logfile']['tmp_name'], $targetPath)) {
        try {
            $parser = new LogParser();
            $lines = $parser->parseLogFile($targetPath);
            $message = "<div class='alert alert-success'>Datei erfolgreich verarbeitet ({$lines} Zeilen).</div>";
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Fehler beim Parsen: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Fehler beim Hochladen der Datei.</div>";
    }
}
?>

<div class="container mt-5">
    <h1>Logdatei hochladen</h1>
    <?= $message ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="logfile" class="form-label">ET Log-Datei (.log)</label>
            <input type="file" class="form-control" name="logfile" id="logfile" accept=".log" required>
        </div>
        <button type="submit" class="btn btn-primary">Hochladen & Verarbeiten</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
