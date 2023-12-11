<?php
/**
 * @package Palabra Censurada
 * @version 1.0.0
 */
/*
Plugin Name: Palabra Censurada
Plugin URI: http://wordpress.org/plugins/Palabra-Censurada/
Description: Este plugin censura las palabras malsonantes en WordPress.
Author: Cristian
Version: 1.0.1
Author URI: http://ma.tt/
*/

function inicioPlugin(){
    createTable();
    insertData();
}

/**
 * Carga tabla wp_dam con las palabras malsonantes
 */
function createTable(){
    global $wpdb;
    $table_name = 'wp_dam';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        tabu varchar(255) NOT NULL,
        eufemismo varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Inserta o actualiza datos en la tabla
 */
function insertData() {
    global $wpdb;
    $table_name = 'wp_dam';
    $data = array(
        array('tabu' => 'puta', 'eufemismo' => 'guapa'),
        array('tabu' => 'puto', 'eufemismo' => 'guapo'),
        array('tabu' => 'coño', 'eufemismo' => 'xoxo'),
        array('tabu' => 'maricon', 'eufemismo' => 'homo'),
        array('tabu' => 'hijo de', 'eufemismo' => 'tu madre es')
    );

    foreach ($data as $row) {
        $existing_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE tabu = %s", $row['tabu']));

        if ($existing_row) {
            // Si ya existe, actualiza el eufemismo
            $wpdb->update($table_name, array('eufemismo' => $row['eufemismo']), array('id' => $existing_row->id));
        } else {
            // Si no existe, inserta un nuevo registro
            $wpdb->insert($table_name, $row);
        }
    }
}

function renym_wordpress_typo_fix($text) {
    global $wpdb;
    $table_name = 'wp_dam';
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );

    foreach ($results as $row) {
        $text = str_replace($row->tabu, $row->eufemismo, $text);
    }

    return $text;
}

add_action('plugins_loaded', 'inicioPlugin');
add_filter('the_content', 'renym_wordpress_typo_fix');

