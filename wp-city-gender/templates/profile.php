<?php
 /* Template Name: Public User Profile */
 ?>

<?php get_header(); ?>

<div>
<?php 

$user_id = absint(get_query_var('user_id'));  
$user    = get_user_by('id', $user_id);

if ($user):
    printf('<p>Name:   %s</p>', $user->display_name);
    printf('<p>City:   %s</p>', get_user_meta($user->ID, 'city',   TRUE));
    printf('<p>Gender: %s</p>', get_user_meta($user->ID, 'gender', TRUE));
else:
    _e('User not found');
endif;

?>
</div>

<?php get_footer(); ?>

