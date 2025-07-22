# Cloudways Deployment - Nur Theme synchronisieren

## Problem
Du hast den gesamten wp-content Ordner in Git, möchtest aber auf dem Cloudways-Server nur das Theme aktualisieren.

## Lösung: Git Sparse-Checkout

### 1. Einmalige Einrichtung auf Cloudways-Server

```bash
# Via SSH auf den Cloudways-Server verbinden
# Dann ins wp-content Verzeichnis wechseln
cd /home/master/applications/DEINE_APP_ID/public_html/wp-content

# Repository klonen (falls noch nicht vorhanden)
git clone https://github.com/svenmass/abf-styleguide.git temp-repo
cp -r temp-repo/.git .
rm -rf temp-repo

# Sparse-Checkout aktivieren
git config core.sparseCheckout true

# Nur das Theme-Verzeichnis definieren
echo "themes/abf-styleguide/*" > .git/info/sparse-checkout

# Initiale Checkout
git read-tree -m -u HEAD
```

### 2. Updates deployen

```bash
# Für jedes Update einfach:
cd /home/master/applications/DEINE_APP_ID/public_html/wp-content

git fetch origin
git reset --hard origin/content-blocks

# Berechtigungen setzen
chown -R master:master themes/abf-styleguide
find themes/abf-styleguide -type d -exec chmod 755 {} \;
find themes/abf-styleguide -type f -exec chmod 644 {} \;
```

## Alternative: GitHub Actions (Automatisierung)

Falls du automatisches Deployment möchtest, können wir ein GitHub Action Setup erstellen.

## Vorteile dieser Lösung

✅ Nur das Theme wird übertragen
✅ Schnelle Updates
✅ Bestehendes Repository bleibt unverändert
✅ Keine Umstrukturierung nötig
✅ Plugins/Uploads bleiben unberührt

## Wichtige Hinweise

- **DEINE_APP_ID** durch deine echte Cloudways App-ID ersetzen
- SSH-Zugang zu Cloudways erforderlich  
- Backup vor erstem Deployment empfohlen
- Bei Änderungen am Sparse-Checkout Pattern neu konfigurieren 