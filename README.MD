
# ETStats Web

**ETStats Web** ist ein modernes, PHP-basiertes Statistiksystem für Wolfenstein: Enemy Territory.  
Es liest Server-Logdateien aus, erstellt Spielerstatistiken und zeigt diese übersichtlich im Browser an.

---

## ✅ Features

- Spielerprofile (Kills, Deaths, K/D-Ratio, Lieblingswaffe/Map)
- Waffen- und Mapstatistiken
- Live-Serverstatus via UDP-Abfrage
- Admin-Panel mit Log-Upload und Servereinstellungen
- Bootstrap-Frontend (responsive)
- Mehrsprachigkeit (Deutsch/Englisch)
- Installer (setup.php) für einfache Ersteinrichtung

---

## 📦 Ordnerstruktur

```
etstats/
├── config/          # Automatisch generierte config.php
├── core/            # Datenbank, Parser, Abfrage-Klassen
├── lang/            # Sprachdateien (de/en)
├── pages/           # Hauptseiten (index, player, admin ...)
├── setup/           # setup.php + install.sql
├── templates/       # HTML-Layout (header, footer, cards)
├── uploads/         # Hochgeladene Log-Dateien
├── assets/          # CSS, JS, Bilder
└── index.php        # Einstiegspunkt (Weiterleitung)
```

---

## 🚀 Installation

1. **Webserver vorbereiten** (PHP 8+, MySQL/MariaDB, Apache/Nginx)
2. **Projekt entpacken oder klonen**
3. **Aufrufen von**: `http://DEINE_URL/setup/setup.php`
4. **MySQL-Zugang und Admin-Login angeben**
5. **Nach Erfolg**: `setup.php` löschen oder umbenennen
6. **ET-Logdatei hochladen** unter `/uploads/upload.php`

---

## ⚠️ Hinweise

- `setup/sql/install.sql` enthält alle Tabellen
- Nach der Installation: `setup.php` entfernen!
- Der LogParser unterstützt aktuell nur `Kill:`-Events (Erweiterung möglich)
- Stelle sicher, dass `config/config.php` nach Schreibvorgang lesbar bleibt

---

## 📜 Lizenz

MIT License – frei verwendbar, anpassbar, Open Source ❤️

---

## ✉️ Mitwirken oder Fragen?

> Erstelle ein Issue oder schreib eine Nachricht. Feedback ist willkommen!
