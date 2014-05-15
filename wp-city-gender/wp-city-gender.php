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
define('CITY', 'city');
define('GENDER', 'gender');
define('MALE', 'male');
define('FEMALE', 'female');

if (!class_exists('WP_City_Gender')) {

	class WP_City_Gender {

		public function __construct() {
		}

		public static function activate() {
		}

		public static function deactivate() {
		}

                public static function fix($str) {
                        return (isset($str) && strlen($str) > 1 ? $str : NULL);
                }

                public static function register_form() {
                        $first_name = self::fix($_POST[FIRST_NAME]);
                        $last_name  = self::fix($_POST[LAST_NAME]);
                        $city       = self::fix($_POST[CITY]);
                        $gender     = self::fix($_POST[GENDER]);

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

                                <label for="city"><?php _e('City', 'wp-city-gender') ?><br />
                                <input type="text" name="city" id="city" class="input" 
                                value="<?php echo esc_attr(stripslashes($city)); ?>" size="25" />
                                </label>

                                <?php _e('Gender', 'wp-city-gender') ?>: 
                                <label><input type="radio" name="gender" value="<?= MALE ?>"
                                <?php print($gender != FEMALE ? 'checked' : ''); ?>/>
                                <?php _e('Male', 'wp-city-gender') ?></label>
                                <label><input type="radio" name="gender" value="<?= FEMALE ?>"
                                <?php print($gender == FEMALE ? 'checked' : ''); ?>/>
                                <?php _e('Female', 'wp-city-gender') ?></label>
                                <br />
                                </p>
                        <?php
                }

                public static function registration_errors($errors, $sanitized_user_login, $user_email) {
                        $first_name = self::fix($_POST[FIRST_NAME]);
                        $last_name  = self::fix($_POST[LAST_NAME]);
                        $city       = self::fix($_POST[CITY]);
                        $gender     = self::fix($_POST[GENDER]);

                        if (!$first_name)
                                $errors->add('first_name_error', __('<strong>ERROR</strong>: First name missing.','wp-city-gender'));
                        if (!$last_name)
                                $errors->add('last_name_error', __('<strong>ERROR</strong>: Last name missing.','wp-city-gender'));
                        if (!$city)
                                $errors->add('city_error', __('<strong>ERROR</strong>: City missing.','wp-city-gender'));
                        if ($gender != MALE && $gender != FEMALE)
                                $errors->add('gender_error', __('<strong>ERROR</strong>: Gender missing.','wp-city-gender'));

                        return $errors;
                }

                public static function user_register($user_id) {
                        $first_name = self::fix($_POST[FIRST_NAME]);
                        $last_name  = self::fix($_POST[LAST_NAME]);
                        $city       = self::fix($_POST[CITY]);
                        $gender     = self::fix($_POST[GENDER]);

                        if ($first_name && 
                            $last_name && 
                            $city && 
                            ($gender == MALE || $gender == FEMALE)) {
                                update_user_meta($user_id, FIRST_NAME, $first_name);
                                update_user_meta($user_id, LAST_NAME, $last_name);
                                update_user_meta($user_id, CITY, $city);
                                update_user_meta($user_id, GENDER, $gender);
                        }
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
