<?php
/**
 * User Authentication System
 * Handles login, logout, and password reset
 */

if (!defined('ABSPATH')) {
    exit;
}

class ABF_User_Authentication {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_ajax_nopriv_abf_login_user', array($this, 'handle_login'));
        add_action('wp_ajax_abf_login_user', array($this, 'handle_login'));
        add_action('wp_ajax_nopriv_abf_reset_password', array($this, 'handle_password_reset'));
        add_action('wp_ajax_abf_reset_password', array($this, 'handle_password_reset'));
        add_action('wp_ajax_abf_logout_user', array($this, 'handle_logout'));
        
        // Custom logout handling
        add_action('wp_logout', array($this, 'custom_logout_redirect'));
    }
    
    public function init() {
        // Handle custom login redirects
        add_filter('login_redirect', array($this, 'custom_login_redirect'), 10, 3);
    }
    
    /**
     * Handle AJAX login request
     */
    public function handle_login() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'abf_login_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Sicherheitsfehler')));
        }
        
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;
        
        // Validate input
        if (empty($email) || empty($password)) {
            wp_die(json_encode(array('success' => false, 'message' => 'Bitte füllen Sie alle Felder aus.')));
        }
        
        // Get user by email
        $user = get_user_by('email', $email);
        
        if (!$user) {
            wp_die(json_encode(array('success' => false, 'message' => 'Unbekannte E-Mail-Adresse.')));
        }
        
        // Check if user is approved
        $approval_status = get_user_meta($user->ID, 'abf_approval_status', true);
        
        if ($approval_status === 'pending') {
            wp_die(json_encode(array(
                'success' => false, 
                'message' => 'Ihr Konto wartet noch auf Freigabe. Sie erhalten eine E-Mail, sobald es genehmigt wurde.'
            )));
        }
        
        if ($approval_status === 'rejected') {
            wp_die(json_encode(array(
                'success' => false, 
                'message' => 'Ihr Konto wurde nicht genehmigt. Bei Fragen wenden Sie sich an unser Team.'
            )));
        }
        
        if ($approval_status === 'revoked') {
            wp_die(json_encode(array(
                'success' => false, 
                'message' => 'Ihr Zugang wurde vorübergehend eingeschränkt. Bei Fragen wenden Sie sich an unser Team.'
            )));
        }
        
        // Attempt login
        $credentials = array(
            'user_login'    => $user->user_login,
            'user_password' => $password,
            'remember'      => $remember
        );
        
        $user_login = wp_signon($credentials, false);
        
        if (is_wp_error($user_login)) {
            wp_die(json_encode(array('success' => false, 'message' => 'Falsches Passwort.')));
        }
        
        // Success
        wp_die(json_encode(array(
            'success' => true, 
            'message' => 'Anmeldung erfolgreich!',
            'redirect_url' => $this->get_redirect_url()
        )));
    }
    
    /**
     * Handle logout request
     */
    public function handle_logout() {
        if (!is_user_logged_in()) {
            wp_die(json_encode(array('success' => false, 'message' => 'Sie sind nicht angemeldet.')));
        }
        
        wp_logout();
        
        wp_die(json_encode(array(
            'success' => true, 
            'message' => 'Sie wurden erfolgreich abgemeldet.',
            'redirect_url' => home_url()
        )));
    }
    
    /**
     * Handle password reset request
     */
    public function handle_password_reset() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'abf_reset_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Sicherheitsfehler')));
        }
        
        $email = sanitize_email($_POST['email']);
        
        if (empty($email)) {
            wp_die(json_encode(array('success' => false, 'message' => 'Bitte geben Sie Ihre E-Mail-Adresse ein.')));
        }
        
        // Check if user exists
        $user = get_user_by('email', $email);
        
        if (!$user) {
            wp_die(json_encode(array('success' => false, 'message' => 'Unbekannte E-Mail-Adresse.')));
        }
        
        // Check if user is approved
        $approval_status = get_user_meta($user->ID, 'abf_approval_status', true);
        
        if ($approval_status !== 'approved') {
            wp_die(json_encode(array(
                'success' => false, 
                'message' => 'Das Passwort kann nur für genehmigte Konten zurückgesetzt werden.'
            )));
        }
        
        // Generate reset key
        $reset_key = get_password_reset_key($user);
        
        if (is_wp_error($reset_key)) {
            wp_die(json_encode(array(
                'success' => false, 
                'message' => 'Fehler beim Erstellen des Zurücksetzungslinks.'
            )));
        }
        
        // Send reset email
        $this->send_password_reset_email($user, $reset_key);
        
        wp_die(json_encode(array(
            'success' => true, 
            'message' => 'Ein Link zum Zurücksetzen des Passworts wurde an Ihre E-Mail-Adresse gesendet.'
        )));
    }
    
    /**
     * Send password reset email
     */
    private function send_password_reset_email($user, $reset_key) {
        $site_name = get_bloginfo('name');
        $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login), 'login');
        
        $subject = "Passwort zurücksetzen für $site_name";
        $message = "Hallo {$user->first_name},\n\n";
        $message .= "Sie haben eine Passwort-Zurücksetzung für Ihr Konto bei $site_name angefordert.\n\n";
        $message .= "Klicken Sie auf den folgenden Link, um Ihr Passwort zurückzusetzen:\n";
        $message .= "$reset_url\n\n";
        $message .= "Wenn Sie diese Anfrage nicht gestellt haben, ignorieren Sie diese E-Mail.\n\n";
        $message .= "Viele Grüße\n";
        $message .= "Das $site_name Team";
        
        wp_mail($user->user_email, $subject, $message);
    }
    
    /**
     * Get redirect URL after login
     */
    private function get_redirect_url() {
        // For now, redirect to homepage where they can see protected content
        // Later this could be customized
        return home_url();
    }
    
    /**
     * Custom login redirect
     */
    public function custom_login_redirect($redirect_to, $request, $user) {
        // Only for our approved users
        if (isset($user->ID)) {
            $approval_status = get_user_meta($user->ID, 'abf_approval_status', true);
            if ($approval_status === 'approved') {
                return $this->get_redirect_url();
            }
        }
        
        return $redirect_to;
    }
    
    /**
     * Custom logout redirect
     */
    public function custom_logout_redirect() {
        wp_safe_redirect(home_url());
        exit;
    }
    
    /**
     * Check if current user can access protected content
     */
    public static function can_access_protected_content() {
        if (!is_user_logged_in()) {
            return false;
        }
        
        $user_id = get_current_user_id();
        $approval_status = get_user_meta($user_id, 'abf_approval_status', true);
        
        // Only approved users can access, not revoked ones
        return $approval_status === 'approved';
    }
    
    /**
     * Get current user info for frontend
     */
    public static function get_current_user_info() {
        if (!is_user_logged_in()) {
            return null;
        }
        
        $user = wp_get_current_user();
        $can_access = self::can_access_protected_content();
        
        return array(
            'ID' => $user->ID,
            'name' => $user->first_name ?: $user->display_name,
            'email' => $user->user_email,
            'can_access' => $can_access,
            'approval_status' => get_user_meta($user->ID, 'abf_approval_status', true),
            'user_type' => get_user_meta($user->ID, 'abf_user_type', true)
        );
    }
}

// Initialize
new ABF_User_Authentication(); 