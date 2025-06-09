
<?php

require_once __DIR__ . '/Database.php';

function getSetting($name)
{
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT value FROM settings WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($value);
    if ($stmt->fetch()) {
        return $value;
    }
    return null;
}

function setSetting($name, $value)
{
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("REPLACE INTO settings (name, value) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $value);
    return $stmt->execute();
}
