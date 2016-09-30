<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action(
    'wp_dashboard_setup',
    create_function('', 'return wp_add_dashboard_widget(
        "wp-lemme-know",
        "Lemme Know",
        "wp_lemme_know_dashboard_callback"
    );')
);

function wp_lemme_know_dashboard_callback()
{
    global $wpdb;

    $tableName = $wpdb->prefix . 'lemme_know_subscribers';
    $subscriberCount = $wpdb->get_var(sprintf('SELECT COUNT(*) FROM `%s`', $tableName));

    $settings = [
        'email_count' => $subscriberCount
    ];

    require_once __DIR__.'/../templates/dashboard.php';
}
