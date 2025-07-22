<?php
/**
 * User Management System Initialization
 * Custom user registration, login, and approval system
 */

if (!defined('ABSPATH')) {
    exit;
}

class ABF_User_Management_System {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_login_modal'));
    }
    
    public function init() {
        // Include all components
        $this->include_components();
        
        // Create custom user roles
        $this->create_custom_roles();
        
        // Set up AJAX handlers
        $this->setup_ajax_handlers();
    }
    
    private function include_components() {
        $components = array(
            'user-registration.php',
            'user-authentication.php', 
            'page-protection.php',
            'admin-interface.php',
            'login-button.php'
        );
        
        foreach ($components as $component) {
            $file = get_template_directory() . '/inc/user-management/' . $component;
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
    
    private function create_custom_roles() {
        // Create pending user role
        if (!get_role('pending_user')) {
            add_role('pending_user', 'Wartender Benutzer', array(
                'read' => false
            ));
        }
    }
    
    private function setup_ajax_handlers() {
        // Registration
        add_action('wp_ajax_nopriv_abf_register_user', array($this, 'handle_registration'));
        add_action('wp_ajax_abf_register_user', array($this, 'handle_registration'));
        
        // Login
        add_action('wp_ajax_nopriv_abf_login_user', array($this, 'handle_login'));
        add_action('wp_ajax_abf_login_user', array($this, 'handle_login'));
        
        // Password Reset
        add_action('wp_ajax_nopriv_abf_reset_password', array($this, 'handle_password_reset'));
        add_action('wp_ajax_abf_reset_password', array($this, 'handle_password_reset'));
        
        // Logout
        add_action('wp_ajax_abf_logout_user', array($this, 'handle_logout'));
    }
    
    public function enqueue_scripts() {
        // Only on homepage for now
        // WICHTIG: Eine Homepage muss in WordPress definiert sein (Einstellungen → Lesen)
        if (is_front_page() || is_home()) {
            // Cache-busting: Use file modification time as version
            $js_file = get_template_directory() . '/assets/js/user-management.js';
            $version = file_exists($js_file) ? filemtime($js_file) : '1.0.0';
            
            wp_enqueue_script('abf-user-management', get_template_directory_uri() . '/assets/js/user-management.js', array('jquery'), $version, true);
            
            wp_localize_script('abf-user-management', 'abf_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'registration_nonce' => wp_create_nonce('abf_registration_nonce'),
                'login_nonce' => wp_create_nonce('abf_login_nonce'),
                'reset_nonce' => wp_create_nonce('abf_reset_nonce'),
                'user_info' => $this->get_current_user_info(),
                'show_modal' => isset($_GET['show_login'])
            ));
        }
    }
    
    public function render_login_modal() {
        // Only on homepage
        if (!is_front_page() && !is_home()) {
            return;
        }
        
        ?>
        <div id="abf-auth-modal" class="abf-modal" style="display: none;">
            <div class="abf-modal-content">
                <span class="abf-modal-close">&times;</span>
                
                <div class="abf-modal-header">
                    <h2 id="abf-modal-title">Anmelden</h2>
                    <div class="abf-modal-tabs">
                        <button type="button" class="abf-tab-btn active" data-tab="login">Anmelden</button>
                        <button type="button" class="abf-tab-btn" data-tab="register">Registrieren</button>
                    </div>
                </div>
                
                <div class="abf-modal-body">
                    
                    <!-- Login Form -->
                    <div id="abf-login-form" class="abf-tab-content active">
                        <form id="abf-login-form-element">
                            <div class="abf-form-group">
                                <label for="login_email">E-Mail-Adresse</label>
                                <input type="email" id="login_email" name="email" required>
                            </div>
                            
                            <div class="abf-form-group">
                                <label for="login_password">Passwort</label>
                                <input type="password" id="login_password" name="password" required>
                            </div>
                            
                            <div class="abf-form-group">
                                <label>
                                    <input type="checkbox" name="remember"> Angemeldet bleiben
                                </label>
                            </div>
                            
                            <div class="abf-form-group">
                                <button type="submit" class="abf-btn abf-btn-primary">Anmelden</button>
                            </div>
                            
                            <div class="abf-form-group">
                                <a href="#" id="show-password-reset">Passwort vergessen?</a>
                            </div>
                        </form>
                        
                        <!-- Password Reset Form -->
                        <form id="abf-reset-form" style="display: none;">
                            <div class="abf-form-group">
                                <label for="reset_email">E-Mail-Adresse</label>
                                <input type="email" id="reset_email" name="email" required>
                            </div>
                            
                            <div class="abf-form-group">
                                <button type="submit" class="abf-btn abf-btn-primary">Passwort zurücksetzen</button>
                            </div>
                            
                            <div class="abf-form-group">
                                <a href="#" id="show-login-form">Zurück zur Anmeldung</a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Registration Form -->
                    <div id="abf-register-form" class="abf-tab-content">
                        <form id="abf-register-form-element">
                            <div class="abf-form-group">
                                <label for="register_name">Name *</label>
                                <input type="text" id="register_name" name="name" required>
                            </div>
                            
                            <div class="abf-form-group">
                                <label for="register_company">Unternehmen *</label>
                                <input type="text" id="register_company" name="company" required>
                            </div>
                            
                            <div class="abf-form-group">
                                <label for="register_email">E-Mail-Adresse *</label>
                                <input type="email" id="register_email" name="email" required>
                                <small class="abf-email-hint">Mitarbeiter mit @a-b-f.de Adresse werden automatisch freigeschaltet.</small>
                            </div>
                            
                            <div class="abf-form-group abf-external-only" style="display: none;">
                                <label for="register_phone">Telefonnummer *</label>
                                <input type="tel" id="register_phone" name="phone">
                            </div>
                            
                            <div class="abf-form-group abf-external-only" style="display: none;">
                                <label for="register_reason">Grund der Anfrage *</label>
                                <textarea id="register_reason" name="reason" rows="3"></textarea>
                            </div>
                            
                            <div class="abf-form-group">
                                <label for="register_password">Passwort *</label>
                                <input type="password" id="register_password" name="password" required>
                                <small>Mindestens 6 Zeichen</small>
                            </div>
                            
                            <div class="abf-form-group">
                                <button type="submit" class="abf-btn abf-btn-primary">Registrieren</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
                
                <div id="abf-modal-message" class="abf-message" style="display: none;"></div>
            </div>
        </div>
        
        <!-- User Info Bar (for logged in users) -->
        <div id="abf-user-bar" class="abf-user-bar" style="display: none;">
            <div class="abf-user-info">
                <span id="abf-user-name"></span>
                <button type="button" id="abf-logout-btn" class="abf-btn abf-btn-small">Abmelden</button>
            </div>
        </div>
        
        <style>
        .abf-modal {
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }
        
        .abf-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: slideIn 0.3s;
            position: relative; /* Für die Positionierung des Schließen-Buttons */
        }
        
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        
        @keyframes slideIn {
            from {transform: translateY(-50px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
        }
        
        .abf-modal-close {
            color: #ffffff;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            z-index: 10;
        }
        
        .abf-modal-close:hover {
            color: #000;
        }
        
        .abf-modal-header {
            background: #575756; /* Nur rot, ohne Verlauf */
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        
        .abf-modal-header h2 {
            margin: 0 0 15px 0;
            font-size: 24px;
        }
        
        .abf-modal-tabs {
            display: flex;
            gap: 10px;
        }
        
        .abf-tab-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .abf-tab-btn.active,
        .abf-tab-btn:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
        }
        
        .abf-modal-body {
            padding: 30px;
        }
        
        .abf-tab-content {
            display: none;
        }
        
        .abf-tab-content.active {
            display: block;
        }
        
        .abf-form-group {
            margin-bottom: 20px;
        }
        
        .abf-form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .abf-form-group input,
        .abf-form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .abf-form-group input:focus,
        .abf-form-group textarea:focus {
            outline: none;
            border-color: var(--color-primary, #007cba);
            box-shadow: 0 0 0 2px rgba(0, 124, 186, 0.2);
        }
        
        .abf-form-group small {
            display: block;
            margin-top: 5px;
            color: #666;
            font-size: 14px;
        }
        
        .abf-email-hint {
            color: var(--color-primary, #007cba) !important;
            font-weight: bold;
        }
        
        .abf-btn {
            background: var(--color-primary, #007cba);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .abf-btn:hover {
            background: var(--color-secondary, #0073aa);
        }
        
        .abf-btn-primary {
            width: 100%;
        }
        
        .abf-btn-small {
            padding: 6px 12px;
            font-size: 14px;
        }
        
        .abf-message {
            margin: 20px;
            padding: 15px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .abf-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .abf-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .abf-user-bar {
            position: fixed;
            top: 0;
            right: 0;
            background: var(--color-primary, #007cba);
            color: white;
            padding: 10px 20px;
            z-index: 9999;
            border-radius: 0 0 0 8px;
        }
        
        .abf-user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        @media (max-width: 768px) {
            .abf-modal-content {
                margin: 10% auto;
                width: 95%;
            }
            
            .abf-modal-body {
                padding: 20px;
            }
        }
        </style>
        <?php
    }
    
    private function get_current_user_info() {
        if (!is_user_logged_in()) {
            return null;
        }
        
        $user = wp_get_current_user();
        $approval_status = get_user_meta($user->ID, 'abf_approval_status', true);
        
        return array(
            'ID' => $user->ID,
            'name' => $user->first_name ?: $user->display_name,
            'email' => $user->user_email,
            'can_access' => $approval_status === 'approved',
            'approval_status' => $approval_status
        );
    }
    
    // Delegate AJAX handlers to the respective classes
    public function handle_registration() {
        if (class_exists('ABF_User_Registration')) {
            $registration = new ABF_User_Registration();
            $registration->handle_registration();
        }
    }
    
    public function handle_login() {
        if (class_exists('ABF_User_Authentication')) {
            $auth = new ABF_User_Authentication();
            $auth->handle_login();
        }
    }
    
    public function handle_password_reset() {
        if (class_exists('ABF_User_Authentication')) {
            $auth = new ABF_User_Authentication();
            $auth->handle_password_reset();
        }
    }
    
    public function handle_logout() {
        if (class_exists('ABF_User_Authentication')) {
            $auth = new ABF_User_Authentication();
            $auth->handle_logout();
        }
    }
}

// Initialize the system
new ABF_User_Management_System(); 