#!/bin/bash

# ABF Styleguide Theme - Screenshot Template Generator
# Creates HTML templates for easy block screenshot creation

set -e

PRODUCTION_THEME="themes/abf-styleguide-production"
SCREENSHOT_DIR="screenshot-templates"
TEMPLATE_WIDTH="1200"
TEMPLATE_HEIGHT="800"

echo "📸 Creating screenshot templates for blocks..."

# Check if production theme exists
if [ ! -d "$PRODUCTION_THEME" ]; then
    echo "❌ Error: Production theme not found. Run production-cleanup.sh first!"
    exit 1
fi

# Create screenshot directory
mkdir -p "$SCREENSHOT_DIR"

# Base HTML template
create_base_template() {
    local block_name=$1
    local block_title=$2
    local block_content=$3
    
    cat > "$SCREENSHOT_DIR/${block_name}-preview.html" << EOF
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$block_title - Block Preview</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f0f1;
            padding: 40px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .preview-container {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 60px 40px;
            width: ${TEMPLATE_WIDTH}px;
            min-height: ${TEMPLATE_HEIGHT}px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .preview-header {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }
        
        .block-demo {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        /* Block-specific styles */
        .headline { 
            font-size: 48px; 
            font-weight: 700; 
            color: #333; 
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .subheadline { 
            font-size: 24px; 
            font-weight: 400; 
            color: #666; 
            margin-bottom: 30px;
        }
        
        .text-content { 
            font-size: 18px; 
            line-height: 1.6; 
            color: #444; 
            margin-bottom: 30px;
            max-width: 700px;
        }
        
        .button {
            display: inline-block;
            background: #66a98c;
            color: white;
            padding: 15px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: background 0.3s;
            align-self: flex-start;
        }
        
        .button:hover { background: #5a9378; }
        
        .image-placeholder {
            width: 300px;
            height: 200px;
            background: linear-gradient(135deg, #66a98c, #c50d14);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin: 20px 0;
        }
        
        .grid-demo {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .grid-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #66a98c;
        }
        
        .separator {
            height: 3px;
            background: linear-gradient(90deg, #66a98c, #c50d14);
            border-radius: 2px;
            margin: 40px 0;
            width: 100%;
        }
        
        .accordion-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        
        .accordion-header {
            background: #f8f9fa;
            padding: 20px;
            font-weight: 600;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .accordion-content {
            padding: 20px;
            color: #666;
            line-height: 1.6;
        }
        
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }
        
        .hero-demo {
            background: linear-gradient(135deg, rgba(102, 169, 140, 0.9), rgba(197, 13, 20, 0.9)),
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><rect width="1200" height="800" fill="%23f0f0f1"/><text x="600" y="400" text-anchor="middle" dy="0.3em" font-family="Arial" font-size="48" fill="%23666">Hero Background</text></svg>');
            background-size: cover;
            background-position: center;
            padding: 80px 40px;
            color: white;
            text-align: center;
            border-radius: 8px;
        }
        
        .parallax-demo {
            background: linear-gradient(45deg, #66a98c 0%, #c50d14 100%);
            padding: 60px 40px;
            color: white;
            text-align: center;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .parallax-demo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(45deg); }
            50% { transform: translateY(-20px) rotate(45deg); }
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">$block_title Block - ABF Styleguide Theme</div>
        <div class="block-demo">
            $block_content
        </div>
    </div>
</body>
</html>
EOF
}

echo "  - Creating block preview templates..."

# Function to create block template
create_block_template() {
    local block_name="$1"
    local title="$2" 
    local content="$3"
    
    create_base_template "$block_name" "$title" "$content"
    echo "    ✅ $block_name-preview.html created"
}

# Define and create block templates (shell-compatible version)
create_block_template "headline" "Headline" "<div class='headline'>Professionelle Überschrift</div><div class='subheadline'>Mit anpassbarer Typografie und Farben</div>"

create_block_template "hero" "Hero" "<div class='hero-demo'><div class='headline' style='color: white; margin-bottom: 20px;'>Willkommen bei ABF</div><div class='text-content' style='color: white; text-align: center; margin-bottom: 30px;'>Ihr Partner für moderne WordPress-Lösungen mit professionellen Blocks und responsivem Design.</div><a href='#' class='button' style='background: rgba(255,255,255,0.2); color: white;'>Mehr erfahren</a></div>"

create_block_template "styleguide-text-element" "Text-Element" "<div class='headline' style='font-size: 36px;'>Flexibles Text-Element</div><div class='text-content'>Ein vielseitiges Text-Element mit konfigurierbarer Typografie, Farben und optionalem Button. Perfekt für Content-Bereiche und Call-to-Actions.</div><a href='#' class='button'>Jetzt starten</a>"

create_block_template "styleguide-bild-text" "Bild-Text" "<div class='two-column'><div><div class='image-placeholder'>Bild-Bereich</div></div><div><div class='headline' style='font-size: 32px; margin-bottom: 20px;'>Bild-Text Kombination</div><div class='text-content' style='margin-bottom: 20px;'>Zweispaltiges Layout mit flexiblen Spaltenaufteilungen und anpassbaren Inhalten.</div><a href='#' class='button'>Mehr erfahren</a></div></div>"

create_block_template "styleguide-akkordeon" "Akkordeon" "<div class='accordion-item'><div class='accordion-header'>Erste Sektion <span>+</span></div><div class='accordion-content'>Aufklappbarer Inhalt mit professioneller Animation und benutzerfreundlicher Bedienung.</div></div><div class='accordion-item'><div class='accordion-header'>Zweite Sektion <span>+</span></div></div><div class='accordion-item'><div class='accordion-header'>Dritte Sektion <span>+</span></div></div>"

create_block_template "styleguide-grid" "Grid-Layout" "<div class='headline' style='text-align: center; margin-bottom: 30px;'>Flexibles Grid-System</div><div class='grid-demo'><div class='grid-item'><h4 style='margin-bottom: 10px; color: #66a98c;'>Feature 1</h4><p>Responsive Grid-Layouts für verschiedene Inhaltstypen.</p></div><div class='grid-item'><h4 style='margin-bottom: 10px; color: #66a98c;'>Feature 2</h4><p>Automatische Anpassung an Bildschirmgrößen.</p></div><div class='grid-item'><h4 style='margin-bottom: 10px; color: #66a98c;'>Feature 3</h4><p>Konfigurierbare Spaltenanzahl und Abstände.</p></div></div>"

create_block_template "styleguide-masonry" "Masonry-Layout" "<div class='headline' style='text-align: center; margin-bottom: 30px;'>Masonry Grid</div><div style='columns: 3; column-gap: 20px;'><div class='grid-item' style='margin-bottom: 20px; break-inside: avoid;'><h4 style='color: #66a98c; margin-bottom: 10px;'>Element 1</h4><p>Pinterest-Style Layout mit automatischer Höhenanpassung.</p></div><div class='grid-item' style='margin-bottom: 20px; break-inside: avoid; padding: 30px;'><h4 style='color: #c50d14; margin-bottom: 10px;'>Element 2</h4><p>Perfekt für Galerien, Portfolio und Content-Sammlungen.</p></div><div class='grid-item' style='margin-bottom: 20px; break-inside: avoid;'><h4 style='color: #66a98c; margin-bottom: 10px;'>Element 3</h4><p>Responsive und benutzerfreundlich.</p></div></div>"

create_block_template "styleguide-trennlinie" "Trennlinie" "<div class='text-content' style='text-align: center;'>Bereich oberhalb der Trennlinie</div><div class='separator'></div><div class='text-content' style='text-align: center;'>Bereich unterhalb der Trennlinie</div><div style='text-align: center; color: #666; font-size: 14px; margin-top: 20px;'>Konfigurierbare Dicke, Farbe und Breite</div>"

create_block_template "styleguide-einzelbild" "Einzelbild" "<div style='text-align: center;'><div class='image-placeholder' style='width: 500px; height: 300px; margin: 0 auto 20px;'>Optimiertes Einzelbild</div><div class='text-content' style='text-align: center; margin: 0 auto;'>Professionelle Bilddarstellung mit Lightbox-Funktion und responsiver Optimierung.</div></div>"

create_block_template "parallax-element" "Parallax Element" "<div class='parallax-demo'><div class='headline' style='color: white; position: relative; z-index: 2;'>Parallax-Effekt</div><div class='text-content' style='color: white; text-align: center; position: relative; z-index: 2;'>Moderne Scroll-Animation für eindrucksvolle visuelle Effekte.</div></div>"

create_block_template "parallax-content" "Parallax Content" "<div class='parallax-demo'><div class='headline' style='color: white; position: relative; z-index: 2;'>Parallax Content</div><div class='text-content' style='color: white; text-align: center; position: relative; z-index: 2;'>Vollständige Parallax-Sektion mit Hintergrund-Animationen und Premium-Design.</div><a href='#' class='button' style='background: rgba(255,255,255,0.2); color: white; position: relative; z-index: 2;'>Entdecken</a></div>"

create_block_template "parallax-grid" "Parallax Grid" "<div class='parallax-demo' style='padding: 40px;'><div class='headline' style='color: white; text-align: center; margin-bottom: 30px; position: relative; z-index: 2;'>Parallax Grid</div><div class='grid-demo' style='position: relative; z-index: 2;'><div style='background: rgba(255,255,255,0.1); padding: 20px; border-radius: 8px; color: white;'><h4>Grid Item 1</h4><p>Kombiniert Grid-Layout mit Parallax-Effekten.</p></div><div style='background: rgba(255,255,255,0.1); padding: 20px; border-radius: 8px; color: white;'><h4>Grid Item 2</h4><p>Für moderne, dynamische Layouts.</p></div></div></div>"

create_block_template "text-block" "Text Block" "<div class='headline' style='font-size: 36px;'>Standard Text Block</div><div class='text-content'>Ein klassischer Text-Block mit professioneller Typografie und responsivem Design. Ideal für längere Textinhalte und Artikel.</div><div class='text-content' style='color: #666;'>Unterstützt alle Standard-Formatierungen und ist für optimale Lesbarkeit optimiert.</div>"

create_block_template "styleguide-posts" "Post Grid" "<div class='headline' style='text-align: center; margin-bottom: 30px;'>Aktuelle Beiträge</div><div class='grid-demo'><div class='grid-item'><div class='image-placeholder' style='width: 100%; height: 120px; margin-bottom: 15px;'>Beitragsbild</div><h4 style='color: #333; margin-bottom: 10px;'>Beitrag 1</h4><p style='color: #666; font-size: 14px;'>Automatische Auflistung der neuesten Beiträge...</p></div><div class='grid-item'><div class='image-placeholder' style='width: 100%; height: 120px; margin-bottom: 15px;'>Beitragsbild</div><h4 style='color: #333; margin-bottom: 10px;'>Beitrag 2</h4><p style='color: #666; font-size: 14px;'>Mit konfigurierbarer Anzahl und Kategorien...</p></div></div>"

create_block_template "styleguide-similar" "Ähnliche Beiträge" "<div class='headline' style='font-size: 28px; margin-bottom: 30px;'>Ähnliche Beiträge</div><div style='border-top: 2px solid #66a98c; padding-top: 20px;'><div style='display: flex; gap: 20px; margin-bottom: 15px;'><div class='image-placeholder' style='width: 80px; height: 60px; flex-shrink: 0;'>Thumb</div><div><h4 style='color: #333; margin-bottom: 5px; font-size: 16px;'>Verwandter Artikel 1</h4><p style='color: #666; font-size: 14px;'>Kurze Beschreibung...</p></div></div><div style='display: flex; gap: 20px;'><div class='image-placeholder' style='width: 80px; height: 60px; flex-shrink: 0;'>Thumb</div><div><h4 style='color: #333; margin-bottom: 5px; font-size: 16px;'>Verwandter Artikel 2</h4><p style='color: #666; font-size: 14px;'>Intelligente Content-Empfehlungen...</p></div></div></div>"

# Create index page for all previews
cat > "$SCREENSHOT_DIR/index.html" << EOF
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABF Styleguide - Block Preview Gallery</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f0f1;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
        }
        .block-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        .block-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .block-card:hover {
            transform: translateY(-4px);
        }
        .block-preview {
            height: 200px;
            overflow: hidden;
        }
        .block-preview iframe {
            width: 100%;
            height: 300px;
            border: none;
            transform: scale(0.67);
            transform-origin: top left;
        }
        .block-info {
            padding: 20px;
        }
        .block-info h3 {
            margin: 0 0 10px;
            color: #333;
        }
        .block-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .actions {
            padding: 0 20px 20px;
        }
        .btn {
            display: inline-block;
            background: #66a98c;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 10px;
        }
        .btn:hover {
            background: #5a9378;
        }
        .btn-secondary {
            background: #666;
        }
        .btn-secondary:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ABF Styleguide - Block Preview Gallery</h1>
        <p>Alle verfügbaren Blöcke im Überblick. Klicke auf die Links, um Screenshots zu erstellen.</p>
    </div>

    <div class="block-grid">
EOF

# Add block cards to index (shell-compatible version)
BLOCKS="headline hero styleguide-text-element styleguide-bild-text styleguide-akkordeon styleguide-grid styleguide-masonry styleguide-trennlinie styleguide-einzelbild parallax-element parallax-content parallax-grid text-block styleguide-posts styleguide-similar"
TITLES="Headline Hero Text-Element Bild-Text Akkordeon Grid-Layout Masonry-Layout Trennlinie Einzelbild 'Parallax Element' 'Parallax Content' 'Parallax Grid' 'Text Block' 'Post Grid' 'Ähnliche Beiträge'"

set -- $BLOCKS
titles_array=($TITLES)

for i in $(seq 1 $#); do
    block_name=$(eval echo \$$i)
    title_index=$((i - 1))
    title=${titles_array[$title_index]}
    
    cat >> "$SCREENSHOT_DIR/index.html" << EOF
        <div class="block-card">
            <div class="block-preview">
                <iframe src="${block_name}-preview.html"></iframe>
            </div>
            <div class="block-info">
                <h3>$title Block</h3>
                <p>Block-Name: $block_name</p>
            </div>
            <div class="actions">
                <a href="${block_name}-preview.html" class="btn" target="_blank">Vollbild öffnen</a>
                <a href="${block_name}-preview.html" class="btn btn-secondary" target="_blank">Screenshot erstellen</a>
            </div>
        </div>
EOF
done

cat >> "$SCREENSHOT_DIR/index.html" << EOF
    </div>

    <div style="text-align: center; margin-top: 50px; padding-top: 30px; border-top: 1px solid #ddd;">
        <h3>Screenshot-Anweisungen</h3>
        <p>1. Öffne jeden Block im Vollbild</p>
        <p>2. Erstelle Screenshots (empfohlen: 1200x800px)</p>
        <p>3. Speichere als PNG in: assets/images/block-previews/</p>
        <p>4. Verwende Dateinamen: [block-name].png</p>
    </div>
</body>
</html>
EOF

echo ""
echo "📸 Screenshot templates created successfully!"
echo ""
echo "📁 Templates directory: $SCREENSHOT_DIR/"
echo "🏠 Gallery page: $SCREENSHOT_DIR/index.html"
echo ""
echo "📋 Next steps:"
echo "   1. Open $SCREENSHOT_DIR/index.html in your browser"
echo "   2. Click 'Vollbild öffnen' for each block"
echo "   3. Take screenshots (recommended: 1200x800px)"
echo "   4. Save as PNG files in: $PRODUCTION_THEME/assets/images/block-previews/"
echo "   5. Use filenames: [block-name].png"
echo ""
echo "🎯 Professional block previews ready for creation!" 