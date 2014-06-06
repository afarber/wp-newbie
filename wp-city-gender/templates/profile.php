<?php
 /* Template Name: Public User Profile */
 ?>

<?php get_header(); ?>

<?php 

$user_id = absint(get_query_var('user_id'));  
$user    = get_user_by('id', $user_id);

if ($user instanceof WP_User) {
    printf("<p>Name:   %s</p>\n", $user->display_name);
    printf("<p>City:   %s</p>\n", get_user_meta($user->ID, 'city',   TRUE));
    printf("<p>Gender: %s</p>\n", get_user_meta($user->ID, 'gender', TRUE));
} else {
    _e('User not found');
}

?>

<?php get_footer(); ?>

