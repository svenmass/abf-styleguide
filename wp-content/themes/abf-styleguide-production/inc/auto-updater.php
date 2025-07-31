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
        
        // Wöchentliche proaktive Bereinigung von Update-Verzeichnissen
        if (!wp_next_scheduled('abf_cleanup_update_directories')) {
            wp_schedule_event(time(), 'weekly', 'abf_cleanup_update_directories');
        }
        add_action('abf_cleanup_update_directories', array($this, 'scheduled_cleanup'));
        
        // Bereinigung beim WordPress-Login (für Admins) - einmal täglich
        add_action('wp_login', array($this, 'login_cleanup'), 10, 2);
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
            <div class="notice-info" style="background: #e7f3ff; padding: 10px; margin: 10px 0; border-left: 4px solid #0073aa;">
                <p><strong>💡 Tipp:</strong> Du kannst sowohl den "Jetzt installieren" Button unten verwenden, 
                als auch das WordPress-eigene Update-System unter Design → Themes.</p>
                <p><strong>🛡️ Sicherheit:</strong> Deine individuellen Farb- und Typografie-Einstellungen werden automatisch gesichert und nach dem Update wiederhergestellt!</p>
            </div>
            
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
        // Robust jQuery loading with fallback
        (function() {
            function initUpdateScript() {
                if (typeof jQuery === 'undefined') {
                    console.error('ABF Update: jQuery not available');
                    return;
                }
                
                jQuery(document).ready(function($) {
                    // Define AJAX URL with fallback
                    var ajax_url = typeof ajaxurl !== 'undefined' ? ajaxurl : '<?php echo admin_url('admin-ajax.php'); ?>';
                    
                    $('.abf-install-update').click(function() {
                        var button = $(this);
                        var notice = button.closest('.abf-update-notice');
                        var progress = notice.find('.abf-update-progress');
                        var progressBar = notice.find('.abf-progress-bar');
                        
                        console.log('ABF Update: Starting update process');
                        
                        button.prop('disabled', true);
                        progress.show();
                        progressBar.css('width', '20%');
                        
                        $.ajax({
                            url: ajax_url,
                            type: 'POST',
                            timeout: 120000, // 2 minutes timeout
                            data: {
                                action: 'abf_install_update',
                                _wpnonce: button.data('nonce')
                            },
                            success: function(response) {
                                console.log('ABF Update: AJAX Response:', response);
                                progressBar.css('width', '100%');
                                if (response.success) {
                                    notice.removeClass('notice-warning').addClass('notice-success');
                                    notice.html('<h3>✅ Update erfolgreich installiert!</h3><p>Das Theme wurde auf Version <strong>v' + (response.data.version || 'unbekannt') + '</strong> aktualisiert.</p><p><a href="' + location.href + '" class="button button-primary">🔄 Seite neu laden</a></p>');
                                } else {
                                    progressBar.css({'background': '#d63638', 'width': '100%'});
                                    var errorMsg = 'Unbekannter Fehler';
                                    if (response.data && response.data.message) {
                                        errorMsg = response.data.message;
                                    }
                                    notice.find('.abf-update-progress p').html('❌ Update fehlgeschlagen: ' + errorMsg);
                                    button.prop('disabled', false);
                                    console.error('ABF Update Error:', response);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('ABF Update AJAX Error:', {xhr: xhr, status: status, error: error});
                                progressBar.css({'background': '#d63638', 'width': '100%'});
                                var errorText = 'Verbindungsfehler beim Update';
                                if (status === 'timeout') {
                                    errorText = 'Update-Timeout - versuche es später erneut';
                                } else if (xhr.responseText) {
                                    errorText = 'Server-Fehler: ' + xhr.status;
                                }
                                notice.find('.abf-update-progress p').html('❌ ' + errorText);
                                button.prop('disabled', false);
                            }
                        });
                    });
                    
                    $('.abf-dismiss-notice').click(function() {
                        $(this).closest('.notice').slideUp();
                        // Transient für 1 Tag pausieren
                        $.post(ajax_url, {
                            action: 'abf_dismiss_update_notice',
                            _wpnonce: '<?php echo wp_create_nonce('abf_dismiss_notice'); ?>'
                        });
                    });
                });
            }
            
            // Initialize when ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initUpdateScript);
            } else {
                initUpdateScript();
            }
        })();
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
        // Robust jQuery loading for update check
        (function() {
            function initCheckScript() {
                if (typeof jQuery === 'undefined') {
                    console.error('ABF Update Check: jQuery not available');
                    return;
                }
                
                jQuery(document).ready(function($) {
                    var ajax_url = typeof ajaxurl !== 'undefined' ? ajaxurl : '<?php echo admin_url('admin-ajax.php'); ?>';
                    
                    $('.abf-check-updates').click(function() {
                        var button = $(this);
                        var notice = button.closest('.abf-check-updates-notice');
                        var progress = notice.find('.abf-check-progress');
                        
                        console.log('ABF Update Check: Starting check');
                        
                        button.prop('disabled', true);
                        progress.show();
                        
                        $.ajax({
                            url: ajax_url,
                            type: 'POST',
                            timeout: 30000, // 30 seconds timeout
                            data: {
                                action: 'abf_force_update_check',
                                _wpnonce: button.data('nonce')
                            },
                            success: function(response) {
                                console.log('ABF Update Check Response:', response);
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
                            error: function(xhr, status, error) {
                                console.error('ABF Update Check Error:', {xhr: xhr, status: status, error: error});
                                var errorText = 'Verbindungsfehler bei der Update-Prüfung';
                                if (status === 'timeout') {
                                    errorText = 'Timeout - GitHub möglicherweise nicht erreichbar';
                                }
                                progress.find('p').html('❌ ' + errorText);
                                button.prop('disabled', false);
                            }
                        });
                    });
                });
            }
            
            // Initialize when ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCheckScript);
            } else {
                initCheckScript();
            }
        })();
        </script>
         <?php
     }
     
     /**
     * 🧹 Bereinige WordPress Update-Verzeichnisse (Fix für häufiges Problem)
     * Robuste Methode die auch bei Berechtigungsproblemen funktioniert
     */
    private function cleanup_update_directories() {
        $directories = array(
            WP_CONTENT_DIR . '/upgrade-temp-backup',
            WP_CONTENT_DIR . '/upgrade',
            WP_CONTENT_DIR . '/upgrader-temp-backup', // Alternative Namen
            get_temp_dir() . 'wp-upgrade-temp-backup'
        );
        
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $this->force_delete_directory($dir, true);
            }
        }
        
        // Zusätzlich: Alte temporäre Theme-Dateien bereinigen
        $this->cleanup_temp_theme_files();
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
     * 💪 Robuste Verzeichnis-Löschung mit mehreren Fallback-Strategien
     * Funktioniert auch bei Berechtigungsproblemen und blockierten Dateien
     */
    private function force_delete_directory($dir, $keep_directory = false) {
        if (!is_dir($dir)) return true;
        
        // Strategie 1: Standard PHP-Löschung
        if ($this->try_standard_delete($dir, $keep_directory)) {
            return true;
        }
        
        // Strategie 2: Berechtigungen ändern und erneut versuchen
        if ($this->try_permission_fix_delete($dir, $keep_directory)) {
            return true;
        }
        
        // Strategie 3: WordPress-eigene Filesystem-Funktionen verwenden
        if ($this->try_wp_filesystem_delete($dir, $keep_directory)) {
            return true;
        }
        
        // Fallback: Nur Inhalt löschen, was möglich ist
        $this->cleanup_what_possible($dir);
        
        return false;
    }
    
    /**
     * 🔧 Standard-Löschung versuchen
     */
    private function try_standard_delete($dir, $keep_directory = false) {
        try {
            if ($keep_directory) {
                $this->delete_directory_contents($dir);
                return true;
            } else {
                return $this->recursive_remove_directory($dir);
            }
        } catch (Exception $e) {
            error_log('ABF Auto-Updater: Standard delete failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 🔐 Berechtigungen ändern und Löschung versuchen
     */
    private function try_permission_fix_delete($dir, $keep_directory = false) {
        try {
            // Berechtigungen rekursiv ändern
            $this->chmod_recursive($dir, 0755);
            
            if ($keep_directory) {
                $this->delete_directory_contents($dir);
                return true;
            } else {
                return $this->recursive_remove_directory($dir);
            }
        } catch (Exception $e) {
            error_log('ABF Auto-Updater: Permission fix delete failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 📁 WordPress Filesystem API verwenden
     */
    private function try_wp_filesystem_delete($dir, $keep_directory = false) {
        if (!function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        if (!WP_Filesystem()) {
            return false;
        }
        
        global $wp_filesystem;
        
        try {
            if ($keep_directory) {
                $files = $wp_filesystem->dirlist($dir, false, true);
                if ($files) {
                    foreach ($files as $file) {
                        $wp_filesystem->delete($dir . '/' . $file['name'], true);
                    }
                }
                return true;
            } else {
                return $wp_filesystem->delete($dir, true);
            }
        } catch (Exception $e) {
            error_log('ABF Auto-Updater: WP Filesystem delete failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 🧹 Bereinige was möglich ist (letzter Ausweg)
     */
    private function cleanup_what_possible($dir) {
        $files = @scandir($dir);
        if ($files) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $path = $dir . '/' . $file;
                if (is_file($path) && is_writable($path)) {
                    @unlink($path);
                } elseif (is_dir($path) && is_writable($path)) {
                    $this->cleanup_what_possible($path);
                    @rmdir($path);
                }
            }
        }
    }
    
    /**
     * 🗂️ Rekursive Verzeichnis-Entfernung
     */
    private function recursive_remove_directory($dir) {
        if (!is_dir($dir)) return true;
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->recursive_remove_directory($path);
            } else {
                unlink($path);
            }
        }
        return rmdir($dir);
    }
    
    /**
     * 🔒 Berechtigungen rekursiv ändern
     */
    private function chmod_recursive($dir, $permissions) {
        if (!is_dir($dir)) return false;
        
        @chmod($dir, $permissions);
        
        $files = @scandir($dir);
        if ($files) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $path = $dir . '/' . $file;
                if (is_dir($path)) {
                    $this->chmod_recursive($path, $permissions);
                } else {
                    @chmod($path, 0644);
                }
            }
        }
        return true;
    }
    
    /**
     * 🧹 Bereinige temporäre Theme-Dateien
     */
    private function cleanup_temp_theme_files() {
        $temp_dirs = array(
            get_temp_dir(),
            WP_CONTENT_DIR . '/uploads',
            WP_CONTENT_DIR . '/cache'
        );
        
        foreach ($temp_dirs as $temp_dir) {
            if (!is_dir($temp_dir)) continue;
            
            $files = @glob($temp_dir . '/*abf-styleguide*');
            if ($files) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        @unlink($file);
                    } elseif (is_dir($file)) {
                        $this->force_delete_directory($file, false);
                    }
                }
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
        
        // 💾 USER-SETTINGS SICHERN vor dem Update
        $user_settings_backup = $this->backup_user_settings();
        
        // 🧹 EXTENSIVE BEREINIGUNG vor dem Update (mehrfach für Sicherheit)
        $this->cleanup_update_directories();
        sleep(1); // Kurze Pause für Filesystem
        $this->cleanup_update_directories(); // Zweite Bereinigung
        
        $update_data = get_transient('abf_update_available');
        
        if (!$update_data) {
            wp_send_json_error(array('message' => 'Keine Update-Daten verfügbar'));
        }
        
        $download_url = $update_data['download_url'];
        $new_version = $update_data['latest_version'];
        
        // WordPress Upgrader verwenden
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/theme.php';
        
        try {
            $upgrader = new Theme_Upgrader();
            $result = $upgrader->install($download_url);
            
            if (is_wp_error($result)) {
                // Bei Fehler: Nochmalige Bereinigung und zweiter Versuch
                error_log('ABF Auto-Updater: First attempt failed, trying cleanup and retry');
                $this->cleanup_update_directories();
                sleep(2);
                
                $result = $upgrader->install($download_url);
                
                if (is_wp_error($result)) {
                    // Bei gescheitertem Update: Versuche User-Settings trotzdem zu retten
                    $this->restore_user_settings($user_settings_backup);
                    
                    wp_send_json_error(array(
                        'message' => 'Update fehlgeschlagen: ' . $result->get_error_message(),
                        'details' => 'Auch nach Bereinigung und erneutem Versuch. User-Settings wurden soweit möglich beibehalten.'
                    ));
                }
            }
            
            // Update erfolgreich - User-Settings wiederherstellen
            $restore_status = $this->restore_user_settings($user_settings_backup);
            
            // Transient löschen und final cleanup
            delete_transient('abf_update_available');
            $this->cleanup_update_directories(); // Aufräumen nach erfolgreichem Update
            
            $success_message = 'Theme erfolgreich aktualisiert!';
            if ($restore_status['colors_restored'] || $restore_status['typography_restored']) {
                $success_message .= ' Deine individuellen Einstellungen wurden beibehalten.';
            }
            
            wp_send_json_success(array(
                'message' => $success_message,
                'version' => $new_version,
                'restored_settings' => $restore_status
            ));
            
        } catch (Exception $e) {
            // Bei Exception: User-Settings wiederherstellen falls möglich
            if (isset($user_settings_backup)) {
                $this->restore_user_settings($user_settings_backup);
            }
            
            error_log('ABF Auto-Updater: Exception during update: ' . $e->getMessage());
            wp_send_json_error(array(
                'message' => 'Update-Fehler: ' . $e->getMessage() . '. User-Settings wurden soweit möglich beibehalten.'
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
    
    /**
     * 🕐 Geplante Bereinigung (Cron-Job)
     */
    public function scheduled_cleanup() {
        error_log('ABF Auto-Updater: Running scheduled cleanup');
        $this->cleanup_update_directories();
    }
    
    /**
     * 🔐 Bereinigung beim Admin-Login (einmal täglich)
     */
    public function login_cleanup($user_login, $user) {
        if (!user_can($user, 'update_themes')) {
            return;
        }
        
        // Nur einmal täglich pro Benutzer
        $last_cleanup = get_user_meta($user->ID, 'abf_last_login_cleanup', true);
        if ($last_cleanup && (time() - $last_cleanup) < DAY_IN_SECONDS) {
            return;
        }
        
        $this->cleanup_update_directories();
        update_user_meta($user->ID, 'abf_last_login_cleanup', time());
        error_log('ABF Auto-Updater: Login cleanup performed for user: ' . $user_login);
    }
    
    /**
     * 💾 User-Settings vor Update sichern
     */
    private function backup_user_settings() {
        $backup_data = array(
            'timestamp' => current_time('mysql'),
            'colors' => null,
            'typography' => null,
            'acf_colors' => null,
            'acf_typography' => null,
            'backup_path' => null
        );
        
        // 1. colors.json sichern
        $colors_file = get_template_directory() . '/colors.json';
        if (file_exists($colors_file)) {
            $colors_content = file_get_contents($colors_file);
            if ($colors_content) {
                $backup_data['colors'] = $colors_content;
                error_log('ABF Auto-Updater: Colors backup created');
            }
        }
        
        // 2. typography.json sichern (falls vorhanden)
        $typography_file = get_template_directory() . '/typography.json';
        if (file_exists($typography_file)) {
            $typography_content = file_get_contents($typography_file);
            if ($typography_content) {
                $backup_data['typography'] = $typography_content;
                error_log('ABF Auto-Updater: Typography backup created');
            }
        }
        
        // 3. ACF Options aus Datenbank sichern
        $backup_data['acf_colors'] = get_field('colors', 'option');
        $backup_data['acf_typography'] = array(
            'font_sizes' => get_field('font_sizes', 'option'),
            'font_weights' => get_field('font_weights', 'option')
        );
        
        // 4. Backup-Datei erstellen (in uploads-Verzeichnis für Sicherheit)
        $uploads_dir = wp_upload_dir();
        $backup_dir = $uploads_dir['basedir'] . '/abf-theme-backups';
        
        if (!file_exists($backup_dir)) {
            wp_mkdir_p($backup_dir);
        }
        
        $backup_filename = 'user-settings-backup-' . date('Y-m-d-H-i-s') . '.json';
        $backup_path = $backup_dir . '/' . $backup_filename;
        
        if (file_put_contents($backup_path, json_encode($backup_data, JSON_PRETTY_PRINT))) {
            $backup_data['backup_path'] = $backup_path;
            error_log('ABF Auto-Updater: Settings backup saved to: ' . $backup_path);
        } else {
            error_log('ABF Auto-Updater: Failed to save backup file');
        }
        
        return $backup_data;
    }
    
    /**
     * 🔄 User-Settings nach Update wiederherstellen
     */
    private function restore_user_settings($backup_data) {
        $restore_status = array(
            'colors_restored' => false,
            'typography_restored' => false,
            'acf_restored' => false,
            'errors' => array()
        );
        
        if (!$backup_data || !is_array($backup_data)) {
            $restore_status['errors'][] = 'Keine Backup-Daten verfügbar';
            return $restore_status;
        }
        
        // 1. colors.json wiederherstellen
        if (!empty($backup_data['colors'])) {
            $colors_file = get_template_directory() . '/colors.json';
            if (file_put_contents($colors_file, $backup_data['colors'])) {
                $restore_status['colors_restored'] = true;
                error_log('ABF Auto-Updater: Colors restored successfully');
            } else {
                $restore_status['errors'][] = 'Fehler beim Wiederherstellen der colors.json';
            }
        }
        
        // 2. typography.json wiederherstellen
        if (!empty($backup_data['typography'])) {
            $typography_file = get_template_directory() . '/typography.json';
            if (file_put_contents($typography_file, $backup_data['typography'])) {
                $restore_status['typography_restored'] = true;
                error_log('ABF Auto-Updater: Typography restored successfully');
            } else {
                $restore_status['errors'][] = 'Fehler beim Wiederherstellen der typography.json';
            }
        }
        
        // 3. ACF Options wiederherstellen
        $acf_errors = 0;
        
        if (!empty($backup_data['acf_colors'])) {
            if (update_field('colors', $backup_data['acf_colors'], 'option')) {
                error_log('ABF Auto-Updater: ACF colors restored');
            } else {
                $acf_errors++;
            }
        }
        
        if (!empty($backup_data['acf_typography']) && is_array($backup_data['acf_typography'])) {
            if (!empty($backup_data['acf_typography']['font_sizes'])) {
                if (!update_field('font_sizes', $backup_data['acf_typography']['font_sizes'], 'option')) {
                    $acf_errors++;
                }
            }
            if (!empty($backup_data['acf_typography']['font_weights'])) {
                if (!update_field('font_weights', $backup_data['acf_typography']['font_weights'], 'option')) {
                    $acf_errors++;
                }
            }
        }
        
        if ($acf_errors === 0) {
            $restore_status['acf_restored'] = true;
            error_log('ABF Auto-Updater: ACF options restored successfully');
        } else {
            $restore_status['errors'][] = 'Fehler beim Wiederherstellen der ACF-Optionen (' . $acf_errors . ' Fehler)';
        }
        
        // 4. Alte Backup-Dateien bereinigen (behalte nur die letzten 5)
        $this->cleanup_old_backups();
        
        if (!empty($restore_status['errors'])) {
            error_log('ABF Auto-Updater: Restore completed with errors: ' . implode(', ', $restore_status['errors']));
        }
        
        return $restore_status;
    }
    
    /**
     * 🧹 Alte Backup-Dateien bereinigen
     */
    private function cleanup_old_backups() {
        $uploads_dir = wp_upload_dir();
        $backup_dir = $uploads_dir['basedir'] . '/abf-theme-backups';
        
        if (!is_dir($backup_dir)) {
            return;
        }
        
        $backup_files = glob($backup_dir . '/user-settings-backup-*.json');
        
        if (count($backup_files) > 5) {
            // Sortiere nach Änderungsdatum (älteste zuerst)
            usort($backup_files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Lösche die ältesten, behalte nur die letzten 5
            $files_to_delete = array_slice($backup_files, 0, -5);
            foreach ($files_to_delete as $file) {
                @unlink($file);
            }
            
            error_log('ABF Auto-Updater: Cleaned up ' . count($files_to_delete) . ' old backup files');
        }
    }
 }
 
 // Initialisiere den Updater
 new ABF_Theme_Updater(); 