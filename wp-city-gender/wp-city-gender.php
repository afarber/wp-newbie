<?php
/*
Plugin Name: WP City Gender
Plugin URI: https://github.com/afarber/wp-newbie
Description: WordPress plugin to display user city and gender
Version: 1.0
Author: Alexander Farber
Author URI: http://afarber.de
License: GPL2
*/

if(!class_exists('WP_City_Gender'))
{
	class WP_City_Gender
	{
		public function __construct()
		{
		}

		public static function activate()
		{
		}

		public static function deactivate()
		{
		}

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=wp-city-gender">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
	}
}

if(class_exists('WP_City_Gender'))
{
	register_activation_hook(__FILE__, array('WP_City_Gender', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_City_Gender', 'deactivate'));

	//$wp_city_gender = new WP_City_Gender();
}
