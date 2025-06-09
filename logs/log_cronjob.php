<?php
require_once __DIR__ . '/LogParser.php';

$logDir        = __DIR__;
$processedDir  = $logDir . '/processed';
$errorDir      = $logDir . '/errors';

$parser = new LogParser();

// Verzeichnisse sicherstellen
foreach ([$processedDir, $errorDir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

foreach (glob($logDir . '/*.log') as $logFile) {
    $baseName = basename($logFile);
    echo "Verarbeite: $baseName\n";

    try {
        $parsedLines = $parser->parseLogFile($logFile);
        echo "→ $parsedLines Zeilen verarbeitet\n";

        // Nach 'processed' verschieben
        rename($logFile, $processedDir . '/' . $baseName);

    } catch (Exception $e) {
        echo "⚠️ Fehler bei $baseName: " . $e->getMessage() . "\n";

        // Nach 'errors' verschieben
        rename($logFile, $errorDir . '/' . $baseName);

        // Optional: Logdatei mit Fehlermeldung
        file_put_contents(
            $errorDir . '/' . $baseName . '.error.log',
            $e->getMessage()
        );
    }
}
