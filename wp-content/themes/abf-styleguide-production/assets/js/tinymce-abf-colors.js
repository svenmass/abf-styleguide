/**
 * TinyMCE Plugin: ABF Colors
 * 
 * Adds a colors dropdown button to TinyMCE toolbar that applies colors
 * from the theme's colors.json file.
 */

(function() {
    'use strict';
    
    tinymce.PluginManager.add('abf_colors', function(editor) {
        
        // Wait for data to be available
        function waitForDataAndRegister() {
            if (window.abfToolbarData && window.abfToolbarData.colors && window.abfToolbarData.colors.length > 0) {
                registerButton();
            } else {
                setTimeout(waitForDataAndRegister, 100);
            }
        }
        
        function registerButton() {
            // Create menu items array
            var menuItems = [];
            
            // Add colors
            window.abfToolbarData.colors.forEach(function(color, index) {
                menuItems.push({
                    text: color.name,
                    value: color.value,
                    onclick: function() {
                        applyColor(color.value, color.name);
                    }
                });
            });
            
            
            // Register the button
            editor.addButton('abf_colors', {
                type: 'menubutton',
                text: '🎨',
                title: 'ABF Farben',
                menu: menuItems,
                onPostRender: function() {
                }
            });
            
        }
        
        // Apply color to selected text
        function applyColor(colorValue, colorName) {
            var selection = editor.selection.getContent();
            if (selection) {
                var wrappedContent = '<span style="color: ' + colorValue + ';" data-abf-color="' + colorName + '">' + selection + '</span>';
                editor.insertContent(wrappedContent);
            } else {
                var placeholder = '<span style="color: ' + colorValue + ';" data-abf-color="' + colorName + '">Farbiger Text</span>';
                editor.insertContent(placeholder);
            }
        }
        
        // Start the process
        waitForDataAndRegister();
    });
})(); 