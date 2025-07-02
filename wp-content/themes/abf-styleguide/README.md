# ABF Styleguide Theme

Ein vollständiges WordPress-Theme mit dynamischen ACF-Blöcken und Farben-System.

## 🚀 Features

- ✅ **ACF-Blöcke** für Gutenberg-Editor
- ✅ **Dynamisches Farben-System** über Theme-Einstellungen
- ✅ **Responsive Design** (Mobile-first)
- ✅ **Modulare Struktur** für einfache Erweiterung
- ✅ **Deutsche Übersetzungen**
- ✅ **CSS Custom Properties** für moderne Browser
- ✅ **Accessibility Features**

## 📁 Projektstruktur

```
wp-content/themes/abf-styleguide/
├── assets/
│   ├── css/main.css          # Kompilierte CSS-Datei
│   ├── fonts/                # Aptos-Schriftarten
│   └── scss/                 # SCSS-Quellcode (optional)
├── blocks/
│   └── headline/             # Beispiel-Block
│       ├── block.json        # Block-Konfiguration
│       └── template.php      # Frontend-Template
├── inc/
│   ├── acf-blocks.php        # Block-Registrierung
│   ├── dynamic-colors.php    # Farben-System
│   ├── theme-settings.php    # Theme-Einstellungen
│   ├── theme-setup.php       # Theme-Setup
│   └── template-functions.php # Template-Hilfsfunktionen
├── template-parts/           # Template-Teile
├── colors.json               # Dynamische Farben
├── functions.php             # Haupt-Functions
├── header.php, footer.php    # WordPress-Templates
└── README.md                 # Diese Datei
```

## 🎨 Farben-System

### Übersicht
Das Theme verwendet ein dynamisches Farben-System, das über ACF verwaltet wird. Alle Farben werden zentral in den Theme-Einstellungen definiert und sind sowohl im Gutenberg-Editor als auch in allen ACF-Blöcken verfügbar.

### Verwendung
1. **Gehe zu WordPress Admin → Theme Einstellungen**
2. **Füge Farben hinzu** (Name + Hex-Wert)
3. **Speichern** - Farben werden automatisch in `colors.json` gespeichert
4. **Verwende sie** in Blöcken und CSS als `var(--color-farbname)`

### Technische Details
- **Primary/Secondary**: Automatisch aus ersten beiden Farben generiert
- **CSS-Variablen**: `--color-primary`, `--color-secondary`, `--color-farbname`
- **JSON-Export**: Automatisch bei Speicherung in `colors.json`

## 🧱 Neue ACF-Blöcke erstellen

### Blueprint: Headline Block

Der Headline-Block dient als perfektes Beispiel für neue Blöcke. Hier ist die Schritt-für-Schritt-Anleitung:

### 1. Block-Verzeichnis erstellen

```bash
mkdir wp-content/themes/abf-styleguide/blocks/BLOCKNAME
```

### 2. block.json erstellen

```json
{
    "name": "acf/BLOCKNAME",
    "title": "Block Titel",
    "description": "Beschreibung des Blocks",
    "category": "abf-blocks",
    "icon": "admin-generic",
    "keywords": ["keyword1", "keyword2"],
    "supports": {
        "jsx": true
    },
    "mode": "edit",
    "example": {
        "attributes": {
            "mode": "preview",
            "data": {
                "field_name": "Beispielwert"
            }
        }
    }
}
```

### 3. template.php erstellen

```php
<?php
/**
 * BLOCKNAME Block Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get block data
$field_name = get_field('field_name');

// Don't render if no content
if (!$field_name) {
    return;
}

// Build CSS classes
$classes = array('block-BLOCKNAME');

// Container class based on location
$container_class = 'container-content'; // 840px max-width
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="<?php echo esc_attr($container_class); ?>">
        <!-- Dein HTML hier -->
        <div class="BLOCKNAME-content">
            <?php echo esc_html($field_name); ?>
        </div>
    </div>
</div>
```

### 4. Block in acf-blocks.php registrieren

Füge in `inc/acf-blocks.php` in der Funktion `abf_register_acf_blocks()` hinzu:

```php
// Register BLOCKNAME Block
acf_register_block_type(array(
    'name'              => 'BLOCKNAME',
    'title'             => __('Block Titel'),
    'description'       => __('Beschreibung des Blocks'),
    'render_template'   => get_template_directory() . '/blocks/BLOCKNAME/template.php',
    'category'          => 'abf-blocks',
    'icon'              => 'admin-generic',
    'keywords'          => array('keyword1', 'keyword2'),
    'supports'          => array(
        'jsx' => true,
    ),
    'mode'              => 'edit',
));
```

### 5. Feldgruppe registrieren

Füge in `inc/acf-blocks.php` in der Funktion `abf_register_acf_field_groups()` hinzu:

```php
// BLOCKNAME Block Field Group
acf_add_local_field_group(array(
    'key' => 'group_BLOCKNAME_block',
    'title' => 'BLOCKNAME Block Felder',
    'fields' => array(
        array(
            'key' => 'field_BLOCKNAME_text',
            'label' => 'Text',
            'name' => 'text_field',
            'type' => 'text',
            'instructions' => 'Gib hier den Text ein',
            'required' => 1,
        ),
        // Farb-Dropdown (nutzt dynamische Farben)
        array(
            'key' => 'field_BLOCKNAME_color',
            'label' => 'Farbe',
            'name' => 'color_field',
            'type' => 'select',
            'instructions' => 'Wähle eine Farbe',
            'required' => 0,
            'choices' => $color_choices, // Automatisch verfügbar
            'default_value' => 'inherit',
        ),
        // Weitere Felder...
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/BLOCKNAME',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => 'Felder für den BLOCKNAME-Block',
));
```

### 6. CSS-Styles hinzufügen

Füge in `assets/css/main.css` im Bereich "BLOCK STYLES" hinzu:

```css
/* BLOCKNAME Block */
.block-BLOCKNAME {
    /* Deine Styles hier */
}

.block-BLOCKNAME .BLOCKNAME-content {
    /* Content-Styles */
}
```

### 7. Verfügbare Feld-Typen

```php
// Text-Feld
'type' => 'text',

// Textarea
'type' => 'textarea',

// WYSIWYG-Editor
'type' => 'wysiwyg',

// Select-Dropdown
'type' => 'select',
'choices' => array(
    'value1' => 'Label 1',
    'value2' => 'Label 2',
),

// Bild
'type' => 'image',
'return_format' => 'array',

// URL
'type' => 'url',

// Nummer
'type' => 'number',

// Checkbox
'type' => 'true_false',

// Farb-Picker
'type' => 'color_picker',

// Datum
'type' => 'date_picker',

// Repeater (Pro)
'type' => 'repeater',
'sub_fields' => array(
    // Sub-Felder hier
),
```

### 8. Dynamische Farben verwenden

```php
// In template.php
$color_value = get_field('color_field');

// CSS-Ausgabe
if ($color_value === 'primary') {
    $styles[] = 'color: var(--color-primary)';
} elseif ($color_value === 'secondary') {
    $styles[] = 'color: var(--color-secondary)';
} else {
    // Dynamische Farbe aus colors.json
    $color_value = abf_get_color_value($color_value);
    if ($color_value) {
        $styles[] = 'color: ' . $color_value;
    }
}
```

### 9. Container-Klassen

```php
// Vollbreite (für Hero-Bereiche)
$container_class = 'container-home';

// Begrenzte Breite (für Content-Bereiche)
$container_class = 'container-content';
```

### 10. Spacing-Utilities

```php
// CSS-Klassen für Abstände
$classes[] = 'p-md';    // Padding medium
$classes[] = 'm-lg';    // Margin large

// Verfügbare Größen: xs, sm, md, lg
```

## 🛠 Entwicklung

### CSS-Variablen
```css
:root {
    --color-primary: #007cba;
    --color-secondary: #6c757d;
    --font-family-primary: 'Aptos', sans-serif;
    --font-size-body: 18px;
    --spacing-md: 24px;
    --container-content: 840px;
}
```

### Breakpoints
```css
/* Mobile first */
@media (min-width: 768px) { /* Tablet */ }
@media (min-width: 1200px) { /* Desktop */ }
```

### Helper-Funktionen
```php
// Farben
abf_get_colors()                    // Alle Farben aus JSON
abf_get_color_value($color_name)    // Farbwert nach Name
abf_get_color_choices()             // Für ACF-Dropdowns

// Template
abf_styleguide_posted_on()          // Datum-Meta
abf_styleguide_posted_by()          // Autor-Meta
abf_styleguide_post_thumbnail()     // Post-Thumbnail
```

## 📝 Best Practices

### 1. Naming Convention
- **Block-Name**: `block-name` (Kleinbuchstaben, Bindestriche)
- **CSS-Klassen**: `.block-name`, `.block-name-element`
- **Feld-Keys**: `field_blockname_fieldname`
- **Feld-Namen**: `fieldname` (ohne Präfix)

### 2. Sicherheit
```php
// Immer escapen
echo esc_html($text);
echo esc_attr($class);
echo esc_url($link);
echo wp_kses_post($rich_text);
```

### 3. Performance
- **Conditional Loading**: Nur rendern wenn Inhalte vorhanden
- **CSS-Optimierung**: Verwende CSS-Variablen statt Inline-Styles wo möglich
- **Bild-Optimierung**: Nutze responsive Bilder

### 4. Accessibility
```php
// Screen-Reader-Text
<span class="screen-reader-text">Beschreibung</span>

// Semantic HTML
<article>, <section>, <header>, <main>

// Alt-Texte für Bilder
alt="<?php echo esc_attr($image['alt']); ?>"
```

## 🚀 Deployment

1. **Teste alle Blöcke** im Gutenberg-Editor
2. **Prüfe Responsive Design** auf verschiedenen Geräten
3. **Validiere HTML/CSS** 
4. **Performance-Test** mit PageSpeed Insights
5. **Accessibility-Test** mit WAVE oder axe

## 🔧 Troubleshooting

### Block erscheint nicht im Editor
- ACF Pro aktiviert?
- Feldgruppe korrekt registriert?
- Block-Name stimmt überein?

### Felder werden nicht angezeigt
- `acf/init` Hook verwendet?
- Eindeutige Keys für Felder?
- Location-Rules korrekt?

### Farben funktionieren nicht
- `colors.json` existiert?
- Theme-Einstellungen gespeichert?
- CSS-Variablen geladen?

## 📚 Weiterführende Links

- [ACF Dokumentation](https://www.advancedcustomfields.com/resources/)
- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [CSS Custom Properties](https://developer.mozilla.org/en-US/docs/Web/CSS/--*)

---

**Entwickelt für ABF** | Version 1.0 | WordPress 6.5+
