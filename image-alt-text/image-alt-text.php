<?php

/**
 * Plugin Name: Image Alt Text
 * Plugin URI: https://wordpress.org/plugins/image-alt-text/
 * Description: Easily manage, add, and update image alt text to enhance website accessibility and improve SEO performance.
 * Version: 4.0.0
 * Author: RS WebStudios
 * Author URI: https://rswebstudios.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: image-alt-text
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * 
 * @package ImageAltText
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Record install date on activation (used by review notice)
register_activation_hook(__FILE__, 'iat_on_activation');
function iat_on_activation()
{
    if (!get_option('iat_install_date')) {
        update_option('iat_install_date', time(), false);
    }
}

register_deactivation_hook(__FILE__, 'iat_on_deactivation');
function iat_on_deactivation()
{
    $timestamp = wp_next_scheduled('iat_refresh_reviews_cache');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'iat_refresh_reviews_cache');
    }
}

// Define plugin constants
define('IMAGE_ALT_TEXT', 'image-alt-text');
define('IAT_PLUGIN_VERSION', '4.0.0');
define('IAT_FILE_VERSION', '6.9.5');
define('IAT_PREFIX', 'iat_');
define('IAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IAT_PLUGIN_URL', plugin_dir_url(__FILE__));

class IMAGE_ALT_TEXT
{
    public function __construct()
    {
        // Plugin load dependencies
        add_action('plugins_loaded', array($this, 'iat_load_dependencies'));
        // Load text domain for translations
        add_action('init', array($this, 'iat_load_textdomain'));
        // Plugin row meta
        add_filter('plugin_row_meta', array($this, 'iat_plugin_row_meta'), 10, 2);
    }

    public function iat_load_dependencies()
    {
        $admin_files = array(
            'class-iat-review-notice.php',
            'class-iat-admin-menu.php',
            'class-iat-list.php',
            'class-iat-row-action.php',
            'class-iat-bulk-action.php',
        );

        foreach ($admin_files as $file) {
            require_once IAT_PLUGIN_DIR . 'includes/admin/' . $file;
        }
    }

    public function iat_load_textdomain()
    {
        load_plugin_textdomain('image-alt-text', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function iat_plugin_row_meta($links, $file)
    {
        if (plugin_basename(__FILE__) === $file) {
            $row_meta = array(
                'rate' => '<a href="https://wordpress.org/support/plugin/image-alt-text/reviews/#new-post" target="_blank">' . __('Rate this plugin ★★★★★', 'image-alt-text') . '</a>',
                'upgrade' => '<a href="https://imagealttext.in/pricing/?utm_source=image_alt_text_plugin&utm_medium=plugin&utm_campaign=get_pro&utm_content=pricing_page" target="_blank" style="color:#CC5500	;font-weight:bold;">' . __('Upgrade to Pro →', 'image-alt-text') . '</a>'
            );
            return array_merge($links, $row_meta);
        }
        return $links;
    }
}

new IMAGE_ALT_TEXT();
