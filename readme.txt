=== WP Slider Captcha ===
Contributors: captbrogers
Donate link:
Tags: comments, anti-spam, jquery ui, slider
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 1.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

== Description ==

An anti-spam captcha using jQuery's UI slider. After the position of the slider is past 60% (default), it will enable the submit button. This will replace the input button specified as the submit button, if JavaScript is not enabled the button will show as expected.

== Installation ==

1. Upload the 'wp-slider-captcha' folder to your wp-content/plugins directory
2. In your admin panel, under the 'plugins' section activate the plugin
3. If you want a different threshold, check the WP Slider Captcha page under your settings

You're all done.

== Frequently Asked Questions ==

= Is there any way to extend the plugin with my options? =
Yes you can! You can set the threshold and if you have a custom form id you can specify that

= I found a problem/bug, can I email you directly? =
Yes, if you find a problem please email me so I can work on it

= Are you open to suggestions about how you could improve your code/plugin? =
Very much so if it is constructive, and is intended to help.

== Screenshots ==

1. Implemented in the default theme.

== Changelog ==

= 1.2.0 =
Added options page

= 1.1.0 =
Updated methods for checking that jQuery and jQuery UI have loaded and attempts to load them if not.

Changed method of inserting into DOM, reducing number of inserts.

Added options page in the administration panel.

Added ability to override CSS styles by selecting your own CSS file.

Tested with version 3.4.1

= 1.0.1 =
Tested with Wordpress version 3.4, worked as expect.

= 0.1 =
Very basic usage, shouldn't have any problems.
