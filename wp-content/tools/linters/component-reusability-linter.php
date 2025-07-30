<?php
/**
 * 🔄 ABF Component Reusability + Code Quality Linter
 * 
 * Analysiert WordPress Theme auf:
 * - Duplicate Code Detection
 * - Shared Component Analysis
 * - Button/Link Pattern Consistency
 * - Code Complexity Analysis
 * - Maintainability Index
 * - Design System Compliance
 * - Component Architecture Quality
 * 
 * @package ABF_Styleguide_QA
 * @version 1.0.0
 */

class ABF_Component_Reusability_Linter {
    
    private $theme_path;
    private $php_files = [];
    private $scss_files = [];
    private $template_files = [];
    private $analysis_results = [];
    
    // Component Patterns to analyze
    private $component_patterns = [
        'buttons' => [
            'css_classes' => ['btn', 'button', 'cta', 'link-button'],
            'html_patterns' => ['<button', '<a.*class.*button', '<input.*type.*button'],
            'php_functions' => ['wp_link_pages', 'get_permalink', 'the_permalink']
        ],
        'cards' => [
            'css_classes' => ['card', 'post-card', 'content-card'],
            'html_patterns' => ['<article', '<div.*class.*card'],
            'php_functions' => ['get_the_excerpt', 'the_excerpt', 'get_post_thumbnail']
        ],
        'forms' => [
            'css_classes' => ['form', 'input', 'textarea', 'select'],
            'html_patterns' => ['<form', '<input', '<textarea', '<select'],
            'php_functions' => ['wp_nonce_field', 'get_search_form']
        ],
        'navigation' => [
            'css_classes' => ['nav', 'menu', 'breadcrumb'],
            'html_patterns' => ['<nav', '<ul.*menu', '<ol.*breadcrumb'],
            'php_functions' => ['wp_nav_menu', 'get_post_navigation', 'paginate_links']
        ]
    ];
    
    // Code Quality Metrics
    private $complexity_thresholds = [
        'cyclomatic_complexity' => 10,
        'function_length' => 50,
        'class_length' => 500,
        'nesting_depth' => 4
    ];
    
    public function __construct($theme_path) {
        $this->theme_path = $theme_path;
        $this->scanFiles();
    }
    
    /**
     * 📁 Scanne alle relevanten Dateien
     */
    private function scanFiles() {
        // PHP Dateien finden
        $this->php_files = $this->findFiles($this->theme_path, '*.php');
        $this->scss_files = $this->findFiles($this->theme_path, '*.scss');
        
        // Template Dateien identifizieren
        $this->template_files = array_filter($this->php_files, function($file) {
            $filename = basename($file);
            return strpos($filename, 'template.php') !== false || 
                   strpos($filename, 'single-') === 0 ||
                   strpos($filename, 'page-') === 0 ||
                   strpos($filename, 'archive-') === 0;
        });
        
        // Filter out vendor/generated files
        $this->php_files = array_filter($this->php_files, [$this, 'isRelevantFile']);
        $this->scss_files = array_filter($this->scss_files, [$this, 'isRelevantFile']);
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
     * ✅ Prüfe ob Datei relevant ist
     */
    private function isRelevantFile($filepath) {
        $exclude_patterns = [
            '/node_modules/',
            '/vendor/',
            '/\.git/',
            '.min.js',
            '.min.css',
            '/tools/'
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
            'reusability_score' => 0,
            'code_quality_score' => 0,
            'component_consistency_score' => 0,
            'maintainability_index' => 0,
            'issues' => [],
            'files_analyzed' => count($this->php_files) + count($this->scss_files),
            'duplicate_code_issues' => [],
            'component_inconsistencies' => [],
            'complexity_issues' => [],
            'reusability_opportunities' => []
        ];
        
        // Duplicate Code Detection
        $this->detectDuplicateCode();
        
        // Component Analysis
        $this->analyzeComponentPatterns();
        
        // Code Complexity Analysis
        $this->analyzeCodeComplexity();
        
        // Maintainability Analysis
        $this->calculateMaintainabilityIndex();
        
        $this->calculateScores();
        $this->generateHTMLReport();
        
        return $this->analysis_results;
    }
    
    /**
     * 🔍 Duplicate Code Detection
     */
    private function detectDuplicateCode() {
        $code_blocks = [];
        
        // Analysiere PHP Dateien
        foreach ($this->php_files as $file) {
            $content = file_get_contents($file);
            $relative_path = str_replace($this->theme_path . '/', '', $file);
            
            // Extrahiere Funktionen
            preg_match_all('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\([^{]*\)\s*\{([^{}]*(?:\{[^{}]*\}[^{}]*)*)\}/s', $content, $functions);
            
            if (!empty($functions[0])) {
                foreach ($functions[0] as $i => $function_code) {
                    $function_name = $functions[1][$i];
                    $function_body = $functions[2][$i];
                    
                    // Normalisiere Code (entferne Whitespace, Kommentare)
                    $normalized = $this->normalizeCode($function_body);
                    
                    if (strlen($normalized) > 100) { // Nur längere Funktionen prüfen
                        $hash = md5($normalized);
                        
                        if (!isset($code_blocks[$hash])) {
                            $code_blocks[$hash] = [];
                        }
                        
                        $code_blocks[$hash][] = [
                            'file' => $relative_path,
                            'function' => $function_name,
                            'size' => strlen($normalized)
                        ];
                    }
                }
            }
            
            // CSS/HTML Block Patterns suchen
            $this->findDuplicateHTMLPatterns($content, $relative_path);
        }
        
        // Duplicates identifizieren
        foreach ($code_blocks as $hash => $occurrences) {
            if (count($occurrences) > 1) {
                $this->analysis_results['duplicate_code_issues'][] = [
                    'type' => 'duplicate_function',
                    'occurrences' => $occurrences,
                    'severity' => count($occurrences) > 2 ? 'high' : 'medium'
                ];
            }
        }
        
        // SCSS Duplicates
        $this->detectSCSSPatterns();
    }
    
    /**
     * 🎨 SCSS Pattern Duplicates
     */
    private function detectSCSSPatterns() {
        $css_patterns = [];
        
        foreach ($this->scss_files as $file) {
            $content = file_get_contents($file);
            $relative_path = str_replace($this->theme_path . '/', '', $file);
            
            // CSS Rules extrahieren
            preg_match_all('/([.#][a-zA-Z0-9_-]+[^{]*)\s*\{([^{}]+)\}/s', $content, $rules);
            
            if (!empty($rules[0])) {
                foreach ($rules[0] as $i => $rule) {
                    $selector = trim($rules[1][$i]);
                    $properties = trim($rules[2][$i]);
                    
                    $normalized_props = $this->normalizeCSSProperties($properties);
                    
                    if (strlen($normalized_props) > 50) {
                        $hash = md5($normalized_props);
                        
                        if (!isset($css_patterns[$hash])) {
                            $css_patterns[$hash] = [];
                        }
                        
                        $css_patterns[$hash][] = [
                            'file' => $relative_path,
                            'selector' => $selector,
                            'properties' => $properties
                        ];
                    }
                }
            }
        }
        
        // CSS Duplicates identifizieren
        foreach ($css_patterns as $hash => $occurrences) {
            if (count($occurrences) > 1) {
                $this->analysis_results['duplicate_code_issues'][] = [
                    'type' => 'duplicate_css',
                    'occurrences' => $occurrences,
                    'severity' => 'medium'
                ];
            }
        }
    }
    
    /**
     * 🔄 Component Pattern Analysis
     */
    private function analyzeComponentPatterns() {
        $component_usage = [];
        
        foreach ($this->component_patterns as $component_type => $patterns) {
            $component_usage[$component_type] = [
                'files' => [],
                'variations' => [],
                'consistency_score' => 0
            ];
            
            // Analysiere alle Template Dateien
            foreach ($this->template_files as $file) {
                $content = file_get_contents($file);
                $relative_path = str_replace($this->theme_path . '/', '', $file);
                
                $found_patterns = [];
                
                // CSS Classes suchen
                foreach ($patterns['css_classes'] as $class) {
                    if (preg_match('/class=["\'][^"\']*' . $class . '[^"\']*["\']/', $content)) {
                        $found_patterns[] = "CSS: .$class";
                    }
                }
                
                // HTML Patterns suchen
                foreach ($patterns['html_patterns'] as $pattern) {
                    if (preg_match('/' . $pattern . '/i', $content)) {
                        $found_patterns[] = "HTML: $pattern";
                    }
                }
                
                // PHP Functions suchen
                foreach ($patterns['php_functions'] as $function) {
                    if (strpos($content, $function) !== false) {
                        $found_patterns[] = "PHP: $function()";
                    }
                }
                
                if (!empty($found_patterns)) {
                    $component_usage[$component_type]['files'][] = $relative_path;
                    $component_usage[$component_type]['variations'] = array_merge(
                        $component_usage[$component_type]['variations'], 
                        $found_patterns
                    );
                }
            }
            
            // Component Consistency bewerten
            $unique_variations = array_unique($component_usage[$component_type]['variations']);
            $file_count = count($component_usage[$component_type]['files']);
            
            if ($file_count > 0) {
                // Weniger Variationen = bessere Konsistenz
                $consistency = max(0, 100 - (count($unique_variations) * 10));
                $component_usage[$component_type]['consistency_score'] = $consistency;
                
                if ($consistency < 70) {
                    $this->analysis_results['component_inconsistencies'][] = [
                        'component' => $component_type,
                        'files' => $file_count,
                        'variations' => count($unique_variations),
                        'consistency_score' => $consistency,
                        'issue' => "Too many variations for $component_type component"
                    ];
                }
            }
        }
        
        // Reusability Opportunities identifizieren
        $this->identifyReusabilityOpportunities($component_usage);
    }
    
    /**
     * 🧠 Code Complexity Analysis
     */
    private function analyzeCodeComplexity() {
        foreach ($this->php_files as $file) {
            $content = file_get_contents($file);
            $relative_path = str_replace($this->theme_path . '/', '', $file);
            
            // Funktionen analysieren
            preg_match_all('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\([^{]*\)\s*\{([^{}]*(?:\{[^{}]*\}[^{}]*)*)\}/s', $content, $functions);
            
            if (!empty($functions[0])) {
                foreach ($functions[0] as $i => $function_code) {
                    $function_name = $functions[1][$i];
                    $function_body = $functions[2][$i];
                    
                    // Cyclomatic Complexity berechnen
                    $complexity = $this->calculateCyclomaticComplexity($function_body);
                    
                    // Function Length
                    $lines = count(explode("\n", $function_body));
                    
                    // Nesting Depth
                    $nesting = $this->calculateNestingDepth($function_body);
                    
                    // Issues identifizieren
                    if ($complexity > $this->complexity_thresholds['cyclomatic_complexity']) {
                        $this->analysis_results['complexity_issues'][] = [
                            'file' => $relative_path,
                            'function' => $function_name,
                            'type' => 'high_complexity',
                            'value' => $complexity,
                            'threshold' => $this->complexity_thresholds['cyclomatic_complexity']
                        ];
                    }
                    
                    if ($lines > $this->complexity_thresholds['function_length']) {
                        $this->analysis_results['complexity_issues'][] = [
                            'file' => $relative_path,
                            'function' => $function_name,
                            'type' => 'long_function',
                            'value' => $lines,
                            'threshold' => $this->complexity_thresholds['function_length']
                        ];
                    }
                    
                    if ($nesting > $this->complexity_thresholds['nesting_depth']) {
                        $this->analysis_results['complexity_issues'][] = [
                            'file' => $relative_path,
                            'function' => $function_name,
                            'type' => 'deep_nesting',
                            'value' => $nesting,
                            'threshold' => $this->complexity_thresholds['nesting_depth']
                        ];
                    }
                }
            }
        }
    }
    
    /**
     * 📊 Maintainability Index berechnen
     */
    private function calculateMaintainabilityIndex() {
        $total_complexity = 0;
        $total_functions = 0;
        $total_duplicates = count($this->analysis_results['duplicate_code_issues']);
        $total_files = $this->analysis_results['files_analyzed'];
        
        foreach ($this->analysis_results['complexity_issues'] as $issue) {
            if ($issue['type'] === 'high_complexity') {
                $total_complexity += $issue['value'];
                $total_functions++;
            }
        }
        
        // Maintainability Index Formula (vereinfacht)
        $avg_complexity = $total_functions > 0 ? $total_complexity / $total_functions : 5;
        $duplicate_penalty = min(50, $total_duplicates * 5);
        $complexity_penalty = min(30, ($avg_complexity - 5) * 3);
        
        $maintainability = max(0, 100 - $duplicate_penalty - $complexity_penalty);
        $this->analysis_results['maintainability_index'] = round($maintainability, 1);
    }
    
    /**
     * 💡 Reusability Opportunities identifizieren
     */
    private function identifyReusabilityOpportunities($component_usage) {
        foreach ($component_usage as $component_type => $usage) {
            if (count($usage['files']) > 2 && $usage['consistency_score'] < 80) {
                $this->analysis_results['reusability_opportunities'][] = [
                    'component' => $component_type,
                    'files' => count($usage['files']),
                    'potential_savings' => count($usage['files']) * 20, // Zeilen Code
                    'suggestion' => "Create reusable $component_type component"
                ];
            }
        }
    }
    
    /**
     * 🔧 Hilfsfunktionen
     */
    private function normalizeCode($code) {
        // Entferne Kommentare und Whitespace
        $code = preg_replace('/\/\*.*?\*\//s', '', $code);
        $code = preg_replace('/\/\/.*$/m', '', $code);
        $code = preg_replace('/\s+/', ' ', $code);
        return trim($code);
    }
    
    private function normalizeCSSProperties($properties) {
        $properties = preg_replace('/\/\*.*?\*\//s', '', $properties);
        $properties = preg_replace('/\s+/', ' ', $properties);
        $lines = explode(';', $properties);
        sort($lines); // Sortiere Properties für besseren Vergleich
        return implode(';', $lines);
    }
    
    private function calculateCyclomaticComplexity($code) {
        $complexity = 1; // Base complexity
        
        // Zähle Decision Points
        $patterns = ['if', 'else', 'elseif', 'for', 'foreach', 'while', 'switch', 'case', '&&', '||', '?'];
        
        foreach ($patterns as $pattern) {
            $complexity += substr_count(strtolower($code), $pattern);
        }
        
        return $complexity;
    }
    
    private function calculateNestingDepth($code) {
        $depth = 0;
        $max_depth = 0;
        
        for ($i = 0; $i < strlen($code); $i++) {
            if ($code[$i] === '{') {
                $depth++;
                $max_depth = max($max_depth, $depth);
            } elseif ($code[$i] === '}') {
                $depth--;
            }
        }
        
        return $max_depth;
    }
    
    private function findDuplicateHTMLPatterns($content, $filepath) {
        // Suche nach wiederholenden HTML Strukturen
        $html_blocks = [];
        preg_match_all('/<div[^>]*class="[^"]*"[^>]*>.*?<\/div>/s', $content, $matches);
        
        foreach ($matches[0] as $block) {
            if (strlen($block) > 100) {
                $normalized = $this->normalizeCode($block);
                $hash = md5($normalized);
                
                if (!isset($html_blocks[$hash])) {
                    $html_blocks[$hash] = [];
                }
                
                $html_blocks[$hash][] = [
                    'file' => $filepath,
                    'pattern' => substr($normalized, 0, 100) . '...'
                ];
            }
        }
        
        // Duplicate HTML Patterns zur Analyse hinzufügen
        foreach ($html_blocks as $hash => $occurrences) {
            if (count($occurrences) > 1) {
                $this->analysis_results['duplicate_code_issues'][] = [
                    'type' => 'duplicate_html',
                    'occurrences' => $occurrences,
                    'severity' => 'low'
                ];
            }
        }
    }
    
    /**
     * 📊 Scores berechnen
     */
    private function calculateScores() {
        $total_files = $this->analysis_results['files_analyzed'];
        
        if ($total_files > 0) {
            // Reusability Score (basierend auf Opportunities und Consistency)
            $opportunities = count($this->analysis_results['reusability_opportunities']);
            $inconsistencies = count($this->analysis_results['component_inconsistencies']);
            $this->analysis_results['reusability_score'] = max(0, 100 - ($opportunities * 15) - ($inconsistencies * 10));
            
            // Code Quality Score (basierend auf Complexity Issues)
            $complexity_issues = count($this->analysis_results['complexity_issues']);
            $this->analysis_results['code_quality_score'] = max(0, 100 - ($complexity_issues * 8));
            
            // Component Consistency Score (Durchschnitt der Component Consistencies)
            $total_consistency = 0;
            $component_count = 0;
            foreach ($this->analysis_results['component_inconsistencies'] as $inconsistency) {
                $total_consistency += $inconsistency['consistency_score'];
                $component_count++;
            }
            
            if ($component_count > 0) {
                $this->analysis_results['component_consistency_score'] = round($total_consistency / $component_count, 1);
            } else {
                $this->analysis_results['component_consistency_score'] = 85; // Default wenn keine Inconsistencies
            }
        }
        
        $this->collectAllIssues();
    }
    
    /**
     * 📋 Alle Issues sammeln
     */
    private function collectAllIssues() {
        $all_issues = [];
        
        foreach ($this->analysis_results['duplicate_code_issues'] as $duplicate) {
            $type_emoji = $duplicate['type'] === 'duplicate_function' ? '🔄' : ($duplicate['type'] === 'duplicate_css' ? '🎨' : '📄');
            $all_issues[] = "{$type_emoji} Duplicate: {$duplicate['type']} found in " . count($duplicate['occurrences']) . " files";
        }
        
        foreach ($this->analysis_results['complexity_issues'] as $issue) {
            $type_emoji = $issue['type'] === 'high_complexity' ? '🧠' : ($issue['type'] === 'long_function' ? '📏' : '🔗');
            $all_issues[] = "{$type_emoji} Complexity: {$issue['file']} - {$issue['function']}() ({$issue['type']}: {$issue['value']})";
        }
        
        foreach ($this->analysis_results['component_inconsistencies'] as $inconsistency) {
            $all_issues[] = "🔧 Component: {$inconsistency['component']} has {$inconsistency['variations']} variations across {$inconsistency['files']} files";
        }
        
        foreach ($this->analysis_results['reusability_opportunities'] as $opportunity) {
            $all_issues[] = "💡 Opportunity: {$opportunity['suggestion']} (potential {$opportunity['potential_savings']} lines saved)";
        }
        
        $this->analysis_results['issues'] = array_slice($all_issues, 0, 15);
    }
    
    /**
     * 📄 HTML Report generieren
     */
    private function generateHTMLReport() {
        $report_path = dirname(__DIR__) . '/quality-reports/component-reusability-analysis.html';
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
    <title>🔄 Component Reusability Report - ABF Styleguide</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #6f42c1 0%, #007bff 100%); color: white; padding: 30px; text-align: center; }
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 30px; }
        .metric { background: #f8f9fa; border-radius: 8px; padding: 20px; text-align: center; border-left: 4px solid #6f42c1; }
        .metric.good { border-left-color: #28a745; }
        .metric.warning { border-left-color: #ffc107; }
        .metric.danger { border-left-color: #dc3545; }
        .metric h3 { margin: 0 0 10px 0; color: #333; }
        .metric .score { font-size: 2.5em; font-weight: bold; color: #6f42c1; }
        .metric.good .score { color: #28a745; }
        .metric.warning .score { color: #ffc107; }
        .metric.danger .score { color: #dc3545; }
        .issues { padding: 0 30px 30px; }
        .issue-list { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; max-height: 600px; overflow-y: auto; }
        .issue-item { margin: 5px 0; padding: 8px; background: white; border-radius: 4px; border-left: 3px solid #6f42c1; font-size: 14px; }
        .timestamp { text-align: center; padding: 20px; color: #666; border-top: 1px solid #eee; }
        .maintainability { background: linear-gradient(135deg, #17a2b8, #20c997); color: white; border-radius: 8px; padding: 20px; margin: 20px 30px; text-align: center; }
        .maintainability h3 { margin: 0 0 10px 0; }
        .maintainability .index { font-size: 3em; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🔄 Component Reusability Analyse</h1>
            <p>Code-Qualität und Wiederverwendbarkeits-Assessment</p>
        </header>
        
        <div class="maintainability">
            <h3>📊 Maintainability Index</h3>
            <div class="index">' . $results['maintainability_index'] . '%</div>
            <p>Gesamtbewertung der Code-Wartbarkeit</p>
        </div>
        
        <div class="metrics">
            <div class="metric ' . ($results['reusability_score'] >= 80 ? 'good' : ($results['reusability_score'] >= 60 ? 'warning' : 'danger')) . '">
                <h3>🔄 Reusability Score</h3>
                <div class="score">' . $results['reusability_score'] . '%</div>
            </div>
            <div class="metric ' . ($results['code_quality_score'] >= 80 ? 'good' : ($results['code_quality_score'] >= 60 ? 'warning' : 'danger')) . '">
                <h3>🧠 Code Quality</h3>
                <div class="score">' . $results['code_quality_score'] . '%</div>
            </div>
            <div class="metric ' . ($results['component_consistency_score'] >= 80 ? 'good' : ($results['component_consistency_score'] >= 60 ? 'warning' : 'danger')) . '">
                <h3>🔧 Component Consistency</h3>
                <div class="score">' . $results['component_consistency_score'] . '%</div>
            </div>
        </div>
        
        <div class="issues">
            <h2>⚠️ Gefundene Issues & Opportunities (' . $issues_count . ')</h2>
            <div class="issue-list">';
        
        if (empty($results['issues'])) {
            $html .= '<div class="issue-item" style="border-left-color: #28a745;">✅ Keine kritischen Code-Qualitätsprobleme gefunden!</div>';
        } else {
            foreach ($results['issues'] as $issue) {
                $html .= '<div class="issue-item">' . htmlspecialchars($issue) . '</div>';
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