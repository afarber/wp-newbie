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

                public static function check($str) {
                        return (isset($str) && strlen($str) > 0 ? $str : NULL);
                }

                public static function register_form() {
                        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
                        ?>
                        <p>
                        <label for="first_name">First Name<br />
                        <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(stripslashes($first_name)); ?>" size="25" />
                        </label>
                        </p>
                        <?php
                }

                public static function registration_errors($errors, $sanitized_user_login, $user_email) {
                        if (empty($_POST['first_name']))
                                $errors->add('first_name_error', '<strong>ERROR</strong>: You must include a first name.');
                        return $errors;
                }

                public static function user_register ($user_id) {
                        if (isset($_POST['first_name']))
                                update_user_meta($user_id, 'first_name', $_POST['first_name']);
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

	//$wp_city_gender = new WP_City_Gender();
}
