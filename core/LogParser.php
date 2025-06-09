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

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new Exception("Konnte Logdatei nicht öffnen: " . $filePath);
        }

        $parsedLines = 0;
        $currentMap = 'unknown';

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parsedLines++;

            // Map-Erkennung (InitGame-Zeile)
            if (preg_match('/InitGame: .*\\\\mapname\\\\([^\\\\]+)/', $line, $match)) {
                $currentMap = $match[1];
                $this->updateMapStats($currentMap);
            }

            // Kill-Zeile parsen
            if (preg_match('/Kill: \d+ \d+ \d+: (.+) killed (.+) by (.+)/', $line, $matches)) {
                $killerName = $this->sanitizeName($matches[1]);
                $victimName = $this->sanitizeName($matches[2]);
                $weapon     = $matches[3];

                $killerId = $this->findOrCreatePlayer($killerName);
                $victimId = $this->findOrCreatePlayer($victimName);

                $this->storeKill($killerId, $victimId, $weapon, $currentMap);
                $this->updateWeaponStats($weapon);
                $this->updateMapKills($currentMap);
            }
        }

        fclose($handle);
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
            $stmt->close();
            return $id;
        }
        $stmt->close();

        // Neu anlegen
        $stmt = $this->conn->prepare("INSERT INTO players (name, first_seen, last_seen) VALUES (?, NOW(), NOW())");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $insertId = $stmt->insert_id;
        $stmt->close();
        return $insertId;
    }

    private function storeKill($killerId, $victimId, $weapon, $map)
    {
        $time = date('Y-m-d H:i:s');

        $stmt = $this->conn->prepare("
            INSERT INTO kills (killer_id, victim_id, weapon, map, kill_time)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisss", $killerId, $victimId, $weapon, $map, $time);
        $stmt->execute();
        $stmt->close();

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
        $stmt->close();
    }

    private function updateMapStats($mapName)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO maps (name, times_played, total_kills, total_deaths)
            VALUES (?, 1, 0, 0)
            ON DUPLICATE KEY UPDATE times_played = times_played + 1
        ");
        $stmt->bind_param("s", $mapName);
        $stmt->execute();
        $stmt->close();
    }

    private function updateMapKills($mapName)
    {
        $escapedMap = $this->conn->real_escape_string($mapName);
        $this->conn->query("
            UPDATE maps
            SET total_kills = total_kills + 1,
                total_deaths = total_deaths + 1
            WHERE name = '$escapedMap'
        ");
    }
}
