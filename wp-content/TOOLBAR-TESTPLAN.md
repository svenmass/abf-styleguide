# 📋 **WYSIWYG Toolbar - Test Plan**

## 🎯 **ZIELE:**
1. **Doppelte Icons beheben**
2. **Leere Dropdowns füllen**
3. **Funktionsfähige Farb- & Typography-Buttons**

---

## ✅ **IMPLEMENTIERTE FIXES:**

### **1. Doppelte Registrierung verhindert**
- ✅ `has_filter()` Checks hinzugefügt
- ✅ Beide Themes aktualisiert

### **2. Umfangreiches Debugging**
- ✅ PHP error_log für JSON-Loading
- ✅ JavaScript console.log für Data-Transfer
- ✅ Detaillierte Fehlermeldungen

### **3. Robustere JSON-Loading**
- ✅ Existenz-Checks für Dateien
- ✅ JSON-Decode Validation
- ✅ Fallback-Handling

---

## 🧪 **TESTSCHRITTE:**

### **SCHRITT 1: Debugging aktivieren**
```php
// In wp-config.php sicherstellen:
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### **SCHRITT 2: Browser-Test**
1. **F12 → Console Tab öffnen**
2. **Block mit WYSIWYG-Feld öffnen** (styleguide-text-element)
3. **In das Textfeld klicken**
4. **Console-Output beobachten**

### **SCHRITT 3: Erwartete Ausgaben**

**✅ ERFOLG (Console):**
```
ABF Colors: Checking for data...
Object { colors: Array[15], typography: {...}, debug: true }
ABF Colors: Data found! 15 colors
ABF Typography: Checking for data...
ABF Typography: Data found! 8 font sizes
```

**✅ ERFOLG (Debug Log `/wp-content/debug.log`):**
```
ABF Toolbar: Loaded 15 colors from JSON
ABF Toolbar: Loaded typography data with 8 font sizes
ABF Toolbar Colors count: 15
ABF Toolbar Typography font_sizes count: 8
```

### **SCHRITT 4: Visual-Test**
- **Toolbar sollte haben:** Bold, Italic, Underline, 🎨 (Colors), 📝 (Typography), Alignment, Lists, Links
- **NUR EINMAL** jeder Button
- **Dropdown bei Click** auf 🎨 & 📝 Buttons

---

## 🚨 **PROBLEM-DIAGNOSE:**

### **❌ CONSOLE: "undefined" bei Data Check**
**Problem:** wp_localize_script funktioniert nicht
**Check:** 
- Ist `abf-wysiwyg-toolbar` Script geladen? (Network Tab)
- Andere JavaScript-Fehler vorher?

### **❌ DEBUG LOG: "file does not exist"**
**Problem:** JSON-Dateien fehlen
**Check:**
- `/themes/abf-styleguide/colors.json` vorhanden?
- `/themes/abf-styleguide/typography.json` vorhanden?

### **❌ VISUAL: Buttons doppelt**
**Problem:** Filter mehrfach angewendet
**Check:** Console nach "has_filter prevented duplicate"

---

## 📧 **SUPPORT-INFO sammeln:**

Bei anhaltenden Problemen, bitte sende:

### **A) Console Output**
```
// Komplette Console-Ausgabe beim Öffnen eines Blocks
```

### **B) Debug Log**
```
// Letzte 20 Zeilen aus /wp-content/debug.log
// Die "ABF Toolbar:" Zeilen sind wichtig
```

### **C) File Check**
```bash
ls -la themes/abf-styleguide/colors.json
ls -la themes/abf-styleguide/typography.json
```

### **D) Theme Check**
- Welches Theme ist aktiv? (Dashboard → Design → Themes)
- ACF Version?
- WordPress Version?

---

## 🎯 **ERWARTETE RESULTATE:**

Nach dem Fix solltest du sehen:
- **✅ Keine doppelten Icons**
- **✅ Dropdown mit 15+ Farben**  
- **✅ Dropdown mit 8 Schriftgrößen**
- **✅ Keine Console-Fehler**
- **✅ Bold/Italic bleibt erhalten**

**🎉 Wenn alle Tests ✅ sind, ist die Toolbar vollständig funktionsfähig!** 