<?php

if (! defined('ABSPATH')) {
    exit;
}

// Include reviews class
require_once IAT_PLUGIN_DIR . 'includes/admin/class-iat-reviews.php';

class Iat_Admin_Menu
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'iat_add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'iat_enqueue_admin_scripts'));
        add_action('admin_head', array($this, 'iat_highlight_menu_css'));
    }

    public function iat_add_admin_menu()
    {
        add_menu_page(
            __('Image Alt Text', 'image-alt-text'),
            __('Image Alt Text', 'image-alt-text'),
            'manage_options',
            'iat-with-alt-text',
            array($this, 'IAT_render_with_alt_text_page_view'),
            'dashicons-format-image',
            11
        );

        add_submenu_page(
            'iat-with-alt-text',
            __('Without Alt Text', 'image-alt-text'),
            __('Without Alt Text', 'image-alt-text'),
            'manage_options',
            'iat-without-alt-text',
            array($this, 'IAT_render_without_alt_text_page_view')
        );

        add_submenu_page(
            'iat-with-alt-text',
            __('Get Pro', 'image-alt-text'),
            __('Get Pro', 'image-alt-text'),
            'manage_options',
            'iat-get-pro',
            array($this, 'iat_render_get_pro_page_view')
        );

        // Remove submneu page
        add_action('admin_head', function () {
            remove_submenu_page('iat-with-alt-text', 'iat-with-alt-text');
            remove_submenu_page('iat-with-alt-text', 'iat-without-alt-text');
            remove_submenu_page('iat-with-alt-text', 'iat-get-pro');
        }, 999);
    }

    public function iat_highlight_menu_css()
    {
        $current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

        $plugin_pages = array(
            'iat-with-alt-text',
            'iat-without-alt-text',
            'iat-get-pro'
        );

        if (in_array($current_page, $plugin_pages, true)) {
?>
            <style>
                #adminmenu li.toplevel_page_iat-with-alt-text.wp-has-current-submenu,
                #adminmenu li.toplevel_page_iat-with-alt-text.current {
                    background-color: #1d2327;
                }

                #adminmenu li.toplevel_page_iat-with-alt-text.wp-has-current-submenu a.wp-has-current-submenu,
                #adminmenu li.toplevel_page_iat-with-alt-text.current a.current {
                    color: #fff;
                }

                #adminmenu li.toplevel_page_iat-with-alt-text.wp-has-current-submenu .wp-menu-image img,
                #adminmenu li.toplevel_page_iat-with-alt-text.current .wp-menu-image img {
                    opacity: 1;
                }

                #adminmenu li.toplevel_page_iat-with-alt-text.wp-has-current-submenu div.wp-menu-image:before,
                #adminmenu li.toplevel_page_iat-with-alt-text.current div.wp-menu-image:before {
                    color: #fff;
                }
            </style>
            <script>
                jQuery(document).ready(function($) {
                    var $menu = $('#adminmenu .toplevel_page_iat-with-alt-text');
                    if ($menu.length) {
                        $menu.removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open current');
                        $menu.find('> a').addClass('wp-has-current-submenu current').attr('aria-expanded', 'false');
                    }
                });
            </script>
<?php
        }
    }

    public function iat_enqueue_admin_scripts($hook)
    {
        // Load scripts only on plugin pages
        if (empty($hook) || strpos($hook, 'iat-') === false) {
            return;
        }

        // Additional security: Verify user capability
        if (!current_user_can('manage_options')) {
            return;
        }

        // css
        wp_enqueue_style('iat-bootstrap', IAT_PLUGIN_URL . 'assets/css/bootstrap.min.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-datatables', IAT_PLUGIN_URL . 'assets/css/datatables.min.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-datatables-responsive', IAT_PLUGIN_URL . 'assets/css/responsive.dataTables.min.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-toastr', IAT_PLUGIN_URL . 'assets/css/toastr.min.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-fontawesome', IAT_PLUGIN_URL . 'assets/css/fontawesome.min.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-fontawesome-all', IAT_PLUGIN_URL . 'assets/css/all.min.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-sweetalert2', IAT_PLUGIN_URL . 'assets/css/sweetalert2.min.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-swal-custom', IAT_PLUGIN_URL . 'assets/css/iat-swal-custom.css', ['iat-sweetalert2'], IAT_FILE_VERSION);
        wp_enqueue_style('iat-select2', IAT_PLUGIN_URL . 'assets/css/select2.min.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-admin-header', IAT_PLUGIN_URL . 'assets/css/iat-admin-header.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-admin-list', IAT_PLUGIN_URL . 'assets/css/iat-admin-list.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-admin-row-action', IAT_PLUGIN_URL . 'assets/css/iat-admin-row-action.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-admin-bulk-action', IAT_PLUGIN_URL . 'assets/css/iat-admin-bulk-action.css', [], IAT_FILE_VERSION);
        wp_enqueue_style('iat-admin-get-pro', IAT_PLUGIN_URL . 'assets/css/iat-admin-get-pro.css', [], IAT_FILE_VERSION);

        // js
        wp_enqueue_script('iat-bootstrap-bundle', IAT_PLUGIN_URL . 'assets/js/bootstrap.bundle.min.js', ['jquery'], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-datatables', IAT_PLUGIN_URL . 'assets/js/datatables.min.js', ['jquery'], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-datatables-responsive', IAT_PLUGIN_URL . 'assets/js/dataTables.responsive.min.js', ['jquery', 'iat-datatables'], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-toastr', IAT_PLUGIN_URL . 'assets/js/toastr.min.js', ['jquery'], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-fontawesome-all', IAT_PLUGIN_URL . 'assets/js/all.min.js', [], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-sweetalert2', IAT_PLUGIN_URL . 'assets/js/sweetalert2.min.js', [], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-select2', IAT_PLUGIN_URL . 'assets/js/select2.min.js', ['jquery'], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-admin-list', IAT_PLUGIN_URL . 'assets/js/iat-admin-list.js', ['jquery', 'iat-datatables', 'iat-datatables-responsive'], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-admin-row-action', IAT_PLUGIN_URL . 'assets/js/iat-admin-row-action.js', ['jquery'], IAT_FILE_VERSION, true);
        wp_enqueue_script('iat-admin-bulk-action', IAT_PLUGIN_URL . 'assets/js/iat-admin-bulk-action.js', ['jquery'], IAT_FILE_VERSION, true);

        // Load reviews slider JS only on Get Pro page
        $current_page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
        if ($current_page === 'iat-get-pro') {
            wp_enqueue_script('iat-admin-reviews-slider', IAT_PLUGIN_URL . 'assets/js/iat-admin-reviews-slider.js', [], IAT_FILE_VERSION, true);
        };

        // Localize scripts
        $localize_data = array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('image_alt_text_nonce'),
            'pluginUrl' => IAT_PLUGIN_URL,
            'currentPage' => isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '',
            'pluginLogo' =>  esc_url_raw(IAT_PLUGIN_URL . 'assets/images/image-alt-text-logo.png'),
        );

        wp_localize_script('iat-admin-list', 'iatObj', $localize_data);
        wp_localize_script('iat-admin-row-action', 'iatObj', $localize_data);
        wp_localize_script('iat-admin-bulk-action', 'iatObj', $localize_data);
    }

    public function iat_render_with_alt_text_page_view()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'image-alt-text'));
        }
        $this->iat_render_view('iat-list-view.php');
    }

    public function iat_render_without_alt_text_page_view()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'image-alt-text'));
        }
        $this->iat_render_view('iat-list-view.php');
    }

    public function iat_render_get_pro_page_view()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'image-alt-text'));
        }

        // Fetch WordPress.org reviews
        $reviews_handler = new Iat_Reviews();

        // Clear cache if requested (for testing) - with nonce verification
        if (isset($_GET['clear_review_cache']) && $_GET['clear_review_cache'] === '1') {
            // Verify nonce for security
            if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'clear_review_cache')) {
                $reviews_handler->clear_cache();
            }
        }

        $reviews = $reviews_handler->iat_get_all_reviews(); // Get ALL reviews (all star ratings)

        $this->iat_render_view('iat-get-pro-view.php', array('reviews' => $reviews));
    }

    private function iat_render_view($file, $data = array())
    {
        $reviews = isset($data['reviews']) ? $data['reviews'] : array();

        ob_start();
        $view = IAT_PLUGIN_DIR . 'views/admin/' . $file;
        if (file_exists($view)) {
            include $view;
        }
        echo ob_get_clean();
    }
}

new Iat_Admin_Menu();
