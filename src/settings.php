<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action(
    'admin_menu',
    create_function('', 'return add_options_page(
        "Lemme Know",
        "Lemme Know",
        "manage_options",
        "wp-lemme-know",
        "wp_lemme_know_options_page"
    );')
);

function wp_lemme_know_options_page() {
    require_once __DIR__.'/../templates/settings.php';
}

add_action(
    'admin_init',
    'wp_lemme_know_admin_init'
);

function wp_lemme_know_admin_init()
{
    register_setting(
        'wp_lemme_know_options',
        'wp_lemme_know_options',
        'wp_lemme_know_validate_callback'
    );

    // general
    add_settings_section(
        'wp_lemme_know_options_general',
        __('General settings', 'wp-lemme-know'),
        'wp_lemme_know_general_callback',
        'wp_lemme_know_plugin'
    );
    add_settings_field(
        'styling',
        __('Styling the widget', 'wp-lemme-know'),
        'wp_lemme_know_styling_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_general'
    );

    // mail
    add_settings_section(
        'wp_lemme_know_options_mail',
        __('Mail settings', 'wp-lemme-know'),
        'wp_lemme_know_mail_callback',
        'wp_lemme_know_plugin'
    );
    add_settings_field(
        'mail_title',
        __('E-mail title', 'wp-lemme-know'),
        'wp_lemme_know_mail_title_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_mail'
    );
    add_settings_field(
        'mail_from',
        __('E-mail from address', 'wp-lemme-know'),
        'wp_lemme_know_mail_from_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_mail'
    );
    add_settings_field(
        'mail_from_name',
        __('E-mail from name', 'wp-lemme-know'),
        'wp_lemme_know_mail_from_name_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_mail'
    );
    add_settings_field(
        'mail_body',
        __('E-mail body (html)', 'wp-lemme-know'),
        'wp_lemme_know_mail_body_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_mail'
    );
    add_settings_field(
        'mailer',
        __('Mailer type', 'wp-lemme-know'),
        'wp_lemme_know_mailer_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_mail'
    );

    // SMTP
    add_settings_section(
        'wp_lemme_know_options_smtp',
        __('SMTP settings', 'wp-lemme-know'),
        'wp_lemme_know_smtp_callback',
        'wp_lemme_know_plugin'
    );
    add_settings_field(
        'smtp_host',
        __('Hostname', 'wp-lemme-know'),
        'wp_lemme_know_smtp_host_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_smtp'
    );
    add_settings_field(
        'smtp_port',
        __('Port number', 'wp-lemme-know'),
        'wp_lemme_know_smtp_port_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_smtp'
    );
    add_settings_field(
        'smtp_auth_mode',
        __('Authentication', 'wp-lemme-know'),
        'wp_lemme_know_smtp_auth_mode_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_smtp'
    );
    add_settings_field(
        'smtp_encryption',
        __('Encryption', 'wp-lemme-know'),
        'wp_lemme_know_smtp_encryption_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_smtp'
    );
    add_settings_field(
        'smtp_user',
        __('Username', 'wp-lemme-know'),
        'wp_lemme_know_smtp_user_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_smtp'
    );
    add_settings_field(
        'smtp_pass',
        __('Password', 'wp-lemme-know'),
        'wp_lemme_know_smtp_pass_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_smtp'
    );

    // notifications
    add_settings_section(
        'wp_lemme_know_options_notifications',
        __('Notifications', 'wp-lemme-know'),
        'wp_lemme_know_notifications_callback',
        'wp_lemme_know_plugin'
    );
    add_settings_field(
        'mail_notify',
        __('New subscriptions', 'wp-lemme-know'),
        'wp_lemme_know_mail_notify_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_notifications'
    );
    add_settings_field(
        'mail_unsubscribe_notify',
        __('Unsubscribe', 'wp-lemme-know'),
        'wp_lemme_know_mail_unsubscribe_notify_callback',
        'wp_lemme_know_plugin',
        'wp_lemme_know_options_notifications'
    );
};

function wp_lemme_know_validate_callback($input)
{
    return $input;
}

function wp_lemme_know_general_callback()
{
    printf(
        '<p>%s</p>',
        __('Those settings have impact on all created widgets.', 'wp-lemme-know')
    );
}

function wp_lemme_know_styling_callback()
{
    printf(
        '<label for="wp-lemme-know-options-embed-css"><input type="checkbox" id="wp-lemme-know-options-embed-css" name="wp_lemme_know_options[embed_css]" value="1" %s /> %s</label>',
        checked(1, WP_LemmeKnowDefaults::getInstance()->getOption('embed_css'), false),
        __('Embed default CSS provided with this plugin (disable if you want to style the widgets by yourself)')
    );
}

function wp_lemme_know_mail_callback()
{
    printf(
        '<p>%s</p>',
        __('Essential settings required for sending e-mail notifications.', 'wp-lemme-know')
    );
}

function wp_lemme_know_mail_title_callback()
{
    printf(
        '<input type="text" name="wp_lemme_know_options[mail_title]" value="%s" class="regular-text ltr" /><p class="description">%s</p>',
        WP_LemmeKnowDefaults::getInstance()->getOption('mail_title'),
        __('text will be used as a title for e-mail notifications')
    );
}

function wp_lemme_know_mail_from_callback()
{
    printf(
        '<input type="text" name="wp_lemme_know_options[mail_from]" value="%s" class="regular-text ltr" /><p class="description">%s</p>',
        WP_LemmeKnowDefaults::getInstance()->getOption('mail_from'),
        __('if empty then no messages will be sent (useful if you want to temporary disable e-mail sending)')
    );
}

function wp_lemme_know_mail_from_name_callback()
{
    printf(
        '<input type="text" name="wp_lemme_know_options[mail_from_name]" value="%s" class="regular-text ltr" />',
        WP_LemmeKnowDefaults::getInstance()->getOption('mail_from_name')
    );
}

function wp_lemme_know_mail_body_callback()
{
    printf(
        '<textarea name="wp_lemme_know_options[mail_body]" class="large-text" rows="10" cols="50">%s</textarea><p class="description">%s</p>',
        WP_LemmeKnowDefaults::getInstance()->getOption('mail_body'),
        __('available short codes are: {{post_title}}, {{post_body}}, {{post_excerpt}}, {{post_date}}, {{post_author}}, {{post_url}} and {{unsubscribe_url}}')
    );
}

function wp_lemme_know_mailer_callback()
{
    printf(
        '<label for="wp-lemme-know-options-mailer-default"><input type="radio" id="wp-lemme-know-options-mailer-default" name="wp_lemme_know_options[mailer_type]" value="default" %s /> %s</label>',
        checked('default', WP_LemmeKnowDefaults::getInstance()->getOption('mailer_type'), false),
        __('Use built-in mail() function')
    );

    echo '<br />';

    printf(
        '<label for="wp-lemme-know-options-mailer-smtp"><input type="radio" id="wp-lemme-know-options-mailer-smtp" name="wp_lemme_know_options[mailer_type]" value="smtp" %s /> %s</label><p class="description">%s</p>',
        checked('smtp', WP_LemmeKnowDefaults::getInstance()->getOption('mailer_type'), false),
        __('Use external SMTP server'),
        __('recommended but requires additional SMTP parameters described below')
    );
}

function wp_lemme_know_smtp_callback()
{
    printf(
        '<p>%s</p>',
        __('Additional parameters required for using external SMTP server.', 'wp-lemme-know')
    );
}


function wp_lemme_know_smtp_host_callback()
{
    printf(
        '<input type="text" name="wp_lemme_know_options[smtp_host]" value="%s" class="regular-text ltr" /><p class="description">%s</p>',
        WP_LemmeKnowDefaults::getInstance()->getOption('smtp_host'),
        __('eg. mail.example.com')
    );
}

function wp_lemme_know_smtp_port_callback()
{
    printf(
        '<input type="number" name="wp_lemme_know_options[smtp_port]" value="%s" class="regular-text ltr" /><p class="description">%s</p>',
        WP_LemmeKnowDefaults::getInstance()->getOption('smtp_port'),
        __('eg. 25, 587 (TLS) or 467 (SSL)')
    );
}

function wp_lemme_know_smtp_auth_mode_callback()
{
    printf('<select name="wp_lemme_know_options[smtp_auth_mode]"><option value="" %s>%s</option>><option value="plain" %s>%s</option><option value="login" %s>%s</option><option value="cram-md5" %s>%s</option></select>',
        selected(WP_LemmeKnowDefaults::getInstance()->getOption('smtp_auth_mode'), '', false),
        __('none'),
        selected(WP_LemmeKnowDefaults::getInstance()->getOption('smtp_auth_mode'), 'plain', false),
        'plain',
        selected(WP_LemmeKnowDefaults::getInstance()->getOption('smtp_auth_mode'), 'login', false),
        'login',
        selected(WP_LemmeKnowDefaults::getInstance()->getOption('smtp_auth_mode'), 'cram-md5', false),
        'cram-md5'
    );
}

function wp_lemme_know_smtp_encryption_callback()
{
    printf('<select name="wp_lemme_know_options[smtp_encryption]"><option value="" %s>%s</option>><option value="tls" %s>%s</option><option value="ssl" %s>%s</option></select>',
        selected(WP_LemmeKnowDefaults::getInstance()->getOption('smtp_encryption'), '', false),
        __('none'),
        selected(WP_LemmeKnowDefaults::getInstance()->getOption('smtp_encryption'), 'tls', false),
        'TLS',
        selected(WP_LemmeKnowDefaults::getInstance()->getOption('smtp_encryption'), 'ssl', false),
        'SSL'
    );
}

function wp_lemme_know_smtp_user_callback()
{
    printf(
        '<input type="text" name="wp_lemme_know_options[smtp_user]" value="%s" class="regular-text ltr" />',
        WP_LemmeKnowDefaults::getInstance()->getOption('smtp_user')
    );
}

function wp_lemme_know_smtp_pass_callback()
{
    printf(
        '<input type="password" name="wp_lemme_know_options[smtp_pass]" value="%s" class="regular-text ltr" />',
        WP_LemmeKnowDefaults::getInstance()->getOption('smtp_pass')
    );
}

function wp_lemme_know_notifications_callback()
{
    printf(
        '<p>%s</p>',
        __('Internal e-mail notifications.', 'wp-lemme-know')
    );
}

function wp_lemme_know_mail_notify_callback()
{
    printf(
        '<label for="wp-lemme-know-options-notifications-subscribe"><input type="checkbox" id="wp-lemme-know-options-notifications-subscribe" name="wp_lemme_know_options[notifications_subscribe]" value="1" %s /> %s</label>',
        checked(1, WP_LemmeKnowDefaults::getInstance()->getOption('notifications_subscribe'), false),
        __('Notify Administrator about the new subscriptions')
    );
}

function wp_lemme_know_mail_unsubscribe_notify_callback()
{
    printf(
        '<label for="wp-lemme-know-options-notifications-unsubscribe"><input type="checkbox" id="wp-lemme-know-options-notifications-unsubscribe" name="wp_lemme_know_options[notifications_unsubscribe]" value="1" %s /> %s</label>',
        checked(1, WP_LemmeKnowDefaults::getInstance()->getOption('notifications_unsubscribe'), false),
        __('Notify Administrator when user unsubscribe')
    );
}
