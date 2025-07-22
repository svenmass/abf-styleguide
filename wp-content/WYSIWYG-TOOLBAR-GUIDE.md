# 🎨 **ABF WYSIWYG Toolbar - Erweiterte Richtext-Formatierung**

## ✅ **PROBLEM GELÖST!**

Das **CSS-Vererbungsproblem** mit den styleguide-x Blocks ist jetzt gelöst! Die neue WYSIWYG-Toolbar ermöglicht:

- **✅ Farben aus `colors.json`** direkt im Editor anwenden
- **✅ Schriftgrößen aus `typography.json`** direkt im Editor anwenden  
- **✅ Bold, Italic, etc. bleiben erhalten** (kein Überschreiben mehr!)
- **✅ Benutzerfreundlicher** als separate ACF-Felder

---

## 🎯 **WIE ES FUNKTIONIERT**

### **1. NEUE TOOLBAR-BUTTONS**
In den WYSIWYG-Feldern findest du jetzt **2 neue Buttons**:

- **🎨 Farben-Button** (mit Farbpalette-Icon)
- **📝 Schriftgrößen-Button** (mit Typography-Icon)

### **2. VERWENDUNG**
1. **Text markieren** (oder Cursor positionieren)
2. **Button klicken** → Dropdown öffnet sich
3. **Farbe/Schriftgröße auswählen**
4. **✅ Fertig!** Text wird formatiert ohne andere Formatierungen zu überschreiben

### **3. BETROFFENE BLOCKS**
- ✅ `styleguide-text-element`
- ✅ `styleguide-bild-text`

---

## 📋 **VERFÜGBARE OPTIONEN**

### **🎨 FARBEN** (aus `colors.json`)
- ABF Grün (`#66a98c`)
- ABF Rot (`#c50d14`)
- ABF Grün 30% (`#d5e4dd`)
- Weiß (Basisfarbe) (`#ffffff`)
- Antrazit (Text) (`#575756`)
- Hellgrau (Basisfarbe) (`#cccccc`)
- Hellblau (Apotheke) (`#7cc1e3`)
- Dunkelblau (Apotheke) (`#00678a`)
- + weitere Apothekenfarben

### **📝 SCHRIFTGROSSEN** (aus `typography.json`)
- **72px - 4XL** (Desktop: 72px, Tablet: 60px, Mobile: 54px)
- **60px - 3XL** (Desktop: 60px, Tablet: 48px, Mobile: 40px)
- **48px - 2XL** (Desktop: 48px, Tablet: 40px, Mobile: 32px)
- **36px - Standard H1** (Desktop: 36px, Tablet: 30px, Mobile: 24px)
- **24px - Standard H2** (Desktop: 24px, Tablet: 20px, Mobile: 18px)
- **18px - Standard Body** (Desktop: 18px, Tablet: 16px, Mobile: 14px)
- **16px - Small Body** (Desktop: 16px, Tablet: 14px, Mobile: 12px)
- **12px - Small** (Desktop: 12px, Tablet: 10px, Mobile: 9px)

### **💪 SCHRIFTGEWICHTE** (aus `typography.json`)
- **Light (300)**
- **Regular (400)**
- **Medium (500)**
- **Semibold (600)**
- **Bold (700)**

---

## ⚠️ **LEGACY-FELDER**

Die **alten ACF-Felder** für Farbe, Schriftgröße und Schriftgewicht sind noch da, aber:

- **⚠️ Als "Legacy" markiert**
- **⚠️ Überschreiben ALLE Formatierungen** (auch Bold/Italic!)
- **🎯 EMPFEHLUNG:** Nutze stattdessen die **Toolbar-Buttons**

---

## 🔧 **TECHNISCHE DETAILS**

### **Dateien erstellt/geändert:**
- `inc/wysiwyg-toolbar.php` - Hauptklasse
- `assets/js/wysiwyg-toolbar.js` - Koordination
- `assets/js/tinymce-abf-colors.js` - Farben-Plugin
- `assets/js/tinymce-abf-typography.js` - Typography-Plugin
- Verschiedene `fields.php` - Toolbar-Umstellung

### **Datenquellen:**
- `colors.json` - Farbdefinitionen
- `typography.json` - Schriftarten und -größen

### **CSS-Klassen:**
Die formatierten Elemente erhalten data-Attribute:
- `data-abf-color="ABF Grün"` 
- `data-abf-font-size="xl"`
- `data-abf-font-weight="bold"`

---

## 🎉 **VORTEILE**

1. **✅ Benutzerfreundlich** - Alles in einem Editor
2. **✅ Konsistent** - Nutzt Theme-Vorgaben (JSON)
3. **✅ Formatierungen bleiben erhalten** - Bold/Italic funktionieren!
4. **✅ Flexibel** - Mehrere Formatierungen pro Text möglich
5. **✅ Zukunftssicher** - JSON-Dateien einfach erweiterbar

---

## 🚀 **NÄCHSTE SCHRITTE**

1. **Teste die neuen Toolbar-Buttons** in den styleguide-x Blocks
2. **Nutze die Legacy-Felder nicht mehr** (außer für globale Einstellungen)
3. **Bei Problemen:** Konsole öffnen und nach Fehlermeldungen schauen

---

**🎯 Das Problem mit überschriebenen Formatierungen ist Geschichte!** 