<?php

function my_custom_header() 
{
        $args = array( 'height' => 800 );
        add_theme_support( 'custom-header', $args );
}

function my_pages_anchor( $url ) 
{
        return ( preg_match( '/#\w*$/', $url ) ? $url : $url . '#navbar' );
}

// flush_rules() if our rules are not yet included
function my_flush_rules()
{
    $rules = get_option( 'rewrite_rules' );

    #if ( ! isset( $rules['(player)-(\d*)$'] ) )
    #{
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    #}
}

// Adding a new rule page slug with number
function my_insert_rewrite_rules( $rules )
{
    $newrules = array();
    $newrules['(player)-(\d*)$'] = 'index.php?pagename=$matches[1]&player_id=$matches[2]';
    //combine all page rules
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
function my_the_content_filter( $content )
{
    if ( is_page( 'player' ) )
    {
        $player_id = intval( get_query_var( 'player_id', 0 ) );
        if ( $player_id > 0 ) {
            /**
             * Add user info only ( overwrite page content )
               $content = _player_info( $player_id );
             */

            /**
             * Include your page content and additional content of user info
             */
            $content .= _player_info( $player_id );
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
function _player_info( $player_id )
{
    ob_start();
    $user = get_user_by( 'id', $player_id );
    ?>

    <table>
        <tr>
            <td><?php _e( 'Name', 'text_domain' ); ?></td>
            <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
        </tr>
    </table>

    <?php
    $html = ob_get_clean();
    return $html;
}

add_action( 'after_setup_theme', 'my_custom_header' );
add_filter( 'page_link', 'my_pages_anchor' );
add_filter( 'page_rewrite_rules','my_insert_rewrite_rules' );
add_filter( 'query_vars','my_insert_query_vars' );
add_action( 'wp_loaded','my_flush_rules' );
add_filter( 'the_content', 'my_the_content_filter' );

?>

