<?php
/**
 * Plugin Name: WP Notification Settings
 * Description: Allows enabling and disabling various email notifications, such as automatic updates for plugins or themes, in single-site or multisite environments.
 * Version: 1.5.8
 * Requires at least: 3.7
 * Requires PHP: 5.6
 * Author: Mikka | zzzooo Studio
 * Author URI: https://zzzooo.studio/
 * Network: true
 * Update URI: https://cdn.zzzooo.studio/wp-plugins/wp-notification-settings/
 */

// Direktzugriff verhindern
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Optionen registrieren
function notification_settings_register_settings() {
    if ( is_multisite() ) {
        add_site_option( 'enable_plugin_update_email', 1 );
        add_site_option( 'enable_theme_update_email', 1 );
        add_site_option( 'enable_core_update_email', 1 ); // Neue Option
    } else {
        add_option( 'enable_plugin_update_email', 1 );
        add_option( 'enable_theme_update_email', 1 );
        add_option( 'enable_core_update_email', 1 ); // Neue Option
    }
}
add_action( 'admin_init', 'notification_settings_register_settings' );

// Mehrsprachigkeit
function wp_notification_settings_load_textdomain() {
    load_plugin_textdomain( 'wp-notification-settings', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wp_notification_settings_load_textdomain' );


// Menü hinzufügen (für Einzel- und Multisite)
function notification_settings_menu() {
    if ( is_multisite() ) {
        // Multisite: Menü in Netzwerk-Einstellungen
        add_submenu_page(
            'settings.php', // Elternmenü
            __( 'Notification Settings', 'wp-notification-settings' ), // Seitentitel
            __( 'Notifications', 'wp-notification-settings' ),         // Menüname
            'manage_network_options',                                 // Berechtigung
            'wp-notification-settings',                              // Menü-Slug
            'notification_settings_page'                             // Callback-Funktion
        );
    } else {
        // Einzelinstallation: Menü in Einstellungen
        add_options_page(
            __( 'Notification Settings', 'wp-notification-settings' ), // Seitentitel
            __( 'Notifications', 'wp-notification-settings' ),         // Menüname
            'manage_options',                                         // Berechtigung
            'wp-notification-settings',                              // Menü-Slug
            'notification_settings_page'                             // Callback-Funktion
        );
    }
}
add_action( 'admin_menu', 'notification_settings_menu' );
add_action( 'network_admin_menu', 'notification_settings_menu' ); // Für Multisite

// Admin-Seiteninhalt (einheitlich für beide Umgebungen)
function notification_settings_page() {
    if ( is_multisite() ) {
        // Netzwerkweite Optionen abrufen
        $plugin_update_email = get_site_option( 'enable_plugin_update_email' );
        $theme_update_email  = get_site_option( 'enable_theme_update_email' );
				$core_update_email   = get_site_option( 'enable_core_update_email' );
    } else {
        // Lokale Optionen abrufen
        $plugin_update_email = get_option( 'enable_plugin_update_email' );
        $theme_update_email  = get_option( 'enable_theme_update_email' );
				$core_update_email   = get_option( 'enable_core_update_email' );
    }

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    		check_admin_referer( 'save_notification_settings' );
		
    		$plugin_update_email = isset( $_POST['enable_plugin_update_email'] ) ? 1 : 0;
    		$theme_update_email  = isset( $_POST['enable_theme_update_email'] ) ? 1 : 0;
    		$core_update_email   = isset( $_POST['enable_core_update_email'] ) ? 1 : 0; // Neue Option
		
    		if ( is_multisite() ) {
        		update_site_option( 'enable_plugin_update_email', $plugin_update_email );
        		update_site_option( 'enable_theme_update_email', $theme_update_email );
        		update_site_option( 'enable_core_update_email', $core_update_email ); // Neue Option
    		} else {
        		update_option( 'enable_plugin_update_email', $plugin_update_email );
        		update_option( 'enable_theme_update_email', $theme_update_email );
        		update_option( 'enable_core_update_email', $core_update_email ); // Neue Option
    		}
		
    		echo '<div class="updated"><p>Einstellungen gespeichert.</p></div>';
		}

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Notification Settings', 'wp-notification-settings' ); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field( 'save_notification_settings' ); ?>
						<h2><?php esc_html_e( 'WordPress-Core-Updates', 'wp-notification-settings' ); ?></h2>
    				<table class="form-table">
        				<tr valign="top">
            				<th scope="row"><?php esc_html_e( 'Update notification', 'wp-notification-settings' ); ?></th>
            				<td>
                				<label class="switch">
                    				<input type="checkbox" name="enable_core_update_email" value="1" <?php checked( 1, $core_update_email, true ); ?> />
                    				<span class="slider round"></span>
                				</label>
            				</td>
        				</tr>
    				</table>
						<h2><?php esc_html_e( 'Plugin Updates', 'wp-notification-settings' ); ?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Update notification', 'wp-notification-settings' ); ?></th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="enable_plugin_update_email" value="1" <?php checked( 1, $plugin_update_email, true ); ?> />
                            <span class="slider round"></span>
                        </label>
                    </td>
                </tr>
						</table>
						<h2><?php esc_html_e( 'Theme Updates', 'wp-notification-settings' ); ?></h2>
						<table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Update notification', 'wp-notification-settings' ); ?></th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="enable_theme_update_email" value="1" <?php checked( 1, $theme_update_email, true ); ?> />
                            <span class="slider round"></span>
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #4caf50;
        }
        input:checked + .slider:before {
            transform: translateX(14px);
        }
    </style>
    <?php
}

// Filter anwenden
function notification_settings_apply_filters() {
    if ( is_multisite() ) {
        $plugin_update_email = get_site_option( 'enable_plugin_update_email' );
        $theme_update_email  = get_site_option( 'enable_theme_update_email' );
        $core_update_email   = get_site_option( 'enable_core_update_email' ); // Neue Option
    } else {
        $plugin_update_email = get_option( 'enable_plugin_update_email' );
        $theme_update_email  = get_option( 'enable_theme_update_email' );
        $core_update_email   = get_option( 'enable_core_update_email' ); // Neue Option
    }

    if ( ! $plugin_update_email ) {
        add_filter( 'auto_plugin_update_send_email', '__return_false' );
    }

    if ( ! $theme_update_email ) {
        add_filter( 'auto_theme_update_send_email', '__return_false' );
    }

    if ( ! $core_update_email ) {
        add_filter( 'auto_core_update_send_email', '__return_false' ); // Neue Option
    }
}
add_action( 'init', 'notification_settings_apply_filters' );

// ---- UPDATE CHECK ----

function check_for_custom_plugin_update($transient) {
    if ( empty($transient->checked) ) {
        return $transient;
    }

    $request_url = 'https://cdn.zzzooo.studio/wp-plugins/wp-notification-settings/update.json';
    $response = wp_remote_get($request_url);

    if (is_wp_error($response)) {
        return $transient;
    }

    $plugin_data = json_decode(wp_remote_retrieve_body($response), true);
    if (! $plugin_data) {
        return $transient;
    }

    $plugin_file = 'wp-notification-settings/wp-notification-settings.php';

    if (
        isset($plugin_data['version']) &&
        version_compare($plugin_data['version'], $transient->checked[$plugin_file], '>')
    ) {
        $transient->response[$plugin_file] = (object) [
            'slug'        => 'wp-notification-settings',
            'plugin'      => $plugin_file,
            'new_version' => $plugin_data['version'],
            'url'         => $plugin_data['url'],
            'package'     => $plugin_data['download_url'],
            'tested'      => $plugin_data['tested'] ?? '',
            'requires'    => $plugin_data['requires'] ?? '',
            'requires_php'=> '5.6',
        ];
    }

    return $transient;
}
add_filter('site_transient_update_plugins', 'check_for_custom_plugin_update');

// ---- UPDATE DETAILS POPUP (Plugin-Information) ----

add_filter('plugins_api', function($result, $action, $args) {

    if ($action !== 'plugin_information' || $args->slug !== 'wp-notification-settings') {
        return $result;
    }

    $request_url = 'https://cdn.zzzooo.studio/wp-plugins/wp-notification-settings/update.json';
    $response = wp_remote_get($request_url);

    if (is_wp_error($response)) {
        return $result;
    }

    $plugin_data = json_decode(wp_remote_retrieve_body($response), true);
    if (! $plugin_data) {
        return $result;
    }

    return (object) [
        'name'         => $plugin_data['name'],
        'slug'         => $plugin_data['slug'],
        'version'      => $plugin_data['version'],
        'author'       => '<a href="https://zzzooo.studio/">Mikka | zzzooo Studio</a>',
        'homepage'     => $plugin_data['url'],
        'download_link'=> $plugin_data['download_url'],
        'requires'     => $plugin_data['requires'],
        'tested'       => $plugin_data['tested'],
        'sections'     => [
            'description' => 'Allows enabling and disabling WordPress update notification emails.',
            'changelog'   => nl2br($plugin_data['changelog'] ?? 'No changelog available.')
        ],
    ];

}, 10, 3);

// ---- OPTIONAL: AUTOMATISCHE UPDATES ERLAUBEN ----
// ohne diesen Block muss der Nutzer manuell updaten

add_filter( 'auto_update_plugin', function( $update, $item ) {
    if ( isset($item->slug) && $item->slug === 'wp-notification-settings' ) {
        return true;
    }
    return $update;
}, 10, 2);
