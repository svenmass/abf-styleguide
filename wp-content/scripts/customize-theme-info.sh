#!/bin/bash

# ABF Styleguide Theme - Theme Information Customizer
# Script to easily modify theme metadata and add custom information

set -e

SOURCE_THEME="themes/abf-styleguide"
PRODUCTION_THEME="themes/abf-styleguide-production"

echo "🎨 ABF Styleguide Theme - Information Customizer"
echo ""

# Check which theme to modify
if [ -d "$PRODUCTION_THEME" ]; then
    TARGET_THEME="$PRODUCTION_THEME"
    echo "📁 Modifying: Production Theme ($PRODUCTION_THEME)"
else
    TARGET_THEME="$SOURCE_THEME"
    echo "📁 Modifying: Source Theme ($SOURCE_THEME)"
fi

echo ""

# Theme Information Menu
while true; do
    echo "=== THEME INFORMATION MENU ==="
    echo "1) 📝 Theme Name & Description"
    echo "2) 👤 Author Information"  
    echo "3) 🏷️  Version & Tags"
    echo "4) 🌐 URLs & Links"
    echo "5) 📋 Feature List"
    echo "6) 🔍 View Current Info"
    echo "7) 💾 Save & Exit"
    echo "8) ❌ Exit without saving"
    echo ""
    
    read -p "Choose option (1-8): " choice
    
    case $choice in
        1)
            echo ""
            echo "=== THEME NAME & DESCRIPTION ==="
            
            read -p "Theme Name [current: ABF Styleguide]: " theme_name
            theme_name=${theme_name:-"ABF Styleguide"}
            
            echo "Theme Description (press Enter when done):"
            echo "Current: Ein professionelles WordPress-Theme mit modularen ACF-Blöcken"
            read -p "New Description: " theme_desc
            theme_desc=${theme_desc:-"Ein professionelles WordPress-Theme mit modularen ACF-Blöcken für moderne Websites"}
            
            echo "✅ Theme name set to: $theme_name"
            echo "✅ Description updated"
            ;;
            
        2)
            echo ""
            echo "=== AUTHOR INFORMATION ==="
            
            read -p "Author Name [current: Sven Massanneck]: " author_name
            author_name=${author_name:-"Sven Massanneck"}
            
            read -p "Author Email: " author_email
            read -p "Author Website: " author_website
            read -p "Support Email: " support_email
            
            echo "✅ Author information updated"
            ;;
            
        3)
            echo ""
            echo "=== VERSION & TAGS ==="
            
            read -p "Theme Version [current: 1.0.0]: " theme_version
            theme_version=${theme_version:-"1.0.0"}
            
            echo "WordPress Tags (comma-separated):"
            echo "Current: blocks, custom-blocks, acf, responsive-design"
            read -p "Add more tags: " additional_tags
            
            echo "✅ Version set to: $theme_version"
            echo "✅ Tags updated"
            ;;
            
        4)
            echo ""
            echo "=== URLS & LINKS ==="
            
            read -p "Theme URI (homepage): " theme_uri
            read -p "Demo URL: " demo_url
            read -p "Documentation URL: " docs_url
            read -p "Support URL: " support_url
            
            echo "✅ URLs updated"
            ;;
            
        5)
            echo ""
            echo "=== CUSTOM FEATURES ==="
            
            echo "Add custom features (one per line, empty line to finish):"
            features=()
            while IFS= read -r line; do
                [[ $line ]] || break
                features+=("* $line")
            done
            
            echo "✅ ${#features[@]} custom features added"
            ;;
            
        6)
            echo ""
            echo "=== CURRENT THEME INFORMATION ==="
            if [ -f "$TARGET_THEME/style.css" ]; then
                echo "--- style.css header ---"
                head -20 "$TARGET_THEME/style.css"
            else
                echo "No style.css found"
            fi
            
            if [ -f "$TARGET_THEME/README.txt" ]; then
                echo ""
                echo "--- README.txt ---"
                head -10 "$TARGET_THEME/README.txt"
            fi
            echo ""
            ;;
            
        7)
            echo ""
            echo "💾 Saving theme information..."
            
            # Update style.css header
            if [ -n "$theme_name" ] || [ -n "$theme_desc" ] || [ -n "$author_name" ]; then
                cat > "$TARGET_THEME/style.css" << EOF
<?php
/*
Theme Name: ${theme_name:-"ABF Styleguide"}
Description: ${theme_desc:-"Ein professionelles WordPress-Theme mit modularen ACF-Blöcken für moderne Websites"}
Author: ${author_name:-"Sven Massanneck"}
Version: ${theme_version:-"1.0.0"}
${theme_uri:+Theme URI: $theme_uri}
${author_website:+Author URI: $author_website}
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: blocks, custom-blocks, acf, responsive-design${additional_tags:+, $additional_tags}
Text Domain: abf-styleguide

Features:
- 15+ vorgefertigte ACF-Blöcke
- Automatische Block-Registrierung  
- Dynamische Farbpalette
- Responsive SCSS-Framework
- Performance-optimiert
- Translation-ready
- Modern PHP 8.0+ Codebase
EOF

                # Add custom features if any
                for feature in "${features[@]}"; do
                    echo "- ${feature#* }" >> "$TARGET_THEME/style.css"
                done

                cat >> "$TARGET_THEME/style.css" << EOF

${support_email:+Support Email: $support_email}
${demo_url:+Demo: $demo_url}
${docs_url:+Documentation: $docs_url}
${support_url:+Support: $support_url}
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// This file loads the main stylesheet
function abf_production_styles() {
    wp_enqueue_style('abf-style', get_template_directory_uri() . '/assets/css/main.css', array(), '${theme_version:-"1.0.0"}');
}
add_action('wp_enqueue_scripts', 'abf_production_styles');
?>
EOF
            fi
            
            # Update README.txt if it exists
            if [ -f "$TARGET_THEME/README.txt" ] && [ -n "$theme_name" ]; then
                sed -i '' "1s/.*/=== ${theme_name:-"ABF Styleguide"} ===/" "$TARGET_THEME/README.txt"
                
                if [ -n "$author_name" ]; then
                    sed -i '' "2s/.*/Contributors: $author_name/" "$TARGET_THEME/README.txt"
                fi
                
                if [ -n "$theme_version" ]; then
                    sed -i '' "s/Stable tag:.*/Stable tag: $theme_version/" "$TARGET_THEME/README.txt"
                fi
            fi
            
            echo "✅ Theme information saved to $TARGET_THEME"
            echo "📝 Files updated: style.css, README.txt"
            echo ""
            echo "🎉 Customization complete!"
            break
            ;;
            
        8)
            echo "❌ Exiting without saving"
            break
            ;;
            
        *)
            echo "❌ Invalid option. Please choose 1-8."
            ;;
    esac
    
    echo ""
    read -p "Press Enter to continue..."
    echo ""
done

echo "💡 Tip: Run ./create-theme-package.sh to create updated ZIP package" 