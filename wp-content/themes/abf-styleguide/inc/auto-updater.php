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
                    wp_send_json_error(array(
                        'message' => 'Update fehlgeschlagen: ' . $result->get_error_message(),
                        'details' => 'Auch nach Bereinigung und erneutem Versuch'
                    ));
                }
            }
            
            // Update erfolgreich - Transient löschen und final cleanup
            delete_transient('abf_update_available');
            $this->cleanup_update_directories(); // Aufräumen nach erfolgreichem Update
            
            wp_send_json_success(array(
                'message' => 'Theme erfolgreich aktualisiert!',
                'version' => $new_version
            ));
            
        } catch (Exception $e) {
            error_log('ABF Auto-Updater: Exception during update: ' . $e->getMessage());
            wp_send_json_error(array(
                'message' => 'Update-Fehler: ' . $e->getMessage()
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
 }
 
 // Initialisiere den Updater
 new ABF_Theme_Updater(); 