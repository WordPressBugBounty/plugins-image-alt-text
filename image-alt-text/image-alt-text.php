<?php

/**
 * Plugin Name: Image Alt Text
 * Plugin URI: https://wordpress.org/plugins/image-alt-text/
 * Description: Easily manage, add, and update image alt text to enhance website accessibility and improve SEO performance.
 * Version: 4.1.0
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
define('IAT_PLUGIN_VERSION', get_file_data(__FILE__, ['Version' => 'Version'])['Version']);
define('IAT_FILE_VERSION', IAT_PLUGIN_VERSION);
define('IAT_PREFIX', 'iat_');
define('IAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IAT_PLUGIN_URL', plugin_dir_url(__FILE__));

if (!function_exists('iat_smart_ucfirst')) {
    /**
     * Capitalize the first character for natural sentence-case reading, while
     * preserving intentional brand / camelCase like "iPhone" or "eBay"
     * (skips when the first letter is lowercase but the second is uppercase).
     *
     * @param string $text Input text.
     * @return string Text with a safely capitalized first letter.
     */
    function iat_smart_ucfirst($text)
    {
        if ($text === '') {
            return $text;
        }

        $has_mb  = function_exists('mb_substr');
        $first   = $has_mb ? mb_substr($text, 0, 1) : substr($text, 0, 1);
        $second  = $has_mb ? mb_substr($text, 1, 1) : substr($text, 1, 1);
        $upper_1 = function_exists('mb_strtoupper') ? mb_strtoupper($first) : strtoupper($first);

        // Already uppercase or not a lowercase letter — leave it.
        if ($first === $upper_1) {
            return $text;
        }

        // Preserve camelCase / brand casing: lowercase first + uppercase-LETTER second.
        if ($second !== '' && preg_match('/[A-Za-z]/', $second)) {
            $upper_2 = function_exists('mb_strtoupper') ? mb_strtoupper($second) : strtoupper($second);
            if ($second === $upper_2) {
                return $text;
            }
        }

        return $upper_1 . ($has_mb ? mb_substr($text, 1) : substr($text, 1));
    }
}

if (!function_exists('iat_clean_filename_for_alt')) {
    /**
     * Convert a raw image filename (without extension) into human-readable alt text.
     *
     * Strips WordPress auto-generated size / scaled / edit suffixes and turns common
     * separators into spaces, so "IMG_0421-1024x768-scaled" becomes "IMG 0421".
     * Always falls back to the original string so it can never return empty alt text.
     *
     * @param string $filename Filename WITHOUT extension.
     * @return string Cleaned, human-readable text.
     */
    function iat_clean_filename_for_alt($filename)
    {
        $original = (string) $filename;
        $text     = $original;

        // Repeatedly strip trailing WordPress-generated suffixes — they can stack
        // (e.g. "-1024x768-scaled"), so loop until nothing more is removed.
        do {
            $before = $text;
            $text = preg_replace('/-\d+x\d+$/', '', $text);           // dimensions e.g. -1024x768
            $text = preg_replace('/-(scaled|rotated)$/i', '', $text); // -scaled / -rotated
            $text = preg_replace('/-e\d{10,}$/i', '', $text);         // edit hash e.g. -e1693400000000
        } while ($text !== $before);

        // Turn common separators into spaces.
        $text = str_replace(array('-', '_', '.', '+'), ' ', $text);

        // Collapse repeated whitespace and trim.
        $text = trim(preg_replace('/\s+/', ' ', $text));

        // Fallback: if cleanup emptied the string, humanize the original minimally,
        // and as a last resort keep the original so we never return empty alt text.
        if ($text === '') {
            $text = trim(preg_replace('/\s+/', ' ', str_replace(array('-', '_', '.', '+'), ' ', $original)));
        }
        if ($text === '') {
            $text = $original;
        }

        // Capitalize the first character (camelCase / brand-safe).
        $text = iat_smart_ucfirst($text);

        return $text;
    }
}

if (!function_exists('iat_clean_title_for_alt')) {
    /**
     * Lightly clean an attachment TITLE into readable alt text.
     *
     * Titles are often human-edited, so this is gentler than the filename cleaner:
     * it only turns "-", "_" and "+" into spaces and fixes capitalization. It does
     * NOT strip dimension/-scaled/edit suffixes (those are filename artifacts) and
     * keeps "." so real punctuation like "U.S.A." survives. Never returns empty.
     *
     * @param string $title Attachment post title.
     * @return string Cleaned, human-readable text.
     */
    function iat_clean_title_for_alt($title)
    {
        $original = (string) $title;

        // Turn filename-style separators into spaces (keep "." for punctuation).
        $text = str_replace(array('-', '_', '+'), ' ', $original);

        // Collapse repeated whitespace and trim.
        $text = trim(preg_replace('/\s+/', ' ', $text));

        // Never return empty — fall back to the original title.
        if ($text === '') {
            $text = trim($original);
        }
        if ($text === '') {
            $text = $original;
        }

        // Capitalize the first character (camelCase / brand-safe).
        $text = iat_smart_ucfirst($text);

        return $text;
    }
}

if (!function_exists('iat_get_alt_coverage_stats')) {
    /**
     * Get alt-text coverage stats for the media library (images only).
     *
     * Uses WP_Query found_posts (SQL count) rather than loading every attachment,
     * so it stays cheap on large libraries.
     *
     * @return array{total:int,with:int,without:int,percent:int}
     */
    function iat_get_alt_coverage_stats()
    {
        $base_args = array(
            'post_type'              => 'attachment',
            'post_mime_type'         => 'image',
            'post_status'            => 'inherit',
            'posts_per_page'         => 1,
            'fields'                 => 'ids',
            'no_found_rows'          => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );

        $total_query = new WP_Query($base_args);
        $total       = (int) $total_query->found_posts;

        $with_args               = $base_args;
        $with_args['meta_query'] = array(
            array(
                'key'     => '_wp_attachment_image_alt',
                'value'   => '',
                'compare' => '!=',
            ),
        );
        $with_query = new WP_Query($with_args);
        $with       = (int) $with_query->found_posts;

        if ($with > $total) {
            $with = $total;
        }
        $without = $total - $with;
        $percent = $total > 0 ? (int) round(($with / $total) * 100) : 0;

        return array(
            'total'   => $total,
            'with'    => $with,
            'without' => $without,
            'percent' => $percent,
        );
    }
}

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
