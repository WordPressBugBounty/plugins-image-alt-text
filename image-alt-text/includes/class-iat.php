<?php

defined('ABSPATH') or die('Plugin file cannot be accessed directly.');

class class_iat
{

    public $conn;
    public $wp_posts;
    public $wp_postmeta;

    public function __construct()
    {

        global $wpdb;
        $this->conn = $wpdb;
        $this->wp_posts = $this->conn->prefix . 'posts';
        $this->wp_postmeta = $this->conn->prefix . 'postmeta';
        /* without alt text list */
        add_action('wp_ajax_iat_get_without_alt_text_list', [$this, 'fn_iat_get_without_alt_text_list']);
        /* with alt list  */
        add_action('wp_ajax_iat_get_with_alt_text_list', [$this, 'fn_iat_get_with_alt_text_list']);
        /* add alt text */
        add_action('wp_ajax_iat_add_alt_text', [$this, 'fn_iat_add_alt_text']);
        /* copy post title to alt text */
        add_action('wp_ajax_iat_copy_post_title_to_alt_text', [$this, 'fn_iat_copy_post_title_to_alt_text']);
        /* copy bulk post title to alt text  */
        add_action('wp_ajax_iat_copy_bulk_post_title_to_alt_text', [$this, 'fn_iat_copy_bulk_post_title_to_alt_text']);
        /* update with alt text */
        add_action('wp_ajax_iat_update_existing_alt_text', [$this, 'fn_iat_update_existing_alt_text']);
        /* copy attached post title to alt text */
        add_action('wp_ajax_iat_copy_attached_post_title_to_alt_text', [$this, 'fn_iat_copy_attached_post_title_to_alt_text']);
        /* copy bulk attached post title to alt text */
        add_action('wp_ajax_iat_copy_bulk_attached_post_title_to_alt_text', [$this, 'fn_iat_copy_bulk_attached_post_title_to_alt_text']);
    }

    public function fn_iat_get_without_alt_text_list()
    {
        if (!current_user_can('manage_options')) {
            return false;
        }

        if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'iat_image_alt_text')) {
            wp_die(esc_html(__('Security check. Hacking not allowed', IMAGE_ALT_TEXT)));
        }

        $this->fn_iat_get_attachment_data('without-alt');
    }

    public function fn_iat_get_with_alt_text_list()
    {
        if (!current_user_can('manage_options')) {
            return false;
        }

        if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'iat_image_alt_text')) {
            wp_die(esc_html(__('Security check. Hacking not allowed', IMAGE_ALT_TEXT)));
        }

        $this->fn_iat_get_attachment_data('with-alt');
    }

    public function fn_iat_get_attachment_data($type = '')
    {
        $without_alt = [];
        $with_alt = [];
        $post_type = 'attachment';
        $post_mime_type = 'image';
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
        $search_value = isset($_POST['search']['value']) ? sanitize_text_field($_POST['search']['value']) : '';
        if ($type == 'with-alt') {
            $meta_query = [
                [
                    'key'     => '_wp_attachment_image_alt',
                    'value'   => '',
                    'compare' => '!=',
                ]
            ];
        } else if ($type == 'without-alt') {
            $meta_query = [
                'relation' => 'OR',
                [
                    'key'     => '_wp_attachment_image_alt',
                    'value'   => '',
                    'compare' => '='
                ],
                [
                    'key'     => '_wp_attachment_image_alt',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }

        $posts = get_posts(array(
            'post_type'      => $post_type,
            'posts_per_page' => $length,
            'offset'         => $start,
            'post_mime_type' => $post_mime_type,
            'meta_query'     => $meta_query,
            's'              => $search_value,
        ));

        foreach ($posts as $post) {
            $post_id = $post->ID;
            $post_alt_text = get_post_meta($post_id, '_wp_attachment_image_alt', true);
            if (empty($post_alt_text)) {
                $without_alt[] = [
                    'post_id'    => $post_id,
                    'post_image' => $this->fn_iat_post_image_html($post_id),
                    'post_title' => $this->fn_iat_post_title_html($post_id, $post->post_title, $type),
                    'post_url'     => $this->fn_iat_post_url_html($post_id),
                    'post_date'  => get_the_date('M d, Y', $post_id),
                    'post_alt_text'   => $post_alt_text,
                    'iat_add_alt_text_form' => $this->fn_iat_add_alt_text_form_html($post_id, $post_alt_text, $type),
                    'iat_action' => $this->fn_iat_action_html($post_id),
                    'attached_to' => $this->fn_iat_get_attached_post($post_id, $type)
                ];
            } else {
                $with_alt[] = array(
                    'post_id'    => $post_id,
                    'post_image' => $this->fn_iat_post_image_html($post_id),
                    'post_title' => $this->fn_iat_post_title_html($post_id, $post->post_title, $type),
                    'post_url'     => $this->fn_iat_post_url_html($post_id),
                    'post_date'  => get_the_date('M d, Y', $post_id),
                    'post_alt_text'   => $post_alt_text,
                    'iat_add_alt_text_form' => $this->fn_iat_add_alt_text_form_html($post_id, $post_alt_text, $type),
                    'iat_action' => $this->fn_iat_action_html($post_id),
                    'attached_to' => $this->fn_iat_get_attached_post($post_id, $type),
                );
            }
        }

        if ($search_value) {
            $records_filtered = count($posts);
        } else {
            $records_filtered = $this->fn_iat_count_total_attachment_record($type);
        }

        $response_data = [
            'draw' => $draw,
            'recordsTotal' => $this->fn_iat_count_total_attachment_record($type),
            'recordsFiltered' => $records_filtered,
        ];

        if ($type == 'with-alt') {
            $response_data['data'] = $with_alt;
        } else if ($type == 'without-alt') {
            $response_data['data'] = $without_alt;
        }

        echo wp_json_encode($response_data);

        wp_die();
    }

    public function fn_iat_post_image_html($post_id = null)
    {
        if (empty($post_id)) {
            return '';
        }

        $post_image_url = wp_get_original_image_url($post_id);

        if (!$post_image_url) {
            return '';
        }

        return sprintf(
            '<a class="iat-post-image" href="%s" target="_blank"><img src="%s" width="80" height="80" alt="%s" /></a>',
            esc_url($post_image_url),
            esc_url($post_image_url),
            esc_attr(get_the_title($post_id))
        );
    }

    public function fn_iat_post_title_html($post_id = '', $post_title = '', $type = '')
    {
        $post_id = esc_attr($post_id);
        $post_title = esc_html($post_title);
        $html = '';
        $html .= '<div class="iat-post-title" id="iat-post-title-' . $post_id . '">';
        $html .= '<div id="iat-post-title-to-alt-text-' . $post_id . '">' . $post_title . '</div>';
        $html .= '<div class="iat-copy-post-title-to-alt-text" id="iat-copy-post-title-to-alt-text-' . $post_id . '">';
        $html .= '<p class="mt-1" data-post-title="' . $post_title . '" data-type="' . $type . '" onclick="fnIatCopyPostTitleToAltText(this, ' . $post_id . ')">';
        $html .= '<i class="loader-1 me-1" id="iat-copy-post-title-loader-' . $post_id . '" style="display:none;"></i>';
        $html .= 'Copy image title to alt text';
        $html .= '</p>';
        $html .= '</div>';
        $html .= '<div id="iat-copy-post-title-to-alt-text-display-msg-' . $post_id . '" style="display:none;">';
        $html .= 'Alt text: <b style="color:green;font-weight:600;"></b>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }


    public function fn_iat_post_url_html($post_id = '')
    {

        $html = '';
        $html .= '<span class="iat-copy-url-span">';
        $html .= '<p id="iat-copy-url-' . esc_attr($post_id) . '" data-post-id="' . esc_attr($post_id) . '" data-url="' . esc_url(wp_get_attachment_url($post_id)) . '">' . esc_html(wp_get_attachment_url($post_id)) . '</p>';
        $html .= '</span>';
        return $html;
    }

    public function fn_iat_add_alt_text_form_html($post_id = '', $post_alt = '', $type = '')
    {
        $html = '';
        if ($type == 'with-alt') {
            $html .= '<div class="iat-update-ex-alt-text" id="iat-update-ex-alt-text-' . $post_id . '">';
            $html .= '<div class="iat-display-ex-alt-text" id="iat-display-ex-alt-text-' . $post_id . '">Alt text: <b>' . esc_html($post_alt) . '</b></div>';
            $html .= '<div class="iat-display-updated-ex-alt-text" id="iat-display-updated-ex-alt-text-' . $post_id . '" style="display:none">Updated text: <b style="color:green;font-weight:600;"></b></div>';
            $html .= '<div class="iat-updated-ex-alt-text-btn-area d-flex align-items-center" id="iat-updated-ex-alt-text-btn-area-' . $post_id . '">';
            $html .= '<input type="text" class="form-control form-control-sm me-3" id="iat-updated-ex-alt-text-input-' . esc_attr($post_id) . '" placeholder="Enter alt text" />';
            $html .= '<button type="button" class="btn btn-secondary btn-sm iat-update-ex-alt-text-btn" id="iat-update-ex-alt-text-btn-' . $post_id . '" data-post-id="' . esc_attr($post_id) . '">';
            $html .= '<i class="loader me-1" id="iat-update-ex-alt-text-loader-' . esc_attr($post_id) . '" style="display:none;"></i>Update';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '</div>';
        } elseif ($type == 'without-alt') {
            $html .= '<div class="iat-add-alt-text" id="iat-add-alt-text-' . $post_id . '">';
            $html .= '<div class="iat-display-added-alt-text" id="iat-display-added-alt-text-' . $post_id . '" style="display:none">Added text: <b style="color:green;font-weight:600;"></b></div>';
            $html .= '<div class="iat-add-alt-text-btn-area d-flex align-items-center" id="iat-add-alt-text-btn-area-' . $post_id . '">';
            $html .= '<input type="text" class="form-control form-control-sm me-3" id="iat-add-alt-text-input-' . esc_attr($post_id) . '" placeholder="Enter alt text" />';
            $html .= '<button type="button" class="btn btn-secondary btn-sm iat-add-alt-text-btn" id="iat-add-alt-text-btn-' . esc_attr($post_id) . '" data-post-id="' . esc_attr($post_id) . '">';
            $html .= '<i class="loader me-1" id="iat-add-alt-text-loader-' . esc_attr($post_id) . '" style="display:none;"></i>Add';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }


    public function fn_iat_action_html($post_id = '')
    {
        $html = '';
        $html .= '<div class="iat-action-html" id="iat-action-html-' . $post_id . '">';
        $html .= '<a class="btn btn-secondary btn-sm" href="' . esc_url(admin_url() . 'upload.php?item=' . $post_id) . '" target="_blank">';
        $html .= '<span class="dashicons dashicons-edit-page icon-edit"></span>';
        $html .= '</a>';
        $html .= '</div>';
        return $html;
    }

    public function fn_iat_get_attached_post($post_id = '', $type = '')
    {
        if (empty($post_id) || !is_numeric($post_id)) {
            return '-----';
        }

        $post_parent_id = get_post_field('post_parent', $post_id);

        if ($post_parent_id > 0) {
            $post = get_post($post_parent_id);
        }

        if (isset($post) && $post) {
            $post_url = esc_url(admin_url('post.php?post=' . $post->ID . '&action=edit'));
            $post_title = esc_html($post->post_title);
            $html = '<div class="iat-attached-to" id="iat-attached-to-' . $post_id . '">';
            $html .= '<div class="iat-attached-to-href" id="iat-attached-to-href-' . $post_id . '">';
            $html .= '<a href="' . $post_url . '" target="_blank" data-post-title="' . $post_title . '" id="iat-attached-post-' . $post_id . '">' . $post_title . '</a>';
            $html .= '</div>';
            $html .= '<div class="iat-copy-attached-post-title-to-alt-text" id="iat-copy-attached-post-title-to-alt-text-' . $post_id . '">';
            $html .= '<p class="mt-1" data-post-title="' . $post_title . '" data-type="' . $type . '" onclick="fnIatCopyAttachedPostTitleToAltText(this, ' . $post_id . ')">';
            $html .= '<i class="loader-1 me-1" id="iat-copy-attached-post-title-loader-' . $post_id . '" style="display:none;"></i>';
            $html .= __('Copy page/post title to alt text', IMAGE_ALT_TEXT);
            $html .= '</p>';
            $html .= '</div>';
            $html .= '<div id="iat-copy-attached-post-title-to-alt-text-display-msg-' . $post_id . '" style="display:none;">';
            $html .= __('Alt text: ', IMAGE_ALT_TEXT) . '<b style="color:green;font-weight:600;"></b>';
            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }
        return '-----';
    }

    public function fn_iat_count_total_attachment_record($type = '')
    {
        if ($type == 'with-alt') {
            return $this->fn_iat_with_alt_media_count();
        } else if ($type == 'without-alt') {
            return $this->fn_iat_without_alt_media_count();
        }
    }

    public function fn_iat_add_alt_text()
    {
        if (!current_user_can('manage_options')) {
            return false;
        }

        if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'iat_image_alt_text')) {
            wp_die(esc_html(__('Security check. Hacking not allowed', IMAGE_ALT_TEXT)));
        }

        $output = [
            'flg' => 0,
            'message' => esc_html(__('Please enter alt text to update.', IMAGE_ALT_TEXT)),
        ];

        $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
        $alt_text = isset($_POST['alt_text']) ? sanitize_text_field(stripslashes($_POST['alt_text'])) : '';

        if ($alt_text) {
            if (update_post_meta($post_id, '_wp_attachment_image_alt', trim($alt_text))) {
                $flg = 1;
                $message = esc_html(__('Alt text added.', IMAGE_ALT_TEXT));
            } else {
                $flg = 0;
                $message = esc_html(__('Something is wrong to add alt text', IMAGE_ALT_TEXT));
            }
            $output = [
                'flg' => $flg,
                'message' => $message,
                'total' => $this->fn_iat_without_alt_media_count()
            ];
        }

        echo wp_json_encode($output);
        wp_die();
    }


    public function fn_iat_copy_post_title_to_alt_text()
    {
        if (!current_user_can('manage_options')) {
            return false;
        }

        $nonce = sanitize_text_field($_POST['nonce']);
        if (!wp_verify_nonce($nonce, 'iat_image_alt_text')) {
            wp_die(esc_html(__('Security check. Hacking not allowed', IMAGE_ALT_TEXT)));
        }

        $output = [
            'flg' => 0,
            'message' => esc_html(__('Something is wrong with the copied text.', IMAGE_ALT_TEXT)),
        ];

        $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
        $title_to_alt_text = isset($_POST['title_to_alt_text']) ? sanitize_text_field(stripslashes($_POST['title_to_alt_text'])) : '';
        $current_alt_text = stripslashes(get_post_meta($post_id, '_wp_attachment_image_alt', true));

        if ($title_to_alt_text) {
            if ($current_alt_text === $title_to_alt_text) {
                $flg = 0;
                $message = esc_html(__('The alt text is the same as the copied text.', IMAGE_ALT_TEXT));
            } else {
                if (update_post_meta($post_id, '_wp_attachment_image_alt', trim($title_to_alt_text))) {
                    $flg = 1;
                    $message = esc_html(__('Alt text updated.', IMAGE_ALT_TEXT));
                } else {
                    $flg = 0;
                    $message = esc_html(__('Failed to update alt text.', IMAGE_ALT_TEXT));
                }
            }
            $output = [
                'flg' => $flg,
                'message' => $message,
                'total' => $this->fn_iat_without_alt_media_count()
            ];
        }
        echo wp_json_encode($output);
        wp_die();
    }


    public function fn_iat_copy_bulk_post_title_to_alt_text()
    {
        if (!current_user_can('manage_options')) {
            return false;
        }

        if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'iat_copy_bulk_post_title_to_alt_text')) {
            die(esc_html(__('Security check. Hacking not allowed', IMAGE_ALT_TEXT)));
        }

        $page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : '';
        $post_type = 'attachment';
        $post_mime_type = 'image';
        $ajax_call = (int) sanitize_text_field($_POST['ajax_call']);
        if ($ajax_call == 0) {
            $posts_count = (int) $this->conn->get_var(
                $this->conn->prepare(
                    "SELECT COUNT(ID) FROM $this->wp_posts WHERE post_type = %s AND post_mime_type LIKE %s",
                    "$post_type",
                    "%$post_mime_type%"
                )
            );
            if ($posts_count <= 0) {
                echo json_encode(['flg' => 0, 'message' => esc_html(__('There are no attachments found that are missing alt text.', IMAGE_ALT_TEXT))]);
                wp_die();
            } else {
                update_option('iat_alt_text_count', $posts_count);
                $ajax_call++;
                echo json_encode(['flg' => 1, 'ajax_call' => $ajax_call]);
                wp_die();
            }
        }

        $per_post = 100;
        $offset = (int) ($ajax_call - 1) * $per_post;
        $posts_count = (int) get_option('iat_alt_text_count');
        $total_ajax_call = (int) ceil($posts_count / $per_post);

        $post_ids = get_posts([
            'post_type'      => $post_type,
            'post_mime_type' => $post_mime_type,
            'posts_per_page' => $per_post,
            'offset'         => $offset,
            'fields'         => 'ids',
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ]);

        if (!empty($post_ids)) {
            foreach ($post_ids as $post_id) {
                $post_title = $this->fn_iat_convert_html_entities(get_the_title($post_id));
                if ($post_title) {
                    $post_alt = get_post_meta($post_id, '_wp_attachment_image_alt', true);
                    if (($page === 'without-alt' && $post_alt === '') || ($page === 'with-alt' && $post_alt)) {
                        update_post_meta($post_id, '_wp_attachment_image_alt', $post_title);
                    }
                }
            }
        }

        if ($ajax_call == $total_ajax_call) {
            $flg = 2;
            delete_option('iat_alt_text_count');
        } else {
            $ajax_call++;
            $flg = 1;
        }

        $output = [
            'flg' => $flg,
            'ajax_call' => $ajax_call,
            'total_ajax_call' => $total_ajax_call
        ];
        echo json_encode($output);
        wp_die();
    }

    public function fn_iat_copy_bulk_attached_post_title_to_alt_text()
    {

        if (!current_user_can('manage_options')) {
            return false;
        }

        if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'iat_copy_bulk_attached_post_title_to_alt_text')) {
            die(esc_html(__('Security check. Hacking not allowed', IMAGE_ALT_TEXT)));
        }

        $page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : '';
        $post_type = 'attachment';
        $post_mime_type = 'image';
        $ajax_call = (int) sanitize_text_field($_POST['ajax_call']);
        if ($ajax_call == 0) {
            $posts_count = (int) $this->conn->get_var(
                $this->conn->prepare(
                    "SELECT COUNT(ID) FROM $this->wp_posts WHERE post_type = %s AND post_mime_type LIKE %s",
                    "$post_type",
                    "%$post_mime_type%"
                )
            );
            if ($posts_count <= 0) {
                echo json_encode(['flg' => 0, 'message' => esc_html(__('There are no attachments found that are missing alt text.', IMAGE_ALT_TEXT))]);
                wp_die();
            } else {
                update_option('iat_alt_text_count', $posts_count);
                $ajax_call++;
                echo json_encode(['flg' => 1, 'ajax_call' => $ajax_call]);
                wp_die();
            }
        }

        $per_post = 100;
        $offset = (int) ($ajax_call - 1) * $per_post;
        $posts_count = (int) get_option('iat_alt_text_count');
        $total_ajax_call = (int) ceil($posts_count / $per_post);

        $post_ids = get_posts([
            'post_type'      => $post_type,
            'post_mime_type' => $post_mime_type,
            'posts_per_page' => $per_post,
            'offset'         => $offset,
            'fields'         => 'ids',
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ]);

        if (!empty($post_ids)) {
            foreach ($post_ids as $post_id) {
                $post_parent_id = get_post_field('post_parent', $post_id);
                if ($post_parent_id > 0) {
                    $post = get_post($post_parent_id);
                    if (isset($post) && $post) {
                        $post_title = $this->fn_iat_convert_html_entities($post->post_title);
                        if ($post_title) {
                            $post_alt = get_post_meta($post_id, '_wp_attachment_image_alt', true);
                            if (($page === 'without-alt' && $post_alt === '') || ($page === 'with-alt' && $post_alt)) {
                                update_post_meta($post_id, '_wp_attachment_image_alt', $post_title);
                            }
                        }
                    }
                }
            }
        }

        if ($ajax_call == $total_ajax_call) {
            $flg = 2;
            delete_option('iat_alt_text_count');
        } else {
            $ajax_call++;
            $flg = 1;
        }

        $output = [
            'flg' => $flg,
            'ajax_call' => $ajax_call,
            'total_ajax_call' => $total_ajax_call
        ];
        echo wp_json_encode($output);
        wp_die();
    }

    public function fn_iat_update_existing_alt_text()
    {
        if (!current_user_can('manage_options')) {
            return false;
        }

        if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'iat_image_alt_text')) {
            wp_die(esc_html(__('Security check. Hacking not allowed', IMAGE_ALT_TEXT)));
        }

        $output = [
            'flg' => 0,
            'message' => esc_html(__('Please enter alt text to update.', IMAGE_ALT_TEXT))
        ];

        $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
        $ex_alt_text = isset($_POST['ex_alt_text']) ? sanitize_text_field(stripslashes($_POST['ex_alt_text'])) : '';
        $current_alt_text = stripslashes(get_post_meta($post_id, '_wp_attachment_image_alt', true));

        if ($ex_alt_text) {
            if ($current_alt_text === $ex_alt_text) {
                $flg = 0;
                $message = esc_html(__('Alt text entered is the same as the previous one. Please add new alt text.', IMAGE_ALT_TEXT));
            } else {
                if (update_post_meta($post_id, '_wp_attachment_image_alt', trim($ex_alt_text))) {
                    $flg = 1;
                    $message = esc_html(__('Alt text updated.', IMAGE_ALT_TEXT));
                } else {
                    $flg = 0;
                    $message = esc_html(__('Failed to update alt text.', IMAGE_ALT_TEXT));
                }
            }
            $output = [
                'flg' => $flg,
                'message' => $message
            ];
        }

        echo wp_json_encode($output);
        wp_die();
    }


    public function fn_iat_without_alt_media_count()
    {
        return $this->conn->get_var("
				SELECT COUNT(*)
				FROM $this->wp_posts p
				LEFT JOIN $this->wp_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_wp_attachment_image_alt'
				WHERE p.post_type = 'attachment'
				AND p.post_mime_type LIKE 'image/%'
				AND (pm.meta_value IS NULL OR pm.meta_value = '' OR pm.meta_key IS NULL)
			");
    }

    public function fn_iat_with_alt_media_count()
    {

        return $this->conn->get_var("
                SELECT COUNT(*)
                FROM $this->wp_posts p
                JOIN $this->wp_postmeta pm ON p.ID = pm.post_id
                WHERE p.post_type = 'attachment'
                AND p.post_mime_type LIKE 'image/%'
                AND pm.meta_key = '_wp_attachment_image_alt'
                AND pm.meta_value != ''
            ");
    }

    public function fn_iat_copy_attached_post_title_to_alt_text()
    {

        if (!current_user_can('manage_options')) {
            return false;
        }

        $nonce = sanitize_text_field($_POST['nonce']);
        if (!wp_verify_nonce($nonce, 'iat_image_alt_text')) {
            wp_die(esc_html(__('Security check. Hacking not allowed', IMAGE_ALT_TEXT)));
        }

        $output = [
            'flg' => 0,
            'message' => esc_html(__('Something is wrong with the copied text.', IMAGE_ALT_TEXT)),
        ];

        $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
        $post_title_to_alt_text = isset($_POST['post_title_to_alt_text']) ? sanitize_text_field(stripslashes($_POST['post_title_to_alt_text'])) : '';
        $current_alt_text = stripslashes(get_post_meta($post_id, '_wp_attachment_image_alt', true));

        if ($post_title_to_alt_text) {
            if ($current_alt_text === $post_title_to_alt_text) {
                $flg = 0;
                $message = esc_html(__('The alt text is the same as the copied text.', IMAGE_ALT_TEXT));
            } else {
                if (update_post_meta($post_id, '_wp_attachment_image_alt', trim($post_title_to_alt_text))) {
                    $flg = 1;
                    $message = esc_html(__('Alt text updated.', IMAGE_ALT_TEXT));
                } else {
                    $flg = 0;
                    $message = esc_html(__('Failed to update alt text.', IMAGE_ALT_TEXT));
                }
            }
            $output = [
                'flg' => $flg,
                'message' => $message,
                'total' => $this->fn_iat_without_alt_media_count()
            ];
        }
        echo json_encode($output);
        wp_die();
    }

    public function fn_iat_convert_html_entities($string = '')
    {
        $entities = [
            '&#38;'   => '&',      // Ampersand
            '&#60;'   => '<',      // Less than
            '&#62;'   => '>',      // Greater than
            '&#34;'   => '"',      // Double quote
            '&#39;'   => "'",      // Apostrophe / Single quote
            '&#160;'  => ' ',      // Non-breaking space
            '&#8217;' => "'",      // Right single quotation mark (')
            '&#8211;' => '-',      // En dash
            '&#8212;' => '—',      // Em dash
            '&#8230;' => '…',      // Ellipsis
            '&#169;'  => '©',      // Copyright symbol
            '&#174;'  => '®',      // Registered trademark symbol
            '&#8482;' => '™',      // Trademark symbol
            '&#8364;' => '€',      // Euro symbol
            '&#163;'  => '£',      // Pound sterling symbol
            '&#165;'  => '¥',      // Yen symbol
            '&#177;'  => '±',      // Plus-minus sign
            '&#247;'  => '÷',      // Division sign
            '&#8804;' => '≤',      // Less than or equal to
            '&#8805;' => '≥',      // Greater than or equal to
            '&#8220;' => '“',      // Left double quotation mark
            '&#8221;' => '”',      // Right double quotation mark
            '&#8216;' => '‘',      // Left single quotation mark
            '&#8218;' => '‚',      // Single low quotation mark
            '&#8239;' => ' ',      // Thin space
            '&#37;'   => '%',      // Percent sign
            '&#40;'   => '(',      // Left parenthesis
            '&#41;'   => ')',      // Right parenthesis
            '&#172;'  => '¦',      // Broken bar
            '&#8260;' => '/',      // Fraction slash
        ];

        return str_replace(array_keys($entities), array_values($entities), $string);
    }
}

new class_iat();
