#!/bin/bash

# ABF Styleguide Theme - Production Cleanup Script
# This script creates a production-ready, client-friendly version of the theme

set -e # Exit on any error

# Configuration
SOURCE_THEME="themes/abf-styleguide"
PRODUCTION_THEME="themes/abf-styleguide-production"
THEME_NAME="ABF Styleguide"
THEME_VERSION="${THEME_VERSION:-1.0.0}"
THEME_AUTHOR="Sven Massanneck"

echo "🚀 Starting production cleanup for ABF Styleguide Theme..."

# Remove production directory if exists
if [ -d "$PRODUCTION_THEME" ]; then
    echo "🗑️  Removing existing production directory..."
    rm -rf "$PRODUCTION_THEME"
fi

# Copy source theme to production
echo "📂 Creating production copy..."
cp -r "$SOURCE_THEME" "$PRODUCTION_THEME"

echo "🧹 Cleaning up production version..."

# Remove development files
echo "  - Removing development files..."
find "$PRODUCTION_THEME" -name ".DS_Store" -delete
find "$PRODUCTION_THEME" -name "*.backup" -delete
find "$PRODUCTION_THEME" -name "README.md" -delete
find "$PRODUCTION_THEME" -name "DEPLOYMENT-CHECKLIST.md" -delete
rm -rf "$PRODUCTION_THEME/documentation" 2>/dev/null || true

# Clean up PHP files - remove debug code and excessive comments
echo "  - Cleaning PHP files..."
find "$PRODUCTION_THEME" -name "*.php" -exec sed -i '' -e '
    /var_dump\|print_r\|error_log\|console\.log/d;
    /\/\*\*.*Debug.*\*\//d;
    /\/\/.*Debug/d;
    /\/\/.*TODO/d;
    /\/\/.*FIXME/d;
    /\/\/.*@dev/d;
' {} \;

# Clean up JavaScript files - remove console.logs and debug code
echo "  - Cleaning JavaScript files..."
find "$PRODUCTION_THEME" -name "*.js" -exec sed -i '' -e '
    /console\.[log|debug|info|warn|error]/d;
    /\/\/.*Debug/d;
    /\/\/.*TODO/d;
    /\/\/.*FIXME/d;
    /debugger;/d;
' {} \;

# Clean up SCSS/CSS files - remove debug comments
echo "  - Cleaning SCSS files..."
find "$PRODUCTION_THEME" -name "*.scss" -exec sed -i '' -e '
    /\/\/.*Debug/d;
    /\/\/.*TODO/d;
    /\/\/.*FIXME/d;
    /\/\/.*@dev/d;
' {} \;

# Minify CSS if main.css exists
if [ -f "$PRODUCTION_THEME/assets/css/main.css" ]; then
    echo "  - Optimizing CSS..."
    # Remove unnecessary whitespace and comments from CSS
    sed -i '' -e '
        /^[[:space:]]*\/\*/,/\*\/[[:space:]]*$/d;
        s/[[:space:]]*{[[:space:]]*/{/g;
        s/[[:space:]]*}[[:space:]]*$/}/g;
        s/[[:space:]]*:[[:space:]]*/ : /g;
        s/[[:space:]]*;[[:space:]]*/; /g;
        /^[[:space:]]*$/d;
    ' "$PRODUCTION_THEME/assets/css/main.css"
fi

# Create professional theme header in style.css
echo "  - Creating professional theme header..."
cat > "$PRODUCTION_THEME/style.css" << EOF
<?php
/*
Theme Name: $THEME_NAME
Description: Ein professionelles WordPress-Theme mit modularen ACF-Blöcken für moderne Websites. Features: Dynamische Farbpalette, responsive Design, 15+ vorgefertigte Blöcke, automatische Block-Registrierung, optimierte Performance.
Author: $THEME_AUTHOR
Version: $THEME_VERSION
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: blocks, custom-blocks, acf, responsive-design, one-column, two-columns, grid-layout, custom-colors, custom-header, custom-logo, custom-menu, editor-style, featured-images, full-site-editing, rtl-language-support, threaded-comments, translation-ready
Text Domain: abf-styleguide

Features:
- 15+ vorgefertigte ACF-Blöcke
- Automatische Block-Registrierung
- Dynamische Farbpalette
- Responsive SCSS-Framework
- Performance-optimiert
- Translation-ready
- Modern PHP 8.0+ Codebase
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// This file loads the main stylesheet
function abf_production_styles() {
    wp_enqueue_style('abf-style', get_template_directory_uri() . '/assets/css/main.css', array(), '$THEME_VERSION');
}
add_action('wp_enqueue_scripts', 'abf_production_styles');
?>
EOF

echo "📝 Creating professional documentation..."

# Create professional README for production theme
cat > "$PRODUCTION_THEME/README.txt" << EOF
=== $THEME_NAME ===
Contributors: $THEME_AUTHOR
Tags: blocks, custom-blocks, acf, responsive-design
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.0
Stable tag: $THEME_VERSION
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ein professionelles WordPress-Theme mit modularen ACF-Blöcken.

== Beschreibung ==

Das ABF Styleguide Theme bietet eine vollständige Lösung für moderne WordPress-Websites mit über 15 vorgefertigten, anpassbaren Blöcken.

= Features =

* 15+ vorgefertigte ACF-Blöcke
* Automatische Block-Registrierung
* Dynamische Farbpalette aus colors.json
* Responsive SCSS-Framework
* Performance-optimiert
* SEO-freundlich
* Translation-ready
* Modern PHP 8.0+ Codebase

= Verfügbare Blöcke =

* Headline - Konfigurierbare Überschriften
* Hero - Vollbreite Hero-Bereiche
* Text-Element - Flexible Textbausteine
* Bild-Text - Zweispaltige Layouts
* Akkordeon - Aufklappbare Inhalte
* Grid - Flexible Raster-Layouts
* Masonry - Pinterest-Style Layouts
* Parallax-Elemente - Moderne Scroll-Effekte
* Trennlinien - Visuelle Abgrenzungen
* Einzelbild - Optimierte Bilddarstellung
* Post-Grids - Automatische Beitragslisten
* Ähnliche Beiträge - Content-Discovery

= Installation =

1. Advanced Custom Fields Pro Plugin installieren
2. Theme hochladen und aktivieren
3. Farben in colors.json anpassen (optional)
4. Blöcke im Gutenberg-Editor verwenden

= Konfiguration =

Das Theme nutzt eine zentrale colors.json Datei für die Farbkonfiguration.
Alle Blöcke können über das ACF-Interface individuell angepasst werden.

= Support =

Für Support und Anpassungen wenden Sie sich an den Theme-Autor.

== Changelog ==

= $THEME_VERSION =
* Initial production release
* 15+ ACF-Blöcke verfügbar
* Automatische Block-Registrierung
* Responsive Design optimiert
* Performance-Optimierungen
EOF

# Create installation guide
cat > "$PRODUCTION_THEME/INSTALLATION.txt" << EOF
=== INSTALLATION UND SETUP ===

VORAUSSETZUNGEN:
- WordPress 6.0+
- PHP 8.0+
- Advanced Custom Fields Pro Plugin

INSTALLATION:
1. ACF Pro Plugin installieren und aktivieren
2. Theme-Ordner nach /wp-content/themes/ hochladen
3. Theme im WordPress-Backend aktivieren
4. Farben in colors.json anpassen (optional)

ERSTE SCHRITTE:
1. Neue Seite/Beitrag erstellen
2. Gutenberg-Editor öffnen
3. Block hinzufügen (+) klicken
4. Kategorie "ABF Blocks" wählen
5. Gewünschten Block auswählen und konfigurieren

KONFIGURATION:
- colors.json: Zentrale Farbkonfiguration
- Alle Blöcke über ACF-Interface anpassbar
- Responsive Breakpoints in SCSS definiert

Bei Fragen: Support über den Theme-Autor verfügbar.
EOF

echo "🎨 Setting up block preview structure..."

# Create block previews directory
mkdir -p "$PRODUCTION_THEME/assets/images/block-previews"

# Create placeholder preview images for all blocks
BLOCKS=("headline" "hero" "parallax-content" "parallax-element" "parallax-grid" "styleguide-akkordeon" "styleguide-bild-text" "styleguide-einzelbild" "styleguide-grid" "styleguide-masonry" "styleguide-posts" "styleguide-similar" "styleguide-text-element" "styleguide-trennlinie" "text-block")

for block in "${BLOCKS[@]}"; do
    # Add preview image reference to block.json files if they exist
    if [ -f "$PRODUCTION_THEME/blocks/$block/block.json" ]; then
        # Add preview image path to block.json
        tmp_file=$(mktemp)
        sed 's/"example": {/"icon": "admin-generic",\n    "preview": "assets\/images\/block-previews\/'$block'.png",\n    "example": {/' "$PRODUCTION_THEME/blocks/$block/block.json" > "$tmp_file"
        mv "$tmp_file" "$PRODUCTION_THEME/blocks/$block/block.json"
    fi
    
    echo "Block: $block - Preview: assets/images/block-previews/$block.png" >> "$PRODUCTION_THEME/BLOCK_PREVIEWS.txt"
done

# Create preview instructions
cat > "$PRODUCTION_THEME/PREVIEW_SETUP.txt" << EOF
=== BLOCK PREVIEW SETUP ===

Um professionelle Block-Previews zu erstellen:

1. Erstelle Screenshots der Blöcke (empfohlene Größe: 1200x800px)
2. Speichere sie als PNG-Dateien in: assets/images/block-previews/
3. Verwende folgende Dateinamen:

$(cat "$PRODUCTION_THEME/BLOCK_PREVIEWS.txt")

4. Die Previews werden automatisch im Block-Editor angezeigt

TIPPS FÜR GUTE PREVIEWS:
- Verwende realistische Inhalte
- Zeige typische Anwendungsfälle
- Nutze einheitliches Styling
- Optimiere für Webansicht (JPG/PNG < 200KB)

THEME SCREENSHOT:
Erstelle auch ein screenshot.png (1200x900px) für das Theme-Verzeichnis.
EOF

echo "🔧 Final optimizations..."

# Clean up temporary files and empty directories
find "$PRODUCTION_THEME" -type d -empty -delete 2>/dev/null || true
find "$PRODUCTION_THEME" -name "*.tmp" -delete 2>/dev/null || true

# Set proper file permissions
find "$PRODUCTION_THEME" -type f -exec chmod 644 {} \;
find "$PRODUCTION_THEME" -type d -exec chmod 755 {} \;

# Create production summary
cat > "$PRODUCTION_THEME/PRODUCTION_INFO.txt" << EOF
=== PRODUCTION VERSION CREATED ===

Theme: $THEME_NAME
Version: $THEME_VERSION
Created: $(date)
Author: $THEME_AUTHOR

WHAT WAS CLEANED:
✅ Debug code removed (console.log, var_dump, etc.)
✅ Development comments removed
✅ TODO/FIXME comments removed
✅ .DS_Store files removed
✅ Backup files removed
✅ Documentation folder removed
✅ CSS optimized
✅ Professional theme header created
✅ Installation guide created
✅ Block preview structure prepared

NEXT STEPS:
1. Create block preview images (see PREVIEW_SETUP.txt)
2. Create theme screenshot.png (1200x900px)
3. Test theme installation
4. Review and adjust colors.json if needed
5. Create final ZIP package for distribution

FILES CREATED:
- README.txt (WordPress standard)
- INSTALLATION.txt (setup guide)
- PREVIEW_SETUP.txt (preview instructions)
- BLOCK_PREVIEWS.txt (block list)
- style.css (professional header)

READY FOR CLIENT DELIVERY!
EOF

echo ""
echo "🎉 Production cleanup completed successfully!"
echo ""
echo "📁 Production theme created at: $PRODUCTION_THEME"
echo "📋 Next steps:"
echo "   1. Review PRODUCTION_INFO.txt for summary"
echo "   2. Create block preview images (see PREVIEW_SETUP.txt)"
echo "   3. Create theme screenshot.png"
echo "   4. Test the production version"
echo "   5. Create distribution ZIP"
echo ""
echo "✨ Your theme is ready for professional client delivery!" 