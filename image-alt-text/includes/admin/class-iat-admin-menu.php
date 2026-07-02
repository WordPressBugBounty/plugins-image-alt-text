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
        $current_page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

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
        // WordPress Dashicons: the plugin UI uses dashicons-* glyphs throughout. Enqueue it
        // explicitly instead of assuming another component already loaded it, so icons render
        // even when an optimizer strips "unused" core styles.
        wp_enqueue_style('dashicons');
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
            // Translatable strings used by the JS (so they reach translate.wordpress.org).
            'i18n' => array(
                // Generic row / list feedback
                'urlCopied'        => __('URL copied!', 'image-alt-text'),
                'processing'       => __('Processing...', 'image-alt-text'),
                'copied'           => __('Copied!', 'image-alt-text'),
                'added'            => __('Added!', 'image-alt-text'),
                'alreadySame'      => __('Alt text is already same.', 'image-alt-text'),
                'errorSaving'      => __('Error saving!', 'image-alt-text'),
                'saving'           => __('Saving...', 'image-alt-text'),
                'saved'            => __('Alt text saved successfully', 'image-alt-text'),
                'duplicateAlt'     => __('This alt text is already set', 'image-alt-text'),
                'failedSave'       => __('Failed to save. Please try again.', 'image-alt-text'),
                'networkError'     => __('Network error - please try again', 'image-alt-text'),
                'networkErrorConn' => __('Network error. Please check your connection.', 'image-alt-text'),
                // Bulk actions
                'chooseAction'        => __('Choose action...', 'image-alt-text'),
                'selectActionFirst'   => __('Please select an action first.', 'image-alt-text'),
                'selectAtLeastOne'    => __('Please select at least one image to process.', 'image-alt-text'),
                'actionCopyTitle'     => __('Copy Title to Alt Text', 'image-alt-text'),
                'actionCopyFilename'  => __('Copy Filename to Alt Text', 'image-alt-text'),
                'noticeNoTitleSkip'   => __('Images without a title will be skipped.', 'image-alt-text'),
                'noticeTitleSameSkip' => __('Images where the alt text already matches the title will be skipped.', 'image-alt-text'),
                'noticeNoUrlSkip'     => __('Images where the URL is not found will be skipped.', 'image-alt-text'),
                'noticeFileSameSkip'  => __('Images where the alt text already matches the filename will be skipped.', 'image-alt-text'),
                'confirmBulkTitle'    => __('Confirm Bulk Action', 'image-alt-text'),
                /* translators: %1$s: action name, %2$d: number of images */
                'confirmBulkFor'      => __('%1$s for %2$d image(s)', 'image-alt-text'),
                'confirmYes'          => __('Yes, process it!', 'image-alt-text'),
                'cancel'              => __('Cancel', 'image-alt-text'),
                'process'             => __('Process', 'image-alt-text'),
                'bulkSuccess'         => __('Bulk action completed successfully!', 'image-alt-text'),
                'processingFailed'    => __('Processing failed', 'image-alt-text'),
                /* translators: %s: error message */
                'bulkError'           => __('Error: %s', 'image-alt-text'),
                // DataTables UI
                'dtSearch'           => __('Search:', 'image-alt-text'),
                'dtLengthMenu'       => __('Show _MENU_ entries', 'image-alt-text'),
                'dtInfo'             => __('Showing _START_ to _END_ of _TOTAL_ entries', 'image-alt-text'),
                'dtInfoEmpty'        => __('Showing 0 to 0 of 0 entries', 'image-alt-text'),
                'dtInfoFiltered'     => __('(filtered from _MAX_ total entries)', 'image-alt-text'),
                'dtZeroRecords'      => __('No matching images found', 'image-alt-text'),
                'dtEmptyTable'       => __('No images found', 'image-alt-text'),
                'dtProcessing'       => __('Processing...', 'image-alt-text'),
                'dtPaginateFirst'    => __('First', 'image-alt-text'),
                'dtPaginateLast'     => __('Last', 'image-alt-text'),
                'dtPaginateNext'     => __('Next', 'image-alt-text'),
                'dtPaginatePrevious' => __('Previous', 'image-alt-text'),
            ),
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
        if (isset($_GET['clear_review_cache']) && sanitize_text_field(wp_unslash($_GET['clear_review_cache'])) === '1') {
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
