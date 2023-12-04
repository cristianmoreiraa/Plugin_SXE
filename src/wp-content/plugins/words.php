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

function inicioPlugin() {
    createTable();
    insertData();
}

/**
 * Crea la tabla 'dam' en la base de datos
 */
function createTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dam';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        word varchar(255) NOT NULL,
        palabrota varchar(255) NOT NULL,
        eufemismo varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Inserta datos en la tabla 'dam'
 */
function insertData() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dam';
    $wpdb->insert(
        $table_name,
        array(
            'word' => 'puta',
            'palabrota' => 'no se dice eso',
            'eufemismo' => 'no se dice eso'
            // Agrega más filas según sea necesario
        )
    );
}

add_action('plugins_loaded', 'inicioPlugin');

/**
 * Filtra el contenido para reemplazar palabras ofensivas
 */
function renym_wordpress_typo_fix($text) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dam';
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    foreach ($results as $row) {
        $text = str_replace($row->word, $row->eufemismo, $text);
    }
    return $text;
}

add_filter('the_content', 'renym_wordpress_typo_fix');
