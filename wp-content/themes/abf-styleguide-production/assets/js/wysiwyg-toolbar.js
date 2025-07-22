/**
 * ABF WYSIWYG Toolbar - Main initialization script
 * 
 * This script coordinates the custom TinyMCE plugins and ensures they have
 * access to the colors and typography data from the JSON files.
 */

(function($) {
    'use strict';
    
    // TinyMCE availability will be checked when needed - don't return early
    if (typeof tinymce === 'undefined') {
    }
    
    // Store the data globally for TinyMCE plugins to access
    window.abfToolbarData = window.abfToolbarData || {};
    
    // Initialize when TinyMCE is ready
    $(document).ready(function() {
        // Watch for new TinyMCE editors (important for ACF fields)
        if (typeof acf !== 'undefined') {
            acf.addAction('wysiwyg_tinymce_init', function(editor, id, field) {
            });
        }
        
        // Also watch for standard WordPress TinyMCE initialization
        $(document).on('tinymce-editor-init', function(event, editor) {
        });
    });
    
    // Utility functions for the plugins
    window.ABFToolbarUtils = {
        /**
         * Apply color to selected text
         */
        applyColor: function(editor, colorValue, colorName) {
            if (!editor || !colorValue) return;
            
            var selection = editor.selection.getContent();
            if (selection) {
                var wrappedContent = '<span style="color: ' + colorValue + '" data-abf-color="' + colorName + '">' + selection + '</span>';
                editor.insertContent(wrappedContent);
            } else {
                // No selection, insert a placeholder
                var placeholder = '<span style="color: ' + colorValue + '" data-abf-color="' + colorName + '">Text hier eingeben</span>';
                editor.insertContent(placeholder);
            }
        },
        
        /**
         * Apply font size to selected text
         */
        applyFontSize: function(editor, fontSize, fontKey) {
            if (!editor || !fontSize) return;
            
            var selection = editor.selection.getContent();
            if (selection) {
                var wrappedContent = '<span style="font-size: ' + fontSize + 'px;" data-abf-font-size="' + fontKey + '">' + selection + '</span>';
                editor.insertContent(wrappedContent);
            } else {
                // No selection, insert a placeholder
                var placeholder = '<span style="font-size: ' + fontSize + 'px;" data-abf-font-size="' + fontKey + '">Text hier eingeben</span>';
                editor.insertContent(placeholder);
            }
        },
        
        /**
         * Create dropdown HTML for colors
         */
        createColorDropdown: function() {
            if (!window.abfToolbarData.colors) return '<div>No colors available</div>';
            
            var html = '<div class="abf-color-dropdown">';
            window.abfToolbarData.colors.forEach(function(color) {
                html += '<div class="abf-dropdown-item" data-color-value="' + color.value + '" data-color-name="' + color.name + '">';
                html += '<div class="abf-color-swatch" style="background-color: ' + color.value + ';"></div>';
                html += '<span>' + color.name + '</span>';
                html += '</div>';
            });
            html += '</div>';
            return html;
        },
        
        /**
         * Create dropdown HTML for typography
         */
        createTypographyDropdown: function() {
            if (!window.abfToolbarData.typography.font_sizes) return '<div>No font sizes available</div>';
            
            var html = '<div class="abf-typography-dropdown">';
            window.abfToolbarData.typography.font_sizes.forEach(function(size) {
                html += '<div class="abf-dropdown-item" data-font-size="' + size.desktop + '" data-font-key="' + size.key + '">';
                html += '<span class="abf-typography-preview" style="font-size: ' + Math.min(parseInt(size.desktop), 16) + 'px;">' + size.label + '</span>';
                html += '</div>';
            });
            html += '</div>';
            return html;
        }
    };
    
})(jQuery); 