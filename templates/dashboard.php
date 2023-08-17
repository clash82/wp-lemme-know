<?= sprintf(__('Total count of subscriptions: %d'), $settings['email_count']);
print('<br /><br /><div style="height: 200px; overflow-y: scroll;"><table style="width: 100%;"><thead style="font-weight: bold;"><tr><th>Email address</th><th>Since</td></tr></thead>');
foreach ($settings['subscribers'] as $subscriber) {
    printf('<tr><td>%s</td><td><time datetime="%s">%s</time></td></tr>', $subscriber['email'], mysql2date('c', $subscriber['date']), mysql2date(get_option('date_format'), $subscriber['date']));
}
print('</table></div>');
?>
