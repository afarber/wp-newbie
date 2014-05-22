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
define('LAST_NAME',  'last_name');
define('CITY',       'city');
define('GENDER',     'gender');
define('MALE',       'male');
define('FEMALE',     'female');
define('DOMAIN',     'wp-city-gender');
define('CNAME',      'WP_City_Gender');
define('SECRET',     'my little secret');
define('PROFILE',    '<a href="/user/%d" rel="external nofollow" class="url">%d</a>');

if (!class_exists(CNAME)) {

	class WP_City_Gender {

		public function __construct() {
		}

		public static function init() {
                        load_plugin_textdomain(DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');

                        $user = wp_get_current_user(); 
                        if (!$user instanceof WP_User) 
                                return; 

                        $year = time() + 60 * 60 * 24 * 30 * 12;
                        $auth = md5(join('_', array($user->ID, SECRET)));

                        setcookie('visitor_id', $user->ID, $year, '/');
                        setcookie('visitor_auth', $auth, $year, '/');
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
        <label for="first_name"><?php _e('First Name', DOMAIN) ?><br />
        <input type="text" name="first_name" id="first_name" class="input" 
        value="<?php echo esc_attr(stripslashes($first_name)); ?>" size="25" />
        </label>

        <label for="last_name"><?php _e('Last Name', DOMAIN) ?><br />
        <input type="text" name="last_name" id="last_name" class="input" 
        value="<?php echo esc_attr(stripslashes($last_name)); ?>" size="25" />
        </label>

        <label for="city"><?php _e('City', DOMAIN) ?><br />
        <input type="text" name="city" id="city" class="input" 
        value="<?php echo esc_attr(stripslashes($city)); ?>" size="25" />
        </label>

        <?php _e('Gender', DOMAIN) ?>: 
        <label><input type="radio" name="gender" value="<?= MALE ?>"
        <?php print($gender != FEMALE ? 'checked' : ''); ?>/>
        <?php _e('Male', DOMAIN) ?></label>

        <label><input type="radio" name="gender" value="<?= FEMALE ?>"
        <?php print($gender == FEMALE ? 'checked' : ''); ?>/>
        <?php _e('Female', DOMAIN) ?></label>
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
                                $errors->add('first_name_error', __('<strong>ERROR</strong>: First name missing.', DOMAIN));
                        if (!$last_name)
                                $errors->add('last_name_error', __('<strong>ERROR</strong>: Last name missing.', DOMAIN));
                        if (!$city)
                                $errors->add('city_error', __('<strong>ERROR</strong>: City missing.', DOMAIN));
                        if ($gender != MALE && $gender != FEMALE)
                                $errors->add('gender_error', __('<strong>ERROR</strong>: Gender missing.', DOMAIN));

                        return $errors;
                }

                public static function user_register($uid) {
                        $first_name = self::fix($_POST[FIRST_NAME]);
                        $last_name  = self::fix($_POST[LAST_NAME]);
                        $city       = self::fix($_POST[CITY]);
                        $gender     = self::fix($_POST[GENDER]);

                        if ($first_name && 
                            $last_name && 
                            $city && 
                            ($gender == MALE || $gender == FEMALE)) {
                                update_user_meta($uid, FIRST_NAME, $first_name);
                                update_user_meta($uid, LAST_NAME, $last_name);
                                update_user_meta($uid, CITY, $city);
                                update_user_meta($uid, GENDER, $gender);
                        }
                }

                public static function show_profile($user) {
                        $city   = self::fix(get_user_meta($user->ID, CITY, TRUE));
                        $gender = self::fix(get_user_meta($user->ID, GENDER, TRUE));

                        ?>
<table class="form-table">
<tr>
        <th>
                <label for="city"><?php _e('City', DOMAIN) ?></label>
        </th>
        <td>
                <input type="text" name="city" id="city" class="input" 
                value="<?php echo esc_attr(stripslashes($city)); ?>" size="25" />
        </td>
</tr>
<tr>
        <th>
                <?php _e('Gender', DOMAIN) ?> 
        </th>
        <td>
                <label><input type="radio" name="gender" value="<?= MALE ?>"
                <?php print($gender != FEMALE ? 'checked' : ''); ?>/>
                <?php _e('Male', DOMAIN) ?></label>

                <label><input type="radio" name="gender" value="<?= FEMALE ?>"
                <?php print($gender == FEMALE ? 'checked' : ''); ?>/>
                <?php _e('Female', DOMAIN) ?></label>
        </td>
</tr>
</table>
                        <?php
                }

                public static function update_profile($uid) {
                        if (current_user_can('edit_user', $uid)) {
                                $city   = self::fix($_POST[CITY]);
                                $gender = self::fix($_POST[GENDER]);

                                if ($city &&
                                    ($gender == MALE || $gender == FEMALE)) {
                                        update_user_meta($uid, CITY, $city);
                                        update_user_meta($uid, GENDER, $gender);
                                }
                        }
                }

                public static function get_comment_author_link($url) {
                        global $comment;
                        $uid = $comment->user_id;
                        //error_log('UID: <' . $uid . '>');
                        return sprintf(PROFILE, $uid, $uid);
                }
        }
}

if (class_exists(CNAME)) {

	register_activation_hook(__FILE__,     array(CNAME, 'activate'));
	register_deactivation_hook(__FILE__,   array(CNAME, 'deactivate'));

        add_action('init',                     array(CNAME, 'init'));
        add_action('register_form',            array(CNAME, 'register_form'));
        add_action('registration_errors',      array(CNAME, 'registration_errors'), 10, 3);
        add_action('user_register',            array(CNAME, 'user_register'));
        add_action('show_user_profile',        array(CNAME, 'show_profile'));
        add_action('edit_user_profile',        array(CNAME, 'show_profile'));
        add_action('personal_options_update',  array(CNAME, 'update_profile'));
        add_action('edit_user_profile_update', array(CNAME, 'update_profile'));
        add_action('get_comment_author_link',  array(CNAME, 'get_comment_author_link'));

        remove_action('shutdown', 'wp_ob_end_flush_all', 1);

	//$wp_city_gender = new WP_City_Gender();
}
