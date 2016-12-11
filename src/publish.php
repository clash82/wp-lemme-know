<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

if (!defined('ABSPATH')) {
    exit;
}

$autoloaderPath = __DIR__.'/../vendor/autoload.php';
if (!file_exists($autoloaderPath)) {
    die (sprintf(
        'ERROR: file `%s` cannot be found. Did you install all dependencies using `composer install` command?',
        $autoloaderPath
    ));
}
require_once __DIR__.'/../vendor/autoload.php';

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

    $message = Swift_Message::newInstance($options->getOption('mail_title'))
        ->setFrom($options->getOption('mail_from'), $options->getOption('mail_from_name'))
        ->setReplyTo($options->getOption('mail_from'), $options->getOption('mail_from_name'))
        ->setReturnPath($options->getOption('mail_from'))
        ->setContentType('text/html');

    $transport = Swift_MailTransport::newInstance();
    if ($options->getOption('mailer_type') === 'smtp') {
        $transport = Swift_SmtpTransport::newInstance($options->getOption('smtp_host'), $options->getOption('smtp_port'))
            ->setUsername($options->getOption('smtp_user'))
            ->setPassword($options->getOption('smtp_pass'));

        if (!empty($options->getOption('smtp_auth_mode'))) {
            $transport->setAuthMode($options->getOption('smtp_auth_mode'));
        }

        if (!empty($options->getOption('smtp_port'))) {
            $transport->setPort($options->getOption('smtp_port'));
        }
    }

    $mailer = Swift_Mailer::newInstance($transport);

    foreach ($subscribers as $item) {
        $message->setBody(wp_lemme_know_parse_body(
            $options->getOption('mail_body'),
            $post,
            $item['hash'])
        );
        $message->setTo($item['email'], $item['email']);

        try {
            $mailer->send($message);
        } catch (Swift_TransportException $e) {
            // do nothing
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
