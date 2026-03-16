<?php

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Remove all transients with our prefix
global $wpdb;
$like1 = $wpdb->esc_like('_transient_iat_') . '%';
$like2 = $wpdb->esc_like('_transient_timeout_iat_') . '%';
$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s", $like1));
$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s", $like2));
