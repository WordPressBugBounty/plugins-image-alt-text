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

// Alt text coverage widget
$iat_stats   = function_exists('iat_get_alt_coverage_stats') ? iat_get_alt_coverage_stats() : array('total' => 0, 'with' => 0, 'without' => 0, 'percent' => 0);
$iat_percent = (int) $iat_stats['percent'];
$iat_level   = $iat_percent >= 90 ? 'high' : ($iat_percent >= 50 ? 'mid' : 'low');
?>
<style>
    .iat-coverage-widget { background:#fff; border:1px solid #e2e4e7; border-radius:10px; padding:16px 20px; margin:16px 0; box-shadow:0 1px 2px rgba(0,0,0,.04); }
    .iat-coverage-top { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:8px; }
    .iat-coverage-label { font-size:13px; font-weight:600; color:#1d2327; text-transform:uppercase; letter-spacing:.04em; }
    .iat-coverage-percent { font-size:22px; font-weight:700; color:#1d2327; line-height:1; }
    .iat-coverage-bar { height:10px; background:#f0f0f1; border-radius:999px; overflow:hidden; }
    .iat-coverage-fill { height:100%; border-radius:999px; transition:width .4s ease; }
    .iat-coverage-low .iat-coverage-fill { background:#d63638; }
    .iat-coverage-mid .iat-coverage-fill { background:#dba617; }
    .iat-coverage-high .iat-coverage-fill { background:#00a32a; }
    .iat-coverage-low .iat-coverage-percent { color:#d63638; }
    .iat-coverage-mid .iat-coverage-percent { color:#bd8600; }
    .iat-coverage-high .iat-coverage-percent { color:#00a32a; }
    .iat-coverage-counts { display:flex; gap:18px; margin-top:10px; flex-wrap:wrap; }
    .iat-coverage-count { font-size:13px; color:#50575e; }
    .iat-coverage-count strong { color:#1d2327; }
    .iat-coverage-empty { color:#50575e; font-size:14px; display:flex; align-items:center; gap:8px; }
</style>
<div class="iat-coverage-widget iat-coverage-<?php echo esc_attr($iat_level); ?>">
    <?php if ($iat_stats['total'] > 0) : ?>
        <div class="iat-coverage-top">
            <span class="iat-coverage-label"><?php esc_html_e('Alt text coverage', 'image-alt-text'); ?></span>
            <span class="iat-coverage-percent"><?php echo esc_html($iat_percent . '%'); ?></span>
        </div>
        <div class="iat-coverage-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($iat_percent); ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?php esc_attr_e('Alt text coverage', 'image-alt-text'); ?>">
            <div class="iat-coverage-fill" style="width:<?php echo esc_attr($iat_percent); ?>%;"></div>
        </div>
        <div class="iat-coverage-counts">
            <span class="iat-coverage-count"><strong><?php echo esc_html(number_format_i18n($iat_stats['with'])); ?></strong> <?php esc_html_e('with alt', 'image-alt-text'); ?></span>
            <span class="iat-coverage-count"><strong><?php echo esc_html(number_format_i18n($iat_stats['without'])); ?></strong> <?php esc_html_e('missing', 'image-alt-text'); ?></span>
            <span class="iat-coverage-count"><strong><?php echo esc_html(number_format_i18n($iat_stats['total'])); ?></strong> <?php esc_html_e('total images', 'image-alt-text'); ?></span>
        </div>
    <?php else : ?>
        <div class="iat-coverage-empty">
            <span class="dashicons dashicons-format-image"></span>
            <?php esc_html_e('No images found in your media library yet.', 'image-alt-text'); ?>
        </div>
    <?php endif; ?>
</div>
<?php

// Get current page with proper sanitization
$current_page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

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