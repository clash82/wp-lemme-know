<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('transition_post_status', 'wp_lemme_know_publish_callback', 10, 3);

function wp_lemme_know_publish_callback($newStatus, $oldStatus, $post)
{
    if ('publish' !== $newStatus || 'publish' === $oldStatus) {
        return;
    }

    wp_lemme_know_send(
        wp_lemme_know_get_subscribers(),
        $post
    );
}

/**
 * Sends e-mails.
 *
 * @param string[] $subscribers
 * @param WP_Post $post
 */
function wp_lemme_know_send($subscribers, $post)
{
    $options = WP_LemmeKnowDefaults::getInstance();

    if (empty($options->getOption('mail_from'))) {
        return;
    }

    // temporary disable max_execution_time (doesn't work if PHP is running in safe-mode)
    ini_set('max_execution_time', 0);

    require_once ABSPATH.'wp-includes/class-phpmailer.php';
    $mailer = new PHPMailer(true);

    if ($options->getOption('mailer_type') === 'smtp') {
        require_once ABSPATH.'wp-includes/class-smtp.php';

        $mailer->isSMTP();
        $mailer->SMTPAutoTLS = false;
        $mailer->SMTPAuth = true;
        $mailer->Host = $options->getOption('smtp_host');
        $mailer->Port = $options->getOption('smtp_port');
        $mailer->Username = $options->getOption('smtp_user');
        $mailer->Password = $options->getOption('smtp_pass');
        $mailer->SMTPSecure = $options->getOption('smtp_encryption');
        $mailer->AuthType = $options->getOption('smtp_auth_mode');

        // additional settings for PHP 5.6
        $mailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];
    }

    $mailer->setFrom($options->getOption('mail_from'), $options->getOption('mail_from_name'));
    $mailer->isHTML(true);
    $mailer->Subject = $options->getOption('mail_title');
    $mailer->CharSet = 'UTF-8';

    foreach ($subscribers as $item) {
        $mailer->clearAddresses();
        $mailer->clearReplyTos();

        $mailer->Body = wp_lemme_know_parse_body(
            $options->getOption('mail_body'),
            $post,
            $item['hash']
        );
        $mailer->addAddress($item['email'], $item['email']);
        $mailer->addReplyTo($item['email'], $item['email']);

        try {
            $mailer->send();
        } catch (Exception $e) {
            error_log(sprintf('wp-lemme-know error: %s', $e->getMessage()));
        }
    }
}

/**
 * Retrieves subscribers.
 *
 * @return array
 */
function wp_lemme_know_get_subscribers()
{
    global $wpdb;

    $tableName = $wpdb->prefix.'lemme_know_subscribers';

    $queryResults = $wpdb->get_results(sprintf(
        "SELECT `s_email`, `s_hash` FROM `%s` WHERE `s_confirmed`='yes'", $tableName
    ));

    $results = [];
    foreach ($queryResults as $subscriber) {
        $results[] = [
            'email' => $subscriber->s_email,
            'hash' => $subscriber->s_hash
        ];
    }

    return $results;
}

/**
 * Parses body template by injecting $post details.
 *
 * @param string $body
 * @param WP_Post $post
 * @param string $hash
 *
 * @return string
 */
function wp_lemme_know_parse_body($body, $post, $hash)
{
    return str_replace([
        '{{post_title}}',
        '{{post_body}}',
        '{{post_excerpt}}',
        '{{post_date}}',
        '{{post_author}}',
        '{{post_url}}',
        '{{unsubscribe_url}}',
    ], [
        $post->post_title,
        $post->post_content,
        $post->post_excerpt,
        $post->post_date,
        $post->post_author,
        get_permalink($post),
        sprintf('%s/lemme_know/unsubscribe/%s/', get_site_url(), $hash)
    ], $body);
}
