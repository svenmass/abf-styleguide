# Deployment-Checkliste für ABF Benutzermanagement

## Sofortige Maßnahmen bei Problemen

### 1. Debug-Test ausführen
```
https://ihre-domain.de/wp-content/themes/abf-styleguide/debug-user-management.php
```

### 2. Cache leeren (WICHTIG!)
1. **Breeze Cache leeren**:
   - WordPress Admin → Breeze → Settings
   - Auf "Purge All Cache" klicken
   - Oder: `?breeze_purge=1` an die URL anhängen

2. **Browser-Cache leeren**:
   - Strg+Shift+R (Hard Refresh)
   - Oder Entwicklertools → Netzwerk → "Disable Cache" aktivieren

### 3. WordPress Debug aktivieren
In `wp-config.php` hinzufügen:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### 4. Häufige Probleme und Lösungen

#### Problem: Modal erscheint nicht
- **Ursache**: JavaScript-Datei wird nicht geladen ODER Homepage nicht definiert
- **Lösung**: 
  1. **Homepage definieren**: WordPress Admin → Einstellungen → Lesen → "Eine statische Seite"
  2. Cache leeren, Browser-Entwicklertools prüfen

#### Problem: AJAX-Requests schlagen fehl
- **Ursache**: Falsche AJAX-URL oder Nonce-Fehler
- **Lösung**: Pretty Permalinks prüfen, Cache leeren

#### Problem: Registrierung funktioniert nicht
- **Ursache**: Datenbank-Rolle wurde nicht erstellt
- **Lösung**: Debug-Script ausführen, WordPress deaktivieren/aktivieren

#### Problem: E-Mails werden nicht gesendet
- **Ursache**: SMTP nicht konfiguriert
- **Lösung**: SMTP-Plugin installieren (z.B. WP Mail SMTP)

### 5. Produktions-Checks

1. **Dateiberechtigungen prüfen**:
   - PHP-Dateien: 644
   - JavaScript-Dateien: 644
   - Verzeichnisse: 755

2. **URL-Struktur prüfen**:
   - Pretty Permalinks aktiviert?
   - .htaccess korrekt?

3. **PHP-Version**:
   - Mindestens PHP 7.4
   - Alle erforderlichen Extensions aktiviert

4. **WordPress-Version**:
   - Aktuell und kompatibel

### 6. Debugging-Befehle

```bash
# Dateiberechtigungen setzen
find /path/to/wp-content/themes/abf-styleguide -type f -name "*.php" -exec chmod 644 {} \;
find /path/to/wp-content/themes/abf-styleguide -type f -name "*.js" -exec chmod 644 {} \;
find /path/to/wp-content/themes/abf-styleguide -type d -exec chmod 755 {} \;

# Error Log anzeigen
tail -f /path/to/wp-content/debug.log

# Cache-Verzeichnis leeren
rm -rf /path/to/wp-content/cache/*
```

### 7. Sofort-Fixes

#### A. Breeze Cache für User Management ausschließen
In Breeze Settings → Advanced → Never Cache folgende URLs hinzufügen:
```
/wp-admin/admin-ajax.php
*/user-management.js
```

#### B. JavaScript Force-Reload
Die JavaScript-Datei verwendet jetzt automatisches Cache-Busting basierend auf der Dateierstellungszeit.

### 8. Monitoring

Nach dem Deployment prüfen:
- [ ] **Homepage ist definiert** (WordPress Admin → Einstellungen → Lesen → "Eine statische Seite")
- [ ] Homepage lädt korrekt
- [ ] "Anmelden/Registrieren" Button ist sichtbar
- [ ] Modal öffnet sich beim Klick
- [ ] AJAX-Requests funktionieren (Browser-Entwicklertools)
- [ ] Registrierung funktioniert
- [ ] E-Mail-Benachrichtigungen funktionieren
- [ ] Admin-Interface funktioniert

### 9. Rollback-Plan

Falls alles fehlschlägt:
1. Cache komplett leeren
2. WordPress-Plugins deaktivieren
3. Standard-Theme aktivieren
4. ABF-Theme wieder aktivieren
5. Plugins wieder aktivieren

### 10. Kontakt

Bei anhaltenden Problemen:
- Error Log prüfen
- Debug-Script ausführen
- Browser-Entwicklertools analysieren
- Hosting-Provider kontaktieren (falls Server-Probleme) 