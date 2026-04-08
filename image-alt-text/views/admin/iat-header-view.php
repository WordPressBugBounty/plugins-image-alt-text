<?php
if (!defined('ABSPATH')) {
    exit;
}
$current = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
$tabs = array(
    'iat-with-alt-text'    => __('With Alt Text', 'image-alt-text'),
    'iat-without-alt-text' => __('Without Alt Text', 'image-alt-text'),
);
$plugin_version = defined('IAT_PLUGIN_VERSION') ? IAT_PLUGIN_VERSION : '';
?>
<div class="iat-header-container">
    <div class="iat-header-inner">
        <div class="iat-brand-left">
            <a href="<?php echo esc_url(admin_url('admin.php?page=iat-with-alt-text')); ?>" class="iat-brand-link" aria-label="<?php esc_attr_e('Image Alt Text dashboard', 'image-alt-text'); ?>">
                <img src="<?php echo esc_url(IAT_PLUGIN_URL . 'assets/images/image-alt-text-logo.png'); ?>" alt="<?php esc_attr_e('Image Alt Text', 'image-alt-text'); ?>" class="iat-brand-logo">
            </a>
        </div>
        <nav class="iat-nav" role="navigation" aria-label="<?php esc_attr_e('Image Alt Text navigation', 'image-alt-text'); ?>">
            <ul class="iat-nav-list">
                <?php foreach ($tabs as $tab_id => $tab_data): ?>
                    <li class="iat-nav-item">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=' . $tab_id)); ?>" class="iat-nav-link <?php echo ($current === $tab_id) ? 'active' : ''; ?>">
                            <?php echo esc_html($tab_data); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="iat-nav-item iat-nav-item-cta">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=iat-get-pro')); ?>" target="_blank" class="iat-nav-link iat-get-pro-btn <?php echo ($current === 'iat-get-pro') ? 'active' : ''; ?>">
                        <span class="iat-get-pro-icon">⚡</span>
                        <?php echo esc_html__('Get Pro', 'image-alt-text'); ?>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="iat-header-meta" aria-hidden="false">
            <?php if (!empty($plugin_version)): ?>
                <span class="iat-plugin-version"><?php echo esc_html(sprintf('v%s', $plugin_version)); ?></span>
            <?php endif; ?>
        </div>
        <a class="iat-media-hygiene-pill" href="https://wordpress.org/plugins/media-hygiene/" target="_blank">
            <span class="iat-mh-icon dashicons dashicons-trash"></span>
            <span class="iat-mh-text">
                <strong><?php esc_html_e('Media Hygiene', 'image-alt-text'); ?></strong>
                <span><?php esc_html_e('Remove unused media files', 'image-alt-text'); ?></span>
            </span>
            <span class="iat-mh-cta"><?php esc_html_e('Try Free →', 'image-alt-text'); ?></span>
        </a>
    </div>
</div>