=== Lemme Know ===

Contributors: clash82
Tags: notifications, e-mail, newsletter
Requires at least: 4.6
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv2

Sends e-mail notification for all subscribers when a new post is published.

== Description ==

This plugin is currently in alpha stage. It includes only basic features like sending e-mail notifications using built-in PHP mail() function or by using SMTP server. Work of this plugin depends mostly on SMTP server configuration.

Lemme Know plugin will allows you to send e-mail notifications only for a small amount of subscribers. There are plans to implement Cron-based solution which will allows to send notifications in portions and bypass server limitations.

== Installation ==

* upload the `wp-lemme-know` directory to the `/wp-content/plugins/` directory
* activate the plugin through the 'Plugins' menu in WordPress
* go to `Settings > Lemme Know` and fill out required settings
* go to `Themes > Widgets` and add Lemme Know widget to the sidebar

== Todo list ==

* implement Cron-based feature allowing to send e-mails in portions
* add e-mail list management (add/edit/remove subscribers manually)
* add e-mail groups
* add double opt-in feature for e-mail subscriptions
* add translations

Feel invited to contribute if you can help make this plugin better :-)

Visit https://github.com/omniproject/wp-lemme-know, fork the project, add your feature and create a Pull Request. I'll be happy to review and add your changes.

== Changelog ==

= v0.1.0 =

* first alpha release

== Upgrade notice ==

* nothing for now :-)
