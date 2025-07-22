#!/bin/bash

# Deployment Script für Cloudways - Nur Theme synchronisieren
# Dieses Script auf dem Cloudways-Server ausführen

# Variablen anpassen
REPO_URL="https://github.com/svenmass/abf-styleguide.git"
WP_CONTENT_PATH="/home/master/applications/DEINE_APP_ID/public_html/wp-content"
THEME_PATH="$WP_CONTENT_PATH/themes/abf-styleguide"

echo "🚀 Starte Theme-Deployment..."

# Ins wp-content Verzeichnis wechseln
cd "$WP_CONTENT_PATH" || exit 1

# Prüfen ob Git-Repository bereits existiert
if [ ! -d ".git" ]; then
    echo "📂 Klone Repository..."
    git clone "$REPO_URL" temp-repo
    cp -r temp-repo/.git .
    rm -rf temp-repo
    
    # Sparse-Checkout aktivieren
    git config core.sparseCheckout true
    echo "themes/abf-styleguide/*" > .git/info/sparse-checkout
    
    echo "✅ Repository initialisiert mit Sparse-Checkout"
else
    echo "📂 Repository existiert bereits"
fi

# Sparse-Checkout konfigurieren (falls noch nicht geschehen)
git config core.sparseCheckout true
echo "themes/abf-styleguide/*" > .git/info/sparse-checkout

# Latest Changes pullen
echo "⬇️ Lade neueste Änderungen..."
git fetch origin
git reset --hard origin/content-blocks

# Theme-Permissions setzen
echo "🔧 Setze Berechtigungen..."
chown -R master:master "$THEME_PATH"
find "$THEME_PATH" -type d -exec chmod 755 {} \;
find "$THEME_PATH" -type f -exec chmod 644 {} \;

echo "✅ Theme-Deployment abgeschlossen!"
echo "📁 Aktualisierte Dateien in: $THEME_PATH" 