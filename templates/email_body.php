<?= __('New post has just been published:'); ?>
<br><br>

<a href="{{post_url}}" target="_blank">{{post_title}}</a>
<br>
<p>{{post_excerpt}}</p>
<br><br>

<a href="{{post_url}}" target="_blank">Read more &raquo;</a>
<br><br>

<?= sprintf(__("If you don't want to receive messages like this in the future, please %s."),
    sprintf('<a href="{{unsubscribe_url}}" target="_blank">%s</a>', __('unsubscribe'))); ?>
