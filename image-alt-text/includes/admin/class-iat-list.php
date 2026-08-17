<?php

if (!defined('ABSPATH')) {
    exit;
}

class Iat_List
{
    public function __construct()
    {
        add_action('wp_ajax_iat_get_list', array($this, 'iat_get_list'));
    }

    public function iat_get_list()
    {
        // Security: Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'image_alt_text_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'image-alt-text')));
            wp_die();
        }

        // Check user capabilities
        if (! current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Unauthorized.', 'image-alt-text')));
            wp_die();
        }

        $display_list = [];
        $post_type = 'attachment';
        $post_mime_type = 'image';
        $type = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
        $length = isset($_POST['length']) ? absint($_POST['length']) : 10;
        $start = isset($_POST['start']) ? absint($_POST['start']) : 0;
        $draw = isset($_POST['draw']) ? absint($_POST['draw']) : 0;
        $search_value = isset($_POST['search']['value']) ? sanitize_text_field(wp_unslash($_POST['search']['value'])) : '';

        // Sorting: map the requested DataTables column to a safe WP_Query orderby.
        // Only columns in this allowlist can be sorted; anything else falls back to date.
        $sortable_columns = array(
            'title' => 'title',
            'date'  => 'date',
        );

        $orderby = 'date';
        $order   = 'DESC';

        if (isset($_POST['order'][0]['column']) && is_scalar($_POST['order'][0]['column'])) {
            $order_column_index = absint($_POST['order'][0]['column']);
            $requested_column   = isset($_POST['columns'][$order_column_index]['data']) && is_scalar($_POST['columns'][$order_column_index]['data'])
                ? sanitize_key(wp_unslash($_POST['columns'][$order_column_index]['data']))
                : '';

            if (isset($sortable_columns[$requested_column])) {
                $orderby = $sortable_columns[$requested_column];

                $requested_dir = isset($_POST['order'][0]['dir']) && is_scalar($_POST['order'][0]['dir'])
                    ? strtoupper(sanitize_text_field(wp_unslash($_POST['order'][0]['dir'])))
                    : '';
                $order = ($requested_dir === 'ASC') ? 'ASC' : 'DESC';
            }
        }

        if ($type === 'iat-with-alt-text') {
            $meta_query = array(
                array(
                    'key'     => '_wp_attachment_image_alt',
                    'value'   => '',
                    'compare' => '!=',
                ),
            );
        } elseif ($type === 'iat-without-alt-text') {
            $meta_query = array(
                'relation' => 'OR',
                array(
                    'key' => '_wp_attachment_image_alt',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_wp_attachment_image_alt',
                    'value' => '',
                    'compare' => '='
                )
            );
        } else {
            wp_send_json_error(array('message' => __('Invalid request type.', 'image-alt-text')));
            wp_die();
        }

        $query_args = array(
            'post_type'      => $post_type,
            'post_mime_type' => $post_mime_type,
            'posts_per_page' => $length,
            'offset'         => $start,
            'meta_query'     => $meta_query,
            's'              => $search_value,
            'orderby'        => $orderby,
            'order'          => $order,
        );

        $posts = get_posts($query_args);

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $post_id = $post->ID;
                $post_title = (string)$post->post_title;
                $alt_text = (string)get_post_meta($post_id, '_wp_attachment_image_alt', true);
                $display_list[] = [
                    'checkbox' => $this->fn_iat_bulk_checkbox($post_id),
                    'image' => $this->fn_iat_image($post_id),
                    'title' => $this->fn_iat_title($post_id, $post_title),
                    'url' => $this->fn_iat_url($post_id),
                    'size' => $this->fn_iat_size($post_id),
                    'alt_text' => $this->fn_iat_alt_text($post_id, $alt_text),
                    'date' => $this->fn_iat_date($post_id),
                    'action' => $this->fn_iat_action($post_id)
                ];
            }
        }

        // Get total records for pagination
        $total_records = $this->fn_iat_total_record('', $type);

        // Get filtered records count
        if ($search_value) {
            $records_filtered = $this->fn_iat_total_record($search_value, $type);
        } else {
            $records_filtered = $total_records;
        }

        $response_data = array(
            'draw' => $draw,
            'recordsTotal' => $total_records,
            'recordsFiltered' => $records_filtered,
            'data' => $display_list
        );

        wp_send_json($response_data);
        wp_die();
    }

    private function fn_iat_bulk_checkbox($post_id)
    {
        $html = '<div class="iat-bulk-checkbox" id="iat-bulk-checkbox-' . esc_attr($post_id) . '">';
        $html .= '<input type="checkbox" class="iat-bulk-select" data-post-id="' . esc_attr($post_id) . '" id="iat-bulk-select-' . esc_attr($post_id) . '" value="' . esc_attr($post_id) . '">';
        $html .= '</div>';
        return $html;
    }

    private function fn_iat_image($post_id)
    {
        $src = wp_get_attachment_image_src($post_id, 'thumbnail');
        $thumbnail_url = $src ? (string)$src[0] : (string)wp_get_attachment_url($post_id);
        $full_image_url = (string)wp_get_attachment_url($post_id);

        $html = '<div class="iat-image" id="iat-image-' . esc_attr($post_id) . '">';
        $html .= '<a href="' . esc_url($full_image_url) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Open full image in new tab', 'image-alt-text') . '">';
        $html .= '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr__('Thumbnail', 'image-alt-text') . '" class="img-thumbnail" />';
        $html .= '</a>';
        $html .= '</div>';
        return $html;
    }

    private function fn_iat_title($post_id, $post_title)
    {
        $html = '<div class="iat-title" id="iat-title-' . esc_attr($post_id) . '">';
        if (empty(trim($post_title))) {
            $html .= '<div class="iat-title-text">-</div>';
        } else {
            $html .= '<div class="iat-title-text">' . esc_html(trim($post_title)) . '</div>';
            $html .= '<div class="iat-copy-title-action">';
            $html .= '<button type="button" class="iat-copy-title-to-alt" data-post_id="' . esc_attr($post_id) . '" data-title="' . esc_attr(trim($post_title)) . '" title="' . esc_attr__('Copy title to alt text', 'image-alt-text') . '">';
            $html .= '<span class="dashicons dashicons-admin-page iat-copy-icon"></span>';
            $html .= '<span class="iat-copy-text">' . esc_html__('Copy Title to Alt', 'image-alt-text') . '</span>';
            $html .= '</button>';
            $html .= '<span class="iat-copy-success">' . esc_html__('✓ Copied!', 'image-alt-text') . '</span>';
            $html .= '<span class="iat-copy-duplicate">' . esc_html__('Already same.', 'image-alt-text') . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }

    private function fn_iat_url($post_id)
    {
        $url = (string)wp_get_attachment_url($post_id);
        $filename = basename($url);
        $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);
        $html = '<div class="iat-url" id="iat-url-' . esc_attr($post_id) . '">';
        $html .= '<a href="#" class="iat-copy-url" data-url="' . esc_attr($url) . '" title="' . esc_attr__('Click to copy URL', 'image-alt-text') . '">' . esc_url(($url)) . '</a>';
        $html .= '<span class="iat-url-copied">' . esc_html__('URL copied!', 'image-alt-text') . '</span>';
        if (!empty(trim($filename_without_ext))) {
            $html .= '<div class="iat-copy-url-action">';
            $html .= '<button type="button" class="iat-copy-filename-to-alt" data-post_id="' . esc_attr($post_id) . '" data-filename="' . esc_attr($filename_without_ext) . '" title="' . esc_attr__('Copy filename to alt text', 'image-alt-text') . '">';
            $html .= '<span class="dashicons dashicons-admin-page iat-copy-icon"></span>';
            $html .= '<span class="iat-copy-text">' . esc_html__('Copy Filename to Alt', 'image-alt-text') . '</span>';
            $html .= '</button>';
            $html .= '<span class="iat-update-success">' . esc_html__('✓ Added!', 'image-alt-text') . '</span>';
            $html .= '<span class="iat-update-duplicate">' . esc_html__('Already same.', 'image-alt-text') . '</span>';
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function fn_iat_size($post_id)
    {
        $file_path = get_attached_file($post_id);
        $file_size = 0;
        if ($file_path) {
            $file_size = filesize($file_path);
        }
        $formatted_size = size_format($file_size);

        $html = '<div class="iat-size" id="iat-size-' . esc_attr($post_id) . '">';
        $html .= '<span title="' . esc_attr($file_size . ' bytes') . '">' . esc_html($formatted_size) . '</span>';
        $html .= '</div>';
        return $html;
    }

    private function fn_iat_alt_text($post_id, $alt_text)
    {
        $html = '<div class="iat-alt-text" id="iat-alt-text-' . esc_attr($post_id) . '">';

        // Advanced Smart Editor (Works for both with and without alt text)
        $html .= $this->fn_iat_smart_alt_text_editor($post_id, $alt_text);

        $html .= '</div>'; // .iat-alt-text
        return $html;
    }

    /**
     * Generate HTML for the smart alt text editor interface
     * Works for both images with existing alt text and images without alt text
     */
    private function fn_iat_smart_alt_text_editor($post_id, $alt_text)
    {
        $html = '';
        $html .= '<div class="iat-smart-alt-editor-wrapper" id="iat-smart-alt-editor-wrapper-' . esc_attr($post_id) . '">';

        // Smart input field with integrated functionality (no border wrapper)
        $placeholder = !empty($alt_text) ? __('Edit alt text...', 'image-alt-text') : __('Enter alt text for this image...', 'image-alt-text');
        $html .= '<div class="iat-input-container">';
        $html .= '<input type="text" class="iat-smart-alt-input" value="' . esc_attr($alt_text) . '" ';
        $html .= 'data-post_id="' . esc_attr($post_id) . '" ';
        $html .= 'data-original="' . esc_attr($alt_text) . '" ';
        $html .= 'placeholder="' . esc_attr($placeholder) . '" />';

        // Status indicator and action buttons in horizontal line
        $html .= '<div class="iat-input-actions">';

        // Save button - always visible but disabled when empty
        $html .= '<button type="button" class="iat-save-btn iat-btn-small" data-post_id="' . esc_attr($post_id) . '" disabled title="' . esc_attr__('💾 Save Alt Text to Database', 'image-alt-text') . '">';
        $html .= '<span class="dashicons dashicons-yes-alt"></span>';
        $html .= '<span class="iat-btn-label">' . esc_html__('Save', 'image-alt-text') . '</span>';
        $html .= '</button>';

        // Reset button - smaller
        $html .= '<button type="button" class="iat-reset-btn iat-btn-small" data-post_id="' . esc_attr($post_id) . '" style="display: none;" title="' . esc_attr__('↶ Reset to Original Text', 'image-alt-text') . '">';
        $html .= '<span class="dashicons dashicons-undo"></span>';
        $html .= '<span class="iat-btn-label">' . esc_html__('Reset', 'image-alt-text') . '</span>';
        $html .= '</button>';

        $html .= '</div>';
        $html .= '</div>';

        // Status messages (minimal and contextual)
        $html .= '<div class="iat-status-line">';
        $html .= '<span class="iat-status-text"></span>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    private function fn_iat_date($post_id)
    {
        $post_date = get_post_time('U', false, $post_id) ?: 0;
        $formatted_date = get_the_date('M d, Y', $post_id);
        $human_time_diff = human_time_diff($post_date, time());

        $html = '<div class="iat-date" id="iat-date-' . esc_attr($post_id) . '">';
        $html .= '<span title="' . esc_attr($formatted_date) . '">' . esc_html($formatted_date) . '</span>';
        /* translators: %s: human-readable time difference, e.g. "5 mins" */
        $html .= '<div class="iat-date-ago">' . sprintf(esc_html__('(%s ago)', 'image-alt-text'), esc_html($human_time_diff)) . '</div>';
        $html .= '</div>';
        return $html;
    }

    private function fn_iat_action($post_id)
    {
        $edit_url = admin_url('post.php?post=' . $post_id . '&action=edit');
        $html = '<div class="iat-action" id="iat-action-' . esc_attr($post_id) . '">';
        $html .= '<div class="iat-action-buttons">';
        $html .= '<a href="' . esc_url($edit_url) . '" class="iat-row-btn iat-edit-btn" title="' . esc_attr__('Edit in Media Library', 'image-alt-text') . '" target="_blank">';
        $html .= '<i class="fas fa-edit"></i>';
        $html .= '</a>';

        // Optional: Add a view button that opens the full image in a new tab
        $image_url = wp_get_attachment_url($post_id);
        $html .= '<a href="' . esc_url($image_url) . '" class="iat-row-btn iat-view-btn" title="' . esc_attr__('View Full Image', 'image-alt-text') . '" target="_blank">';
        $html .= '<i class="fas fa-eye"></i>';
        $html .= '</a>';

        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    private function fn_iat_total_record($search_value = '', $type = '')
    {
        // Get all image attachments
        $args = [
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ];

        if (!empty($search_value)) {
            $args['s'] = $search_value;
        }

        if ($type === 'iat-with-alt-text') {
            $args['meta_query'] = [
                [
                    'key'     => '_wp_attachment_image_alt',
                    'value'   => '',
                    'compare' => '!=',
                ],
            ];
        } elseif ($type === 'iat-without-alt-text') {
            $args['meta_query'] = [
                'relation' => 'OR',
                [
                    'key' => '_wp_attachment_image_alt',
                    'compare' => 'NOT EXISTS'
                ],
                [
                    'key' => '_wp_attachment_image_alt',
                    'value' => '',
                    'compare' => '='
                ]
            ];
        }

        $all_posts = get_posts($args);
        return count($all_posts);
    }
}

new Iat_List();
