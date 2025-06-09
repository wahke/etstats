<?php
// === Globale Konfiguration für ETStats Web ===

define('DB_HOST', 'localhost');
define('DB_NAME', 'etstats');
define('DB_USER', 'root');
define('DB_PASS', ''); // Bei Bedarf dein Passwort eintragen

// Fehleranzeige (für Entwicklung aktivieren, in Produktion deaktivieren)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
