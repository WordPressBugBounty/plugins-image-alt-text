<?php
// Security check
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Modern Bootstrap Bulk Actions -->
<div class="iat-bulk-action-wrapper">
    <div class="iat-bulk-action-header">
        <h3>
            <?php esc_html_e('Bulk Actions', 'image-alt-text'); ?>
            <div class="iat-info-tooltip">
                <i class="dashicons dashicons-editor-help"></i>
                <div class="tooltip-content">
                    <?php esc_html_e('Bulk Actions allow you to add or update alt text for multiple images at once. Simply select the images you want to process by checking the checkboxes in the table below, choose your preferred action, and click Process. This feature is perfect for handling specific groups of images efficiently.', 'image-alt-text'); ?>
                </div>
            </div>
        </h3>
    </div>

    <div class="iat-bulk-action-content">
        <div class="iat-bulk-action-form">
            <div class="iat-bulk-action-form-row">
                <select id="iat-bulk-action-select" class="iat-bulk-action-dropdown form-select" style="max-width: 100%;">
                    <option value=""><?php esc_html_e('Choose Action...', 'image-alt-text'); ?></option>
                    <option value="copy_title"><?php esc_html_e('Copy Title to Alt Text', 'image-alt-text'); ?></option>
                    <option value="copy_filename"><?php esc_html_e('Copy Filename to Alt Text', 'image-alt-text'); ?></option>
                </select>
                <button type="button" id="iat-bulk-action-process-btn" class="iat-bulk-action-process-btn btn" disabled>
                    <span class="iat-bulk-action-btn-loader" style="display: none;">
                        <span class="dashicons dashicons-update"></span>
                    </span>
                    <span class="iat-bulk-action-btn-text"><?php esc_html_e('Process', 'image-alt-text'); ?></span>
                </button>
                <div class="iat-bulk-action-selection-info">
                    <span id="iat-bulk-action-selection-count">0</span>&nbsp;selected
                </div>
            </div>
        </div>

        <div class="iat-bulk-action-status-area">
            <div id="iat-bulk-action-status" class="iat-bulk-action-status-msg hidden">
                <span class="dashicons dashicons-info"></span>
                <span class="iat-bulk-action-status-text"></span>
            </div>
        </div>
    </div>
</div>