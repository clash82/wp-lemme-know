<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action(
    'wp_dashboard_setup',
    function() {
        wp_add_dashboard_widget(
            'wp-lemme-know',
            'Lemme Know',
            'wp_lemme_know_dashboard_callback'
        );
    }
);

function wp_lemme_know_dashboard_callback()
{
    global $wpdb;

    $tableName = $wpdb->prefix . 'lemme_know_subscribers';
    $subscriberCount = $wpdb->get_var(sprintf('SELECT COUNT(*) FROM `%s`', $tableName));

    $subscribers = $wpdb->get_results(sprintf("SELECT `s_email`, `s_date` FROM `%s` WHERE `s_confirmed`='yes' ORDER BY `s_date` DESC", $tableName));
    $results = [];
    foreach ($subscribers as $subscriber) {
        $results[] = [
            'email' => esc_html($subscriber->s_email),
            'date' => $subscriber->s_date
        ];
    }

    $settings = [
        'email_count' => $subscriberCount,
        'subscribers' => $results
    ];

    require_once __DIR__.'/../templates/dashboard.php';
}
