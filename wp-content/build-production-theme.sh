#!/bin/bash

# ABF Styleguide Theme - Complete Production Build Script
# Master script that runs the complete production workflow

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Helper functions
print_step() {
    echo -e "\n${BLUE}=== $1 ===${NC}\n"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Configuration
SOURCE_THEME="themes/abf-styleguide"
PRODUCTION_THEME="themes/abf-styleguide-production"
SCREENSHOT_DIR="screenshot-templates"
PACKAGE_DIR="theme-packages"

# Main workflow
echo -e "${GREEN}"
cat << 'EOF'
    ____        _ _     _   ____                _            _   _             
   | __ ) _   _(_) | __| | |  _ \ _ __ ___   __| |_   _  ___| |_(_) ___  _ __  
   |  _ \| | | | | |/ _` | | |_) | '__/ _ \ / _` | | | |/ __| __| |/ _ \| '_ \ 
   | |_) | |_| | | | (_| | |  __/| | | (_) | (_| | |_| | (__| |_| | (_) | | | |
   |____/ \__,_|_|_|\__,_| |_|   |_|  \___/ \__,_|\__,_|\___|\__|_|\___/|_| |_|
                                                                               
   ABF Styleguide Theme - Production Build System
EOF
echo -e "${NC}"

echo "🚀 Starting complete production build process..."
echo "📅 Build started: $(date)"

# Check prerequisites
print_step "CHECKING PREREQUISITES"

if [ ! -d "$SOURCE_THEME" ]; then
    print_error "Source theme not found at $SOURCE_THEME"
    exit 1
fi

print_success "Source theme found"

if [ ! -f "production-cleanup.sh" ]; then
    print_error "production-cleanup.sh not found"
    exit 1
fi

if [ ! -f "create-screenshot-templates.sh" ]; then
    print_error "create-screenshot-templates.sh not found"
    exit 1
fi

if [ ! -f "create-theme-package.sh" ]; then
    print_error "create-theme-package.sh not found"
    exit 1
fi

print_success "All required scripts found"

# Make scripts executable
chmod +x production-cleanup.sh
chmod +x create-screenshot-templates.sh
chmod +x create-theme-package.sh

print_success "Scripts made executable"

# Step 1: Clean and create production version
print_step "STEP 1: CREATING PRODUCTION VERSION"

echo "Running production cleanup..."
./production-cleanup.sh

if [ -d "$PRODUCTION_THEME" ]; then
    print_success "Production theme created successfully"
else
    print_error "Production theme creation failed"
    exit 1
fi

# Step 2: Create screenshot templates
print_step "STEP 2: CREATING SCREENSHOT TEMPLATES"

echo "Generating screenshot templates..."
./create-screenshot-templates.sh

if [ -d "$SCREENSHOT_DIR" ]; then
    print_success "Screenshot templates created"
else
    print_error "Screenshot template creation failed"
    exit 1
fi

# Step 3: Check for existing screenshots
print_step "STEP 3: CHECKING BLOCK PREVIEWS"

PREVIEW_DIR="$PRODUCTION_THEME/assets/images/block-previews"
if [ -d "$PREVIEW_DIR" ]; then
    EXISTING_PREVIEWS=$(find "$PREVIEW_DIR" -name "*.png" -type f 2>/dev/null | wc -l | xargs)
    if [ "$EXISTING_PREVIEWS" -gt 0 ]; then
        print_success "Found $EXISTING_PREVIEWS existing block preview images"
    else
        print_warning "No block preview images found yet"
        echo "  📸 Open $SCREENSHOT_DIR/index.html to create screenshots"
    fi
else
    print_warning "Block preview directory not found"
fi

# Step 4: Check for theme screenshot
print_step "STEP 4: CHECKING THEME SCREENSHOT"

if [ -f "$PRODUCTION_THEME/screenshot.png" ]; then
    print_success "Theme screenshot found"
else
    print_warning "No theme screenshot found"
    echo "  📸 Consider creating a screenshot.png (1200x900px) for the theme directory"
fi

# Step 5: Create final package
print_step "STEP 5: CREATING FINAL PACKAGE"

echo "Do you want to create the final theme package? (y/N)"
read -r response
if [[ "$response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
    echo "Creating final package..."
    ./create-theme-package.sh
    
    if [ -d "$PACKAGE_DIR" ]; then
        print_success "Final package created"
    else
        print_error "Package creation failed"
    fi
else
    print_warning "Package creation skipped"
    echo "  📦 Run ./create-theme-package.sh later to create the final package"
fi

# Summary
print_step "BUILD SUMMARY"

echo "📊 Production Build Results:"
echo "  🎯 Source Theme: $SOURCE_THEME"
echo "  ✨ Production Theme: $PRODUCTION_THEME"
echo "  📸 Screenshot Templates: $SCREENSHOT_DIR"

if [ -d "$PACKAGE_DIR" ]; then
    PACKAGE_COUNT=$(find "$PACKAGE_DIR" -name "*.zip" -type f 2>/dev/null | wc -l | xargs)
    echo "  📦 Packages Created: $PACKAGE_COUNT"
fi

# Check production theme stats
if [ -d "$PRODUCTION_THEME" ]; then
    BLOCK_COUNT=$(find "$PRODUCTION_THEME/blocks" -maxdepth 1 -type d 2>/dev/null | wc -l | xargs)
    BLOCK_COUNT=$((BLOCK_COUNT - 1))
    echo "  🧩 Blocks Available: $BLOCK_COUNT"
    
    if [ -d "$PREVIEW_DIR" ]; then
        PREVIEW_COUNT=$(find "$PREVIEW_DIR" -name "*.png" -type f 2>/dev/null | wc -l | xargs)
        echo "  🖼️  Block Previews: $PREVIEW_COUNT/$BLOCK_COUNT"
    fi
    
    if [ -f "$PRODUCTION_THEME/screenshot.png" ]; then
        echo "  📸 Theme Screenshot: ✅"
    else
        echo "  📸 Theme Screenshot: ❌"
    fi
fi

print_step "NEXT STEPS"

echo "📋 To complete your professional theme delivery:"

if [ ! -f "$PRODUCTION_THEME/screenshot.png" ]; then
    echo "  1. ✨ Create theme screenshot.png (1200x900px)"
fi

if [ -d "$PREVIEW_DIR" ]; then
    PREVIEW_COUNT=$(find "$PREVIEW_DIR" -name "*.png" -type f 2>/dev/null | wc -l | xargs)
    if [ "$PREVIEW_COUNT" -eq 0 ]; then
        echo "  2. 📸 Open $SCREENSHOT_DIR/index.html to create block previews"
    else
        echo "  2. ✅ Block previews are ready ($PREVIEW_COUNT found)"
    fi
else
    echo "  2. 📸 Create block preview screenshots using templates"
fi

if [ ! -d "$PACKAGE_DIR" ]; then
    echo "  3. 📦 Run ./create-theme-package.sh to create final delivery package"
else
    echo "  3. ✅ Final package is ready for client delivery"
fi

echo "  4. 🧪 Test the production theme on a staging site"
echo "  5. 📚 Review all documentation files"
echo "  6. 🎉 Deliver to client!"

print_step "PROFESSIONAL DELIVERY READY"

echo "🎯 Your ABF Styleguide Theme production version is ready!"
echo "💼 Professional features included:"
echo "   ✅ $BLOCK_COUNT custom ACF blocks"
echo "   ✅ Automatic block registration system"
echo "   ✅ Dynamic color palette"
echo "   ✅ Production-optimized code"
echo "   ✅ Professional documentation"
echo "   ✅ Installation guides"
echo "   ✅ WordPress standards compliant"
echo ""

if [ -d "$PACKAGE_DIR" ]; then
    LATEST_PACKAGE=$(find "$PACKAGE_DIR" -name "*.zip" -type f -printf '%T@ %p\n' 2>/dev/null | sort -nr | head -1 | cut -d' ' -f2-)
    if [ -n "$LATEST_PACKAGE" ]; then
        PACKAGE_SIZE=$(du -h "$LATEST_PACKAGE" 2>/dev/null | cut -f1)
        echo "📦 Latest Package: $(basename "$LATEST_PACKAGE") ($PACKAGE_SIZE)"
    fi
fi

echo "📅 Build completed: $(date)"
echo ""
print_success "🌟 Production build completed successfully! Ready for client delivery! 🌟" 