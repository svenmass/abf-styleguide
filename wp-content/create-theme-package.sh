#!/bin/bash

# ABF Styleguide Theme - Final Packaging Script
# Creates a professional ZIP package for client delivery

set -e

# Configuration
PRODUCTION_THEME="themes/abf-styleguide-production"
PACKAGE_NAME="abf-styleguide-theme-v1.0.0"
OUTPUT_DIR="theme-packages"
DATE=$(date +%Y-%m-%d)

echo "📦 Creating final theme package..."

# Check if production theme exists
if [ ! -d "$PRODUCTION_THEME" ]; then
    echo "❌ Error: Production theme not found at $PRODUCTION_THEME"
    echo "   Run ./production-cleanup.sh first!"
    exit 1
fi

# Create output directory
mkdir -p "$OUTPUT_DIR"

# Remove old packages
rm -f "$OUTPUT_DIR/$PACKAGE_NAME"*.zip

echo "✅ Validating production theme..."

# Check required files
REQUIRED_FILES=(
    "style.css"
    "index.php" 
    "functions.php"
    "README.txt"
    "INSTALLATION.txt"
)

for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -f "$PRODUCTION_THEME/$file" ]; then
        echo "❌ Missing required file: $file"
        exit 1
    fi
done

# Check if ACF blocks directory exists
if [ ! -d "$PRODUCTION_THEME/blocks" ]; then
    echo "❌ Missing blocks directory"
    exit 1
fi

# Count blocks
BLOCK_COUNT=$(find "$PRODUCTION_THEME/blocks" -maxdepth 1 -type d | wc -l | xargs)
BLOCK_COUNT=$((BLOCK_COUNT - 1)) # Subtract 1 for the blocks directory itself

echo "✅ Found $BLOCK_COUNT blocks"

# Check for screenshot
if [ -f "$PRODUCTION_THEME/screenshot.png" ]; then
    echo "✅ Theme screenshot found"
else
    echo "⚠️  Warning: No screenshot.png found (recommended for WordPress themes)"
fi

# Check for block previews
PREVIEW_COUNT=$(find "$PRODUCTION_THEME/assets/images/block-previews" -name "*.png" -type f 2>/dev/null | wc -l | xargs)
echo "✅ Found $PREVIEW_COUNT block preview images"

echo "📁 Creating ZIP package..."

# Create ZIP package
cd "$(dirname "$PRODUCTION_THEME")"
THEME_DIR_NAME=$(basename "$PRODUCTION_THEME")

zip -r "../$OUTPUT_DIR/$PACKAGE_NAME-$DATE.zip" "$THEME_DIR_NAME" \
    -x "*.DS_Store" \
    -x "*/.git/*" \
    -x "*/node_modules/*" \
    -x "*.tmp" \
    -q

cd - > /dev/null

# Get package size
PACKAGE_SIZE=$(du -h "$OUTPUT_DIR/$PACKAGE_NAME-$DATE.zip" | cut -f1)

echo "📄 Creating delivery documentation..."

# Create delivery documentation
cat > "$OUTPUT_DIR/$PACKAGE_NAME-DELIVERY-INFO.txt" << EOF
=== ABF STYLEGUIDE THEME - CLIENT DELIVERY PACKAGE ===

Package: $PACKAGE_NAME-$DATE.zip
Size: $PACKAGE_SIZE
Created: $(date)
Theme Version: 1.0.0

=== PACKAGE CONTENTS ===

✅ Complete WordPress Theme
✅ $BLOCK_COUNT Custom ACF Blocks
✅ Professional Documentation
✅ Installation Guide
✅ $PREVIEW_COUNT Block Preview Images
✅ Production-optimized Code

=== INSTALLATION INSTRUCTIONS ===

1. PREREQUISITES:
   - WordPress 6.0 or higher
   - PHP 8.0 or higher
   - Advanced Custom Fields Pro Plugin

2. INSTALLATION:
   - Download and install ACF Pro Plugin
   - Upload theme ZIP via WordPress Admin → Appearance → Themes → Add New
   - Or extract ZIP to /wp-content/themes/
   - Activate the theme

3. CONFIGURATION:
   - Review colors.json for color customization
   - Start building pages with ABF blocks
   - See INSTALLATION.txt in theme for detailed setup

=== INCLUDED BLOCKS ===

1. Headline - Configurable headings with custom styling
2. Hero - Full-width hero sections with video/image backgrounds  
3. Text Element - Flexible text blocks with buttons
4. Image-Text - Two-column layouts with flexible ratios
5. Accordion - Collapsible content sections
6. Grid - Flexible grid layouts
7. Masonry - Pinterest-style layouts  
8. Parallax Elements - Modern scroll effects
9. Separator Lines - Visual dividers
10. Single Image - Optimized image display
11. Post Grids - Automatic post listings
12. Similar Posts - Content discovery
13. Parallax Content - Advanced parallax sections
14. Parallax Grid - Grid with parallax effects
15. Text Block - Standard text formatting

=== SUPPORT ===

For technical support, customizations, or questions:
Contact: Sven Massanneck
Email: [Client contact information]

Theme is ready for production use!

=== FILES INCLUDED ===

Core Theme Files:
- style.css (WordPress theme header)
- functions.php (theme functionality)
- index.php & other templates
- colors.json (color configuration)

Block System:
- /blocks/ directory with all 15 blocks
- Automatic block registration system
- ACF field configurations

Assets:
- /assets/css/ optimized stylesheets
- /assets/js/ production JavaScript
- /assets/fonts/ custom fonts
- /assets/images/ theme images

Documentation:
- README.txt (WordPress standard)
- INSTALLATION.txt (setup guide)
- PREVIEW_SETUP.txt (preview instructions)

=== CUSTOMIZATION NOTES ===

Colors: Edit colors.json to match brand colors
Blocks: All blocks configurable via ACF interface  
Styles: SCSS source files included for advanced customization
Responsive: Mobile-first responsive design
Performance: Optimized for speed and SEO

Ready for immediate deployment!
EOF

# Create quick start guide
cat > "$OUTPUT_DIR/QUICK-START-GUIDE.txt" << EOF
=== QUICK START GUIDE ===

🚀 FASTEST WAY TO GET STARTED:

1. Install ACF Pro Plugin
2. Upload & activate theme
3. Create new page
4. Add ABF blocks from Gutenberg
5. Configure blocks via ACF fields

🎨 CUSTOMIZATION:

- Colors: Edit colors.json file
- Blocks: Configure via ACF interface
- Layout: Use WordPress Full Site Editor

📞 SUPPORT:

Technical questions? Contact theme developer.

That's it - your professional website is ready! 🎉
EOF

echo ""
echo "🎉 Package created successfully!"
echo ""
echo "📦 Package: $OUTPUT_DIR/$PACKAGE_NAME-$DATE.zip ($PACKAGE_SIZE)"
echo "📄 Documentation: $OUTPUT_DIR/$PACKAGE_NAME-DELIVERY-INFO.txt"
echo "🚀 Quick Guide: $OUTPUT_DIR/QUICK-START-GUIDE.txt"
echo ""
echo "✨ Ready for client delivery!"
echo ""
echo "📋 Final checklist:"
echo "   ✅ Theme package created"
echo "   ✅ Documentation included"
echo "   ✅ Installation guide ready"
if [ -f "$PRODUCTION_THEME/screenshot.png" ]; then
    echo "   ✅ Theme screenshot included"
else
    echo "   ⚠️  Consider adding screenshot.png"
fi
echo "   ✅ $BLOCK_COUNT blocks included"
echo "   ✅ $PREVIEW_COUNT block previews ready"
echo ""
echo "🎯 Package is ready for professional client delivery!" 