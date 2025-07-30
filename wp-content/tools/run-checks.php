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
            case 'acf':
                $this->runACFChecks();
                break;
            case 'security':
                $this->runSecurityPerformanceChecks();
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
                case '--acf':
                case '-a':
                    return 'acf';
                case '--security':
                case '-s':
                    return 'security';
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
        echo "  --acf, -a      Nur ACF Fields Prüfung\n";
        echo "  --security, -s Nur Security+Performance Prüfung\n";
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
        $this->runACFChecks();
        $this->runSecurityPerformanceChecks();
        $this->checkBasicStructure();
    }
    
    /**
     * 🔍 Vollständige Analyse
     */
    private function runFullAnalysis() {
        echo "🔍 VOLLSTÄNDIGE QUALITÄTSANALYSE\n";
        echo "─────────────────────────────────\n\n";
        
        $this->runCSSChecks();
        $this->runACFChecks();
        $this->runSecurityPerformanceChecks();
        $this->checkBasicStructure();
        echo "ℹ️  Component Reusability Linter wird in Chat 4 implementiert...\n\n";
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
     * 🔧 ACF Fields Prüfungen
     */
    private function runACFChecks() {
        echo "🔧 ACF FIELDS CONSISTENCY PRÜFUNG\n";
        echo "─────────────────────────────────\n";
        
        $linter_path = __DIR__ . '/linters/acf-consistency-linter.php';
        
        if (file_exists($linter_path)) {
            include $linter_path;
            $acf_linter = new ABF_ACF_Consistency_Linter($this->theme_path);
            $acf_results = $acf_linter->analyze();
            $this->results['acf'] = $acf_results;
            
            $this->printACFResults($acf_results);
        } else {
            echo "⚠️  ACF Linter noch nicht implementiert\n";
            echo "📝 Wird in diesem Chat erstellt...\n\n";
        }
    }
    
    /**
     * 🔒 Security + Performance Prüfungen
     */
    private function runSecurityPerformanceChecks() {
        echo "🔒 SECURITY + PERFORMANCE PRÜFUNG\n";
        echo "────────────────────────────────\n";
        
        $linter_path = __DIR__ . '/linters/security-performance-linter.php';
        
        if (file_exists($linter_path)) {
            include $linter_path;
            $security_linter = new ABF_Security_Performance_Linter($this->theme_path);
            $security_results = $security_linter->analyze();
            $this->results['security'] = $security_results;
            
            $this->printSecurityResults($security_results);
        } else {
            echo "⚠️  Security+Performance Linter noch nicht implementiert\n";
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
     * 📊 ACF Ergebnisse ausgeben
     */
    private function printACFResults($results) {
        if (!$results) return;
        
        echo "📊 Naming Convention: " . ($results['naming_score'] ?? 'N/A') . "%\n";
        echo "📊 Typography System: " . ($results['typography_score'] ?? 'N/A') . "%\n";
        echo "📊 Color Integration: " . ($results['color_score'] ?? 'N/A') . "%\n";
        echo "📊 Structure Pattern: " . ($results['structure_score'] ?? 'N/A') . "%\n";
        
        if (!empty($results['issues'])) {
            echo "\n⚠️  Gefundene Probleme:\n";
            foreach (array_slice($results['issues'], 0, 8) as $issue) {
                echo "   • {$issue}\n";
            }
            if (count($results['issues']) > 8) {
                echo "   • ... und " . (count($results['issues']) - 8) . " weitere\n";
            }
        }
        
        echo "\n";
    }
    
    /**
     * 🔒 Security + Performance Ergebnisse ausgeben
     */
    private function printSecurityResults($results) {
        if (!$results) return;
        
        echo "📊 Security Score: " . ($results['security_score'] ?? 'N/A') . "%\n";
        echo "📊 Performance Score: " . ($results['performance_score'] ?? 'N/A') . "%\n";
        echo "📊 WP Standards: " . ($results['wp_standards_score'] ?? 'N/A') . "%\n";
        echo "📊 PHP Compatibility: " . ($results['php_compatibility_score'] ?? 'N/A') . "%\n";
        
        if (!empty($results['issues'])) {
            echo "\n⚠️  Gefundene Probleme:\n";
            foreach (array_slice($results['issues'], 0, 8) as $issue) {
                echo "   • {$issue}\n";
            }
            if (count($results['issues']) > 8) {
                echo "   • ... und " . (count($results['issues']) - 8) . " weitere\n";
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