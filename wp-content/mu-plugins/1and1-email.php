<?php
/**
 * Plugin Name: 1&1 Managed WordPress
 * Plugin URI: http://www.1and1.com/
 * Description: Adds extra functionality to the WordPress core in order to achieve continuous delivery.
 * Version: 1.1.0
 * Author: 1&1
 * Author URI: http://www.1and1.com/
 */

if (!defined('ABSPATH')) {
    die();
}

class Safe_Mode_Install
{
    public function __construct()
    {
        add_action('init', array($this, 'load_hooks'));
    }

    public function load_hooks()
    {
        if ($this->is_safemode()) {
            $this->dont_email_updates();

            add_filter('auto_update_core', '__return_false');
            add_filter('user_has_cap', array($this, 'block_core_updates'));

            add_action('admin_init', array($this, 'admin_init'), 1);
        }
    }

    public function dont_email_updates()
    {
        // Don't send e-mails after an update
        add_filter('auto_core_update_send_email', '__return_false');
        add_filter('auto_core_update_notification_email', '__return_false');
        add_filter('automatic_updates_debug_email', '__return_false');
    }

    public function block_core_updates($allcaps)
    {
        $allcaps['update_core'] = false;

        return $allcaps;
    }


    public function admin_init()
    {
        /**
        // Don't show FTP credentials
        if (!defined('FS_METHOD')) {
        define('FS_METHOD', 'direct');
        }
         **/

        remove_action('admin_init', '_maybe_update_core');
        remove_action('wp_version_check', 'wp_version_check');

        remove_action('admin_notices', 'maintenance_nag');
        remove_action('admin_notices', 'update_nag', 3);
        remove_action('network_admin_notices', 'maintenance_nag');
    }

    private function is_safemode()
    {
        return preg_match("/^app\d{5,15}$/", basename(ABSPATH)) || preg_match("/^trial\d{9,15}$/", basename(ABSPATH));
    }

}

new Safe_Mode_Install;
