/**
 * TinyMCE Plugin: ABF Typography
 * 
 * Adds a typography dropdown button to TinyMCE toolbar that applies font sizes
 * from the theme's typography.json file.
 */

(function() {
    'use strict';
    
    tinymce.PluginManager.add('abf_typography', function(editor) {
        console.log('🔧 ABF Typography: Plugin initializing...', window.abfToolbarData);
        
        // Wait for data to be available
        function waitForDataAndRegister() {
            if (window.abfToolbarData && window.abfToolbarData.typography && window.abfToolbarData.typography.font_sizes && window.abfToolbarData.typography.font_sizes.length > 0) {
                console.log('🔧 ABF Typography: Data ready, creating menu button with', window.abfToolbarData.typography.font_sizes.length, 'font sizes');
                registerButton();
            } else {
                console.log('🔧 ABF Typography: Data not ready, retrying in 100ms...');
                setTimeout(waitForDataAndRegister, 100);
            }
        }
        
        function registerButton() {
            // Create menu items array
            var menuItems = [];
            
            // Add font sizes
            window.abfToolbarData.typography.font_sizes.forEach(function(fontSizeObj, index) {
                console.log('📝 ABF Typography: Adding menu item', index, ':', fontSizeObj.label, fontSizeObj.desktop + 'px');
                menuItems.push({
                    text: fontSizeObj.label + ' (' + fontSizeObj.desktop + 'px)',
                    value: fontSizeObj.desktop,
                    onclick: function() {
                        console.log('📝 ABF Typography: Applying font size', fontSizeObj.label, fontSizeObj.desktop + 'px');
                        applyFontSize(fontSizeObj.desktop, fontSizeObj.key);
                    }
                });
            });
            
            console.log('📝 ABF Typography: Menu items created:', menuItems.length, 'items');
            
            // Register the button
            editor.addButton('abf_typography', {
                type: 'menubutton',
                text: '📝',
                title: 'ABF Schriftgrößen',
                menu: menuItems,
                onPostRender: function() {
                    console.log('✅ ABF Typography: MenuButton rendered with', menuItems.length, 'menu items');
                }
            });
            
            console.log('✅ ABF Typography: Button registered successfully');
        }
        
        // Apply font size to selected text
        function applyFontSize(fontSize, fontKey) {
            var selection = editor.selection.getContent();
            if (selection) {
                var wrappedContent = '<span style="font-size: ' + fontSize + 'px;" data-abf-font-size="' + fontKey + '">' + selection + '</span>';
                editor.insertContent(wrappedContent);
                console.log('✅ ABF Typography: Applied font size', fontSize + 'px', 'to selection');
            } else {
                var placeholder = '<span style="font-size: ' + fontSize + 'px;" data-abf-font-size="' + fontKey + '">Formatierter Text</span>';
                editor.insertContent(placeholder);
                console.log('✅ ABF Typography: Inserted sized placeholder');
            }
        }
        
        // Start the process
        waitForDataAndRegister();
    });
})(); 