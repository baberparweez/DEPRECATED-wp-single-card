<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/baberparweez
 * @since             1.0.0
 * @package           Single_Card
 *
 * @wordpress-plugin
 * Plugin Name:       Single Card
 * Plugin URI:        https://github.com/baberparweez
 * Description:       Allows a single masonry-type card to be added which can pull in pages or posts or allow custom data.
 * Version:           1.0.0
 * Author:            Baber Parweez
 * Author URI:        https://github.com/baberparweez
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       single-card
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLUGIN_NAME_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-single-card-activator.php
 */
function activate_single_card()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-single-card-activator.php';
	Single_Card_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-single-card-deactivator.php
 */
function deactivate_single_card()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-single-card-deactivator.php';
	Single_Card_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_single_card');
register_deactivation_hook(__FILE__, 'deactivate_single_card');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-single-card.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_single_card()
{

	$plugin = new Single_Card();
	$plugin->run();
}
run_single_card();
if (!function_exists('dropdown_multi_settings_field')) {
	require plugin_dir_path(__FILE__) . 'public/partials/multiple-dropdown.php';
}
