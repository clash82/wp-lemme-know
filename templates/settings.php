<div class="wrap">
    <h1><?= __('Lemme Know settings', 'wp-lemme-know'); ?></h1>
    <p><?= __('Notify users every time when new posts are published.', 'wp-lemme-know'); ?></p>

    <form action="options.php" method="post">
        <?php settings_fields('wp_lemme_know_options'); ?>
        <?php do_settings_sections('wp_lemme_know_plugin'); ?>

        <input name="submit" type="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
    </form>
</div>
