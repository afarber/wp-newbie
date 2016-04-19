<?php
        $PROVIDERS = array(
                'google'        => 0,
                'odnoklassniki' => 1,
                'mailru'        => 2,
                'vkontakte'     => 3,
                'facebook'      => 4,
                'twitter'       => 5,
        );

        function words_get_users($user_id) {
                global $wpdb;

                $sql = '
SELECT 
        firstname AS given, 
        lastname AS last, 
        LOWER(gender) AS female, 
        photourl AS photo, 
        LOWER(provider) AS social, 
        identifier AS sid, 
        city 
FROM ' . $wpdb->prefix . 'wslusersprofiles 
WHERE user_id = %d';

                return $wpdb->get_results($wpdb->prepare($sql, $user_id));
        }

        get_header();
?>

<div id="main" class="site-main">
<pre>
        
<?php
        $current_user = wp_get_current_user();
        if ($current_user->exists()) {
                #print_r($current_user);
                #printf("ID: %s\n", $current_user->ID);
                #printf("firstname: %s\n", $current_user->user_firstname);
                #printf("lastname: %s\n", $current_user->user_lastname);

                $users = words_get_users($current_user->ID);
                if (!empty($users)) {
                        $info = array();
                        foreach($users as $user) {
                                #print_r($user);
                                array_push($info, array(
                                        'sid'    => $user->sid,
                                        'auth'   => 'TODO',
                                        'stamp'  => 'TODO',
                                        'social' => $PROVIDERS[$user->social],
                                        'given'  => $user->given,
                                        'last'   => $user->last,
                                        'photo'  => $user->photo,
                                        'city'   => $user->city,
                                        'female' => ($user->female == 'female' ? 1 : 0),
                                ));
                        }

                        printf("info: %s\n", json_encode($info));
                }
        }
?>

</pre>

<canvas id="board" width="300" height="300"></canvas>

</div>

<?php
        get_sidebar();
        get_footer();
?>

