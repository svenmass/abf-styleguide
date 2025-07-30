<?php
/**
 * 🔒 ABF Security + Performance Linter
 * 
 * Analysiert WordPress Theme auf:
 * - Security Vulnerabilities (OWASP Top 10)
 * - WordPress Security Guidelines
 * - PHP Best Practices & Version Compatibility
 * - Performance Anti-Patterns
 * - Core Web Vitals Optimization
 * - XSS, SQL Injection, CSRF Protection
 * 
 * @package ABF_Styleguide_QA
 * @version 1.0.0
 */

class ABF_Security_Performance_Linter {
    
    private $theme_path;
    private $php_files = [];
    private $js_files = [];
    private $analysis_results = [];
    
    // Security Patterns to check
    private $security_patterns = [
        'xss_vulnerabilities' => [
            '/echo\s+\$_[GET|POST|REQUEST]/' => 'Direct output of user input (XSS risk)',
            '/print\s+\$_[GET|POST|REQUEST]/' => 'Direct output of user input (XSS risk)',
            '/\$_[GET|POST|REQUEST].*echo/' => 'Potential XSS vulnerability',
        ],
        'sql_injection' => [
            '/\$wpdb->query\(\s*[^prepare]/' => 'Direct SQL query without prepare() (SQL Injection risk)',
            '/\$wpdb->get_results\(\s*[^prepare]/' => 'Direct SQL query without prepare()',
            '/mysql_query\s*\(/' => 'Deprecated mysql_query usage',
        ],
        'file_inclusion' => [
            '/include\s*\(\s*\$_[GET|POST|REQUEST]/' => 'Dynamic file inclusion (LFI risk)',
            '/require\s*\(\s*\$_[GET|POST|REQUEST]/' => 'Dynamic file inclusion (LFI risk)',
        ],
        'authentication' => [
            '/wp_verify_nonce\s*\(/' => 'CSRF protection implemented',
            '/check_ajax_referer\s*\(/' => 'AJAX CSRF protection implemented',
            '/current_user_can\s*\(/' => 'Permission check implemented',
        ]
    ];
    
    // Performance Anti-Patterns
    private $performance_patterns = [
        'database' => [
            '/get_posts\(\s*array\s*\(\s*[^}]*\'numberposts\'\s*=>\s*-1/' => 'Unlimited posts query (performance risk)',
            '/WP_Query.*\'posts_per_page\'\s*=>\s*-1/' => 'Unlimited posts query',
            '/wp_query.*\'meta_query\'/' => 'Meta query detected (check indexes)',
        ],
        'assets' => [
            '/wp_enqueue_script.*jquery/' => 'jQuery dependency (check if necessary)',
            '/wp_enqueue_style.*\.css[^?]/' => 'CSS without version parameter',
            '/wp_enqueue_script.*\.js[^?]/' => 'JS without version parameter',
        ],
        'images' => [
            '/<img[^>]*src="[^"]*\.(jpg|jpeg|png|gif)"[^>]*>/' => 'Image without size attributes',
            '/wp_get_attachment_image_src\s*\([^,]*\)/' => 'Image without size parameter',
        ]
    ];
    
    // WordPress Coding Standards
    private $wp_standards = [
        'escaping' => [
            '/esc_html\s*\(/' => 'Proper HTML escaping',
            '/esc_attr\s*\(/' => 'Proper attribute escaping', 
            '/esc_url\s*\(/' => 'Proper URL escaping',
            '/wp_kses\s*\(/' => 'Proper HTML filtering',
        ],
        'sanitization' => [
            '/sanitize_text_field\s*\(/' => 'Text field sanitization',
            '/sanitize_email\s*\(/' => 'Email sanitization',
            '/sanitize_url\s*\(/' => 'URL sanitization',
        ],
        'hooks' => [
            '/add_action\s*\(\s*[\'"]wp_footer[\'"]/' => 'Footer hook usage',
            '/add_action\s*\(\s*[\'"]wp_head[\'"]/' => 'Header hook usage',
            '/do_action\s*\(/' => 'Custom action hook',
            '/apply_filters\s*\(/' => 'Filter hook usage',
        ]
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
        $this->js_files = $this->findFiles($this->theme_path, '*.js');
        
        // Filter out vendor/node_modules
        $this->php_files = array_filter($this->php_files, [$this, 'isRelevantFile']);
        $this->js_files = array_filter($this->js_files, [$this, 'isRelevantFile']);
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
            '.min.css'
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
            'security_score' => 0,
            'performance_score' => 0,
            'wp_standards_score' => 0,
            'php_compatibility_score' => 0,
            'issues' => [],
            'files_analyzed' => count($this->php_files) + count($this->js_files),
            'security_vulnerabilities' => [],
            'performance_issues' => [],
            'wp_standards_violations' => [],
            'php_compatibility_issues' => []
        ];
        
        // Analysiere PHP Dateien
        foreach ($this->php_files as $file) {
            $this->analyzeSecurityPatterns($file);
            $this->analyzePerformancePatterns($file);
            $this->analyzeWPStandards($file);
            $this->analyzePHPCompatibility($file);
        }
        
        // Analysiere JS Dateien für Performance
        foreach ($this->js_files as $file) {
            $this->analyzeJSPerformance($file);
        }
        
        $this->calculateScores();
        $this->generateHTMLReport();
        
        return $this->analysis_results;
    }
    
    /**
     * 🔒 Security Patterns analysieren
     */
    private function analyzeSecurityPatterns($filepath) {
        $content = file_get_contents($filepath);
        $relative_path = str_replace($this->theme_path . '/', '', $filepath);
        
        $security_score = 100; // Start with perfect score
        
        foreach ($this->security_patterns as $category => $patterns) {
            foreach ($patterns as $pattern => $description) {
                if (preg_match($pattern, $content)) {
                    if ($category === 'authentication' && strpos($description, 'implemented') !== false) {
                        // Positive security pattern
                        $this->analysis_results['security_score'] += 5;
                    } else {
                        // Security vulnerability found
                        $security_score -= 20;
                        $this->analysis_results['security_vulnerabilities'][] = [
                            'file' => $relative_path,
                            'category' => $category,
                            'issue' => $description,
                            'severity' => $this->getSeverity($category),
                            'pattern' => $pattern
                        ];
                    }
                }
            }
        }
        
        $this->analysis_results['security_score'] += max(0, $security_score);
    }
    
    /**
     * ⚡ Performance Patterns analysieren
     */
    private function analyzePerformancePatterns($filepath) {
        $content = file_get_contents($filepath);
        $relative_path = str_replace($this->theme_path . '/', '', $filepath);
        
        foreach ($this->performance_patterns as $category => $patterns) {
            foreach ($patterns as $pattern => $description) {
                if (preg_match($pattern, $content)) {
                    $this->analysis_results['performance_issues'][] = [
                        'file' => $relative_path,
                        'category' => $category,
                        'issue' => $description,
                        'impact' => $this->getPerformanceImpact($category)
                    ];
                }
            }
        }
    }
    
    /**
     * 📋 WordPress Standards analysieren
     */
    private function analyzeWPStandards($filepath) {
        $content = file_get_contents($filepath);
        $relative_path = str_replace($this->theme_path . '/', '', $filepath);
        
        $standards_score = 0;
        
        foreach ($this->wp_standards as $category => $patterns) {
            foreach ($patterns as $pattern => $description) {
                if (preg_match($pattern, $content)) {
                    $standards_score += 10; // Bonus for following standards
                }
            }
        }
        
        $this->analysis_results['wp_standards_score'] += $standards_score;
        
        // Check for common violations
        $violations = [
            '/echo\s+[^esc_]/' => 'Unescaped output detected',
            '/\$_[GET|POST|REQUEST].*[^sanitiz]/' => 'Unsanitized user input',
            '/wp_query\s*\(/' => 'Direct wp_query usage (use WP_Query)',
        ];
        
        foreach ($violations as $pattern => $description) {
            if (preg_match($pattern, $content)) {
                $this->analysis_results['wp_standards_violations'][] = [
                    'file' => $relative_path,
                    'issue' => $description
                ];
            }
        }
    }
    
    /**
     * 🐘 PHP Compatibility analysieren
     */
    private function analyzePHPCompatibility($filepath) {
        $content = file_get_contents($filepath);
        $relative_path = str_replace($this->theme_path . '/', '', $filepath);
        
        // Check for PHP version compatibility issues
        $compatibility_issues = [
            '/mysql_[a-zA-Z_]+\s*\(/' => 'Deprecated MySQL functions (PHP 7.0+)',
            '/create_function\s*\(/' => 'create_function deprecated (PHP 7.2+)',
            '/each\s*\(/' => 'each() deprecated (PHP 7.2+)',
            '/ereg[a-zA-Z_]*\s*\(/' => 'POSIX regex deprecated (PHP 7.0+)',
            '/mcrypt_[a-zA-Z_]+\s*\(/' => 'Mcrypt deprecated (PHP 7.2+)',
        ];
        
        foreach ($compatibility_issues as $pattern => $description) {
            if (preg_match($pattern, $content)) {
                $this->analysis_results['php_compatibility_issues'][] = [
                    'file' => $relative_path,
                    'issue' => $description,
                    'severity' => 'high'
                ];
            }
        }
        
        // Check for modern PHP features usage (positive)
        $modern_features = [
            '/\?\?\s/' => 'Null coalescing operator (PHP 7.0+)',
            '/\?\?\=\s/' => 'Null coalescing assignment (PHP 7.4+)',
            '/\?\-\>/' => 'Null safe operator (PHP 8.0+)',
            '/declare\s*\(\s*strict_types\s*=\s*1\s*\)/' => 'Strict types declaration',
        ];
        
        foreach ($modern_features as $pattern => $description) {
            if (preg_match($pattern, $content)) {
                $this->analysis_results['php_compatibility_score'] += 15;
            }
        }
    }
    
    /**
     * 📊 JavaScript Performance analysieren
     */
    private function analyzeJSPerformance($filepath) {
        $content = file_get_contents($filepath);
        $relative_path = str_replace($this->theme_path . '/', '', $filepath);
        
        $js_issues = [
            '/document\.write\s*\(/' => 'document.write blocks parsing',
            '/\$\(document\)\.ready\s*\(/' => 'jQuery document ready (consider vanilla JS)',
            '/setInterval\s*\([^,]*,\s*[1-9]\d{0,2}\)/' => 'High frequency setInterval (< 1000ms)',
            '/innerHTML\s*=/' => 'innerHTML usage (consider textContent)',
        ];
        
        foreach ($js_issues as $pattern => $description) {
            if (preg_match($pattern, $content)) {
                $this->analysis_results['performance_issues'][] = [
                    'file' => $relative_path,
                    'category' => 'javascript',
                    'issue' => $description,
                    'impact' => 'medium'
                ];
            }
        }
    }
    
    /**
     * 🔍 Hilfsfunktionen
     */
    private function getSeverity($category) {
        $severity_map = [
            'xss_vulnerabilities' => 'critical',
            'sql_injection' => 'critical',
            'file_inclusion' => 'high',
            'authentication' => 'medium'
        ];
        
        return $severity_map[$category] ?? 'medium';
    }
    
    private function getPerformanceImpact($category) {
        $impact_map = [
            'database' => 'high',
            'assets' => 'medium',
            'images' => 'medium',
            'javascript' => 'medium'
        ];
        
        return $impact_map[$category] ?? 'low';
    }
    
    /**
     * 📊 Scores berechnen
     */
    private function calculateScores() {
        $total_files = $this->analysis_results['files_analyzed'];
        
        if ($total_files > 0) {
            // Security Score normalisieren
            $this->analysis_results['security_score'] = min(100, max(0, $this->analysis_results['security_score']));
            
            // Performance Score basierend auf Issues
            $performance_issues = count($this->analysis_results['performance_issues']);
            $this->analysis_results['performance_score'] = max(0, 100 - ($performance_issues * 10));
            
            // WordPress Standards Score normalisieren
            $this->analysis_results['wp_standards_score'] = min(100, $this->analysis_results['wp_standards_score']);
            
            // PHP Compatibility Score normalisieren
            $php_issues = count($this->analysis_results['php_compatibility_issues']);
            $this->analysis_results['php_compatibility_score'] = max(0, 100 - ($php_issues * 25) + $this->analysis_results['php_compatibility_score']);
            $this->analysis_results['php_compatibility_score'] = min(100, $this->analysis_results['php_compatibility_score']);
        }
        
        $this->collectAllIssues();
    }
    
    /**
     * 📋 Alle Issues sammeln
     */
    private function collectAllIssues() {
        $all_issues = [];
        
        foreach ($this->analysis_results['security_vulnerabilities'] as $vuln) {
            $severity_emoji = $vuln['severity'] === 'critical' ? '🚨' : ($vuln['severity'] === 'high' ? '⚠️' : '⚡');
            $all_issues[] = "{$severity_emoji} Security: {$vuln['file']} - {$vuln['issue']}";
        }
        
        foreach ($this->analysis_results['performance_issues'] as $issue) {
            $impact_emoji = $issue['impact'] === 'high' ? '🐌' : '⚡';
            $all_issues[] = "{$impact_emoji} Performance: {$issue['file']} - {$issue['issue']}";
        }
        
        foreach ($this->analysis_results['wp_standards_violations'] as $violation) {
            $all_issues[] = "📋 WP Standards: {$violation['file']} - {$violation['issue']}";
        }
        
        foreach ($this->analysis_results['php_compatibility_issues'] as $issue) {
            $all_issues[] = "🐘 PHP: {$issue['file']} - {$issue['issue']}";
        }
        
        $this->analysis_results['issues'] = array_slice($all_issues, 0, 12);
    }
    
    /**
     * 📄 HTML Report generieren
     */
    private function generateHTMLReport() {
        $report_path = dirname(__DIR__) . '/quality-reports/security-performance-analysis.html';
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
    <title>🔒 Security + Performance Report - ABF Styleguide</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; padding: 30px; text-align: center; }
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 30px; }
        .metric { background: #f8f9fa; border-radius: 8px; padding: 20px; text-align: center; border-left: 4px solid #dc3545; }
        .metric.good { border-left-color: #28a745; }
        .metric.warning { border-left-color: #ffc107; }
        .metric h3 { margin: 0 0 10px 0; color: #333; }
        .metric .score { font-size: 2.5em; font-weight: bold; color: #dc3545; }
        .metric.good .score { color: #28a745; }
        .metric.warning .score { color: #ffc107; }
        .issues { padding: 0 30px 30px; }
        .issue-list { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; max-height: 500px; overflow-y: auto; }
        .issue-item { margin: 5px 0; padding: 8px; background: white; border-radius: 4px; border-left: 3px solid #dc3545; font-size: 14px; }
        .timestamp { text-align: center; padding: 20px; color: #666; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🔒 Security + Performance Analyse</h1>
            <p>Kritische Sicherheits- und Performance-Prüfung für IT-Abnahme</p>
        </header>
        
        <div class="metrics">
            <div class="metric ' . ($results['security_score'] >= 80 ? 'good' : ($results['security_score'] >= 60 ? 'warning' : '')) . '">
                <h3>🔒 Security Score</h3>
                <div class="score">' . $results['security_score'] . '%</div>
            </div>
            <div class="metric ' . ($results['performance_score'] >= 80 ? 'good' : ($results['performance_score'] >= 60 ? 'warning' : '')) . '">
                <h3>⚡ Performance Score</h3>
                <div class="score">' . $results['performance_score'] . '%</div>
            </div>
            <div class="metric ' . ($results['wp_standards_score'] >= 80 ? 'good' : ($results['wp_standards_score'] >= 60 ? 'warning' : '')) . '">
                <h3>📋 WP Standards</h3>
                <div class="score">' . $results['wp_standards_score'] . '%</div>
            </div>
            <div class="metric ' . ($results['php_compatibility_score'] >= 80 ? 'good' : ($results['php_compatibility_score'] >= 60 ? 'warning' : '')) . '">
                <h3>🐘 PHP Compatibility</h3>
                <div class="score">' . $results['php_compatibility_score'] . '%</div>
            </div>
        </div>
        
        <div class="issues">
            <h2>⚠️ Gefundene Issues (' . $issues_count . ')</h2>
            <div class="issue-list">';
        
        if (empty($results['issues'])) {
            $html .= '<div class="issue-item" style="border-left-color: #28a745;">✅ Keine kritischen Sicherheits- oder Performance-Issues gefunden!</div>';
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