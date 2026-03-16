<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Security: Include header file safely
$header_file = dirname(__FILE__) . '/iat-header-view.php';
if (file_exists($header_file)) {
    include $header_file;
}

// Get current page with proper sanitization
$current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

// Get table title based on page
$table_title = '';
if ($current_page === 'iat-with-alt-text') {
    $table_title = esc_html__('Images With Alt Text', 'image-alt-text');
} elseif ($current_page === 'iat-without-alt-text') {
    $table_title = esc_html__('Images Without Alt Text', 'image-alt-text');
}

?>

<?php do_action('iat_render_review_notice'); ?>

<div class='iat-action-container'>
    <?php
    // Include bulk action view
    $bulk_action_file = dirname(__FILE__) . '/iat-bulk-action-view.php';
    if (file_exists($bulk_action_file)) {
        include $bulk_action_file;
    }
    ?>
</div>

<div class="iat-page-container">
    <div class="iat-datatable-container">
        <table class="iat-datatable table table-striped table-sm responsive" id="<?php echo esc_attr($current_page); ?>-datatable">
            <thead class="table-light">
                <tr>
                    <th class="text-center">
                        <input type="checkbox" id="iat-select-all" class="iat-bulk-select" aria-label="<?php esc_attr_e('Select all', 'image-alt-text'); ?>">
                    </th>
                    <th class="text-center"><?php esc_html_e('Image', 'image-alt-text'); ?></th>
                    <th><?php esc_html_e('Title', 'image-alt-text'); ?></th>
                    <th><?php esc_html_e('URL', 'image-alt-text'); ?></th>
                    <?php if ($current_page === 'iat-with-alt-text'): ?>
                        <th><?php esc_html_e('Update Alt Text', 'image-alt-text'); ?></th>
                    <?php else: ?>
                        <th><?php esc_html_e('Add Alt Text', 'image-alt-text'); ?></th>
                    <?php endif; ?>
                    <th class="text-center"><?php esc_html_e('Size', 'image-alt-text'); ?></th>
                    <th class="text-center"><?php esc_html_e('Date', 'image-alt-text'); ?></th>
                    <th class="text-center"><?php esc_html_e('Action', 'image-alt-text'); ?></th>
                </tr>
            </thead>
        </table>
    </div>
</div>