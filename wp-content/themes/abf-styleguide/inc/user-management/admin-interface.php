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
        $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'pending';
        ?>
        <div class="wrap">
            <h1>ABF Benutzer-Verwaltung</h1>
            
            <nav class="nav-tab-wrapper">
                <a href="?page=abf-user-management&tab=pending" class="nav-tab <?php echo $current_tab === 'pending' ? 'nav-tab-active' : ''; ?>">
                    Wartende Benutzer
                </a>
                <a href="?page=abf-user-management&tab=approved" class="nav-tab <?php echo $current_tab === 'approved' ? 'nav-tab-active' : ''; ?>">
                    Genehmigte Benutzer
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
        
        $this->render_user_table($users, true);
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
        
        $this->render_user_table($users, false);
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
        
        $this->render_user_table($users, false);
    }
    
    private function render_user_table($users, $show_actions = false) {
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
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
                    ?>
                    <tr>
                        <td><?php echo esc_html($user->first_name ?: $user->display_name); ?></td>
                        <td><?php echo esc_html($user->user_email); ?></td>
                        <td><?php echo esc_html($company); ?></td>
                        <td><?php echo $user_type === 'internal' ? 'Intern' : 'Extern'; ?></td>
                        <td>
                            <?php 
                            switch ($approval_status) {
                                case 'pending': echo '<span style="color: #d63638;">Wartend</span>'; break;
                                case 'approved': echo '<span style="color: #00a32a;">Genehmigt</span>'; break;
                                case 'rejected': echo '<span style="color: #646970;">Abgelehnt</span>'; break;
                            }
                            ?>
                        </td>
                        <td><?php echo date('d.m.Y H:i', strtotime($user->user_registered)); ?></td>
                        <?php if ($show_actions): ?>
                            <td>
                                <button type="button" class="button button-primary" onclick="approveUser(<?php echo $user->ID; ?>)">
                                    Genehmigen
                                </button>
                                <button type="button" class="button" onclick="rejectUser(<?php echo $user->ID; ?>)">
                                    Ablehnen
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
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
}

// Initialize
new ABF_Admin_Interface();
