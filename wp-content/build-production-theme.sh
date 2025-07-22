#!/bin/bash

# ABF Styleguide Theme - Simplified Production Build Script
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

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
VERSION_FILE=".theme-version"

get_current_version() {
    if [ -f "$VERSION_FILE" ]; then
        cat "$VERSION_FILE"
    else
        echo "1.0.0"
    fi
}

increment_version() {
    local version=$1
    local type=$2
    
    local major=$(echo $version | cut -d. -f1)
    local minor=$(echo $version | cut -d. -f2)
    local patch=$(echo $version | cut -d. -f3)
    
    case $type in
        "patch") patch=$((patch + 1));;
        "minor") minor=$((minor + 1)); patch=0;;
        "major") major=$((major + 1)); minor=0; patch=0;;
        *) echo $version; return;;
    esac
    
    echo "$major.$minor.$patch"
}

# Main workflow
echo -e "${GREEN}"
cat << 'EOF'
    ____        _ _     _   ____                _            _   _             
   | __ ) _   _(_) | __| | |  _ \ _ __ ___   __| |_   _  ___| |_(_) ___  _ __  
   |  _ \| | | | | |/ _` | | |_) | '__/ _ \ / _` | | | |/ __| __| |/ _ \| '_ \ 
   | |_) | |_| | | | (_| | |  __/| | | (_) | (_| | |_| | (__| |_| | (_) | | | |
   |____/ \__,_|_|_|\__,_| |_|   |_|  \___/ \__,_|\__,_|\___|\__|_|\___/|_| |_|
                                                                               
   ABF Styleguide Theme - Simple Production Build System
EOF
echo -e "${NC}"

print_info "🚀 Starting production build process..."
print_info "📅 Build started: $(date)"

# Version Management
print_step "VERSION MANAGEMENT"

current_version=$(get_current_version)
print_info "📋 Aktuelle Version: v${current_version}"

echo ""
echo "Welcher Update-Typ?"
echo "1) 🐛 Patch/Bugfix    (v$(increment_version $current_version patch))"
echo "2) ✨ Minor/Feature   (v$(increment_version $current_version minor))"  
echo "3) 🚀 Major/Breaking  (v$(increment_version $current_version major))"
echo "4) ❌ Keine Änderung  (v${current_version})"
echo ""

read -p "Auswahl (1-4): " choice

case $choice in
    1)
        update_type="patch"
        new_version=$(increment_version $current_version patch)
        ;;
    2)
        update_type="minor"
        new_version=$(increment_version $current_version minor)
        ;;
    3) 
        update_type="major"
        new_version=$(increment_version $current_version major)
        ;;
    4)
        update_type="none"
        new_version=$current_version
        ;;
    *)
        print_warning "Ungültige Auswahl. Verwende keine Änderung."
        update_type="none"
        new_version=$current_version
        ;;
esac

if [ "$update_type" != "none" ]; then
    echo "$new_version" > "$VERSION_FILE"
    print_success "Version updated: v${current_version} → v${new_version}"
else
    print_info "Version bleibt unverändert: v${current_version}"
fi

THEME_VERSION="$new_version"

# Prerequisites Check
print_step "CHECKING PREREQUISITES"

if [ ! -d "$SOURCE_THEME" ]; then
    print_error "Source theme not found: $SOURCE_THEME"
    exit 1
fi
print_success "Source theme found"

if [ ! -f "production-cleanup.sh" ]; then
    print_error "production-cleanup.sh not found"
    exit 1
fi
print_success "Production cleanup script found"

chmod +x production-cleanup.sh
print_success "Scripts made executable"

# Step 1: Create Production Version
print_step "STEP 1: CREATING PRODUCTION VERSION"

export THEME_VERSION="$THEME_VERSION"
print_info "Running production cleanup with version v${THEME_VERSION}..."
./production-cleanup.sh

if [ -d "$PRODUCTION_THEME" ]; then
    print_success "Production theme created successfully"
else
    print_error "Production theme creation failed"
    exit 1
fi

# Step 2: CSS Compilation
print_step "STEP 2: CSS COMPILATION"

print_info "Compiling SCSS to CSS in production theme..."
cd "$PRODUCTION_THEME"

if [ -f "package.json" ] && command -v npm &> /dev/null; then
    print_info "Installing dependencies..."
    npm install --silent
    
    print_info "Compiling SCSS..."
    npm run build
    
    if [ -f "assets/css/main.css" ]; then
        file_size=$(du -h assets/css/main.css | cut -f1)
        print_success "CSS compiled successfully! (${file_size})"
    else
        print_error "CSS compilation failed - main.css not found"
        cd ..
        exit 1
    fi
else
    print_warning "npm not available or package.json missing"
    print_info "Manual CSS compilation may be required"
fi

cd ..

# Summary
print_step "BUILD SUMMARY"

echo "📊 Production Build Results:"
echo "  🏷️  Theme Version: v${THEME_VERSION}"
echo "  🎯 Source Theme: $SOURCE_THEME"
echo "  ✨ Production Theme: $PRODUCTION_THEME"
if [ -f "$PRODUCTION_THEME/assets/css/main.css" ]; then
    css_size=$(du -h "$PRODUCTION_THEME/assets/css/main.css" | cut -f1)
    echo "  🎨 CSS Compilation: ✅ Completed (${css_size})"
else
    echo "  🎨 CSS Compilation: ❌ Failed"
fi

if [ -d "$PRODUCTION_THEME/blocks" ]; then
    BLOCK_COUNT=$(find "$PRODUCTION_THEME/blocks" -maxdepth 1 -type d 2>/dev/null | wc -l | tr -d ' ')
    BLOCK_COUNT=$((BLOCK_COUNT - 1))
    echo "  🧩 Blocks Available: $BLOCK_COUNT"
fi

print_step "NEXT STEPS"

echo "📋 To deploy your theme:"
echo "  1. 🚀 Upload the $PRODUCTION_THEME folder to your live site"
echo "  2. 🔄 Activate the theme in WordPress admin"
echo "  3. 🧪 Test all blocks and functionality"
echo "  4. ✅ You're ready to go!"

echo ""
echo "📅 Build completed: $(date)"
print_success "🌟 Production theme build completed successfully! 🌟" 