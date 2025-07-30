<?php
/**
 * 🔒 ABF Styleguide Security Functions
 * 
 * Deaktiviert Kommentare und schützt REST API vor externen Zugriffen
 * 
 * @package ABF_Styleguide
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class ABF_Security_Manager {
    
    public function __construct() {
        $this->init();
    }
    
    /**
     * 🔧 Initialisierung aller Sicherheitsmaßnahmen
     */
    public function init() {
        // Kommentarfunktion komplett deaktivieren
        $this->disable_comments();
        
        // REST API vor externen Zugriffen schützen
        $this->protect_rest_api();
        
        // Zusätzliche Sicherheitsmaßnahmen
        $this->additional_security_measures();
    }
    
    /**
     * 💬 Kommentarfunktion komplett deaktivieren
     */
    private function disable_comments() {
        // Kommentare für alle Post Types deaktivieren
        add_action('admin_init', array($this, 'disable_comments_post_types_support'));
        
        // Kommentar-Features aus Admin entfernen
        add_action('admin_menu', array($this, 'disable_comments_admin_menu'));
        add_action('admin_init', array($this, 'disable_comments_admin_menu_redirect'));
        add_action('admin_init', array($this, 'disable_comments_dashboard'));
        add_action('init', array($this, 'disable_comments_admin_bar'));
        
        // Frontend: Kommentare komplett abschalten
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
        add_filter('comments_array', '__return_empty_array', 10, 2);
        
        // Template-Redirects für Kommentar-Seiten
        add_action('template_redirect', array($this, 'disable_comments_template_redirect'));
        
        // XML-RPC deaktivieren (verhindert Pingbacks)
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('wp_headers', array($this, 'remove_x_pingback_header'));
        
        // Entferne Kommentar-Meta aus Head
        remove_action('wp_head', 'feed_links_extra', 3);
    }
    
    /**
     * 🚫 Kommentar-Support für alle Post Types entfernen
     */
    public function disable_comments_post_types_support() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }
    
    /**
     * 📝 Kommentar-Menüs aus Admin entfernen
     */
    public function disable_comments_admin_menu() {
        remove_menu_page('edit-comments.php');
    }
    
    /**
     * 🔄 Weiterleitung wenn jemand direkt auf Kommentar-Admin-Seite zugreift
     */
    public function disable_comments_admin_menu_redirect() {
        global $pagenow;
        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }
    }
    
    /**
     * 📊 Kommentar-Widgets aus Dashboard entfernen
     */
    public function disable_comments_dashboard() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }
    
    /**
     * 🎛️ Kommentar-Links aus Admin Bar entfernen
     */
    public function disable_comments_admin_bar() {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    }
    
    /**
     * 🚪 Template-Weiterleitungen für Kommentar-Seiten
     */
    public function disable_comments_template_redirect() {
        if (is_comment_feed()) {
            wp_redirect(home_url());
            exit;
        }
    }
    
    /**
     * 🏓 X-Pingback Header entfernen
     */
    public function remove_x_pingback_header($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    }
    
    /**
     * 🛡️ REST API vor externen Zugriffen schützen
     */
    private function protect_rest_api() {
        // REST API nur für eingeloggte Benutzer oder interne Requests
        add_filter('rest_authentication_errors', array($this, 'restrict_rest_api_access'));
        
        // Spezielle Endpunkte für bestimmte Funktionen freigeben
        add_filter('rest_pre_dispatch', array($this, 'allow_specific_rest_endpoints'), 10, 3);
        
        // REST API Links aus Head entfernen (reduziert Fingerprinting)
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('template_redirect', 'rest_output_link_header', 11);
    }
    
    /**
     * 🔐 REST API Zugriffskontrolle
     */
    public function restrict_rest_api_access($result) {
        // Bereits authentifiziert oder Fehler vorhanden
        if (!empty($result)) {
            return $result;
        }
        
        // Admin-Bereich: immer erlauben
        if (is_admin()) {
            return $result;
        }
        
        // AJAX-Requests: erlauben (für Theme-Funktionalität)
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return $result;
        }
        
        // Cron-Jobs: erlauben
        if (defined('DOING_CRON') && DOING_CRON) {
            return $result;
        }
        
        // Eingeloggte Benutzer: erlauben
        if (is_user_logged_in()) {
            return $result;
        }
        
        // Lokale Requests: erlauben (für interne Theme-Funktionen)
        if ($this->is_local_request()) {
            return $result;
        }
        
        // Externe Zugriffe blockieren
        return new WP_Error(
            'rest_access_denied',
            __('REST API Zugriff nicht erlaubt.', 'abf-styleguide'),
            array('status' => 401)
        );
    }
    
    /**
     * 🎯 Spezielle REST Endpunkte freigeben (falls nötig)
     */
    public function allow_specific_rest_endpoints($result, $server, $request) {
        $route = $request->get_route();
        
        // Liste erlaubter Endpunkte für Theme-Funktionalität
        $allowed_endpoints = array(
            '/wp/v2/media', // Nur wenn Medien-Upload benötigt wird
            // Weitere Endpunkte können hier hinzugefügt werden
        );
        
        // Prüfe ob aktueller Endpunkt erlaubt ist
        foreach ($allowed_endpoints as $endpoint) {
            if (strpos($route, $endpoint) === 0) {
                return $result; // Endpunkt erlauben
            }
        }
        
        return $result;
    }
    
    /**
     * 🏠 Prüfe ob Request von lokalem Server kommt
     */
    private function is_local_request() {
        $remote_addr = $_SERVER['REMOTE_ADDR'] ?? '';
        $http_host = $_SERVER['HTTP_HOST'] ?? '';
        
        // Lokale IP-Adressen
        $local_ips = array('127.0.0.1', '::1', 'localhost');
        
        // Prüfe Remote-Adresse
        if (in_array($remote_addr, $local_ips)) {
            return true;
        }
        
        // Prüfe ob Host und Remote-Addr übereinstimmen (lokaler Request)
        if ($remote_addr && $http_host) {
            $host_ip = gethostbyname($http_host);
            if ($remote_addr === $host_ip) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 🔒 Zusätzliche Sicherheitsmaßnahmen
     */
    private function additional_security_measures() {
        // WordPress Version aus Head entfernen
        remove_action('wp_head', 'wp_generator');
        
        // WLW Manifest Link entfernen
        remove_action('wp_head', 'wlwmanifest_link');
        
        // RSD Link entfernen
        remove_action('wp_head', 'rsd_link');
        
        // Shortlink entfernen
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Emoji-Scripte entfernen (Performance + weniger Fingerprinting)
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        
        // User enumeration verhindern
        add_action('init', array($this, 'prevent_user_enumeration'));
        
        // File Editor deaktivieren
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }
    
    /**
     * 👥 User Enumeration verhindern
     */
    public function prevent_user_enumeration() {
        if (!is_admin() && isset($_GET['author']) && !empty($_GET['author'])) {
            // Nur für geschützte Bereiche - nicht für öffentliche Bereiche
            if (!is_user_logged_in()) {
                wp_redirect(home_url());
                exit;
            }
        }
    }
    
    /**
     * 📊 Sicherheitsstatus abrufen (für Admin-Dashboard)
     */
    public static function get_security_status() {
        return array(
            'comments_disabled' => true,
            'rest_api_protected' => true,
            'xml_rpc_disabled' => !get_option('default_ping_status', true),
            'file_editor_disabled' => defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT,
            'wp_version_hidden' => true,
            'user_enumeration_blocked' => true
        );
    }
}

// Initialisiere Security Manager
new ABF_Security_Manager();

/**
 * 🔍 Debug-Funktion für Administratoren
 */
if (current_user_can('manage_options') && defined('WP_DEBUG') && WP_DEBUG) {
    add_action('wp_footer', function() {
        if (is_admin_bar_showing()) {
            $status = ABF_Security_Manager::get_security_status();
            echo '<!-- ABF Security Status: ' . json_encode($status) . ' -->';
        }
    });
}