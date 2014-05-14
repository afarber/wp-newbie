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

define('FIRST_NAME', 'first_name');
define('LAST_NAME', 'last_name');
define('GENDER', 'gender');
define('CITY', 'city');

if (!class_exists('WP_City_Gender')) {

	class WP_City_Gender {

		public function __construct() {
		}

		public static function activate() {
		}

		public static function deactivate() {
		}

		// Add the settings link to the plugins page
		function plugin_settings_link($links) {
			$settings_link = '<a href="options-general.php?page=wp-city-gender">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

                public static function valid($str) {
                        return (isset($str) && strlen($str) > 0);
                }

                public static function fix($str) {
                        return (WP_City_Gender::valid($str) ? $str : '');
                }

                public static function register_form() {
                        $first_name = WP_City_Gender::fix($_POST[FIRST_NAME]);
                        $last_name  = WP_City_Gender::fix($_POST[LAST_NAME]);
                        $gender     = WP_City_Gender::fix($_POST[GENDER]);
                        $city       = WP_City_Gender::fix($_POST[CITY]);
                        ?>
                        <p>
                        <label for="first_name"><?php _e('First Name', 'wp-city-gender') ?><br />
                        <input type="text" name="first_name" id="first_name" class="input" 
                        value="<?php echo esc_attr(stripslashes($first_name)); ?>" size="25" />
                        </label>
                        <label for="last_name"><?php _e('Last Name', 'wp-city-gender') ?><br />
                        <input type="text" name="last_name" id="last_name" class="input" 
                        value="<?php echo esc_attr(stripslashes($last_name)); ?>" size="25" />
                        </label>
                        <label for="gender"><?php _e('Gender', 'wp-city-gender') ?><br />
                        <input type="text" name="gender" id="gender" class="input" 
                        value="<?php echo esc_attr(stripslashes($gender)); ?>" size="25" />
                        </label>
                        <label for="city"><?php _e('City', 'wp-city-gender') ?><br />
                        <input type="text" name="city" id="city" class="input" 
                        value="<?php echo esc_attr(stripslashes($city)); ?>" size="25" />
                        </label>
                        </p>
                        <?php
                }

                public static function registration_errors($errors, $sanitized_user_login, $user_email) {
                        if (!WP_City_Gender::valid($_POST[FIRST_NAME]))
                                $errors->add('first_name_error', __('<strong>ERROR</strong>: First name missing.','wp-city-gender'));
                        if (!WP_City_Gender::valid($_POST[LAST_NAME]))
                                $errors->add('last_name_error', __('<strong>ERROR</strong>: Last name missing.','wp-city-gender'));
                        if (!WP_City_Gender::valid($_POST[GENDER]))
                                $errors->add('gender_error', __('<strong>ERROR</strong>: Gender missing.','wp-city-gender'));
                        if (!WP_City_Gender::valid($_POST[CITY]))
                                $errors->add('city_error', __('<strong>ERROR</strong>: City missing.','wp-city-gender'));
                        return $errors;
                }

                public static function user_register($user_id) {
                        if (WP_City_Gender::valid($_POST[FIRST_NAME]))
                                update_user_meta($user_id, FIRST_NAME, $_POST[FIRST_NAME]);
                        if (WP_City_Gender::valid($_POST[LAST_NAME]))
                                update_user_meta($user_id, LAST_NAME, $_POST[LAST_NAME]);
                        if (WP_City_Gender::valid($_POST[GENDER]))
                                update_user_meta($user_id, GENDER, $_POST[GENDER]);
                        if (WP_City_Gender::valid($_POST[CITY]))
                                update_user_meta($user_id, CITY, $_POST[CITY]);
                }
        }
}

if (class_exists('WP_City_Gender')) {

	register_activation_hook(__FILE__, array('WP_City_Gender', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_City_Gender', 'deactivate'));

        //1. Add a new form element...
        add_action('register_form', array('WP_City_Gender', 'register_form'));

        //2. Add validation. In this case, we make sure first_name is required.
        add_action('registration_errors', array('WP_City_Gender', 'registration_errors'), 10, 3);

        //3. Finally, save our extra registration user meta.
        add_action('user_register', array('WP_City_Gender', 'user_register'));

        load_plugin_textdomain('wp-city-gender', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	//$wp_city_gender = new WP_City_Gender();
}
