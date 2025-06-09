
<?php

require_once __DIR__ . '/Database.php';

class LogParser
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function parseLogFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("Logdatei nicht gefunden: " . $filePath);
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $parsedLines = 0;

        foreach ($lines as $line) {
            $parsedLines++;

            // Beispielzeile: 0:23 Kill: 5 2 3: PlayerA killed PlayerB by MOD_MP40
            if (preg_match('/Kill: \d+ \d+ \d+: (.+) killed (.+) by (.+)/', $line, $matches)) {
                $killerName = $this->sanitizeName($matches[1]);
                $victimName = $this->sanitizeName($matches[2]);
                $weapon     = $matches[3];

                $killerId = $this->findOrCreatePlayer($killerName);
                $victimId = $this->findOrCreatePlayer($victimName);

                $this->storeKill($killerId, $victimId, $weapon);
                $this->updateWeaponStats($weapon);
            }

            // TODO: Weitere Events (Join, MapStart, MapEnd) erkennen
        }

        return $parsedLines;
    }

    private function sanitizeName($name)
    {
        return trim(preg_replace('/\^[0-9]/', '', $name)); // Farbcode entfernen
    }

    private function findOrCreatePlayer($name)
    {
        $stmt = $this->conn->prepare("SELECT id FROM players WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->bind_result($id);
        if ($stmt->fetch()) {
            return $id;
        }
        $stmt->close();

        // Neu anlegen
        $stmt = $this->conn->prepare("INSERT INTO players (name, first_seen, last_seen) VALUES (?, NOW(), NOW())");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    private function storeKill($killerId, $victimId, $weapon)
    {
        $map = 'unknown'; // Später dynamisch setzen
        $time = date('Y-m-d H:i:s');

        $stmt = $this->conn->prepare("
            INSERT INTO kills (killer_id, victim_id, weapon, map, kill_time)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisss", $killerId, $victimId, $weapon, $map, $time);
        $stmt->execute();

        // Spielerstatistiken aktualisieren
        $this->conn->query("UPDATE players SET total_kills = total_kills + 1, last_seen = NOW() WHERE id = $killerId");
        $this->conn->query("UPDATE players SET total_deaths = total_deaths + 1, last_seen = NOW() WHERE id = $victimId");
    }

    private function updateWeaponStats($weapon)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO weapons (name, total_kills) VALUES (?, 1)
            ON DUPLICATE KEY UPDATE total_kills = total_kills + 1
        ");
        $stmt->bind_param("s", $weapon);
        $stmt->execute();
    }
}
