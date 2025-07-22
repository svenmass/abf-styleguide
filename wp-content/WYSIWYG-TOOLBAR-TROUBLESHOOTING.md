# 🛠️ **WYSIWYG Toolbar - Troubleshooting**

## ❌ **AKTUELLE PROBLEME:**

1. **Doppelte Icons** → Plugins werden mehrfach registriert
2. **Leere Dropdowns** → Daten kommen nicht an

---

## 🔧 **SOFORTMASSNAHMEN IMPLEMENTIERT:**

### **1. ✅ Doppelte Registrierung verhindert**
```php
// Verhindert mehrfache Filter-Registration
if (!has_filter('mce_external_plugins', [$this, 'add_tinymce_plugins'])) {
    add_filter('mce_external_plugins', [$this, 'add_tinymce_plugins']);
}
```

### **2. ✅ Debug-Logging aktiviert**
```php
// In wysiwyg-toolbar.php
error_log('ABF Toolbar Colors count: ' . count($this->colors));
error_log('ABF Toolbar Typography font_sizes count: ' . count($this->typography['font_sizes']));
```

### **3. ✅ JavaScript-Debug hinzugefügt**
```javascript
// In tinymce-abf-colors.js & tinymce-abf-typography.js
console.log('ABF Colors: Checking for data...', window.abfToolbarData);
```

---

## 🕵️ **DEBUGGING-SCHRITTE:**

### **SCHRITT 1: Browser-Konsole prüfen**
1. **Öffne Developer Tools** (F12)
2. **Gehe zur Console**
3. **Öffne einen Block mit WYSIWYG-Feld**
4. **Schaue nach diesen Meldungen:**
   ```
   ABF Colors: Checking for data...
   ABF Colors: Data found! [number] colors
   ABF Typography: Checking for data...
   ABF Typography: Data found! [number] font sizes
   ```

### **SCHRITT 2: WordPress Debug-Log prüfen**
1. **Aktiviere Debug-Logging in wp-config.php:**
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
   ```
2. **Schaue in `/wp-content/debug.log` nach:**
   ```
   ABF Toolbar: Loaded [X] colors from JSON
   ABF Toolbar: Loaded typography data with [X] font sizes
   ```

### **SCHRITT 3: JSON-Dateien prüfen**
1. **Überprüfe:** `themes/abf-styleguide/colors.json` existiert
2. **Überprüfe:** `themes/abf-styleguide/typography.json` existiert
3. **Teste JSON-Syntax:** Copy-paste Inhalt in JSON-Validator

---

## 🚨 **HÄUFIGE PROBLEME:**

### **❌ PROBLEM: Icons doppelt**
**Ursache:** TinyMCE-Filter mehrfach registriert
**Lösung:** ✅ Bereits gefixt mit `has_filter()` Check

### **❌ PROBLEM: Leere Dropdowns**
**Mögliche Ursachen:**
1. **JSON-Dateien nicht gefunden**
2. **JSON-Syntax-Fehler**
3. **wp_localize_script Daten kommen nicht an**
4. **Timing-Problem beim Laden**

---

## 🔍 **DIAGNOSE:**

### **A) Konsolen-Ausgaben analysieren:**

**✅ ERWARTET:**
```
ABF Colors: Checking for data...
Object { colors: [...], typography: {...} }
ABF Colors: Data found! 15 colors
```

**❌ PROBLEM:**
```
ABF Colors: Checking for data...
undefined
ABF Colors: Data not ready, retrying in 100ms...
```
→ **wp_localize_script funktioniert nicht**

### **B) Debug-Log analysieren:**

**✅ ERWARTET:**
```
ABF Toolbar: Loaded 15 colors from JSON
ABF Toolbar: Loaded typography data with 8 font sizes
```

**❌ PROBLEM:**
```
ABF Toolbar: Colors file does not exist
ABF Toolbar: Typography JSON decode failed
```
→ **JSON-Dateien fehlen oder sind beschädigt**

---

## 🎯 **NÄCHSTE SCHRITTE:**

1. **Debug-Ausgaben prüfen** (Konsole + Log)
2. **JSON-Dateien validieren**
3. **Bei weiteren Problemen:** Debug-Info an Entwickler senden

---

**📝 Mit den Debug-Meldungen können wir das Problem schnell eingrenzen!** 