<?php
/**
 * 🎯 ABF Styleguide Quality Assurance System
 * 
 * Hauptscript für alle Qualitätsprüfungen
 * 
 * Usage:
 * php tools/run-checks.php --quick     (Schnelle Prüfung)
 * php tools/run-checks.php --full      (Vollständige Analyse)
 * php tools/run-checks.php --css       (Nur CSS/BEM)
 * php tools/run-checks.php --help      (Hilfe)
 * 
 * @package ABF_Styleguide_QA
 * @version 1.0.0
 */

// Prevent direct access
if (php_sapi_name() !== 'cli') {
    exit('❌ Dieses Script kann nur über die Kommandozeile ausgeführt werden.');
}

class ABF_Quality_Assurance {
    
    private $start_time;
    private $theme_path;
    private $results = [];
    
    public function __construct() {
        $this->start_time = microtime(true);
        $this->theme_path = dirname(__DIR__) . '/themes/abf-styleguide';
        
        // Prüfe ob Theme-Verzeichnis existiert
        if (!is_dir($this->theme_path)) {
            $this->theme_path = dirname(__DIR__) . '/themes/abf-styleguide-production';
        }
        
        if (!is_dir($this->theme_path)) {
            $this->error('❌ Theme-Verzeichnis nicht gefunden!');
        }
    }
    
    /**
     * 🚀 Haupteinstiegspunkt
     */
    public function run($args = []) {
        $this->printHeader();
        
        // Kommandozeilen-Argumente verarbeiten
        $mode = $this->parseArguments($args);
        
        switch ($mode) {
            case 'help':
                $this->showHelp();
                break;
            case 'quick':
                $this->runQuickChecks();
                break;
            case 'full':
                $this->runFullAnalysis();
                break;
            case 'css':
                $this->runCSSChecks();
                break;
            default:
                $this->runQuickChecks();
        }
        
        $this->printSummary();
    }
    
    /**
     * 📋 Kommandozeilen-Argumente parsen
     */
    private function parseArguments($args) {
        if (empty($args)) return 'quick';
        
        foreach ($args as $arg) {
            switch ($arg) {
                case '--help':
                case '-h':
                    return 'help';
                case '--quick':
                case '-q':
                    return 'quick';
                case '--full':
                case '-f':
                    return 'full';
                case '--css':
                case '-c':
                    return 'css';
            }
        }
        
        return 'quick';
    }
    
    /**
     * 🎨 Header ausgeben
     */
    private function printHeader() {
        echo "\n";
        echo "🎯 ===============================================\n";
        echo "   ABF Styleguide - Quality Assurance System   \n";
        echo "===============================================\n";
        echo "📁 Theme Path: " . $this->theme_path . "\n";
        echo "⏰ Start Time: " . date('Y-m-d H:i:s') . "\n";
        echo "\n";
    }
    
    /**
     * ❓ Hilfe anzeigen
     */
    private function showHelp() {
        echo "🔧 VERWENDUNG:\n";
        echo "php tools/run-checks.php [OPTION]\n\n";
        echo "📋 OPTIONEN:\n";
        echo "  --quick, -q    Schnelle Prüfung (Standard)\n";
        echo "  --full, -f     Vollständige Analyse\n";
        echo "  --css, -c      Nur CSS/BEM Prüfung\n";
        echo "  --help, -h     Diese Hilfe anzeigen\n\n";
        echo "📊 BERICHTE:\n";
        echo "  HTML Reports: tools/quality-reports/\n";
        echo "  Dashboard:    tools/dashboard/index.html\n\n";
        echo "🔒 SICHERHEIT:\n";
        echo "  ✅ Nur Analyse - keine Code-Änderungen\n";
        echo "  ✅ Separater Branch: quality-assurance\n";
        echo "  ✅ Funktionierender Code bleibt unberührt\n\n";
    }
    
    /**
     * ⚡ Schnelle Prüfungen
     */
    private function runQuickChecks() {
        echo "⚡ SCHNELLE QUALITÄTSPRÜFUNG\n";
        echo "─────────────────────────────\n\n";
        
        $this->runCSSChecks();
        $this->checkBasicStructure();
    }
    
    /**
     * 🔍 Vollständige Analyse
     */
    private function runFullAnalysis() {
        echo "🔍 VOLLSTÄNDIGE QUALITÄTSANALYSE\n";
        echo "─────────────────────────────────\n\n";
        
        $this->runCSSChecks();
        $this->checkBasicStructure();
        echo "ℹ️  Weitere Linter werden in den nächsten Chats implementiert...\n\n";
    }
    
    /**
     * 🎨 CSS/BEM Prüfungen
     */
    private function runCSSChecks() {
        echo "🎨 CSS/BEM STRUKTUR PRÜFUNG\n";
        echo "──────────────────────────\n";
        
        $linter_path = __DIR__ . '/linters/css-bem-linter.php';
        
        if (file_exists($linter_path)) {
            include $linter_path;
            $css_linter = new ABF_CSS_BEM_Linter($this->theme_path);
            $css_results = $css_linter->analyze();
            $this->results['css'] = $css_results;
            
            $this->printCSSResults($css_results);
        } else {
            echo "⚠️  CSS Linter noch nicht implementiert\n";
            echo "📝 Wird in diesem Chat erstellt...\n\n";
        }
    }
    
    /**
     * 🏗️ Basis-Struktur prüfen
     */
    private function checkBasicStructure() {
        echo "🏗️  GRUNDSTRUKTUR PRÜFUNG\n";
        echo "────────────────────────\n";
        
        $checks = [
            'blocks' => is_dir($this->theme_path . '/blocks'),
            'assets' => is_dir($this->theme_path . '/assets'),
            'scss' => is_dir($this->theme_path . '/assets/scss'),
            'inc' => is_dir($this->theme_path . '/inc'),
        ];
        
        foreach ($checks as $name => $exists) {
            if ($exists) {
                echo "✅ {$name}/ Verzeichnis vorhanden\n";
            } else {
                echo "❌ {$name}/ Verzeichnis fehlt\n";
            }
        }
        
        echo "\n";
    }
    
    /**
     * 📊 CSS Ergebnisse ausgeben
     */
    private function printCSSResults($results) {
        if (!$results) return;
        
        echo "📊 BEM Konformität: " . ($results['bem_score'] ?? 'N/A') . "%\n";
        echo "📊 SCSS Variablen: " . ($results['variable_usage'] ?? 'N/A') . "%\n";
        echo "📊 Responsive Design: " . ($results['responsive_score'] ?? 'N/A') . "%\n";
        
        if (!empty($results['issues'])) {
            echo "\n⚠️  Gefundene Probleme:\n";
            foreach ($results['issues'] as $issue) {
                echo "   • {$issue}\n";
            }
        }
        
        echo "\n";
    }
    
    /**
     * 📈 Zusammenfassung ausgeben
     */
    private function printSummary() {
        $duration = round(microtime(true) - $this->start_time, 2);
        
        echo "📈 ZUSAMMENFASSUNG\n";
        echo "─────────────────\n";
        echo "⏱️  Ausführungszeit: {$duration} Sekunden\n";
        echo "🎯 Geprüfte Komponenten: " . count($this->results) . "\n";
        
        if (!empty($this->results)) {
            echo "📄 Reports generiert in: tools/quality-reports/\n";
        }
        
        echo "\n🔒 PRÜFUNGSROUTINE FÜR USER:\n";
        echo "1. ✅ Git Status prüfen: git status\n";
        echo "2. ✅ Branch prüfen: git branch (sollte 'quality-assurance' sein)\n";
        echo "3. ✅ Reports öffnen: tools/quality-reports/\n";
        echo "4. ✅ Bei Problemen: git checkout content-blocks\n";
        echo "\n🎯 System bereit für weitere Linter-Implementierung!\n\n";
    }
    
    /**
     * ❌ Fehler ausgeben und beenden
     */
    private function error($message) {
        echo $message . "\n";
        exit(1);
    }
}

// Script ausführen
$qa_system = new ABF_Quality_Assurance();
$qa_system->run(array_slice($argv, 1)); 