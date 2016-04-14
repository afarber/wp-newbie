<?php

function my_custom_header() {
        $args = array('height' => 600);
        add_theme_support('custom-header', $args);
}

add_action('after_setup_theme', 'my_custom_header');

function my_pages_anchor($url) {
        return $url . '#navbar';
}

add_filter('page_link', 'my_pages_anchor');

?>
