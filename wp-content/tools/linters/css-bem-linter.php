<?php
/**
 * 🎨 ABF CSS/BEM Structure Linter
 * 
 * Analysiert CSS/SCSS Dateien auf:
 * - BEM Methodology Konformität
 * - SCSS Variable Nutzung
 * - Responsive Design Patterns
 * - Performance Best Practices
 * 
 * @package ABF_Styleguide_QA
 * @version 1.0.0
 */

class ABF_CSS_BEM_Linter {
    
    private $theme_path;
    private $scss_files = [];
    private $css_files = [];
    private $analysis_results = [];
    
    // BEM Pattern Regex
    private $bem_patterns = [
        'block' => '/^[a-z][a-z0-9]*(-[a-z0-9]+)*$/',
        'element' => '/^[a-z][a-z0-9]*(-[a-z0-9]+)*__[a-z][a-z0-9]*(-[a-z0-9]+)*$/',
        'modifier' => '/^[a-z][a-z0-9]*(-[a-z0-9]+)*((__[a-z][a-z0-9]*(-[a-z0-9]+)*)?--[a-z][a-z0-9]*(-[a-z0-9]+)*)$/'
    ];
    
    // Responsive Breakpoints Pattern
    private $breakpoint_patterns = [
        '@media',
        '@include media',
        '@include breakpoint',
        'min-width',
        'max-width'
    ];
    
    public function __construct($theme_path) {
        $this->theme_path = $theme_path;
        $this->scanFiles();
    }
    
    /**
     * 📁 Scanne alle CSS/SCSS Dateien
     */
    private function scanFiles() {
        // SCSS Dateien finden
        $this->scss_files = $this->findFiles($this->theme_path, '*.scss');
        $this->css_files = $this->findFiles($this->theme_path, '*.css');
        
        // Ausschließen von generierten/vendor Dateien
        $this->scss_files = array_filter($this->scss_files, [$this, 'isRelevantFile']);
        $this->css_files = array_filter($this->css_files, [$this, 'isRelevantFile']);
    }
    
    /**
     * 🔍 Dateien finden mit Pattern
     */
    private function findFiles($directory, $pattern) {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && fnmatch($pattern, $file->getFilename())) {
                $files[] = $file->getPathname();
            }
        }
        
        return $files;
    }
    
    /**
     * ✅ Prüfe ob Datei relevant ist (nicht vendor/generated)
     */
    private function isRelevantFile($filepath) {
        $exclude_patterns = [
            '/node_modules/',
            '/vendor/',
            '/dist/',
            '/build/',
            '.min.css',
            '.min.scss',
            'bootstrap',
            'normalize'
        ];
        
        foreach ($exclude_patterns as $pattern) {
            if (strpos($filepath, $pattern) !== false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 🚀 Hauptanalyse ausführen
     */
    public function analyze() {
        $this->analysis_results = [
            'bem_score' => 0,
            'variable_usage' => 0,
            'responsive_score' => 0,
            'performance_score' => 0,
            'issues' => [],
            'files_analyzed' => count($this->scss_files) + count($this->css_files),
            'bem_violations' => [],
            'variable_issues' => [],
            'responsive_issues' => [],
            'performance_issues' => []
        ];
        
        // Analysiere alle SCSS Dateien
        foreach ($this->scss_files as $file) {
            $this->analyzeFile($file, 'scss');
        }
        
        // Analysiere alle CSS Dateien
        foreach ($this->css_files as $file) {
            $this->analyzeFile($file, 'css');
        }
        
        // Berechne Gesamtscores
        $this->calculateScores();
        
        // Generiere HTML Report
        $this->generateHTMLReport();
        
        return $this->analysis_results;
    }
    
    /**
     * 📄 Einzelne Datei analysieren
     */
    private function analyzeFile($filepath, $type) {
        $content = file_get_contents($filepath);
        $relative_path = str_replace($this->theme_path . '/', '', $filepath);
        
        // BEM Analyse
        $this->analyzeBEM($content, $relative_path);
        
        // Variable Analyse (nur SCSS)
        if ($type === 'scss') {
            $this->analyzeVariables($content, $relative_path);
        }
        
        // Responsive Design Analyse
        $this->analyzeResponsive($content, $relative_path);
        
        // Performance Analyse
        $this->analyzePerformance($content, $relative_path);
    }
    
    /**
     * 🏗️ BEM Struktur analysieren
     */
    private function analyzeBEM($content, $filepath) {
        // Extrahiere CSS Selektoren
        preg_match_all('/\.([a-zA-Z][a-zA-Z0-9_-]*)/m', $content, $matches);
        
        if (empty($matches[1])) return;
        
        $selectors = array_unique($matches[1]);
        $bem_compliant = 0;
        $total_selectors = count($selectors);
        
        foreach ($selectors as $selector) {
            $is_bem = false;
            
            // Prüfe gegen BEM Patterns
            foreach ($this->bem_patterns as $type => $pattern) {
                if (preg_match($pattern, $selector)) {
                    $is_bem = true;
                    break;
                }
            }
            
            if ($is_bem) {
                $bem_compliant++;
            } else {
                // Ausnahmen für spezielle Selektoren
                if (!$this->isSpecialSelector($selector)) {
                    $this->analysis_results['bem_violations'][] = [
                        'file' => $filepath,
                        'selector' => $selector,
                        'suggestion' => $this->suggestBEMName($selector)
                    ];
                }
            }
        }
        
        // BEM Score für diese Datei berechnen
        if ($total_selectors > 0) {
            $file_bem_score = ($bem_compliant / $total_selectors) * 100;
            $this->analysis_results['bem_score'] += $file_bem_score;
        }
    }
    
    /**
     * 🎨 SCSS Variablen analysieren
     */
    private function analyzeVariables($content, $filepath) {
        // Definierte Variablen finden
        preg_match_all('/\$([a-zA-Z][a-zA-Z0-9_-]*)\s*:\s*([^;]+);/m', $content, $defined_vars);
        
        // Verwendete Variablen finden
        preg_match_all('/\$([a-zA-Z][a-zA-Z0-9_-]*)/m', $content, $used_vars);
        
        $defined_count = count(array_unique($defined_vars[1]));
        $used_count = count(array_unique($used_vars[1]));
        
        // Hardcoded Werte prüfen (Farben, Schriftgrößen, etc.)
        $hardcoded_colors = preg_match_all('/#[a-fA-F0-9]{3,6}/', $content);
        $hardcoded_sizes = preg_match_all('/\d+px/', $content);
        
        if ($hardcoded_colors > 5 || $hardcoded_sizes > 10) {
            $this->analysis_results['variable_issues'][] = [
                'file' => $filepath,
                'issue' => 'Zu viele hardcoded Werte',
                'colors' => $hardcoded_colors,
                'sizes' => $hardcoded_sizes
            ];
        }
    }
    
    /**
     * 📱 Responsive Design analysieren
     */
    private function analyzeResponsive($content, $filepath) {
        $responsive_patterns = 0;
        
        foreach ($this->breakpoint_patterns as $pattern) {
            $responsive_patterns += substr_count($content, $pattern);
        }
        
        // Mobile-first Ansatz prüfen
        $mobile_first = strpos($content, 'min-width') !== false;
        
        if ($responsive_patterns === 0) {
            $this->analysis_results['responsive_issues'][] = [
                'file' => $filepath,
                'issue' => 'Keine responsive Patterns gefunden'
            ];
        }
        
        $this->analysis_results['responsive_score'] += $responsive_patterns;
    }
    
    /**
     * ⚡ Performance analysieren
     */
    private function analyzePerformance($content, $filepath) {
        $performance_issues = 0;
        
        // Problematische Selektoren
        $problematic_patterns = [
            '/\*\s*{\s*/' => 'Universal selector (*)',
            '/\..*\s+\..*\s+\..*\s+\..*/' => 'Zu tiefe Verschachtelung (4+ Level)',
            '/!important/' => '!important usage'
        ];
        
        foreach ($problematic_patterns as $pattern => $issue) {
            if (preg_match($pattern, $content)) {
                $performance_issues++;
                $this->analysis_results['performance_issues'][] = [
                    'file' => $filepath,
                    'issue' => $issue
                ];
            }
        }
    }
    
    /**
     * 🔍 Spezielle Selektoren prüfen (Ausnahmen)
     */
    private function isSpecialSelector($selector) {
        $special_patterns = [
            'wp-', 'admin-', 'screen-reader-text', 'sr-only',
            'alignleft', 'alignright', 'aligncenter',
            'container', 'row', 'col-'
        ];
        
        foreach ($special_patterns as $pattern) {
            if (strpos($selector, $pattern) === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 💡 BEM Namen vorschlagen
     */
    private function suggestBEMName($selector) {
        // Einfache BEM Vorschläge basierend auf üblichen Patterns
        if (strpos($selector, '-') !== false) {
            return "Eventuell als Block: " . strtolower($selector);
        }
        
        return "Überlege Block__Element oder Block--Modifier Structure";
    }
    
    /**
     * 📊 Gesamtscores berechnen
     */
    private function calculateScores() {
        $total_files = $this->analysis_results['files_analyzed'];
        
        if ($total_files > 0) {
            $this->analysis_results['bem_score'] = round($this->analysis_results['bem_score'] / $total_files, 1);
            $this->analysis_results['responsive_score'] = min(100, round($this->analysis_results['responsive_score'] * 10, 1));
        }
        
        // Variable Usage Score
        $total_variable_issues = count($this->analysis_results['variable_issues']);
        $this->analysis_results['variable_usage'] = max(0, 100 - ($total_variable_issues * 20));
        
        // Performance Score
        $total_performance_issues = count($this->analysis_results['performance_issues']);
        $this->analysis_results['performance_score'] = max(0, 100 - ($total_performance_issues * 15));
        
        // Sammle alle Issues für Hauptausgabe
        $this->collectAllIssues();
    }
    
    /**
     * 📋 Alle Issues sammeln
     */
    private function collectAllIssues() {
        $all_issues = [];
        
        foreach ($this->analysis_results['bem_violations'] as $violation) {
            $all_issues[] = "BEM: {$violation['file']} - .{$violation['selector']}";
        }
        
        foreach ($this->analysis_results['variable_issues'] as $issue) {
            $all_issues[] = "Variables: {$issue['file']} - {$issue['issue']}";
        }
        
        foreach ($this->analysis_results['responsive_issues'] as $issue) {
            $all_issues[] = "Responsive: {$issue['file']} - {$issue['issue']}";
        }
        
        foreach ($this->analysis_results['performance_issues'] as $issue) {
            $all_issues[] = "Performance: {$issue['file']} - {$issue['issue']}";
        }
        
        $this->analysis_results['issues'] = array_slice($all_issues, 0, 10); // Top 10 Issues
    }
    
    /**
     * 📄 HTML Report generieren
     */
    private function generateHTMLReport() {
        $report_path = dirname(__DIR__) . '/quality-reports/css-analysis.html';
        
        $html = $this->buildHTMLReport();
        file_put_contents($report_path, $html);
    }
    
    /**
     * 🏗️ HTML Report erstellen
     */
    private function buildHTMLReport() {
        $results = $this->analysis_results;
        $timestamp = date('Y-m-d H:i:s');
        $issues_count = count($results['issues']);
        
        $html = '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎨 CSS/BEM Analyse Report - ABF Styleguide</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 30px; }
        .metric { background: #f8f9fa; border-radius: 8px; padding: 20px; text-align: center; border-left: 4px solid #007cba; }
        .metric h3 { margin: 0 0 10px 0; color: #333; }
        .metric .score { font-size: 2.5em; font-weight: bold; color: #007cba; }
        .issues { padding: 0 30px 30px; }
        .issue-list { background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; }
        .issue-item { margin: 5px 0; padding: 5px 0; border-bottom: 1px solid #eee; }
        .timestamp { text-align: center; padding: 20px; color: #666; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🎨 CSS/BEM Struktur Analyse</h1>
            <p>Automatisierte Qualitätsprüfung für ABF Styleguide Theme</p>
        </header>
        
        <div class="metrics">
            <div class="metric">
                <h3>📏 BEM Konformität</h3>
                <div class="score">' . $results['bem_score'] . '%</div>
            </div>
            <div class="metric">
                <h3>🎨 SCSS Variablen</h3>
                <div class="score">' . $results['variable_usage'] . '%</div>
            </div>
            <div class="metric">
                <h3>📱 Responsive Design</h3>
                <div class="score">' . $results['responsive_score'] . '%</div>
            </div>
            <div class="metric">
                <h3>⚡ Performance</h3>
                <div class="score">' . $results['performance_score'] . '%</div>
            </div>
        </div>
        
        <div class="issues">
            <h2>⚠️ Gefundene Probleme (' . $issues_count . ')</h2>
            <div class="issue-list">';
        
        if (empty($results['issues'])) {
            $html .= '<p>✅ Keine kritischen Probleme gefunden!</p>';
        } else {
            foreach ($results['issues'] as $issue) {
                $html .= '<div class="issue-item">• ' . htmlspecialchars($issue) . '</div>';
            }
        }
        
        $html .= '
            </div>
        </div>
        
        <div class="timestamp">
            <p>📊 Analysierte Dateien: ' . $results['files_analyzed'] . ' | ⏰ Generiert: ' . $timestamp . '</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
} 