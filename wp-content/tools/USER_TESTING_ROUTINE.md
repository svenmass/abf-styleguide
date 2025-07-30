# 🔒 User-Prüfungsroutine - ABF Styleguide QA System

## ✅ **SICHERHEITS-CHECKLISTE** (Vor jeder Nutzung)

### 1. Branch-Status prüfen
```bash
git branch
# ✅ Sollte "* quality-assurance" anzeigen
# ❌ Falls nicht: git checkout quality-assurance
```

### 2. Git-Status überprüfen
```bash
git status
# ✅ Sollte nur neue Dateien in tools/ anzeigen
# ❌ Falls Theme-Dateien geändert: STOPP - git checkout content-blocks
```

### 3. Backup-Branch prüfen
```bash
git log --oneline content-blocks -5
# ✅ Sollte Ihre letzten Commits zeigen
```

---

## 🚀 **QA-SYSTEM AUSFÜHREN**

### Schnelle Prüfung (Empfohlen)
```bash
php tools/run-checks.php --quick
```

### Vollständige Analyse
```bash
php tools/run-checks.php --full
```

### Nur CSS/BEM Prüfung
```bash
php tools/run-checks.php --css
```

### Hilfe anzeigen
```bash
php tools/run-checks.php --help
```

---

## 📊 **ERGEBNISSE INTERPRETIEREN**

### ✅ **Gute Werte:**
- **BEM Konformität**: > 80%
- **SCSS Variablen**: > 70%
- **Responsive Design**: > 80%
- **Performance**: > 85%

### ⚠️ **Verbesserungsbedarf:**
- **BEM Konformität**: < 80% (Selektor-Namen überprüfen)
- **SCSS Variablen**: < 70% (Mehr $variables nutzen)
- **Performance**: < 70% (Zu tiefe Verschachtelung)

### ❌ **Kritische Probleme:**
- Jeder Score < 50%
- Mehr als 15 Issues in der Liste

---

## 📄 **REPORTS ÖFFNEN**

### HTML-Reports anzeigen
```bash
# Im Browser öffnen:
open tools/quality-reports/css-analysis.html

# Oder manuell navigieren zu:
# wp-content/tools/quality-reports/css-analysis.html
```

### Alle Reports auf einmal
```bash
open tools/quality-reports/
```

---

## 🛡️ **NOTFALL-PROTOKOLL**

### Bei unerwarteten Problemen:
1. **Sofort zurück zum funktionierenden Code:**
   ```bash
   git checkout content-blocks
   ```

2. **System-Status prüfen:**
   ```bash
   git status
   # Sollte "nothing to commit" zeigen
   ```

3. **Funktionalität testen:**
   - WordPress Dashboard öffnen
   - Block-Editor testen
   - Frontend anzeigen

### Bei Fehlermeldungen:
1. **Screenshot machen** (für Support)
2. **Fehlermeldung kopieren**
3. **Branch wechseln:**
   ```bash
   git checkout content-blocks
   ```

---

## 📈 **MONITORING & TRENDS**

### Regelmäßige Prüfungen (Empfehlung):
- **Täglich**: `php tools/run-checks.php --quick`
- **Wöchentlich**: `php tools/run-checks.php --full`
- **Vor Releases**: Vollständige Analyse + manuelle Review

### Qualitäts-Trends verfolgen:
- BEM-Konformität sollte steigen
- Performance-Issues sollten sinken
- Variable-Nutzung sollte zunehmen

---

## 🎯 **NEXT STEPS - Nach Chat 1**

### Implementiert in Chat 1:
- ✅ CSS/BEM Linter
- ✅ Basis-Infrastructure
- ✅ HTML Reports
- ✅ Sicherheits-Protokoll

### Geplant für Chat 2:
- 🔄 ACF Fields Consistency Linter
- 🔄 WordPress Coding Standards
- 🔄 Extended Dashboard

### Geplant für Chat 3+:
- 🔄 Component Reusability Analyzer
- 🔄 Security Vulnerability Scanner
- 🔄 Performance Optimization
- 🔄 CI/CD Integration

---

## 📞 **SUPPORT**

### Bei Fragen oder Problemen:
1. **Zuerst**: Notfall-Protokoll befolgen
2. **Dann**: Screenshot + Fehlermeldung sammeln
3. **Info bereithalten**: 
   - Welcher Branch aktiv war
   - Welcher Befehl ausgeführt wurde
   - Was das erwartete vs. tatsächliche Ergebnis war

### Logs überprüfen:
```bash
# PHP Errors
tail -f /var/log/php/error.log

# System Status
php tools/run-checks.php --help
```

---

*🔒 Diese Prüfungsroutine garantiert, dass Ihr funktionierender Code niemals gefährdet wird.* 