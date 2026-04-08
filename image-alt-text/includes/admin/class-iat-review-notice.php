<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles the review reminder notice shown on plugin pages.
 * Shows after 7 days, dismissible permanently or snooze for 14 days.
 */
class Iat_Review_Notice
{
    private $review_url    = 'https://wordpress.org/support/plugin/image-alt-text/reviews/#new-post';
    private $days_to_wait  = 7;
    private $snooze_days   = 14;

    public function __construct()
    {
        add_action('wp_ajax_iat_review_remind_later', array($this, 'iat_review_remind_later'));
        add_action('wp_ajax_iat_review_dismiss',      array($this, 'iat_review_dismiss'));
        add_action('iat_render_review_notice',        array($this, 'render'));
    }

    /**
     * Save install date. Called on plugin activation.
     */
    public static function iat_set_install_date()
    {
        if (!get_option('iat_install_date')) {
            update_option('iat_install_date', time(), false);
        }
    }

    /**
     * Whether the notice should be shown right now.
     */
    public function should_show()
    {
        // Dismissed permanently
        if (get_option('iat_review_dismissed') === 'yes') {
            return false;
        }

        // Snoozed via "Remind me later"
        if (get_transient('iat_review_remind_later')) {
            return false;
        }

        $install_date = (int) get_option('iat_install_date');

        // No install date recorded — this is an existing install (plugin was already
        // active before tracking was added). Treat as past the threshold and show now.
        if (!$install_date) {
            update_option('iat_install_date', time() - ($this->days_to_wait * DAY_IN_SECONDS), false);
            return true;
        }

        return (time() - $install_date) >= ($this->days_to_wait * DAY_IN_SECONDS);
    }

    /**
     * Render the notice HTML. Call this inside a plugin page view.
     */
    public function render()
    {
        if (!$this->should_show()) {
            return;
        }

        $nonce = wp_create_nonce('iat_review_notice_nonce');
        ?>
        <div class="iat-review-notice" id="iat-review-notice">
            <span class="iat-review-notice-icon">&#9733;</span>
            <p>
                <?php
                printf(
                    /* translators: %s: plugin name in bold */
                    esc_html__('If you\'ve found our WordPress plugin %s helpful, we would greatly appreciate it if you could take a moment to leave us a review. Your feedback helps us improve our plugin and also lets other users know the value of our product. Thank you for taking the time to share your thoughts!', 'image-alt-text'),
                    '<strong>' . esc_html__('Image Alt Text', 'image-alt-text') . '</strong>'
                );
                ?>
            </p>
            <div class="iat-review-notice-actions">
                <a href="<?php echo esc_url($this->review_url); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="iat-review-btn iat-review-btn-primary"
                   id="iat-review-btn-review"
                   data-nonce="<?php echo esc_attr($nonce); ?>">
                    <?php esc_html_e('Review', 'image-alt-text'); ?>
                </a>
                <button type="button"
                        class="iat-review-btn iat-review-btn-secondary"
                        id="iat-review-btn-remind"
                        data-nonce="<?php echo esc_attr($nonce); ?>">
                    <?php esc_html_e('Remind me later', 'image-alt-text'); ?>
                </button>
                <button type="button"
                        class="iat-review-btn iat-review-btn-muted"
                        id="iat-review-btn-dismiss"
                        data-nonce="<?php echo esc_attr($nonce); ?>">
                    <?php esc_html_e('Do not show again', 'image-alt-text'); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX: snooze the notice for 14 days.
     */
    public function iat_review_remind_later()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'iat_review_notice_nonce')) {
            wp_send_json_error();
        }
        if (!current_user_can('manage_options')) {
            wp_send_json_error();
        }
        set_transient('iat_review_remind_later', '1', $this->snooze_days * DAY_IN_SECONDS);
        wp_send_json_success();
    }

    /**
     * AJAX: dismiss the notice permanently.
     */
    public function iat_review_dismiss()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'iat_review_notice_nonce')) {
            wp_send_json_error();
        }
        if (!current_user_can('manage_options')) {
            wp_send_json_error();
        }
        update_option('iat_review_dismissed', 'yes', false);
        wp_send_json_success();
    }
}

new Iat_Review_Notice();
