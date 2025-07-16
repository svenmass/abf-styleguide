# Templates - ABF Styleguide Theme

## 🎯 Übersicht

Das Theme verfügt über zwei verschiedene Page-Templates für unterschiedliche Anwendungsfälle:

1. **Fullscreen Template**: Ohne Header/Navigation (für Homepage)
2. **Styleguide Page Template**: Mit Header/Navigation (für Content-Seiten)

## 📄 Template-Optionen

### 1. Fullscreen Template
**Datei**: `page-fullscreen.php`  
**Template Name**: "Fullscreen"

#### Verwendung
- **Homepage/Landing Pages**
- **Vollbild-Präsentationen**
- **Hero-Bereiche ohne Navigation**

#### Features
- ✅ **Kein Header** - Vollbild-Erfahrung
- ✅ **Keine Navigation** - Cleanes Design
- ✅ **Vollbreite Blöcke** - Alle Blöcke nutzen 100vw
- ✅ **Optimiert für Hero-Blöcke** - Perfekt für Parallax-Effekte

#### CSS-Klassen
- `.fullscreen-template` - Body-Klasse
- `.fullscreen-site` - Site-Container
- `.fullscreen-content` - Content-Container
- `.fullscreen-main` - Main-Container

### 2. Styleguide Page Template
**Datei**: `page-styleguide.php`  
**Template Name**: "Styleguide Page"

#### Verwendung
- **Content-Seiten**
- **Über uns, Kontakt, etc.**
- **Normale Seiten mit Navigation**

#### Features
- ✅ **Header mit Logo** - Branding sichtbar
- ✅ **Sidebar-Navigation** - Desktop immer sichtbar
- ✅ **Burger Menu** - Mobile Navigation
- ✅ **Container-Layout** - Begrenzte Breite für Content

#### CSS-Klassen
- `.styleguide-main` - Main-Container
- `.page-header` - Seiten-Header
- `.page-title` - Seiten-Titel

## 🚀 Template zuweisen

### In WordPress Admin
1. **Seite bearbeiten** → Seitenattribute
2. **Template auswählen**:
   - "Fullscreen" für Homepage
   - "Styleguide Page" für normale Seiten
3. **Aktualisieren**

### Programmatisch
```php
// Template für eine Seite setzen
update_post_meta($post_id, '_wp_page_template', 'page-fullscreen.php');
update_post_meta($post_id, '_wp_page_template', 'page-styleguide.php');
```

## 🎨 Layout-Unterschiede

### Fullscreen Template
```css
.fullscreen-template {
    .fullscreen-content {
        margin: 0 !important;
        padding: 0 !important;
        width: 100vw;
    }
    
    .container-home,
    .container-content {
        width: 100vw;
        max-width: 100vw;
        margin: 0;
        padding: 0;
    }
}
```

### Styleguide Page Template
```css
.styleguide-main {
    padding: 32px 0;
    
    .page-header {
        text-align: center;
        margin-bottom: 32px;
    }
    
    .page-title {
        font-size: 36px;
        color: var(--color-primary);
    }
}
```

## 📱 Responsive Verhalten

### Fullscreen Template
- **Mobile**: Vollbild ohne Einschränkungen
- **Desktop**: Vollbild ohne Header/Navigation
- **Blöcke**: Nutzen immer 100vw Breite

### Styleguide Page Template
- **Mobile**: Header + Burger Menu, Content vollbreite
- **Desktop**: Header + Sidebar-Navigation, Content versetzt
- **Blöcke**: Nutzen Container-Breiten

## 🧱 Block-Kompatibilität

### Fullscreen Template
- ✅ **Hero-Blöcke** - Perfekt für Vollbild
- ✅ **Parallax-Blöcke** - Unbegrenzte Breite
- ✅ **Text-Blöcke** - Vollbreite Layout
- ✅ **Grid-Blöcke** - Maximale Flexibilität

### Styleguide Page Template
- ✅ **Alle Blöcke** - Mit Container-Beschränkungen
- ✅ **Text-Blöcke** - Begrenzte Breite für Lesbarkeit
- ✅ **Content-Blöcke** - Optimiert für Content

## 💻 Code-Beispiele

### Template-Abfrage
```php
// Prüfen welches Template verwendet wird
if (is_page_template('page-fullscreen.php')) {
    // Fullscreen Template
    echo 'Vollbild-Modus';
} elseif (is_page_template('page-styleguide.php')) {
    // Styleguide Template
    echo 'Content-Modus';
}
```

### Conditional Loading
```php
// Unterschiedliche Header je Template
if (is_page_template('page-fullscreen.php')) {
    get_header('fullscreen');
} else {
    get_header();
}
```

### Custom Fields je Template
```php
// Template-spezifische Felder
if (is_page_template('page-fullscreen.php')) {
    $hero_background = get_field('hero_background');
    // Fullscreen-spezifische Logik
} else {
    $page_subtitle = get_field('page_subtitle');
    // Content-spezifische Logik
}
```

## 🎯 Best Practices

### Fullscreen Template
- **Verwende für**: Homepage, Landing Pages, Präsentationen
- **Vermeide**: Zu viel Text-Content
- **Optimiere für**: Visuelle Effekte und Hero-Bereiche

### Styleguide Page Template
- **Verwende für**: Content-Seiten, About, Kontakt
- **Vermeide**: Zu viele Hero-Blöcke
- **Optimiere für**: Lesbarkeit und Navigation

### Template-Auswahl
- **Homepage**: Fullscreen Template
- **Über uns**: Styleguide Page Template
- **Kontakt**: Styleguide Page Template
- **Services**: Styleguide Page Template
- **Landing Pages**: Fullscreen Template

## 🔍 Troubleshooting

### Template wird nicht angezeigt
1. **Dateiname prüfen**: `page-fullscreen.php` / `page-styleguide.php`
2. **Template Name prüfen**: Kommentar im PHP-File
3. **Cache leeren**: WordPress Cache leeren
4. **Permalinks aktualisieren**: Einstellungen → Permalinks

### Layout-Probleme
1. **CSS-Kompilierung**: `npm run build` ausführen
2. **Template-Klassen**: Body-Klassen prüfen
3. **Container-Breiten**: CSS-Regeln prüfen

### Navigation funktioniert nicht
1. **Template prüfen**: Nur Styleguide Template hat Navigation
2. **JavaScript**: Navigation.js wird nur bei Bedarf geladen
3. **Menü-Einstellungen**: WordPress-Menü zuweisen

## 📚 Weiterführende Links

- [WordPress Page Templates](https://developer.wordpress.org/themes/templates/page-templates/)
- [Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
- [Conditional Tags](https://developer.wordpress.org/themes/basics/conditional-tags/) 