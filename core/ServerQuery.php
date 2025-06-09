
<?php

class ServerQuery
{
    public static function getETStatus($ip, $port, $timeout = 2)
    {
        $socket = fsockopen("udp://$ip", $port, $errno, $errstr, $timeout);
        if (!$socket) return false;

        stream_set_timeout($socket, $timeout);
        fwrite($socket, "\xFF\xFF\xFF\xFFgetstatus\n");

        $data = fread($socket, 8192);
        fclose($socket);

        if (strpos($data, 'statusResponse') === false) return false;

        $lines = explode("\n", $data);
        $info = self::parseInfoLine($lines[1]);
        $players = [];

        for ($i = 2; $i < count($lines); $i++) {
            if (trim($lines[$i]) === '') continue;
            if (preg_match('/(\d+) (\d+) "(.*)"/', $lines[$i], $matches)) {
                $players[] = [
                    'score' => $matches[1] ?? 0,
                    'ping'  => $matches[2] ?? 0,
                    'name'  => $matches[3] ?? 'n/a',
                ];
            }
        }

        return [
            'hostname' => $info['sv_hostname'] ?? 'Unbekannt',
            'mapname'  => $info['mapname'] ?? 'Unbekannt',
            'mod'      => $info['gamename'] ?? 'ETMain',
            'maxplayers' => $info['sv_maxclients'] ?? 0,
            'players'  => $players
        ];
    }

    private static function parseInfoLine($line)
    {
        $parts = explode("\\", trim($line));
        $info = [];
        for ($i = 1; $i < count($parts); $i += 2) {
            $info[$parts[$i - 1]] = $parts[$i];
        }
        return $info;
    }
}
