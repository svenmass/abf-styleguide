<?php
/**
 * User Registration System
 * Handles custom user registration with approval workflow
 */

if (!defined('ABSPATH')) {
    exit;
}

class ABF_User_Registration {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_ajax_nopriv_abf_register_user', array($this, 'handle_registration'));
        add_action('wp_ajax_abf_register_user', array($this, 'handle_registration'));
    }
    
    public function init() {
        // Add custom user meta fields
        add_action('show_user_profile', array($this, 'show_custom_user_fields'));
        add_action('edit_user_profile', array($this, 'show_custom_user_fields'));
        add_action('personal_options_update', array($this, 'save_custom_user_fields'));
        add_action('edit_user_profile_update', array($this, 'save_custom_user_fields'));
    }
    
    /**
     * Handle AJAX registration request
     */
    public function handle_registration() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'abf_registration_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Sicherheitsfehler')));
        }
        
        // Sanitize input data
        $name = sanitize_text_field($_POST['name']);
        $company = sanitize_text_field($_POST['company']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $reason = sanitize_textarea_field($_POST['reason']);
        $password = $_POST['password'];
        
        // Validate required fields
        $errors = array();
        
        if (empty($name)) {
            $errors[] = 'Name ist erforderlich.';
        }
        
        if (empty($company)) {
            $errors[] = 'Unternehmen ist erforderlich.';
        }
        
        if (empty($email) || !is_email($email)) {
            $errors[] = 'Gültige E-Mail-Adresse ist erforderlich.';
        }
        
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Passwort muss mindestens 6 Zeichen lang sein.';
        }
        
        // Check if email already exists
        if (email_exists($email)) {
            $errors[] = 'Diese E-Mail-Adresse ist bereits registriert.';
        }
        
        // Determine user type
        $is_internal = $this->is_internal_email($email);
        
        // Validate external user fields
        if (!$is_internal) {
            if (empty($phone)) {
                $errors[] = 'Telefonnummer ist für externe Nutzer erforderlich.';
            }
            if (empty($reason)) {
                $errors[] = 'Grund der Anfrage ist für externe Nutzer erforderlich.';
            }
        }
        
        if (!empty($errors)) {
            wp_die(json_encode(array(
                'success' => false, 
                'message' => implode('<br>', $errors)
            )));
        }
        
        // Create user
        $username = $this->generate_username($email);
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            wp_die(json_encode(array(
                'success' => false, 
                'message' => 'Fehler beim Erstellen des Benutzerkontos: ' . $user_id->get_error_message()
            )));
        }
        
        // Set user meta
        update_user_meta($user_id, 'first_name', $name);
        update_user_meta($user_id, 'abf_company', $company);
        update_user_meta($user_id, 'abf_phone', $phone);
        update_user_meta($user_id, 'abf_reason', $reason);
        update_user_meta($user_id, 'abf_user_type', $is_internal ? 'internal' : 'external');
        
        // Set approval status
        if ($is_internal) {
            update_user_meta($user_id, 'abf_approval_status', 'approved');
            $user = new WP_User($user_id);
            $user->set_role('subscriber');
            
            // Send welcome email to internal user
            $this->send_welcome_email($user_id, true);
            
            $message = 'Ihr Konto wurde erfolgreich erstellt und ist sofort verfügbar.';
        } else {
            update_user_meta($user_id, 'abf_approval_status', 'pending');
            $user = new WP_User($user_id);
            $user->set_role('pending_user'); // Custom role for pending users
            
            // Send confirmation email to external user
            $this->send_confirmation_email($user_id);
            
            // Notify admins
            $this->notify_admins_new_registration($user_id);
            
            $message = 'Ihr Konto wurde erstellt. Sie erhalten eine E-Mail, sobald es freigegeben wurde.';
        }
        
        wp_die(json_encode(array(
            'success' => true, 
            'message' => $message,
            'auto_login' => $is_internal
        )));
    }
    
    /**
     * Check if email is internal (@a-b-f.de)
     */
    private function is_internal_email($email) {
        return strpos($email, '@a-b-f.de') !== false;
    }
    
    /**
     * Generate unique username from email
     */
    private function generate_username($email) {
        $username = sanitize_user(current(explode('@', $email)));
        
        // Make sure username is unique
        $counter = 1;
        $original_username = $username;
        
        while (username_exists($username)) {
            $username = $original_username . $counter;
            $counter++;
        }
        
        return $username;
    }
    
    /**
     * Send welcome email
     */
    private function send_welcome_email($user_id, $is_internal = false) {
        $user = get_userdata($user_id);
        $site_name = get_bloginfo('name');
        $login_url = wp_login_url();
        
        $subject = "Willkommen bei $site_name";
        
        if ($is_internal) {
            $message = "Hallo {$user->first_name},\n\n";
            $message .= "Ihr Konto bei $site_name wurde erfolgreich erstellt und ist sofort verfügbar.\n\n";
            $message .= "Sie können sich jetzt anmelden: $login_url\n\n";
            $message .= "Ihre Anmeldedaten:\n";
            $message .= "E-Mail: {$user->user_email}\n\n";
            $message .= "Viele Grüße\n";
            $message .= "Das $site_name Team";
        }
        
        wp_mail($user->user_email, $subject, $message);
    }
    
    /**
     * Send confirmation email to external user
     */
    private function send_confirmation_email($user_id) {
        $user = get_userdata($user_id);
        $site_name = get_bloginfo('name');
        
        $subject = "Registrierung bei $site_name erhalten";
        $message = "Hallo {$user->first_name},\n\n";
        $message .= "vielen Dank für Ihre Registrierung bei $site_name.\n\n";
        $message .= "Ihre Registrierung wird nun von unserem Team geprüft. Sie erhalten eine weitere E-Mail, sobald Ihr Konto freigegeben wurde.\n\n";
        $message .= "Viele Grüße\n";
        $message .= "Das $site_name Team";
        
        wp_mail($user->user_email, $subject, $message);
    }
    
    /**
     * Notify admins about new registration
     */
    private function notify_admins_new_registration($user_id) {
        $user = get_userdata($user_id);
        $site_name = get_bloginfo('name');
        $admin_url = admin_url('admin.php?page=abf-user-management');
        
        // Get all admin users
        $admins = get_users(array('role' => 'administrator'));
        
        $subject = "Neue Registrierung wartet auf Freigabe - $site_name";
        $message = "Eine neue externe Registrierung wartet auf Freigabe:\n\n";
        $message .= "Name: {$user->first_name}\n";
        $message .= "Unternehmen: " . get_user_meta($user_id, 'abf_company', true) . "\n";
        $message .= "E-Mail: {$user->user_email}\n";
        $message .= "Telefon: " . get_user_meta($user_id, 'abf_phone', true) . "\n";
        $message .= "Grund: " . get_user_meta($user_id, 'abf_reason', true) . "\n\n";
        $message .= "Zur Freigabe: $admin_url";
        
        foreach ($admins as $admin) {
            wp_mail($admin->user_email, $subject, $message);
        }
    }
    
    /**
     * Show custom user fields in admin
     */
    public function show_custom_user_fields($user) {
        ?>
        <h3>ABF Zusatzinformationen</h3>
        <table class="form-table">
            <tr>
                <th><label for="abf_company">Unternehmen</label></th>
                <td>
                    <input type="text" name="abf_company" id="abf_company" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'abf_company', true)); ?>" 
                           class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="abf_phone">Telefon</label></th>
                <td>
                    <input type="text" name="abf_phone" id="abf_phone" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'abf_phone', true)); ?>" 
                           class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="abf_reason">Grund der Anfrage</label></th>
                <td>
                    <textarea name="abf_reason" id="abf_reason" rows="3" cols="30"><?php 
                        echo esc_textarea(get_user_meta($user->ID, 'abf_reason', true)); 
                    ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="abf_user_type">Nutzertyp</label></th>
                <td>
                    <select name="abf_user_type" id="abf_user_type">
                        <option value="internal" <?php selected(get_user_meta($user->ID, 'abf_user_type', true), 'internal'); ?>>Intern</option>
                        <option value="external" <?php selected(get_user_meta($user->ID, 'abf_user_type', true), 'external'); ?>>Extern</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="abf_approval_status">Freigabestatus</label></th>
                <td>
                    <select name="abf_approval_status" id="abf_approval_status">
                        <option value="pending" <?php selected(get_user_meta($user->ID, 'abf_approval_status', true), 'pending'); ?>>Wartend</option>
                        <option value="approved" <?php selected(get_user_meta($user->ID, 'abf_approval_status', true), 'approved'); ?>>Genehmigt</option>
                        <option value="rejected" <?php selected(get_user_meta($user->ID, 'abf_approval_status', true), 'rejected'); ?>>Abgelehnt</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save custom user fields
     */
    public function save_custom_user_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        
        update_user_meta($user_id, 'abf_company', sanitize_text_field($_POST['abf_company']));
        update_user_meta($user_id, 'abf_phone', sanitize_text_field($_POST['abf_phone']));
        update_user_meta($user_id, 'abf_reason', sanitize_textarea_field($_POST['abf_reason']));
        update_user_meta($user_id, 'abf_user_type', sanitize_text_field($_POST['abf_user_type']));
        
        $old_status = get_user_meta($user_id, 'abf_approval_status', true);
        $new_status = sanitize_text_field($_POST['abf_approval_status']);
        
        update_user_meta($user_id, 'abf_approval_status', $new_status);
        
        // Handle status change
        if ($old_status !== $new_status) {
            $this->handle_status_change($user_id, $old_status, $new_status);
        }
    }
    
    /**
     * Handle approval status change
     */
    private function handle_status_change($user_id, $old_status, $new_status) {
        $user = get_userdata($user_id);
        
        if ($new_status === 'approved' && $old_status !== 'approved') {
            // User approved
            $user->set_role('subscriber');
            $this->send_approval_email($user_id);
            
        } elseif ($new_status === 'rejected' && $old_status !== 'rejected') {
            // User rejected
            $this->send_rejection_email($user_id);
        }
    }
    
    /**
     * Send approval email
     */
    private function send_approval_email($user_id) {
        $user = get_userdata($user_id);
        $site_name = get_bloginfo('name');
        $login_url = home_url(); // They'll see the modal on homepage
        
        $subject = "Ihr Konto bei $site_name wurde freigegeben";
        $message = "Hallo {$user->first_name},\n\n";
        $message .= "großartige Neuigkeiten! Ihr Konto bei $site_name wurde freigegeben.\n\n";
        $message .= "Sie können sich jetzt anmelden: $login_url\n\n";
        $message .= "Ihre Anmeldedaten:\n";
        $message .= "E-Mail: {$user->user_email}\n\n";
        $message .= "Viele Grüße\n";
        $message .= "Das $site_name Team";
        
        wp_mail($user->user_email, $subject, $message);
    }
    
    /**
     * Send rejection email
     */
    private function send_rejection_email($user_id) {
        $user = get_userdata($user_id);
        $site_name = get_bloginfo('name');
        
        $subject = "Registrierung bei $site_name";
        $message = "Hallo {$user->first_name},\n\n";
        $message .= "vielen Dank für Ihr Interesse an $site_name.\n\n";
        $message .= "Leider können wir Ihre Registrierung zu diesem Zeitpunkt nicht genehmigen.\n\n";
        $message .= "Bei Fragen können Sie sich gerne an uns wenden.\n\n";
        $message .= "Viele Grüße\n";
        $message .= "Das $site_name Team";
        
        wp_mail($user->user_email, $subject, $message);
    }
}

// Initialize
new ABF_User_Registration(); 