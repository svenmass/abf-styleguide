<?php
/**
 * Page Protection System
 * Protects all pages except homepage for non-registered users
 */

if (!defined('ABSPATH')) {
    exit;
}

class ABF_Page_Protection {
    
    public function __construct() {
        add_action('template_redirect', array($this, 'protect_pages'));
        add_action('wp_ajax_nopriv_abf_check_protection', array($this, 'check_protection_status'));
        add_action('wp_ajax_abf_check_protection', array($this, 'check_protection_status'));
    }
    
    /**
     * Main page protection logic
     */
    public function protect_pages() {
        // Skip protection for admin pages
        if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
            return;
        }
        
        // Skip protection for homepage
        if (is_front_page() || is_home()) {
            return;
        }
        
        // Skip protection for login/register related pages
        if ($this->is_auth_related_page()) {
            return;
        }
        
        // Check if user can access protected content
        if (!$this->can_access_protected_content()) {
            $this->redirect_to_homepage_with_modal();
        }
    }
    
    /**
     * Check if current page is auth-related (login, register, password reset)
     */
    private function is_auth_related_page() {
        global $pagenow;
        
        // WordPress login pages
        if ($pagenow === 'wp-login.php') {
            return true;
        }
        
        // Custom auth pages (if any)
        if (is_page(array('login', 'register', 'password-reset'))) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Redirect to homepage with modal trigger
     */
    private function redirect_to_homepage_with_modal() {
        $homepage_url = home_url();
        
        // Add parameter to trigger modal
        $redirect_url = add_query_arg(array(
            'show_login' => '1',
            'redirect_to' => urlencode($_SERVER['REQUEST_URI'])
        ), $homepage_url);
        
        wp_safe_redirect($redirect_url);
        exit;
    }
    
    /**
     * AJAX endpoint to check protection status
     */
    public function check_protection_status() {
        $response = array(
            'is_logged_in' => is_user_logged_in(),
            'can_access' => $this->can_access_protected_content(),
            'user_info' => $this->get_enhanced_user_info(),
            'is_homepage' => is_front_page() || is_home(),
            'should_show_modal' => isset($_GET['show_login'])
        );
        
        wp_die(json_encode($response));
    }
    
    /**
     * Get enhanced user info including admin status
     */
    private function get_enhanced_user_info() {
        if (!is_user_logged_in()) {
            return null;
        }
        
        $user = wp_get_current_user();
        $can_access = $this->can_access_protected_content();
        
        // Check if user is privileged (admin/editor/author)
        $is_privileged = current_user_can('manage_options') || 
                        current_user_can('edit_others_posts') || 
                        current_user_can('publish_posts');
        
        $approval_status = 'approved'; // Default for privileged users
        $user_type = 'privileged'; // Default for privileged users
        
        // Get ABF-specific data if exists
        if (!$is_privileged) {
            $approval_status = get_user_meta($user->ID, 'abf_approval_status', true) ?: 'unknown';
            $user_type = get_user_meta($user->ID, 'abf_user_type', true) ?: 'unknown';
        }
        
        return array(
            'ID' => $user->ID,
            'name' => $user->first_name ?: $user->display_name,
            'email' => $user->user_email,
            'can_access' => $can_access,
            'approval_status' => $approval_status,
            'user_type' => $user_type,
            'is_privileged' => $is_privileged
        );
    }
    
    /**
     * Get protected pages list (for frontend info)
     */
    public static function get_protected_pages_info() {
        $total_pages = wp_count_posts('page')->publish;
        $total_posts = wp_count_posts('post')->publish;
        
        return array(
            'total_pages' => $total_pages,
            'total_posts' => $total_posts,
            'protected_count' => $total_pages + $total_posts - 1, // -1 for homepage
            'protection_message' => 'Alle Inhalte außer der Startseite sind nur für registrierte Benutzer zugänglich.'
        );
    }
    
    /**
     * Check if specific page/post is protected
     */
    public static function is_page_protected($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        // Homepage is never protected
        if (get_option('page_on_front') == $post_id) {
            return false;
        }
        
        // All other pages/posts are protected
        return true;
    }
    
    /**
     * Enhanced access check including admin privileges
     */
    private function can_access_protected_content() {
        // Not logged in = no access
        if (!is_user_logged_in()) {
            return false;
        }
        
        // Admin users always have access
        if (current_user_can('manage_options')) {
            return true;
        }
        
        // Editor users have access
        if (current_user_can('edit_others_posts')) {
            return true;
        }
        
        // Author users have access
        if (current_user_can('publish_posts')) {
            return true;
        }
        
        // Check ABF-specific approval for regular users
        return ABF_User_Authentication::can_access_protected_content();
    }
    
    /**
     * Get protection notice for frontend
     */
    public static function get_protection_notice() {
        // Create instance to use the enhanced method
        $protection = new self();
        if ($protection->can_access_protected_content()) {
            return null;
        }
        
        return array(
            'title' => 'Zugang beschränkt',
            'message' => 'Diese Inhalte sind nur für registrierte und genehmigte Benutzer verfügbar.',
            'action' => 'Bitte melden Sie sich an oder registrieren Sie sich für den Zugang.'
        );
    }
}

// Initialize
new ABF_Page_Protection(); 