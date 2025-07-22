<?php
/**
 * Admin Interface for User Management
 */

if (!defined('ABSPATH')) {
    exit;
}

class ABF_Admin_Interface {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_abf_approve_user', array($this, 'approve_user'));
        add_action('wp_ajax_abf_reject_user', array($this, 'reject_user'));
        add_action('wp_ajax_abf_revoke_user', array($this, 'revoke_user'));
        add_action('wp_ajax_abf_restore_user', array($this, 'restore_user'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Benutzer-Verwaltung',
            'ABF Benutzer',
            'manage_options',
            'abf-user-management',
            array($this, 'admin_page'),
            'dashicons-groups',
            30
        );
    }
    
    public function admin_page() {
        // Handle bulk actions
        if (isset($_POST['action']) && isset($_POST['users'])) {
            $this->handle_bulk_action($_POST['action'], $_POST['users']);
        }
        
        $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'pending';
        ?>
        <div class="wrap">
            <h1>ABF Benutzer-Verwaltung</h1>
            
            <?php $this->show_admin_notices(); ?>
            
            <nav class="nav-tab-wrapper">
                <a href="?page=abf-user-management&tab=pending" class="nav-tab <?php echo $current_tab === 'pending' ? 'nav-tab-active' : ''; ?>">
                    Wartende Benutzer <?php echo $this->get_pending_count(); ?>
                </a>
                <a href="?page=abf-user-management&tab=approved" class="nav-tab <?php echo $current_tab === 'approved' ? 'nav-tab-active' : ''; ?>">
                    Genehmigte Benutzer <?php echo $this->get_approved_count(); ?>
                </a>
                <a href="?page=abf-user-management&tab=revoked" class="nav-tab <?php echo $current_tab === 'revoked' ? 'nav-tab-active' : ''; ?>">
                    Rechte entzogen <?php echo $this->get_revoked_count(); ?>
                </a>
                <a href="?page=abf-user-management&tab=all" class="nav-tab <?php echo $current_tab === 'all' ? 'nav-tab-active' : ''; ?>">
                    Alle ABF Benutzer
                </a>
            </nav>
            
            <div class="tabcontent">
                <?php
                switch ($current_tab) {
                    case 'pending':
                        $this->show_pending_users();
                        break;
                    case 'approved':
                        $this->show_approved_users();
                        break;
                    case 'revoked':
                        $this->show_revoked_users();
                        break;
                    case 'all':
                        $this->show_all_abf_users();
                        break;
                }
                ?>
            </div>
        </div>
        <?php
    }
    
    private function show_pending_users() {
        $users = get_users(array(
            'meta_query' => array(
                array(
                    'key' => 'abf_approval_status',
                    'value' => 'pending'
                )
            )
        ));
        
        if (empty($users)) {
            echo '<p>Keine wartenden Benutzer gefunden.</p>';
            return;
        }
        
        $this->render_user_table($users, true, true, 'approve');
    }
    
    /**
     * Show admin notices
     */
    private function show_admin_notices() {
        if (isset($_GET['message'])) {
            $message = sanitize_text_field($_GET['message']);
            $type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'success';
            echo "<div class='notice notice-{$type} is-dismissible'><p>{$message}</p></div>";
        }
    }
    
    /**
     * Get user counts for tabs
     */
    private function get_pending_count() {
        $count = count(get_users(array(
            'meta_query' => array(array('key' => 'abf_approval_status', 'value' => 'pending'))
        )));
        return $count > 0 ? "({$count})" : '';
    }
    
    private function get_approved_count() {
        $count = count(get_users(array(
            'meta_query' => array(array('key' => 'abf_approval_status', 'value' => 'approved'))
        )));
        return $count > 0 ? "({$count})" : '';
    }
    
    private function get_revoked_count() {
        $count = count(get_users(array(
            'meta_query' => array(array('key' => 'abf_approval_status', 'value' => 'revoked'))
        )));
        return $count > 0 ? "({$count})" : '';
    }
    
    private function show_approved_users() {
        $users = get_users(array(
            'meta_query' => array(
                array(
                    'key' => 'abf_approval_status',
                    'value' => 'approved'
                )
            )
        ));
        
        if (empty($users)) {
            echo '<p>Keine genehmigten Benutzer gefunden.</p>';
            return;
        }
        
        echo '<form method="post">';
        echo '<p><button type="submit" name="action" value="bulk_revoke" class="button" onClick="return confirm(\'Rechte der ausgewählten Benutzer wirklich entziehen?\')">Ausgewählte Rechte entziehen</button></p>';
        
        $this->render_user_table($users, true, true, 'revoke');
        echo '</form>';
    }
    
    private function show_revoked_users() {
        $users = get_users(array(
            'meta_query' => array(
                array(
                    'key' => 'abf_approval_status',
                    'value' => 'revoked'
                )
            )
        ));
        
        if (empty($users)) {
            echo '<p>Keine Benutzer mit entzogenen Rechten gefunden.</p>';
            return;
        }
        
        echo '<form method="post">';
        echo '<p><button type="submit" name="action" value="bulk_restore" class="button button-primary" onClick="return confirm(\'Rechte der ausgewählten Benutzer wirklich wiederherstellen?\')">Ausgewählte wiederherstellen</button></p>';
        
        $this->render_user_table($users, true, true, 'restore');
        echo '</form>';
    }
    
    private function show_all_abf_users() {
        $users = get_users(array(
            'meta_query' => array(
                array(
                    'key' => 'abf_user_type',
                    'value' => array('internal', 'external'),
                    'compare' => 'IN'
                )
            )
        ));
        
        $this->render_user_table($users, false, false, '');
    }
    
    private function render_user_table($users, $show_actions = false, $show_checkboxes = false, $action_type = '') {
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <?php if ($show_checkboxes): ?>
                        <th><input type="checkbox" id="select-all" /></th>
                    <?php endif; ?>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Unternehmen</th>
                    <th>Typ</th>
                    <th>Status</th>
                    <th>Registriert</th>
                    <?php if ($show_actions): ?>
                        <th>Aktionen</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <?php
                    $user_type = get_user_meta($user->ID, 'abf_user_type', true);
                    $approval_status = get_user_meta($user->ID, 'abf_approval_status', true);
                    $company = get_user_meta($user->ID, 'abf_company', true);
                    $phone = get_user_meta($user->ID, 'abf_phone', true);
                    $reason = get_user_meta($user->ID, 'abf_reason', true);
                    ?>
                    <tr>
                        <?php if ($show_checkboxes): ?>
                            <td><input type="checkbox" name="users[]" value="<?php echo $user->ID; ?>" /></td>
                        <?php endif; ?>
                        <td><?php echo esc_html($user->first_name ?: $user->display_name); ?></td>
                        <td><?php echo esc_html($user->user_email); ?></td>
                        <td><?php echo esc_html($company); ?></td>
                        <td>
                            <span class="user-type-<?php echo $user_type; ?>">
                                <?php echo $user_type === 'internal' ? 'Intern' : 'Extern'; ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-<?php echo $approval_status; ?>">
                                <?php 
                                switch ($approval_status) {
                                    case 'pending': echo '⏳ Wartend'; break;
                                    case 'approved': echo '✅ Genehmigt'; break;
                                    case 'rejected': echo '❌ Abgelehnt'; break;
                                    case 'revoked': echo '🚫 Rechte entzogen'; break;
                                    default: echo 'Unbekannt';
                                }
                                ?>
                            </span>
                        </td>
                        <td><?php echo date('d.m.Y H:i', strtotime($user->user_registered)); ?></td>
                        <?php if ($show_actions): ?>
                            <td class="user-actions">
                                <?php if ($action_type === 'approve'): ?>
                                    <button type="button" class="button button-primary" onClick="approveUser(<?php echo $user->ID; ?>)">
                                        Genehmigen
                                    </button>
                                    <button type="button" class="button" onClick="rejectUser(<?php echo $user->ID; ?>)">
                                        Ablehnen
                                    </button>
                                <?php elseif ($action_type === 'revoke'): ?>
                                    <button type="button" class="button button-secondary" onClick="revokeUser(<?php echo $user->ID; ?>)" style="background: #d63638; border-color: #d63638; color: white;">
                                        Rechte entziehen
                                    </button>
                                <?php elseif ($action_type === 'restore'): ?>
                                    <button type="button" class="button button-primary" onClick="restoreUser(<?php echo $user->ID; ?>)">
                                        Wiederherstellen
                                    </button>
                                    <button type="button" class="button" onClick="rejectUser(<?php echo $user->ID; ?>)">
                                        Endgültig ablehnen
                                    </button>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($show_checkboxes): ?>
        <script>
        document.getElementById('select-all')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="users[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
        </script>
        <?php endif; ?>
        
        <?php if ($show_actions): ?>
        <script>
        function approveUser(userId) {
            if (!confirm('Benutzer wirklich genehmigen?')) return;
            
            jQuery.post(ajaxurl, {
                action: 'abf_approve_user',
                user_id: userId,
                nonce: '<?php echo wp_create_nonce('abf_admin_nonce'); ?>'
            }, function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    location.reload();
                } else {
                    alert('Fehler: ' + data.message);
                }
            });
        }
        
        function rejectUser(userId) {
            if (!confirm('Benutzer wirklich ablehnen?')) return;
            
            jQuery.post(ajaxurl, {
                action: 'abf_reject_user',
                user_id: userId,
                nonce: '<?php echo wp_create_nonce('abf_admin_nonce'); ?>'
            }, function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    location.reload();
                } else {
                    alert('Fehler: ' + data.message);
                }
            });
        }
        
        function revokeUser(userId) {
            if (!confirm('Benutzerrechte wirklich entziehen?\n\nDer Benutzer verliert sofort den Zugang zu allen geschützten Inhalten und wird per E-Mail benachrichtigt.')) return;
            
            jQuery.post(ajaxurl, {
                action: 'abf_revoke_user',
                user_id: userId,
                nonce: '<?php echo wp_create_nonce('abf_admin_nonce'); ?>'
            }, function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    location.reload();
                } else {
                    alert('Fehler: ' + data.message);
                }
            });
        }
        
        function restoreUser(userId) {
            if (!confirm('Benutzerrechte wirklich wiederherstellen?')) return;
            
            jQuery.post(ajaxurl, {
                action: 'abf_restore_user',
                user_id: userId,
                nonce: '<?php echo wp_create_nonce('abf_admin_nonce'); ?>'
            }, function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    location.reload();
                } else {
                    alert('Fehler: ' + data.message);
                }
            });
        }
        </script>
        <?php endif; ?>
        <?php
    }
    
    public function approve_user() {
        if (!wp_verify_nonce($_POST['nonce'], 'abf_admin_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Sicherheitsfehler')));
        }
        
        if (!current_user_can('manage_options')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Keine Berechtigung')));
        }
        
        $user_id = intval($_POST['user_id']);
        update_user_meta($user_id, 'abf_approval_status', 'approved');
        
        $user = get_userdata($user_id);
        $user->set_role('subscriber');
        
        wp_die(json_encode(array('success' => true, 'message' => 'Benutzer wurde genehmigt.')));
    }
    
    public function reject_user() {
        if (!wp_verify_nonce($_POST['nonce'], 'abf_admin_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Sicherheitsfehler')));
        }
        
        if (!current_user_can('manage_options')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Keine Berechtigung')));
        }
        
        $user_id = intval($_POST['user_id']);
        update_user_meta($user_id, 'abf_approval_status', 'rejected');
        
        wp_die(json_encode(array('success' => true, 'message' => 'Benutzer wurde abgelehnt.')));
    }
    
    public function revoke_user() {
        if (!wp_verify_nonce($_POST['nonce'], 'abf_admin_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Sicherheitsfehler')));
        }
        
        if (!current_user_can('manage_options')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Keine Berechtigung')));
        }
        
        $user_id = intval($_POST['user_id']);
        $this->change_user_status($user_id, 'revoked');
        
        wp_die(json_encode(array('success' => true, 'message' => 'Benutzerrechte wurden entzogen.')));
    }
    
    public function restore_user() {
        if (!wp_verify_nonce($_POST['nonce'], 'abf_admin_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Sicherheitsfehler')));
        }
        
        if (!current_user_can('manage_options')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Keine Berechtigung')));
        }
        
        $user_id = intval($_POST['user_id']);
        $this->change_user_status($user_id, 'approved');
        
        wp_die(json_encode(array('success' => true, 'message' => 'Benutzerrechte wurden wiederhergestellt.')));
    }
    
    /**
     * Handle bulk actions
     */
    private function handle_bulk_action($action, $user_ids) {
        $count = 0;
        
        foreach ($user_ids as $user_id) {
            switch ($action) {
                case 'bulk_approve':
                    $this->change_user_status($user_id, 'approved');
                    break;
                case 'bulk_reject':
                    $this->change_user_status($user_id, 'rejected');
                    break;
                case 'bulk_revoke':
                    $this->change_user_status($user_id, 'revoked');
                    break;
                case 'bulk_restore':
                    $this->change_user_status($user_id, 'approved');
                    break;
            }
            $count++;
        }
        
        $messages = array(
            'bulk_approve' => 'genehmigt',
            'bulk_reject' => 'abgelehnt',
            'bulk_revoke' => 'Rechte entzogen',
            'bulk_restore' => 'wiederhergestellt'
        );
        
        $action_text = $messages[$action] ?? 'bearbeitet';
        $redirect_url = add_query_arg(array(
            'message' => "{$count} Benutzer wurden {$action_text}.",
            'type' => 'success'
        ), admin_url('admin.php?page=abf-user-management'));
        
        wp_safe_redirect($redirect_url);
        exit;
    }
    
    /**
     * Change user status with email notifications
     */
    private function change_user_status($user_id, $new_status) {
        $old_status = get_user_meta($user_id, 'abf_approval_status', true);
        update_user_meta($user_id, 'abf_approval_status', $new_status);
        
        $user = get_userdata($user_id);
        
        if ($new_status === 'approved' && $old_status !== 'approved') {
            $user->set_role('subscriber');
            $this->send_approval_notification($user_id);
        } elseif ($new_status === 'rejected' && $old_status !== 'rejected') {
            $this->send_rejection_notification($user_id);
        } elseif ($new_status === 'revoked' && $old_status !== 'revoked') {
            // Don't change role for revoked users, just change status
            $this->send_revocation_notification($user_id);
        }
    }
    
    /**
     * Send email notifications
     */
    private function send_approval_notification($user_id) {
        $user = get_userdata($user_id);
        $site_name = get_bloginfo('name');
        $login_url = home_url();
        
        $subject = "Ihr Konto bei $site_name wurde freigegeben";
        $message = "Hallo {$user->first_name},\n\n";
        $message .= "großartige Neuigkeiten! Ihr Konto bei $site_name wurde freigegeben.\n\n";
        $message .= "Sie können sich jetzt anmelden: $login_url\n\n";
        $message .= "Viele Grüße\n";
        $message .= "Das $site_name Team";
        
        wp_mail($user->user_email, $subject, $message);
    }
    
    private function send_rejection_notification($user_id) {
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
    
    private function send_revocation_notification($user_id) {
        $user = get_userdata($user_id);
        $site_name = get_bloginfo('name');
        
        $subject = "Zugangsänderung bei $site_name";
        $message = "Hallo {$user->first_name},\n\n";
        $message .= "wir informieren Sie darüber, dass Ihr Zugang zu $site_name vorübergehend eingeschränkt wurde.\n\n";
        $message .= "Falls Sie Fragen haben oder glauben, dass dies ein Versehen ist, wenden Sie sich gerne an unser Team.\n\n";
        $message .= "Viele Grüße\n";
        $message .= "Das $site_name Team";
        
        wp_mail($user->user_email, $subject, $message);
    }
}

// Initialize
new ABF_Admin_Interface();
