# 🚀 PRODUCTION DEPLOYMENT - RICHTIGE LÖSUNG

## ❌ AKTUELLES PROBLEM:
- node_modules auf Live-Server (falsch!)
- Build-Prozess läuft live (gefährlich!)
- Assets nicht optimiert

## ✅ RICHTIGE LÖSUNG:

### SCHRITT 1: 🏗️ LOKALER BUILD
```bash
# Auf deinem lokalen Mac:
cd /Users/svenmassanneck/Sites/abf-styleguide/wp-content/themes/abf-styleguide-production

# Dependencies installieren (falls nötig)
npm install

# Production Build erstellen
npm run build

# Das kompiliert:
# assets/scss/main.scss → assets/css/main.css (komprimiert)
```

### SCHRITT 2: 📦 NUR OUTPUTS HOCHLADEN
```bash
# NUR diese Dateien auf Live-Server hochladen:
✅ assets/css/main.css           ← Kompilierte CSS
✅ assets/css/main.css.map       ← Source Map
✅ assets/js/main.js             ← JavaScript Dateien
✅ alle .php Dateien             ← Theme Code
✅ alle .json Dateien            ← Block Configs

# NICHT hochladen:
❌ node_modules/                 ← Development Dependencies  
❌ package.json                  ← Build Config
❌ package-lock.json             ← Dependency Lock
❌ assets/scss/                  ← Source SCSS Files
```

### SCHRITT 3: 🧹 LIVE-SERVER BEREINIGEN
```bash
# Vom Live-Server ENTFERNEN:
rm -rf wp-content/themes/abf-styleguide-production/node_modules/
rm -f wp-content/themes/abf-styleguide-production/package*.json
```

### SCHRITT 4: 🔄 BUILD-AUTOMATISIERUNG
```bash
# Build-Script für zukünftige Deployments:
#!/bin/bash
cd themes/abf-styleguide-production
npm run build
rsync -av --exclude=node_modules --exclude=package* ./ user@server:/path/to/theme/
```

## 🎯 WARUM DAS FUNKTIONIEREN WIRD:
1. **Kleinere Uploads** (ohne node_modules)
2. **Optimierte Assets** (komprimierte CSS)
3. **Sicherheit** (keine dev dependencies)
4. **Performance** (weniger Dateien auf Server)

## ⚡ SOFORT-FIX FÜR DEIN PROBLEM:
Das erklärt warum der Editor nicht funktioniert:
- Live lädt möglicherweise unkompilierte SCSS
- JavaScript dependencies fehlen
- Build-Pipeline ist unterbrochen 