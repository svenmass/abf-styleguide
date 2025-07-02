# ABF Styleguide Theme

## Farben-System

### Übersicht
Das ABF Styleguide Theme verwendet ein dynamisches Farben-System, das über ACF (Advanced Custom Fields) verwaltet wird. Alle Farben werden zentral in den Theme-Einstellungen definiert und sind sowohl im Gutenberg-Editor als auch in allen ACF-Blöcken verfügbar.

### Funktionsweise

#### 1. Farben definieren
- Gehe zu **WordPress Admin → Theme Einstellungen**
- Im Bereich "Farben" kannst du beliebig viele Farben hinzufügen
- Für jede Farbe gibst du einen Namen und einen Farbwert an
- Klicke auf "Farbe hinzufügen" um weitere Farben zu erstellen

#### 2. Automatische Speicherung
- Beim Speichern der Theme-Einstellungen werden alle Farben automatisch in `colors.json` gespeichert
- Die Datei befindet sich im Theme-Verzeichnis: `wp-content/themes/abf-styleguide/colors.json`
- Das Format ist: `[{"name": "Farbname", "color": "#hexcode"}]`

#### 3. Verfügbarkeit in Gutenberg
- Alle definierten Farben werden automatisch im Gutenberg-Editor registriert
- Sie erscheinen in der Farbpalette aller Blöcke
- Die Farben sind über CSS-Klassen verfügbar (z.B. `has-primary-color-color`)

#### 4. Verwendung in ACF-Blöcken
- Die Farben sind in allen ACF-Blöcken verfügbar
- Sie können über die `colors.json` Datei ausgelesen werden
- Beispiel-Code:
```php
$colors_json = file_get_contents(get_template_directory() . '/colors.json');
$colors = json_decode($colors_json, true);
```

### Dateien
- **`functions.php`**: Enthält die ACF-Felder und Gutenberg-Registrierung
- **`colors.json`**: Automatisch generierte Datei mit allen Farben
- **Theme-Einstellungen**: WordPress Admin-Bereich für die Farbverwaltung

### Vorteile
- ✅ Zentrale Farbverwaltung
- ✅ Dynamische Farben ohne Code-Änderungen
- ✅ Automatische Gutenberg-Integration
- ✅ Verfügbar in allen ACF-Blöcken
- ✅ Einfache Wartung und Erweiterung
