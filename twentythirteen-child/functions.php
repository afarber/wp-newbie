<?php

function my_enqueue_scripts() 
{
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    if ( is_front_page() )
    {
        wp_enqueue_script( 'board-script', get_stylesheet_directory_uri() . '/board.js' );
    }
    elseif ( is_page( 'player' ) )
    {
        wp_enqueue_style( 'datatables-style', 'https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css' );
        wp_enqueue_script( 'datatables-script', 'https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js' );
        wp_enqueue_script( 'google-script', 'https://www.google.com/jsapi?autoload={%27modules%27:[{%27name%27:%27visualization%27,%27version%27:%271%27,%27packages%27:[%27corechart%27],%27language%27:%27ru%27}]}' );
    }
}

function my_custom_header() 
{
    $args = array( 'height' => 800 );
    add_theme_support( 'custom-header', $args );
}

// flush_rules() if our rules are not yet included
function my_flush_rules()
{
    $rules = get_option( 'rewrite_rules' );

    if ( ! isset( $rules['(player)-(\d+)$'] ) )
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
}

// Adding a new rule page slug with number
function my_insert_rewrite_rules( $rules )
{
    $newrules = array();
    $newrules['(player)-(\d+)$'] = 'index.php?pagename=$matches[1]&player_id=$matches[2]';
    return $newrules + $rules;
}

// Adding the player_id var so that WP recognizes it
function my_insert_query_vars( $vars )
{
    array_push( $vars, 'player_id' );
    return $vars;
}

/**
 * Filter page content
 * by filter the_content
 * Use condition statement is_page( slug ) with get_query_var( 'player_id' )
 * 
 * @param  string $content Oringinal text
 * @return string New Content with or without original text
 */
function my_content_filter( $content )
{
    if ( is_page( 'player' ) )
    {
        $player_id = intval( get_query_var( 'player_id', 0 ) );
        if ( $player_id > 0 ) {
            /**
             * Add user info only ( overwrite page content )
               $content = player_info( $player_id );
             */

            /**
             * Include your page content and additional content of user info
             */
            $content .= player_info( $player_id );
        }
    }

    return $content;
}

/**
 * Set your user data, html, table, etc
 * @see param https://developer.wordpress.org/reference/functions/get_user_by/
 * @param  integer $player_id User ID
 * @return string User data front-end
 */
function player_info( $player_id )
{
    ob_start();
    $user = get_user_by( 'id', $player_id );
    ?>

    <table cellpadding="8">
        <tr>
            <td><?php _e( 'Name', 'text_domain' ); ?></td>
            <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
        </tr>
    </table>

    <?php
    $html = ob_get_clean();
    return $html;
}

function my_nav_menu_items( $items, $args ) 
{
    //error_log(print_r($args, TRUE));

    // only append profile link to the primary menu

    // TODO: check the valid cookie too

    if( $args->theme_location != 'primary' ) 
    {
        return $items;
    }

    $profile = sprintf('<li class="menu-item menu-item-type-custom menu-item-object-custom %s"><a href="/player-%d/#navbar">Профиль</a></li>', 
        ( is_page( 'player' ) ? 'current-menu-item' : '' ),
        1);
    return $items . $profile;
}

add_filter( 'wp_nav_menu_items', 'my_nav_menu_items', 10, 2 );
add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts' );
add_action( 'after_setup_theme', 'my_custom_header' );
add_filter( 'page_rewrite_rules','my_insert_rewrite_rules' );
add_filter( 'query_vars','my_insert_query_vars' );
add_action( 'wp_loaded','my_flush_rules' );
add_filter( 'the_content', 'my_content_filter' );

?>

