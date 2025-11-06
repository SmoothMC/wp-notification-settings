=== WP Notification Settings ===
Contributors: zzzooostudio
Donate link: https://zzzooo.studio/
Tags: email, notifications, updates, multisite, core updates, plugin updates, theme updates
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.2
Stable tag: 1.5.8
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author: Mikka | zzzooo Studio
Author URI: https://zzzooo.studio/

Allows enabling and disabling automatic email notifications for WordPress core, plugin, and theme updates — works in single-site and multisite environments.

== Description ==

**WP Notification Settings** lets you control which email notifications WordPress sends after performing automatic updates.

You can enable or disable email notifications for:
* WordPress Core updates
* Plugin updates
* Theme updates

### Multisite Support
If activated network-wide, the settings appear under **Network Admin → Settings → Notifications** and apply across all subsites.

### Benefits
* Keeps inboxes clean
* Full control over WordPress update emails
* Lightweight — no ads, no tracking, no external dependencies

== Screenshots ==

1. Settings page with toggle switches.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/wp-notification-settings/`
2. Activate through **Plugins**
3. Go to **Settings → Notifications** (or **Network Settings → Notifications** in Multisite)

== Frequently Asked Questions ==

= Does this disable the updates themselves? =
No — only the *emails* WordPress sends after updates.

= Does this work with Multisite? =
Yes — and provides a centralized network settings page.

= Does the plugin send any data externally? =
No. No telemetry. No tracking.

== Changelog ==

= 1.5.5 =
* Added support for disabling WordPress Core update emails
* Improved UI for multisite
* General code cleanup

= 1.5.0 =
* Initial stable release

== Upgrade Notice ==

= 1.5.5 =
New toggle added to control core update emails.
