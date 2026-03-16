<?php

if (!defined('ABSPATH')) {
    exit;
}

class Iat_Bulk_Action
{
    private $action_types = array('copy_title', 'copy_filename');

    public function __construct()
    {
        // Register AJAX handlers for bulk actions
        add_action('wp_ajax_iat_bulk_process_action', array($this, 'iat_bulk_process_action'));
    }

    /**
     * AJAX handler for bulk processing actions
     */
    public function iat_bulk_process_action()
    {
        // Security: Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'image_alt_text_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed. Please refresh the page and try again.', 'image-alt-text')
            ));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => __('You do not have permission to perform this action.', 'image-alt-text')
            ));
        }

        // Get and validate parameters
        $action_type = isset($_POST['action_type']) ? sanitize_text_field(wp_unslash($_POST['action_type'])) : '';
        $post_ids = isset($_POST['post_ids']) ? array_map('absint', wp_unslash($_POST['post_ids'])) : array();

        // Validate inputs
        if (empty($action_type) || !in_array($action_type, $this->action_types)) {
            wp_send_json_error(array(
                'message' => __('Invalid action type selected.', 'image-alt-text')
            ));
        }

        if (empty($post_ids) || !is_array($post_ids)) {
            wp_send_json_error(array(
                'message' => __('No images selected for processing.', 'image-alt-text')
            ));
        }

        // Validate that all post IDs are valid image attachments (sanitize and check type)
        $valid_post_ids = array();
        foreach ($post_ids as $post_id) {
            $pid = (int) $post_id;
            $post_type = get_post_type($pid);
            if ($post_type === 'attachment' && wp_attachment_is_image($pid)) {
                $valid_post_ids[] = $pid;
            }
        }

        if (empty($valid_post_ids)) {
            wp_send_json_error(array(
                'message' => __('No valid image attachments found in your selection.', 'image-alt-text')
            ));
        }

        // Process based on action type
        $results = array();
        switch ($action_type) {
            case 'copy_title':
                $results = $this->process_copy_title($valid_post_ids);
                break;
            case 'copy_filename':
                $results = $this->process_copy_filename($valid_post_ids);
                break;
        }

        // Send response
        wp_send_json_success($results);
    }

    /**
     * Process copy title to alt text for multiple images
     */
    private function process_copy_title($post_ids)
    {
        $total_checked = count($post_ids);
        $updated_count = 0;
        $skipped_count = 0;
        $already_same_count = 0;
        $no_title_count = 0;
        $failed_count = 0;

        $detailed_results = array();

        foreach ($post_ids as $post_id) {
            // Only fetch the title field, not the full post object
            $title = (string)get_post_field('post_title', $post_id);
            $image_title = !empty($title) ? $title : 'Untitled Image #' . $post_id;

            // Check if no title available
            if (empty(trim($title))) {
                $no_title_count++;
                $skipped_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'skipped',
                    'reason' => 'No title available',
                    'action_taken' => 'none'
                );
                continue;
            }

            // Check if alt text already matches title
            $current_alt = get_post_meta($post_id, '_wp_attachment_image_alt', true);
            if ($current_alt === $title) {
                $already_same_count++;
                $skipped_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'already_same',
                    'reason' => 'Alt text already matches title',
                    'current_alt' => $current_alt,
                    'action_taken' => 'none'
                );
                continue;
            }

            // Update alt text
            $result = update_post_meta($post_id, '_wp_attachment_image_alt', $title);
            if ($result !== false) {
                $updated_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'updated',
                    'reason' => 'Title copied to alt text successfully',
                    'old_alt' => $current_alt,
                    'new_alt' => $title,
                    'action_taken' => 'updated'
                );
            } else {
                $failed_count++;
                $skipped_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'failed',
                    'reason' => 'Database update failed',
                    'action_taken' => 'none'
                );
            }
        }


        // Create summary message
        $summary_parts = array();
        if ($updated_count > 0) {
            $summary_parts[] = $updated_count . ' updated';
        }
        if ($already_same_count > 0) {
            $summary_parts[] = $already_same_count . ' already same';
        }
        if ($no_title_count > 0) {
            $summary_parts[] = $no_title_count . ' no title';
        }
        if ($failed_count > 0) {
            $summary_parts[] = $failed_count . ' failed';
        }

        $summary_message = 'Results: ' . implode(', ', $summary_parts) . ' out of ' . $total_checked . ' checked images';

        return array(
            'action' => 'copy_title',
            'success' => $updated_count > 0,
            'message' => $updated_count > 0 ? 'Title copy completed!' : 'No images were updated.',
            'summary' => $summary_message,
            'counts' => array(
                'total_checked' => $total_checked,
                'updated' => $updated_count,
                'skipped' => $skipped_count,
                'already_same' => $already_same_count,
                'no_title' => $no_title_count,
                'failed' => $failed_count
            ),
            'detailed_results' => $detailed_results
        );
    }

    /**
     * Process copy filename to alt text for multiple images
     */
    private function process_copy_filename($post_ids)
    {
        $total_checked = count($post_ids);
        $updated_count = 0;
        $skipped_count = 0;
        $already_same_count = 0;
        $invalid_filename_count = 0;
        $no_url_count = 0;
        $failed_count = 0;

        $detailed_results = array();

        foreach ($post_ids as $post_id) {
            // Fetch only the title field, not full post object
            $title = (string)get_post_field('post_title', $post_id);
            $image_title = !empty($title) ? $title : 'Image #' . $post_id;

            // Check if URL is available
            $url = wp_get_attachment_url($post_id);
            if (empty($url)) {
                $no_url_count++;
                $skipped_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'skipped',
                    'reason' => 'Could not get image URL',
                    'action_taken' => 'none'
                );
                continue;
            }

            // Extract filename without extension (no cleaning, keep as is)
            $filename = basename($url);
            $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);

            // Check if filename is meaningful
            if (empty($filename_without_ext) || strlen($filename_without_ext) < 3) {
                $invalid_filename_count++;
                $skipped_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'skipped',
                    'reason' => 'Filename too short or invalid ("' . $filename . '")',
                    'original_filename' => $filename,
                    'action_taken' => 'none'
                );
                continue;
            }

            // Check if alt text already matches filename (as is)
            $current_alt = get_post_meta($post_id, '_wp_attachment_image_alt', true);
            if ($current_alt === $filename_without_ext) {
                $already_same_count++;
                $skipped_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'already_same',
                    'reason' => 'Alt text already matches filename',
                    'current_alt' => $current_alt,
                    'filename' => $filename_without_ext,
                    'action_taken' => 'none'
                );
                continue;
            }

            // Update alt text (save as is)
            $result = update_post_meta($post_id, '_wp_attachment_image_alt', $filename_without_ext);
            if ($result !== false) {
                $updated_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'updated',
                    'reason' => 'Filename copied to alt text successfully',
                    'old_alt' => $current_alt,
                    'new_alt' => $filename_without_ext,
                    'original_filename' => $filename,
                    'action_taken' => 'updated'
                );
            } else {
                $failed_count++;
                $skipped_count++;
                $detailed_results[] = array(
                    'post_id' => $post_id,
                    'image_title' => $image_title,
                    'status' => 'failed',
                    'reason' => 'Database update failed',
                    'intended_alt' => $filename_without_ext,
                    'action_taken' => 'none'
                );
            }
        }


        // Create summary message
        $summary_parts = array();
        if ($updated_count > 0) {
            $summary_parts[] = $updated_count . ' updated';
        }
        if ($already_same_count > 0) {
            $summary_parts[] = $already_same_count . ' already same';
        }
        if ($invalid_filename_count > 0) {
            $summary_parts[] = $invalid_filename_count . ' invalid filename';
        }
        if ($no_url_count > 0) {
            $summary_parts[] = $no_url_count . ' no URL';
        }
        if ($failed_count > 0) {
            $summary_parts[] = $failed_count . ' failed';
        }

        $summary_message = 'Results: ' . implode(', ', $summary_parts) . ' out of ' . $total_checked . ' checked images';

        return array(
            'action' => 'copy_filename',
            'success' => $updated_count > 0,
            'message' => $updated_count > 0 ? 'Filename copy completed!' : 'No images were updated.',
            'summary' => $summary_message,
            'counts' => array(
                'total_checked' => $total_checked,
                'updated' => $updated_count,
                'skipped' => $skipped_count,
                'already_same' => $already_same_count,
                'invalid_filename' => $invalid_filename_count,
                'no_url' => $no_url_count,
                'failed' => $failed_count
            ),
            'detailed_results' => $detailed_results
        );
    }
}

new Iat_Bulk_Action();
