<?php
/**
 * Plugin Name: WordPress Backup Manager
 * Description: Simple backup manager starter plugin for WordPress portfolio use.
 * Version: 1.0.0
 * Author: Alan Teh
 */

if (!defined('ABSPATH')) exit;

class WP_Backup_Manager_Portfolio {
    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
    }

    public function menu() {
        add_management_page('Backup Manager', 'Backup Manager', 'manage_options', 'backup-manager', [$this, 'page']);
    }

    public function page() {
        if (!current_user_can('manage_options')) return;
        $message = '';
        if (isset($_POST['wpbm_backup']) && check_admin_referer('wpbm_backup_action')) {
            $message = $this->create_backup();
        }
        echo '<div class="wrap"><h1>WordPress Backup Manager</h1>';
        if ($message) echo '<div class="notice notice-success"><p>' . esc_html($message) . '</p></div>';
        echo '<p>Create a simple backup folder for demonstration purposes.</p>';
        echo '<form method="post">';
        wp_nonce_field('wpbm_backup_action');
        submit_button('Create Backup', 'primary', 'wpbm_backup');
        echo '</form></div>';
    }

    private function create_backup() {
        $upload_dir = wp_upload_dir();
        $backup_dir = trailingslashit($upload_dir['basedir']) . 'portfolio-backups';
        if (!file_exists($backup_dir)) wp_mkdir_p($backup_dir);
        $file = $backup_dir . '/backup-' . date('Ymd-His') . '.txt';
        file_put_contents($file, 'Backup created at ' . current_time('mysql'));
        return 'Backup created: ' . basename($file);
    }
}
new WP_Backup_Manager_Portfolio();
