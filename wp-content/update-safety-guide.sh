#!/bin/bash

# ABF Styleguide Theme - Update Safety & Maintenance System
# Protects existing block functionality during theme updates

set -e

SOURCE_THEME="themes/abf-styleguide"
BACKUP_DIR="theme-backups"
SAFETY_DIR="update-safety"

echo "🛡️ ABF Styleguide Theme - Update Safety System"
echo ""

# Create safety directories
mkdir -p "$BACKUP_DIR"
mkdir -p "$SAFETY_DIR"

# Main menu
while true; do
    echo "=== UPDATE SAFETY MENU ==="
    echo "1) 🔒 Create Safety Backup"
    echo "2) 📋 Generate Block Compatibility Report"
    echo "3) 🧪 Test Block Functionality"
    echo "4) 🔍 Compare with Previous Version"  
    echo "5) 📖 View Update Guidelines"
    echo "6) 🚨 Fix ACF Block Issues"
    echo "7) ❌ Exit"
    echo ""
    
    read -p "Choose option (1-7): " choice
    
    case $choice in
        1)
            echo ""
            echo "🔒 Creating Safety Backup..."
            
            TIMESTAMP=$(date +%Y%m%d_%H%M%S)
            BACKUP_NAME="abf-styleguide-backup-$TIMESTAMP"
            
            if [ -d "$SOURCE_THEME" ]; then
                cp -r "$SOURCE_THEME" "$BACKUP_DIR/$BACKUP_NAME"
                
                # Create backup info
                cat > "$BACKUP_DIR/$BACKUP_NAME/BACKUP_INFO.txt" << EOF
=== ABF STYLEGUIDE BACKUP ===
Created: $(date)
Theme Version: $(grep -o 'Version: [0-9.]*' "$SOURCE_THEME/style.css" 2>/dev/null || echo "Unknown")
Purpose: Pre-update safety backup

Block Count: $(find "$SOURCE_THEME/blocks" -maxdepth 1 -type d 2>/dev/null | wc -l | xargs)

This backup contains the complete theme structure
before any modifications or updates.

To restore:
1. Stop WordPress
2. Replace current theme with this backup
3. Restart WordPress
4. Test all blocks
EOF
                
                echo "✅ Backup created: $BACKUP_DIR/$BACKUP_NAME"
                echo "📁 Backup contains complete theme with all blocks"
                
                # Create restore script
                cat > "$BACKUP_DIR/restore-$BACKUP_NAME.sh" << EOF
#!/bin/bash
echo "🔄 Restoring ABF Styleguide from backup..."
echo "Timestamp: $TIMESTAMP"

if [ -d "$SOURCE_THEME" ]; then
    echo "Creating current backup before restore..."
    mv "$SOURCE_THEME" "${SOURCE_THEME}-replaced-$(date +%Y%m%d_%H%M%S)"
fi

cp -r "$BACKUP_DIR/$BACKUP_NAME" "$SOURCE_THEME"
echo "✅ Theme restored from backup!"
echo "🔧 Please check all blocks in WordPress admin"
EOF
                
                chmod +x "$BACKUP_DIR/restore-$BACKUP_NAME.sh"
                echo "📜 Restore script: $BACKUP_DIR/restore-$BACKUP_NAME.sh"
                
            else
                echo "❌ Source theme not found at $SOURCE_THEME"
            fi
            ;;
            
        2)
            echo ""
            echo "📋 Generating Block Compatibility Report..."
            
            REPORT_FILE="$SAFETY_DIR/block-compatibility-$(date +%Y%m%d).txt"
            
            cat > "$REPORT_FILE" << EOF
=== ABF STYLEGUIDE BLOCK COMPATIBILITY REPORT ===
Generated: $(date)
Theme Location: $SOURCE_THEME

EOF

            if [ -d "$SOURCE_THEME/blocks" ]; then
                echo "BLOCK ANALYSIS:" >> "$REPORT_FILE"
                echo "" >> "$REPORT_FILE"
                
                for block_dir in "$SOURCE_THEME/blocks"/*; do
                    if [ -d "$block_dir" ]; then
                        block_name=$(basename "$block_dir")
                        echo "Block: $block_name" >> "$REPORT_FILE"
                        
                        # Check required files
                        files_status=""
                        [ -f "$block_dir/block.json" ] && files_status+="✅ block.json " || files_status+="❌ block.json "
                        [ -f "$block_dir/template.php" ] && files_status+="✅ template.php " || files_status+="❌ template.php "
                        [ -f "$block_dir/fields.php" ] && files_status+="✅ fields.php " || files_status+="❌ fields.php "
                        [ -f "$block_dir/style.scss" ] && files_status+="✅ style.scss" || files_status+="❌ style.scss"
                        
                        echo "  Files: $files_status" >> "$REPORT_FILE"
                        
                        # Check for potential issues
                        if [ -f "$block_dir/template.php" ]; then
                            if grep -q "get_field" "$block_dir/template.php"; then
                                echo "  ✅ Uses ACF get_field() function" >> "$REPORT_FILE"
                            else
                                echo "  ⚠️  No ACF fields detected" >> "$REPORT_FILE"
                            fi
                            
                            if grep -q "<?php" "$block_dir/template.php"; then
                                echo "  ✅ Valid PHP template" >> "$REPORT_FILE"
                            else
                                echo "  ❌ Missing PHP opening tag" >> "$REPORT_FILE"
                            fi
                        fi
                        
                        echo "" >> "$REPORT_FILE"
                    fi
                done
                
                # Summary
                TOTAL_BLOCKS=$(find "$SOURCE_THEME/blocks" -maxdepth 1 -type d | wc -l | xargs)
                TOTAL_BLOCKS=$((TOTAL_BLOCKS - 1))
                echo "SUMMARY:" >> "$REPORT_FILE"
                echo "Total Blocks: $TOTAL_BLOCKS" >> "$REPORT_FILE"
                echo "Report saved to: $REPORT_FILE" >> "$REPORT_FILE"
                
                echo "✅ Report generated: $REPORT_FILE"
                echo "📊 Found $TOTAL_BLOCKS blocks"
                
            else
                echo "❌ No blocks directory found"
            fi
            ;;
            
        3)
            echo ""
            echo "🧪 Block Functionality Test..."
            
            if [ -d "$SOURCE_THEME/blocks" ]; then
                echo "Testing block templates for common issues..."
                
                issues_found=0
                
                for block_dir in "$SOURCE_THEME/blocks"/*; do
                    if [ -d "$block_dir" ]; then
                        block_name=$(basename "$block_dir")
                        echo "Testing: $block_name"
                        
                        # Test 1: PHP Syntax
                        if [ -f "$block_dir/template.php" ]; then
                            if php -l "$block_dir/template.php" > /dev/null 2>&1; then
                                echo "  ✅ PHP syntax valid"
                            else
                                echo "  ❌ PHP syntax error detected"
                                ((issues_found++))
                            fi
                        fi
                        
                        # Test 2: Block.json validation
                        if [ -f "$block_dir/block.json" ]; then
                            if python3 -m json.tool "$block_dir/block.json" > /dev/null 2>&1; then
                                echo "  ✅ JSON valid"
                            else
                                echo "  ❌ JSON syntax error"
                                ((issues_found++))
                            fi
                        fi
                        
                        # Test 3: Empty template check (fixes ACF error)
                        if [ -f "$block_dir/template.php" ]; then
                            if grep -q "<div\|<section\|<article\|<span\|<p\|<h[1-6]" "$block_dir/template.php"; then
                                echo "  ✅ Contains HTML elements"
                            else
                                echo "  ⚠️  No HTML elements found - may cause ACF errors"
                                ((issues_found++))
                            fi
                        fi
                        
                        echo ""
                    fi
                done
                
                if [ $issues_found -eq 0 ]; then
                    echo "🎉 All tests passed! No issues found."
                else
                    echo "⚠️  Found $issues_found potential issues"
                    echo "💡 Run option 6 to fix ACF block issues"
                fi
                
            else
                echo "❌ No blocks directory found"
            fi
            ;;
            
        4)
            echo ""
            echo "🔍 Version Comparison..."
            
            if [ -d "$BACKUP_DIR" ]; then
                echo "Available backups:"
                ls -la "$BACKUP_DIR" | grep abf-styleguide-backup
                echo ""
                
                read -p "Enter backup name to compare with: " backup_name
                
                if [ -d "$BACKUP_DIR/$backup_name" ]; then
                    echo "Comparing current version with backup..."
                    
                    # Compare block structure
                    echo "Block differences:"
                    diff -r "$SOURCE_THEME/blocks" "$BACKUP_DIR/$backup_name/blocks" || echo "Differences found above"
                    
                else
                    echo "❌ Backup not found"
                fi
            else
                echo "❌ No backups available. Create one first (option 1)"
            fi
            ;;
            
        5)
            echo ""
            echo "📖 UPDATE SAFETY GUIDELINES"
            echo "=========================="
            echo ""
            echo "🔒 BEFORE ANY UPDATE:"
            echo "1. Create safety backup (option 1)"
            echo "2. Test all blocks (option 3)"  
            echo "3. Generate compatibility report (option 2)"
            echo ""
            echo "⚠️  CRITICAL UPDATE RULES:"
            echo "• NEVER modify existing block field names"
            echo "• NEVER change block.json 'name' property"
            echo "• NEVER remove existing ACF fields"
            echo "• NEVER change field 'key' values"
            echo ""
            echo "✅ SAFE UPDATE PRACTICES:"
            echo "• Add new fields with NEW names"
            echo "• Keep backward compatibility"
            echo "• Use versioning for field groups"
            echo "• Test on staging first"
            echo ""
            echo "🔄 VERSION CONTROL STRATEGY:"
            echo "• Keep field keys consistent"
            echo "• Document all changes"  
            echo "• Use semantic versioning"
            echo "• Maintain changelog"
            echo ""
            echo "🆘 IF SOMETHING BREAKS:"
            echo "1. Stop WordPress"
            echo "2. Restore from backup"
            echo "3. Check error logs"
            echo "4. Fix issues incrementally"
            echo ""
            ;;
            
        6)
            echo ""
            echo "🚨 Fixing ACF Block Issues..."
            
            if [ -d "$SOURCE_THEME/blocks" ]; then
                echo "Scanning for empty block templates..."
                
                fixed_count=0
                
                for block_dir in "$SOURCE_THEME/blocks"/*; do
                    if [ -d "$block_dir" ]; then
                        block_name=$(basename "$block_dir")
                        template_file="$block_dir/template.php"
                        
                        if [ -f "$template_file" ]; then
                            # Check if template has minimal HTML
                            if ! grep -q "<div\|<section\|<article\|<span\|<p\|<h[1-6]" "$template_file"; then
                                echo "Fixing: $block_name"
                                
                                # Add wrapper div at the beginning of PHP templates
                                if grep -q "<?php" "$template_file"; then
                                    # Create backup
                                    cp "$template_file" "${template_file}.backup"
                                    
                                    # Add wrapper div after PHP opening
                                    sed -i '' '1 a\
<div class="block-'$block_name'">
' "$template_file"
                                    
                                    # Add closing div at the end
                                    echo "</div>" >> "$template_file"
                                    
                                    echo "  ✅ Added wrapper div to prevent React errors"
                                    ((fixed_count++))
                                fi
                            fi
                        fi
                    fi
                done
                
                if [ $fixed_count -gt 0 ]; then
                    echo "🎉 Fixed $fixed_count block templates"
                    echo "💾 Backups saved as *.backup files"
                    echo "🔄 Refresh WordPress editor to see changes"
                else
                    echo "✅ No fixes needed - all templates have HTML elements"
                fi
                
            else
                echo "❌ No blocks directory found"
            fi
            ;;
            
        7)
            echo "👋 Update Safety System - Goodbye!"
            break
            ;;
            
        *)
            echo "❌ Invalid option. Please choose 1-7."
            ;;
    esac
    
    echo ""
    read -p "Press Enter to continue..."
    echo ""
done 