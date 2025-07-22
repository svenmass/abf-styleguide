<?php
/**
 * Login Button for Homepage
 * Simple button to trigger the login modal
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add login button to homepage
function abf_add_login_button() {
    if (is_front_page() || is_home()) {
        ?>
        <div id="abf-login-button-container" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
            <?php if (!is_user_logged_in()): ?>
                <button type="button" class="abf-login-trigger abf-btn" onClick="ABF_UserManagement.showModal()">
                    Anmelden / Registrieren
                </button>
            <?php else: ?>
                <?php
                $user = wp_get_current_user();
                $approval_status = get_user_meta($user->ID, 'abf_approval_status', true);
                
                // Check if user is privileged
                $is_admin = current_user_can('manage_options');
                $is_editor = current_user_can('edit_others_posts');
                $is_author = current_user_can('publish_posts');
                ?>
                <div class="abf-user-info-box" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                    <div style="margin-bottom: 10px;">
                        <strong><?php echo esc_html($user->first_name ?: $user->display_name); ?></strong>
                    </div>
                    <?php if ($is_admin): ?>
                        <div style="color: #007cba; margin-bottom: 10px;">👑 Administrator</div>
                    <?php elseif ($is_editor): ?>
                        <div style="color: #0073aa; margin-bottom: 10px;">✏️ Redakteur</div>
                    <?php elseif ($is_author): ?>
                        <div style="color: #00a32a; margin-bottom: 10px;">📝 Autor</div>
                    <?php elseif ($approval_status === 'approved'): ?>
                        <div style="color: #00a32a; margin-bottom: 10px;">✓ Zugang gewährt</div>
                    <?php elseif ($approval_status === 'pending'): ?>
                        <div style="color: #d63638; margin-bottom: 10px;">⏳ Warte auf Freigabe</div>
                    <?php elseif ($approval_status === 'rejected'): ?>
                        <div style="color: #646970; margin-bottom: 10px;">❌ Nicht genehmigt</div>
                    <?php elseif ($approval_status === 'revoked'): ?>
                        <div style="color: #d63638; margin-bottom: 10px;">🚫 Zugang eingeschränkt</div>
                    <?php else: ?>
                        <div style="color: #666970; margin-bottom: 10px;">🔓 Vollzugang</div>
                    <?php endif; ?>
                    <button type="button" id="abf-logout-btn" class="abf-btn abf-btn-small" style="font-size: 12px; padding: 8px 12px;">
                        Abmelden
                    </button>
                </div>
            <?php endif; ?>
        </div>
        
        <style>
        .abf-login-trigger {
            background: #007cba;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .abf-login-trigger:hover {
            background: #005a87;
        }
        
        .abf-user-info-box {
            min-width: 180px;
        }
        </style>
        <?php
    }
}
add_action('wp_footer', 'abf_add_login_button', 5); // Run before the modal is rendered 