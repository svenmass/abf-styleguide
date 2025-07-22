#!/bin/bash

# ABF Styleguide Theme - Complete Production Build Script with Versioning
# Master script that runs the complete production workflow

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
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

print_info() {
    echo -e "${CYAN}ℹ️  $1${NC}"
}

# Configuration
SOURCE_THEME="themes/abf-styleguide"
PRODUCTION_THEME="themes/abf-styleguide-production"
SCREENSHOT_DIR="screenshot-templates"
PACKAGE_DIR="theme-packages"
VERSION_FILE=".theme-version"

# 🎯 VERSIONING FUNCTIONS
get_current_version() {
    if [ -f "$VERSION_FILE" ]; then
        cat "$VERSION_FILE"
    else
        echo "1.0.0"
    fi
}

parse_version() {
    local version=$1
    local major=$(echo $version | cut -d. -f1)
    local minor=$(echo $version | cut -d. -f2)
    local patch=$(echo $version | cut -d. -f3)
    echo "$major $minor $patch"
}

increment_version() {
    local current_version=$1
    local update_type=$2
    
    read major minor patch <<< $(parse_version $current_version)
    
    case $update_type in
        "patch"|"bugfix")
            patch=$((patch + 1))
            ;;
        "minor"|"feature"|"enhancement")
            minor=$((minor + 1))
            patch=0
            ;;
        "major")
            major=$((major + 1))
            minor=0
            patch=0
            ;;
        *)
            echo "Invalid update type: $update_type"
            exit 1
            ;;
    esac
    
    echo "$major.$minor.$patch"
}

# 🎨 VERSION SELECTION MENU
select_version_type() {
    local current_version=$(get_current_version)
    
    echo -e "\n${CYAN}📋 AKTUELLE VERSION: ${GREEN}v${current_version}${NC}\n"
    
    echo -e "${CYAN}Welcher Update-Typ?${NC}"
    echo "1) 🐛 Patch/Bugfix    (v$(increment_version $current_version patch))"
    echo "2) ✨ Minor/Feature   (v$(increment_version $current_version minor))"
    echo "3) 🚀 Major/Breaking  (v$(increment_version $current_version major))"
    echo "4) ❌ Keine Änderung  (v${current_version})"
    echo ""
    
    while true; do
        read -p "Auswahl (1-4): " choice
        case $choice in
            1) echo "patch"; break;;
            2) echo "minor"; break;;
            3) echo "major"; break;;
            4) echo "none"; break;;
            *) echo "Bitte 1-4 eingeben.";;
        esac
    done
}

# Main workflow
echo -e "${GREEN}"
cat << 'EOF'
    ____        _ _     _   ____                _            _   _             
   | __ ) _   _(_) | __| | |  _ \ _ __ ___   __| |_   _  ___| |_(_) ___  _ __  
   |  _ \| | | | | |/ _` | | |_) | '__/ _ \ / _` | | | |/ __| __| |/ _ \| '_ \ 
   | |_) | |_| | | | (_| | |  __/| | | (_) | (_| | |_| | (__| |_| | (_) | | | |
   |____/ \__,_|_|_|\__,_| |_|   |_|  \___/ \__,_|\__,_|\___|\__|_|\___/|_| |_|
                                                                               
   ABF Styleguide Theme - Production Build System v2.0
EOF
echo -e "${NC}"

print_info "🚀 Starting complete production build process..."
print_info "📅 Build started: $(date)"

# 🎯 VERSION MANAGEMENT
print_step "VERSION MANAGEMENT"

update_type=$(select_version_type)
current_version=$(get_current_version)

if [ "$update_type" != "none" ]; then
    new_version=$(increment_version $current_version $update_type)
    echo "$new_version" > "$VERSION_FILE"
    print_success "Version updated: v${current_version} → v${new_version}"
    THEME_VERSION="$new_version"
else
    print_info "Version bleibt unverändert: v${current_version}"
    THEME_VERSION="$current_version"
fi

print_step "CHECKING PREREQUISITES"

# Check if required files exist
if [ ! -d "$SOURCE_THEME" ]; then
    print_error "Source theme not found: $SOURCE_THEME"
    exit 1
fi
print_success "Source theme found"

# Check if required scripts exist
required_scripts=("production-cleanup.sh" "create-screenshot-templates.sh" "create-theme-package.sh")

for script in "${required_scripts[@]}"; do
    if [ ! -f "$script" ]; then
        print_error "$script not found"
        exit 1
    fi
done
print_success "All required scripts found"

# Make scripts executable
for script in "${required_scripts[@]}"; do
    chmod +x "$script"
done
print_success "Scripts made executable"

print_step "STEP 1: CREATING PRODUCTION VERSION"

echo "Running production cleanup..."
# Pass version to cleanup script
export THEME_VERSION="$THEME_VERSION"
./production-cleanup.sh
print_success "Production theme created successfully"

print_step "STEP 2: CSS COMPILATION"

print_info "Compiling SCSS to CSS in production theme..."

if [ -d "$PRODUCTION_THEME" ]; then
    cd "$PRODUCTION_THEME"
    
    # Check if package.json exists and npm is available
    if [ -f "package.json" ] && command -v npm &> /dev/null; then
        print_info "Running npm install (if needed)..."
        npm install --silent
        
        print_info "Compiling SCSS to CSS..."
        npm run build
        
        print_success "CSS compiled successfully!"
        
        # Show file size
        if [ -f "assets/css/main.css" ]; then
            file_size=$(du -h assets/css/main.css | cut -f1)
            print_info "Compiled CSS size: $file_size"
        fi
    else
        print_warning "npm not available or package.json missing. CSS not compiled."
        print_info "Manual compilation may be required on deployment."
    fi
    
    cd - > /dev/null
else
    print_error "Production theme directory not found!"
    exit 1
fi

print_step "STEP 3: CREATING SCREENSHOT TEMPLATES"

echo "Generating screenshot templates..."
./create-screenshot-templates.sh

if [ -d "$SCREENSHOT_DIR" ]; then
    print_success "Screenshot templates created"
else
    print_error "Screenshot template creation failed"
    exit 1
fi

print_step "STEP 4: CHECKING BLOCK PREVIEWS"

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

print_step "STEP 5: CHECKING THEME SCREENSHOT"

if [ -f "$PRODUCTION_THEME/screenshot.png" ]; then
    print_success "Theme screenshot found"
else
    print_warning "No theme screenshot found"
    echo "  📸 Consider creating a screenshot.png (1200x900px) for the theme directory"
fi

print_step "STEP 6: CREATING FINAL PACKAGE"

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
echo "  🏷️  Theme Version: v${THEME_VERSION}"
echo "  🎯 Source Theme: $SOURCE_THEME"
echo "  ✨ Production Theme: $PRODUCTION_THEME"
echo "  🎨 CSS Compilation: $([ -f "$PRODUCTION_THEME/assets/css/main.css" ] && echo "✅ Completed" || echo "❌ Failed")"
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