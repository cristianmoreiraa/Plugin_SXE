<?php
/**
 * @package Cristian
 * @version 1.0.0
 */
/*
Plugin Name: Popi Plugin
Plugin URI: http://wordpress.org/plugins/cristian-words/
Description: This plugin is a test for the WordPress plugin development course.
Author: Popi
Version: 0.0.1
Author URI: MiPrima
*/

# list of offensive words
$offensiveWordsList = [
    "puta",
    "puto",
    "maricon",
    "coño",
    "hijo de puta"
];
$nonOffensiveWordsList = [
    "no se dice eso",
    "pene",
    "homosexual",
    "xoxo",
    "guapo"
];

/**
 * Whenever the word WordPress appears in the content
 * of a post or a page,
 * it will be replaced by WordPressDAM.
 * @param $text string
 * @return string
 */
function renym_wordpress_typo_fix( $text ) {
    global $offensiveWordsList, $nonOffensiveWordsList;
    return str_replace( $offensiveWordsList, $nonOffensiveWordsList, $text );
}

add_filter('the_content', 'renym_wordpress_typo_fix');
