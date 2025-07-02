# ABF Styleguide - WordPress Theme

Ein maßgeschneidertes WordPress-Theme mit Custom Blocks und modernem Design-System.

## 🚀 Schnellstart

### Entwicklung
```bash
npm install
npm run dev    # Development Server
npm run build  # Production Build
```

### Docker Setup
```bash
docker-compose up -d
```

---

## 📐 Block-Entwicklung Best Practices

### Viewport & Bildschirmbreite Handling

#### ⚠️ Wichtig: Editor vs. Frontend
**Problem:** WordPress-Editor hat begrenzten Platz → `100vw` verursacht horizontale Scrollbalken

**Lösung:** Editor-spezifische Styles verwenden

```scss
// ✅ Editor-spezifische Styles
.wp-block-acf-{block-name} {
    .block-{name} {
        // Editor: begrenzte Breite
        width: 100% !important;
        max-width: 100% !important;
        
        // Editor: begrenzte Höhe
        height: 50vh;
        min-height: 400px;
        max-height: 600px;
    }
}

// ✅ Frontend: volle Viewport-Größe
.block-{name} {
    width: 100vw;
    height: 100vh;
    max-width: 100vw;
    max-height: 100vh;
}
```

#### 📱 Responsive Breakpoints
```scss
// Mobile (bis 575px)
@media (max-width: #{$breakpoint-mobile - 1px}) { }

// Tablet (576px bis 1199px)  
@media (min-width: $breakpoint-mobile) and (max-width: #{$breakpoint-desktop - 1px}) { }

// Desktop (ab 1200px)
@media (min-width: $breakpoint-desktop) { }
```

---

## 🎨 Design-System

### Farb-System
```scss
// Primärfarben
$color-primary: #007cba;
$color-secondary: #666;
$color-accent: #ff6b35;

// Graustufen
$color-white: #ffffff;
$color-black: #000000;
$color-gray-light: #f8f9fa;
$color-gray: #6c757d;
$color-gray-dark: #343a40;
```

### Spacing-System
```scss
$spacing-xs: 8px;
$spacing-sm: 16px;
$spacing-md: 24px;
$spacing-lg: 32px;
$spacing-xl: 48px;
$spacing-xxl: 64px;
```

### Typography
```scss
$font-family-primary: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
$font-family-secondary: 'Georgia', serif;

// Font Sizes
$font-size-xs: 12px;
$font-size-sm: 14px;
$font-size-body: 16px;
$font-size-lg: 18px;
$font-size-xl: 20px;
$font-size-h6: 16px;
$font-size-h5: 18px;
$font-size-h4: 20px;
$font-size-h3: 24px;
$font-size-h2: 30px;
$font-size-h1: 36px;
```

---

## 🧩 Block-Entwicklung Workflow

### 1. Block-Struktur erstellen
```
blocks/
├── {block-name}/
│   ├── block.json          # Block-Konfiguration
│   ├── template.php        # Block-Template
│   └── style.scss          # Block-spezifische Styles (optional)
```

### 2. Block registrieren
```php
// inc/acf-blocks.php
acf_register_block_type([
    'name' => 'block-name',
    'title' => 'Block Title',
    'description' => 'Block Description',
    'category' => 'abf-blocks',
    'icon' => 'admin-appearance',
    'keywords' => ['keyword1', 'keyword2'],
    'mode' => 'auto', // Wichtig für Editor-Preview!
    'render_template' => get_template_directory() . '/blocks/block-name/template.php'
]);
```

### 3. SCSS hinzufügen
```scss
// assets/scss/_blocks.scss

// Editor-spezifische Styles IMMER zuerst
.wp-block-acf-{block-name} {
    .block-{name} {
        // Editor-Anpassungen hier
    }
}

// Frontend-Styles danach
.block-{name} {
    // Frontend-Styles hier
}
```

---

## 🔧 Technische Konfiguration

### Upload-Limits (für große Videos)
```php
// functions.php
@ini_set('upload_max_size', '128M');
@ini_set('post_max_size', '128M');
@ini_set('max_execution_time', 300);
```

```ini
# docker/php.ini
upload_max_filesize = 128M
post_max_size = 128M
max_execution_time = 300
```

### WordPress Header entfernen
```php
// functions.php
remove_action('wp_head', '_wp_render_title_tag', 1);
remove_action('wp_head', 'wp_generator');
// ... weitere Optimierungen
```

---

## 🎯 ACF Integration

### Feldgruppen-Struktur
```php
// Konsistente Feld-Benennung
'background_type' => 'radio',
'background_image' => 'image',
'background_video' => 'file',
'headline_text' => 'text',
'headline_tag' => 'select',
'headline_weight' => 'select',
'headline_size' => 'select',
'headline_color' => 'select',
```

### Dynamische Farb-Optionen
```php
// inc/acf-blocks.php
function get_color_choices() {
    return [
        'var(--color-primary)' => 'Primärfarbe',
        'var(--color-secondary)' => 'Sekundärfarbe',
        'var(--color-white)' => 'Weiß',
        'var(--color-black)' => 'Schwarz',
        // ...
    ];
}
```

---

## 🐛 Troubleshooting

### Problem: Horizontale Scrollbalken im Editor
**Lösung:** Editor-spezifische Styles verwenden (siehe oben)

### Problem: Block wird nicht im Editor angezeigt
**Lösung:** 
1. `mode => 'auto'` in der Block-Registrierung
2. CSS für `.wp-block-acf-{block-name}` hinzufügen

### Problem: Styles werden nicht geladen
**Lösung:**
1. `npm run build` ausführen
2. Cache leeren (Browser + WordPress)
3. SCSS-Syntax prüfen

### Problem: ACF Felder werden nicht gespeichert
**Lösung:**
1. Feldgruppen-Namen prüfen
2. `get_field()` vs. `get_sub_field()` korrekt verwenden
3. ACF Pro aktiviert?

---

## 📁 Projektstruktur

```
wp-content/themes/abf-styleguide/
├── assets/
│   ├── css/                # Kompilierte CSS-Dateien
│   ├── fonts/              # Webfonts
│   └── scss/               # SCSS-Quelldateien
│       ├── _variables.scss # Design-System Variablen
│       ├── _blocks.scss    # Block-spezifische Styles
│       └── main.scss       # Haupt-SCSS-Datei
├── blocks/                 # Custom Blocks
│   ├── hero/
│   └── headline/
├── inc/                    # PHP-Includes
│   ├── acf-blocks.php      # Block-Registrierung
│   ├── enqueue.php         # Asset-Verwaltung
│   └── theme-setup.php     # Theme-Konfiguration
├── template-parts/         # Template-Teile
├── functions.php           # Theme-Funktionen
└── style.css              # Theme-Stil (nur Metadaten)
```

---

## 🚨 Wichtige Hinweise

### ⚠️ Viewport-Units im Editor
- **NIE** `100vw` oder `100vh` ohne Editor-Styles verwenden
- **IMMER** Editor-spezifische Anpassungen vorsehen
- **Mode: 'auto'** für beste Editor-Experience

### ⚠️ Performance
- Bilder optimieren (WebP verwenden)
- Videos komprimieren
- SCSS kompilieren, nicht direkt laden
- Unnötige WordPress-Features entfernen

### ⚠️ Git-Hygiene
- WordPress-Core-Dateien NICHT committen
- `.gitignore` aktuell halten
- Sinnvolle Commit-Messages verwenden

---

## 📞 Support

Bei Fragen oder Problemen:
1. Diese README konsultieren
2. Browser-Entwicklertools verwenden
3. WordPress-Debug-Modus aktivieren
4. Git-History für Referenz-Implementierungen nutzen

---

**Viel Erfolg bei der Block-Entwicklung!** 🎉 