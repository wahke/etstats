-- ========================
-- ETStats Web - MySQL Schema
-- ========================

-- Spieler
CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    guid VARCHAR(64) DEFAULT NULL,
    ip VARCHAR(45) DEFAULT NULL,
    last_seen DATETIME DEFAULT NULL,
    first_seen DATETIME DEFAULT NULL,
    total_kills INT DEFAULT 0,
    total_deaths INT DEFAULT 0,
    score INT DEFAULT 0,
    longest_killstreak INT DEFAULT 0,
    favorite_weapon VARCHAR(64) DEFAULT NULL,
    favorite_map VARCHAR(64) DEFAULT NULL
);

-- Kills
CREATE TABLE kills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    killer_id INT,
    victim_id INT,
    weapon VARCHAR(64),
    map VARCHAR(64),
    kill_time DATETIME,
    FOREIGN KEY (killer_id) REFERENCES players(id) ON DELETE SET NULL,
    FOREIGN KEY (victim_id) REFERENCES players(id) ON DELETE SET NULL
);

-- Waffen
CREATE TABLE weapons (
    name VARCHAR(64) PRIMARY KEY,
    total_kills INT DEFAULT 0,
    total_hits INT DEFAULT 0,
    total_shots INT DEFAULT 0
);

-- Maps
CREATE TABLE maps (
    name VARCHAR(64) PRIMARY KEY,
    times_played INT DEFAULT 0,
    total_kills INT DEFAULT 0,
    total_deaths INT DEFAULT 0
);

-- Spielsessions
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    start_time DATETIME,
    end_time DATETIME,
    map VARCHAR(64),
    `mod` VARCHAR(64),
    log_file_name VARCHAR(128)
);

-- Spieler in Sessions
CREATE TABLE session_players (
    session_id INT,
    player_id INT,
    team VARCHAR(16),
    kills INT DEFAULT 0,
    deaths INT DEFAULT 0,
    score INT DEFAULT 0,
    PRIMARY KEY (session_id, player_id),
    FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

-- Serverstatus
CREATE TABLE server_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hostname VARCHAR(128),
    ip VARCHAR(45),
    port INT,
    `mod` VARCHAR(64),
    map VARCHAR(64),
    player_count INT,
    max_players INT,
    time_checked DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Übersetzungen
CREATE TABLE translations (
    key_name VARCHAR(64) PRIMARY KEY,
    en TEXT,
    de TEXT,
    fr TEXT
);

-- Admins
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'editor',
    last_login DATETIME
);

-- Parser Logs
CREATE TABLE parse_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_file VARCHAR(128),
    parse_time DATETIME,
    lines_parsed INT,
    success BOOLEAN,
    error_message TEXT
);

-- Einstellungen
CREATE TABLE settings (
    name VARCHAR(64) PRIMARY KEY,
    value TEXT NOT NULL
);
