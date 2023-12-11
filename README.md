# Tarea de Plugin

## Enunciado:

Realiza un plugin en WP que utilice el hook 'the_content' (fijaros en los comentarios, 
hay mucha información muy interesante):

El plugin tiene que recorrer el contenido del post (esto ya lo hace el hook) y sustituir 
cinco palabras (pueden ser, por ejemplo, malsonantes) por otras. Utiliza array o 
algín tipo de dato parecido en PHP.

Ten en cuenta que este plugin vamos a seguir trabajando en él para que utilice la base de 
datos de WP y también que se pueda configurar desde el backend. Por lo tanto, estructura 
bien el código, separa los datos de la parte lógica, realiza funciones sencillas que 
realizen una sola cosa. De esta manera podremos ir evolucinando el código.

Adjunta el repositorio con únicamente el directorio del plugin, con un Readme explicatorio.

Se tiene que poder clonar el repositorio directamente en la carpeta plugin y activarlo desde 
cualquier instalación de WP. 

## Explicación:

1. Primero activamos el plugin:

![Activar plugin](./img/activarplugin.png)

2. Una vez activado el plugin, debemos crear una nueva entrada



![1Entrada](./img/Entrada_sin_cambiar.png)

3. Una vez creada la entrada, le damos a ver la entrada y veremos como nuestro texto cambia

![2Entrada](./img/Entrada.png)

        Como curiosidad podemos ver que al tener cambiada la palabra 'puta' por la palabra 'no se dice eso', cuando abajo de todo ponemos 
        'hijo de puta'  nos cambiará la palabra 'puta' por 'no se dice eso' y quedaría 'hijo de no se dice eso'.

## Pensaba que ya se había acabado, pero no, hay más:

    Por lo que se ve el profe quiere seguir trabajando en este proyecto así que a seguir. 

# Tengo problema con esta tarea y no sé porqué no funciona como debería, lo iré actualizando a medida que vaya avanzando en el proyecto. Intentaré que me ayude algun compañero de clase.e

## Actualizacion día 11/12/23

He conseguido que funcione el plugin, asi es como me ha quedado el codigo

```php
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

```

### Por aquí dejamos esta tarea, ha sido un placer trabajar en este proyecto, nos vemos en la próxima.

`Cristian Moreira 2DAM`