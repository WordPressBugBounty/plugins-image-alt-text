<?php

if (! defined('ABSPATH')) exit;

class iat_Row_Action
{
    public function __construct()
    {
        // Copy title to alt text - row action
        add_action('wp_ajax_iat_copy_title_to_alt_text', array($this, 'iat_copy_title_to_alt_text'));
        // Copy filename to alt text - row action
        add_action('wp_ajax_iat_copy_filename_to_alt_text', array($this, 'iat_copy_filename_to_alt_text'));
        // Save alt text - row action
        add_action('wp_ajax_iat_save_alt_text', array($this, 'iat_save_alt_text'));
        // Update alt text - enhanced version for smart editor
        add_action('wp_ajax_iat_update_alt_text', array($this, 'iat_update_alt_text'));
    }


    /**
     * AJAX handler for copying title to alt text
     */
    public function iat_copy_title_to_alt_text()
    {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'image_alt_text_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'image-alt-text')));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Unauthorized.', 'image-alt-text')));
        }

        // Get parameters
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        $alt_text = isset($_POST['alt_text']) ? sanitize_text_field(wp_unslash($_POST['alt_text'])) : '';

        // Clean the title into readable alt text (strips -/_ separators, fixes casing).
        $alt_text = iat_clean_title_for_alt($alt_text);

        // Validate post ID
        if ($post_id <= 0) {
            wp_send_json_error(array('message' => __('Invalid image ID.', 'image-alt-text')));
        }

        // Check if post exists and is an attachment
        if (get_post_type($post_id) !== 'attachment') {
            wp_send_json_error(array(
                'message' => __('Image not found.', 'image-alt-text')
            ));
        }


        // Check if alt text is already the same
        $current_alt = get_post_meta($post_id, '_wp_attachment_image_alt', true);
        if ($current_alt === $alt_text) {
            wp_send_json_success(array(
                'message' => __('Alt text already same.', 'image-alt-text'),
                'alt_text' => $current_alt,
                'already_same' => true
            ));
        } else {
            // Update alt text
            update_post_meta($post_id, '_wp_attachment_image_alt', $alt_text);
            wp_send_json_success(array(
                'message' => __('Alt text updated from title.', 'image-alt-text'),
                'alt_text' => $alt_text,
                'already_same' => false
            ));
        }
    }

    /**
     * AJAX handler for copying filename (without extension) to alt text
     */
    public function iat_copy_filename_to_alt_text()
    {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'image_alt_text_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'image-alt-text')));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Unauthorized.', 'image-alt-text')));
        }

        // Get parameters
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;

        // Validate post ID
        if ($post_id <= 0) {
            wp_send_json_error(array('message' => __('Invalid image ID.', 'image-alt-text')));
        }

        // Check if post exists and is an attachment
        if (get_post_type($post_id) !== 'attachment') {
            wp_send_json_error(array(
                'message' => __('Image not found.', 'image-alt-text')
            ));
        }

        // Extract filename (without extension) and use as alt text
        $url = wp_get_attachment_url($post_id);
        if ($url) {
            $filename = basename($url);
            $filename_no_ext = pathinfo($filename, PATHINFO_FILENAME);
            // Clean the raw filename into readable alt text (strips -scaled, dimensions, separators).
            $filename_no_ext = sanitize_text_field(iat_clean_filename_for_alt($filename_no_ext));
            $current_alt = get_post_meta($post_id, '_wp_attachment_image_alt', true);
            if ($current_alt === $filename_no_ext) {
                wp_send_json_success(array(
                    'message' => __('Alt text already same.', 'image-alt-text'),
                    'alt_text' => $current_alt,
                    'already_same' => true
                ));
            } else {
                update_post_meta($post_id, '_wp_attachment_image_alt', $filename_no_ext);
                wp_send_json_success(array(
                    'message' => __('Alt text updated from filename.', 'image-alt-text'),
                    'alt_text' => $filename_no_ext,
                    'already_same' => false
                ));
            }
        } else {
            wp_send_json_error(array('message' => __('Image URL not found.', 'image-alt-text')));
        }
    }

    /**
     * AJAX handler for saving alt text
     */
    public function iat_save_alt_text()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'image_alt_text_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'image-alt-text')));
        }
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Unauthorized.', 'image-alt-text')));
        }
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        $alt_text = isset($_POST['alt_text']) ? sanitize_text_field(wp_unslash($_POST['alt_text'])) : '';
        if ($post_id <= 0) {
            wp_send_json_error(array('message' => __('Invalid image ID.', 'image-alt-text')));
        }
        // Only allow writing alt text to image attachments (consistency with the other handlers).
        if (get_post_type($post_id) !== 'attachment') {
            wp_send_json_error(array('message' => __('Image not found.', 'image-alt-text')));
        }
        update_post_meta($post_id, '_wp_attachment_image_alt', $alt_text);
        wp_send_json_success(array('message' => __('Alt text saved.', 'image-alt-text'), 'alt_text' => $alt_text));
    }

    /**
     * Enhanced AJAX handler for updating alt text with duplicate checking
     */
    public function iat_update_alt_text()
    {
        // Security: Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'image_alt_text_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'image-alt-text')));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Unauthorized', 'image-alt-text')));
        }

        // Get the post ID and alt text from the request
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        $alt_text = isset($_POST['alt_text']) ? sanitize_text_field(wp_unslash($_POST['alt_text'])) : '';

        // Check if post exists and is an attachment
        if (get_post_type($post_id) !== 'attachment') {
            wp_send_json_error(array('message' => __('Invalid image ID', 'image-alt-text')));
        }

        // Check if the same alt text already exists
        $current_alt_text = get_post_meta($post_id, '_wp_attachment_image_alt', true);
        if ($current_alt_text === $alt_text) {
            wp_send_json_error(array('message' => __('Same alt text already there', 'image-alt-text'), 'type' => 'duplicate'));
        }

        // Update the alt text in the database
        $result = update_post_meta($post_id, '_wp_attachment_image_alt', $alt_text);

        if ($result !== false) {
            wp_send_json_success(array(
                'message' => __('Alt text updated successfully', 'image-alt-text')
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to update alt text', 'image-alt-text')));
        }
    }
}

new iat_Row_Action();
