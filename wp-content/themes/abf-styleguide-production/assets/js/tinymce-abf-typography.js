/**
 * TinyMCE Plugin: ABF Typography
 * 
 * Adds a typography dropdown button to TinyMCE toolbar that applies font sizes
 * from the theme's typography.json file.
 */

(function() {
    'use strict';
    
    tinymce.PluginManager.add('abf_typography', function(editor) {
        
        // Wait for data to be available
        function waitForDataAndRegister() {
            if (window.abfToolbarData && window.abfToolbarData.typography && window.abfToolbarData.typography.font_sizes && window.abfToolbarData.typography.font_sizes.length > 0) {
                registerButton();
            } else {
                setTimeout(waitForDataAndRegister, 100);
            }
        }
        
        function registerButton() {
            // Create menu items array
            var menuItems = [];
            
            // Add font sizes
            window.abfToolbarData.typography.font_sizes.forEach(function(fontSizeObj, index) {
                menuItems.push({
                    text: fontSizeObj.label + ' (' + fontSizeObj.desktop + 'px)',
                    value: fontSizeObj.desktop,
                    onclick: function() {
                        applyFontSize(fontSizeObj.desktop, fontSizeObj.key);
                    }
                });
            });
            
            
            // Register the button
            editor.addButton('abf_typography', {
                type: 'menubutton',
                text: '📝',
                title: 'ABF Schriftgrößen',
                menu: menuItems,
                onPostRender: function() {
                }
            });
            
        }
        
        // Apply font size to selected text
        function applyFontSize(fontSize, fontKey) {
            var selection = editor.selection.getContent();
            if (selection) {
                var wrappedContent = '<span style="font-size: ' + fontSize + 'px;" data-abf-font-size="' + fontKey + '">' + selection + '</span>';
                editor.insertContent(wrappedContent);
            } else {
                var placeholder = '<span style="font-size: ' + fontSize + 'px;" data-abf-font-size="' + fontKey + '">Formatierter Text</span>';
                editor.insertContent(placeholder);
            }
        }
        
        // Start the process
        waitForDataAndRegister();
    });
})(); 