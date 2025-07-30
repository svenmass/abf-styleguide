<?php
/**
 * 🔧 ABF ACF Fields Consistency Linter
 * 
 * Analysiert ACF Fields auf:
 * - Naming Convention Konsistenz
 * - Typography Field Standardisierung
 * - Color System Integration
 * - Field Structure Patterns
 * - Default Values Konsistenz
 * 
 * @package ABF_Styleguide_QA
 * @version 1.0.0
 */

class ABF_ACF_Consistency_Linter {
    
    private $theme_path;
    private $field_files = [];
    private $analysis_results = [];
    
    // Standard Field Patterns
    private $standard_patterns = [
        'headline' => [
            'text' => 'headline_text',
            'tag' => 'headline_tag', 
            'size' => 'headline_size',
            'weight' => 'headline_weight',
            'color' => 'headline_color'
        ],
        'content' => [
            'text' => 'content_text',
            'align' => 'content_align'
        ],
        'image' => [
            'image' => 'image',
            'alt' => 'image_alt',
            'size' => 'image_size'
        ]
    ];
    
    // Standard Typography Choices
    private $standard_typography = [
        'font_sizes' => ['12', '18', '24', '36', '48', '60', '72'],
        'font_weights' => ['300', '400', '500', '600', '700'],
        'html_tags' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']
    ];
    
    public function __construct($theme_path) {
        $this->theme_path = $theme_path;
        $this->scanFieldFiles();
    }
    
    /**
     * 📁 Scanne alle ACF Field Dateien
     */
    private function scanFieldFiles() {
        $blocks_path = $this->theme_path . '/blocks';
        
        if (!is_dir($blocks_path)) {
            return;
        }
        
        $directories = scandir($blocks_path);
        foreach ($directories as $dir) {
            if ($dir === '.' || $dir === '..') continue;
            
            $field_file = $blocks_path . '/' . $dir . '/fields.php';
            if (file_exists($field_file)) {
                $this->field_files[] = [
                    'block' => $dir,
                    'path' => $field_file
                ];
            }
        }
    }
    
    /**
     * 🚀 Hauptanalyse ausführen
     */
    public function analyze() {
        $this->analysis_results = [
            'naming_score' => 0,
            'typography_score' => 0,
            'color_score' => 0,
            'structure_score' => 0,
            'issues' => [],
            'files_analyzed' => count($this->field_files),
            'naming_violations' => [],
            'typography_issues' => [],
            'color_issues' => [],
            'structure_issues' => []
        ];
        
        foreach ($this->field_files as $file_info) {
            $this->analyzeFieldFile($file_info);
        }
        
        $this->calculateScores();
        $this->generateHTMLReport();
        
        return $this->analysis_results;
    }
    
    /**
     * 📄 Einzelne Field-Datei analysieren
     */
    private function analyzeFieldFile($file_info) {
        $block_name = $file_info['block'];
        $filepath = $file_info['path'];
        
        // Field-Datei einlesen
        $content = file_get_contents($filepath);
        
        // PHP-Array extrahieren (vereinfacht)
        $fields_data = $this->extractFieldsData($content);
        
        if ($fields_data) {
            // Naming Convention Analysis
            $this->analyzeNamingConvention($fields_data, $block_name);
            
            // Typography Fields Analysis
            $this->analyzeTypographyFields($fields_data, $block_name);
            
            // Color System Analysis
            $this->analyzeColorSystem($fields_data, $block_name);
            
            // Structure Pattern Analysis
            $this->analyzeStructurePatterns($fields_data, $block_name);
        }
    }
    
    /**
     * 🔍 Field-Daten aus PHP-Datei extrahieren
     */
    private function extractFieldsData($content) {
        // Vereinfachte Extraktion - suche nach field names
        preg_match_all("/'name'\s*=>\s*'([^']+)'/", $content, $matches);
        
        $fields = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $field_name) {
                if (!empty($field_name)) {
                    $fields[] = [
                        'name' => $field_name,
                        'raw_content' => $this->extractFieldContext($content, $field_name)
                    ];
                }
            }
        }
        
        return $fields;
    }
    
    /**
     * 🔍 Field-Kontext extrahieren
     */
    private function extractFieldContext($content, $field_name) {
        // Finde den Array-Block für dieses Field
        $pattern = "/array\s*\(\s*[^)]*'name'\s*=>\s*'{$field_name}'[^}]*\)/s";
        preg_match($pattern, $content, $matches);
        
        return isset($matches[0]) ? $matches[0] : '';
    }
    
    /**
     * 📝 Naming Convention analysieren
     */
    private function analyzeNamingConvention($fields, $block_name) {
        $naming_issues = 0;
        $total_fields = count($fields);
        
        foreach ($fields as $field) {
            $field_name = $field['name'];
            
            // Skip empty field names (tabs, etc.)
            if (empty($field_name)) continue;
            
            // Check für inkonsistente Prefixes
            $inconsistent_prefix = $this->checkInconsistentPrefix($field_name, $block_name);
            if ($inconsistent_prefix) {
                $naming_issues++;
                $this->analysis_results['naming_violations'][] = [
                    'block' => $block_name,
                    'field' => $field_name,
                    'issue' => $inconsistent_prefix,
                    'suggestion' => $this->suggestStandardName($field_name)
                ];
            }
            
            // Check für non-standard naming patterns
            if (!$this->followsStandardPattern($field_name)) {
                $naming_issues++;
                $this->analysis_results['naming_violations'][] = [
                    'block' => $block_name,
                    'field' => $field_name,
                    'issue' => 'Non-standard naming pattern',
                    'suggestion' => $this->suggestStandardName($field_name)
                ];
            }
        }
        
        // Score berechnen
        if ($total_fields > 0) {
            $naming_score = max(0, (($total_fields - $naming_issues) / $total_fields) * 100);
            $this->analysis_results['naming_score'] += $naming_score;
        }
    }
    
    /**
     * 🎨 Typography Fields analysieren
     */
    private function analyzeTypographyFields($fields, $block_name) {
        $typography_fields = ['size', 'weight', 'tag', 'font', 'color'];
        $found_typography = false;
        
        foreach ($fields as $field) {
            $field_name = $field['name'];
            $field_content = $field['raw_content'];
            
            // Check if this is a typography-related field
            foreach ($typography_fields as $typo_type) {
                if (strpos($field_name, $typo_type) !== false) {
                    $found_typography = true;
                    
                    // Check if it uses standard choices
                    if ($typo_type === 'size' && !$this->usesStandardFontSizes($field_content)) {
                        $this->analysis_results['typography_issues'][] = [
                            'block' => $block_name,
                            'field' => $field_name,
                            'issue' => 'Non-standard font size choices'
                        ];
                    }
                    
                    if ($typo_type === 'weight' && !$this->usesStandardFontWeights($field_content)) {
                        $this->analysis_results['typography_issues'][] = [
                            'block' => $block_name,
                            'field' => $field_name,
                            'issue' => 'Non-standard font weight choices'
                        ];
                    }
                    
                    if ($typo_type === 'tag' && !$this->usesStandardHTMLTags($field_content)) {
                        $this->analysis_results['typography_issues'][] = [
                            'block' => $block_name,
                            'field' => $field_name,
                            'issue' => 'Non-standard HTML tag choices'
                        ];
                    }
                }
            }
        }
        
        // Typography Score
        if ($found_typography) {
            $this->analysis_results['typography_score'] += 80; // Base score if typography found
        }
    }
    
    /**
     * 🎨 Color System analysieren
     */
    private function analyzeColorSystem($fields, $block_name) {
        foreach ($fields as $field) {
            $field_name = $field['name'];
            $field_content = $field['raw_content'];
            
            if (strpos($field_name, 'color') !== false) {
                // Check if it uses the central color function
                if (strpos($field_content, 'abf_get_color_choices') === false) {
                    $this->analysis_results['color_issues'][] = [
                        'block' => $block_name,
                        'field' => $field_name,
                        'issue' => 'Does not use central color function'
                    ];
                } else {
                    $this->analysis_results['color_score'] += 20; // Bonus für korrekte Nutzung
                }
            }
        }
    }
    
    /**
     * 🏗️ Structure Patterns analysieren
     */
    private function analyzeStructurePatterns($fields, $block_name) {
        // Check für Tab-Strukturierung
        $has_tabs = false;
        foreach ($fields as $field) {
            if (strpos($field['raw_content'], "'type' => 'tab'") !== false) {
                $has_tabs = true;
                break;
            }
        }
        
        if ($has_tabs) {
            $this->analysis_results['structure_score'] += 25; // Bonus für Tab-Organisation
        }
        
        // Check für Wrapper-Konsistenz
        $wrapper_pattern = "/wrapper[^}]*'width'\s*=>\s*'(\d+)'/";
        preg_match_all($wrapper_pattern, implode(' ', array_column($fields, 'raw_content')), $matches);
        
        if (!empty($matches[1])) {
            $widths = array_unique($matches[1]);
            if (count($widths) > 5) { // Zu viele verschiedene Breiten
                $this->analysis_results['structure_issues'][] = [
                    'block' => $block_name,
                    'issue' => 'Too many different wrapper widths: ' . implode(', ', $widths)
                ];
            }
        }
    }
    
    /**
     * 🔍 Hilfsfunktionen für Pattern-Checks
     */
    private function checkInconsistentPrefix($field_name, $block_name) {
        // Check für Block-spezifische Prefixes wie 'sp_', 'sm_'
        if (preg_match('/^[a-z]{1,3}_/', $field_name)) {
            return 'Uses block-specific prefix instead of semantic naming';
        }
        return false;
    }
    
    private function followsStandardPattern($field_name) {
        $standard_suffixes = ['text', 'tag', 'size', 'weight', 'color', 'align', 'image', 'content'];
        
        foreach ($standard_suffixes as $suffix) {
            if (strpos($field_name, $suffix) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    private function suggestStandardName($field_name) {
        // Einfache Vorschläge basierend auf häufigen Patterns
        if (strpos($field_name, 'headline') !== false || strpos($field_name, 'title') !== false) {
            return 'headline_text, headline_tag, headline_size, etc.';
        }
        
        if (strpos($field_name, 'content') !== false || strpos($field_name, 'text') !== false) {
            return 'content_text, content_align';
        }
        
        return 'Follow semantic naming: {purpose}_{type}';
    }
    
    private function usesStandardFontSizes($content) {
        $standard_sizes = implode('|', $this->standard_typography['font_sizes']);
        return preg_match("/({$standard_sizes})/", $content);
    }
    
    private function usesStandardFontWeights($content) {
        $standard_weights = implode('|', $this->standard_typography['font_weights']);
        return preg_match("/({$standard_weights})/", $content);
    }
    
    private function usesStandardHTMLTags($content) {
        $standard_tags = implode('|', $this->standard_typography['html_tags']);
        return preg_match("/({$standard_tags})/", $content);
    }
    
    /**
     * 📊 Scores berechnen
     */
    private function calculateScores() {
        $total_files = $this->analysis_results['files_analyzed'];
        
        if ($total_files > 0) {
            // Naming Score (Durchschnitt)
            $this->analysis_results['naming_score'] = round($this->analysis_results['naming_score'] / $total_files, 1);
            
            // Typography Score (max 100)
            $this->analysis_results['typography_score'] = min(100, $this->analysis_results['typography_score']);
            
            // Color Score (max 100)
            $this->analysis_results['color_score'] = min(100, $this->analysis_results['color_score']);
            
            // Structure Score (max 100)
            $this->analysis_results['structure_score'] = min(100, $this->analysis_results['structure_score']);
        }
        
        // Sammle alle Issues
        $this->collectAllIssues();
    }
    
    /**
     * 📋 Alle Issues sammeln
     */
    private function collectAllIssues() {
        $all_issues = [];
        
        foreach ($this->analysis_results['naming_violations'] as $violation) {
            $all_issues[] = "Naming: {$violation['block']} - {$violation['field']} ({$violation['issue']})";
        }
        
        foreach ($this->analysis_results['typography_issues'] as $issue) {
            $all_issues[] = "Typography: {$issue['block']} - {$issue['field']} ({$issue['issue']})";
        }
        
        foreach ($this->analysis_results['color_issues'] as $issue) {
            $all_issues[] = "Color: {$issue['block']} - {$issue['field']} ({$issue['issue']})";
        }
        
        foreach ($this->analysis_results['structure_issues'] as $issue) {
            $all_issues[] = "Structure: {$issue['block']} - {$issue['issue']}";
        }
        
        $this->analysis_results['issues'] = array_slice($all_issues, 0, 15); // Top 15 Issues
    }
    
    /**
     * 📄 HTML Report generieren
     */
    private function generateHTMLReport() {
        $report_path = dirname(__DIR__) . '/quality-reports/acf-analysis.html';
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
    <title>🔧 ACF Fields Consistency Report - ABF Styleguide</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; text-align: center; }
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 30px; }
        .metric { background: #f8f9fa; border-radius: 8px; padding: 20px; text-align: center; border-left: 4px solid #28a745; }
        .metric h3 { margin: 0 0 10px 0; color: #333; }
        .metric .score { font-size: 2.5em; font-weight: bold; color: #28a745; }
        .issues { padding: 0 30px 30px; }
        .issue-list { background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; max-height: 400px; overflow-y: auto; }
        .issue-item { margin: 5px 0; padding: 5px 0; border-bottom: 1px solid #eee; font-size: 14px; }
        .timestamp { text-align: center; padding: 20px; color: #666; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🔧 ACF Fields Consistency Analyse</h1>
            <p>Automatisierte Prüfung der ACF Field Standardisierung</p>
        </header>
        
        <div class="metrics">
            <div class="metric">
                <h3>📝 Naming Convention</h3>
                <div class="score">' . $results['naming_score'] . '%</div>
            </div>
            <div class="metric">
                <h3>🎨 Typography System</h3>
                <div class="score">' . $results['typography_score'] . '%</div>
            </div>
            <div class="metric">
                <h3>🌈 Color Integration</h3>
                <div class="score">' . $results['color_score'] . '%</div>
            </div>
            <div class="metric">
                <h3>🏗️ Structure Pattern</h3>
                <div class="score">' . $results['structure_score'] . '%</div>
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
            <p>📊 Analysierte Field-Dateien: ' . $results['files_analyzed'] . ' | ⏰ Generiert: ' . $timestamp . '</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
} 