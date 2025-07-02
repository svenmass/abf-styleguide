# ABF Styleguide WordPress Theme

Ein modulares WordPress Theme mit ACF Blocks, SCSS-Kompilierung via Vite und dynamischen Farbeinstellungen.

## 🚀 Schnellstart

### Voraussetzungen
- Docker und Docker Compose
- Node.js (für Vite)

### Installation
1. Repository klonen
2. Docker Container starten:
   ```bash
   docker-compose up -d
   ```
3. Dependencies installieren:
   ```bash
   npm install
   ```
4. SCSS kompilieren:
   ```bash
   npm run dev
   ```
5. WordPress unter http://localhost:8083 aufrufen

## 📁 Projektstruktur

```
wp-content/themes/abf-styleguide/
├── assets/
│   ├── fonts/           # Schriftarten (Aptos)
│   └── scss/           # SCSS Dateien
├── blocks/             # ACF Blocks (automatisch registriert)
├── inc/               # PHP Funktionen (modular)
├── functions.php      # Hauptfunktionen
└── colors.json        # Dynamische Farben
```

## 🎨 Design System

### Typografie
- **H1**: Aptos 400, 36px
- **H2**: Aptos 400, 24px  
- **H3**: Aptos 700, 18px
- **H4**: Aptos 400, 18px
- **Body**: Aptos 300, 18px
- **Small**: Aptos 300, 12px
- **Zeilenabstand**: 1.4

### Links
- **Farbe**: #B62D1F
- **Schriftschnitt**: 700 bold
- **Dekoration**: underline
- **Hover**: #74A68E

### Container
- **Home Blocks**: Fullwidth
- **Content Blocks**: 840px max-width

### Spacing System
- **Mobile**: 12px
- **Tablet**: 16px
- **Desktop <1200px**: 24px
- **Desktop >1200px**: 32px

### Breakpoints
- 576px (Mobile)
- 768px (Tablet)
- 1200px (Desktop)
- >1200px (Large Desktop)

### Buttons
- **Schrift**: Aptos 400, 18px
- **Padding**: 16px top/bottom, 48px left/right
- **Border Radius**: 10px
- **Farben**: Dynamisch aus Theme-Settings

## 🔧 Entwicklung

### SCSS kompilieren
```bash
npm run dev      # Development mit Watch
npm run build    # Production Build
```

### ACF Blocks hinzufügen
1. Neuen Ordner in `/blocks/` erstellen
2. `block.json` und Template-Dateien hinzufügen
3. Blocks werden automatisch registriert

### PHP Funktionen hinzufügen
1. Neue PHP-Datei in `/inc/` erstellen
2. Datei wird automatisch eingebunden

## 🎯 Theme Settings

Farben werden über WordPress Admin → Theme Settings definiert und in `colors.json` gespeichert.

## 📝 Nächste Schritte für neue Chats

1. ✅ Projektstruktur ist bereits aufgesetzt
2. ✅ SCSS-Variablen sind definiert (Typografie, Layout, Komponenten)
3. ✅ Modulare PHP-Struktur implementiert (inc/ Ordner mit Autoload)
4. ✅ ACF Block-System eingerichtet (automatische Registrierung)
5. ✅ Dynamische Farben-System implementiert
6. ✅ Beispiel Hero-Block erstellt
7. ✅ Gutenberg Editor Integration vorbereitet

### Implementierte Features
- [x] Modulare PHP-Struktur mit automatischem Laden
- [x] SCSS-System mit Variablen, Typografie, Layout und Komponenten
- [x] Dynamische Farben aus Theme-Settings
- [x] ACF Block-System mit automatischer Registrierung
- [x] ACF Field Groups für Blocks (Hero-Block)
- [x] Responsive Design mit Breakpoints
- [x] Button-Komponenten mit dynamischen Farben
- [x] Container-System (Home: Fullwidth, Content: 840px)
- [x] Spacing-System (12px, 16px, 24px, 32px)
- [x] Gutenberg Editor Integration (Farben verfügbar)
- [x] Beispiel Hero-Block vollständig implementiert

### Offene Punkte
- [ ] Weitere ACF Blocks erstellen
- [ ] Weitere Komponenten (Cards, Forms, etc.)
- [ ] Performance Optimierungen
- [ ] Gutenberg Editor Styles erweitern

## 🔗 Links
- WordPress Admin: http://localhost:8083/wp-admin
- Theme Settings: http://localhost:8083/wp-admin/admin.php?page=theme-settings 