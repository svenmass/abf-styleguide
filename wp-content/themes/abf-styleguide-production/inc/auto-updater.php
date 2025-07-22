<?php
/**
 * 🚀 ABF Styleguide Theme Auto-Updater
 * 
 * Automatische Theme-Updates von GitHub Releases
 * 
 * @package ABF_Styleguide
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class ABF_Theme_Updater {
    
    private $theme_slug;
    private $theme_version;
    private $github_repo;
    private $theme_file;
    
    public function __construct() {
        $this->theme_slug = 'abf-styleguide';
        $this->theme_version = wp_get_theme()->get('Version');
        $this->github_repo = 'svenmass/abf-styleguide'; // GitHub username/repository
        $this->theme_file = get_template_directory() . '/style.css';
        
        // WordPress Hooks
        add_action('admin_init', array($this, 'init'));
        add_action('admin_notices', array($this, 'show_update_notice'));
        add_action('wp_ajax_abf_install_update', array($this, 'install_update'));
        add_action('wp_ajax_abf_force_update_check', array($this, 'force_update_check')); // 🚀 Manuelle Prüfung
        add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update'));
    }
    
    /**
     * 🔧 Initialisierung
     */
    public function init() {
        // Täglich nach Updates prüfen
        if (!wp_next_scheduled('abf_check_theme_update')) {
            wp_schedule_event(time(), 'daily', 'abf_check_theme_update');
        }
        add_action('abf_check_theme_update', array($this, 'check_for_update_cron'));
    }
    
    /**
     * 🔍 Prüfe GitHub Releases auf Updates
     */
    public function check_for_update_cron() {
        $this->check_for_update(false);
    }
    
    /**
     * 📡 GitHub API Call für neueste Version
     */
    private function get_latest_release() {
        $request_uri = "https://api.github.com/repos/{$this->github_repo}/releases/latest";
        
        $response = wp_remote_get($request_uri, array(
            'timeout' => 15,
            'headers' => array(
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'ABF-Styleguide-Theme-Updater'
            )
        ));
        
        if (is_wp_error($response)) {
            error_log('ABF Theme Updater: GitHub API Error - ' . $response->get_error_message());
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (!$data || !isset($data['tag_name'])) {
            error_log('ABF Theme Updater: Invalid GitHub API response');
            return false;
        }
        
        return $data;
    }
    
    /**
     * 🔄 Prüfe auf Theme-Updates
     */
    public function check_for_update($transient = false) {
        $release_data = $this->get_latest_release();
        
        if (!$release_data) {
            return $transient;
        }
        
        $latest_version = ltrim($release_data['tag_name'], 'v');
        $current_version = $this->theme_version;
        
        // Speichere Release-Daten für Admin-Notice
        set_transient('abf_latest_release', $release_data, DAY_IN_SECONDS);
        
        // Vergleiche Versionen
        if (version_compare($current_version, $latest_version, '<')) {
            // Update verfügbar
            set_transient('abf_update_available', array(
                'current_version' => $current_version,
                'latest_version' => $latest_version,
                'download_url' => $this->get_download_url($release_data),
                'changelog' => $release_data['body'] ?? 'Neue Version verfügbar',
                'release_date' => $release_data['published_at'] ?? '',
            ), DAY_IN_SECONDS);
            
            // WordPress Update-System benachrichtigen
            if ($transient !== false) {
                $transient->response[$this->theme_slug] = array(
                    'theme' => $this->theme_slug,
                    'new_version' => $latest_version,
                    'url' => $release_data['html_url'] ?? '',
                    'package' => $this->get_download_url($release_data)
                );
            }
        } else {
            // Kein Update verfügbar - Transient löschen
            delete_transient('abf_update_available');
        }
        
        return $transient;
    }
    
    /**
     * 📥 Download-URL aus Release-Daten extrahieren
     */
    private function get_download_url($release_data) {
        if (isset($release_data['assets']) && is_array($release_data['assets'])) {
            foreach ($release_data['assets'] as $asset) {
                if (strpos($asset['name'], $this->theme_slug) !== false && 
                    strpos($asset['name'], '.zip') !== false) {
                    return $asset['browser_download_url'];
                }
            }
        }
        
        // Fallback: GitHub Release ZIP
        return "https://github.com/{$this->github_repo}/archive/refs/tags/{$release_data['tag_name']}.zip";
    }
    
    /**
     * 📢 Admin-Notice für verfügbare Updates anzeigen
     */
    public function show_update_notice() {
        if (!current_user_can('update_themes')) {
            return;
        }
        
        $update_data = get_transient('abf_update_available');
        
        // 🔍 Zeige "Jetzt prüfen" Button wenn kein Update verfügbar
        if (!$update_data) {
            // Nur auf Theme-/Plugin-Update-Seiten oder Dashboard anzeigen
            $screen = get_current_screen();
            if ($screen && in_array($screen->id, array('dashboard', 'themes', 'update-core'))) {
                $this->show_check_for_updates_notice();
            }
            return;
        }
        
        $current = $update_data['current_version'];
        $latest = $update_data['latest_version'];
        $nonce = wp_create_nonce('abf_install_update');
        
        ?>
        <div class="notice notice-warning is-dismissible abf-update-notice">
            <h3>🎨 ABF Styleguide Theme Update verfügbar!</h3>
            <p>
                <strong>Aktuelle Version:</strong> v<?php echo esc_html($current); ?><br>
                <strong>Neue Version:</strong> v<?php echo esc_html($latest); ?>
            </p>
            
            <?php if (!empty($update_data['changelog'])): ?>
                <details style="margin: 10px 0;">
                    <summary style="cursor: pointer; font-weight: bold;">📋 Was ist neu?</summary>
                    <div style="margin-top: 10px; padding: 10px; background: #f9f9f9; border-left: 4px solid #0073aa;">
                        <?php echo wp_kses_post(wpautop($update_data['changelog'])); ?>
                    </div>
                </details>
            <?php endif; ?>
            
            <p>
                <button type="button" class="button button-primary abf-install-update" 
                        data-nonce="<?php echo esc_attr($nonce); ?>">
                    🚀 Jetzt installieren
                </button>
                <button type="button" class="button abf-dismiss-notice">
                    ⏰ Später erinnern
                </button>
            </p>
            
            <div class="abf-update-progress" style="display: none;">
                <p><strong>🔄 Update wird installiert...</strong></p>
                <div style="background: #f0f0f0; height: 20px; border-radius: 10px;">
                    <div class="abf-progress-bar" style="background: #0073aa; height: 100%; width: 0%; border-radius: 10px; transition: width 0.3s;"></div>
                </div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('.abf-install-update').click(function() {
                var button = $(this);
                var notice = button.closest('.abf-update-notice');
                var progress = notice.find('.abf-update-progress');
                var progressBar = notice.find('.abf-progress-bar');
                
                button.prop('disabled', true);
                progress.show();
                progressBar.css('width', '20%');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'abf_install_update',
                        _wpnonce: button.data('nonce')
                    },
                    success: function(response) {
                        progressBar.css('width', '100%');
                        if (response.success) {
                            notice.removeClass('notice-warning').addClass('notice-success');
                            notice.html('<h3>✅ Update erfolgreich installiert!</h3><p>Das Theme wurde auf Version <strong>v' + (response.data.version || 'unbekannt') + '</strong> aktualisiert.</p><p><a href="' + location.href + '" class="button button-primary">🔄 Seite neu laden</a></p>');
                        } else {
                            progressBar.css({'background': '#d63638', 'width': '100%'});
                            notice.find('.abf-update-progress p').html('❌ Update fehlgeschlagen: ' + (response.data && response.data.message ? response.data.message : 'Unbekannter Fehler'));
                            button.prop('disabled', false);
                        }
                    },
                    error: function() {
                        progressBar.css({'background': '#d63638', 'width': '100%'});
                        notice.find('.abf-update-progress p').html('❌ Verbindungsfehler beim Update');
                        button.prop('disabled', false);
                    }
                });
            });
            
            $('.abf-dismiss-notice').click(function() {
                $(this).closest('.notice').slideUp();
                // Transient für 1 Tag pausieren
                $.post(ajaxurl, {
                    action: 'abf_dismiss_update_notice',
                    _wpnonce: '<?php echo wp_create_nonce('abf_dismiss_notice'); ?>'
                });
            });
        });
        </script>
        
        <style>
        .abf-update-notice h3 { margin-top: 0; }
        .abf-update-notice details { margin: 15px 0; }
        .abf-update-notice summary { outline: none; }
        .abf-progress-bar { transition: width 0.3s ease; }
                 </style>
         <?php
     }
     
     /**
      * 🔍 Update-Prüfung Notice anzeigen
      */
     private function show_check_for_updates_notice() {
         $current_version = $this->theme_version;
         $nonce = wp_create_nonce('abf_force_update_check');
         
         ?>
         <div class="notice notice-info is-dismissible abf-check-updates-notice" style="position: relative;">
             <p>
                 <strong>🎨 ABF Styleguide Theme</strong> (v<?php echo esc_html($current_version); ?>)
                 <span style="margin-left: 15px;">
                     <button type="button" class="button button-secondary abf-check-updates" 
                             data-nonce="<?php echo esc_attr($nonce); ?>">
                         🔍 Jetzt auf Updates prüfen
                     </button>
                 </span>
             </p>
             
             <div class="abf-check-progress" style="display: none; margin-top: 10px;">
                 <p><em>🔄 Prüfe GitHub auf neue Updates...</em></p>
             </div>
         </div>
         
         <script>
         jQuery(document).ready(function($) {
             $('.abf-check-updates').click(function() {
                 var button = $(this);
                 var notice = button.closest('.abf-check-updates-notice');
                 var progress = notice.find('.abf-check-progress');
                 
                 button.prop('disabled', true);
                 progress.show();
                 
                 $.ajax({
                     url: ajaxurl,
                     type: 'POST',
                     data: {
                         action: 'abf_force_update_check',
                         _wpnonce: button.data('nonce')
                     },
                     success: function(response) {
                         if (response.success) {
                             progress.find('p').html('✅ ' + response.data.message);
                             if (response.data.latest_version && response.data.current_version !== response.data.latest_version) {
                                 setTimeout(function() {
                                     location.reload();
                                 }, 2000);
                             }
                         } else {
                             progress.find('p').html('❌ Fehler: ' + (response.data.message || 'Unbekannter Fehler'));
                         }
                         button.prop('disabled', false);
                     },
                     error: function() {
                         progress.find('p').html('❌ Verbindungsfehler bei der Update-Prüfung');
                         button.prop('disabled', false);
                     }
                 });
             });
         });
         </script>
         <?php
     }
     
     /**
     * 🧹 Bereinige WordPress Update-Verzeichnisse (Fix für häufiges Problem)
     */
    private function cleanup_update_directories() {
        $upgrade_temp_dir = WP_CONTENT_DIR . '/upgrade-temp-backup';
        
        if (is_dir($upgrade_temp_dir)) {
            // Rekursiv alle Inhalte des Backup-Verzeichnisses löschen
            $this->delete_directory_contents($upgrade_temp_dir);
        }
        
        // WordPress Upgrader temp Verzeichnis auch bereinigen
        $wp_upgrade_dir = WP_CONTENT_DIR . '/upgrade';
        if (is_dir($wp_upgrade_dir)) {
            $this->delete_directory_contents($wp_upgrade_dir);
        }
    }
    
    /**
     * 🗑️ Lösche Verzeichnis-Inhalte rekursiv (aber behalte das Verzeichnis)
     */
    private function delete_directory_contents($dir) {
        if (!is_dir($dir)) return;
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->delete_directory_contents($path);
                rmdir($path);
            } else {
                unlink($path);
            }
        }
    }

    /**
      * 🚀 Update installieren (AJAX)
      */
    public function install_update() {
        if (!current_user_can('update_themes')) {
            wp_die(__('You do not have sufficient permissions to update themes.'));
        }
        
        check_ajax_referer('abf_install_update');
        
        // 🧹 AUTOMATISCHE BEREINIGUNG vor dem Update (Fix für upgrade-temp-backup Problem)
        $this->cleanup_update_directories();
        
        $update_data = get_transient('abf_update_available');
        
        if (!$update_data) {
            wp_send_json_error(array('message' => 'Keine Update-Daten verfügbar'));
        }
        
        $download_url = $update_data['download_url'];
        $new_version = $update_data['latest_version'];
        
        // WordPress Upgrader verwenden
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/theme.php';
        
        $upgrader = new Theme_Upgrader();
        $result = $upgrader->install($download_url);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        } else {
            // Update erfolgreich - Transient löschen
            delete_transient('abf_update_available');
            
            wp_send_json_success(array(
                'message' => 'Theme erfolgreich aktualisiert!',
                'version' => $new_version
                         ));
         }
     }
     
         /**
     * 🚀 Manuelle Update-Prüfung (AJAX)
     */
    public function force_update_check() {
        if (!current_user_can('update_themes')) {
            wp_send_json_error(array('message' => 'Keine Berechtigung'));
        }
        
        check_ajax_referer('abf_force_update_check');
        
        // Cache löschen für sofortige Prüfung
        delete_transient('abf_update_available');
        delete_transient('abf_latest_release');
        
        // Sofortige Update-Prüfung
        $this->check_for_update(false);
        
        $update_data = get_transient('abf_update_available');
        
        if ($update_data) {
            wp_send_json_success(array(
                'message' => 'Update gefunden!',
                'current_version' => $update_data['current_version'],
                'latest_version' => $update_data['latest_version']
            ));
        } else {
            wp_send_json_success(array(
                'message' => 'Keine Updates verfügbar. Sie haben bereits die neueste Version.',
                'current_version' => $this->theme_version
            ));
        }
    }
 }
 
 // Initialisiere den Updater
 new ABF_Theme_Updater(); 